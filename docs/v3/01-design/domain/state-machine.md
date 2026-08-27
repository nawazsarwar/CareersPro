# Application State Machine

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Supersedes:** `docs/v2-archive/spec/state-machine.md` — 23 lines defining 8 linear states, which cannot
express a domain with three independent gates, has no Withdrawn state, no Not-Eligible terminal, no
return edge from Deficiency, and references a *"quorum sign-off from the Screening Committee"* using
a quorum that is defined nowhere and a Committee entity that exists in neither schema.

---

## 1. The core insight

**The application lifecycle is not a chain. It is four independent dimensions that advance
concurrently.** Forcing them into one linear `status` column is what produced a nullable integer that
the wizard writes the string `'Submitted'` into.

```
   ┌─ LIFECYCLE ──────────────────────────────────────────────────────┐
   │  draft → submitted → under_scrutiny → … → selected / archived    │
   └──────────────────────────────────────────────────────────────────┘
   ┌─ PAYMENT ─────────────┐  ┌─ GATES (0–3 active) ──────────────────┐
   │ unpaid → pending →    │  │ scrutiny      : NULL | eligible | rej │
   │ paid | failed |       │  │ written_test  : NULL | eligible | rej │
   │ double_payment        │  │ interview     : NULL | eligible | rej │
   └───────────────────────┘  └───────────────────────────────────────┘
   ┌─ CUSTODY (hard copy) ────────────────────────────────────────────┐
   │ not_required | awaited → received → due_for_destruction → destroyed│
   └──────────────────────────────────────────────────────────────────┘
```

**The active gate set is derived, never assumed.** It comes from
`post_types.default_selection_method`:

| Selection method | Active gates |
|---|---|
| `interview_only` | scrutiny · interview |
| `written_interview` | scrutiny · written_test · interview |
| `written_skill_interview` | scrutiny · written_test · interview |
| `trade_test` / `driving_test` | scrutiny · written_test |

An interview-only post **has no `written_test` gate** — no row, and the UI must not render one. The
legacy modal enables all three regardless, on a legally consequential decision. The schema comment in
production even says written-test eligibility should be *"Blank if post is interview-only"*.

---

## 2. Lifecycle states

| State | Meaning | Terminal? |
|---|---|---|
| `draft` | Wizard started, not submitted. Editable. | no |
| `submitted` | Submitted. **Snapshot taken. Dossier locked.** | no |
| `under_scrutiny` | A scrutiny officer has opened it | no |
| `deficient` | Deficiency raised; rectification window open | no |
| `scrutiny_cleared` | Scrutiny gate = eligible | no |
| `rejected` | Any active gate = rejected | **yes** |
| `shortlisted` | Appears on a published shortlist | no |
| `test_scheduled` | Roll number and seat allocated | no |
| `interviewed` | Interview attendance recorded | no |
| `selected` | On the merit list as selected | **yes** |
| `waitlisted` | On the merit list as waitlisted | no |
| `not_selected` | Process complete, not selected | **yes** |
| `withdrawn` | Candidate retracted | **yes** |
| `archived` | Process closed. **Retained indefinitely (DR-011)** | **yes** |

> **`archived` is not deletion.** Nothing is ever removed electronically. See §6.

---

## 3. Transitions

Every transition names an **actor**, a **guard**, a **side-effect** and an **audit event**. A
transition with no guard is a defect.

| # | From → To | Actor | Guard | Side-effect |
|---|---|---|---|---|
| T1 | `draft → submitted` | candidate | profile complete · all mandatory sections done · declaration accepted · **post is open** (`now ≤ closing_date`) · not already applied to this post | **Write `application_snapshot` with `content_hash`.** Copy `rule_set_version_id` + `relaxation_policy_version_id` from the advertisement. Allocate `application_no`. Lock the dossier. Create eligibility_decision rows for the **active gates only** |
| T2 | `submitted → under_scrutiny` | scrutiny officer | payment `paid` (unless fee-exempt) · **actor's OU scope covers the post** (DR-010) | Record `opened_by`, `opened_at` |
| T3 | `under_scrutiny → deficient` | scrutiny officer | deficiency description non-empty · `rectification_window_closes_at` in the future | Notify candidate. Re-open the named sections only |
| T4 | `deficient → under_scrutiny` | candidate | within the rectification window | **Write a new snapshot** (`reason: correction_window`). Re-lock |
| T5 | `deficient → rejected` | system | rectification window expired, unrectified | Set `scrutiny` gate = `rejected`, remark `deficiency not rectified` |
| T6 | `under_scrutiny → scrutiny_cleared` | scrutiny officer | `scrutiny` gate = `eligible` · **decided_by ≠ the candidate** | Advance |
| T7 | `* → rejected` | scrutiny officer / system | **any active gate** = `rejected` · remark non-empty | Terminal. Remark is **mandatory** — a rejection without a reason is not appealable |
| T8 | `scrutiny_cleared → shortlisted` | system | on a published shortlist · **configured formula and the 1:5 ceiling satisfied** (DR-019, AMU CRR Rule 15) | Record `shortlist_entry` |
| T9 | `shortlisted → test_scheduled` | exam admin | roll number allocated · centre has capacity | Allocate seat. Enable admit card **within the window only** |
| T10 | `* → interviewed` | interview admin | attendance recorded | |
| T11 | `interviewed → selected \| waitlisted \| not_selected` | committee | **merit list approved** · quorum met (§4) | Terminal |
| T12 | `* → withdrawn` | candidate | not yet `selected` · **before the closing date** | **No refund** (fee is non-refundable) |
| T13 | `* → archived` | system | process closed | Set `hardcopy_receipts.destruction_due_on` = close + 5 years, **unsuccessful candidates only** |

### 3.1 Two guards that are statutory, not conventional

**G1 — Ruleset immutability.** No transition may change `rule_set_version_id` or
`relaxation_policy_version_id` after T1. They are copied once and are read-only thereafter. An
advertisement published under UGC 2018 scores under 2018 for ever, even after 2025 is notified.

**G2 — Merit-source separation.** For a **teaching** designation, `MeritStrategy` **must reject a
shortlisting score as an input** (UGC 2018 cl. 4.1 I Note, cl. 5.3). This is enforced at the type
level in code, not by convention. Violating it is a statutory violation, not a bug.

---

## 4. Gate rules

```
eligibility_decisions
  (application_id, gate) UNIQUE
  decision: NULL (pending) | eligible | rejected
  remark, decided_by_id, decided_at
```

1. **Rows exist only for active gates.** Derived from `post_types.default_selection_method`.
2. **`NULL` is pending and is a distinct third value.** The legacy UI renders a merged label
   *"Pending / Not Eligible"* over `1` / `0` / `NULL`. Not reproduced.
3. **Gates are ordered but independent.** `written_test` cannot be decided before `scrutiny` is
   `eligible`; but a `written_test` rejection does not retroactively alter the `scrutiny` decision.
4. **A rejection remark is mandatory.** Enforced at the request layer.
5. **Every decision writes an audit entry** with actor, IP and previous value.
6. **Decisions are revisable, never overwritten silently** — a change writes a new audit entry
   carrying the prior value. CRR Rule 22.4 permits verification *"at any point of time even after
   joining"*, so the history must survive.

### 4.1 Committee quorum — real numbers, not a placeholder

The old spec's *"requires quorum sign-off"* had no quorum defined. These are transcribed:

| Committee | Quorum |
|---|---|
| UGC 2018 — Asst./Assoc./Professor, University | **4**, including **2 external subject experts** |
| UGC 2018 — Colleges | **5**, including **2 external subject experts** |
| UGC 2025 draft — universities | **5**, including **2 external subject experts** |
| CRR 2022 — Selection Committee / DPC / DCC | **two-thirds of members**, including the Chairperson, the VC's nominee where applicable, **≥1 of the 2 external experts**, and **1 reserved-category representative** |

**CRR minority representative:** associated only where **vacancies ≥ 10**.

---

## 5. The SLA clocks

Two statutory timers, both requiring breach alerting.

| Clock | Limit | Starts | Source |
|---|---|---|---|
| Advertisement window | **≥ 30 days** | publish | CRR |
| Hard-copy receipt | per advertisement, **17:00 cut-off** | online close | Advertisements |
| Process completion | **6 months**, extendable **once to 12** | publish | DoPT O.M. Misc.14017/15/2015-Estt.(RR) |

The extension requires a **recorded VC approval artefact** — an approval with no record is not an
approval.

**A third, non-statutory clock:** `deficiencies.rectification_window_closes_at`. This is University
policy (M15) and must be sanctioned by the Executive Council — it has **no UGC backing**. And UGC
2018 cl. 5.3 requires selection to complete on the day of the committee meeting, so **the only
compatible slot for any objection window is the screening stage**, never post-committee.

---

## 6. What never happens

| Never | Why |
|---|---|
| An application row is deleted | DR-011. `archived`, never removed |
| A snapshot is updated or deleted | Append-only. It is the evidence |
| An audit row is updated or deleted | Hash-chained. `audit_logs` has **no `updated_at`** |
| A frozen ruleset version changes | G1 |
| A shortlisting score reaches a teaching merit list | G2 — statutory violation |
| A gate is decided outside the actor's OU scope | DR-010 |
| A rejection is recorded without a remark | Not appealable |

---

## 7. Worked example — deficiency and recovery

Application `10087779`, post 2599 (`written_skill_interview` → all three gates active).

| Step | State | Gates | Detail |
|---|---|---|---|
| 1 | `draft` | — | Candidate completes the wizard |
| 2 | `submitted` | scrutiny NULL · written NULL · interview NULL | **T1.** Snapshot `#1`, hash `a3f9…`. Three gate rows created |
| 3 | `submitted` | unchanged | Payment: gateway debits, callback lost. Reconciliation matches `pg_ref_no` → `paid`. **No second charge** |
| 4 | `under_scrutiny` | unchanged | **T2.** Officer's OU scope covers the Computer Centre |
| 5 | `deficient` | unchanged | **T3.** Experience certificate illegible. Window closes in 7 days. Only that section re-opens |
| 6 | `under_scrutiny` | unchanged | **T4.** Candidate re-uploads on day 3. **Snapshot `#2`, hash `b71c…`, reason `correction_window`.** Snapshot `#1` is untouched |
| 7 | `scrutiny_cleared` | **scrutiny eligible** · written NULL · interview NULL | **T6.** Remark recorded, audit entry chained |
| 8 | `shortlisted` | unchanged | **T8.** Rank 12 of 15 under the 1:15 cap |
| 9 | `test_scheduled` | unchanged | **T9.** Roll `2599-0012`, centre, room, seat. Admit card enabled 2026-04-01 → 04-10 only |
| 10 | `rejected` | scrutiny eligible · **written rejected** · interview NULL | **T7.** Scored below 40% in Paper I. Remark mandatory. **The scrutiny decision is unchanged** — gates are independent |
| 11 | `archived` | unchanged | **T13.** `destruction_due_on` = close + 5 years. Both snapshots, all gate history and the full audit chain **persist indefinitely** |

If in 2029 the candidate files an RTI or a service appeal, snapshot `#1` shows exactly what was
submitted, snapshot `#2` what was rectified, the gate history who decided what and when, and the
audit chain proves none of it was altered.

---

## 8. Traceability

| Section | Feeds |
|---|---|
| §1, §4 | M18 Scrutiny Workbench · M34 Eligibility Gates |
| §3 | M05 · M10 · M18 · M21 |
| §3.1 G2 | `scoring-engine.md` · M21 |
| §4.1 | M19 Committee Workspace |
| §5 | M16 · M23 · M15 |
| §6 | `snapshot-and-audit.md` · M26 · M27 |

---

## 9. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Replaces the 8-state linear chain with four orthogonal dimensions and three post-type-derived gates. 13 transitions with actor, guard, side-effect. Real quorum figures transcribed. Two statutory SLA clocks. | Implementation team |
