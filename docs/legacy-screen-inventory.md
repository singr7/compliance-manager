# Legacy Screen Inventory

Field capture template used throughout: **Field / Type / Required / Possible values / Source of values / Validation / Conditional behaviour / Stored as / Business meaning.**
Styling is ignored; only structure/function is captured. Evidence: CONFIRMED (view/controller read) or LIKELY (inferred from controller + naming convention, view not fully line-read).

---

## Admin — Auth

### Screen: Admin Login
- Route: `admin/auth/login` · Controller/Action: `Auth::login` (module `admin`) · Persona: all staff personas actually log in through the admin-branded auth flow (`is_adminprotected()` gate in `function_helper.php`) · Purpose: authenticate staff/customer users.
- Fields (CONFIRMED existence, LIKELY exact names): email/username (text, required), password (password, required), possibly captcha (`getcaptchacode()` exists in `function_helper.php`).
- Buttons: Login, Forgot Password link.
- Navigation: on success → role-specific dashboard (`admin/dashboard`, `consultant/dashboard`, `customer/dashboard`, `qa/dashboard`, `qsa/dashboard`) based on `user_type`.
- Underlying entity: `users`.

### Screen: Forgot Password
- Route: `admin/auth/forgot` · Purpose: password reset request. Field: email (text, required). LIKELY sends OTP/reset link (`generate_otp()`, `send_otp()` helpers exist).

---

## Admin — Compliances (core engagement workflow)

### Screen: Compliances Listing
- Route: `admin/compliances/compliances_listing?id={service}` · Controller: `Compliances::compliances_listing` · Persona: Admin · Purpose: list all compliance engagements (compliance_project rows) for a given service.
- Table columns (CONFIRMED from `project_list($id)` query context + view existence): Customer, Process, QSA, Consultant, QA, Start Date, End Date, Status, Actions (View/Edit/Delete).
- Buttons: Add Compliance, Edit, Delete, View.
- Underlying entities: `compliance_project`, `process`, `services`, `users` (staff joins).

### Screen: Add Compliance
- Route: `admin/compliances/add_compliances?id={service}` · Controller: `Compliances::add_compliances`.
- Fields:
  | Field | Type | Required | Possible values | Source | Validation | Stored as | Meaning |
  |---|---|---|---|---|---|---|---|
  | customer_id | select | Yes | active customers | `customer_list()` | `required` | `compliance_project.customer_id` | which client org |
  | process_id | select | Yes | processes of chosen customer, AJAX-loaded via `get_customer_process` | `get_customer_process()` | `required` | `.process_id` | which engagement container |
  | qsa_id | select | Yes | active QSA users | `qsa_list()` | `required` | `.qsa_id` | assigned QSA reviewer |
  | consultant_id | select | Yes | active consultants | `consulants_list()` | `required` | `.consultant_id` | assigned consultant |
  | qa_id | select | Yes | active QA users | `qa_list()` | `required` | `.qa_id` | assigned QA reviewer |
  | start_date | date text | Yes | any | free entry | `required` | `.start_date` (stored as varchar!) | engagement start |
  | end_date | date text | No | any | free entry | none observed | `.end_date` | engagement end, set later via `EndDateformAdmin` |
- Conditional behaviour: server rejects submit if `(process_id, customer_id)` pair already has a compliance project for this service (`check_preexistance_add`) — duplicate-prevention business rule.
- Business meaning: this is effectively "assign a compliance framework + review team to a customer's process."

### Screen: Edit Compliance
- Route: `admin/compliances/edit_compliances?id={id}&service_id={sid}` · Same fields as Add, pre-filled; same duplicate-check (`check_preexistance`) excluding self.

### Screen: View Compliance (the questionnaire response/review screen — CENTRAL screen)
- Route: `admin/compliances/view_compliances?service_id=&cus_id=&process_id=` · Controller: `Compliances::view_compliances`.
- Displays: engagement start date, ROC/AOC upload state (`get_data_roc`/`get_data_aoc`), service name, the full `questionnaire` list for the service, and a "disabled button" flag (`check_disabledbutton`) that appears to lock further edits once fully approved.
- Per-question row (CONFIRMED from surrounding controller methods, table layout LIKELY): question text, uploaded evidence/docs list, per-role status badges (admin_status/qa_status/all_status), a comment thread pulled from `all_comments`/`qsa_comments`/`qa_comments`/`consultant_comments`, and a checkbox for bulk selection.
- Buttons/Actions: per-row or bulk **Approve / Disapprove / Mark Incomplete** (`select_multiple_change_status`, all_status ∈ {7,8,9}), **Accept modification request** variants scoped per role (`accepttocustomer`, `accepttoqsa`, `accepttoqa`, `AcceptToConsultant`), **Upload AOC** / **Upload ROC** file pickers (`FileUploadAOC`/`FileUploadROC`, one-shot — rejected if already uploaded), **Set End Date** (`EndDateformAdmin`).
- Business meaning: this is the audit review desk — the single screen where all evidence + comments + approvals for one customer's one-service engagement converge. It is the direct ancestor of the new product's "Assessment Overview + Question Detail" screens.

### Screen: get_user_process (AJAX partial)
- Route: `admin/compliances/get_customer_process` (POST, ajax) · Purpose: populate the Process dropdown based on selected Customer. Not a full screen — a cascading-select data source.

---

## Admin — Questionnaire

### Screen: Questionnaire Listing
- Route: `admin/questionnaire/questionnaire_list?id={service}` · Controller: `Questionnaire::questionnaire_list`.
- Table columns: checkbox (bulk select), question text, status (active/inactive), Edit action.
- Buttons: **Activate Selected**, **Deactivate Selected** (`active_questionnaire`/`inactive_questionnaire`, bulk by `ids` query param), Edit.
- **No Add/Delete question button found** in this controller — questions appear to be fixed/seeded per service, only editable text + enable/disable toggle, not created/removed via UI (CONFIRMED absence in `Questionnaire.php`; **UNCLEAR** if another entry point exists).
- Underlying entity: `questionnaire`.

### Screen: Edit Question
- Route: `admin/questionnaire/edit_question?id={qid}` · Field: question (textarea, required, `required` rule) · Stored as `questionnaire.question` · Redirects back to listing for the question's own `service_id`.

---

## Admin — Testing (parallel structure to Compliances)

### Screens: Testing Listing / Add / Edit / View
- Routes: `admin/testing/testing_list`, `add_testing_project`, `edit_testing_project`, `view_testing_project` (views present: `testing_list.php`, `add_testing_project.php`, `edit_testing_project.php`, `view_testing_project.php`).
- LIKELY (structure mirrors Compliances exactly, per `testing_project` table columns identical to `compliance_project` with `testing_id` replacing `service_id`): same field set — customer, process, qsa, consultant, qa, start_date, end_date — and a similar per-question/per-item review-and-approve screen for the seven `testing` types (ASV, External PT, Internal PT, Internal VA, Application PT, Segmentation PT, Card Data Scan).
- This is flagged in `docs/legacy-to-new-mapping.md` as a **near-total duplication** of the Compliances flow — the new product should not build two parallel engagement types.

---

## Admin — Customer / Consultants / Qas / Qsa (staff & client directory screens)

### Screens: {Customer|Consultants|Qas|Qsa} Listing / Add / Edit / View
- Routes: `admin/customer/customer_listing` (+ `add_customer`, `edit_customer`, `view_customer`, `add_sub_customer`, `edit_sub_customer`, `sub_customer_list`, `asign_process`, `view_process`); analogous for `admin/consultants/*`, `admin/qas/*` (QA), `admin/qsa/*`.
- Common fields across all four (CONFIRMED pattern from `users` table shape): full_name, email, phone_number, company_name, company_number, address, status (active/inactive/delete), password (on add). Required: name/email/password on create; validation LIKELY includes email-format and uniqueness (not directly traced).
- Customer-specific: **sub-customer** add/edit/list — a customer can have child user accounts under it (`users.parent_id`), used to represent multiple named users at one client company. `asign_process` — assigns/creates a `process` for a customer. `view_process` — shows all processes/engagements for that customer.
- Business meaning: this is staff/client directory management — CRUD on the five `user_type` personas, largely boilerplate CRUD screens with no unique domain logic beyond the customer sub-account hierarchy and process assignment.

---

## Admin — Role, Cms, Archive, Dashboard, Profile

### Screens: Role Listing / View
- Routes: `admin/role/listing`, `admin/role/view` (+ a stray `view_1.php` — **LIKELY dead/duplicate view file**, a code smell).
- Backed by `roles` table (Administrator/Employee/Supplier/Sub-contractors) which does **not** correspond to the actual `user_type` persona system (admin/qsa/qa/consultant/customer) — **LIKELY vestigial/unused feature**, candidate DROP.

### Screens: Cms Listing / Add / Edit / View
- Routes: `admin/cms/listing`, `add`, `edit`, `view` — generic CMS/static-page content management, fields LIKELY: title, slug, content (rich text), status. No connection found to the compliance domain — **LIKELY leftover from a generic CI admin boilerplate/starter kit**, candidate DROP.

### Screen: Archive Listing
- Route: `admin/archive/archive_listing` — **UNCLEAR purpose**; LIKELY an audit trail or soft-deleted-record view, not confirmed against a specific table.

### Screen: Admin Dashboard
- Route: `admin/dashboard/site_dashboard` — LIKELY summary counts (customers, engagements, pending reviews) — exact widgets not confirmed line-by-line, but this is the direct ancestor of the new product's Auditor Dashboard.

### Screens: Profile / Reset Password
- Routes: `admin/profile/profile`, `admin/profile/reset_password` — standard self-service profile edit + password change, present identically in every persona module (`consultant`, `customer`, `qa`, `qsa`).

---

## Customer module

### Screen: Customer Dashboard
- Route: `customer/dashboard/site_dashboard` — LIKELY lists the customer's processes/engagements and their status/progress at a glance. Direct ancestor of the new Customer Dashboard.

### Screen: Customer Process Detail
- Route: `customer/process/customer_process_details` (view: `customer_process_details.php`) — the customer-facing counterpart to Admin's `view_compliances`: shows the questionnaire for the customer's engagement, their answers/evidence, and reviewer comments.

### Screen: Service Detail
- Route: `customer/process/service_details` — LIKELY a picker/summary of which compliance service(s) apply to a given process before drilling into questions.

### Screen: Upload Evidence
- Route: `customer/process/upload_evidences` (view: `upload_evidences.php`) · Fields: file upload (required per question, type/size validation **UNCLEAR** — not observed), optional note/comment text. Stored to `uplaod_evidence.docs` / `common_uplaod_docs`. This is the direct ancestor of the new product's Evidence upload control.

### Screen: View Evidence
- Route: `customer/process/view_evidence` — read-only list of previously uploaded evidence for a question, with reviewer status/comments alongside.

### Screens: Attestations Dashboard / Details / Report
- Routes: `customer/attestaions/attestaions_dashboard`, `attestaions_details`, `attestaions_report` — LIKELY: dashboard lists engagements ready for/pending attestation sign-off; details shows the attestation content; report renders/downloads the final AOC/ROC-style document. This is the ancestor of a "final report / sign-off" concept, but is **UNCLEAR** whether it's a distinct workflow step or just a read view over `report_aoc_roc`.

### Screen: Customer Auth (Login / Forgot)
- Routes: `customer/auth/login`, `customer/auth/forgot` — same shape as Admin auth; **UNCLEAR** why customer has its own separate Auth controller when `is_adminprotected()` is the shared gate — LIKELY historical duplication, not two genuinely different auth mechanisms.

---

## Consultant / QA / QSA modules

### Screens: {Consultant|QA|QSA} Dashboard
- Routes: `{consultant|qa|qsa}/dashboard/site_dashboard` — LIKELY each lists engagements assigned to that staff member (by `qsa_id`/`consultant_id`/`qa_id` FK on `compliance_project`/`testing_project`) with a pending-review count.

### Screens: {Consultant|QA|QSA} Process View
- Routes: `consultant/consultant/process/consultant_view`, `qa/qa/process/qa_view`, `qsa/qsa/process/qsa_view` — the reviewer-side counterpart of Admin's `view_compliances`: same question/evidence/comment list, but scoped to the logged-in reviewer's own role, with that role's Accept/Comment action wired to `accepttoqsa`/`accepttoqa`/`AcceptToConsultant` in `Compliances.php`. **LIKELY near-identical view markup across the three roles** (a strong SIMPLIFY signal for the new product: one Reviewer role, not three).

### Screens: Profile / Reset Password (×3)
- Identical pattern to Admin's, one copy per module — pure duplication, a DROP-and-consolidate candidate in the new codebase (not a business-logic DROP, just an implementation-simplification note).

---

## Cross-cutting observations for the new product

1. **One review screen appears five times** (Admin, Consultant, QA, QSA each render essentially the same question/evidence/comment list with role-scoped actions; Customer renders the answer-side equivalent). This strongly supports collapsing QSA/QA/Consultant into a single **Auditor/Reviewer** role in the new product (see `docs/domain-model.md`).
2. **Compliances and Testing are the same screen set twice.** The new product should have one generic "Checklist Template → Assessment" concept, not two parallel engagement types.
3. **Profile/Reset Password is duplicated five times** with no variation — a single shared account-settings screen suffices.
4. **No question-authoring UI was found** (only edit text + enable/disable) — the new product's requirement for auditors to create/duplicate/reorder checklists is a genuine improvement over the legacy system's fixed, seed-only question sets.
