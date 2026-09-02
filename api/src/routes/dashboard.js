import { Router } from 'express';
import { Assessment } from '../models/Assessment.js';
import { AssessmentResponse } from '../models/AssessmentResponse.js';
import { Organisation } from '../models/Organisation.js';
import { ChecklistTemplate } from '../models/ChecklistTemplate.js';
import { requireAuth, resolveOrgScope } from '../middleware/auth.js';

export const dashboardRouter = Router();

dashboardRouter.use(requireAuth, resolveOrgScope);

async function withProgress(assessment, responsesByAssessment) {
  const responses = responsesByAssessment.get(assessment._id.toString()) || [];
  const counts = responses.reduce((acc, r) => {
    acc[r.status] = (acc[r.status] || 0) + 1;
    return acc;
  }, {});
  const total = responses.length;
  const done = responses.filter((r) => r.status !== 'not_started').length;
  return {
    _id: assessment._id,
    templateId: assessment.templateId,
    organisationId: assessment.organisationId,
    status: assessment.status,
    dueDate: assessment.dueDate,
    progress: { total, done, pctComplete: total > 0 ? Math.round((done / total) * 100) : 0, counts },
  };
}

async function groupResponsesByAssessment(assessmentIds) {
  const responses = await AssessmentResponse.find({ assessmentId: { $in: assessmentIds } });
  const map = new Map();
  for (const r of responses) {
    const key = r.assessmentId.toString();
    if (!map.has(key)) map.set(key, []);
    map.get(key).push(r);
  }
  return map;
}

async function buildAuditorDashboard() {
  const activeAssessments = await Assessment.find({ status: 'active' });
  const activeAssessmentIds = activeAssessments.map((a) => a._id);
  const responsesByAssessment = await groupResponsesByAssessment(activeAssessmentIds);

  const orgIds = [...new Set(activeAssessments.map((a) => a.organisationId.toString()))];
  const orgs = await Organisation.find({ _id: { $in: orgIds } });
  const orgNameById = new Map(orgs.map((o) => [o._id.toString(), o.name]));

  const assessmentById = new Map(activeAssessments.map((a) => [a._id.toString(), a]));

  // Customers needing attention: at least one response awaiting auditor review.
  const attentionByOrg = new Map();
  for (const [assessmentId, responses] of responsesByAssessment) {
    const assessment = assessmentById.get(assessmentId);
    if (!assessment) continue;
    const submittedCount = responses.filter((r) => r.status === 'submitted').length;
    if (submittedCount === 0) continue;
    const orgKey = assessment.organisationId.toString();
    attentionByOrg.set(orgKey, (attentionByOrg.get(orgKey) || 0) + submittedCount);
  }
  const customersNeedingAttention = [...attentionByOrg.entries()].map(([organisationId, count]) => ({
    organisationId,
    organisationName: orgNameById.get(organisationId) || organisationId,
    submittedCount: count,
  }));

  const controlsNeedingReview = customersNeedingAttention.reduce((sum, c) => sum + c.submittedCount, 0);

  // Recent submissions across all active assessments, most recent first.
  const allActiveResponses = [...responsesByAssessment.values()].flat();
  const recentSubmissions = allActiveResponses
    .filter((r) => r.submittedAt)
    .sort((a, b) => new Date(b.submittedAt) - new Date(a.submittedAt))
    .slice(0, 10)
    .map((r) => {
      const assessment = assessmentById.get(r.assessmentId.toString());
      return {
        assessmentId: r.assessmentId,
        organisationId: assessment?.organisationId,
        organisationName: orgNameById.get(assessment?.organisationId?.toString()) || null,
        questionText: r.questionTextSnapshot,
        status: r.status,
        submittedAt: r.submittedAt,
      };
    });

  const now = new Date();
  const behindSchedule = activeAssessments
    .filter((a) => a.dueDate && new Date(a.dueDate) < now)
    .map((a) => ({
      assessmentId: a._id,
      organisationId: a.organisationId,
      organisationName: orgNameById.get(a.organisationId.toString()) || null,
      dueDate: a.dueDate,
    }));

  return {
    role: 'auditor',
    activeAssessmentCount: activeAssessments.length,
    customersNeedingAttention,
    recentSubmissions,
    controlsNeedingReview,
    behindSchedule,
  };
}

async function buildCustomerDashboard(organisationId) {
  const assessments = await Assessment.find({ organisationId, status: { $ne: 'draft' } });
  const assessmentIds = assessments.map((a) => a._id);
  const responsesByAssessment = await groupResponsesByAssessment(assessmentIds);

  const templateIds = [...new Set(assessments.map((a) => a.templateId.toString()))];
  const templates = await ChecklistTemplate.find({ _id: { $in: templateIds } });
  const templateNameById = new Map(templates.map((t) => [t._id.toString(), t.name]));

  const activeAssessments = await Promise.all(
    assessments
      .filter((a) => a.status === 'active')
      .map(async (a) => ({
        ...(await withProgress(a, responsesByAssessment)),
        templateName: templateNameById.get(a.templateId.toString()) || null,
      }))
  );

  const allResponses = [...responsesByAssessment.values()].flat();
  const assessmentById = new Map(assessments.map((a) => [a._id.toString(), a]));

  function toItem(r) {
    const assessment = assessmentById.get(r.assessmentId.toString());
    return {
      assessmentId: r.assessmentId,
      responseId: r._id,
      templateName: templateNameById.get(assessment?.templateId?.toString()) || null,
      questionText: r.questionTextSnapshot,
      status: r.status,
    };
  }

  const needsMyAttention = allResponses.filter((r) => r.status === 'needs_clarification').map(toItem);
  const awaitingAuditor = allResponses.filter((r) => r.status === 'submitted').map(toItem);

  const now = new Date();
  const sevenDaysOut = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
  const dueSoon = assessments
    .filter((a) => a.status === 'active' && a.dueDate && new Date(a.dueDate) >= now && new Date(a.dueDate) <= sevenDaysOut)
    .map((a) => ({
      assessmentId: a._id,
      templateName: templateNameById.get(a.templateId.toString()) || null,
      dueDate: a.dueDate,
    }));

  return {
    role: 'customer_user',
    activeAssessments,
    needsMyAttention,
    awaitingAuditor,
    dueSoon,
  };
}

dashboardRouter.get('/', async (req, res) => {
  if (req.auth.role === 'customer_user') {
    res.json(await buildCustomerDashboard(req.scopedOrganisationId));
  } else {
    res.json(await buildAuditorDashboard());
  }
});
