# MVP Backlog — Vertical Slices

8 vertical slices, each independently deployable/demoable.

## 1. Auth + Organisations
- **User outcome**: an auditor can log in; an auditor can create a customer organisation and invite a customer user who can also log in.
- **Frontend**: Login screen, Organisations list + New Organisation dialog, Users list + Invite User dialog, Profile/Account Settings screen.
- **Backend**: `/auth/*`, `/organisations`, `/users` endpoints; bcrypt hashing; JWT/session issuance; org-scoping middleware.
- **Data changes**: `users`, `organisations` collections created with indexes (`users.email` unique).
- **Acceptance criteria**: auditor and customer_user can each log in and see role-appropriate empty dashboards; a customer_user cannot see another org's data; passwords are never stored or logged in plaintext.

## 2. Template creation
- **User outcome**: an auditor can build a checklist template from scratch (sections + questions) and activate it.
- **Frontend**: Templates list, Template Editor (add section, add/edit/reorder/disable question, mark required, response type, guidance).
- **Backend**: `/templates`, `/templates/:id/sections`, `.../questions` endpoints.
- **Data changes**: `checklistTemplates` collection (embedded sections/questions).
- **Acceptance criteria**: a template with ≥1 section and ≥1 question can be created and activated; activating a template with zero questions is blocked; duplicating a template (slice 2b, can ship same sprint) copies all sections/questions into a new Draft template.

## 3. Assessment creation
- **User outcome**: an auditor picks an active template and a customer org and creates an assessment for them.
- **Frontend**: Assessments list, New Assessment dialog.
- **Backend**: `/assessments` create + list; duplicate-active-assessment guard.
- **Data changes**: `assessments` collection; on creation, snapshot the template's questions into `assessmentResponses` (one per question, status `not_started`).
- **Acceptance criteria**: creating an assessment produces one `assessmentResponses` row per template question; a duplicate active assessment for the same template+org is rejected with a clear error.

## 4. Customer questionnaire
- **User outcome**: a customer user opens an assessment, sees the section-grouped question list, and answers questions.
- **Frontend**: Assessment Overview (progress bar + counts + per-section list), Question/Response Detail drawer (answer field per response type, save/submit).
- **Backend**: `GET /assessments/:id`, `GET/PATCH .../responses/:id`.
- **Data changes**: `assessmentResponses.answer`/`status` updates.
- **Acceptance criteria**: required questions cannot be submitted without an answer; progress counts on the Overview update immediately after a submit; a customer only sees their own org's assessments.

## 5. Evidence upload
- **User outcome**: a customer uploads a file against a question that requires evidence.
- **Frontend**: upload control + evidence list inside Question Detail.
- **Backend**: `POST/GET .../evidence`, storage abstraction (local filesystem for dev/on-prem, S3-compatible for cloud), `GET /evidence/:id/download`.
- **Data changes**: `evidence` collection; files never stored in Mongo, only metadata + storageKey.
- **Acceptance criteria**: MIME allow-list and configurable size limit enforced server-side; a `file_required` question cannot be marked submitted without at least one active evidence record; download is scoped to the owning org + assigned auditor only.

## 6. Auditor review + clarification
- **User outcome**: an auditor reviews a submitted response, accepts it or requests clarification with a comment; the customer sees and resolves the request.
- **Frontend**: review actions in Question Detail (Auditor view), comment thread (shared component for both personas), "needs my attention" surfacing on the customer's Question Detail/Overview.
- **Backend**: `POST .../responses/:id/review`, `POST/GET .../comments`.
- **Data changes**: `assessmentResponses.status`/`reviewedBy`/`reviewedAt`; `comments` collection.
- **Acceptance criteria**: requesting clarification always requires a comment (enforced server-side); the status transition is visible on both the Assessment Overview and the Customer Dashboard's "needs my attention" list without a page refresh being required to discover it (poll or refetch-on-focus is acceptable for V1, no real-time infra required).

## 7. Progress + dashboard
- **User outcome**: both personas get a role-appropriate dashboard answering "what's next."
- **Frontend**: Auditor Dashboard (active assessments, customers needing attention, recent submissions, controls needing review, behind-schedule), Customer Dashboard (active assessments + %complete, needs-my-attention, awaiting-auditor, due-soon).
- **Backend**: `GET /dashboard` (role-aware aggregation query).
- **Data changes**: none new — computed from existing collections; confirm indexes support the aggregation without full collection scans at expected V1 scale.
- **Acceptance criteria**: dashboard reflects the ABC Manufacturing scenario numbers correctly (e.g., a newly created 64-question assessment with 18 accepted responses shows 18/64 and the right per-status counts) within one request round-trip, no client-side recomputation of counts across pages.

## 8. Deployment hardening
- **User outcome**: the whole stack runs via `docker compose up` in both an on-prem (filesystem storage) and cloud-like (S3-compatible storage, env-configured) mode.
- **Frontend**: environment-driven API base URL, no hardcoded hosts.
- **Backend**: Dockerfile + docker-compose.yml (API, Mongo, Nginx, and either MinIO or S3 config via env), health check endpoint, structured logging (no PHI/secrets in logs), configurable file-size limits via env, HTTPS-ready Nginx config (cert paths configurable, not baked in).
- **Data changes**: none — this slice is infra-only, but confirms all prior collections/indexes are created via a startup migration/init script rather than manual setup.
- **Acceptance criteria**: `docker compose up` brings up a working stack against a clean database; switching the storage backend env var from filesystem to S3-compatible requires no code change, only config; no secret or credential is committed to the repo.
