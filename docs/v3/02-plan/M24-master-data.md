# M24 — Master Data Management

**Wave:** 2 · **Scope:** v1
**Depends on:** DR-009 · M00, M25

## 1. Purpose and statutory basis

Every lookup the system depends on, **seeded and correct**. Today all 34 lookup tables are **empty
after `db:seed`** — 240 countries and nothing else — so the application cannot function at all.

The organisational hierarchy is statutory context, not decoration: **CRR Rules 8 and 9.1** vest
sanctioned strength in the Executive Council per unit, and **DR-010** makes the unit the boundary of
Dean's-office authority.

## 2. Data

**Organisational** — full schema in `../01-design/domain/organisational-units.md` §2:
`organisational_unit_types` (29 rows) · `organisational_units` (301 rows, tree + materialised `path`).

**Reference tables** (all seeded): `countries` (240) · `provinces` · `districts` · `postal_codes` ·
`religions` · `categories` (UR, SC, ST, OBC-NCL, **EWS**) · `horizontal_categories` (PwBD, ESM,
Women) · `castes` · `marital_statuses` · `disability_types` (the **five** UGC 2018 cl. 3.4 I
categories) · `qualification_levels` (with `ncrf_level`) · `boards` · `subjects` · `degrees` ·
`pay_levels` · `advertisement_types` · `post_types` (**7 live rows**) · `exam_centres`.

**Named but never created by the previous catalogue, and created here:** faculties, departments,
centres, campuses (all as `organisational_units`), designations (M35), pay levels, subjects, degrees.

## 3. Domain services

```
App\Domain\Organisation\OrganisationalUnitProvider     interface
App\Domain\Organisation\DatalakeDbProvider             driver
App\Domain\Organisation\ManualProvider                 driver — DEFAULT
App\Domain\Organisation\ImportOrganisationalUnits::handle(Provider, bool $dryRun): ImportReport
App\Domain\Organisation\RebuildPaths::handle(): void
```

**Invariants.**
- **No runtime code reads Data Lake.** Import only. The suite passes with `datalake` and
  `mysql_readonly` **removed from `config/database.php`**.
- The import is **idempotent on `datalake_id`**, **transactional**, and **never silently repairs** —
  anomalies go to an exception report.
- It **never deletes**. A unit absent from the source becomes `draft`; a post may reference it.
- A unit may not be its own ancestor. Cycles **abort** the import.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/master/{resource}` | `admin.master.index` | `MasterDataPolicy@viewAny` |
| GET/POST/PATCH/DELETE | `/admin/master/{resource}/{id?}` | `admin.master.*` | `MasterDataPolicy@*` |
| GET | `/admin/organisational-units` | `admin.ou.index` | `OrganisationalUnitPolicy@viewAny` |
| GET | `/admin/organisational-units/tree` | `admin.ou.tree` | as above |
| POST | `/admin/organisational-units/import` | `admin.ou.import` | `OrganisationalUnitPolicy@import` |

Console: `php artisan ou:import --provider=datalake-db [--dry-run]`.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `code` | required, max:50, unique per table, `regex:/^[A-Z0-9\-]+$/` | Use capitals, digits and hyphens only. |
| `title` | required, max:191, unique per table | That title is already in use. |
| `type_id` | required, exists | Select a unit type. |
| `parent_id` | nullable, exists, **not self**, **not a descendant** | A unit cannot be inside itself. |
| `status` | required, in:draft,published | |
| `is_recruitment_eligible` | boolean (types only) | |

**Cross-field.** A unit may only be `published` if its parent is `published`. A unit whose `code`
begins `TMP-` may not be `published` — it carries a placeholder from DH-002.

## 6. Authorisation

`MasterDataPolicy`, `OrganisationalUnitPolicy` — **university-wide**, mutation restricted to
`recruitment_admin` and `super_admin`. Read granted to all staff roles. `import` is `super_admin`
only and always audited.

## 7. UI

Standard tables per `../01-design/ux/data-table.md`. Organisational units additionally offer a
**tree view** with expand/collapse and the `path` shown in the detail panel.

The **import screen** shows a dry-run diff before committing and renders the exception report
grouped by class (DH-001…DH-004) with a link to
`../00-clarify/data-hygiene-backlog.md`.

## 8. Worked example

```
$ php artisan ou:import --provider=datalake-db --dry-run

types:  29 to import (0 update)
units: 301 to import (0 update)

EXCEPTIONS (15)
  DH-002  10 units with NULL code → will assign TMP-{datalake_id}
  DH-003   2 units in Draft status → imported as draft, NOT selectable
  DH-001   3 units whose type is inconsistent with the parent's type:
             id 3   Controller of Examinations    type=Campus         parent=13 (Faculty)
             id 222 Accounts Section COE          type=Office of COE  parent=13 (Faculty)
             id 226 COE Secretariat               type=Office of COE  parent=13 (Faculty)
           → reported for correction at source; NOT re-parented

Nothing written (dry run).
```

After committing, Faculty of Arts is `id 11, code FART, path /1/11/` with three child departments at
`/1/11/{36,56,57}/`. `Faculty` type reports **13 units**, `Department` **111** — the legacy
`faculties` (22) and `departments` (123) tables are **superseded and not imported** (§6.3 of the
decision register).

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M24-R01 | Given a fresh database, when `db:seed` runs, then **every** lookup table has rows |
| M24-R02 | Given the import, when run twice, then row counts are identical and no duplicates exist |
| M24-R03 | Given a unit with a NULL source code, when imported, then it receives `TMP-{id}` **and** an exception row |
| M24-R04 | Given a mis-parented unit, when imported, then `parent_id` is **unchanged** and an exception is reported |
| M24-R05 | Given a completed import, when paths are checked, then `path` matches the `parent_id` graph for all 301 |
| M24-R06 | Given a unit moved to a new parent, when saved, then every descendant's `path` and `depth` update |
| M24-R07 | Given a unit set as its own ancestor, when saved, then it is rejected |
| M24-R08 | Given a `draft` or `TMP-` coded unit, when selecting an advertisement's unit, then it is not offered |
| M24-R09 | Given `config/database.php` with `datalake` and `mysql_readonly` **removed**, when the full suite runs, then it passes |
| M24-R10 | Given a source unit that disappears, when re-imported, then it becomes `draft`, **not deleted** |
| M24-R11 | Given a cycle in the source, when imported, then the import **aborts** and writes nothing |
| M24-R12 | Given no Data Lake credentials, when the app boots, then the `manual` provider is used and the app serves |

## 10. Test cases

`tests/Feature/MasterData/SeederTest` — R01 · `OrganisationalUnit/ImportIdempotenceTest` — R02 ·
`ImportExceptionTest` — R03, R04, R10 · `PathIntegrityTest` — R05, R06 · `CycleGuardTest` — R07, R11 ·
`UnitSelectabilityTest` — R08 · `tests/Architecture/AutonomyTest` — **R09, boots with both
connections removed** · `ProviderDefaultTest` — R12.

Fixtures: `OrganisationalUnitFactory` producing a 3-level tree; a fixture dump of the 29 types and a
representative 40-unit subset.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01 | `database/seeders/*` |
| R02–R04, R10, R11 | `App\Domain\Organisation\ImportOrganisationalUnits` |
| R05–R07 | `App\Domain\Organisation\RebuildPaths`, `OrganisationalUnitObserver` |
| R08 | `App\Rules\SelectableOrganisationalUnit` |
| R09, R12 | `config/organisational-units.php`, `ManualProvider` |
