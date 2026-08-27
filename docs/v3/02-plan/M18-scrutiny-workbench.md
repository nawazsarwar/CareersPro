# M18 — Scrutiny Workbench

**Wave:** 6 · **Scope:** v1
**Depends on:** M34, M25, M07 · M05
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Queue-based, side-by-side verification of a candidate's **claims against their documents**, with
deficiency raising and a time-bound rectification window.

| Obligation | Source |
|---|---|
| Screening Committee verifies Proforma grades | UGC 2018 cl. 5.2 |
| Part-C claims verified by the *"Concerned Chairman/Director/Principal"* — repeated after **every** sub-table | FN-1 Part C |
| Claims verifiable *"at any point of time **even after joining**"* | CRR Rule 22.4 |
| CVO may investigate at any stage | CRR Rule 34.2 |
| **Incomplete applications shall not be entertained** | CRR Rule 11 III(d) |
| **Local recruitment is scrutinised in the Dean's office** | DR-010 |
| **Scrutiny Committee**: Head of Department/Office (Chairman) · **two** members from the Department · **one** VC nominee from **outside** the Department. None lower in Pay Level than the advertised post | **AMU CRR Rule 15** |
| A separate Scrutiny Committee may be constituted for **Common Pool** posts | AMU CRR Rule 15 |
| The Committee may recommend a candidate **conditionally**; conditions must be met **before** the test or interview, and the candidature stays **provisional** until then | AMU CRR Rule 15 |

**The rectification window is the differentiator.** CU-Chayan has *"no time-bound in-portal objection
window"* and pushes candidates to *"generic university email addresses that go unacknowledged"*; the
legacy AMU system locks irreversibly at payment. **It is University policy requiring Executive
Council sanction** and must not be presented as UGC compliance — and per UGC 2018 cl. 5.3 the
**screening stage is the only compatible slot** for it.

## 2. Data

```
deficiencies
  id · application_id · raised_by_id · raised_at
  sections json                                -- the named sections that re-open
  description text
  rectification_window_closes_at
  rectified_at NULL · rectified_by_id NULL · resolution NULL
  status enum(open, rectified, expired, waived)

scrutiny_assignments
  id · application_id · assigned_to_id · assigned_at · completed_at
  UNIQUE (application_id, assigned_to_id)
```

Also writes `eligibility_decisions` (M34), `research_claims.verified_at` (M06) and
`application_snapshots` on rectification.

**Indexes:** `deficiencies(application_id, status)` · `scrutiny_assignments(assigned_to_id, completed_at)`.

## 3. Domain services

```
App\Domain\Scrutiny\BuildQueue::for(User, QueueFilters): Builder
App\Domain\Scrutiny\OpenScrutiny::handle(Application, User): void
App\Domain\Scrutiny\VerifyClaim::handle(ResearchClaim, User, bool, ?string): void
App\Domain\Scrutiny\RaiseDeficiency::handle(Application, DeficiencyData, User): Deficiency
App\Domain\Scrutiny\RectifyDeficiency::handle(Deficiency, User): void
App\Domain\Scrutiny\ExpireDeficiencies::handle(): int          -- scheduled
```

**Invariants.**
- **`BuildQueue` applies `visibleTo($user)` first.** No filter, sort or page can widen it.
- **Payment is a precondition** for scrutiny, unless the application is fee-exempt.
- `RaiseDeficiency` re-opens **only the named sections**. It is not a general re-open.
- `RectifyDeficiency` writes a **new snapshot** (`reason: correction_window`); the earlier snapshot
  is untouched.
- `ExpireDeficiencies` sets `status: expired` and the scrutiny gate to `rejected` with the remark
  *"deficiency not rectified"* — a state transition, not a silent lapse.
- **A scrutiny officer may not scrutinise their own application.** Guarded.
- **Scrutiny Committee composition is validated on constitution** (AMU CRR Rule 15): a chairman who
  heads the Department/Office, exactly two departmental members, one external-to-department VC
  nominee, and **every member at or above the advertised post's Pay Level**. Where the Department
  cannot supply them, the VC nominates from another Department — recorded as such.
- **A conditional recommendation keeps the candidature `provisional`** until the conditions are met,
  and the conditions must clear **before** the written/skill test or interview.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/scrutiny` | `admin.scrutiny.index` | `ScrutinyPolicy@viewAny` |
| GET | `/admin/scrutiny/{application}` | `admin.scrutiny.show` | `ApplicationPolicy@scrutinise` |
| POST | `/admin/scrutiny/{application}/open` | `admin.scrutiny.open` | `@scrutinise` |
| POST | `/admin/scrutiny/{application}/claims/{claim}/verify` | `admin.scrutiny.claims.verify` | `@scrutinise` |
| POST | `/admin/scrutiny/{application}/gates` | `admin.scrutiny.gates` | `ApplicationPolicy@decideGate` |
| POST | `/admin/scrutiny/{application}/deficiencies` | `admin.scrutiny.deficiencies.store` | `@scrutinise` |
| POST | `/admin/scrutiny/bulk/gates` | `admin.scrutiny.bulk` | `@decideGate` |

All under `auth`, `verified`, `2fa`.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| application | **submitted**, **paid or exempt** | This application has not been paid for. |
| | **`decided_by ≠ applicant`** | You cannot scrutinise your own application. |
| deficiency `sections[]` | required, array, min:1, each in the track's section list | Name at least one section to re-open. |
| deficiency `description` | required, min:20, max:2000 | Describe what is deficient and what is needed. |
| `rectification_window_days` | required, integer, between:3,30 | The window must be 3 to 30 days. |
| | **must close before the post's process deadline** | The window would extend past the process deadline. |
| claim verification `remark` | **`required_if:verified,false`**, min:10 | Record why this claim is not accepted. |
| bulk `application_ids[]` | required, each exists · **each within the actor's scope** | |
| bulk `remark` | required when the decision is `rejected` | |
| committee `members[]` | **exactly 2 departmental + 1 external-to-department**, chairman heads the unit | A Scrutiny Committee is the Head plus two departmental members and one nominee from outside. |
| committee member `pay_level` | **≥ the advertised post's Pay Level** | {name} is below the Pay Level of the advertised post. |
| conditional recommendation `conditions` | required, min:20 · **due before the test/interview date** | State the conditions and when they must be met. |

## 6. Authorisation

`ScrutinyPolicy` and `ApplicationPolicy@scrutinise` extend `ScopedPolicy`.

| Actor | Reaches |
|---|---|
| `scrutiny_officer` (university-wide) | all applications |
| `dean_office_scrutiny` | **local** posts within their OU subtree only |
| `dean_office_*` | **403** on any General post |

**Bulk actions apply the same scope per row** — a bulk gate decision cannot escalate privilege by
including an out-of-scope id. Tested explicitly.

## 7. UI

**Queue:** the shared table, filtered by post, gate state, category and deficiency status, with a
count. The scope is stated in the page subtitle — *"Faculty of Arts and 3 departments."*

**Detail — side by side.** Claims on the left, the **document inline on the right**. No downloading:
CU-Chayan's committees *"download hundreds of loose PDFs/ZIPs"*, which is a documented weakness.
Each claim carries verify / reject with a remark, and the officer moves down the list without
leaving the page.

The **gate control** (M34) sits at the foot, rendering only active gates.

**Deficiency composer:** section checkboxes, description, window length, and a preview of exactly
what the candidate will see.

## 8. Worked example

Dr Rehman, `dean_office_scrutiny` scoped to **Faculty of Arts** (`/1/11/`), opens the queue.

1. Queue shows **23** applications — local posts under Arts and its 3 departments. He filters to
   *scrutiny pending* → 14.
2. He opens application `884/2026/01109`. Payment confirmed. `OpenScrutiny` records him and the time;
   `lifecycle_state → under_scrutiny`.
3. Claims and documents side by side. He verifies 6 of 7. The 7th — an experience certificate — is
   illegible.
4. **Raise deficiency**: sections `["employment"]`, description, window **7 days**. The window is
   checked against the process deadline → allowed. The candidate is notified; **only the employment
   section re-opens**; `lifecycle_state → deficient`.
5. Day 3: the candidate re-uploads. `RectifyDeficiency` writes **snapshot #2**; state returns to
   `under_scrutiny`. Snapshot #1 is untouched.
6. He verifies the claim and sets `scrutiny → eligible` with a remark. One audit entry per change.
7. He tries to open a Faculty of Commerce local post by URL → **403**. He tries the queue export URL
   for Commerce → **403**, same scoped query.

Had the candidate not rectified by day 7, `ExpireDeficiencies` would set `status: expired` and the
scrutiny gate to `rejected` with *"deficiency not rectified"*.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M18-R01 | Given a Dean's-office user of Faculty X, when opening any Faculty Y application, then **403** |
| M18-R02 | Given the same, when applying any filter, sort or page, then no out-of-scope row is ever returned |
| M18-R03 | Given an unpaid, non-exempt application, when opened for scrutiny, then it is refused |
| M18-R04 | Given a deficiency naming one section, when the candidate edits another, then it is refused |
| M18-R05 | Given a rectification, when saved, then a **new snapshot** exists and the earlier one is unchanged |
| M18-R06 | Given an expired window, when the scheduler runs, then the scrutiny gate becomes `rejected` with the standard remark |
| M18-R07 | Given a deficiency window extending past the process deadline, when set, then it is refused |
| M18-R08 | Given a claim rejection without a remark, when saved, then validation fails |
| M18-R09 | Given a bulk action including an out-of-scope id, when submitted, then that row is refused |
| M18-R10 | Given an officer, when scrutinising their own application, then it is refused |
| M18-R11 | Given any verification or gate change, when committed, then an audit entry is written |
| M18-R12 | Given a document viewed in the workbench, when served, then `document.accessed` is recorded |
| M18-R13 | Given a 100-row queue with documents, when rendered, then the query count is within budget |
| M18-R14 | Given a Scrutiny Committee member below the advertised Pay Level, when constituted, then it is refused, naming the member |
| M18-R15 | Given a committee without one external-to-department nominee, when constituted, then it is refused |
| M18-R16 | Given a conditional recommendation, when the conditions are unmet at the test date, then the candidature remains **provisional** and the candidate is not admitted |

## 10. Test cases

`tests/Feature/Admin/Scrutiny/QueueScopeTest` — **R01, R02, R09** · `PreconditionTest` — R03, R10 ·
`DeficiencyTest` — R04, R05, R07, R08 · `ExpiryTest` — R06 · `AuditTest` — R11, R12 ·
`QueuePerformanceTest` — R13 ·
`ScrutinyCommitteeCompositionTest` — **R14, R15** · `ConditionalRecommendationTest` — R16.

Fixtures: two faculties each with local posts and applications, so scope tests cannot pass by
accident.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R09 | `App\Domain\Scrutiny\BuildQueue`, `App\Policies\ScrutinyPolicy` |
| R03, R10 | `App\Domain\Scrutiny\OpenScrutiny` |
| R04, R05, R07, R08 | `RaiseDeficiency`, `RectifyDeficiency` |
| R06 | `App\Domain\Scrutiny\ExpireDeficiencies` (scheduled) |
| R11, R12 | `App\Domain\Audit\*` (M26) |
