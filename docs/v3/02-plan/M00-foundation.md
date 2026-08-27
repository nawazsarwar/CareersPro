# M00 — Purge, Toolchain, CI, Shared Table

**Wave:** 0 · **Scope:** v1
**Depends on:** DR-002, DR-003
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Clear the ground and lay the floor. No statutory basis — this is the enabling work that makes every
other module's statutory obligations implementable. It also closes three **critical** security
defects (`../01-design/security/security-model.md` §2, items 3, 5 and 8).

## 2. Data

No domain tables. This wave **deletes all 56 existing migrations** and establishes the migration
baseline: naming conventions (`../00-clarify/glossary.md` §7), `json` columns per ADR-001, real
foreign keys, PHP enums.

Creates only framework tables the current schema is missing: `sessions`, `cache`, `jobs`,
`failed_jobs`, **`password_reset_tokens`** (the name `config/auth.php` actually expects — the current
migration creates `password_resets`, so password reset is dead), and `personal_access_tokens`
(Sanctum's, never published here).

## 3. Domain services

```
App\Support\Table\TableQuery         results(): LengthAwarePaginator · export(ExportFormat): Response
App\Support\Table\TableConfig        columns, sortable, filterable, filter_type, eager
App\Support\Canonical\CanonicalJson  encode(array): string · hash(array): string
```

**Invariants.** `TableQuery` **always** paginates — no unbounded result set may leave it. Sorting or
filtering on an undeclared column throws. `visibleTo($user)` is applied **before** any filter.
`CanonicalJson` is deterministic across processes and PHP versions
(`../01-design/domain/snapshot-and-audit.md` §2.3).

## 4. Routes and controllers

None. This wave ships no routes.

## 5. Validation

`TableConfig` self-validates on boot: every `sortable` or `filterable` column must exist on the model
and have a declared `filter_type`. A misconfigured table fails at boot, not at request time.

## 6. Authorisation

`TableQuery` requires a `visibleTo` scope on every model it is given. A model without one is a
configuration error and throws.

## 7. UI

`resources/views/components/data/table.blade.php` and its parts, per
`../01-design/ux/data-table.md` §4. Tokens per `../01-design/ux/design-system.md` §2–§3.
`lang/en/cruds.php` and `lang/en/global.php` created — **5,702 translation keys are referenced today
and neither file exists**, so every label in 260 views renders as its raw key.

## 8. Worked example

```
$ php artisan migrate:fresh --seed
  ✔ 0 errors                       # currently fails: ->after('salary') on a column that doesn't exist
$ php artisan test
  Tests: 41 passed
$ vendor/bin/phpstan analyse
  [OK] No errors
$ npm run build && grep -c jquery public/build/assets/*.js
  0
$ gitleaks detect
  no leaks found
```

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M00-R01 | Given an empty database, when `migrate:fresh --seed` runs, then it completes with no error |
| M00-R02 | Given the built frontend, when the output is scanned, then jQuery and DataTables are absent |
| M00-R03 | Given the repository, when `gitleaks` runs, then no secret is found and `.env` is untracked |
| M00-R04 | Given a `TableQuery` on an unindexed sortable column, when the config boots, then it throws |
| M00-R05 | Given the same payload, when `CanonicalJson::hash` runs in two processes, then the hashes match |
| M00-R06 | Given a `TableQuery`, when no page size is given, then results are paginated, never unbounded |
| M00-R07 | Given any Blade view, when rendered, then no `trans()` key resolves to itself |
| M00-R08 | Given `composer audit` and `npm audit`, when CI runs, then the build fails on a high-severity advisory |

## 10. Test cases

| Test | Covers |
|---|---|
| `tests/Feature/Foundation/MigrationTest::test_fresh_migrate_succeeds` | R01 |
| `tests/Feature/Foundation/AssetTest::test_no_jquery_in_build` | R02 |
| CI job `secrets` | R03 |
| `tests/Unit/Table/TableConfigTest::test_rejects_unindexed_sortable_column` | R04 |
| `tests/Unit/Canonical/CanonicalJsonTest` (fixture, cross-version) | R05 |
| `tests/Unit/Table/TableQueryTest::test_always_paginates` | R06 |
| `tests/Feature/Foundation/TranslationTest::test_no_unresolved_keys` | R07 |
| CI job `audit` | R08 |

## 11. Traceability

| Requirement | Artefact | Test |
|---|---|---|
| M00-R01…R08 | `database/migrations/*`, `App\Support\Table\*`, `App\Support\Canonical\*`, `lang/en/*`, `.github/workflows/ci.yml` | as above |

### Deletion manifest

Per DR-002, one reviewable commit: `app/Http/Controllers/{Admin,Frontend,Api}/**` (99) ·
`Auth_backup/**` (7) · top-level `Frontend*.php` (5, shadowed and unroutable) ·
`app/Http/Requests/**` (99) · `resources/views/{admin,frontend,auth_backup}/**` (271) ·
`database/migrations/**` (56) · `tests/Browser/**` (33, cannot run) · `laravel` (stray SQLite) ·
`debug_error.html` (**contains a live session token**) · `verify-*.cjs` · `.phpunit.result.cache` ·
9 dead npm dependencies.

**Immediately, in the same wave:** `.env` out of git and **credentials rotated** — they are in the
public history — `.gitignore` hardened, `gitleaks` blocking in CI.
