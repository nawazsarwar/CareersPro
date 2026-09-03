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

**Configuration files created in this wave** (DR-023, DR-024, engineering-standards §7.1):

| File | Holds |
|---|---|
| `config/auth_channels.php` | Per-user-class default login channel and mobile-verification flag — the boot default that `system_settings` later overrides |
| `config/otp.php` | Code length, TTL (`OTP_VALID_MINUTES`, default **10**), resend cooldown (`OTP_DELAY_MINUTES`, default **3**), hourly cap (`AUTH_OTP_MAX_PER_HOUR`, default **5**), default gateway |
| `config/services.php` | The `proactive` credential block — separate `user` and `password` keys, **never** a composed URL |

`.env.example` gains every key above with the credentials **blank**. Two existing values in
`config/auth.php` are corrected at the same time: the broker table stays `password_reset_tokens`, and
`password_timeout` drops from **10800** to **900** seconds
(`../01-design/security/security-model.md` §4).

**Composer.** `pragmarx/google2fa` and `bacon/bacon-qr-code` are added; `laravel/breeze` and
`laravel/ui` are removed with the scaffolding they generated (DR-022).

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
`../01-design/ux/data-table.md` §4. Tokens per `../01-design/ux/design-system.md` §2–§3, carried in
`resources/css/app.css` as CSS custom properties with the complete light palette on bare `:root` and
the dark blocks redefining only what changes.

**Correction — the translation files were not missing.** Earlier drafts of this section, and the
audit behind them, recorded that *"5,702 translation keys are referenced and neither
`lang/en/cruds.php` nor `lang/en/global.php` exists, so every label in 260 views renders as its raw
key."* Both files did exist, at the **Laravel 8 path** `resources/lang/en/` — 56 KB of `cruds.php`
across 36 namespaces and 283 keys in `global.php` — and Laravel resolved that path because the
directory was present. Labels were rendering correctly.

What was actually wrong was **two lang directories**: `lang/` (the canonical path, holding the
framework's own four files) and `resources/lang/` (holding the application's). The second shadowed
the first. This wave deletes `resources/lang/` and consolidates onto `lang/`. `cruds.php` is not
carried across: every one of the 260 views it labelled is deleted in this wave, so it labels
nothing. Module strings arrive with their modules, and
`tests/Feature/Foundation/TranslationTest` fails the build if any rendered key resolves to itself,
which is what stops the backlog re-accumulating.

## 8. Worked example

```
$ php artisan migrate:fresh --seed
  ✔ 0 errors                       # previously failed: ->after('salary') on a column that does not exist
$ vendor/bin/pest
  Tests: 38 passed (67 assertions)
$ vendor/bin/phpstan analyse --memory-limit=1G
  [OK] No errors                   # level 6
$ vendor/bin/pint --test
  passed
$ npm run build && grep -rilE 'jquery|datatables' public/build/assets
  (no output)
$ gitleaks detect
  no leaks found
```

`--memory-limit=1G` is required: the default 128 MB crashes the parallel worker. The CI job carries
the same flag.

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
| M00-R09 | Given the config cache is built, when the application boots, then no `env()` call outside a config file is reached |
| M00-R10 | Given `.env.example`, when it is read, then every gateway credential key is present and **empty** |

## 10. Test cases

| Test | Covers |
|---|---|
| `tests/Feature/Foundation/MigrationTest` | R01 |
| `tests/Feature/Foundation/AssetTest` | R02 |
| CI job `secrets` — `gitleaks` over full history, plus a tracked-`.env` guard | R03 |
| `tests/Unit/Table/TableConfigTest` | R04 |
| `tests/Unit/Canonical/CanonicalJsonTest` | R05 |
| `tests/Unit/Table/TableQueryTest` | R06 |
| `tests/Feature/Foundation/TranslationTest` | R07 |
| CI job `audit` — `composer audit` and `npm audit --audit-level=high` | R08 |
| `tests/Unit/Arch/NoEnvOutsideConfigTest` | R09 |
| `tests/Feature/Foundation/EnvExampleTest` | R10 |
| `tests/Unit/Arch/ArchitectureTest` | strict types, no debug helpers, TOTP libraries fenced to `App\Domain\Identity\SecondFactor\Totp` (DR-022) |

**Static analysis covers `app`, `config`, `database` and `routes`, not `tests`.** Pest's fluent API
resolves to `Pest\PendingCalls\TestCall` under PHPStan, so analysing the suite produces false
positives that can only be silenced with ignores — and an `ignoreErrors` list that starts non-empty
stops being read. The reason is recorded in `phpstan.neon` beside the exclusion. The suite is checked
by running it, and by the architecture tests above.

## 11. Traceability

| Requirement | Artefact | Test |
|---|---|---|
| M00-R01…R08 | `database/migrations/*`, `App\Support\Table\*`, `App\Support\Canonical\*`, `lang/en/*`, `.github/workflows/ci.yml` | as above |
| M00-R09, R10 | `config/auth_channels.php`, `config/otp.php`, `config/services.php`, `.env.example` | `Unit/Arch/NoEnvOutsideConfigTest`, `Unit/EnvExampleTest` |

### Deletion manifest

Per DR-002, one reviewable commit: `app/Http/Controllers/{Admin,Frontend,Api}/**` (99) ·
`Auth_backup/**` (7) · top-level `Frontend*.php` (5, shadowed and unroutable) ·
`app/Http/Requests/**` (99) · `resources/views/{admin,frontend,auth_backup}/**` (271) ·
`database/migrations/**` (56) · `tests/Browser/**` (33, cannot run) · `laravel` (stray SQLite) ·
`debug_error.html` (**contains a live session token**) · `verify-*.cjs` · `.phpunit.result.cache` ·
9 dead npm dependencies.

**Immediately, in the same wave:** `.gitignore` hardened and `gitleaks` blocking in CI.

**Deleted beyond the manifest, and why.** Four groups were not on the list above but could not
coherently survive it:

- **33 of 34 Eloquent models**, and all 27 `App\Http\Resources\Admin\*`. Their tables went with
  the migrations and their callers went with the controllers, so they modelled nothing. Each wave
  introduces its models with the migrations that back them. `User` is kept as a placeholder because
  `config/auth.php` names it as the provider model and the container cannot resolve the guard
  without it; **M03 owns its real shape**, and it already carries `HasApiTokens`.
- **`app/Http/Kernel.php`, `app/Console/Kernel.php` and `app/Exceptions/Handler.php`** — nothing has
  referenced them since the Laravel 11 skeleton moved that configuration to `bootstrap/app.php`.
  `throttle:api` living only in the dead HTTP kernel is precisely why the API had no rate limiting.
- **`App\Traits\Auditable`** — M26 replaces it with `App\Domain\Audit\Auditable`, which is
  hash-chained and covers every model rather than 27 of 34.
- **`resources/views/{layouts,partials,components}` and `resources/js/components`** — the TailAdmin
  shim: a jQuery/DataTables layout, a menu built from deleted routes, and six demo chart modules.
  `resources/css/app.css` was 881 lines of template CSS for carousels and stock sliders that no
  screen in this system has; it is replaced by the design-system tokens.

**Two config defects fixed while the files were open.** `config/sanctum.php` named
`App\Http\Middleware\VerifyCsrfToken` and `EncryptCookies`, neither of which has existed since the
published kernel was removed; and `config/database.php` used `PDO::MYSQL_ATTR_SSL_CA`, deprecated in
PHP 8.5, which emitted a deprecation on every test run.


**Correction — `.env` was never committed.** `git log --all -- .env` returns **zero commits**, the
file is untracked, and `.gitignore:8` has covered it throughout. Earlier drafts of this manifest and
of `../01-design/security/security-model.md` §5 called for **rotating the MySQL credentials on the
grounds that they were in the public history. They are not, and no rotation is required on that
basis.** The claim is recorded here as withdrawn so that nobody reinstates it from the old text.

What *is* in the history is **`debug_error.html`**, added by commit `2c8a071` and carrying a live
CSRF and session token. Deleting it from the working tree does not remove it from history, so the
token it exposes is treated as compromised: the session is long expired and `APP_KEY` is unaffected,
but the file is the reason `gitleaks` runs over **history as well as the diff**, not just the diff.
