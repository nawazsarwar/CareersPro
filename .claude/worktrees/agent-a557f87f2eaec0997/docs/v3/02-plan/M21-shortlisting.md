# M21 — Shortlisting & Cut-offs

**Wave:** 7 · **Scope:** v1
**Depends on:** M20, M34, M17
**Blocked by:** **OQ-008** *(Group B/C interview conflict — teaching shortlisting is unaffected and
proceeds.)*
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Produce ranked and category-wise shortlists, and enforce the statutory ratio.

| Obligation | Source |
|---|---|
| **The Table 3A/3B score is for shortlisting only; selection is on interview performance alone** | UGC 2018 cl. 4.1 I Note, cl. 5.3 |
| Number called for interview **decided by the university** | Table 3A/3B Note (B) |
| **Ratio must not exceed 1:5** — *AMU's own rule, not the UGC model's 1:15* | **AMU CRR Rule 15** |
| Five posts **exempt** from the ratio — Registrar, Finance Officer, Controller of Examinations, Librarian, Director of Physical Education | AMU CRR Rule 15 |
| **Fewer than 2 eligible applicants ⇒ re-advertise at least once more**, then proceed with selection | **AMU CRR Rule 15** |
| Configured call formula **`5 + 3 × (vacancies − 1)`**, per designation, cadre and subject | **DR-019** |
| Where a common written test is held, all eligible (min 3) may be called; interview then subject to 1:15 | CRR Rule 16 |
| Screening Committee may fix **higher** criteria to comply | CRR Rule 16 |

## 2. Data

```
shortlists        id · post_id · rule_set_version_id · type enum(interview, written_test)
                  ratio_applied · cutoff decimal(8,2) NULL · higher_criteria json NULL
                  generated_at · generated_by_id · published_at NULL
                  status enum(draft, published, superseded)
shortlist_entries id · shortlist_id · application_id · rank · score decimal(8,2)
                  category · horizontal_category NULL · is_called bool
                  UNIQUE (shortlist_id, application_id)
```

**Indexes:** `shortlist_entries(shortlist_id, rank)` · `shortlists(post_id, status)`.

## 3. Domain services

```
App\Domain\Shortlist\GenerateShortlist::handle(Post, ShortlistParams, User): Shortlist
App\Domain\Shortlist\AssertRatio::check(Post, int $called): void
App\Domain\Shortlist\AssertMinimumApplicants::check(Post): void
App\Domain\Shortlist\RankCandidates::for(Post, RuleSetVersion): Collection

interface MeritStrategy { public function rank(array $inputs): MeritList; }
App\Domain\Merit\TeachingMeritStrategy       // interview alone
App\Domain\Merit\NonTeachingMeritStrategy    // Paper I + II + interview 20%
```

**Invariants.**

- **`TeachingMeritStrategy` throws on a shortlisting-score input.** It does not ignore it — a silent
  drop would let a caller believe the score was considered:

```php
if (isset($inputs['shortlisting_score'])) {
    throw new StatutoryViolation(
        'UGC 2018 cl. 4.1 I Note: shortlisting score must not enter a teaching merit list'
    );
}
```

- **Only applications with `scrutiny = eligible` are shortlistable.**
- **`AssertRatio` enforces two limits, and both must hold**: the **configured formula**
  `5 + 3 × (vacancies − 1)` (DR-019) **and** the **statutory ceiling** of `5 × vacancies`
  (AMU CRR Rule 15). The five named posts are exempt from both.
- **The formula is versioned, effective-dated configuration, not code.** A change takes effect from
  its effective date without a release, and advertisements published earlier keep the formula they
  were published under — the same freezing principle as the ruleset (I1).
- **A configuration that would exceed the statutory ceiling is rejected at save time**, not at use
  time.
- `AssertMinimumApplicants` blocks generation below **2** eligible and records that re-advertisement
  **at least once more** is required, after which selection proceeds.
- **Ties at the shortlisting stage** are broken by a declared, recorded rule — never database order.
  Default: higher qualification score, then earlier submission, recorded on the shortlist.
  *(Distinct from the interview stage, where **AMU Ordinances Ch. V §4 gives the Chairperson a casting
  vote** — see M14.)*
- **Category-wise lists respect the frozen reservation policy version** — or, with none active,
  are administrator-defined and marked as such.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/posts/{post}/shortlists` | `admin.shortlists.index` | `ShortlistPolicy@viewAny` |
| POST | `/admin/posts/{post}/shortlists` | `admin.shortlists.store` | `@generate` |
| GET | `/admin/shortlists/{shortlist}` | `admin.shortlists.show` | `@view` |
| POST | `/admin/shortlists/{shortlist}/publish` | `admin.shortlists.publish` | `@publish` |
| GET | `/admin/shortlists/{shortlist}/export` | `admin.shortlists.export` | `@view` |
| GET | `/posts/{post:slug}/shortlist` | `shortlists.public` | — *(published only)* |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| post | **must have at least 2 eligible applicants**, unless exempt | Only {n} eligible applicant(s). This post must be re-advertised at least once more (AMU CRR Rule 15). |
| `called_count` | required, integer, min:1 · **≤ the configured formula** unless exempt | The shortlist policy allows {f} candidates for {v} vacancies. |
| | · **≤ vacancies × 5** unless exempt | Calling {n} exceeds the 1:5 ceiling for {v} vacancies (AMU CRR Rule 15). |
| shortlist policy `formula` | required · **must not exceed `5 × vacancies` at any vacancy count** | This formula would exceed the 1:5 statutory ceiling at {v} vacancies. |
| `higher_criteria` | nullable, json · **may only raise, never lower, the statutory threshold** | Screening criteria may be raised, not lowered. |
| `tie_break_rule` | required, in:… | Record how ties are broken. |
| publish | **status must be `draft`**; a published shortlist supersedes rather than mutating | |

## 6. Authorisation

`ShortlistPolicy` extends `ScopedPolicy`. `generate` and `publish` for `recruitment_admin`
university-wide, and for `dean_office` **within their subtree for local posts only**.
The public route serves **published** shortlists only.

## 7. UI

**Generation:** post summary with eligible count, vacancies and the computed 1:15 maximum shown
**before** the admin enters a number. Higher criteria are optional with a live effect preview.

**Shortlist view:** rank, application number, name, category, score, called. Category-wise tabs.
Publishing is a confirmation stating that the list becomes public and superseding requires a new
version.

**The teaching merit warning is a first-class UI element**, not a tooltip:

> This is a **shortlisting** score. Under UGC 2018 cl. 4.1 I, selection is based **only** on interview
> performance. This score decides who is interviewed and plays no further part.

## 8. Worked example

**Post 884, Assistant Professor, 1 vacancy, teaching.**

47 applications; 31 with `scrutiny = eligible`. `AssertMinimumApplicants` passes (31 ≥ 2).
`AssertRatio`: configured formula `5 + 3 × 0` = **5**; statutory ceiling `5 × 1` = **5**.

The admin requests 8 → refused: *"The shortlist policy allows 5 candidates for 1 vacancy."* They
request 5 → allowed.

**Post 2599, 3 vacancies.** Formula → `5 + 3 × 2` = **11**; ceiling → `5 × 3` = **15**. 11 ≤ 15, so
11 is allowed and 12 is refused on the formula, 16 on the ceiling — both figures shown.

`RankCandidates` runs Table 3A against each frozen snapshot. Ranks 1–15 are called; two candidates
tie at 78.0 and the recorded tie-break — higher qualification score, then earlier submission —
resolves them, with the rule stored on the shortlist.

Later the Selection Committee interviews the 15. `TeachingMeritStrategy` builds the merit list from
**interview performance alone**. A developer who passes the shortlisting score gets:

```
StatutoryViolation: UGC 2018 cl. 4.1 I Note: shortlisting score must not enter a teaching merit list
```

**Post 2599, System Manager, 1 vacancy, non-teaching.** Paper I and II are computed, but
`NonTeachingMeritStrategy` **refuses to finalise** — CRR-AMB-01 (whether Group A/B/C gets an
interview at all) and CRR-AMB-02 (whether the composite is 240 or 100) are unratified. It reports
`PendingRatificationError` rather than choosing.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M21-R01 | Given 1 vacancy, when calling 6, then it is refused, citing the configured formula of 5 |
| M21-R02 | Given a Registrar post, when calling above the ratio, then it is **allowed** — exempt |
| M21-R14 | Given 3 vacancies, when the formula resolves, then it yields **11** (`5 + 3 × 2`) |
| M21-R15 | Given a configured formula exceeding `5 × vacancies`, when saved, then it is **refused at save time** |
| M21-R16 | Given a formula change, when a previously published advertisement is shortlisted, then the **frozen** formula applies |
| M21-R03 | Given 1 eligible applicant, when generating, then it is refused and re-advertisement **once more** is recorded |
| M21-R04 | Given an application with scrutiny pending, when ranking, then it is excluded |
| M21-R05 | Given a teaching merit list, when a shortlisting score is passed, then `StatutoryViolation` is thrown — **REG-08** |
| M21-R06 | Given tied scores, when ranked, then the recorded tie-break resolves them deterministically |
| M21-R07 | Given the same inputs, when generated twice, then the ranking is identical |
| M21-R08 | Given higher criteria below the statutory threshold, when set, then it is refused |
| M21-R09 | Given a published shortlist, when regenerated, then a **new** version supersedes it; the original is retained |
| M21-R10 | Given a draft shortlist, when requested publicly, then **404** |
| M21-R11 | Given a Dean's-office user of Faculty X, when generating for Faculty Y, then **403** |
| M21-R12 | Given a non-teaching post with OQ-008 open, when finalising merit, then `PendingRatificationError` |
| M21-R13 | Given a shortlist, when displayed, then the shortlisting-only notice is present |

## 10. Test cases

`tests/Feature/Admin/Shortlist/RatioTest` — R01, R02, **R14, R15, R16** · `MinimumApplicantsTest` — R03 ·
`EligibilityFilterTest` — R04 · **`tests/Unit/Merit/TeachingMeritStrategyTest` — R05 (REG-08)** ·
`TieBreakTest` — R06, R07 · `HigherCriteriaTest` — R08 · `PublishTest` — R09, R10, R13 ·
`Authz/ShortlistScopeTest` — R11 · `NonTeachingMeritTest` — R12.

Fixtures: a 47-application cohort with a deliberate tie at rank 8; a Registrar post for R02.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03 | `App\Domain\Shortlist\AssertRatio`, `AssertMinimumApplicants` |
| R04, R06, R07 | `App\Domain\Shortlist\RankCandidates` |
| R05 | `App\Domain\Merit\TeachingMeritStrategy` |
| R08 | `App\Http\Requests\Shortlist\GenerateShortlistRequest` |
| R09, R10, R13 | `App\Domain\Shortlist\GenerateShortlist`, public controller |
| R11 | `App\Policies\ShortlistPolicy` |
| R12 | `App\Domain\Merit\NonTeachingMeritStrategy` |
| R14 | `App\Domain\Shortlist\AssertRatio` |
| R15 | `App\Rules\ShortlistFormula` — refused at save |
| R16 | `App\Domain\Recruitment\FreezeRuleSet` (M16) |
