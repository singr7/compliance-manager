import { Router } from 'express';
import multer from 'multer';
import { Assessment } from '../models/Assessment.js';
import { AssessmentResponse } from '../models/AssessmentResponse.js';
import { ChecklistTemplate } from '../models/ChecklistTemplate.js';
import { Evidence } from '../models/Evidence.js';
import { Comment } from '../models/Comment.js';
import { requireAuth, requireRole, resolveOrgScope } from '../middleware/auth.js';
import { getStorage } from '../storage/index.js';
import { buildStorageKey } from '../storage/filesystem.js';
import { ALLOWED_MIME_TYPES } from '../services/evidenceRules.js';
import { env } from '../config/env.js';

const upload = multer({ storage: multer.memoryStorage(), limits: { fileSize: env.evidenceMaxSizeBytes } });

function handleUpload(req, res, next) {
  upload.single('file')(req, res, (err) => {
    if (err instanceof multer.MulterError && err.code === 'LIMIT_FILE_SIZE') {
      return res.status(400).json({ error: `File exceeds the maximum size of ${env.evidenceMaxSizeBytes} bytes` });
    }
    if (err) return next(err);
    next();
  });
}

export const assessmentsRouter = Router();

assessmentsRouter.use(requireAuth, resolveOrgScope);

async function withProgress(assessment) {
  const responses = await AssessmentResponse.find({ assessmentId: assessment._id });
  const counts = responses.reduce((acc, r) => {
    acc[r.status] = (acc[r.status] || 0) + 1;
    return acc;
  }, {});
  return {
    ...assessment.toObject(),
    progress: { total: responses.length, counts },
  };
}

assessmentsRouter.get('/', async (req, res) => {
  const filter = {};
  if (req.query.status) filter.status = req.query.status;
  if (req.scopedOrganisationId) {
    filter.organisationId = req.scopedOrganisationId;
  } else if (req.query.organisationId) {
    filter.organisationId = req.query.organisationId;
  }

  const assessments = await Assessment.find(filter).sort({ createdAt: -1 });
  const withCounts = await Promise.all(assessments.map(withProgress));
  res.json(withCounts);
});

assessmentsRouter.post('/', requireRole('auditor'), async (req, res) => {
  const { templateId, organisationId, assignedAuditorId, dueDate } = req.body || {};
  if (!templateId || !organisationId) {
    return res.status(400).json({ error: 'templateId and organisationId are required' });
  }

  const template = await ChecklistTemplate.findById(templateId);
  if (!template) return res.status(404).json({ error: 'Template not found' });
  if (template.status !== 'active') {
    return res.status(400).json({ error: 'Template must be active to create an assessment' });
  }

  const duplicate = await Assessment.findOne({
    templateId,
    organisationId,
    status: { $in: ['draft', 'active', 'under_review'] },
  });
  if (duplicate) {
    return res.status(409).json({ error: 'An active assessment already exists for this template and organisation' });
  }

  const assessment = await Assessment.create({
    templateId,
    organisationId,
    assignedAuditorId: assignedAuditorId || null,
    dueDate: dueDate || null,
    createdBy: req.auth.sub,
  });

  const responseDocs = [];
  for (const section of template.sections) {
    for (const question of section.questions) {
      if (!question.enabled) continue;
      responseDocs.push({
        assessmentId: assessment._id,
        questionId: question._id,
        sectionId: section._id,
        questionTextSnapshot: question.text,
        controlRefSnapshot: question.controlRef || '',
        status: 'not_started',
      });
    }
  }
  if (responseDocs.length > 0) {
    await AssessmentResponse.insertMany(responseDocs);
  }

  res.status(201).json(await withProgress(assessment));
});

assessmentsRouter.get('/:id', async (req, res) => {
  const assessment = await Assessment.findById(req.params.id);
  if (!assessment) return res.status(404).json({ error: 'Assessment not found' });

  if (
    req.scopedOrganisationId &&
    assessment.organisationId.toString() !== req.scopedOrganisationId.toString()
  ) {
    return res.status(403).json({ error: 'Forbidden' });
  }

  res.json(await withProgress(assessment));
});

assessmentsRouter.patch('/:id', requireRole('auditor'), async (req, res) => {
  const assessment = await Assessment.findById(req.params.id);
  if (!assessment) return res.status(404).json({ error: 'Assessment not found' });

  const { status, dueDate } = req.body || {};
  const VALID_STATUSES = ['draft', 'active', 'under_review', 'completed'];
  if (status !== undefined) {
    if (!VALID_STATUSES.includes(status)) {
      return res.status(400).json({ error: `status must be one of ${VALID_STATUSES.join(', ')}` });
    }
    assessment.status = status;
    if (status === 'completed') assessment.completedAt = new Date();
  }
  if (dueDate !== undefined) assessment.dueDate = dueDate;

  await assessment.save();
  res.json(await withProgress(assessment));
});

async function loadScopedAssessment(req, res) {
  const assessment = await Assessment.findById(req.params.id);
  if (!assessment) {
    res.status(404).json({ error: 'Assessment not found' });
    return null;
  }
  if (
    req.scopedOrganisationId &&
    assessment.organisationId.toString() !== req.scopedOrganisationId.toString()
  ) {
    res.status(403).json({ error: 'Forbidden' });
    return null;
  }
  return assessment;
}

function requiresAnswer(question) {
  if (!question) return false;
  return question.required;
}

function findTemplateQuestion(template, sectionId, questionId) {
  const section = template.sections.id(sectionId);
  if (!section) return null;
  return section.questions.id(questionId);
}

// The response endpoints join in a read-only `question` view (responseType, required,
// guidance) from the live template so the customer questionnaire UI can render the right
// input control without needing access to the auditor-only /templates/:id endpoint.
function withQuestionView(response, template) {
  const section = template ? template.sections.id(response.sectionId) : null;
  const templateQuestion = section ? section.questions.id(response.questionId) : null;
  return {
    ...response.toObject(),
    sectionTitle: section ? section.title : null,
    sectionOrder: section ? section.order : null,
    question: templateQuestion
      ? {
          responseType: templateQuestion.responseType,
          required: templateQuestion.required,
          guidance: templateQuestion.guidance,
          expectedEvidence: templateQuestion.expectedEvidence,
        }
      : null,
  };
}

assessmentsRouter.get('/:id/responses', async (req, res) => {
  const assessment = await loadScopedAssessment(req, res);
  if (!assessment) return;

  const [template, responses] = await Promise.all([
    ChecklistTemplate.findById(assessment.templateId),
    AssessmentResponse.find({ assessmentId: assessment._id }).sort({ sectionId: 1, createdAt: 1 }),
  ]);
  res.json(responses.map((r) => withQuestionView(r, template)));
});

assessmentsRouter.get('/:id/responses/:responseId', async (req, res) => {
  const assessment = await loadScopedAssessment(req, res);
  if (!assessment) return;

  const response = await AssessmentResponse.findOne({
    _id: req.params.responseId,
    assessmentId: assessment._id,
  });
  if (!response) return res.status(404).json({ error: 'Response not found' });

  const template = await ChecklistTemplate.findById(assessment.templateId);
  res.json(withQuestionView(response, template));
});

assessmentsRouter.patch(
  '/:id/responses/:responseId',
  requireRole('customer_user'),
  async (req, res) => {
    const assessment = await loadScopedAssessment(req, res);
    if (!assessment) return;

    const response = await AssessmentResponse.findOne({
      _id: req.params.responseId,
      assessmentId: assessment._id,
    });
    if (!response) return res.status(404).json({ error: 'Response not found' });

    const { answer, customerNote, submit } = req.body || {};
    if (answer !== undefined) response.answer = answer;
    if (customerNote !== undefined) response.customerNote = customerNote;

    const template = await ChecklistTemplate.findById(assessment.templateId);

    if (submit) {
      const hasValue =
        response.answer && response.answer.value !== null && response.answer.value !== undefined && response.answer.value !== '';
      const templateQuestion = findTemplateQuestion(template, response.sectionId, response.questionId);
      if (
        requiresAnswer(templateQuestion) &&
        templateQuestion.responseType !== 'file_required' &&
        !hasValue
      ) {
        return res.status(400).json({ error: 'This question requires an answer before it can be submitted' });
      }
      if (templateQuestion?.responseType === 'file_required') {
        const activeEvidenceCount = await Evidence.countDocuments({
          assessmentResponseId: response._id,
          isActive: true,
        });
        if (activeEvidenceCount === 0) {
          return res.status(400).json({ error: 'This question requires at least one evidence file before it can be submitted' });
        }
      }
      response.status = 'submitted';
      response.submittedAt = new Date();
    } else {
      response.status = 'in_progress';
    }

    await response.save();
    res.json(withQuestionView(response, template));
  }
);

async function loadScopedResponse(req, res, assessment) {
  const response = await AssessmentResponse.findOne({
    _id: req.params.responseId,
    assessmentId: assessment._id,
  });
  if (!response) {
    res.status(404).json({ error: 'Response not found' });
    return null;
  }
  return response;
}

assessmentsRouter.post(
  '/:id/responses/:responseId/evidence',
  requireRole('customer_user'),
  handleUpload,
  async (req, res) => {
    const assessment = await loadScopedAssessment(req, res);
    if (!assessment) return;
    const response = await loadScopedResponse(req, res, assessment);
    if (!response) return;

    if (!req.file) return res.status(400).json({ error: 'A file is required' });
    if (!ALLOWED_MIME_TYPES.includes(req.file.mimetype)) {
      return res.status(400).json({ error: `File type ${req.file.mimetype} is not allowed` });
    }

    const evidence = new Evidence({
      assessmentResponseId: response._id,
      originalFilename: req.file.originalname,
      storageKey: '',
      mimeType: req.file.mimetype,
      sizeBytes: req.file.size,
      uploadedBy: req.auth.sub,
      description: req.body?.description || '',
    });
    evidence.storageKey = buildStorageKey({
      organisationId: assessment.organisationId,
      assessmentId: assessment._id,
      questionId: response.questionId,
      evidenceId: evidence._id,
    });

    await getStorage().put(evidence.storageKey, req.file.buffer);
    await evidence.save();

    res.status(201).json(evidence);
  }
);

assessmentsRouter.get('/:id/responses/:responseId/evidence', async (req, res) => {
  const assessment = await loadScopedAssessment(req, res);
  if (!assessment) return;
  const response = await loadScopedResponse(req, res, assessment);
  if (!response) return;

  const evidence = await Evidence.find({ assessmentResponseId: response._id }).sort({ uploadedAt: -1 });
  res.json(evidence);
});

assessmentsRouter.delete(
  '/:id/responses/:responseId/evidence/:evidenceId',
  requireRole('customer_user'),
  async (req, res) => {
    const assessment = await loadScopedAssessment(req, res);
    if (!assessment) return;
    const response = await loadScopedResponse(req, res, assessment);
    if (!response) return;

    if (response.status !== 'not_started' && response.status !== 'in_progress') {
      return res.status(400).json({ error: 'Evidence can only be removed before the response is submitted' });
    }

    const evidence = await Evidence.findOne({
      _id: req.params.evidenceId,
      assessmentResponseId: response._id,
    });
    if (!evidence) return res.status(404).json({ error: 'Evidence not found' });

    await getStorage().delete(evidence.storageKey);
    await evidence.deleteOne();
    res.status(204).end();
  }
);

const REVIEW_DECISIONS = ['accept', 'needs_clarification', 'non_compliant'];
const REVIEW_DECISION_TO_STATUS = {
  accept: 'accepted',
  needs_clarification: 'needs_clarification',
  non_compliant: 'non_compliant',
};

assessmentsRouter.post(
  '/:id/responses/:responseId/review',
  requireRole('auditor'),
  async (req, res) => {
    const assessment = await loadScopedAssessment(req, res);
    if (!assessment) return;
    const response = await loadScopedResponse(req, res, assessment);
    if (!response) return;

    const { decision, comment } = req.body || {};
    if (!REVIEW_DECISIONS.includes(decision)) {
      return res.status(400).json({ error: `decision must be one of ${REVIEW_DECISIONS.join(', ')}` });
    }
    if (response.status !== 'submitted' && response.status !== 'needs_clarification') {
      return res.status(400).json({ error: 'Only a submitted response can be reviewed' });
    }
    if (decision === 'needs_clarification' && !comment?.trim()) {
      return res.status(400).json({ error: 'A comment is required when requesting clarification' });
    }

    response.status = REVIEW_DECISION_TO_STATUS[decision];
    response.reviewedAt = new Date();
    response.reviewedBy = req.auth.sub;
    await response.save();

    if (comment?.trim()) {
      await Comment.create({
        assessmentResponseId: response._id,
        authorId: req.auth.sub,
        authorRole: 'auditor',
        text: comment.trim(),
      });
    }

    const template = await ChecklistTemplate.findById(assessment.templateId);
    res.json(withQuestionView(response, template));
  }
);

assessmentsRouter.post('/:id/responses/:responseId/comments', async (req, res) => {
  const assessment = await loadScopedAssessment(req, res);
  if (!assessment) return;
  const response = await loadScopedResponse(req, res, assessment);
  if (!response) return;

  const { text } = req.body || {};
  if (!text?.trim()) {
    return res.status(400).json({ error: 'text is required' });
  }

  const comment = await Comment.create({
    assessmentResponseId: response._id,
    authorId: req.auth.sub,
    authorRole: req.auth.role,
    text: text.trim(),
  });

  res.status(201).json(comment);
});

assessmentsRouter.get('/:id/responses/:responseId/comments', async (req, res) => {
  const assessment = await loadScopedAssessment(req, res);
  if (!assessment) return;
  const response = await loadScopedResponse(req, res, assessment);
  if (!response) return;

  const comments = await Comment.find({ assessmentResponseId: response._id }).sort({ createdAt: 1 });
  res.json(comments);
});
