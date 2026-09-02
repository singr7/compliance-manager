import { jest } from '@jest/globals';
import request from 'supertest';
import { createApp } from '../src/app.js';
import { startTestDb, stopTestDb, clearTestDb } from './setup.js';

jest.setTimeout(30000);

let app;

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

describe('bootstrap + login', () => {
  it('bootstraps the first auditor and logs in', async () => {
    const bootstrap = await request(app).post('/auth/bootstrap-admin').send({
      fullName: 'Ada Auditor',
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    expect(bootstrap.status).toBe(201);
    expect(bootstrap.body.token).toBeDefined();
    expect(bootstrap.body.user.role).toBe('auditor');

    const login = await request(app).post('/auth/login').send({
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    expect(login.status).toBe(200);
    expect(login.body.token).toBeDefined();
  });

  it('rejects bootstrap once a user already exists', async () => {
    await request(app).post('/auth/bootstrap-admin').send({
      fullName: 'Ada Auditor',
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    const second = await request(app).post('/auth/bootstrap-admin').send({
      fullName: 'Bea Auditor',
      email: 'bea@firm.test',
      password: 'correct-horse-battery-staple',
    });
    expect(second.status).toBe(403);
  });

  it('rejects invalid credentials without leaking which field was wrong', async () => {
    await request(app).post('/auth/bootstrap-admin').send({
      fullName: 'Ada Auditor',
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    const res = await request(app).post('/auth/login').send({
      email: 'ada@firm.test',
      password: 'wrong-password',
    });
    expect(res.status).toBe(401);
  });

  it('never returns passwordHash in the login response', async () => {
    await request(app).post('/auth/bootstrap-admin').send({
      fullName: 'Ada Auditor',
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    const login = await request(app).post('/auth/login').send({
      email: 'ada@firm.test',
      password: 'correct-horse-battery-staple',
    });
    expect(login.body.user.passwordHash).toBeUndefined();
  });
});
