import os from 'os';
import path from 'path';
import fs from 'fs/promises';
import { jest } from '@jest/globals';
import request from 'supertest';

jest.setTimeout(30000);

let app;
let clearTestDb;

// Env vars must be set before anything transitively imports config/env.js (a module
// singleton). Even './setup.js' pulls in db.js -> env.js, so it too must be imported
// dynamically in beforeAll, after these are assigned, rather than statically up here.
const evidenceDir = path.join(os.tmpdir(), `pcm-evidence-test-${Date.now()}`);

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

async function createActiveTemplate(token, responseType = 'file_required') {
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
    .send({ text: 'Upload your network diagram', controlRef: '1.1.1', responseType });

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

let stopTestDb;

beforeAll(async () => {
  process.env.STORAGE_BACKEND = 'filesystem';
  process.env.EVIDENCE_STORAGE_DIR = evidenceDir;
  process.env.EVIDENCE_MAX_SIZE_BYTES = '1000';
  const setup = await import('./setup.js');
  const { createApp } = await import('../src/app.js');
  clearTestDb = setup.clearTestDb;
  stopTestDb = setup.stopTestDb;
  await setup.startTestDb();
  app = createApp();
});

afterAll(async () => {
  await stopTestDb();
  await fs.rm(evidenceDir, { recursive: true, force: true });
});

afterEach(async () => {
  await clearTestDb();
});

describe('evidence upload', () => {
  it('uploads, lists, and lets the auditor download evidence for a response', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const uploadRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .field('description', 'Network diagram v1')
      .attach('file', Buffer.from('%PDF-1.4 fake pdf content'), {
        filename: 'diagram.pdf',
        contentType: 'application/pdf',
      });

    expect(uploadRes.status).toBe(201);
    expect(uploadRes.body.originalFilename).toBe('diagram.pdf');
    expect(uploadRes.body.isActive).toBe(true);

    const evidenceListRes = await request(app)
      .get(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${auditorToken}`);
    expect(evidenceListRes.status).toBe(200);
    expect(evidenceListRes.body).toHaveLength(1);

    const evidenceId = evidenceListRes.body[0]._id;
    const downloadRes = await request(app)
      .get(`/evidence/${evidenceId}/download`)
      .set('Authorization', `Bearer ${auditorToken}`)
      .buffer(true)
      .parse((res, cb) => {
        const chunks = [];
        res.on('data', (chunk) => chunks.push(chunk));
        res.on('end', () => cb(null, Buffer.concat(chunks)));
      });
    expect(downloadRes.status).toBe(200);
    expect(downloadRes.body.toString()).toContain('fake pdf content');
    expect(downloadRes.headers['content-disposition']).toContain('diagram.pdf');
  });

  it('rejects a disallowed MIME type and an oversized file', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const badMimeRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.from('#!/bin/sh\necho hi'), {
        filename: 'script.sh',
        contentType: 'application/x-sh',
      });
    expect(badMimeRes.status).toBe(400);

    const tooBigRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.alloc(2000, 'a'), {
        filename: 'big.pdf',
        contentType: 'application/pdf',
      });
    expect(tooBigRes.status).toBe(400);
  });

  it('blocks submitting a file_required question with no active evidence, then allows it once uploaded', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const blockedSubmit = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ submit: true });
    expect(blockedSubmit.status).toBe(400);

    await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.from('%PDF-1.4 content'), {
        filename: 'diagram.pdf',
        contentType: 'application/pdf',
      });

    const allowedSubmit = await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ submit: true });
    expect(allowedSubmit.status).toBe(200);
    expect(allowedSubmit.body.status).toBe('submitted');
  });

  it('lets a customer delete their own not-yet-submitted evidence, but not after submit', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken);
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const uploadRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.from('%PDF-1.4 content'), {
        filename: 'diagram.pdf',
        contentType: 'application/pdf',
      });
    const evidenceId = uploadRes.body._id;

    const deleteRes = await request(app)
      .delete(`/assessments/${assessment._id}/responses/${responseId}/evidence/${evidenceId}`)
      .set('Authorization', `Bearer ${customerToken}`);
    expect(deleteRes.status).toBe(204);

    const secondUpload = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.from('%PDF-1.4 content'), {
        filename: 'diagram2.pdf',
        contentType: 'application/pdf',
      });
    await request(app)
      .patch(`/assessments/${assessment._id}/responses/${responseId}`)
      .set('Authorization', `Bearer ${customerToken}`)
      .send({ submit: true });

    const blockedDelete = await request(app)
      .delete(`/assessments/${assessment._id}/responses/${responseId}/evidence/${secondUpload.body._id}`)
      .set('Authorization', `Bearer ${customerToken}`);
    expect(blockedDelete.status).toBe(400);
  });

  it('rejects download and evidence access for a customer from another organisation', async () => {
    const auditorToken = await bootstrapAuditor();
    const templateId = await createActiveTemplate(auditorToken);
    const { org, token: customerToken } = await createCustomer(auditorToken, 'ABC Manufacturing');
    const { token: otherCustomerToken } = await createCustomer(auditorToken, 'Other Org');
    const assessment = await createAssessment(auditorToken, templateId, org._id);

    const listRes = await request(app)
      .get(`/assessments/${assessment._id}/responses`)
      .set('Authorization', `Bearer ${customerToken}`);
    const responseId = listRes.body[0]._id;

    const uploadRes = await request(app)
      .post(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${customerToken}`)
      .attach('file', Buffer.from('%PDF-1.4 content'), {
        filename: 'diagram.pdf',
        contentType: 'application/pdf',
      });
    const evidenceId = uploadRes.body._id;

    const forbiddenDownload = await request(app)
      .get(`/evidence/${evidenceId}/download`)
      .set('Authorization', `Bearer ${otherCustomerToken}`);
    expect(forbiddenDownload.status).toBe(403);

    const forbiddenList = await request(app)
      .get(`/assessments/${assessment._id}/responses/${responseId}/evidence`)
      .set('Authorization', `Bearer ${otherCustomerToken}`);
    expect(forbiddenList.status).toBe(403);
  });
});
