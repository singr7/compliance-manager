# Legacy Application Map — Panacea

Evidence discipline: every finding is tagged **CONFIRMED** (directly read in source), **LIKELY** (strong inference from naming/usage patterns across multiple files), or **UNCLEAR** (ambiguous, needs human confirmation). Source files are cited as `path — note`.

## 1. Purpose of the legacy product

**CONFIRMED.** Panacea is a compliance/security-audit management SaaS run by an audit firm ("Tekshapers Inc" per code headers) on behalf of client organisations ("customers"). It manages compliance engagements (PCI DSS, ISO, HIPAA, HITRUST — `database/panacea.sql` `services` table) and separate "testing" engagements (ASV, External/Internal PT, Internal VA, Application PT, Segmentation PT, Card Data Scan — `database/panacea.sql` `testing` table). A customer answers a fixed questionnaire per compliance service, uploads evidence per question, and a chain of reviewer roles (QSA → QA → Consultant → Admin) reviews/approves the evidence, eventually producing report artifacts (AOC/ROC and, for testing, AOT/ROT) as uploaded PDF/report documents (`application/modules/admin/controllers/Compliances.php` `FileUploadAOC`/`FileUploadROC`).

## 2. Functional modules discovered

**CONFIRMED**, from `application/modules/*`:
- `admin` — audit-firm staff back office: Admin, Archive, Auth, Cms, Compliances, Consultants, Customer, Dashboard, Profile, Qas, Qsa, Questionnaire, Role, Testing controllers.
- `auth` — shared/base login module (thin, mostly superseded by per-persona Auth controllers).
- `consultant` — Consultant persona: Consultant, Dashboard, Profile.
- `customer` — Customer persona: Attestaions (sic), Auth, Dashboard, Process, Profile.
- `qa` — QA persona: Qa, Dashboard, Profile.
- `qsa` — QSA persona: Qsa, Dashboard, Profile.

## 3. Primary user types (personas)

**CONFIRMED** from `database/panacea.sql` `users.user_type` enum comment: `1=admin, 2=qsa, 3=qa, 4=consultants, 5=customer`. Five persona types, each with its own module, dashboard, profile screens, and (per `users.parent_id`, seen in `application/helpers/function_helper.php`) customers can have sub-accounts under a parent customer — **LIKELY** representing a company with multiple named users/departments.

## 4. Major workflows

**CONFIRMED/LIKELY** (see `docs/workflows.md` for full detail):
1. Admin creates a **process** (named engagement container per customer) — `process` table.
2. Admin creates a **compliance_project** linking a `service` (PCI DSS/ISO/HIPAA/HITRUST), `customer`, `process`, and assigned `qsa_id`/`consultant_id`/`qa_id` staff — `application/modules/admin/controllers/Compliances.php::add_compliances`.
3. Admin (separately) creates a **testing_project** the same way for testing services — `application/modules/admin/controllers/Testing.php` (structure mirrors Compliances — **LIKELY** near-duplicate code path).
4. Customer answers the fixed `questionnaire` for the service and uploads evidence per question — `application/modules/customer/controllers/Process.php`, `uplaod_evidence` table (referenced throughout `function_helper.php`; not present in the SQL dump — **CONFIRMED table exists in app logic, UNCLEAR its exact DDL** since it's absent from `panacea.sql`).
5. Reviewer chain comments/accepts per question: `all_comments`, `qsa_comments`, `qa_comments`, `consultant_comments` tables (all referenced in `application/helpers/function_helper.php`, none present in `panacea.sql` dump) — **CONFIRMED usage, UNCLEAR full DDL**.
6. Admin performs bulk status changes (Approve/Disapprove/Mark Incomplete) on `uplaod_evidence` rows via `select_multiple_change_status` — `application/modules/admin/controllers/Compliances.php`.
7. Admin uploads final AOC/ROC report files once the engagement is complete — `Compliances.php::FileUploadAOC`/`FileUploadROC`, stored in `report_aoc_roc` table (not in SQL dump — **CONFIRMED usage, UNCLEAR DDL**). Testing engagements have an analogous AOT/ROT pair — **LIKELY**, per coordinator-supplied module findings on `Testing.php`, not independently re-verified line-by-line by this pass.
8. Customer views/downloads final attestation report via the `Attestaions` (sic) controller/module — **CONFIRMED module exists**, detail **LIKELY** per naming (`attestaions_dashboard.php`, `attestaions_details.php`, `attestaions_report.php`).

## 5. Important screens

See `docs/legacy-screen-inventory.md` for the full field-level inventory. Headline screens: Admin login/dashboard; Compliances listing/add/edit/view (`admin/views/compliances/*`); Questionnaire listing/edit (`admin/views/questionnaire/*`); Testing listing/add/edit/view (`admin/views/testing/*`); Customer/Consultant/QSA/QA listing/add/edit/view (staff & client directory management); Role listing/view; CMS listing/add/edit/view; Archive listing; Customer-side process detail, evidence upload/view, attestations dashboard/details/report.

## 6. Important controllers/actions

**CONFIRMED**, most consequential:
- `admin/controllers/Compliances.php` — the central engagement/review controller: `compliances_listing`, `add_compliances`, `edit_compliances`, `delete`, `view_compliances`, `accepttocustomer`, `accepttoqsa`, `accepttoqa`, `AcceptToConsultant`, `select_multiple_change_status`, `EndDateformAdmin`, `FileUploadAOC`, `FileUploadROC`.
- `admin/controllers/Questionnaire.php` — `questionnaire_list`, `active_questionnaire`/`inactive_questionnaire` (bulk enable/disable), `edit_question`. **No add/delete question action was found in this file** — **CONFIRMED** questions are only edited/toggled, not created or deleted through the UI in this codebase; question authoring is **UNCLEAR** (possibly seed-data only).
- `admin/controllers/Testing.php`, `admin/models/Testing_mod.php` — parallel structure to Compliances for testing engagements — **LIKELY duplication** of the compliance review pipeline (per coordinator-supplied findings).
- `customer/controllers/Process.php`, `customer/controllers/Attestaions.php` — customer-side questionnaire response, evidence upload, and attestation report viewing.
- `consultant/controllers/Consultant.php`, `qa/controllers/Qa.php`, `qsa/controllers/Qsa.php` — reviewer-side process views (`process/consultant_view.php`, `process/qa_view.php`, `process/qsa_view.php`) — **LIKELY** each renders the same question/evidence list with role-specific accept/comment actions, mirroring the accepttoqsa/accepttoqa/AcceptToConsultant handlers in Compliances.php.

## 7. Important models

**CONFIRMED**: `Compliances_mod.php` (engagement CRUD, per-question status updates, AOC/ROC lookups), `Questionnaire_mod.php` (question listing/edit/status toggle), `Testing_mod.php` (testing engagement CRUD, parallel to Compliances_mod), `Customer_mod.php`/`Consultants_mod.php`/`Qas_mod.php`/`Qsa_mod.php` (staff/client directory CRUD), `Role_mod.php`, `Cms_mod.php`, `Archive_mod.php`.

## 8. Important DB entities

**CONFIRMED from `panacea.sql`**: `users`, `roles`, `services`, `testing`, `process`, `compliance_project`, `testing_project`, `questionnaire`, `country` (reference data).

**CONFIRMED to exist from code but ABSENT from the `panacea.sql` dump** (the dump is a partial/early snapshot — see README_Migration_Context.md's note that the repo is intentionally incomplete): `uplaod_evidence` (per-question evidence + status columns: `admin_status`, `qa_status`, `admin_status_date`, `all_status`, `all_status_date`, `docs`, `service_id`, `questionnaire_id`, `process_id`, `customer_id`), `all_comments`, `qsa_comments`, `qa_comments`, `consultant_comments`, `common_uplaod_docs`, `admin_status_log`, `report_aoc_roc` (`report_of` ∈ {AOC, ROC}, `report_docs`, `date`, `year`). **This gap is itself an important finding: the SQL dump cannot be trusted as complete ground truth — code (`function_helper.php`, `Compliances.php`) is the more reliable source for these tables.**

## 9. Important statuses/state transitions

**CONFIRMED**, scattered/undocumented status vocabulary (a genuine legacy smell — see `docs/legacy-to-new-mapping.md`):
- `users.status`: `active | inactive | delete` (soft states, `panacea.sql`).
- `users.user_type`: `1..5` (role) — a string-typed enum of digits, not a proper FK to `roles`.
- `questionnaire.status`: `'1'=active, '2'=inactive` (`panacea.sql` column comment).
- `compliance_project.status` / `testing_project.status`: `tinyint`, default `0` — **UNCLEAR** meaning of non-zero values (no comment, no seed data showing other values).
- `uplaod_evidence.admin_status`, `.qa_status`: booleans/flags gating "Approved"/"disabled" display logic in `Admin_status_change()`/`Admin_status_approved_change()` (`function_helper.php`).
- `uplaod_evidence.all_status`: `7=Approved, 8=Disapproved, 9=Marked Incomplete` — **CONFIRMED** from `Compliances.php::select_multiple_change_status`. Values 1–6 are used elsewhere but their meaning was **not directly observed** in this pass — **UNCLEAR**, likely per-role status codes (e.g. submitted/QSA-accepted/QA-accepted/consultant-accepted) given the four `accept*` handlers.
- `roles.status`: `active|inactive` — separate from `users.status`; `roles` table itself (Administrator/Employee/Supplier/Sub-contractors) appears **disconnected from the actual `user_type` persona system** — **LIKELY dead/legacy code**, a leftover from a more generic prior app (see mapping doc).

## 10. Important relationships

**CONFIRMED**: `compliance_project` / `testing_project` are the join entities tying together `service`(or `testing`) × `customer` × `process` × three reviewer staff (`qsa_id`, `consultant_id`, `qa_id`) — i.e. one engagement always has exactly one QSA, one Consultant, one QA assigned, set at creation time via dropdowns (`Compliances_mod::qsa_list/consulants_list/qa_list`). `questionnaire` rows belong to a `service`, not to a specific `compliance_project` — **CONFIRMED**: the same fixed question set is reused across every customer/engagement for that service (no per-engagement question customization observed). `uplaod_evidence` is the true fact table joining `questionnaire` × `process` × `service` × `customer`, one row per question-per-engagement.

## 11. Evidence/document handling

**CONFIRMED**: Evidence files are uploaded per question (`uplaod_evidence.docs` / `common_uplaod_docs`), stored on local disk under `./uploads/...` (e.g. `./uploads/report/`), with filenames munged by appending a random 4-digit suffix (`mt_rand(1000,9999)`) — no dedupe, no virus scan, no size/type allow-list observed in the code read so far (**UNCLEAR** whether validation exists elsewhere, e.g. in `MY_Form_validation.php`, not inspected in this pass). Final engagement reports (AOC/ROC and, by extension, AOT/ROT) are separate one-per-engagement uploads to `report_aoc_roc`, gated so only one file can exist per engagement (`FileUploadAOC`/`FileUploadROC` both check "already uploaded" before accepting a new file — i.e. **no versioning**, an overwrite/reject model).

## 12. Reporting/certificate behaviour

**CONFIRMED module usage, not deep-inspected**: `application/libraries/PHPExcel.php`/`IOFactory.php` (Excel export, likely for question/response export listings) and `application/libraries/M_pdf.php` (PDF generation, likely for AOC/ROC/certificate output) are present and referenced by the admin module — **LIKELY** used for exporting questionnaire results and/or generating the final certificate PDF, but the exact call sites were not traced end-to-end in this pass. Per project instructions these vendor libraries' internals are out of scope; only their presence/purpose is noted here.

## 13. Custom JS behaviour that matters

**UNCLEAR** — no CSS/JS asset files exist in this stripped-down repo (per README_Migration_Context.md, deliberately omitted). Any client-side validation/interactivity referenced by views (e.g. checkbox bulk-select for `select_multiple_change_status`, AJAX endpoints like `get_customer_process`/`ajax_layout`) is inferable from controller code but the actual JS is not present to inspect.

## 14. Security smells found (relevant to KEEP/DROP decisions)

**CONFIRMED**:
- Passwords hashed with bare **MD5** (`users.password`, e.g. `e10adc3949ba59abbe56e057f20f883e` = md5("123456")) — no salt, no bcrypt/argon2.
- A separate **plaintext-looking `pwdstring`** column stores what appears to be a raw/reversible representation alongside the hash (`panacea.sql` `users` table, e.g. `'1542365745'`-style values) — **LIKELY** a plaintext or weakly-encoded password store, a serious PHI/credential exposure pattern.
- `custom_encryption()` in `function_helper.php` uses `MCRYPT_RIJNDAEL_256` with an **MD5-derived, hardcoded-style key** (`md5($key)` where `$key` is a caller-supplied string) — deprecated `mcrypt` extension, and no evidence of a securely-managed secret.
- `certificate_key`/`new_certificate_key`/`is_certificate_verified` columns on `users` plus long, apparently机-generated blob values suggest a **device-certificate or cookie-based "remember this device" gate** bolted onto login — mechanism not fully traced, but the pattern (long opaque string stored in plaintext DB column, compared against a client-supplied value) is a classic legacy security smell.
- Custom **`ID_encode`/`ID_decode`** "obfuscation" of numeric IDs in URLs (`rand(1111,9999) . ($id+19) . rand(1111,9999)`, substring-decoded) is not real encryption/authorization — it is trivially reversible and provides no access control by itself.

These are called out explicitly because they must **DROP**, not migrate, into the new product (see `docs/legacy-to-new-mapping.md`).

## 15. Unknown/ambiguous functionality (needs human confirmation)

- **UNCLEAR**: Exact meaning of `compliance_project.status`/`testing_project.status` values 1+ (only `0` seen in seed data).
- **UNCLEAR**: Exact DDL of `uplaod_evidence`, `all_comments`, `qsa_comments`, `qa_comments`, `consultant_comments`, `common_uplaod_docs`, `admin_status_log`, `report_aoc_roc` — reconstructed from column names used in code, not from a schema definition.
- **UNCLEAR**: Whether `Cms` and `Role` modules are live features or abandoned scaffolding — the CMS pages (`cms/add.php`, `edit.php`, `listing.php`, `view.php`) and the `roles` table (Administrator/Employee/Supplier/Sub-contractors) don't obviously connect to the compliance workflow at all — **LIKELY leftover boilerplate from a prior/generic CodeIgniter admin template** the vendor reused, not a real Panacea feature. Flagged DROP.
- **UNCLEAR**: Full extent of the QSA→QA→Consultant→Admin review sequencing/ordering — the four independent `accept*` handlers in `Compliances.php` suggest each role can act somewhat independently rather than a strictly gated sequential pipeline, but the exact business rule (can QA approve before QSA?) was not confirmed from validation logic in this pass.
- **UNCLEAR**: Whether `Testing` module truly duplicates `Compliances` end-to-end (including its own comment/status tables) or shares some of the same underlying tables — code structure strongly suggests duplication (`testing_project` mirrors `compliance_project` column-for-column) but this needs a byte-for-byte diff to confirm before deciding merge strategy.
