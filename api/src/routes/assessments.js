import { Router } from 'express';
import { Assessment } from '../models/Assessment.js';
import { AssessmentResponse } from '../models/AssessmentResponse.js';
import { ChecklistTemplate } from '../models/ChecklistTemplate.js';
import { requireAuth, requireRole, resolveOrgScope } from '../middleware/auth.js';

export const assessmentsRouter = Router();

assessmentsRouter.use(requireAuth, resolveOrgScope);

async function withProgress(assessment) {
  const responses = await AssessmentResponse.find({ assessmentId: assessment._id });
  const counts = responses.reduce((acc, r) => {
    acc[r.status] = (acc[r.status] || 0) + 1;
    return acc;
  }, {});
  return {
    ...assessment.toObject(),
    progress: { total: responses.length, counts },
  };
}

assessmentsRouter.get('/', async (req, res) => {
  const filter = {};
  if (req.query.status) filter.status = req.query.status;
  if (req.scopedOrganisationId) {
    filter.organisationId = req.scopedOrganisationId;
  } else if (req.query.organisationId) {
    filter.organisationId = req.query.organisationId;
  }

  const assessments = await Assessment.find(filter).sort({ createdAt: -1 });
  const withCounts = await Promise.all(assessments.map(withProgress));
  res.json(withCounts);
});

assessmentsRouter.post('/', requireRole('auditor'), async (req, res) => {
  const { templateId, organisationId, assignedAuditorId, dueDate } = req.body || {};
  if (!templateId || !organisationId) {
    return res.status(400).json({ error: 'templateId and organisationId are required' });
  }

  const template = await ChecklistTemplate.findById(templateId);
  if (!template) return res.status(404).json({ error: 'Template not found' });
  if (template.status !== 'active') {
    return res.status(400).json({ error: 'Template must be active to create an assessment' });
  }

  const duplicate = await Assessment.findOne({
    templateId,
    organisationId,
    status: { $in: ['draft', 'active', 'under_review'] },
  });
  if (duplicate) {
    return res.status(409).json({ error: 'An active assessment already exists for this template and organisation' });
  }

  const assessment = await Assessment.create({
    templateId,
    organisationId,
    assignedAuditorId: assignedAuditorId || null,
    dueDate: dueDate || null,
    createdBy: req.auth.sub,
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

  res.status(201).json(await withProgress(assessment));
});

assessmentsRouter.get('/:id', async (req, res) => {
  const assessment = await Assessment.findById(req.params.id);
  if (!assessment) return res.status(404).json({ error: 'Assessment not found' });

  if (
    req.scopedOrganisationId &&
    assessment.organisationId.toString() !== req.scopedOrganisationId.toString()
  ) {
    return res.status(403).json({ error: 'Forbidden' });
  }

  res.json(await withProgress(assessment));
});

assessmentsRouter.patch('/:id', requireRole('auditor'), async (req, res) => {
  const assessment = await Assessment.findById(req.params.id);
  if (!assessment) return res.status(404).json({ error: 'Assessment not found' });

  const { status, dueDate } = req.body || {};
  const VALID_STATUSES = ['draft', 'active', 'under_review', 'completed'];
  if (status !== undefined) {
    if (!VALID_STATUSES.includes(status)) {
      return res.status(400).json({ error: `status must be one of ${VALID_STATUSES.join(', ')}` });
    }
    assessment.status = status;
    if (status === 'completed') assessment.completedAt = new Date();
  }
  if (dueDate !== undefined) assessment.dueDate = dueDate;

  await assessment.save();
  res.json(await withProgress(assessment));
});
