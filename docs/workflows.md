# Workflows

Each workflow: Actor, Precondition, Steps, State changes, Data created/changed, Alternative paths, Failure/edge cases. Legacy-derived workflows cite source files; new-product workflows are marked **[NEW DESIGN]**.

## 1. Auditor creates checklist/template [NEW DESIGN — legacy has no equivalent]
- **Legacy gap**: `application/modules/admin/controllers/Questionnaire.php` has no create/delete action — only `edit_question` (text) and `active_questionnaire`/`inactive_questionnaire` (bulk toggle). Templates are effectively fixed seed data per service.
- Actor: Auditor/Admin. Precondition: logged in.
- Steps: Create new template (name, category) → add sections → add questions to sections (text, control ref, guidance, expected evidence, required?, response type) → save as Draft or Active.
- State changes: Template created in Draft; becomes usable for new Assessments once Active.
- Data created: ChecklistTemplate, Section(s), Question(s).
- Alt paths: **Duplicate existing template** then edit (explicitly required by product design) — copies all sections/questions as a new template.
- Failure/edge cases: template with zero questions cannot be activated; duplicate template names allowed but discouraged with a UI warning.

## 2. Auditor modifies/adds questions [NEW DESIGN, legacy only supports text-edit + toggle]
- Actor: Auditor/Admin. Precondition: template exists (Draft, or Active with care).
- Steps: add question to a section / edit question fields / reorder within section (drag or up-down) / mark mandatory or optional / disable (soft-remove without breaking existing assessments already using the template).
- State changes: Question added/edited/reordered/disabled.
- Data changed: Question row(s), Section.questionOrder.
- Alt paths: editing a question already in use by an active Assessment does not retroactively change already-submitted responses (versioning-light: the response snapshot keeps the question text at time of assessment creation — **design decision**, since legacy shows no precedent either way).
- Failure/edge cases: disabling a question that has in-progress responses should not delete those responses, only hide it from new assessments.

## 3. Auditor creates customer assessment
- **Legacy**: `admin/controllers/Compliances.php::add_compliances` — Actor: Admin. Precondition: Customer, Process, and at least one available QSA/Consultant/QA user exist.
- Steps: choose Customer → Process (AJAX-populated) → QSA → Consultant → QA → Start Date → Submit. System checks `check_preexistance_add` to block a duplicate (same service+process+customer).
- State changes: new `compliance_project` row, status defaults to 0 (open/in-progress).
- Data created: `compliance_project` (KEEP as Assessment in new model, minus the three-reviewer assignment — see below).
- **[NEW DESIGN variant]**: Auditor picks a Template + Customer + (optionally one) assigned Auditor reviewer, no Process step, no three-way QSA/Consultant/QA assignment — a single Auditor/Admin role owns the assessment.
- Failure/edge cases: legacy blocks duplicate process+customer+service combos; new design should similarly warn/block duplicate active assessments of the same template for the same customer.

## 4. Auditor assigns assessment / invites customer
- **Legacy**: assignment happens implicitly at creation time (qsa_id/consultant_id/qa_id fields); no explicit "invite customer" step was found — customer accounts are pre-existing (`admin/customer/*` CRUD), so **LIKELY** the customer simply sees the new engagement appear on next login rather than receiving an active invite. **UNCLEAR** whether email notification fires on creation (`_sendMailPhpMailer` exists but call site not traced to `add_compliances`).
- **[NEW DESIGN]**: creating an assessment optionally sends the customer contact an email notification; assessment is immediately visible on their dashboard.

## 5. Customer completes questionnaire
- **Legacy**: `customer/controllers/Process.php` + `customer/views/process/customer_process_details.php` — Actor: Customer. Precondition: assessment exists for their org.
- Steps: open process/engagement → view questionnaire list → answer/respond per question → (see workflow 6 for evidence).
- State changes: response recorded against `uplaod_evidence` row for that question/process/service/customer (status starts un-set/"in progress").
- Data created/changed: `uplaod_evidence` row per question.
- **[NEW DESIGN]** explicit status: NOT_STARTED → IN_PROGRESS → SUBMITTED once the customer marks a question done.
- Failure/edge cases: legacy shows no required-field validation distinguishing mandatory vs optional questions (flat list) — new design must enforce `Question.required` before allowing SUBMITTED.

## 6. Customer uploads evidence
- **Legacy**: `customer/controllers/Process.php::upload_evidences` (view `upload_evidences.php`) — file(s) attached per question, written to `uplaod_evidence.docs`/`common_uplaod_docs`, stored under `./uploads/...` with a randomized filename suffix.
- State changes: evidence attached to the question's response row.
- Data created: Evidence record (file, uploader, timestamp, question link).
- Alt paths: multiple files per question appear supported (`common_uplaod_docs` as a separate multi-doc table alongside the single `docs` field on `uplaod_evidence` — **LIKELY** `uplaod_evidence.docs` is the primary/first file and `common_uplaod_docs` holds additional ones, not fully confirmed).
- Failure/edge cases: no file type/size validation observed in the code read — **[NEW DESIGN]** must add server-side MIME/size validation (explicit product requirement).

## 7. Auditor reviews response
- **Legacy**: the reviewer-side views (`consultant_view.php`, `qa_view.php`, `qsa_view.php`, and Admin's `view_compliances.php`) all render the same question+evidence+comment list, each with role-scoped action buttons wired to `Compliances.php`.
- Steps: reviewer opens assessment → inspects question, evidence, existing comments → chooses Approve / Disapprove / Mark Incomplete (bulk, `select_multiple_change_status`, `all_status` 7/8/9) and/or leaves a role-specific comment.
- State changes: `uplaod_evidence.admin_status`/`qa_status`/`all_status` updated; a row added to the relevant `*_comments` table.
- **[NEW DESIGN]**: single Auditor role, single action set (Accept / Request Clarification), single unified Comment thread — collapses four parallel reviewer flows into one.

## 8. Auditor requests clarification
- **Legacy**: modeled implicitly via a comment left in the reviewer's comment table (`qsa_comments`/`qa_comments`/`consultant_comments`) plus (LIKELY) leaving the question's status short of Approved; no distinct "NEEDS_CLARIFICATION" status value was confirmed — inferred from the "Mark Incomplete" (`all_status=9`) bulk action, which is the closest legacy analogue.
- **[NEW DESIGN]**: explicit `NEEDS_CLARIFICATION` question status, triggered by a comment + status change together, so it's unambiguous to the customer what's being asked of them.

## 9. Customer supplies clarification / new evidence
- **Legacy**: customer re-visits `upload_evidences`/`view_evidence` and adds another file/comment; the four `accepttoqsa`/`accepttoqa`/`AcceptToConsultant`/`accepttocustomer` handlers in `Compliances.php` appear to be the mechanism by which a "modification request" round-trip is closed out ("Modification request accepted successfully" flash message) — **LIKELY** this is the accept-the-customer's-resubmission step for each reviewer role respectively.
- **[NEW DESIGN]**: customer adds evidence/comment → question flips back to SUBMITTED → auditor re-reviews.

## 10. Auditor accepts/rejects/marks control
- Legacy: `select_multiple_change_status` (bulk Approve/Disapprove/Mark Incomplete) and the four `accept*` per-role handlers — see workflows 7–8.
- **[NEW DESIGN]**: ACCEPTED is terminal-positive; NEEDS_CLARIFICATION loops back to customer; optionally NON_COMPLIANT as a terminal-negative if legacy's "Disapprove" (all_status=8) genuinely needs a distinct terminal state rather than looping back — **recommend keeping NON_COMPLIANT** since "Disapprove" in legacy is a bulk, final-sounding action distinct from "Mark Incomplete."

## 11. Assessment completion
- **Legacy**: `Admin_status_change()` helper computes "Approved" only when every question's `admin_status` is set; end date can be manually set (`EndDateformAdmin`); AOC/ROC report file is then uploaded (`FileUploadAOC`/`FileUploadROC`, one-shot).
- **[NEW DESIGN]**: Assessment auto-transitions to COMPLETED when all required questions are ACCEPTED; auditor may still manually mark complete with outstanding NON_COMPLIANT items (documented override, not silently blocked).

## 12. Progress/status tracking
- **Legacy**: computed ad hoc per view via helper functions (`Admin_status_change`, `Admin_status_approved_change`) that loop over questions and count matching statuses — no persisted/cached progress field, recomputed on every page load.
- **[NEW DESIGN]**: same on-the-fly computation is fine at MVP scale (small question counts) — Assessment Overview shows counts of Completed / Need Attention / Awaiting Review / Not Started, computed from the AssessmentResponse collection, no denormalized counters needed for V1.
