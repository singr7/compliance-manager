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

describe('assessments', () => {
  it('creating an assessment snapshots one assessmentResponse per template question', async () => {
    const token = await bootstrapAuditor();
    const templateId = await createActiveTemplate(token, 3);
    const { org } = await createCustomer(token);

    const res = await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId, organisationId: org._id });

    expect(res.status).toBe(201);
    expect(res.body.status).toBe('draft');
    expect(res.body.progress.total).toBe(3);
    expect(res.body.progress.counts.not_started).toBe(3);
  });

  it('rejects a duplicate active assessment for the same template + organisation', async () => {
    const token = await bootstrapAuditor();
    const templateId = await createActiveTemplate(token);
    const { org } = await createCustomer(token);

    const first = await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId, organisationId: org._id });
    expect(first.status).toBe(201);

    const second = await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId, organisationId: org._id });
    expect(second.status).toBe(409);
  });

  it('rejects creating an assessment against a non-active template', async () => {
    const token = await bootstrapAuditor();
    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'Draft Template' });
    const { org } = await createCustomer(token);

    const res = await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId: createRes.body._id, organisationId: org._id });
    expect(res.status).toBe(400);
  });

  it('a customer_user only sees assessments for their own organisation', async () => {
    const token = await bootstrapAuditor();
    const templateId = await createActiveTemplate(token);
    const { org: org1, token: customerToken } = await createCustomer(token, 'Org One');
    const { org: org2 } = await createCustomer(token, 'Org Two');

    await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId, organisationId: org1._id });
    await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${token}`)
      .send({ templateId, organisationId: org2._id });

    const res = await request(app)
      .get('/assessments')
      .set('Authorization', `Bearer ${customerToken}`);
    expect(res.status).toBe(200);
    expect(res.body).toHaveLength(1);
    expect(res.body[0].organisationId).toBe(org1._id);
  });

  it('a customer_user cannot create an assessment', async () => {
    const token = await bootstrapAuditor();
    const templateId = await createActiveTemplate(token);
    const { org, token: customerToken } = await createCustomer(token);

    const res = await request(app)
      .post('/assessments')
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ templateId, organisationId: org._id });
    expect(res.status).toBe(403);
  });
});
