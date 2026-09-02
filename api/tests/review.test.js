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

async function createActiveTemplate(token) {
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

  await request(app)
    .post(`/templates/${templateId}/sections/${sectionId}/questions`)
    .set('Authorization', `Bearer ${token}`)
    .send({ text: 'Is a firewall configured?', controlRef: '1.1.1', responseType: 'yes_no_na' });

  await request(app)
    .patch(`/templates/${templateId}`)
    .set('Authorization', `Bearer ${token}`)
    .send({ status: 'active' });

  return templateId;
}

async function createSubmittedResponse(auditorToken, customerToken, templateId, organisationId) {
  const assessment = (
    await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ templateId, organisationId })
  ).body;

  const listRes = await request(app)
    .get(`/assessments/${assessment._id}/responses`)
    .set('Authorization', `Bearer ${customerToken}`);
  const responseId = listRes.body[0]._id;

  await request(app)
    .patch(`/assessments/${assessment._id}/responses/${responseId}`)
    .set('Authorization', `Bearer ${customerToken}`)
    .send({ answer: { type: 'yes_no_na', value: 'yes' }, submit: true });

  return { assessment, responseId };
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

describe('auditor review + clarification', () => {
  it('accepts a submitted response', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const { assessment, responseId } = await createSubmittedResponse(auditorToken, customerToken, templateId, org._id);

    const reviewRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'accept' });

    expect(reviewRes.status).toBe(200);
    expect(reviewRes.body.status).toBe('accepted');
    expect(reviewRes.body.reviewedAt).toBeTruthy();
  });

  it('requires a comment when requesting clarification, and posts it to the thread', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const { assessment, responseId } = await createSubmittedResponse(auditorToken, customerToken, templateId, org._id);

    const missingComment = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'needs_clarification' });
    expect(missingComment.status).toBe(400);

    const withComment = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'needs_clarification', comment: 'Please provide the firewall config export' });
    expect(withComment.status).toBe(200);
    expect(withComment.body.status).toBe('needs_clarification');

    const thread = await request(app)
      .get(`/assessments/${assessment._id}/responses/${responseId}/comments`)
      .set('Authorization', `Bearer ${customerToken}`);
    expect(thread.body).toHaveLength(1);
    expect(thread.body[0].text).toBe('Please provide the firewall config export');
    expect(thread.body[0].authorRole).toBe('auditor');
  });

  it('lets the customer resolve a clarification by resubmitting, then the auditor accepts', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const { assessment, responseId } = await createSubmittedResponse(auditorToken, customerToken, templateId, org._id);

    await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'needs_clarification', comment: 'More detail please' });

    await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/comments`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ text: 'Updated the answer with more detail' });

    const resubmit = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ answer: { type: 'yes_no_na', value: 'yes' }, submit: true });
    expect(resubmit.body.status).toBe('submitted');

    const accept = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'accept' });
    expect(accept.status).toBe(200);
    expect(accept.body.status).toBe('accepted');

    const thread = await request(app)
      .get(`/assessments/${assessment._id}/responses/${responseId}/comments`)
      .set('Authorization', `Bearer ${auditorToken}`);
    expect(thread.body).toHaveLength(2);
  });

  it('rejects reviewing a response that has not been submitted, and a customer_user reviewing', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = (
      await request(app)
        .post('/assessments')
        .set('Authorization', `Bearer ${auditorToken}`)
        .send({ templateId, organisationId: org._id })
    ).body;
    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const notSubmittedReview = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ decision: 'accept' });
    expect(notSubmittedReview.status).toBe(400);

    const customerReview = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/review`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ decision: 'accept' });
    expect(customerReview.status).toBe(403);
  });

  it('scopes comments to the owning organisation', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken, 'ABC Manufacturing');
    const { token: otherCustomerToken } = await createCustomer(auditorToken, 'Other Org');
    const { assessment, responseId } = await createSubmittedResponse(auditorToken, customerToken, templateId, org._id);

    const forbidden = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/comments`)
      .set('Authorization', `Bearer ${otherCustomerToken}`)
      .send({ text: 'sneaky' });
    expect(forbidden.status).toBe(403);
  });
});
