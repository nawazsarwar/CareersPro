# M25 — RBAC & Impersonation

**Wave:** 1 · **Scope:** v1
**Depends on:** DR-008, DR-010 · M03
**Blocked by:** OQ-015 *(Dean's-office role granularity — one OU-scoped role, or separate scrutiny
and appointment roles. Design proceeds; the answer changes the seeded role list, not the mechanism.)*

## 1. Purpose and statutory basis

Authorisation with **two orthogonal row-level scopes**. UGC 2018 cl. 5.1 and CRR Schedule-II define
who may sit on which committee; CRR Rule 6 defines appointing authorities; **DR-010** establishes
that local recruitment is administered in the Dean's office, not centrally.

**This module closes the most serious defect in the codebase.** Today the `frontend.` route group
has **no `auth` middleware**, its 35 controllers are verbatim admin CRUD with no `user_id` scoping,
and `PermissionRoleTableSeeder` grants the `User` role `profile_edit`, `application_form_edit` and
`academic_qualification_delete` **on every row**. Any authenticated candidate can read and modify any
other candidate's dossier.

## 2. Data

```
roles           id · name · slug UNIQUE · is_system · description
permissions     id · slug UNIQUE · resource · action
permission_role role_id · permission_id
role_user       role_id · user_id · organisational_unit_id (NULLABLE)
                UNIQUE(role_id, user_id, organisational_unit_id)
impersonation_tokens  id · actor_id · target_id · token_hash · expires_at
                      consumed_at · actor_ip · ended_at
```

**`role_user.organisational_unit_id` is the second scope.** `NULL` = university-wide; non-null = that
unit **and its subtree**, resolved through `organisational_units.path`.

**Indexes:** `role_user(user_id)` · `role_user(organisational_unit_id)` ·
`permissions.slug` unique.

## 3. Domain services

```
App\Domain\Access\ResolvePermissions::for(User): Collection      // cached 15 min
App\Domain\Access\ResolveScopes::for(User): ?array               // OU paths, or null = university-wide
App\Domain\Access\StartImpersonation::handle(User $actor, User $target): string
App\Domain\Access\EndImpersonation::handle(): void

abstract class ScopedPolicy    // every scoped resource policy MUST extend this
{
    protected function permits(User $u, string $ability, Model $m): bool
    {   return $this->hasPermission($u, $ability) && $this->inScope($u, $m); }
}
```

**Invariants.**
- **A permission alone never authorises a row.** `ScopedPolicy::permits` requires both. A policy that
  does not extend it fails an architecture test.
- Permissions are **cached per user for 15 minutes**, invalidated on any role or permission change.
  Today `AuthGates` runs 2 queries and **162 `Gate::define()` closures on every request**, uncached.
- `is_admin` is a **named-role check**, never `roles()->where('id', 1)`.
- Impersonation tokens are single-use, expiring, and **always audited** with the actor's IP.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/admin/roles` | `admin.roles.index` | `auth`, `verified`, `2fa` | `RolePolicy@viewAny` |
| GET/POST/PATCH/DELETE | `/admin/roles/{role?}` | `admin.roles.*` | as above | `RolePolicy@*` |
| GET | `/admin/users` | `admin.users.index` | as above | `UserPolicy@viewAny` |
| POST | `/admin/users/{user}/roles` | `admin.users.roles.attach` | as above | `UserPolicy@assignRole` |
| POST | `/admin/impersonate/{user}` | `admin.impersonate.start` | as above, `password.confirm` | `ImpersonationPolicy@start` |
| DELETE | `/admin/impersonate` | `admin.impersonate.stop` | `auth` | — |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `role_id` | required, exists:roles,id | Select a role. |
| `organisational_unit_id` | nullable, exists:organisational_units,id, **`published_unit`** | Select a published organisational unit. |
| | **required when the role is OU-scoped** (`dean_office`) | This role must be limited to an organisational unit. |
| | **must be null when the role is university-wide** | This role cannot be limited to an organisational unit. |
| `permissions[]` | array, each exists:permissions,id | |
| impersonation `user` | exists, **not the actor**, **target must not hold `super_admin`** | You cannot impersonate this user. |

## 6. Authorisation

`RolePolicy`, `UserPolicy`, `ImpersonationPolicy` — all university-wide, `super_admin` only for
mutation. **Separation of duties:** `rules_admin` may author a ruleset; only `rules_verifier` may
activate it, **and the two must be different users** (`../01-design/security/security-model.md`
§3.1). Enforced in `RuleSetPolicy@activate`.

The 11 roles and the full matrix are in `../01-design/security/security-model.md` §3.

## 7. UI

Role list and editor as standard tables. The user-role editor shows the OU picker **only** for
OU-scoped roles, with the subtree it grants spelled out: *"Faculty of Arts and its 3 departments."*

An **impersonation banner** is persistent, high-contrast, and names both parties: *"You are viewing
as AISHA KHAN. End impersonation."*

## 8. Worked example

Dr Rehman is Dean's-office staff for the **Faculty of Arts** (unit 11, path `/1/11/`).

```
role_user: (dean_office, rehman, organisational_unit_id = 11)
ResolveScopes::for(rehman) → ['/1/11/']
```

- Scrutiny queue → `WHERE ou_path_snapshot LIKE '/1/11/%' OR organisational_unit_id = 11`.
  Sees local posts in **Arts and its 3 departments**.
- Opens a local post in the **Faculty of Commerce** (`/1/12/`) → **403**.
- Tries the export URL for Commerce directly → **403**, because the export runs the same
  `visibleTo` query.
- Opens a **General** advertisement → **403**. General recruitment is centrally administered
  (DR-010); it is not in the `dean_office` permission set at all.

A central `recruitment_admin` has `organisational_unit_id = NULL`, so `ResolveScopes` returns `null`
and no path filter is applied.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M25-R01 | Given candidate A, when requesting any of candidate B's resources, then **403** — profile, documents, application, order, snapshot |
| M25-R02 | Given a Dean's-office user of Faculty X, when requesting any local advertisement, post, application or scrutiny action of Faculty Y, then **403** |
| M25-R03 | Given a Dean's-office user, when requesting a General advertisement, then **403** |
| M25-R04 | Given a policy class for a scoped resource, when it does not extend `ScopedPolicy`, then an architecture test fails |
| M25-R05 | Given a filtered list, when any filter, sort or page combination is applied, then the result never exceeds the actor's scope |
| M25-R06 | Given a `rules_admin`, when activating a ruleset, then it is refused |
| M25-R07 | Given the same user as author and verifier, when activating, then it is refused |
| M25-R08 | Given an impersonation, when it starts, then the existing session is invalidated and an audit entry records the actor's IP |
| M25-R09 | Given an impersonation token, when reused, then it is rejected |
| M25-R10 | Given a permission change, when the user's next request runs, then the cache is invalidated |
| M25-R11 | Given any request, when permissions resolve, then **no more than 1 query** is issued (cached) |
| M25-R12 | Given an OU-scoped role, when assigned without an organisational unit, then validation fails |

## 10. Test cases

`tests/Feature/Authz/AuthorisationMatrixTest` — iterates **every role × resource × action** in
`security-model.md` §3.3, asserting positives **and negatives** (R01, R03) ·
`OwnershipIsolationTest` — R01 · `OrganisationalUnitIsolationTest` — R02, R05 ·
`tests/Architecture/PolicyTest` — R04 · `SeparationOfDutiesTest` — R06, R07 ·
`ImpersonationTest` — R08, R09 · `PermissionCacheTest` — R10, R11 ·
`RoleAssignmentValidationTest` — R12.

Fixtures: `OrganisationalUnitFactory` producing a 3-level tree; `RoleFactory` with all 11 roles.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R05, R12 | `App\Domain\Access\*`, `App\Policies\ScopedPolicy` and descendants |
| R06, R07 | `App\Policies\RuleSetPolicy` |
| R08, R09 | `App\Domain\Access\StartImpersonation` |
| R10, R11 | `App\Domain\Access\ResolvePermissions` |
