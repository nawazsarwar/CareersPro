# M28 — System Administration

**Wave:** 9 · **Scope:** **v1-partial** *(backup controls deferred to v2 — infrastructure concern)*
**Depends on:** M25, M26

## 1. Purpose and statutory basis

Operational control: feature flags, theme, background-job monitoring, system health.

No direct statutory basis. Two obligations bear on it: **CRR Rule 34.2** gives the CVO investigation
powers at any stage, so job and error visibility must be available to an investigator; and **DR-011**
means no administrative action may delete a domain record.

**What this deliberately replaces.** The legacy habit of `CREATE TABLE … SELECT` backups **inside the
production schema** — three ad-hoc copies of the applications table holding **215,946 orphan rows**
(`applicationforms_20102025_1856`, `applicationforms_24072026_0300`,
`applicationforms_backup_27012025_1709`). That is the practice this module exists to make
unnecessary, not to reproduce.

## 2. Data

```
feature_flags   id · key UNIQUE · description
                enabled bool · rollout json NULL     -- role or OU targeting
                updated_by_id · updated_at
system_settings id · key UNIQUE · value json · group · description
                is_sensitive bool · updated_by_id
job_failures    -- Laravel's failed_jobs, surfaced read-only
```

**Indexes:** `feature_flags.key` unique · `system_settings(group, key)`.

**No table here holds domain data**, and no route here can write to one.

## 3. Domain services

```
App\Domain\System\FeatureFlags::enabled(string $key, ?User $u = null): bool
App\Domain\System\Settings::get(string $key): mixed
App\Domain\System\HealthCheck::run(): HealthReport
App\Domain\System\RetryFailedJob::handle(string $uuid, User $actor): void
```

**Invariants.**
- **Flag and setting changes are audited** with before and after. A flag that silently changes
  behaviour on a statutory system is an unrecorded decision.
- **`is_sensitive` settings are never rendered** — only *"set"* or *"not set"*, and never returned by
  an API.
- **`HealthCheck` explicitly asserts autonomy**: it reports the `datalake` and `mysql_readonly`
  connections as **import-only and not required**, and reports **healthy** when they are absent
  (DR-009).
- No administrative route deletes a domain record. Failed jobs may be retried or dismissed; the
  underlying record is untouched.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/system` | `admin.system.index` | `SystemPolicy@view` |
| GET/PATCH | `/admin/system/flags/{flag?}` | `admin.system.flags.*` | `@manageFlags` |
| GET/PATCH | `/admin/system/settings` | `admin.system.settings.*` | `@manageSettings` |
| GET | `/admin/system/jobs` | `admin.system.jobs` | `@viewJobs` |
| POST | `/admin/system/jobs/{uuid}/retry` | `admin.system.jobs.retry` | `@manageJobs` |
| GET | `/admin/system/health` | `admin.system.health` | `@view` |
| GET | `/up` | `health` | — *(unauthenticated liveness)* |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| flag `key` | required, unique, `regex:/^[a-z0-9_.]+$/` | Use lowercase letters, digits, dots and underscores. |
| flag `rollout` | nullable, json · roles and OUs must exist | |
| setting `value` | required · **must match the setting's declared type** | {key} must be a {type}. |
| sensitive setting | **`super_admin` only**, `password.confirm` required | |
| job retry | uuid exists in `failed_jobs` | |
| | **the job class must be on the retry allow-list** | This job type cannot be retried from the interface. |

**The retry allow-list matters:** blindly retrying a payment or dispatch job can double-charge or
double-send. Only idempotent jobs are retryable here.

## 6. Authorisation

`SystemPolicy` — `view` and `viewJobs` for `super_admin` and `auditor` (read-only);
`manageFlags`, `manageSettings`, `manageJobs` for **`super_admin` only**, with password confirmation
on sensitive settings. Every mutation audited.

## 7. UI

**Flags:** key, description, state, rollout, last changed by and when. Toggling a flag that affects a
**published** advertisement warns first and names the affected posts.

**Settings** grouped, with sensitive values masked as *"set"* / *"not set"*.

**Jobs:** failed jobs with class, queue, exception and attempts. Retry is offered only for
allow-listed classes; others show why not.

**Health** as a plain checklist — database, cache, queue, storage, mail — with the autonomy line
stated explicitly:

> Data Lake connection: **not configured — not required.** Organisational units are held locally
> (DR-009).

## 8. Worked example

1. An admin enables `claims.doi_lookup` for the `candidate` role. Audited with before and after.
2. They attempt to disable `payment.enabled` while advertisement 2/2026/NT is open → warning:
   *"3 published posts are accepting payments. Disabling this will block 41 candidates mid-application."*
   They cancel.
3. A `DispatchCampaignBatch` job fails on a provider timeout. It is on the retry allow-list
   (idempotent per recipient) → retried → succeeds.
4. A `CreateOrder` job appears in failures. Retry is **not** offered: *"Payment jobs cannot be
   retried from the interface. Use the reconciliation queue."* — the correct route, since
   reconciliation is idempotent and a blind retry is not.
5. Health, on a machine with no Data Lake credentials: **all green**, with the autonomy line above.
   That is the DR-009 guarantee visible in the product, not only in a test.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M28-R01 | Given a flag change, when saved, then an audit entry records before and after |
| M28-R02 | Given a flag affecting published posts, when toggled, then a warning names the affected posts |
| M28-R03 | Given a sensitive setting, when viewed, then only *"set"* or *"not set"* is shown |
| M28-R04 | Given a sensitive setting, when changed without password confirmation, then it is refused |
| M28-R05 | Given a non-allow-listed job, when retry is attempted, then it is refused with the reason |
| M28-R06 | Given an allow-listed job, when retried, then it runs idempotently |
| M28-R07 | Given no Data Lake connection, when health runs, then it reports **healthy** and states import-only |
| M28-R08 | Given a non-`super_admin`, when changing a flag, then **403** |
| M28-R09 | Given `auditor`, when viewing settings, then read succeeds and write is refused |
| M28-R10 | Given any administrative route, when exercised, then **no domain record is deleted** |
| M28-R11 | Given a setting value of the wrong type, when saved, then validation fails |
| M28-R12 | Given `/up`, when requested unauthenticated, then it returns liveness without disclosing configuration |

## 10. Test cases

`tests/Feature/System/FlagTest` — R01, R02, R08 · `SettingTest` — R03, R04, R09, R11 ·
`JobRetryTest` — R05, R06 · `HealthTest` — **R07**, R12 ·
`tests/Architecture/NoDomainDeletionTest` — **R10, enumerated over all admin routes**.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\System\FeatureFlags` |
| R03, R04, R11 | `App\Domain\System\Settings` |
| R05, R06 | `App\Domain\System\RetryFailedJob`, the retry allow-list |
| R07, R12 | `App\Domain\System\HealthCheck` |
| R08–R10 | `App\Policies\SystemPolicy` |
