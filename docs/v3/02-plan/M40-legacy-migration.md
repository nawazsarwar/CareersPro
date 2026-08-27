# M40 — Legacy Migration & Cut-over

**Wave:** 10 · **Scope:** v1
**Depends on:** every other module
**Blocked by:** **OQ-004** *(dual-run window; disposition of the orphan backup rows; destination
schema for the financial history)*

## 1. Purpose and statutory basis

Move `careers_db` into the new schema without losing a record.

| Obligation | Source |
|---|---|
| **Nothing is deleted electronically** | DR-011 |
| Claims verifiable *"at any point of time even after joining"* — so historical applications must remain reconstructible | CRR Rule 22.4 |
| Financial records retained for audit | Finance Office |
| RTI over past recruitment drives | RTI Act 2005 |

**What `docs/v2-archive/spec/migration-plan.md` says:** *"5-6 years of legacy data"*, dual-run, ETL, hashes,
rollback — **15 lines, no volumetrics, and it skips every hard part.**

## 2. Data — what is actually there

| Table | Rows | Problem |
|---|---:|---|
| `applicationforms` | **78,232** | Real payload lives in `basic_details` + `additional_details` **`longtext` blobs**; 26 columns are denormalised regenerations of them |
| `users` | **55,050** | |
| `uploads` | **92,064** | **`media` has 0 rows** — an abandoned migration to Spatie left in production |
| `orders` | **45,280** | |
| `receivables` | **22,584** | |
| **`transactions`** | **0** | Empty, while orders hold ₹2.29 crore |
| **`services`** | **0** | Empty |
| `additionaldetails` | 339,504 | Duplicates `otherdetails` (13,787) |
| `academic_qualifications` | 135,808 | Duplicates `profile_academicqualifications` (110,528) |
| `otps` | 25,527 | No expiry or cleanup |
| `password_resets` | 1,265 | No expiry |
| `pincodes` | **0** | Empty, while the form asks for PIN codes |
| `scrutiny_reports_all_it_posts` | 382 | A one-off drive **frozen into DDL** |
| `applicationforms_20102025_1856` | **71,671** | **Orphan backup in the production schema** |
| `applicationforms_24072026_0300` | **78,568** | Orphan backup |
| `applicationforms_backup_27012025_1709` | **65,707** | Orphan backup |

**215,946 orphan rows across three ad-hoc `CREATE TABLE … SELECT` backups.** OQ-004 must decide their
disposition — the working assumption is **archive to cold storage, do not migrate**, because they are
point-in-time copies of a table we are migrating in full.

## 3. Domain services

```
App\Domain\Migration\ExtractLegacy::table(string): LazyCollection
App\Domain\Migration\DecomposeBlob::handle(string $json, BlobSchema): array
App\Domain\Migration\MapOrganisationalUnit::from(string $title, string $location): ?int
App\Domain\Migration\MigrateTable::handle(string, MigrationOptions): MigrationReport
App\Domain\Migration\ReconcileCounts::handle(): ReconciliationReport
App\Domain\Migration\BuildLegacySnapshot::for(int $legacyApplicationId): ApplicationSnapshot
```

**Invariants.**
- **Idempotent on `legacy_id`.** Every migrated row carries its source id; re-running updates, never
  duplicates.
- **Nothing is deleted from the source.** `careers_db` is read through `mysql_readonly` and stays
  intact until the Finance Office and Registrar sign off.
- **Every legacy application gets a snapshot** (`reason: migration`) so M27 reconstruction works for
  historical records too. Without it, the RTI capability has a five-year hole.
- **Unmappable rows are quarantined and reported, never guessed.** Especially organisational units —
  see §5.
- **Row counts and checksums must reconcile before cut-over.** A migration that "mostly worked" is a
  failed migration.

## 4. Routes and controllers

Console only. No web routes — a migration behind a web request is a migration that times out.

```
php artisan migrate:legacy --table=users        [--dry-run] [--chunk=1000]
php artisan migrate:legacy:blobs                [--dry-run]
php artisan migrate:legacy:uploads              [--dry-run]
php artisan migrate:legacy:finance              [--dry-run]
php artisan migrate:legacy:snapshots            [--dry-run]
php artisan migrate:legacy:reconcile
php artisan migrate:legacy:report
```

## 5. Validation — the five hard problems

**1. The `longtext` blobs.** `basic_details` and `additional_details` hold the real application
payload, unindexed and unvalidated. `DecomposeBlob` parses each against a declared schema per post
type and writes to the normalised tables. **Malformed or unparseable blobs are quarantined with the
raw text retained** — not dropped, not guessed.

**2. Organisational units.** `careers_db.posts` has **no organisational-unit reference at all**; the
department is embedded in free text — *"Assistant Professor, Dept of Conservative Dentistry &
Endodontics"* — and `location varchar(300)`. `MapOrganisationalUnit` matches against the 301 local
units by normalised title and code. **Ambiguous or unmatched posts go to a manual review queue.**
~2,874 posts, so this is a real task with a human in it, not a join.

**3. Uploads.** 92,064 rows in `uploads`, **0 in `media`**. Files are re-ingested into the new
document store, hashed, and linked. **A missing file is reported, never silently skipped** — an
application whose evidence is gone must be visible as such.

**4. Finance.** `orders` 45,280 and `receivables` 22,584 have **no destination schema** in the
previous redesign, and `transactions`/`services` are empty. The new `orders`/`transactions` tables
(M08) receive them. **Reconciliation against the Finance Office's own totals is a hard gate**: the
migrated sum must equal ₹2,29,94,500 received, or cut-over does not proceed.

**5. Duplicate concepts.** `additionaldetails` (339,504) vs `otherdetails` (13,787);
`academic_qualifications` (135,808) vs `profile_academicqualifications` (110,528). **The precedence
rule is declared per pair, in writing, before migration** — not decided by whichever runs last.

**Also:** `scrutiny_reports_all_it_posts` (382 rows, a one-off drive in DDL) is exported to cold
storage and **not** migrated. Expired `otps` and `password_resets` are not migrated.

## 6. Authorisation

Console commands run under a deployment identity. **`mysql_readonly` is read-only at the database
grant level**, not by convention. Every run writes an audit entry with the operator, table, counts
and duration.

## 7. UI

A **migration dashboard**, read-only: per-table source count, migrated count, quarantined count,
percentage, last run. Quarantine queues are worked in the UI — the organisational-unit review queue
in particular, where an operator maps a legacy post title to a unit and the mapping is remembered for
identical titles.

**The reconciliation report is the go/no-go artefact**, and it says so:

```
users                55,050 → 55,050    ✔
applicationforms     78,232 → 78,232    ✔
uploads              92,064 → 91,998    ✘  66 files missing at source
orders               45,280 → 45,280    ✔  ₹2,29,94,500 ✔ matches Finance
posts → org units     2,874 →  2,801    ✘  73 awaiting manual mapping
snapshots                  →  78,232    ✔
CUT-OVER: BLOCKED (2 gates)
```

## 8. Worked example

**Dual-run window** (OQ-004 sets the length; assume 30 days).

1. **Day −30.** Full migration into the new schema. Reconciliation: 3 gates fail — 66 missing
   uploads, 73 unmapped posts, one blob quarantine batch.
2. **Days −29 to −8.** The 73 posts are mapped in the review queue. The 66 missing files are traced;
   61 recovered, 5 confirmed lost at source and recorded on those applications as
   *"evidence unavailable — file missing in the legacy system."* Quarantined blobs are parsed against
   a corrected schema.
3. **Day −7.** Reconciliation green. The Finance Office signs off the ₹2,29,94,500 match.
4. **Days −7 to 0.** Legacy is **read-only**. Delta migration runs nightly for records changed in the
   window.
5. **Day 0.** Final delta, final reconciliation, DNS cut-over. Legacy stays online read-only for
   90 days.
6. **Day +1.** An RTI request about the 2023 drive. M27 reconstructs from a **migration snapshot** —
   which exists because every legacy application received one.
7. **The three orphan backup tables are never migrated.** They are exported to cold storage with a
   manifest, per OQ-004.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M40-R01 | Given a migration run, when repeated, then row counts are identical and no duplicates exist |
| M40-R02 | Given the source database, when migration completes, then **no source row is modified or deleted** |
| M40-R03 | Given an unparseable blob, when migrated, then it is quarantined with the raw text retained |
| M40-R04 | Given an unmappable post title, when migrated, then it enters the review queue, **not a guessed unit** |
| M40-R05 | Given a missing upload, when migrated, then it is reported and the application is marked, never silently skipped |
| M40-R06 | Given finance migration, when reconciled, then the total equals ₹2,29,94,500 |
| M40-R07 | Given a mismatch in any gate, when cut-over is attempted, then it is **blocked** |
| M40-R08 | Given a legacy application, when migrated, then a snapshot exists with `reason: migration` |
| M40-R09 | Given a migrated legacy application, when reconstructed via M27, then it succeeds |
| M40-R10 | Given the orphan backup tables, when migration runs, then they are **not** migrated |
| M40-R11 | Given expired OTPs and reset tokens, when migration runs, then they are not migrated |
| M40-R12 | Given a duplicate-concept pair, when migrated, then the declared precedence rule is applied and recorded |
| M40-R13 | Given a delta run, when it completes, then only records changed since the last run are touched |
| M40-R14 | Given any run, when it completes, then an audit entry records operator, table, counts and duration |

## 10. Test cases

`tests/Feature/Migration/IdempotenceTest` — R01, R13 · `SourceIntegrityTest` — **R02** ·
`BlobDecompositionTest` — R03, R12 · `OrganisationalUnitMappingTest` — R04 ·
`UploadMigrationTest` — R05 · `FinanceReconciliationTest` — **R06** ·
`CutOverGateTest` — **R07** · `SnapshotTest` — R08, R09 · `ExclusionTest` — R10, R11 ·
`AuditTest` — R14.

Fixtures: an anonymised extract of 500 legacy applications including a malformed blob, an ambiguous
post title, a missing upload and a duplicate-concept conflict — so every hard case is exercised
before touching production.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R13 | `App\Domain\Migration\MigrateTable`, `legacy_id` unique indexes |
| R02 | `mysql_readonly` grant + `SourceIntegrityTest` |
| R03, R12 | `App\Domain\Migration\DecomposeBlob` |
| R04 | `App\Domain\Migration\MapOrganisationalUnit` |
| R05 | `App\Console\Commands\MigrateLegacyUploads` |
| R06, R07 | `App\Domain\Migration\ReconcileCounts` |
| R08, R09 | `App\Domain\Migration\BuildLegacySnapshot` |
| R10, R11, R14 | migration command exclusion list |
