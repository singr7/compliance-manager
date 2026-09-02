import { Router } from 'express';
import { ChecklistTemplate, RESPONSE_TYPE_VALUES } from '../models/ChecklistTemplate.js';
import { requireAuth, requireRole } from '../middleware/auth.js';

export const templatesRouter = Router();

templatesRouter.use(requireAuth, requireRole('auditor'));

function totalQuestionCount(template) {
  return template.sections.reduce((sum, section) => sum + section.questions.length, 0);
}

function findSectionOr404(template, sectionId, res) {
  const section = template.sections.id(sectionId);
  if (!section) {
    res.status(404).json({ error: 'Section not found' });
    return null;
  }
  return section;
}

templatesRouter.get('/', async (req, res) => {
  const filter = {};
  if (req.query.status) filter.status = req.query.status;
  const templates = await ChecklistTemplate.find(filter).sort({ createdAt: -1 });
  res.json(templates);
});

templatesRouter.post('/', async (req, res) => {
  const { name, category } = req.body || {};
  if (!name || !name.trim()) {
    return res.status(400).json({ error: 'name is required' });
  }
  const template = await ChecklistTemplate.create({
    name: name.trim(),
    category: category || '',
    createdBy: req.auth.sub,
  });
  res.status(201).json(template);
});

templatesRouter.post('/:id/duplicate', async (req, res) => {
  const source = await ChecklistTemplate.findById(req.params.id);
  if (!source) return res.status(404).json({ error: 'Template not found' });

  const { newName } = req.body || {};
  const copy = await ChecklistTemplate.create({
    name: newName || `${source.name} (copy)`,
    category: source.category,
    status: 'draft',
    createdBy: req.auth.sub,
    sections: source.sections.map((section) => ({
      title: section.title,
      order: section.order,
      questions: section.questions.map((q) => ({
        text: q.text,
        controlRef: q.controlRef,
        guidance: q.guidance,
        expectedEvidence: q.expectedEvidence,
        required: q.required,
        responseType: q.responseType,
        enabled: q.enabled,
        order: q.order,
      })),
    })),
  });
  res.status(201).json(copy);
});

templatesRouter.get('/:id', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });
  res.json(template);
});

templatesRouter.patch('/:id', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });

  const { name, category, status } = req.body || {};
  if (name !== undefined) template.name = name;
  if (category !== undefined) template.category = category;
  if (status !== undefined) {
    if (status === 'active' && totalQuestionCount(template) === 0) {
      return res.status(400).json({ error: 'Cannot activate a template with zero questions' });
    }
    template.status = status;
  }

  await template.save();
  res.json(template);
});

templatesRouter.post('/:id/sections', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });

  const { title } = req.body || {};
  if (!title || !title.trim()) {
    return res.status(400).json({ error: 'title is required' });
  }

  const order = template.sections.length;
  template.sections.push({ title: title.trim(), order, questions: [] });
  await template.save();
  res.status(201).json(template.sections[template.sections.length - 1]);
});

templatesRouter.patch('/:id/sections/:sectionId', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });

  const section = findSectionOr404(template, req.params.sectionId, res);
  if (!section) return;

  const { title, order } = req.body || {};
  if (title !== undefined) section.title = title;
  if (order !== undefined) section.order = order;

  await template.save();
  res.json(section);
});

templatesRouter.post('/:id/sections/:sectionId/questions', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });

  const section = findSectionOr404(template, req.params.sectionId, res);
  if (!section) return;

  const { text, controlRef, guidance, expectedEvidence, required, responseType } = req.body || {};
  if (!text || !text.trim()) {
    return res.status(400).json({ error: 'text is required' });
  }
  if (!responseType || !RESPONSE_TYPE_VALUES.includes(responseType)) {
    return res.status(400).json({ error: `responseType must be one of ${RESPONSE_TYPE_VALUES.join(', ')}` });
  }

  const order = section.questions.length;
  section.questions.push({
    text: text.trim(),
    controlRef: controlRef || '',
    guidance: guidance || '',
    expectedEvidence: expectedEvidence || '',
    required: required !== undefined ? Boolean(required) : true,
    responseType,
    enabled: true,
    order,
  });

  await template.save();
  res.status(201).json(section.questions[section.questions.length - 1]);
});

templatesRouter.patch('/:id/sections/:sectionId/questions/:questionId', async (req, res) => {
  const template = await ChecklistTemplate.findById(req.params.id);
  if (!template) return res.status(404).json({ error: 'Template not found' });

  const section = findSectionOr404(template, req.params.sectionId, res);
  if (!section) return;

  const question = section.questions.id(req.params.questionId);
  if (!question) return res.status(404).json({ error: 'Question not found' });

  const { text, controlRef, guidance, expectedEvidence, required, responseType, enabled, order } =
    req.body || {};
  if (text !== undefined) question.text = text;
  if (controlRef !== undefined) question.controlRef = controlRef;
  if (guidance !== undefined) question.guidance = guidance;
  if (expectedEvidence !== undefined) question.expectedEvidence = expectedEvidence;
  if (required !== undefined) question.required = Boolean(required);
  if (responseType !== undefined) {
    if (!RESPONSE_TYPE_VALUES.includes(responseType)) {
      return res.status(400).json({ error: `responseType must be one of ${RESPONSE_TYPE_VALUES.join(', ')}` });
    }
    question.responseType = responseType;
  }
  if (enabled !== undefined) question.enabled = Boolean(enabled);
  if (order !== undefined) question.order = order;

  await template.save();
  res.json(question);
});
