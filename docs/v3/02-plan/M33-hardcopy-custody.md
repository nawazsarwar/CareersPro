# M33 — Application Receipt & Hardcopy Custody

**Wave:** 6 · **Scope:** **v1-partial** *(scope reduces to migration-only if hard copy is ever dropped)*
**Depends on:** DR-011 · M05, M26

## 1. Purpose and statutory basis

Track the physical dossier from receipt to destruction. A live admin sidebar section today
(*Application Receipt*), and **absent from the previous module catalogue entirely.**

| Obligation | Source |
|---|---|
| Applications addressed to *"The Registrar"* in a closed cover **super-scribing the post applied for** | CRR Rule 11 III(e) |
| Incomplete and late applications not entertained; VC may admit a late one **on proof it was posted on or before the closing date** | CRR Rule 11 III(d) |
| Submission venue is per post type | `post_types.submission_venue` |
| **Selected candidates who joined: permanent. Unsuccessful: destroyed after 5 years** | **DR-011** |

**DR-011 is the shape of this module.** Nothing is destroyed electronically. The weeding is a
**physical-custody process**, and this is where it lives.

## 2. Data

```
hardcopy_receipts
  id · application_id UNIQUE
  status enum(awaited, received, not_required)
  received_at NULL · received_by_id NULL · dispatch_ref NULL
  postmark_date NULL                       -- CRR Rule 11 III(d) late-application proof
  late bool · late_admitted_by_id NULL · late_admission_ref NULL
  storage_location NULL
  destruction_due_on NULL                  -- process close + 5 years, unsuccessful only
  destroyed_at NULL · destroyed_by_id NULL · destruction_batch_ref NULL

destruction_batches
  id · batch_ref UNIQUE · authorised_by_id · authorised_on · executed_on NULL · item_count
```

**Indexes:** `hardcopy_receipts(status, destruction_due_on)` · `(application_id)` unique.

## 3. Domain services

```
App\Domain\Custody\RecordReceipt::handle(Application, ReceiptData, User): HardcopyReceipt
App\Domain\Custody\AdmitLateApplication::handle(HardcopyReceipt, string $ref, User): void
App\Domain\Custody\ScheduleDestruction::handle(Post): int          -- on process close
App\Domain\Custody\ExecuteDestruction::handle(DestructionBatch, User): void
```

**Invariants.**
- **`ExecuteDestruction` touches no electronic record.** It sets `destroyed_at` and writes a
  `hardcopy.destroyed` audit event. The application, its snapshots, documents and audit chain are
  untouched (DR-011).
- `ScheduleDestruction` sets `destruction_due_on` **only for unsuccessful candidates**. Selected and
  joined candidates are marked permanent and never scheduled.
- A late receipt may be admitted **only** with a postmark on or before the closing date **and** a
  recorded VC authorisation reference (CRR Rule 11 III(d)).
- Destruction requires a **batch with a prior authorisation**. No single-record deletion path exists.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/receipts` | `admin.receipts.index` | `CustodyPolicy@viewAny` |
| POST | `/admin/receipts/{application}` | `admin.receipts.store` | `@record` |
| POST | `/admin/receipts/{receipt}/admit-late` | `admin.receipts.admitLate` | `@admitLate` |
| GET | `/admin/custody/due` | `admin.custody.due` | `@viewAny` |
| POST | `/admin/custody/batches` | `admin.custody.batches.store` | `@authoriseDestruction` |
| POST | `/admin/custody/batches/{batch}/execute` | `admin.custody.batches.execute` | `@executeDestruction` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| application | **submitted** | |
| `received_at` | required, date, `before_or_equal:today` | |
| `postmark_date` | **required when `received_at` is after the closing date** | Record the postmark date for a late receipt. |
| late admission `ref` | required, min:5 · **postmark must be ≤ the closing date** | A late application may be admitted only if posted on or before the closing date. |
| `storage_location` | required when `status = received`, max:100 | Record where the dossier is stored. |
| batch `authorised_by` | **`super_admin` or `recruitment_admin`** | |
| batch execution | **every item's `destruction_due_on` must have passed** | {n} items are not yet due for destruction. |

## 6. Authorisation

`CustodyPolicy` — `record` for `recruitment_admin` and the Selection Committee Sections;
`admitLate` for `super_admin` only (it is a VC-delegated decision);
`authoriseDestruction` and `executeDestruction` for `super_admin` only, **both audited**.

## 7. UI

**Receipt desk:** scan or type the application number, confirm the candidate and post, record
storage location. Optimised for a clerk processing a postal batch — one field, keyboard-only.

**Due-for-destruction queue:** grouped by post and process-close date, with counts. Building a batch
requires an authorisation reference before anything can be executed.

The screen states plainly, because it is the most misunderstood thing in the system:

> Destroying a batch removes **physical dossiers only**. Electronic records, documents and the audit
> trail are retained permanently.

## 8. Worked example

Post 2599 closes 7 Mar 2026; the process concludes 12 Aug 2026.

1. Aisha's dossier arrives 9 Mar — **after** the closing date. The clerk records
   `received_at: 2026-03-09`, `postmark_date: 2026-03-06`. `late = true`.
2. The Registrar's office refers it to the VC. Admitted with reference `VC/REC/2026/118` — the
   postmark precedes the closing date, so Rule 11 III(d) is satisfied. Audited.
3. Aisha is not selected. On process close, `ScheduleDestruction` sets
   `destruction_due_on = 2031-08-12` for her and for 57 other unsuccessful candidates. The three
   selected candidates who joined are marked **permanent** and not scheduled.
4. On 15 Aug 2031 the queue shows 58 items due. A batch `DEST/2031/07` is authorised, then executed.
   58 `hardcopy.destroyed` audit events are written.
5. **Aisha's electronic application, both snapshots, her uploaded documents and the entire audit
   chain remain intact and queryable.** In 2033 an RTI request about the 2026 drive is answered in
   full.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M33-R01 | Given a receipt after the closing date without a postmark date, when saved, then validation fails |
| M33-R02 | Given a postmark after the closing date, when late admission is attempted, then it is refused |
| M33-R03 | Given a late admission, when recorded, then it carries an authorisation reference and is audited |
| M33-R04 | Given process close, when destruction is scheduled, then **only unsuccessful** candidates receive a due date |
| M33-R05 | Given a selected-and-joined candidate, when scheduled, then they are marked permanent, never due |
| M33-R06 | Given a batch containing an item not yet due, when executed, then it is refused |
| M33-R07 | Given destruction execution, when it completes, then **no** application, snapshot, document or audit row is deleted |
| M33-R08 | Given destruction execution, when it completes, then one `hardcopy.destroyed` audit event exists per item |
| M33-R09 | Given a non-`super_admin`, when authorising a batch, then **403** |
| M33-R10 | Given the due queue, when rendered, then it groups by post and close date with counts |

## 10. Test cases

`tests/Feature/Custody/ReceiptTest` — R01–R03 · `ScheduleTest` — R04, R05, R10 ·
`DestructionTest` — **R06, R07, R08** · `Authz/CustodyTest` — R09.

R07 asserts row counts across `applications`, `application_snapshots`, `documents` and `audit_logs`
before and after — the DR-011 guarantee, tested rather than assumed.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03 | `App\Domain\Custody\RecordReceipt`, `AdmitLateApplication` |
| R04, R05 | `App\Domain\Custody\ScheduleDestruction` |
| R06–R08 | `App\Domain\Custody\ExecuteDestruction` |
| R09 | `App\Policies\CustodyPolicy` |
