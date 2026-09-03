# M29 — Public API

**Wave:** 9 · **Scope:** **v1-partial** *(a small, real, documented API — not CRUD over every table)*
**Depends on:** M25, M26, M01
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

A documented API for the two things that genuinely need one: **public vacancy discovery** and
**candidate application status**.

No statutory basis. UGC 2025 draft cl. 3.1 requires an *"all-India advertisement"*, and a machine-
readable feed serves that; nothing else here is required.

**What this replaces.** 27 auto-generated CRUD controllers over master-data tables, mounted at
`api/v1` behind `auth:sanctum` — with **no `sanctum` guard in `config/auth.php`, no
`personal_access_tokens` table, `User` lacking `HasApiTokens`, and no rate limiting** (`throttle:api`
lives only in the dead `Http/Kernel.php`). The API cannot authenticate anyone and never could.

**What is explicitly out of scope.** `docs/v2-archive/spec/api.md` specifies `/api/v2/vacancies`,
`/api/v2/applications`, `/api/v2/applications/{uuid}/status` and
`POST /api/v2/integrations/cu-chayan/push`. **No CU-Chayan ingestion endpoint, credential set or data
contract exists in any document held.** It was invented, and it is dropped
(`../00-clarify/scope-boundary.md` §6).

## 2. Data

```
personal_access_tokens     -- Sanctum's, PUBLISHED this time
api_clients   id · name · owner_email · abilities json
              rate_limit_per_minute · is_active
              created_by_id · last_used_at
api_request_logs  id · api_client_id NULL · user_id NULL
                  method · path · status · duration_ms · ip · created_at
```

`config/auth.php` gains a **`sanctum` guard**; `User` gains **`HasApiTokens`**. Both absent today.

**Indexes:** `api_request_logs(api_client_id, created_at)` · partitioned monthly.

## 3. Domain services

```
App\Domain\Api\IssueToken::handle(ApiClient, array $abilities, User): string
App\Domain\Api\RevokeToken::handle(string $tokenId, User): void
App\Http\Resources\Api\V1\{VacancyResource, PostResource, ApplicationStatusResource}
```

**Invariants.**
- **Every endpoint declares its abilities**; a token without the ability gets **403**, not 404.
- **Rate limiting is defined in a real service provider** — `RateLimiter::for('api')` — and applies
  per token and per IP.
- **Public endpoints expose no personal data.** Vacancy and post resources contain no candidate
  information at all.
- **The status endpoint is ownership-scoped** and returns the candidate's **own** application only.
- **No write endpoints in v1.** Read-only. Applications are made through the wizard, where the
  statutory completeness and declaration checks live.
- Every request is logged; every token issue and revocation is audited.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Ability |
|---|---|---|---|---|
| GET | `/api/v1/vacancies` | `api.vacancies.index` | `throttle:api` | — *(public)* |
| GET | `/api/v1/vacancies/{post:slug}` | `api.vacancies.show` | `throttle:api` | — |
| GET | `/api/v1/advertisements` | `api.advertisements.index` | `throttle:api` | — |
| GET | `/api/v1/advertisements/{advertisement:slug}` | `api.advertisements.show` | `throttle:api` | — |
| GET | `/api/v1/me/applications` | `api.me.applications` | `auth:sanctum`, `throttle:api` | `applications:read` |
| GET | `/api/v1/me/applications/{application}` | `api.me.application` | as above | `applications:read` |
| GET | `/api/docs` | `api.docs` | — | — *(OpenAPI 3.1)* |

**Seven endpoints.** Not 27 CRUD resources over lookup tables.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| filters | as M01, whitelisted against `TableConfig` | |
| `per_page` | nullable, integer, between:1,100 | |
| token `abilities[]` | required, each in the declared ability list | Unknown ability: {ability}. |
| `rate_limit_per_minute` | required, integer, between:1,600 | |
| client `owner_email` | required, email | |

Unrecognised query parameters are **ignored, not errored** — a stale client should keep working.

## 6. Authorisation

Public endpoints apply `VacancyVisibility` unconditionally, so an unpublished advertisement is
unreachable by direct slug.

`/me/*` endpoints are **ownership-scoped**: the token's user, and only that user's applications.
There is **no admin API in v1** — administrative work happens in the authenticated interface, where
the two authorisation scopes and the audit trail are already enforced.

Token issue and revocation: `super_admin` only, audited.

## 7. UI

**OpenAPI 3.1 document at `/api/docs`**, generated from route attributes and resource schemas, with a
live try-it console for the public endpoints. **Generated, not hand-written** — the previous spec
promised *"documented via OpenAPI 3.1"* with no file anywhere.

Admin: API clients with abilities, rate limit, last used, and a usage chart. Tokens are shown **once**
at issue and never again.

## 8. Worked example

A job-aggregation site wants AMU vacancies.

```
GET /api/v1/vacancies?cadre=teaching&status=open&per_page=50
```

Returns published, non-withdrawn, currently open teaching posts: post title, designation,
organisational unit **from the snapshot**, vacancies, pay level, appointment nature, fee, dates, and
the public URL. **No candidate data.** Rate limited to 60 per minute per IP.

A candidate's mobile client:

```
GET /api/v1/me/applications
Authorization: Bearer 3|xxxxx
```

The token carries `applications:read` → returns **that user's** applications with state, payment
state, gate states and next action. A token without the ability gets **403**. A token belonging to
another user returns **that** user's applications and never Aisha's — asserted by test.

An attempt on `GET /api/v1/users` returns **404**. It does not exist, and never will in v1.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M29-R01 | Given an unpublished advertisement, when requested by slug, then **404** |
| M29-R02 | Given a public endpoint, when the response is inspected, then it contains **no personal data** |
| M29-R03 | Given a token without the ability, when calling `/me/applications`, then **403** |
| M29-R04 | Given user A's token, when calling `/me/applications`, then only A's applications return |
| M29-R05 | Given 61 requests in a minute, when the 61st is made, then **429** |
| M29-R06 | Given any route, when enumerated, then **no write endpoint exists** |
| M29-R07 | Given `/api/docs`, when requested, then a valid OpenAPI 3.1 document is returned |
| M29-R08 | Given the document, when compared to the routes, then every route is documented |
| M29-R09 | Given a token issue, when it completes, then it is audited and the token is shown once |
| M29-R10 | Given a revoked token, when used, then **401** |
| M29-R11 | Given an unknown query parameter, when supplied, then it is ignored and the request succeeds |
| M29-R12 | Given `config/auth.php`, when inspected, then a `sanctum` guard exists and `User` has `HasApiTokens` |

## 10. Test cases

`tests/Feature/Api/PublicEndpointTest` — R01, R02, R11 · `TokenAbilityTest` — R03, R09, R10 ·
`OwnershipTest` — **R04** · `RateLimitTest` — R05 ·
`tests/Architecture/ApiReadOnlyTest` — **R06** · `OpenApiTest` — R07, R08 ·
`tests/Feature/Api/SanctumConfigTest` — **R12, the defect that makes the current API inert**.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R11 | `App\Http\Resources\Api\V1\*`, `VacancyVisibility` (M01) |
| R03, R09, R10 | `App\Domain\Api\IssueToken`, Sanctum abilities |
| R04 | `/me` controllers, ownership scope |
| R05 | `RateLimiter::for('api')` in `AppServiceProvider` |
| R06–R08 | `routes/api.php`, generated OpenAPI |
| R12 | `config/auth.php`, `App\Models\User` |
