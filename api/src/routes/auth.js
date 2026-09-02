import { Router } from 'express';
import { User } from '../models/User.js';
import { hashPassword, verifyPassword } from '../utils/password.js';
import { signToken } from '../utils/jwt.js';
import { seedReferenceTemplates } from '../services/referenceTemplates.js';
import { env } from '../config/env.js';
import { logger } from '../utils/logger.js';

export const authRouter = Router();

function toProfile(user) {
  return {
    id: user._id,
    fullName: user.fullName,
    email: user.email,
    role: user.role,
    organisationId: user.organisationId,
    status: user.status,
  };
}

authRouter.post('/login', async (req, res) => {
  const { email, password } = req.body || {};
  if (!email || !password) {
    return res.status(400).json({ error: 'email and password are required' });
  }

  const user = await User.findOne({ email: email.toLowerCase().trim() });
  if (!user || user.status !== 'active') {
    return res.status(401).json({ error: 'Invalid credentials' });
  }

  const valid = await verifyPassword(password, user.passwordHash);
  if (!valid) {
    return res.status(401).json({ error: 'Invalid credentials' });
  }

  user.lastLoginAt = new Date();
  await user.save();

  const token = signToken(user);
  res.json({ token, user: toProfile(user) });
});

authRouter.post('/logout', (req, res) => {
  // Stateless JWT — client discards the token. No server-side session to invalidate in V1.
  res.status(204).send();
});

// Exposed for seeding the very first auditor account in a fresh environment.
// Only allowed when no users exist yet — after that, use POST /users (auditor-only).
authRouter.post('/bootstrap-admin', async (req, res) => {
  const existingCount = await User.countDocuments({});
  if (existingCount > 0) {
    return res.status(403).json({ error: 'Bootstrap is only available on an empty database' });
  }
  const { fullName, email, password } = req.body || {};
  if (!fullName || !email || !password) {
    return res.status(400).json({ error: 'fullName, email and password are required' });
  }
  const passwordHash = await hashPassword(password);
  const user = await User.create({
    fullName,
    email,
    passwordHash,
    role: 'auditor',
    organisationId: null,
  });

  // First auditor on a fresh deployment — seed the reference checklist templates so
  // there's real content to work with immediately, without a separate manual step.
  // Idempotent and one-time in practice (bootstrap only ever succeeds on an empty DB).
  if (env.seedTemplatesOnBootstrap) {
    try {
      const created = await seedReferenceTemplates(user._id);
      logger.info('reference_templates_seeded', { count: created.length });
    } catch (err) {
      logger.error('reference_templates_seed_failed', { message: err.message });
    }
  }

  const token = signToken(user);
  res.status(201).json({ token, user: toProfile(user) });
});

export { toProfile };
