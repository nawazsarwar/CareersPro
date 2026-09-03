# Organisational Units — Local, Autonomous, Provider-Fed

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Decision:** DR-009 · **Evidence:** decision register §6

---

## 1. The requirement in one line

> *"Keep all the tables local here… let this app be autonomous and independent totally."*
> *"…so that its absence does not stop the functioning of this application."*

**No runtime code reads Data Lake.** Not a model, not a query, not a fallback. The `datalake` and
`mysql_readonly` connections exist **for import only**, and the verification suite proves it: the
full test suite must pass with both connections **removed from `config/database.php` entirely**.

---

## 2. Schema

```
organisational_unit_types
  id                      bigint PK
  title                   varchar    NOT NULL
  code                    varchar    NOT NULL UNIQUE
  category                enum(academic, administrative)   NOT NULL
  parent_id               → self, nullable
  is_recruitment_eligible boolean    NOT NULL DEFAULT false
  status                  enum(draft, published)
  sort_order              int
  datalake_id             bigint     NULL UNIQUE     -- import provenance only
  timestamps, soft deletes

organisational_units
  id            bigint PK
  title         varchar NOT NULL
  title_hindi   varchar NULL
  title_urdu    varchar NULL
  code          varchar NOT NULL UNIQUE
  type_id       → organisational_unit_types   NOT NULL
  parent_id     → self, nullable
  path          varchar(255) NOT NULL          -- '/1/10/27/'  INDEXED
  depth         tinyint      NOT NULL
  status        enum(draft, published) NOT NULL
  datalake_id   bigint NULL UNIQUE
  timestamps, soft deletes

  INDEX (path)
  INDEX (type_id, status)
```

### 2.1 Seven deliberate differences from the source

| # | Data Lake | Ours | Why |
|---|---|---|---|
| 1 | `code` nullable — **10 of 301 NULL** | **NOT NULL**, unique | It is the snapshot identifier that survives renames. Import assigns `TMP-{datalake_id}` and reports (DH-002) |
| 2 | `category` on **units** — NULL in all 301 | dropped from units, kept on **types** | Dead column in the source; category genuinely lives on the type |
| 3 | `category`, `status` `varchar(255)` | **enums** | Only 2 and 2 distinct values exist |
| 4 | **No FK constraints** — indexes merely *named* `parent_fk_*` | **real FKs** | The naming shows they were intended and lost. ADR-001 integrity |
| 5 | recursive `parent_id` walk only | **materialised `path`** | §3 |
| 6 | every type can host anything | `is_recruitment_eligible` | Only some of the 29 types can carry a vacancy |
| 7 | `title_hindi` / `title_urdu` exist, **0 populated** | **kept** | AMU is multilingual; GIGW-relevant. The columns are right, the data was never entered (DH-004) |

**Spelling:** Data Lake uses `organizational_units` (z) and `organizational_units_types`
(double-pluralised). Ours are `organisational_units` and `organisational_unit_types`. `datalake_id`
preserves the link.

---

## 3. Why `path` is a correctness requirement, not an optimisation

Dean-scoped authorisation (DR-010) runs on **every admin request**. A Dean's-office user of the
Faculty of Arts must see that faculty and its child departments and nothing else.

```sql
-- With path: one indexed range scan
SELECT * FROM posts p
JOIN organisational_units ou ON ou.id = p.organisational_unit_id
WHERE ou.path LIKE '/1/11/%' OR ou.id = 11;
```

Without it, every request walks `parent_id` recursively across 301 units. At 13 Faculties → 111
Departments, with an authorisation check on every list, detail and action, that is not viable.

**Maintenance.** `path` and `depth` are recomputed by an observer on create and on `parent_id`
change, in a transaction, cascading to descendants. Re-parenting is rare (see DH-001) and correctness
matters more than write speed. A `RebuildOrganisationalUnitPaths` command reconciles after a bulk
import, and a nightly check asserts `path` matches the `parent_id` graph.

**Cycle guard.** A unit may not be its own ancestor. Validated on save; a self-referential tree with
a cycle produces an infinite path rebuild.

---

## 4. The import provider

```php
interface OrganisationalUnitProvider
{
    public function types(): iterable;   // OrganisationalUnitTypeDto
    public function units(): iterable;   // OrganisationalUnitDto
}
```

| Driver | Status | Reads |
|---|---|---|
| `datalake-db` | available | `datalakeamu_db.organizational_units` + `..._units_types` over the read-only connection |
| `datalake-api` | future | An HTTP endpoint, if one is ever offered |
| `manual` | **always available** | Nothing. Units are maintained through admin CRUD (M24) |

```php
// config/organisational-units.php
'provider' => env('OU_PROVIDER', 'manual'),
```

**The default is `manual`.** A fresh checkout with no Data Lake credentials works, seeds from a
fixture, and runs. That is what autonomy means in practice.

### 4.1 Import behaviour

```
php artisan ou:import --provider=datalake-db [--dry-run]
```

1. **Idempotent on `datalake_id`.** Re-running updates, never duplicates.
2. **Two passes:** all rows first with `parent_id` null, then wire parents — the source has no FKs
   and no guaranteed ordering.
3. **Rebuild `path` and `depth`** in one pass at the end.
4. **Never silently repairs.** Every anomaly goes to an exception report; the import does not guess.
5. **Never deletes.** A unit absent from the source is marked `status: draft`, not removed — a post
   may reference it.
6. **Transactional.** All or nothing.

### 4.2 Exception report

| Condition | Action | Backlog |
|---|---|---|
| `code` is NULL | assign `TMP-{datalake_id}`, report | DH-002 |
| Unit's `type` inconsistent with its parent's type — e.g. a `Campus`- or `Office of COE`-typed unit whose parent is a `Faculty` | import as-is, **report** | **DH-001** |
| `status = Draft` in source | import as `draft`, **not selectable** for an advertisement | DH-003 |
| `title_hindi` / `title_urdu` empty | carry the NULL | DH-004 |
| Cycle detected | **abort the import** | — |

**DH-001 matters most.** Faculty of Engineering & Technology (id 13) currently parents
`Controller of Examinations`, `Accounts Section COE` and `COE Secretariat`. Dean-scoped authorisation
resolves by subtree, so **that faculty's Dean's office would inherit visibility of COE units**. The
import reports it; the data is corrected at source; the import is re-run.

**Validation rules that follow:** a unit with a `TMP-` code, or `status = draft`, **cannot be
selected as an advertisement's or post's organisational unit** (M16).

---

## 5. The snapshot rule

Advertisements and posts store a **denormalised copy** of their OU at publish:

```
advertisements / posts
  organisational_unit_id        -- soft reference
  ou_code_snapshot              -- 'DENG'
  ou_title_snapshot             -- 'Department of English'
  ou_type_snapshot              -- 'Department'
  ou_path_snapshot              -- '/1/11/56/'
```

**Two independent reasons, either sufficient:**

1. **Historical integrity.** A department renamed in 2028 must not silently rewrite what a 2026
   advertisement said. An RTI request or a service appeal will surface exactly that.
2. **No cross-connection joins, ever.** Filtering and reporting run entirely on local columns.

`ou_path_snapshot` also lets Dean-scoped filtering run **without joining** to `organisational_units`
at all — the post row carries its own ancestry.

**Snapshots are written once, at publish, and never refreshed.** If an OU is corrected *before*
publish, re-selecting it re-snapshots. After publish, a change requires a **corrigendum** — which is
correct: it is a change to a published statutory notice.

---

## 6. Worked example

**Import.** `php artisan ou:import --provider=datalake-db`

```
types:  29 imported (0 updated)
units: 301 imported (0 updated)
paths rebuilt: 301

EXCEPTIONS (10):
  DH-002  10 units with NULL code, assigned TMP-*: ids 118, 141, 199, …
  DH-003  2 units in Draft status: ids 288, 294 — not selectable
  DH-001  3 units whose type is inconsistent with the parent's type:
            id 3   Controller of Examinations       type=Campus       parent=13 (Faculty)
            id 222 Accounts Section COE             type=Office of COE parent=13 (Faculty)
            id 226 COE Secretariat                  type=Office of COE parent=13 (Faculty)
          → reported for correction at source; NOT re-parented
```

**Use.** Faculty of Arts (id 11, code `FART`, path `/1/11/`) → Department of English (id 56, code
`DENG`, path `/1/11/56/`).

A local advertisement is created for a temporary Assistant Professor post in DENG:
`appointment_nature = local`, `tenure_months = 12`. On publish the post snapshots
`{DENG, 'Department of English', 'Department', '/1/11/56/'}`.

The Dean's office of Arts holds `role_user(role: dean_office, organisational_unit_id: 11)`. Their
scrutiny queue filters `ou_path_snapshot LIKE '/1/11/%'` — the post appears. The Dean's office of
Commerce (id 12, path `/1/12/`) does not match, and gets a 403 on the post's URL.

**Data Lake is offline for the whole of this.** Nothing above touches it.

---

## 7. Test strategy

| Test | Asserts |
|---|---|
| **Autonomy** | Full suite passes with `datalake` and `mysql_readonly` **deleted from `config/database.php`**. Fails the build if any runtime code references either |
| Provider default | A fresh install with no Data Lake env vars boots, seeds and serves |
| Import idempotence | Running twice ⇒ identical row counts and identical `updated_at` |
| Import exceptions | A NULL code yields `TMP-*` **and** an exception row — never a silent fill |
| No auto-repair | A mis-parented unit is imported **as-is** and reported; `parent_id` unchanged |
| Path integrity | After import, `path` matches the `parent_id` graph for all 301 |
| Re-parent cascade | Moving a unit updates every descendant's `path` and `depth` |
| Cycle guard | Setting a unit as its own ancestor is rejected |
| Snapshot immutability | Renaming an OU does **not** alter a published post's `ou_title_snapshot` |
| **OU-scoped authorisation** | A Dean's-office user of Faculty X gets **403** on every local advertisement, post, application and scrutiny action of Faculty Y |
| Draft exclusion | A `draft` or `TMP-`coded unit cannot be selected in M16 |

---

## 8. Traceability

| Section | Feeds |
|---|---|
| §2 | M24 Master Data |
| §3 | M25 RBAC — OU scope |
| §4 | M24 import · `../../00-clarify/data-hygiene-backlog.md` |
| §5 | M16 Advertisement Builder · M02 |
| §6 | DR-009 · DR-010 |

---

## 9. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Local schema with 7 deliberate improvements over the source; materialised `path` for Dean-scoped authorisation; pluggable provider defaulting to `manual`; detect-and-report import with 5 exception classes; publish-time snapshot rule. | Implementation team |
