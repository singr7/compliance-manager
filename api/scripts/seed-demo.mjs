// Local/demo seed: the two reference templates (via src/services/referenceTemplates.js,
// idempotent — never overwrites a template that already exists) plus a demo organisation,
// customer login, and one assessment per template so there's something to click through
// immediately.
//
// Safe to re-run. Organisations are matched by name and reused rather than duplicated;
// the customer user's password is reset each run so the printed credentials always work.
//
// Usage: node scripts/seed-demo.mjs
// Reads MONGO_URI the same way the server does (env var, falling back to the local default).

import { connectDb, disconnectDb } from '../src/db.js';
import { ChecklistTemplate } from '../src/models/ChecklistTemplate.js';
import { Organisation } from '../src/models/Organisation.js';
import { User } from '../src/models/User.js';
import { Assessment } from '../src/models/Assessment.js';
import { AssessmentResponse } from '../src/models/AssessmentResponse.js';
import { hashPassword } from '../src/utils/password.js';
import { env } from '../src/config/env.js';
import { REFERENCE_TEMPLATES, seedReferenceTemplates } from '../src/services/referenceTemplates.js';

const DEMO_ORG_NAME = 'ABC Manufacturing (PCI DSS Demo)';
const DEMO_CUSTOMER_EMAIL = 'demo.customer@pcidss-demo.test';
const DEMO_CUSTOMER_PASSWORD = 'DemoCustomer123!';

async function findOrCreateOrg(name, auditorId) {
  let org = await Organisation.findOne({ name });
  if (!org) {
    org = await Organisation.create({ name, createdBy: auditorId });
    console.log(`Created organisation "${name}"`);
  } else {
    console.log(`Reusing existing organisation "${name}"`);
  }
  return org;
}

async function upsertCustomerUser(email, password, fullName, organisationId) {
  const passwordHash = await hashPassword(password);
  let user = await User.findOne({ email });
  if (user) {
    user.passwordHash = passwordHash;
    user.organisationId = organisationId;
    await user.save();
    console.log(`Reset password for existing customer user ${email}`);
  } else {
    user = await User.create({
      fullName,
      email,
      passwordHash,
      role: 'customer_user',
      organisationId,
    });
    console.log(`Created customer user ${email}`);
  }
  return user;
}

async function findOrCreateAssessment(templateId, organisationId, auditorId) {
  let assessment = await Assessment.findOne({
    templateId,
    organisationId,
    status: { $in: ['draft', 'active', 'under_review'] },
  });
  if (assessment) {
    console.log(`Reusing existing assessment ${assessment._id} for this template+org`);
    return assessment;
  }

  const template = await ChecklistTemplate.findById(templateId);
  assessment = await Assessment.create({
    templateId,
    organisationId,
    status: 'active',
    createdBy: auditorId,
  });

  const responseDocs = [];
  for (const section of template.sections) {
    for (const question of section.questions) {
      if (!question.enabled) continue;
      responseDocs.push({
        assessmentId: assessment._id,
        questionId: question._id,
        sectionId: section._id,
        questionTextSnapshot: question.text,
        controlRefSnapshot: question.controlRef || '',
        status: 'not_started',
      });
    }
  }
  if (responseDocs.length > 0) {
    await AssessmentResponse.insertMany(responseDocs);
  }
  console.log(`Created assessment ${assessment._id} with ${responseDocs.length} responses`);
  return assessment;
}

async function main() {
  await connectDb();
  console.log(`Connected to ${env.mongoUri}`);

  const auditor = await User.findOne({ role: 'auditor' });
  if (!auditor) {
    throw new Error('No auditor account exists yet. Log in once via /auth/bootstrap-admin first, then re-run this script.');
  }
  console.log(`Attributing new records to auditor ${auditor.email}`);

  const created = await seedReferenceTemplates(auditor._id);
  for (const t of created) {
    const total = t.sections.reduce((s, sec) => s + sec.questions.length, 0);
    console.log(`Created template "${t.name}" — ${t.sections.length} sections, ${total} questions`);
  }
  for (const def of REFERENCE_TEMPLATES) {
    if (created.some((t) => t.name === def.name)) continue;
    console.log(`Reusing existing template "${def.name}"`);
  }

  const templates = await ChecklistTemplate.find({ name: { $in: REFERENCE_TEMPLATES.map((t) => t.name) } });
  const legacyTemplate = templates.find((t) => t.name === REFERENCE_TEMPLATES[0].name);
  const pciTemplate = templates.find((t) => t.name === REFERENCE_TEMPLATES[1].name);

  const org = await findOrCreateOrg(DEMO_ORG_NAME, auditor._id);
  await upsertCustomerUser(DEMO_CUSTOMER_EMAIL, DEMO_CUSTOMER_PASSWORD, 'Demo Customer', org._id);

  await findOrCreateAssessment(legacyTemplate._id, org._id, auditor._id);
  await findOrCreateAssessment(pciTemplate._id, org._id, auditor._id);

  console.log('\nDemo seed complete.');
  console.log('  Customer login:', DEMO_CUSTOMER_EMAIL, '/', DEMO_CUSTOMER_PASSWORD);
  console.log('  Organisation:  ', DEMO_ORG_NAME);

  await disconnectDb();
}

main().catch((err) => {
  console.error('Seed failed:', err.message);
  process.exit(1);
});
