# M10 — Applicant Dashboard

**Wave:** 4 · **Scope:** v1
**Depends on:** M05, M08, M34

## 1. Purpose and statutory basis

The candidate's single view of where every application stands, and what they must do next.

No direct statutory basis — this is the **transparency differentiator**, and its limits are
statutory. CU-Chayan's documented weaknesses include *"candidates see only their own score, with no
itemised rationale"* and *"no time-bound in-portal objection window"*. What we may offer is
constrained by **UGC 2018 cl. 5.3**: selection completes **on the day of the committee meeting**, so
there is no room for a post-committee objection. A **screening-stage** window is the only compatible
slot.

**This is University policy requiring Executive Council sanction** (`reservation-and-relaxation.md`
§5) and must **not** be presented as UGC compliance.

## 2. Data

No new tables. Reads `applications`, `eligibility_decisions`, `deficiencies`, `orders`,
`application_status_history`, `score_runs`, `score_lines`.

## 3. Domain services

```
App\Domain\Application\BuildTimeline::for(Application): Timeline
App\Domain\Application\NextActions::for(User): Collection<Action>
App\Domain\Application\VisibleScoreBreakdown::for(Application): ?ScoreBreakdown
```

**Invariants.**
- `VisibleScoreBreakdown` returns the candidate's **own** breakdown only, and only once scrutiny is
  cleared. It **never** reveals another candidate's score, a cut-off, or a relative position.
- Where scoring is blocked by an unratified rule, the dashboard **says so** rather than showing a
  partial total (`../01-design/domain/scoring-engine.md` §4).
- A deficiency window shows a **live countdown**; when it expires the state changes and the timeline
  records it.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/dashboard` | `dashboard` | `auth`, `verified` | — |
| GET | `/applications` | `applications.index` | as above | `ApplicationPolicy@viewAny` |
| GET | `/applications/{application}` | `applications.show` | as above | `@view` |
| GET | `/applications/{application}/timeline` | `applications.timeline` | as above | `@view` |
| POST | `/applications/{application}/deficiencies/{d}/rectify` | `deficiencies.rectify` | as above | `@rectify` |

## 5. Validation

Rectification: the window must be open (`rectification_window_closes_at > now`), and only the
**named sections** of the deficiency may be changed. A submission touching an unnamed section is
refused — a deficiency window is not a general re-open.

## 6. Authorisation

`ApplicationPolicy` — **ownership scope only** on every dashboard route. `viewAny` is scoped to the
actor's own applications; there is no admin path through this module.

## 7. UI

**Applications list:** post · advertisement · submitted date · payment state · **stage** · next
action. Ruled records, not cards.

**Detail:** the timeline as a vertical rule with dated events; the three gate states shown with
**glyph and word** (`✓ Eligible`, `✕ Not eligible`, `◦ Pending`) and their remarks where disclosed;
the score breakdown with per-line citations; documents; the generated form.

**Deficiency banner** — high contrast, with the countdown, the named sections and a direct link.
This is the single most valuable screen in the product for a candidate, because the legacy system
locks irreversibly at payment with no rectification path at all.

## 8. Worked example

Aisha's dashboard shows two applications.

**2599/2026/00412 — System Manager.** Timeline: Submitted 23 Jan · Paid 23 Jan · Under scrutiny
11 Mar · **Deficiency raised 12 Mar**. A banner reads:

> **Action needed — 5 days remaining.** Your experience certificate is illegible. Re-upload it in
> **Employment history**. Closes 19 Mar 2026, 5:00 pm.

Only that section is editable. She re-uploads; the timeline records **Rectified 14 Mar**, snapshot #2
is written, and the state returns to under scrutiny.

**884/2026/01109 — Assistant Professor.** Scrutiny cleared. The score breakdown shows:

> Research papers, Column II — 5 sole-authored × 10 = **50** <span class="citation">App. II Table 2 row 1</span>
> Book, national publisher — **10** <span class="citation">row 2(a)</span>
> Project completed, Co-PI, ₹8 lakh — 5 × 0.50 = **2.5** <span class="citation">row 4(b) · PI/Co-PI 50% each</span>
> **Provisional total 92.5** · threshold 75 <span class="citation">cl. 4.1 II</span>
>
> **Impact-factor scoring is not applied.** It awaits Executive Council ratification of two points
> of interpretation. Your claims are recorded in full.

She sees her own lines and her own total. She does **not** see the cut-off, the shortlist size, or
where she ranks.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M10-R01 | Given candidate A, when opening the dashboard, then only A's applications appear |
| M10-R02 | Given candidate A, when requesting B's application by id, then **403** |
| M10-R03 | Given a score breakdown, when rendered, then **every line carries a rule id and citation** |
| M10-R04 | Given a blocked rule, when the breakdown renders, then it states scoring is blocked and shows **no partial total** for that rule |
| M10-R05 | Given an open deficiency, when the dashboard renders, then a countdown and the named sections appear |
| M10-R06 | Given an expired window, when rectification is attempted, then it is refused |
| M10-R07 | Given a deficiency naming one section, when another is edited, then it is refused |
| M10-R08 | Given a rectification, when saved, then a **new snapshot** is written and the earlier one is untouched |
| M10-R09 | Given any candidate, when viewing their breakdown, then no other candidate's score, cut-off or rank is disclosed |
| M10-R10 | Given a pending gate, when rendered, then it shows **glyph and word**, never colour alone |

## 10. Test cases

`tests/Feature/Dashboard/OwnershipTest` — R01, R02 · `ScoreBreakdownTest` — R03, R04, R09 ·
`DeficiencyWindowTest` — R05–R08 · `tests/Accessibility/DashboardTest` — R10.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Policies\ApplicationPolicy` |
| R03, R04, R09 | `App\Domain\Application\VisibleScoreBreakdown` |
| R05–R08 | `App\Domain\Scrutiny\RectifyDeficiency` (M18) |
| R10 | `resources/views/components/ui/badge.blade.php` |
