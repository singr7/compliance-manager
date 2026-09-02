// Explicit index creation, run on every startup (idempotent — Mongo no-ops on an index
// that already exists). Keeps collections/indexes reproducible without a manual setup
// step, independent of Mongoose's autoIndex dev-convenience behavior.
import { User } from './models/User.js';
import { Organisation } from './models/Organisation.js';
import { ChecklistTemplate } from './models/ChecklistTemplate.js';
import { Assessment } from './models/Assessment.js';
import { AssessmentResponse } from './models/AssessmentResponse.js';
import { Evidence } from './models/Evidence.js';
import { Comment } from './models/Comment.js';
import { logger } from './utils/logger.js';

const MODELS = [User, Organisation, ChecklistTemplate, Assessment, AssessmentResponse, Evidence, Comment];

export async function ensureIndexes() {
  for (const model of MODELS) {
    await model.ensureIndexes();
  }
  logger.info('indexes_ensured', { models: MODELS.length });
}
