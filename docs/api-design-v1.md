# API Design V1

Only MVP-needed endpoints, organized by feature. Modular monolith (Node/Express), not microservices.

## /auth
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| POST | /auth/login | authenticate | any | email, password | JWT/session token, user profile | credentials match, user.status=active |
| POST | /auth/logout | end session | any | — | 204 | valid session |
| POST | /auth/forgot-password | request reset | any | email | 204 (always, no user enumeration) | rate-limited |
| POST | /auth/reset-password | complete reset | any | token, newPassword | 204 | token valid & unexpired, password strength |

## /organisations
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| GET | /organisations | list customer orgs | auditor | ?status | list | — |
| POST | /organisations | create org | auditor | name | org | name required, unique |
| GET | /organisations/:id | detail | auditor, org's own customer_user | — | org | scoping check |
| PATCH | /organisations/:id | edit | auditor | fields | org | scoping check |

## /users
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| GET | /users | list | auditor | ?organisationId,?role | list | scoped to firm/org |
| POST | /users | invite/create user | auditor | fullName, email, role, organisationId? | user | email unique, role∈{auditor,customer_user}, organisationId required if role=customer_user |
| PATCH | /users/:id | edit/deactivate | auditor, self (profile fields only) | fields | user | self-edit limited to name/phone/password |
| GET | /users/me | current profile | any | — | user | — |

## /templates
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| GET | /templates | list | auditor | ?status | list | — |
| POST | /templates | create | auditor | name, category | template (empty sections) | name required |
| POST | /templates/:id/duplicate | duplicate | auditor | newName? | new template | source must exist |
| GET | /templates/:id | detail | auditor | — | template + sections + questions | — |
| PATCH | /templates/:id | edit name/category/status | auditor | fields | template | can't activate with 0 questions |
| POST | /templates/:id/sections | add section | auditor | title | section | — |
| PATCH | /templates/:id/sections/:sectionId | edit/reorder section | auditor | title?, order? | section | — |
| POST | /templates/:id/sections/:sectionId/questions | add question | auditor | text, controlRef, guidance, expectedEvidence, required, responseType | question | text & responseType required |
| PATCH | /templates/:id/sections/:sectionId/questions/:questionId | edit/reorder/disable question | auditor | fields | question | — |

No standalone `/questions` or `/sections` top-level resource — they are only ever accessed as part of a template (per the domain-model embedding decision), so exposing them independently would add surface area with no consumer.

## /assessments
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| GET | /assessments | list | auditor (all their customers'), customer_user (own org) | ?status,?organisationId | list w/ progress summary | scoping |
| POST | /assessments | create | auditor | templateId, organisationId, assignedAuditorId?, dueDate? | assessment (draft) | template active, no duplicate active assessment for same template+org |
| GET | /assessments/:id | overview | auditor, customer_user (own org) | — | assessment + section/question progress counts | scoping |
| PATCH | /assessments/:id | activate/complete/edit dueDate | auditor | status?, dueDate? | assessment | valid status transition |

## /assessments/:id/responses
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| GET | /assessments/:id/responses | list responses for assessment | auditor, customer_user (own) | — | list | scoping |
| GET | /assessments/:id/responses/:responseId | detail (answer, evidence, comments) | auditor, customer_user (own) | — | response + evidence[] + comments[] | scoping |
| PATCH | /assessments/:id/responses/:responseId | save/submit answer | customer_user (own) | answer, customerNote?, submit:boolean | response | required-field check on submit |
| POST | /assessments/:id/responses/:responseId/review | accept / request clarification / mark non-compliant | auditor | decision, comment? | response | decision∈{accept,needs_clarification,non_compliant} |

## /assessments/:id/responses/:responseId/evidence
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| POST | .../evidence | upload file | customer_user (own) | multipart file, description? | evidence record | MIME allow-list, size limit (configurable), scoping |
| GET | .../evidence | list | auditor, customer_user (own) | — | list | scoping |
| DELETE | .../evidence/:evidenceId | remove own not-yet-submitted upload | customer_user (own, only pre-submit) | — | 204 | ownership + response not yet submitted |
| GET | /evidence/:evidenceId/download | download file | auditor, customer_user (own org) | — | signed URL / stream | scoping, storage-abstraction lookup |

## /assessments/:id/responses/:responseId/comments
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| POST | .../comments | add comment | auditor, customer_user (own) | text | comment | text required, non-empty |
| GET | .../comments | list thread | auditor, customer_user (own) | — | list, chronological | scoping |

## /assessments/:id/report
| Method | URL | Purpose | Roles | Input | Output | Key validation |
|---|---|---|---|---|---|---|
| POST | /assessments/:id/report | upload final report (AOC/ROC/etc) | auditor | reportType, multipart file | report record (versioned) | assessment exists |
| GET | /assessments/:id/report | list/download reports | auditor, customer_user (own) | — | list | scoping |

## /dashboard
| Method | URL | Purpose | Roles | Output |
|---|---|---|---|---|
| GET | /dashboard | role-appropriate summary | any | auditor: active assessment count, customers needing attention, recent submissions, controls needing review, behind-schedule list. customer_user: active assessments + %complete, needs-my-attention list, awaiting-auditor list, due-soon list |

## Cross-cutting
- All endpoints require auth (JWT bearer or session cookie); org-scoping enforced server-side on every customer_user request (never trust a client-supplied organisationId for a customer_user).
- No independent CRUD surface for Section/Question/Comment/Evidence outside their parent's nested routes — deliberately avoids over-exposing internal collections that have no standalone consumer, per the "avoid CRUD for entities that don't need independent exposure" instruction.
