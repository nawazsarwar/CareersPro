# M22 — Examination Admin

**Wave:** 8 · **Scope:** v1
**Depends on:** M11, M34
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Run the written and skill tests: attendance, marks capture, incident logging.

| Obligation | Source |
|---|---|
| Written and/or skill tests **mandatory** for all Group B and C direct recruitment | CRR Rule 11 III(f) |
| Paper I objective **100**, qualifying **40%** | CRR Rule 11 III(g) |
| Paper II descriptive **100**, evaluated **only** for Paper I qualifiers, **50%** to proceed | CRR Rule 11 III(g) |
| Skill test **50**, minimum **25** — **qualifying only, never additive** | CRR Rule 11 III(g) |
| Syllabi, modalities and evaluation framed by the **Vice-Chancellor** | CRR Rule 11 III(h) |
| Relaxation in qualifying marks for reserved categories **per GoI guidelines** | CRR Rule 11 III(i) — DOC-003 |
| MTS: written **and trade test**; LDC: skill test in MS Office; typing 35 wpm English / 30 wpm Hindi | CRR Sch-1, Rule 25.5 |

**Computer-based delivery is out of scope (M12, v2).** This module supports **offline** tests: the
paper is conducted physically, and marks are imported.

## 2. Data

```
exam_sessions   id · post_id · type enum(paper_i, paper_ii, skill_test, trade_test, typing_test)
                held_on · reporting_time · gate_closing_time · duration_minutes
                max_marks · qualifying_percent · is_additive bool
                status enum(scheduled, conducted, marks_imported, finalised)
attendances     id · exam_session_id · application_id
                status enum(present, absent, debarred) · marked_by_id · marked_at
                UNIQUE (exam_session_id, application_id)
exam_marks      id · exam_session_id · application_id
                marks decimal(6,2) NULL · qualified bool NULL
                imported_batch_ref · verified_at · verified_by_id
                UNIQUE (exam_session_id, application_id)
mark_imports    id · exam_session_id · file_ref · uploaded_by_id · uploaded_at
                row_count · matched · unmatched · status
incidents       id · exam_session_id · application_id NULL · exam_centre_id
                type enum(malpractice, medical, disruption, other)
                description · reported_by_id · occurred_at · action_taken
```

**Indexes:** `exam_marks(exam_session_id, qualified)` · `attendances(exam_session_id, status)`.

## 3. Domain services

```
App\Domain\Examination\ScheduleSession::handle(Post, SessionData): ExamSession
App\Domain\Examination\ImportMarks::handle(ExamSession, UploadedFile, User): ImportReport
App\Domain\Examination\EvaluateQualification::handle(ExamSession): int
App\Domain\Examination\GateFromExam::handle(Post): int
```

**Invariants — each encodes a CRR rule that is easy to get wrong:**

- **`is_additive` is `false` for skill and trade tests.** They are qualifying only. A skill-test mark
  can never reach a merit total.
- **Paper II is evaluated only for candidates who qualified Paper I.** Importing a Paper II mark for
  a Paper I failure is rejected, not silently stored.
- Qualifying thresholds come from the **frozen ruleset**, not from the session form. Reserved-category
  relaxation applies **only** when a reservation policy version is active (M17); with none, no
  relaxation is applied and that is recorded.
- **`GateFromExam` sets the `written_test` gate** (M34) from the result — it does not write a merit
  position.
- Marks import is **idempotent on `(session, application)`**; re-importing corrects, and both values
  are audited.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET/POST | `/admin/posts/{post}/exam-sessions` | `admin.exams.*` | `ExaminationPolicy@manage` |
| GET | `/admin/exam-sessions/{s}/attendance` | `admin.exams.attendance` | `@manage` |
| POST | `/admin/exam-sessions/{s}/attendance` | `admin.exams.attendance.mark` | `@manage` |
| POST | `/admin/exam-sessions/{s}/marks/import` | `admin.exams.marks.import` | `@importMarks` |
| GET | `/admin/exam-sessions/{s}/marks` | `admin.exams.marks` | `@manage` |
| POST | `/admin/exam-sessions/{s}/finalise` | `admin.exams.finalise` | `@finalise` |
| POST | `/admin/exam-sessions/{s}/incidents` | `admin.exams.incidents.store` | `@manage` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `type` | required, in:… · **must be active on the post's selection method** | A {type} does not apply to this post. |
| `held_on` | required, date · **after the closing date**, **within the process deadline** | |
| `max_marks` | required, numeric · **100 for papers, 50 for skill tests** | |
| `qualifying_percent` | required · **must match the frozen ruleset** | The qualifying threshold is fixed at {n}% by CRR Rule 11 III(g). |
| import file | required, `mimes:csv,xlsx`, max 10 MB, **expected columns** | The file does not match the expected format. |
| import row `marks` | numeric, `between:0,{max_marks}` | Row {n}: marks exceed the maximum. |
| import Paper II row | **application must have qualified Paper I** | Row {n}: this candidate did not qualify Paper I. |
| finalise | **every present candidate has a mark** | {n} present candidates have no mark. |
| attendance | application must be **allocated a seat** | |

## 6. Authorisation

`ExaminationPolicy` — `exam_admin` and `super_admin`, university-wide. `importMarks` and `finalise`
are separately permissioned and **always audited**, since a mark change alters an eligibility gate.

## 7. UI

**Session list** per post with status. **Attendance:** a keyboard-first grid ordered by roll number,
with present/absent/debarred, designed for a hall superintendent working a printed sheet.

**Marks import:** upload → **preview with matched, unmatched and out-of-range rows named** → commit.
Nothing is stored until the preview is accepted.

**Finalisation** shows how many qualify at the threshold and which gate decisions will follow, before
committing.

## 8. Worked example

Post 2599 — `written_skill_interview`. Sessions: Paper I (100, 40%), Paper II (100, 50%),
Skill test (50, min 25, **not additive**).

1. Paper I held 12 Apr. Attendance: 14 present, 1 absent. Marks imported: 11 score ≥ 40.
2. Paper II imported for those 11. An import row for the absent candidate is **rejected**:
   *"Row 12: this candidate did not qualify Paper I."*
3. Nine score ≥ 50 in Paper II. Skill test: 8 score ≥ 25 → qualified. One scores 22 → **not
   qualified**, and is excluded from the merit list regardless of paper marks.
4. `GateFromExam` sets `written_test = eligible` for the 8, `rejected` for the rest, each with a
   remark naming the stage failed. Eight audit entries.
5. A developer attempts to add the skill-test mark to the merit total. `is_additive = false`, so the
   merit strategy never reads it, and a test asserts this.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M22-R01 | Given a skill test, when merit is computed, then its marks are **never** added |
| M22-R02 | Given a Paper I failure, when a Paper II mark is imported, then that row is rejected |
| M22-R03 | Given a qualifying threshold differing from the frozen ruleset, when set, then it is refused |
| M22-R04 | Given a marks file, when uploaded, then a preview names matched, unmatched and out-of-range rows |
| M22-R05 | Given a preview, when not accepted, then **nothing is stored** |
| M22-R06 | Given a re-import, when it runs, then it corrects idempotently and both values are audited |
| M22-R07 | Given finalisation with missing marks, when attempted, then it is refused with the count |
| M22-R08 | Given finalisation, when it completes, then `written_test` gates are set with remarks |
| M22-R09 | Given a session type inactive on the post, when scheduled, then it is refused |
| M22-R10 | Given a candidate with no seat, when marked present, then it is refused |
| M22-R11 | Given no active reservation policy, when relaxation is considered, then none is applied and it is recorded |
| M22-R12 | Given an incident, when logged, then it records reporter, time and action taken |

## 10. Test cases

`tests/Feature/Admin/Examination/SkillTestNonAdditiveTest` — **R01** · `MarkImportTest` — R02, R04–R06 ·
`ThresholdTest` — R03, R11 · `FinaliseTest` — R07, R08 · `SessionValidationTest` — R09, R10 ·
`IncidentTest` — R12.

Fixtures: a marks file with a Paper I failure, an out-of-range mark and an unmatched roll number.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01 | `exam_sessions.is_additive`, `App\Domain\Merit\NonTeachingMeritStrategy` |
| R02, R04–R06 | `App\Domain\Examination\ImportMarks` |
| R03, R11 | `App\Domain\Examination\EvaluateQualification` |
| R07, R08 | `App\Domain\Examination\GateFromExam` |
| R09, R10, R12 | `App\Http\Requests\Examination\*` |
