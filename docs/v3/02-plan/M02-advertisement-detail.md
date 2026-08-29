# M02 — Advertisement Detail

**Wave:** 3 · **Scope:** v1
**Depends on:** M16, M01
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

The public reading of a statutory notice. A candidate must be able to determine, **before paying a
non-refundable fee**, whether they are eligible.

| Obligation | Source |
|---|---|
| Applications only in the prescribed format, with the prescribed fee | CRR Rule 11 III(a)–(c) |
| Incomplete and late applications not entertained; **closing date on a holiday moves to the next working day** | CRR Rule 11 III(d) |
| Applications addressed to *"The Registrar"* in a closed cover **super-scribing the post applied for** | CRR Rule 11 III(e) |
| Eligibility criteria decided and published per advertisement | CU-Chayan FAQ; UGC 2018 cl. 4.1 |

## 2. Data

Read-only over `advertisements`, `corrigenda`, `posts`, `post_vacancy_breakup`, `designations`,
`rule_set_versions`. No new tables.

## 3. Domain services

```
App\Domain\Public\RenderEligibilitySummary::for(Post): EligibilitySummary
App\Domain\Public\SubmissionInstructions::for(Post): Instructions
```

**Invariants.** The eligibility summary is rendered **from the frozen ruleset version bound to the
advertisement**, never from the currently active one. Submission instructions come from
`post_types.submission_venue`, so the address a candidate is told to post to is the one configured
for that post type — not a hardcoded string.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/advertisements/{advertisement:slug}` | `advertisements.show` | `throttle:120,1` | — |
| GET | `/posts/{post:slug}` | `posts.show` | `throttle:120,1` | — |
| GET | `/advertisements/{advertisement}/document` | `advertisements.document` | `throttle:60,1` | — |

## 5. Validation

No user input beyond route binding. Slug binding is scoped by `VacancyVisibility`, so an unpublished
slug **404s** rather than 403s — the existence of an unpublished advertisement is itself not public.

## 6. Authorisation

None. Public. Visibility scoping applies unconditionally (M01 §6).

## 7. UI

Per `../01-design/ux/screens.md` §4–§5, public variant.

**Advertisement:** number · title · dated · type · appointment nature · organisational unit ·
description · document download · **corrigenda list, dated** · child posts.

**Post:** designation · unit · vacancies with **category-wise breakdown** · pay level and range ·
fee · the three dates with markers · **eligibility summary** · **submission instructions naming the
exact venue** · apply action.

**Two things that must be unmissable**, because both are recorded pain points:

1. **The fee is non-refundable** — stated at the apply action, not buried.
2. **Hard copy is required** (DR-011) — stated with the venue, the deadline and *"a separate envelope
   for each post applied for."* The legacy manual buries this at step 8.

The eligibility summary shows each criterion with its **`.citation`** treatment, so a candidate can
see the clause behind the requirement.

## 8. Worked example

Post 2599, System Manager, under advertisement 2/2026/NT.

The page shows: **Pay Level-12 (₹78,800–₹2,09,200) plus allowances** · 1 vacancy · fee **₹500** ·
opens 22 Jan 2026 · **closes 7 Mar 2026** · payment closes 7 Mar 2026 · organisational unit
**Computer Centre**.

Eligibility summary, rendered from `ugc-crr-non-teaching-2022@1`:

> Maximum age **50 years** <span class="citation">CRR Sch-1 · computed as at 7 Mar 2026, Rule 14</span>
> Selection: **written test and interview** <span class="citation">CRR Rule 11 III(f)</span>

Submission instructions, from the post type:

> Print, sign and post to the **Joint Registrar, Selection Committee (Non-Teaching) Section,
> Registrar's Office, AMU Aligarh 202002**, to arrive by **5:00 pm on 7 Mar 2026**. Use a separate
> envelope for each post. Super-scribe *"Application for the post of System Manager."*

A corrigendum issued 20 Feb 2026 extending the closing date appears **above** the dates, dated, with
its own description.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M02-R01 | Given an unpublished advertisement slug, when requested, then **404** |
| M02-R02 | Given a corrigendum, when the advertisement renders, then it appears dated and above the affected fields |
| M02-R03 | Given a post, when the eligibility summary renders, then it derives from the **frozen** ruleset version |
| M02-R04 | Given the active ruleset later changes, when the page is reloaded, then the summary is **unchanged** |
| M02-R05 | Given a post, when submission instructions render, then the venue comes from `post_types.submission_venue` |
| M02-R06 | Given a post, when the apply action renders, then the non-refundable fee notice is present |
| M02-R07 | Given a post, when the page renders, then the hard-copy requirement, venue and deadline are present |
| M02-R08 | Given a category-wise breakdown, when it exists, then vacancies are shown per category |
| M02-R09 | Given a closed post, when rendered, then the apply action is absent and the reason stated |
| M02-R10 | Given the detail route, when `axe-core` runs, then no violation is reported |

## 10. Test cases

`tests/Feature/Frontend/Public/AdvertisementDetailTest` — R01, R02, R08, R09 ·
`EligibilitySummaryTest` — R03, R04 · `SubmissionInstructionsTest` — R05, R07 ·
`FeeNoticeTest` — R06 · `tests/Accessibility/PublicRoutesTest` — R10.

Fixtures: an advertisement with two corrigenda and a post type carrying a distinctive venue string,
so R05 cannot pass on a hardcoded value.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01 | `App\Domain\Public\VacancyVisibility` |
| R02, R08, R09 | `resources/views/public/advertisements/show.blade.php` |
| R03, R04 | `App\Domain\Public\RenderEligibilitySummary` |
| R05, R07 | `App\Domain\Public\SubmissionInstructions` |
| R06 | `resources/views/public/posts/show.blade.php` |
| R10 | `resources/views/public/**` — axe-core assertion in CI |
