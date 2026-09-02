import { Router } from 'express';
import { User } from '../models/User.js';
import { requireAuth, requireRole } from '../middleware/auth.js';
import { hashPassword } from '../utils/password.js';
import { toProfile } from './auth.js';

export const usersRouter = Router();

usersRouter.use(requireAuth);

usersRouter.get('/me', async (req, res) => {
  const user = await User.findById(req.auth.sub);
  if (!user) return res.status(404).json({ error: 'User not found' });
  res.json(toProfile(user));
});

usersRouter.get('/', requireRole('auditor'), async (req, res) => {
  const filter = {};
  if (req.query.organisationId) filter.organisationId = req.query.organisationId;
  if (req.query.role) filter.role = req.query.role;
  const users = await User.find(filter).sort({ createdAt: -1 });
  res.json(users.map(toProfile));
});

usersRouter.post('/', requireRole('auditor'), async (req, res) => {
  const { fullName, email, password, role, organisationId, phoneNumber } = req.body || {};
  if (!fullName || !email || !password || !role) {
    return res.status(400).json({ error: 'fullName, email, password and role are required' });
  }
  if (!['auditor', 'customer_user'].includes(role)) {
    return res.status(400).json({ error: 'role must be auditor or customer_user' });
  }
  if (role === 'customer_user' && !organisationId) {
    return res.status(400).json({ error: 'organisationId is required for role customer_user' });
  }

  const existing = await User.findOne({ email: email.toLowerCase().trim() });
  if (existing) {
    return res.status(409).json({ error: 'A user with this email already exists' });
  }

  const passwordHash = await hashPassword(password);
  const user = await User.create({
    fullName,
    email,
    phoneNumber,
    passwordHash,
    role,
    organisationId: role === 'customer_user' ? organisationId : null,
  });
  res.status(201).json(toProfile(user));
});

usersRouter.patch('/:id', async (req, res) => {
  const isSelf = req.auth.sub === req.params.id;
  const isAuditor = req.auth.role === 'auditor';
  if (!isSelf && !isAuditor) {
    return res.status(403).json({ error: 'Forbidden' });
  }

  const update = {};
  const { fullName, phoneNumber, password, status } = req.body || {};
  if (fullName !== undefined) update.fullName = fullName;
  if (phoneNumber !== undefined) update.phoneNumber = phoneNumber;
  if (password) update.passwordHash = await hashPassword(password);
  // Only an auditor may deactivate/reactivate a user; self-edit is limited to name/phone/password.
  if (status !== undefined) {
    if (!isAuditor) {
      return res.status(403).json({ error: 'Only an auditor can change account status' });
    }
    update.status = status;
  }

  const user = await User.findByIdAndUpdate(req.params.id, update, {
    new: true,
    runValidators: true,
  });
  if (!user) return res.status(404).json({ error: 'User not found' });
  res.json(toProfile(user));
});
