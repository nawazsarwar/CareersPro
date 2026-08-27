# M31 — Attendance Sheet Generator

**Wave:** 8 · **Scope:** v1
**Depends on:** M34, M11, M22

## 1. Purpose and statutory basis

Generate printable attendance registers for exam halls and interview boards.

A **live admin screen** in Manage Careers today (`media_1787422553896.png`) and **absent from the
previous module catalogue entirely.**

**Two of its three report types read the eligibility gates directly** — `Scrutiny eligible only` and
`Interview eligible only`. On the collapsed four-column schema they cannot be built at all, which is
why M34 is load-bearing rather than a nicety.

## 2. Data

No new tables. Reads `applications`, `eligibility_decisions`, `seat_allocations`, `exam_sessions`,
`documents` (photograph), `posts`.

```
generated_reports
  id · type enum(attendance_sheet, …) · post_id · exam_session_id NULL
  parameters json · media_id · row_count
  generated_at · generated_by_id · status enum(queued, ready, failed)
```

## 3. Domain services

```
App\Domain\Reports\BuildAttendanceSheet::handle(AttendanceSheetParams, User): GeneratedReport
App\Domain\Reports\ResolveCohort::for(Post, ReportType): Builder
```

**Invariants.**
- `ResolveCohort` maps the report type to a gate query — `all` · `scrutiny = eligible` ·
  `interview = eligible` — and **applies `visibleTo($user)` first**, so a Dean's-office user cannot
  generate a sheet for another faculty.
- **Generation is queued.** A 106-row sheet with photographs is not a synchronous response; the
  legacy code sets `memory_limit = -1` and `max_execution_time = -1` and hopes.
- Photographs render from a **pre-generated 80×100 conversion**, never the original upload.
- Every generation writes an audit event with parameters and row count — an attendance sheet is bulk
  PII leaving the system.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/reports/attendance` | `admin.reports.attendance` | `ReportPolicy@viewAny` |
| POST | `/admin/reports/attendance` | `admin.reports.attendance.generate` | `@generate` |
| GET | `/admin/reports/{report}/download` | `admin.reports.download` | `@view` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `advertisement_id` | required, exists · **within the actor's scope** | |
| `post_id` | required, exists, **belongs to the advertisement** | |
| `report_type` | required, in:all,scrutiny_eligible,interview_eligible | |
| | **the named gate must be active on this post** | The {gate} gate does not apply to this post. |
| `roll_numbers_generated` | required, boolean · **if true, roll numbers must exist** | Roll numbers have not been allocated for this post. |
| `with_photo` | required, boolean | |
| `with_barcode` | required, boolean · **requires roll numbers** | Barcodes need roll numbers. |
| `sort_by` | required, in:roll_no,name,application_no | |
| cohort | **must be non-empty** | No candidates match these criteria. |

## 6. Authorisation

`ReportPolicy` extends `ScopedPolicy`. `exam_admin` and `recruitment_admin` university-wide;
`dean_office` **within their subtree, local posts only**. Downloads re-check the policy — a generated
report's URL is not a bearer token.

## 7. UI

Exactly the reference flow, with the dependent selects preserved: advertisement → post →
*"Has roll no been uploaded/generated?"* → report type → with photo? → with barcode? → **Generate**.

**Two additions.** A **live cohort count** beside the report type, so the operator sees *"7
candidates"* before generating. And progress with a download link on completion, rather than a
blocked request.

Output: A4 landscape, repeating header with post, session, date and centre; columns for roll number,
application number, name, father's name, photograph, signature box and remarks; page numbers as
*n of m*; and a signature block for the invigilator.

## 8. Worked example

Post 2599, Paper I on 12 Apr 2026.

The operator selects advertisement 2/2026/NT → post 2599 → roll numbers generated **Yes** → report
type **Scrutiny eligible only** → photo **Yes** → barcode **Yes**.

The cohort count shows **7** — matching the pipeline widget's *"Scrutiny eligible 7"*, because both
read `eligibility_decisions`.

Generation is queued. The PDF lists 7 candidates ordered by roll number, each with an 80×100
photograph and a Code-128 barcode of the roll number, with signature boxes.

Selecting **Interview eligible only** returns a cohort of **0** and generation is refused: *"No
candidates match these criteria."*

On post 2881 (`interview_only`) the report type **Scrutiny eligible only** is available; there is no
written-test gate, so no report type references one.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M31-R01 | Given report type `scrutiny_eligible`, when generated, then only candidates with that gate `eligible` appear |
| M31-R02 | Given report type `interview_eligible`, when generated, then the cohort matches the pipeline widget exactly |
| M31-R03 | Given a gate inactive on the post, when that report type is chosen, then it is refused |
| M31-R04 | Given barcodes requested without roll numbers, when generated, then it is refused |
| M31-R05 | Given an empty cohort, when generated, then it is refused with a clear message |
| M31-R06 | Given a Dean's-office user of Faculty X, when generating for Faculty Y, then **403** |
| M31-R07 | Given a generated report URL, when opened by an out-of-scope user, then **403** |
| M31-R08 | Given generation, when requested, then it is queued and progress is reported |
| M31-R09 | Given photographs, when rendered, then the 80×100 conversion is used, not the original |
| M31-R10 | Given a generation, when it completes, then an audit event records parameters and row count |
| M31-R11 | Given `sort_by: roll_no`, when generated, then rows are in roll-number order |

## 10. Test cases

`tests/Feature/Reports/AttendanceCohortTest` — R01–R03, R05, R11 · `BarcodeTest` — R04 ·
`Authz/ReportScopeTest` — R06, R07 · `QueueTest` — R08 · `PhotoConversionTest` — R09 ·
`AuditTest` — R10.

R02 asserts the sheet's row count equals `posts.scrutiny_eligible_count` — the same source the widget
uses, so the two cannot drift.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03, R05, R11 | `App\Domain\Reports\ResolveCohort` |
| R04 | `App\Http\Requests\Reports\AttendanceSheetRequest` |
| R06, R07 | `App\Policies\ReportPolicy` |
| R08–R10 | `App\Jobs\BuildAttendanceSheet` |
