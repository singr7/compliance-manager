# Data Model V1 — MongoDB Collections

Derived from the domain model, not copied from `panacea.sql`. Embedding vs referencing decisions are justified per collection.

## `organisations`
```js
{
  _id, name, status: "active"|"inactive",
  createdAt, updatedAt, createdBy
}
```
Referenced (not embedded) by users and assessments — an org can have many users and many assessments; embedding would duplicate and bloat.

## `users`
```js
{
  _id, fullName, email, phoneNumber,
  passwordHash,                // bcrypt/argon2 — replaces legacy MD5 + plaintext pwdstring (DROP)
  role: "auditor" | "customer_user",
  organisationId: ObjectId | null,   // null for auditor/admin (firm-side), set for customer_user
  status: "active" | "inactive",
  createdAt, updatedAt, lastLoginAt
}
```
Referenced by organisationId. Two-role enum replaces legacy's 5-value `user_type` + unused `roles` table.

## `checklistTemplates`
```js
{
  _id, name, category, status: "draft"|"active",
  sections: [
    {
      sectionId, title, order,
      questions: [
        {
          questionId, text, controlRef, guidance,
          expectedEvidence, required: Boolean,
          responseType: "yes_no_na" | "short_text" | "long_text" | "file_required",
          enabled: Boolean, order
        }
      ]
    }
  ],
  createdBy, createdAt, updatedAt
}
```
**Embedding decision**: sections and questions are embedded inside the template. Justification: they are always read/written together (the whole template editor loads/saves as one unit, matching the legacy pattern where the question list is always fetched per-service in one query), question counts are small (legacy seed data: ~10 questions per service), and questions never need to be queried independently of their template. This avoids the join-heavy, always-together access pattern that plagued the legacy flat `questionnaire` table (no sections at all, one giant per-service list).

## `assessments`
```js
{
  _id, templateId, organisationId,
  assignedAuditorId,
  status: "draft"|"active"|"under_review"|"completed",
  dueDate, createdAt, updatedAt, completedAt,
  createdBy
}
```
Referenced (not embedded) — an assessment's responses are numerous and independently updated/queried (progress bars, filters), so they live in their own collection. This directly replaces legacy's `compliance_project` + `testing_project` duplication with one collection, and drops the `process` indirection layer (see `docs/domain-model.md` — Process's business value was unconfirmed).

## `assessmentResponses`
```js
{
  _id, assessmentId, questionId, sectionId,   // denormalized questionId/sectionId snapshot from the template at assessment-creation time
  questionTextSnapshot, controlRefSnapshot,   // frozen copy so later template edits don't retroactively change what the customer answered
  status: "not_started"|"in_progress"|"submitted"|"needs_clarification"|"accepted"|"non_compliant",
  answer: { type: String, value: Mixed },     // shape depends on question.responseType
  customerNote: String,
  submittedAt, reviewedAt, reviewedBy,
  createdAt, updatedAt
}
```
One document per (assessment, question) pair — this is the direct successor of legacy's `uplaod_evidence` fact table, but cleanly split: evidence and comments move to their own collections instead of being crammed into the same row alongside three separate reviewer-status booleans (`admin_status`, `qa_status`, `all_status`). Indexed on `(assessmentId, status)` for the overview's progress counts.

## `evidence`
```js
{
  _id, assessmentResponseId, // or (assessmentId, questionId) — see note
  originalFilename, storageKey, mimeType, sizeBytes,
  uploadedBy, uploadedAt,
  description, isActive: Boolean
}
```
Referenced from `assessmentResponses` by `assessmentResponseId`; the actual file bytes live in the pluggable storage abstraction (S3-compatible / filesystem-MinIO), never in Mongo. Replaces legacy's split-brain `uplaod_evidence.docs` + `common_uplaod_docs` with one collection supporting multiple files per response, each independently described and timestamped — a strict superset of legacy capability with none of the ambiguity.

## `comments`
```js
{
  _id, assessmentResponseId,
  authorId, authorRole: "auditor" | "customer_user",
  text, createdAt
}
```
Replaces legacy's four parallel tables (`all_comments`, `qsa_comments`, `qa_comments`, `consultant_comments`) with one collection, filterable/sortable by `assessmentResponseId` and displayed as a single chronological thread — this is the single biggest structural simplification versus the legacy schema.

## `evidenceReports` (final AOC/ROC-style artifact)
```js
{
  _id, assessmentId, reportType: String, // e.g. "AOC", "ROC" — free text, not a hardcoded enum, since categories vary by template
  storageKey, originalFilename,
  uploadedBy, uploadedAt, version: Number
}
```
Small collection, one-to-many with assessment (versioned, unlike legacy's rigid one-shot upload-or-reject model in `report_aoc_roc`).

## Indexes
- `users.email` unique.
- `assessments.organisationId`, `assessments.status`.
- `assessmentResponses.assessmentId + status` (compound, drives the Assessment Overview progress counts).
- `evidence.assessmentResponseId`.
- `comments.assessmentResponseId`.

## Deliberately NOT modeled as collections
- Legacy `roles` (Administrator/Employee/Supplier/Sub-contractors) — dropped, replaced by the two-value `users.role` enum.
- Legacy `cms_*`, `archive`, `country` — dropped, no confirmed V1 need.
- Legacy `process` — folded away; an Assessment references an Organisation directly. If a real "which system/business-unit is in scope" need is confirmed later, add a `scopeNote` string field to `assessments` rather than reviving a separate collection.
