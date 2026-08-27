# M34 — Eligibility Decision Gates

**Wave:** 6 · **Scope:** v1
**Depends on:** M05, M25, M26
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

**Not a screen — the core domain object the whole operational half depends on.** It is specified
separately because collapsing it is exactly what the previous redesign did, and doing so silently
breaks four other modules.

Production `careers_db.applicationforms` carries **three independent gates across 12 columns**:

```
scrutiny_eligible       scrutiny_remark       scrutiny_updated_by       scrutiny_updated_at
written_test_eligible   written_test_..._remark   ..._updated_by   ..._updated_at
interview_eligible      interview_..._remark      ..._updated_by   ..._updated_at
```

`betacareers_db.application_forms` collapses all of it into **four generic columns**. The
consequences, each verifiable against a reference screenshot:

| Broken | Why |
|---|---|
| The three-column decision modal | Cannot store three decisions |
| *"Scrutiny eligible 7 / Eligible for interview 0"* pipeline widget (M23) | Cannot compute |
| Bulk document filter `Eligible only` vs `Interview eligible only` (M32) | Cannot evaluate |
| Attendance sheet report types `Scrutiny eligible only` / `Interview eligible only` (M31) | Cannot build |

**Statutory context:** CRR Rule 22.4 permits verification *"at any point of time even after
joining"*, so decision **history** must survive; CRR Rule 34.2 gives the CVO investigation powers at
any stage.

## 2. Data

```
eligibility_decisions
  id · application_id → applications
  gate enum(scrutiny, written_test, interview)
  decision enum(eligible, rejected) NULL          -- NULL = pending, a THIRD value
  remark text NULL
  decided_by_id → users NULL · decided_at NULL
  UNIQUE (application_id, gate)
  INDEX (application_id, gate, decision)
```

**Rows exist only for gates active on the post type.** An interview-only post has **two** rows.

**Counter columns on `posts`**, maintained by an observer, so the pipeline widget never aggregates
per render: `scrutiny_eligible_count` · `written_test_eligible_count` · `interview_eligible_count`.

## 3. Domain services

```
App\Domain\Eligibility\ActiveGates::for(Post): array<Gate>
App\Domain\Eligibility\DecideGate::handle(Application, Gate, ?Decision, ?string $remark, User): void
App\Domain\Eligibility\GateOrder::assertDecidable(Application, Gate): void
```

**Invariants.**
- `ActiveGates` derives from `post_types.default_selection_method`:

  | Selection method | Active gates |
  |---|---|
  | `interview_only` | scrutiny · interview |
  | `written_interview`, `written_skill_interview` | scrutiny · written_test · interview |
  | `trade_test`, `driving_test` | scrutiny · written_test |

- **A gate that is not active cannot be decided.** `DecideGate` throws.
- **Gates are ordered but independent.** `written_test` cannot be decided before `scrutiny` is
  `eligible`; but a `written_test` rejection **does not** alter the `scrutiny` decision.
- **A rejection requires a non-empty remark.** A rejection without a reason is not appealable.
- **Decisions are revisable, never silently overwritten** — a change writes a new audit entry
  carrying the prior value (CRR Rule 22.4).
- `decided_by_id` is always the acting user, never a default.

## 4. Routes and controllers

No routes of its own. Consumed by M18 (`POST /admin/scrutiny/{application}/gates`) and read by M21,
M23, M31 and M32.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `gate` | required, in:scrutiny,written_test,interview · **must be active on this post** | The {gate} gate does not apply to this post. |
| `decision` | nullable, in:eligible,rejected | |
| `remark` | **`required_if:decision,rejected`**, min:10, max:2000 | Record why this candidate is not eligible. |
| | `required` when changing an existing decision | Record why this decision is being changed. |
| ordering | `written_test` and `interview` require `scrutiny = eligible` | Complete scrutiny before deciding this stage. |

## 6. Authorisation

Through `ApplicationPolicy@decideGate` — permission **and** OU scope (DR-010). A Dean's-office user
may decide gates only for local posts inside their subtree.

**`decided_by_id` must not be the candidate**, guarded even though no role permits it — because the
guard is cheap and the failure is catastrophic.

## 7. UI

The gate control in `../01-design/ux/data-table.md` §6, with its two fixes: **three explicit options
rather than a merged "Pending / Not Eligible" label**, and **only active gates rendered**.

## 8. Worked example

**Post 2599** — `written_skill_interview` → `ActiveGates` = scrutiny, written_test, interview.
On submit, **three** rows are created, all `decision: NULL`.

1. An officer sets `scrutiny → eligible`, remark *"Documents verified against claims."*
   One row updated, one audit entry chained. `posts.scrutiny_eligible_count` increments to 7.
2. They attempt `interview` while `written_test` is still NULL → allowed, because ordering only
   requires **scrutiny** to be eligible. Both later stages are independent of each other.
3. The candidate scores 34% in Paper I. `written_test → rejected`, remark required and given.
   **`scrutiny` remains `eligible`** — the record shows scrutiny passed and the written test did
   not, which is the truth and what an appeal will examine.
4. Six weeks later the officer revises `written_test` to `eligible` after a re-evaluation. A new
   audit entry records `from: rejected, to: eligible` with the reason. **The prior value is
   preserved.**

**Post 2881** — `interview_only` → **two** rows. Attempting to decide `written_test` throws:
*"The written test gate does not apply to this post."* The UI never offers it.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M34-R01 | Given an `interview_only` post, when an application is submitted, then **two** gate rows exist |
| M34-R02 | Given a `written_skill_interview` post, when submitted, then **three** gate rows exist |
| M34-R03 | Given an inactive gate, when decided, then it throws |
| M34-R04 | Given `decision: rejected` without a remark, when saved, then validation fails |
| M34-R05 | Given `scrutiny` still pending, when deciding `written_test`, then it is refused |
| M34-R06 | Given `written_test: rejected`, when saved, then `scrutiny` is **unchanged** |
| M34-R07 | Given a decision change, when saved, then an audit entry records the **previous** value |
| M34-R08 | Given a gate decision, when committed, then the post counter column updates |
| M34-R09 | Given 100 posts listed, when counts render, then they come from counter columns — **query count within budget** |
| M34-R10 | Given a Dean's-office user of Faculty X, when deciding a gate on Faculty Y's post, then **403** |
| M34-R11 | Given `decision: NULL`, when rendered, then it displays as **Pending**, distinct from *Not eligible* |
| M34-R12 | Given a decision, when `decided_by_id` is set, then it is the acting user |

## 10. Test cases

`tests/Feature/Admin/Eligibility/ActiveGatesTest` — R01–R03 · `DecisionValidationTest` — R04, R05 ·
`GateIndependenceTest` — **R06** · `AuditTest` — R07, R12 · `CounterColumnTest` — R08, R09 ·
`Authz/GateScopeTest` — R10 · `tests/Feature/Ui/GateControlTest` — R11.

Fixtures: `PostFactory` states for each of the five selection methods.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03 | `App\Domain\Eligibility\ActiveGates` |
| R04–R07, R12 | `App\Domain\Eligibility\DecideGate`, `App\Http\Requests\Scrutiny\DecideGateRequest` |
| R08, R09 | `EligibilityDecisionObserver` |
| R10 | `App\Policies\ApplicationPolicy@decideGate` |
| R11 | `resources/views/components/data/gate-control.blade.php` |
