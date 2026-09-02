# Compliance Manager — Claude Code Instructions

This file is read automatically at the start of every Claude Code session in
this repo. It is the single source of standing instructions.

---

## What this project is

A greenfield rebuild of a legacy PHP/CodeIgniter compliance-audit tool
("Panacea") as a simpler SaaS: audit firms create checklist templates, run
assessments against customer organisations, customers answer questions and
upload evidence, auditors review and request clarification. The legacy
`application/` tree and `database/panacea.sql` are discovery-only inputs —
see `README_Migration_Context.md` — never make them runnable, never treat
them as the target architecture.

Stack: Node.js/Express (`api/`) + MongoDB (Mongoose) + React SPA via Vite
(`web/`), Docker Compose for local/on-prem, S3-compatible storage pluggable
behind a local abstraction for evidence files. See `docs/api-design-v1.md`,
`docs/data-model-v1.md`, `docs/product-design.md` for the full spec.

Node 22.2 is pinned in this environment; do not upgrade to vite/rolldown
versions that require Node ≥22.12 without checking the environment first
(vite 8's bundled rolldown broke on this Node — pinned to vite 5).

---

## LOCKED — do not change without an explicit version bump instruction

These docs define the product contract. Changing entity shapes, field names,
or status enums across sessions breaks earlier sessions' work silently.

- `docs/data-model-v1.md` — MongoDB collection shapes
- `docs/api-design-v1.md` — endpoint contracts
- `docs/domain-model.md` — entity definitions and verdicts
- `docs/product-design.md` — status models, permissions, screen map

If a change is genuinely needed, say so explicitly and update the doc in the
same session — never silently diverge code from docs.

---

## Architecture decisions that are final (don't re-derive or re-debate)

1. Two roles only: `auditor` and `customer_user`. No fine-grained
   per-question permissions.
2. `checklistTemplates` embeds its sections/questions (always read/written
   together, small counts) — see justification in `docs/data-model-v1.md`.
   `assessmentResponses`, `evidence`, `comments` are separate collections.
3. `assessmentResponses` snapshot question text/controlRef at assessment
   creation time — later template edits never retroactively change an
   in-flight or completed response.
4. No standalone `/questions` or `/sections` or `/comments` top-level REST
   resource — always accessed nested under their parent, per
   `docs/api-design-v1.md`.
5. Evidence files never live in MongoDB — metadata + storageKey only, actual
   bytes behind a pluggable storage abstraction (filesystem/MinIO on-prem,
   S3-compatible in cloud).
6. Org-scoping is enforced server-side, never trusting a client-supplied
   `organisationId` for a `customer_user` — see `resolveOrgScope` middleware
   pattern in `api/src/middleware/auth.js`.
7. Stateless JWT auth in V1. No session store, no refresh-token rotation
   unless a real need is confirmed.
8. Visual design is locked to the tokens in `web/src/index.css` (`:root`
   custom properties) — ink sidebar, warm off-white canvas, single muted
   slate accent, Newsreader (headings) + Public Sans (UI) + IBM Plex Mono
   (control refs/counts). Approved by the user via a design-review canvas
   before Session 2 was built. Reuse these tokens for every new screen;
   don't introduce new colors/fonts without checking with the user first.

---

## Session scope discipline

Sessions follow the 8 vertical slices in `docs/mvp-backlog.md`, each
independently deployable/demoable:

| # | Slice | Status |
|---|-------|--------|
| 1 | Auth + Organisations | **Done** |
| 2 | Template creation | **Done** |
| 3 | Assessment creation | **Done** |
| 4 | Customer questionnaire | Not started |
| 5 | Evidence upload | Not started |
| 6 | Auditor review + clarification | Not started |
| 7 | Progress + dashboard | Not started |
| 8 | Deployment hardening | Not started |

Do not pull work forward from a later slice even if it looks easy — later
slices assume earlier ones are solid (e.g. slice 4's response snapshot logic
depends on slice 2/3 being correct). Update the table above at the end of
every session.

---

## Code quality rules (every session, no exceptions)

- **Keep all existing tests green before starting and after finishing.** Run
  `cd api && npm test` as the first and last action of every session.
- Add tests for new work (Jest + Supertest + mongodb-memory-server pattern
  in `api/tests/`). Every new route needs at least one happy-path test and
  one authorization/scoping test.
- No secrets or API keys in code — `.env` is git-ignored, `.env.example`
  documents required vars for both `api/` and `web/`.
- All timestamps via Mongoose `timestamps: true`. All IDs are Mongo
  ObjectIds (not UUIDs — this is Mongo, not Postgres).
- No `console.log` of request bodies containing passwords, tokens, or
  uploaded evidence content. `console.error(err.message)` only, never the
  full error object with stack in a response body.
- Commit at the end of every session with a message naming the slice and
  summarizing what changed.

---

## File layout quick reference

```
api/src/
  models/       Mongoose schemas (Organisation, User, ...)
  routes/       Express routers, one per resource
  middleware/   requireAuth, requireRole, resolveOrgScope
  utils/        password hashing, JWT signing
  config/       env.js
  app.js        Express app factory (used by tests, no listen())
  server.js     connects DB, calls app.listen()
api/tests/      Jest + Supertest + mongodb-memory-server
web/src/
  pages/        one component per screen
  components/   shared UI (Layout/nav)
  lib/          api.js (typed fetch client), AuthContext.jsx
docs/           product spec — read before changing behavior
application/    legacy CodeIgniter source — discovery only, do not run
```

---

## How to start each session

1. Run `cd api && npm test` — confirm all prior tests green.
2. Read this file (already done automatically) and the relevant `docs/*.md`
   sections for the slice being built.
3. State what you will do and what you will NOT do this session.
4. Do the work, testing as you go (`npm test` in `api/`, `npm run build` in
   `web/`).
5. Run `npm test` again — all tests must pass.
6. Update the session table above.
7. Commit.
