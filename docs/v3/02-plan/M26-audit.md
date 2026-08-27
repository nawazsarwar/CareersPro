# M26 — Audit & Traceability

**Wave:** 1 · **Scope:** v1
**Depends on:** DR-011 · M00, M25

## 1. Purpose and statutory basis

A tamper-evident, append-only record of every state change, decision, override and document access.

| Requirement | Source |
|---|---|
| Claims verifiable *"at any point of time **even after joining**"* | **CRR Rule 22.4** |
| CVO may investigate **at any stage** | **CRR Rule 34.2** |
| Point-in-time reconstruction for RTI and service appeals | M27 · RTI Act 2005 |
| *"Every document access, score override or state transition must be logged with an immutable audit entry"* | `MEMORY.md` §2 |

**Current state:** `audit_logs` is a stock Spatie activity-log table with a **mutable `updated_at`**,
no `hash`, no `previous_hash` and no sequence — while three separate documents assert hash-chained
immutability. The `Auditable` trait is applied to 27 of 34 models, **omitting `User`, `Role`,
`Permission` and `ResearchPublication`** — the security-sensitive models are precisely the unaudited
ones. And `properties` stores whole rows unredacted, including Aadhaar.

## 2. Data

```
audit_logs
  id            bigint PK
  sequence      bigint UNIQUE      -- global, gapless, monotonic
  previous_hash char(64)           -- genesis = 64 zeros
  hash          char(64)
  event         varchar(64)        -- application.submitted, eligibility.decided, …
  subject_type  varchar(64)        -- MORPH ALIAS, never a class name
  subject_id    bigint NULL
  actor_id      bigint NULL        -- NULL for system actions
  actor_ip      varchar(45)
  actor_role    varchar(64)        -- the role in effect, including OU scope
  properties    json
  occurred_at   timestamp(6)
  -- NO updated_at. NO soft delete.

audit_checkpoints
  id · sequence · cumulative_hash · created_at    -- every 10,000 rows

INDEX (subject_type, subject_id, sequence)
INDEX (actor_id, occurred_at)
INDEX (event, occurred_at)
PARTITION BY RANGE (occurred_at)   -- monthly
```

Database triggers reject `UPDATE` and `DELETE`.

## 3. Domain services

```
App\Domain\Audit\RecordAuditEvent::handle(AuditEvent): AuditLog
App\Domain\Audit\VerifyAuditChain::handle(?int $from, ?int $to): ChainReport
App\Domain\Audit\SequenceAllocator::next(): int      // serialised
App\Domain\Audit\RedactProperties::handle(array): array
App\Domain\Audit\Auditable                            // trait, ALL models
```

**Invariants.**
- `hash(n) = SHA-256(CanonicalJson([sequence, previous_hash, event, subject_type, subject_id, actor_id, actor_ip, actor_role, properties, occurred_at]))`.
- The sequence is **gapless** — allocated from a counter row taken `FOR UPDATE`, or a single-writer
  queue. Gapless is asserted, so it is enforced.
- `RedactProperties` replaces sensitive values with `{"changed": true, "hash": "…"}`. Redacted:
  `aadhaar_no`, `password`, `remember_token`, `otp_code`, `two_factor_secret`, gateway credentials.
- **No update path exists.** Not on the model, not on the repository, not in the database.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/admin/audit` | `admin.audit.index` | `auth`, `verified`, `2fa` | `AuditPolicy@viewAny` |
| GET | `/admin/audit/{log}` | `admin.audit.show` | as above | `AuditPolicy@view` |
| POST | `/admin/audit/verify` | `admin.audit.verify` | as above | `AuditPolicy@verify` |
| GET | `/admin/audit/subject/{type}/{id}` | `admin.audit.subject` | as above | `AuditPolicy@viewAny` |

**No create, update or delete routes exist.** Entries are written by the domain, never by a request.

## 5. Validation

Not user-authored, so validation is on the event contract: `event` must match a registered event
name; `subject_type` must be a registered morph alias; `occurred_at` must be server-generated.
An unregistered event name throws in all environments — a typo'd event is a silent hole in the record.

## 6. Authorisation

`AuditPolicy` — **read-only for `auditor` and `super_admin`, university-wide.** No role can create,
edit or delete. `viewAny` requires the `audit.view` permission; entries whose subject is a candidate
record are additionally filtered by the actor's scope so an OU-scoped auditor sees only their subtree.

## 7. UI

Standard table: sequence · occurred at · event · subject · actor · actor role · IP. Filters on event,
actor, subject type and date range. Detail shows the redacted `properties` diff and the chain
position with the previous and next hashes.

**Verification is a visible action** with a clear result: *"Chain verified, sequences 1–184,502"* or
*"Chain broken at sequence 92,118."* The second is a **P1 security incident**, and the UI says so
rather than showing a yellow warning.

## 8. Worked example

A scrutiny officer sets the scrutiny gate to `eligible` on application 10087779.

```json
{ "sequence": 184502,
  "previous_hash": "9f2c…a11b",
  "hash": "4e88…c730",
  "event": "eligibility.decided",
  "subject_type": "application", "subject_id": 10087779,
  "actor_id": 331, "actor_ip": "10.4.22.19", "actor_role": "dean_office@/1/11/",
  "properties": { "gate": "scrutiny", "from": null, "to": "eligible",
                  "remark": "Documents verified against claims." },
  "occurred_at": "2026-03-11T09:41:22.418Z" }
```

Six weeks later someone edits `properties` directly in the database to change `to` to `rejected`.
`hash(184502)` no longer matches the stored value, and `previous_hash` at 184503 no longer matches
`hash(184502)`. The nightly `VerifyAuditChain` reports **the first divergent sequence: 184502**.
Rewriting history would require rewriting every subsequent row **and** the signed checkpoints.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M26-R01 | Given any model mutation, when it commits, then an audit entry is written with actor, IP and role |
| M26-R02 | Given an audit row, when `UPDATE` is attempted, then the database rejects it |
| M26-R03 | Given an audit row, when `DELETE` is attempted, then the database rejects it |
| M26-R04 | Given a tampered row *n*, when the chain is verified, then it reports **exactly** sequence *n* |
| M26-R05 | Given 1,000 concurrent writes, when they complete, then the sequence has no gaps and no duplicates |
| M26-R06 | Given a profile update containing an Aadhaar change, when audited, then `aadhaar_no` appears **only as a hash**, never as a value |
| M26-R07 | Given a document read, when it is served, then a `document.accessed` entry is written with actor and IP |
| M26-R08 | Given the model list, when enumerated, then **every** model in `app/Models` emits audit events — including `User`, `Role` and `Permission` |
| M26-R09 | Given an export, when generated, then `export.generated` records row count and applied filters |
| M26-R10 | Given an impersonated session, when any action occurs, then the entry records **both** the actor and the impersonated user |
| M26-R11 | Given 10,000 new entries, when they commit, then a signed checkpoint is written |
| M26-R12 | Given a subject, when its timeline is requested, then entries return in sequence order |

## 10. Test cases

`tests/Feature/Audit/ChainIntegrityTest` — R04, R11 · `ImmutabilityTest` — R02, R03 ·
`SequenceConcurrencyTest` (1,000 parallel) — R05 · `RedactionTest` — R06 ·
`DocumentAccessTest` — R07 · `tests/Architecture/AuditCoverageTest` — R08, **enumerated from
`app/Models`, not sampled** · `ExportAuditTest` — R09 · `ImpersonationAuditTest` — R10 ·
`TimelineTest` — R12 · `Unit/HashStabilityTest` — cross-process, cross-version.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R08, R10 | `App\Domain\Audit\Auditable`, `RecordAuditEvent` |
| R02, R03 | migration triggers + repository guards |
| R04, R11 | `App\Domain\Audit\VerifyAuditChain`, `audit_checkpoints` |
| R05 | `App\Domain\Audit\SequenceAllocator` |
| R06 | `App\Domain\Audit\RedactProperties` |
| R07, R09 | `App\Domain\Documents\ServeDocument`, `App\Support\Table\Export` |
