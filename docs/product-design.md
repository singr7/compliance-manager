# Product Design — Compliance Manager (V1)

## Product objective
Make it extremely obvious to a customer what they need to do next. Compliance Manager should feel like a simple, well-organised task tracker for an audit engagement — not an enterprise GRC suite. Every screen answers: what's asked, why, what's needed, what's been provided, is the auditor satisfied, and what's next.

## Personas
1. **Auditor / Admin** — audit-firm staff. Creates templates, creates assessments, reviews responses, requests clarification, accepts controls. Collapses the legacy's Admin + QSA + QA + Consultant into one role (see `docs/domain-model.md` — legacy evidence showed near-identical review screens across all three reviewer personas).
2. **Customer User** — belongs to one Organisation. Answers questions, uploads evidence, responds to clarification requests. Legacy's `users.parent_id` sub-account concept survives as "multiple Customer Users per Organisation," simplified to a flat org membership rather than a parent/child user tree.

## MVP scope (~14 capabilities)
Login · Organisation/Customer · Users (Auditor/Admin, Customer User) · Compliance Template · Sections · Questions/Controls · Assessment · Customer response per question · Evidence upload · Auditor review · Clarification/comments · Status · Progress tracking · Simple dashboard.

## Explicit non-goals (V1)
- No workflow/BPM engine, no microservices, no event bus, no AI agents.
- No configurable multi-stage review pipeline (legacy's QSA→QA→Consultant chain is replaced by one Auditor role).
- No separate "Testing" engagement type — one generic Template/Assessment model covers both compliance frameworks and testing checklists.
- No enterprise SSO (local auth only, until a real need is confirmed).
- No sophisticated multi-tenancy infrastructure — simple organisationId scoping.
- No document versioning beyond "latest evidence wins" (matches legacy's one-shot report upload, kept intentionally simple).
- No CMS, no generic Role-management module, no Archive module (all DROP per `docs/legacy-to-new-mapping.md`).

## Core domain concepts
Organisation, User, ChecklistTemplate, Section, Question, Assessment, AssessmentResponse, Evidence, Comment. Full definitions in `docs/domain-model.md`.

## Primary workflows
See `docs/workflows.md` for the full [NEW DESIGN] versions of: template creation, question management, assessment creation, customer questionnaire completion, evidence upload, auditor review, clarification round-trip, and completion/progress tracking.

## Screen map
Login → {Auditor Dashboard | Customer Dashboard} → Assessments (list) → Assessment Overview → Question/Response Detail (drawer/dialog, not a new page). Auditor-only: Templates (list) → Template Editor. Admin-only: Customers, Users. Full spec in `docs/screen-specification.md`.

## Navigation model
Flat, shallow hierarchy — at most 3 levels deep (Dashboard → Assessments → Assessment Overview → Question detail as an in-page drawer). No nested wizards. Sidebar: Dashboard, Assessments, (Auditor: Templates, Customers, Users), Profile.

## Status model
**Question-level**: `NOT_STARTED → IN_PROGRESS → SUBMITTED → NEEDS_CLARIFICATION (loops to IN_PROGRESS) → ACCEPTED`, with an optional terminal `NON_COMPLIANT` (justified by legacy's distinct "Disapprove" bulk action, `all_status=8`, from `Compliances.php::select_multiple_change_status` — a genuinely different outcome from "needs more info").
**Assessment-level**: `DRAFT → ACTIVE → UNDER_REVIEW → COMPLETED`. DRAFT exists for the (new) capability of building an assessment before inviting the customer; legacy has no equivalent (engagements are created "live"), but it's a small addition that materially helps the ABC Manufacturing scenario (auditor customizes the checklist before the customer ever sees it).

## Evidence workflow
Evidence is first-class: file, original filename, uploaded by, uploaded at, question link, optional description, active/current flag. Stored behind a pluggable storage abstraction (S3-compatible in cloud, filesystem/MinIO on-prem) — never coupled to a specific cloud vendor, matching legacy's local-disk-only storage but replacing the ad hoc randomized-suffix naming with (orgId, assessmentId, questionId, evidenceId) addressing, and adding real MIME/size validation (a gap confirmed absent in the legacy code).

## Auditor workflow
Create/duplicate template → build assessment for a customer → review submitted responses → accept or request clarification → mark assessment complete. Dashboard surfaces: active assessments, which customers need attention, recent submissions, which controls need review, which assessments are behind schedule.

## Customer workflow
See active assessments and % complete → open an assessment → answer questions section by section, uploading evidence where required → submit → see auditor feedback prominently when clarification is requested → resolve → done. Dashboard surfaces: active assessments, % complete, what needs my attention, what's waiting on the auditor, what's due soon.

## Checklist/template management
Auditor/Admin can: create a checklist, duplicate an existing one, create sections, add/edit/reorder questions, mark mandatory/optional, specify expected evidence, disable questions (soft-remove, doesn't affect in-flight assessments), add optional guidance text. Deliberately simple CRUD + ordering — no questionnaire-builder engine, no conditional branching logic (none was found or needed in the legacy evidence).

## Permissions
Two roles only: Auditor/Admin (full access within their firm's tenant: templates, all their customers' assessments, review actions) and Customer User (their own Organisation's assessments only — answer, upload, comment). No fine-grained per-question or per-field permission model — legacy's `permission` longtext column on `users` and the unused `roles` table are both DROP candidates; they added complexity with no observed corresponding UI enforcement.

## Notifications
Minimal, only where the workflow genuinely needs it: assessment created/assigned (email to customer contact), auditor requests clarification (email to customer), customer submits/resubmits (email to auditor). No in-app notification center required for V1.

## Deployment model
Docker Compose, no Kubernetes. Browser → React (Next-free SPA or simple React app) → Node.js/Express API → MongoDB + pluggable Evidence Storage (S3-compatible or filesystem/MinIO), Nginx reverse proxy. Same codebase supports both patterns below.

## On-premise considerations
A single-customer on-prem install may hold just one Organisation. Filesystem/MinIO storage backend, local Mongo instance, no outbound dependency on a cloud provider. Backups: mongodump + evidence-directory backup, documented but not automated in V1.

## Cloud considerations
Multi-organisation SaaS deployment: S3-compatible storage backend, environment-var-driven config for DB connection string, storage credentials, and mail provider. organisationId-scoped queries everywhere (simple multi-tenancy, not a dedicated tenancy-isolation infrastructure layer).

## Design-quality check — ABC Manufacturing scenario
Audit firm picks an existing template, removes 3 questions, adds 2 custom ones, creates an assessment, invites the customer's compliance manager. The manager logs in and immediately sees "64 questions · 18 done · 6 need evidence · 4 have comments" on the Assessment Overview. They answer a question and upload a PDF; status flips to SUBMITTED. The auditor requests one more document via a comment; the question flips to NEEDS_CLARIFICATION and is visibly flagged on the customer's dashboard under "needs my attention." The customer uploads it; the auditor accepts; progress updates automatically. Every step above maps directly to a screen and a status transition already defined in this document — no additional entities, screens, or workflow steps were required to satisfy the scenario, which is the intended simplicity bar.
