# M11 — Admit Card & Centre Allotment

**Wave:** 8 · **Scope:** v1
**Depends on:** M21, M34, M09
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Allocate roll numbers, seats and centres, and release admit cards **inside their prescribed window**.

| Obligation | Source |
|---|---|
| Written test mandatory for all Group B and C direct recruitment | CRR Rule 11 III(f) |
| Test syllabi, modalities and evaluation framed by the **Vice-Chancellor** | CRR Rule 11 III(h) |
| Admit-card download windows | `posts.admit_card_opening_date` / `closing_date` |

**Two legacy defects this replaces.** `application_forms.roll_no` is a nullable integer validated only
as `min:-2147483648`, exposed as a free-text field — **no sequence, no per-post prefix, no
uniqueness**. And there is **no centres master table anywhere**: `careers_db.applicationforms` carries
nine exam-logistics columns (`centre_name`, `centre_code`, `centre_address`, `centre_city`,
`room_no`, `seat_no`) typed **per application**.

## 2. Data

```
exam_centres        id · name · code UNIQUE · address · city · state_id
                    capacity · is_active · contact
roll_number_sequences  id · post_id UNIQUE · prefix · next_value · allocated_count
seat_allocations    id · application_id UNIQUE · exam_centre_id
                    room_no · seat_no · allocated_at · allocated_by_id
                    UNIQUE (exam_centre_id, room_no, seat_no)
centre_preferences  id · application_id · exam_centre_id · rank
```

`applications.roll_no` becomes `varchar` (e.g. `2599/2026/0012`), **unique per post**.

**Indexes:** `seat_allocations(exam_centre_id)` · `applications(post_id, roll_no)` unique.

## 3. Domain services

```
App\Domain\Examination\AllocateRollNumber::handle(Application): string
App\Domain\Examination\AllocateSeats::handle(Post, AllocationParams): AllocationReport
App\Domain\Examination\AssertDownloadWindow::check(Post, DocumentType): void
App\Domain\Examination\GenerateAdmitCard::handle(Application): GeneratedDocument
```

**Invariants.**
- Roll numbers come from a **per-post sequence** taken `FOR UPDATE` — gapless, unique, never
  user-entered.
- `AllocateSeats` is **clash-free by construction**: the unique index on
  `(centre, room, seat)` makes a double allocation impossible, not merely unlikely.
- Allocation **honours candidate preference where capacity allows**, then falls back by proximity,
  and records which rule applied to each candidate.
- **`AssertDownloadWindow` gates every admit-card generation and download.** Outside
  `admit_card_opening_date … closing_date` it refuses — the columns exist in production and were
  dropped by the previous redesign.
- Only applications with the relevant gate `eligible` are allocated.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET/POST/PATCH | `/admin/centres/{c?}` | `admin.centres.*` | `ExamCentrePolicy@*` |
| POST | `/admin/posts/{post}/roll-numbers` | `admin.rollNumbers.allocate` | `ExaminationPolicy@allocate` |
| POST | `/admin/posts/{post}/seats` | `admin.seats.allocate` | `@allocate` |
| GET | `/admin/posts/{post}/seats` | `admin.seats.index` | `@view` |
| GET | `/applications/{application}/admit-card.pdf` | `applications.admitCard` | `ApplicationPolicy@view` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| centre `code` | required, unique, `regex:/^[A-Z0-9\-]+$/` | |
| centre `capacity` | required, integer, min:1 | |
| allocation | **post must have a published shortlist** | Publish the shortlist before allocating. |
| | **total eligible ≤ total active centre capacity** | Capacity is {c}; {n} candidates need seats. |
| roll number prefix | required, max:20 | |
| admit-card download | **within the window** | Admit cards are available from {open} to {close}. |
| | relevant gate = `eligible` | |

## 6. Authorisation

`ExamCentrePolicy` and `ExaminationPolicy` — `exam_admin` and `super_admin`, university-wide.
Candidates reach only their **own** admit card, through `ApplicationPolicy@view`, and only inside the
window.

## 7. UI

**Centre master:** standard table with capacity and current allocation shown together, so an admin
sees headroom before allocating.

**Allocation screen:** a dry-run summary — candidates, centres, capacity, preference satisfaction —
**before** committing. Allocation is **queued** with progress; 106 candidates across centres is not a
synchronous response.

**Candidate:** the admit card appears on the dashboard **only inside the window**, with the window
dates stated before and after.

## 8. Worked example

Post 2599, 15 shortlisted, written test 12 Apr 2026, admit-card window 1–10 Apr.

1. `AllocateRollNumber` issues `2599/2026/0001` … `0015` from the per-post sequence.
2. `AllocateSeats`: Aligarh centre capacity 200, 12 candidates prefer it → all satisfied. 3 prefer
   Delhi (capacity 0 active) → allocated to Aligarh by fallback, and the report records
   *"preference unavailable"* for each.
3. On 28 Mar a candidate requests the admit card → refused: *"Admit cards are available from
   1 Apr 2026 to 10 Apr 2026."*
4. On 3 Apr it generates from the snapshot with roll number, centre, room 12, seat 34, reporting
   time, gate-closing time and a QR verification stamp.
5. An attempt to allocate a second candidate to centre 1, room 12, seat 34 fails on the unique
   index — **the clash cannot occur.**

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M11-R01 | Given a post, when roll numbers are allocated, then they are unique, gapless and prefixed |
| M11-R02 | Given a roll number, when a user attempts to set it manually, then it is refused |
| M11-R03 | Given two allocations to the same centre, room and seat, when saved, then the second fails |
| M11-R04 | Given candidate preferences within capacity, when allocated, then preferences are honoured |
| M11-R05 | Given preferences beyond capacity, when allocated, then fallback applies and the reason is recorded |
| M11-R06 | Given a request before the window opens, when the admit card is requested, then it is refused with the dates |
| M11-R07 | Given a request after the window closes, then it is refused |
| M11-R08 | Given a candidate whose gate is not `eligible`, when allocated, then they are excluded |
| M11-R09 | Given capacity below the candidate count, when allocating, then it is refused with both figures |
| M11-R10 | Given an admit card, when generated, then it renders from the **snapshot** |
| M11-R11 | Given candidate A, when requesting B's admit card, then **403** |
| M11-R12 | Given allocation, when it runs, then it is queued and reports progress |

## 10. Test cases

`tests/Feature/Admin/Examination/RollNumberTest` — R01, R02 · `SeatAllocationTest` — R03–R05, R08, R09,
R12 · `DownloadWindowTest` — **R06, R07** · `AdmitCardTest` — R10 · `Authz/AdmitCardTest` — R11.

Concurrency fixture for R03: two parallel allocations targeting the same seat.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\Examination\AllocateRollNumber`, `roll_number_sequences` |
| R03–R05, R08, R09 | `App\Domain\Examination\AllocateSeats`, unique index |
| R06, R07 | `App\Domain\Examination\AssertDownloadWindow` |
| R10 | `App\Domain\Examination\GenerateAdmitCard` |
| R11 | `App\Policies\ApplicationPolicy` |
| R12 | `App\Jobs\AllocateSeatsJob`, `App\Domain\Examination\AllocateSeats` |
