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

async function createCustomer(auditorToken) {
  const orgRes = await request(app)
    .post('/organisations')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({ name: 'ABC Manufacturing' });
  const org = orgRes.body;
  await request(app)
    .post('/users')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({
      fullName: 'Cara Customer',
      email: 'cara@abc.test',
      password: 'another-strong-password',
      role: 'customer_user',
      organisationId: org._id,
    });
  const loginRes = await request(app).post('/auth/login').send({
    email: 'cara@abc.test',
    password: 'another-strong-password',
  });
  return loginRes.body.token;
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

describe('templates', () => {
  it('an auditor can build a template with a section and question, then activate it', async () => {
    const token = await bootstrapAuditor();

    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'PCI DSS v4.0 Assessment', category: 'Payment Security' });
    expect(createRes.status).toBe(201);
    expect(createRes.body.status).toBe('draft');
    const templateId = createRes.body._id;

    const sectionRes = await request(app)
      .post(`/templates/${templateId}/sections`)
      .set('Authorization', `Bearer ${token}`)
      .send({ title: 'Network Security' });
    expect(sectionRes.status).toBe(201);
    const sectionId = sectionRes.body._id;

    const questionRes = await request(app)
      .post(`/templates/${templateId}/sections/${sectionId}/questions`)
      .set('Authorization', `Bearer ${token}`)
      .send({
        text: 'Is a firewall configured to restrict traffic?',
        controlRef: '1.1.a',
        required: true,
        responseType: 'yes_no_na',
      });
    expect(questionRes.status).toBe(201);

    const activateRes = await request(app)
      .patch(`/templates/${templateId}`)
      .set('Authorization', `Bearer ${token}`)
      .send({ status: 'active' });
    expect(activateRes.status).toBe(200);
    expect(activateRes.body.status).toBe('active');
  });

  it('blocks activating a template with zero questions', async () => {
    const token = await bootstrapAuditor();
    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'Empty Template' });

    const res = await request(app)
      .patch(`/templates/${createRes.body._id}`)
      .set('Authorization', `Bearer ${token}`)
      .send({ status: 'active' });
    expect(res.status).toBe(400);
  });

  it('duplicating a template copies all sections and questions into a new draft', async () => {
    const token = await bootstrapAuditor();
    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'Source Template' });
    const templateId = createRes.body._id;

    const sectionRes = await request(app)
      .post(`/templates/${templateId}/sections`)
      .set('Authorization', `Bearer ${token}`)
      .send({ title: 'Section A' });
    await request(app)
      .post(`/templates/${templateId}/sections/${sectionRes.body._id}/questions`)
      .set('Authorization', `Bearer ${token}`)
      .send({ text: 'Question 1', responseType: 'short_text' });

    await request(app)
      .patch(`/templates/${templateId}`)
      .set('Authorization', `Bearer ${token}`)
      .send({ status: 'active' });

    const dupRes = await request(app)
      .post(`/templates/${templateId}/duplicate`)
      .set('Authorization', `Bearer ${token}`)
      .send({});
    expect(dupRes.status).toBe(201);
    expect(dupRes.body.status).toBe('draft');
    expect(dupRes.body.sections).toHaveLength(1);
    expect(dupRes.body.sections[0].questions).toHaveLength(1);
    expect(dupRes.body._id).not.toBe(templateId);
  });

  it('rejects a question with an invalid responseType', async () => {
    const token = await bootstrapAuditor();
    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'Template X' });
    const sectionRes = await request(app)
      .post(`/templates/${createRes.body._id}/sections`)
      .set('Authorization', `Bearer ${token}`)
      .send({ title: 'Section A' });

    const res = await request(app)
      .post(`/templates/${createRes.body._id}/sections/${sectionRes.body._id}/questions`)
      .set('Authorization', `Bearer ${token}`)
      .send({ text: 'Bad question', responseType: 'not_a_real_type' });
    expect(res.status).toBe(400);
  });

  it('disabling a question keeps it in the template but marked disabled', async () => {
    const token = await bootstrapAuditor();
    const createRes = await request(app)
      .post('/templates')
      .set('Authorization', `Bearer ${token}`)
      .send({ name: 'Template Y' });
    const sectionRes = await request(app)
      .post(`/templates/${createRes.body._id}/sections`)
      .set('Authorization', `Bearer ${token}`)
      .send({ title: 'Section A' });
    const questionRes = await request(app)
      .post(`/templates/${createRes.body._id}/sections/${sectionRes.body._id}/questions`)
      .set('Authorization', `Bearer ${token}`)
      .send({ text: 'Question 1', responseType: 'short_text' });

    const disableRes = await request(app)
      .patch(
        `/templates/${createRes.body._id}/sections/${sectionRes.body._id}/questions/${questionRes.body._id}`
      )
      .set('Authorization', `Bearer ${token}`)
      .send({ enabled: false });
    expect(disableRes.status).toBe(200);
    expect(disableRes.body.enabled).toBe(false);

    const templateRes = await request(app)
      .get(`/templates/${createRes.body._id}`)
      .set('Authorization', `Bearer ${token}`);
    expect(templateRes.body.sections[0].questions).toHaveLength(1);
  });

  it('a customer_user cannot access template routes', async () => {
    const auditorToken = await bootstrapAuditor();
    const customerToken = await createCustomer(auditorToken);

    const res = await request(app)
      .get('/templates')
      .set('Authorization', `Bearer ${customerToken}`);
    expect(res.status).toBe(403);
  });
});
