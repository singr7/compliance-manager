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

async function createOrgAndCustomer(auditorToken) {
  const orgRes = await request(app)
    .post('/organisations')
    .set('Authorization', `Bearer ${auditorToken}`)
    .send({ name: 'ABC Manufacturing' });
  const org = orgRes.body;

  const userRes = await request(app)
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

  return { org, customerToken: loginRes.body.token, customerUser: userRes.body };
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

describe('organisations + users', () => {
  it('an auditor can create an organisation and invite a customer user who can log in', async () => {
    const auditorToken = await bootstrapAuditor();
    const { org, customerToken } = await createOrgAndCustomer(auditorToken);

    expect(org.name).toBe('ABC Manufacturing');
    expect(customerToken).toBeDefined();
  });

  it('a customer_user cannot list organisations (auditor-only)', async () => {
    const auditorToken = await bootstrapAuditor();
    const { customerToken } = await createOrgAndCustomer(auditorToken);

    const res = await request(app)
      .get('/organisations')
      .set('Authorization', `Bearer ${customerToken}`);
    expect(res.status).toBe(403);
  });

  it("a customer_user cannot see another org's detail", async () => {
    const auditorToken = await bootstrapAuditor();
    const { customerToken } = await createOrgAndCustomer(auditorToken);

    const otherOrgRes = await request(app)
      .post('/organisations')
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({ name: 'Other Org' });

    const res = await request(app)
      .get(`/organisations/${otherOrgRes.body._id}`)
      .set('Authorization', `Bearer ${customerToken}`);
    expect(res.status).toBe(403);
  });

  it('rejects creating a customer_user without an organisationId', async () => {
    const auditorToken = await bootstrapAuditor();
    const res = await request(app)
      .post('/users')
      .set('Authorization', `Bearer ${auditorToken}`)
      .send({
        fullName: 'Missing Org',
        email: 'missing@abc.test',
        password: 'another-strong-password',
        role: 'customer_user',
      });
    expect(res.status).toBe(400);
  });

  it('requires auth on protected routes', async () => {
    const res = await request(app).get('/organisations');
    expect(res.status).toBe(401);
  });
});
