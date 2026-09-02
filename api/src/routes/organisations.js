import { Router } from 'express';
import { Organisation } from '../models/Organisation.js';
import { requireAuth, requireRole } from '../middleware/auth.js';

export const organisationsRouter = Router();

organisationsRouter.use(requireAuth);

organisationsRouter.get('/', requireRole('auditor'), async (req, res) => {
  const filter = {};
  if (req.query.status) filter.status = req.query.status;
  const orgs = await Organisation.find(filter).sort({ createdAt: -1 });
  res.json(orgs);
});

organisationsRouter.post('/', requireRole('auditor'), async (req, res) => {
  const { name } = req.body || {};
  if (!name || !name.trim()) {
    return res.status(400).json({ error: 'name is required' });
  }
  const org = await Organisation.create({ name: name.trim(), createdBy: req.auth.sub });
  res.status(201).json(org);
});

organisationsRouter.get('/:id', async (req, res) => {
  const org = await Organisation.findById(req.params.id);
  if (!org) return res.status(404).json({ error: 'Organisation not found' });

  if (req.auth.role === 'customer_user' && req.auth.organisationId !== req.params.id) {
    return res.status(403).json({ error: 'Forbidden' });
  }
  res.json(org);
});

organisationsRouter.patch('/:id', requireRole('auditor'), async (req, res) => {
  const { name, status } = req.body || {};
  const update = {};
  if (name !== undefined) update.name = name;
  if (status !== undefined) update.status = status;

  const org = await Organisation.findByIdAndUpdate(req.params.id, update, {
    new: true,
    runValidators: true,
  });
  if (!org) return res.status(404).json({ error: 'Organisation not found' });
  res.json(org);
});
