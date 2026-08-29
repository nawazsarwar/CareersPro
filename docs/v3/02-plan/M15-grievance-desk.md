# M15 — Grievance Desk

**Wave:** 9 · **Scope:** v1
**Depends on:** M18, M30, M25
**Blocked by:** **Executive Council sanction** — see §1
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

An SLA-tracked grievance register with a named appellate authority.

**Read this before building.** **Neither instrument creates a candidate-facing grievance right.**
Nothing in the UGC Regulations 2018 or the Model CRR 2022 requires publication of shortlists or
scores, disclosure of a candidate's scoring proforma, a representation window against screening
rejection, an answer-key challenge, or a time limit for grievance disposal.

**The only finality clauses run the other way** — CRR Rules 19.6 and 22.15(v) make the Executive
Council's decision final.

So this module is **University policy, and it requires Executive Council sanction. It must not be
presented as UGC compliance.**

**And there is a hard structural constraint.** UGC 2018 cl. 5.1 VIII(c) and cl. 5.3 require the
selection process to be **completed on the day of the committee meeting**. There is therefore **no
room for a post-committee objection window**. The only compatible slot is the **screening stage** —
which is where the M18 deficiency window already sits.

**Why build it anyway.** CU-Chayan's documented weaknesses include *"no time-bound in-portal
objection window"* and candidates *"pushed to generic university email addresses that go
unacknowledged."* This is the differentiator, and it is achievable within the constraint above.

## 2. Data

```
grievances       id · reference UNIQUE · user_id · application_id NULL
                 category enum(payment, document, scrutiny_decision, technical,
                               data_correction, other)
                 stage enum(pre_screening, screening, examination, post_result)
                 subject · description · raised_at
                 sla_due_at · assigned_to_id NULL · assigned_at NULL
                 status enum(open, in_progress, awaiting_applicant, resolved,
                             closed, escalated)
                 resolution NULL · resolved_at NULL · resolved_by_id NULL
                 escalated_to_id NULL · escalated_at NULL
grievance_messages id · grievance_id · author_id · body · is_internal bool · created_at
grievance_attachments id · grievance_id · document_id
sla_policies     id · category · stage · response_hours · resolution_hours
```

**Indexes:** `grievances(status, sla_due_at)` · `grievances(user_id)` ·
`grievances(assigned_to_id, status)`.

## 3. Domain services

```
App\Domain\Grievance\RaiseGrievance::handle(User, GrievanceData): Grievance
App\Domain\Grievance\AssignGrievance::handle(Grievance, User, User $actor): void
App\Domain\Grievance\ResolveGrievance::handle(Grievance, string, User): void
App\Domain\Grievance\EscalateOverdue::handle(): int          -- scheduled
App\Domain\Grievance\AssertStageAllowed::check(Application, GrievanceCategory): void
```

**Invariants.**
- **`AssertStageAllowed` refuses a `scrutiny_decision` grievance after the selection committee has
  concluded** — cl. 5.3 forecloses it, and accepting one would create an expectation the University
  cannot lawfully meet.
- `sla_due_at` is computed from `sla_policies` at creation and is **not editable**.
- **`EscalateOverdue` escalates automatically** to the named appellate authority. An SLA that is
  merely reported is not an SLA.
- `grievance_messages` is **append-only**. `is_internal` messages are never disclosed to the
  applicant.
- Every status change and every disclosure writes an audit entry.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET/POST | `/grievances/{g?}` | `grievances.*` | `GrievancePolicy@*` |
| POST | `/grievances/{g}/messages` | `grievances.messages.store` | `@reply` |
| GET | `/admin/grievances` | `admin.grievances.index` | `GrievancePolicy@viewAny` |
| POST | `/admin/grievances/{g}/assign` | `admin.grievances.assign` | `@assign` |
| POST | `/admin/grievances/{g}/resolve` | `admin.grievances.resolve` | `@resolve` |
| POST | `/admin/grievances/{g}/escalate` | `admin.grievances.escalate` | `@escalate` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `category` | required, in:… | |
| `application_id` | nullable, exists · **owned by the applicant** | |
| `stage` | required · **`AssertStageAllowed` must pass** | The selection process for this post has concluded. Decisions are final under the University's regulations. |
| `subject` | required, max:200 | |
| `description` | required, min:30, max:5000 | Describe the issue in at least 30 characters. |
| attachments | max 3, each owned by the applicant, ≤ 2 MB | |
| resolution | required, min:30 | Record what was decided and why. |
| assignment | assignee must hold a grievance-handling role | |
| duplicate | **no open grievance of the same category for the same application** | You already have an open grievance about this. |

## 6. Authorisation

`GrievancePolicy` — **ownership** for the applicant: raise, view own, reply.
`viewAny`, `assign`, `resolve` for `grievance_officer` and `super_admin`; **`dean_office` sees
grievances for applications within their subtree only.**
`escalate` for `super_admin`, or automatic on SLA breach.

**Internal messages are never returned on an applicant-facing route** — enforced by a query scope,
not by a view condition.

## 7. UI

**Candidate:** a simple form, then a threaded view with the SLA due date shown. The reference number
is prominent — it is what they will quote.

**Officer queue:** the shared table, filtered by category, stage, status, assignee and **overdue**.
Overdue rows are marked with a glyph and a word, never colour alone.

**The refusal must be honest.** Where `AssertStageAllowed` refuses, the message states the legal
position plainly and names what the candidate *can* do — rather than a generic error that reads as a
system failure.

## 8. Worked example

1. Aisha's payment debits but shows unpaid. She raises a `payment` grievance against application
   `2599/2026/00412`. Reference `GRV-2026-01184`. SLA: response 24h, resolution 72h.
2. Auto-assigned to `finance_admin`. They see the order, confirm reconciliation is pending, reply
   *"Your payment has been traced and will reconcile within 24 hours."*
3. Reconciliation completes; the officer resolves with a recorded resolution. Aisha is notified.
4. **Separately:** her scrutiny is rejected on 14 Mar. She raises a `scrutiny_decision` grievance on
   16 Mar. The committee has **not** concluded → `AssertStageAllowed` passes → it is accepted and
   routed to the Screening Committee.
5. Had she raised it on 25 May, after the committee concluded on 22 May, it is refused:
   *"The selection process for this post concluded on 22 May 2026. Under UGC Regulations 2018
   cl. 5.3 the committee's recommendation is made and signed on the day of the meeting."*
6. An unassigned grievance breaching its 24-hour response SLA is **automatically escalated** to the
   appellate authority, with an audit entry.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M15-R01 | Given a concluded committee, when a `scrutiny_decision` grievance is raised, then it is refused with the legal position stated |
| M15-R02 | Given an open committee, when the same grievance is raised, then it is accepted |
| M15-R03 | Given a grievance, when created, then `sla_due_at` is computed from policy and is not editable |
| M15-R04 | Given an overdue grievance, when the scheduler runs, then it escalates automatically and is audited |
| M15-R05 | Given an internal message, when the applicant views the thread, then it is absent |
| M15-R06 | Given candidate A, when viewing B's grievance, then **403** |
| M15-R07 | Given a Dean's-office user, when viewing a grievance outside their subtree, then **403** |
| M15-R08 | Given an open grievance of the same category, when a duplicate is raised, then it is refused |
| M15-R09 | Given a resolution under 30 characters, when submitted, then validation fails |
| M15-R10 | Given a message, when edited or deleted, then it is refused |
| M15-R11 | Given an application not owned by the applicant, when referenced, then it is refused |
| M15-R12 | Given a status change, when committed, then an audit entry is written |

## 10. Test cases

`tests/Feature/Frontend/Grievance/StageGuardTest` — **R01, R02** · `SlaTest` — R03, R04 ·
`InternalMessageTest` — R05, R10 · `Authz/GrievanceScopeTest` — R06, R07, R11 ·
`DuplicateTest` — R08 · `ResolutionTest` — R09 · `AuditTest` — R12.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\Grievance\AssertStageAllowed` |
| R03, R04 | `sla_policies`, `App\Domain\Grievance\EscalateOverdue` |
| R05, R10 | `grievance_messages` scope and append-only guard |
| R06, R07, R11 | `App\Policies\GrievancePolicy` |
| R08, R09 | `App\Http\Requests\Grievance\*` |
| R12 | `App\Domain\Audit\*` (M26), `GrievanceObserver` |
