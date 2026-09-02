import { Router } from 'express';
import { Evidence } from '../models/Evidence.js';
import { AssessmentResponse } from '../models/AssessmentResponse.js';
import { Assessment } from '../models/Assessment.js';
import { requireAuth, resolveOrgScope } from '../middleware/auth.js';
import { getStorage } from '../storage/index.js';

export const evidenceRouter = Router();

evidenceRouter.use(requireAuth, resolveOrgScope);

evidenceRouter.get('/:evidenceId/download', async (req, res) => {
  const evidence = await Evidence.findById(req.params.evidenceId);
  if (!evidence) return res.status(404).json({ error: 'Evidence not found' });

  const response = await AssessmentResponse.findById(evidence.assessmentResponseId);
  if (!response) return res.status(404).json({ error: 'Evidence not found' });

  const assessment = await Assessment.findById(response.assessmentId);
  if (!assessment) return res.status(404).json({ error: 'Evidence not found' });

  if (
    req.scopedOrganisationId &&
    assessment.organisationId.toString() !== req.scopedOrganisationId.toString()
  ) {
    return res.status(403).json({ error: 'Forbidden' });
  }

  const buffer = await getStorage().get(evidence.storageKey);
  res.setHeader('Content-Type', evidence.mimeType);
  res.setHeader('Content-Disposition', `attachment; filename="${evidence.originalFilename}"`);
  res.send(buffer);
});
