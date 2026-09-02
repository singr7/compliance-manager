# Screen Specification — Compliance Manager (V1)

Minimum screen count; dialogs/drawers preferred over new pages where simpler.

---

### Login
- Purpose: authenticate. Persona: both.
- Info displayed: email/password form, error message on failure.
- Primary action: Log in.
- Secondary actions: Forgot password.
- States: default, submitting, error (invalid credentials), locked (too many attempts — optional V1).
- Empty/error state: inline validation errors under fields.

### Dashboard (Auditor view)
- Purpose: "what needs my attention today."
- Info displayed: active assessments count, customers needing attention (table), recent submissions (table), controls needing review (table), assessments behind schedule (badge/table). No charts — tables, progress bars, status badges only.
- Primary action: open an assessment from any table row.
- Secondary actions: jump to Templates, jump to Customers.
- Empty state: "No active assessments yet — create one from Templates."
- Error state: retry banner if data fails to load.

### Dashboard (Customer view)
- Purpose: "what do I need to do."
- Info displayed: active assessments with % complete, "needs my attention" list (NEEDS_CLARIFICATION questions across all assessments), "awaiting auditor" list, due-soon items if a due date is set.
- Primary action: open an assessment.
- Empty state: "No active assessments." Error state: retry banner.

### Assessments (list)
- Purpose: browse all assessments (Auditor: all their customers'; Customer: their org's own).
- Info displayed: table — customer/org, template name, status badge, progress bar, last activity date.
- Primary action (Auditor): New Assessment. (Customer): none — read-only entry point.
- Secondary actions: filter by status, filter by customer (Auditor only).
- Empty state: "No assessments yet." Error state: retry banner.

### New Assessment (dialog, not a page)
- Purpose: Auditor creates an assessment.
- Fields: Template (select, required), Customer/Organisation (select, required), Assigned Auditor (select, defaults to self), optional due date.
- Primary action: Create (→ DRAFT). Secondary: Cancel.
- Validation: template + customer required; blocks duplicate active assessment of same template+customer (mirrors legacy `check_preexistance_add`).

### Assessment Overview
- Purpose: the customer/auditor's home base for one engagement — progress at a glance.
- Info displayed: progress bar + counts (Completed / Need Attention / Awaiting Review / Not Started), per-section progress list, question list grouped by section with status badges.
- Primary action: open a Question detail (drawer).
- Secondary actions (Auditor): bulk accept selected, export (optional), mark assessment complete. (Customer): none beyond opening questions.
- States: DRAFT (Auditor only, "not yet visible to customer" banner), ACTIVE, UNDER_REVIEW, COMPLETED (read-only banner).
- Empty state: n/a (template always has ≥1 question to activate). Error state: retry banner.

### Question / Response Detail (drawer/dialog over Assessment Overview — not a new page)
- Purpose: everything about one question in one place.
- Info displayed: question text, control reference, guidance text, status badge, customer's answer (per response type), evidence-required note, uploaded evidence list (filename, uploader, date, download), upload control, customer note field, auditor comment thread, submit/save button.
- Primary action (Customer): Save / Submit answer, Upload evidence.
- Primary action (Auditor): Accept / Request Clarification / Mark Non-Compliant, Add comment.
- Secondary actions: delete own not-yet-submitted evidence, reopen a submitted answer (Customer, before Auditor review).
- States: NOT_STARTED, IN_PROGRESS, SUBMITTED, NEEDS_CLARIFICATION (comment highlighted), ACCEPTED (read-only for Customer), NON_COMPLIANT (read-only, flagged).
- Empty state: no evidence yet — "Upload a file to satisfy this control." Error state: upload failure banner with retry.

### Templates (list) — Auditor only
- Purpose: manage checklist templates.
- Info displayed: table — name, category, # sections, # questions, status (Draft/Active), last updated.
- Primary action: New Template.
- Secondary actions: Duplicate, Edit, Deactivate.
- Empty state: "No templates yet." Error state: retry banner.

### Template Editor — Auditor only
- Purpose: build/edit a template's sections and questions.
- Info displayed: template name/category fields, list of sections each with its questions (text, control ref, guidance, expected evidence, required?, response type, enabled/disabled), drag or up/down reorder.
- Primary action: Save.
- Secondary actions: Add Section, Add Question (dialog), Duplicate Template (from list), Disable Question.
- States: Draft (fully editable), Active (editable but changes only affect new assessments, per workflow design decision).
- Empty state: "Add a section to get started." Error state: inline save-failure banner.

### Customers — Auditor only
- Purpose: manage client organisations.
- Info displayed: table — org name, primary contact, # active assessments, status.
- Primary action: New Customer/Organisation.
- Secondary actions: Edit, view assessments (links to filtered Assessments list).
- Empty state: "No customers yet." Error state: retry banner.

### Users — Auditor/Admin only
- Purpose: manage Auditor and Customer User accounts.
- Info displayed: table — name, email, role, organisation (for customer users), status.
- Primary action: Invite User.
- Secondary actions: Edit, deactivate.
- Empty state: "No users yet." Error state: retry banner.

### Profile / Account Settings (shared, single screen for both personas — collapses legacy's 5 duplicated Profile/Reset-Password screen pairs)
- Purpose: self-service profile edit + password change.
- Info displayed: name, email, phone (read-only email if login identity), change-password form.
- Primary action: Save. Secondary: Cancel.
- States: default, saving, error (validation).
