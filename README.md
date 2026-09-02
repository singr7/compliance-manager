# Compliance Manager

A simpler replacement for a legacy PHP/CodeIgniter audit-compliance tool.
Audit firms build checklist templates, run assessments against customer
organisations, customers answer questions and upload evidence, auditors
review and request clarification. See `docs/product-design.md` for the full
product spec and `CLAUDE.md` for engineering conventions and session plan.

## Local development

```bash
# API
cd api
cp .env.example .env
npm install
npm test
npm run dev   # http://localhost:4000

# Web
cd web
cp .env.example .env
npm install
npm run dev   # http://localhost:5173
```

Requires a local MongoDB (`mongod`) or `docker compose up mongo`.

## Docker Compose (full stack)

```bash
cp api/.env.example api/.env
docker compose up --build
```

API on :4000, web on :5173, Mongo on :27017.
