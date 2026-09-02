# Domain Model — derived from legacy evidence

This is a conceptual model, independent of the CodeIgniter/MySQL implementation. For each concept: purpose, key attributes, relationships, lifecycle, legacy source, and verdict (KEEP/SIMPLIFY/REPLACE/DROP — detailed rationale in `docs/legacy-to-new-mapping.md`).

## Organisation
- **Purpose**: the audit firm's client company being assessed.
- **Legacy source**: implicit in `users.company_name`/`company_number` on customer-type users; no dedicated `organisation` table exists — a "customer" IS a user row (CONFIRMED, `panacea.sql` `users`).
- **Verdict**: **REPLACE.** The new model needs a first-class `Organisation` entity distinct from the `User` who logs in, so multiple customer users can belong to one org (legacy approximates this with `users.parent_id` sub-accounts, which is fragile).

## User
- **Purpose**: any person who logs in — staff or customer contact.
- **Attributes (legacy)**: full_name, email, phone_number, password (MD5), status (active/inactive/delete), user_type (1–5), parent_id (implicit hierarchy).
- **Verdict**: **KEEP the concept, REPLACE the implementation.** Password hashing, the plaintext-looking `pwdstring`, and the custom `certificate_key` device-gate must be dropped entirely (see security notes in `docs/legacy-application-map.md` §14).

## Role
- **Purpose (legacy)**: a `roles` table (Administrator/Employee/Supplier/Sub-contractors) that does **not** map onto the actual five `user_type` personas used everywhere else in the app.
- **Verdict**: **DROP.** The real role concept the app uses is `user_type` (admin/qsa/qa/consultant/customer), not this table. The new product needs exactly two roles: **Auditor/Admin** (audit-firm staff) and **Customer User** (client-side).

## Compliance Framework / Service
- **Purpose**: the named standard being assessed against (PCI DSS, ISO, HIPAA, HITRUST) — `services` table, `testing` table (parallel concept for testing engagement types).
- **Verdict**: **SIMPLIFY → Checklist Template's category/name.** No need for a separate "Testing" framework type — a testing engagement (e.g. "ASV Scan") is just another checklist template in the new model.

## Checklist Template / Questionnaire
- **Purpose**: the fixed set of questions for a given service — `questionnaire` table (`service_id`, `question`, `status` active/inactive).
- **Lifecycle (legacy)**: seeded once; only text-edit and active/inactive toggle observed (`Questionnaire.php`) — no create/delete/reorder/section UI found.
- **Verdict**: **REPLACE.** The new product needs auditors to create templates, duplicate existing ones, organise into sections, add/edit/reorder/disable questions, mark mandatory/optional, and specify expected evidence and guidance text — none of which the legacy system supports today. This is one of the biggest genuine improvements over the legacy app.

## Section
- **Purpose**: no evidence of question grouping/sections in the legacy schema — `questionnaire` is a flat list per service.
- **Verdict**: **NEW** (not present in legacy, but required by product requirements: "per-section progress").

## Question / Control
- **Attributes (legacy)**: question (text), status (active/inactive). No fields for guidance, expected evidence, required/optional, or response type — legacy evidence upload is implicitly required and freeform text (Yes/No/N-A distinction not observed in schema).
- **Verdict**: **REPLACE** with a richer Question entity: text, control reference, guidance, expected evidence description, required flag, response type (Yes/No/N-A, short text, long text, file-required).

## Assessment (legacy: compliance_project / testing_project)
- **Purpose**: one engagement — a specific customer + process + service/testing-type + assigned review team (qsa_id, consultant_id, qa_id) + date range + status.
- **Lifecycle**: created by Admin (`add_compliances`), optionally end-dated later (`EndDateformAdmin`), status changes as questions are reviewed (`Admin_status_change` computes "Approved"/"In Progress" by counting fully-approved questions).
- **Verdict**: **KEEP the concept, SIMPLIFY the implementation.** Collapse `compliance_project` and `testing_project` into one `Assessment` entity (template reference + customer + assigned auditor(s) + dates + status), computed from its questions' statuses rather than duplicated per engagement type.

## Process
- **Purpose (legacy)**: a named container per customer (`process` table: customer_id, process_name, status) that an engagement (`compliance_project`/`testing_project`) is created "against." Customers can have several named processes (e.g. "Process1", "Process2").
- **Verdict**: **UNCLEAR** whether this maps to a meaningful business concept (e.g. "which business unit/system is in scope") or is just a free-text label with no real behaviour attached — no distinguishing fields beyond a name were found. **LIKELY DROP or fold into Assessment.name/scope-note** — needs human confirmation before finalizing; flagged in unknowns.

## Assessment Question / Response
- **Purpose (legacy)**: `uplaod_evidence` — the fact table joining questionnaire × process × service × customer, carrying the per-question review state (`admin_status`, `qa_status`, `all_status`, dates) and the evidence file (`docs`).
- **Verdict**: **KEEP the concept, REPLACE the status model.** Split cleanly into `AssessmentResponse` (customer's answer + status) and `Evidence` (the file), rather than overloading one row with three different reviewers' boolean flags.

## Evidence
- **Purpose (legacy)**: uploaded files per question, in `uplaod_evidence.docs` and `common_uplaod_docs` (a second table with overlapping purpose — likely evidence shared/visible across roles, or a legacy migration artifact — **UNCLEAR** exact distinction between the two tables from code alone).
- **Verdict**: **KEEP as first-class entity**, per product requirements (file, filename, uploader, timestamp, question association, description, active/current flag). Consolidate the two overlapping legacy tables into one `Evidence` collection.

## Comment / Clarification
- **Purpose (legacy)**: FOUR parallel comment tables — `all_comments`, `qsa_comments`, `qa_comments`, `consultant_comments` — one per reviewer role, each independently queried and displayed per question.
- **Verdict**: **REPLACE with a single unified `Comment`/clarification thread per question**, tagged by author role, instead of four separate siloed tables. This is a clear SIMPLIFY: the legacy split adds implementation complexity without adding business value — a customer benefits from seeing all reviewer feedback in one place, not scattered across four tables the UI has to separately fetch and stitch together.

## Review / Approval action
- **Purpose (legacy)**: four independent "accept" actions per role (`accepttocustomer`, `accepttoqsa`, `accepttoqa`, `AcceptToConsultant`) plus a bulk Approve/Disapprove/Mark-Incomplete action (`select_multiple_change_status`, `all_status` ∈ {7,8,9}).
- **Verdict**: **SIMPLIFY.** Three separate reviewer roles independently "accepting" the same question is unnecessary process weight for the product goal ("obvious what to do next"). Collapse to one Auditor role with one accept/needs-clarification/reject action per question.

## Report / Certificate (AOC / ROC, and by extension AOT/ROT for testing)
- **Purpose (legacy)**: `report_aoc_roc` — one uploaded PDF per engagement per report type (Attestation of Compliance / Report on Compliance), one-shot (rejected if already uploaded — no versioning).
- **Verdict**: **SIMPLIFY.** Keep as a final-artifact-upload concept attached to a completed Assessment, but allow replace/versioning rather than the legacy's rigid one-shot upload.

## Legacy DB structures that should NOT survive
- `roles` table (disconnected from the real persona system) — **DROP**.
- `cms_*` tables/screens (unrelated static-page CMS) — **DROP**.
- `archive` (purpose unconfirmed, no clear domain tie) — **DROP** unless a human confirms a real audit-log use case.
- `country` reference table — **DROP** unless address/locale fields are required in V1 (not clearly required by any confirmed workflow).
- The `certificate_key`/`new_certificate_key`/`is_certificate_verified`/`pwdstring` columns on `users` — **DROP**, security smells, replaced by standard hashed-password + session/JWT auth.
- Duplicate `testing_project`/`testing` structures — **DROP**, merged into the generic Assessment/Template model.
- Four-way comment table split (`all_comments`/`qsa_comments`/`qa_comments`/`consultant_comments`) — **DROP**, replaced by one `Comment` collection.
