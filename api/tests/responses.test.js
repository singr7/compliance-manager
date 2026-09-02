import { jest } from '@jest/globals';
import request from 'supertest';
import { createApp } from '../src/app.js';
import { startTestDb, stopTestDb, clearTestDb } from './setup.js';

jest.setTimeout(30000);

let app;

async function bootstrapAuditor() {
  const res = await request(app).post('/auth/bootstrap-admin').send({
    fullName: 'Ada Auditor',
    email: 'ada@firm.test',
    password: 'correct-horse-battery-staple',
  });
  return res.body.token;
}

async function createCustomer(auditorToken, orgName = 'ABC Manufacturing') {
  const orgRes = await request(app)
    .post('/organisations')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({ name: orgName });
  const org = orgRes.body;
  await request(app)
    .post('/users')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({
      fullName: 'Cara Customer',
      email: `cara-${org._id}@abc.test`,
      password: 'another-strong-password',
      role: 'customer_user',
      organisationId: org._id,
    });
  const loginRes = await request(app).post('/auth/login').send({
    email: `cara-${org._id}@abc.test`,
    password: 'another-strong-password',
  });
  return { org, token: loginRes.body.token };
}

async function createActiveTemplate(token, questionCount = 2) {
  const createRes = await request(app)
    .post('/templates')
    .set('Authorization', `Bearer ${token}`)
    .send({ name: 'PCI DSS v4.0 Assessment', category: 'Payment Security' });
  const templateId = createRes.body._id;

  const sectionRes = await request(app)
    .post(`/templates/${templateId}/sections`)
    .set('Authorization', `Bearer ${token}`)
    .send({ title: 'Network Security' });
  const sectionId = sectionRes.body._id;

  for (let i = 0; i < questionCount; i++) {
    await request(app)
      .post(`/templates/${templateId}/sections/${sectionId}/questions`)
      .set('Authorization', `Bearer ${token}`)
      .send({
        text: `Question ${i + 1}`,
        controlRef: `1.1.${i + 1}`,
        responseType: 'yes_no_na',
      });
  }

  await request(app)
    .patch(`/templates/${templateId}`)
    .set('Authorization', `Bearer ${token}`)
    .send({ status: 'active' });

  return templateId;
}

async function createAssessment(auditorToken, templateId, organisationId) {
  const res = await request(app)
    .post('/assessments')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({ templateId, organisationId });
  return res.body;
}

beforeAll(async () => {
  await startTestDb();
  app = createApp();
});

afterAll(async () => {
  await stopTestDb();
});

afterEach(async () => {
  await clearTestDb();
});

describe('assessment responses', () => {
  it('lists responses grouped by section and lets a customer save an answer', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken, 2);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    expect(listRes.status).toBe(200);
    expect(listRes.body).toHaveLength(2);

    const responseId = listRes.body[0]._id;
    const saveRes = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ answer: { type: 'yes_no_na', value: 'yes' } });

    expect(saveRes.status).toBe(200);
    expect(saveRes.body.status).toBe('in_progress');
    expect(saveRes.body.answer.value).toBe('yes');
  });

  it('blocks submit of a required question with no answer', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken, 1);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const submitRes = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ submit: true });

    expect(submitRes.status).toBe(400);

    const submitWithAnswer = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ answer: { type: 'yes_no_na', value: 'no' }, submit: true });

    expect(submitWithAnswer.status).toBe(200);
    expect(submitWithAnswer.body.status).toBe('submitted');
    expect(submitWithAnswer.body.submittedAt).toBeTruthy();
  });

  it('rejects a customer from another organisation reading or editing a response', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken, 1);
    const { org, token: customerToken } = await createCustomer(auditorToken, 'ABC Manufacturing');
    const { token: otherCustomerToken } = await createCustomer(auditorToken, 'Other Org');
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const forbiddenList = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${otherCustomerToken}`);
    expect(forbiddenList.status).toBe(403);

    const forbiddenPatch = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${otherCustomerToken}`)
      .send({ answer: { type: 'yes_no_na', value: 'yes' } });
    expect(forbiddenPatch.status).toBe(403);
  });
});
