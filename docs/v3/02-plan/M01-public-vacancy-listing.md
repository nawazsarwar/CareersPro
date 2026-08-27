# M01 — Public Vacancy Listing

**Wave:** 3 · **Scope:** v1 *(saved searches with alerts deferred to v2)*
**Depends on:** M16

## 1. Purpose and statutory basis

The public front door. UGC 2025 draft cl. 3.1 requires direct recruitment to proceed *"through an
**all-India advertisement**"* — the listing is how that advertisement is discoverable, and its
completeness is therefore a compliance concern, not a marketing one.

Benchmark: CU-Chayan offers **nine filters**. We match or exceed them.

## 2. Data

Read-only over `posts`, `advertisements`, `designations` and the OU snapshot columns. No new tables.

**Indexes required by the filters** — every one is asserted by the `TableConfig` index test:
`posts(status, closing_date)` · `posts(designation_id)` · `posts(organisational_unit_id)` ·
`posts(appointment_nature)` · `posts(pay_level)` · a **fulltext index** on `posts(title, subject)`.

## 3. Domain services

```
App\Domain\Public\VacancySearch::apply(Builder, VacancyFilters): Builder
App\Domain\Public\VacancyVisibility::scope(Builder): Builder
```

**Invariants.** Only posts whose advertisement is `published`, whose `withdrawn` is false and whose
`opening_date` has passed are visible. A closed post remains **visible and readable** — it is a
public record — but marked closed and not applicable. Draft and paused advertisements are invisible.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/` | `home` | — | — |
| GET | `/vacancies` | `vacancies.index` | `throttle:120,1` | — |
| GET | `/vacancies/feed.xml` | `vacancies.feed` | `throttle:60,1` | — |
| GET | `/universities` *(single-institution stub)* | `about` | — | — |

**Public and unauthenticated.** No login required to browse — the current system's `frontend.` group
has no `auth` middleware at all, which is a defect there but correct here, deliberately.

## 5. Validation

Filter inputs, all optional, all whitelisted against `TableConfig`:

| Filter | Rules |
|---|---|
| `q` | nullable, string, max:100 — fulltext, escaped |
| `designation_id`, `organisational_unit_id`, `post_type_id`, `advertisement_id` | nullable, exists |
| `cadre` | nullable, in:teaching,non_teaching,library,physical_education,school_teacher |
| `appointment_nature` | nullable, in:general,local |
| `pay_level` | nullable, exists |
| `location` | nullable, string, max:100 |
| `closing_before` | nullable, date |
| `status` | nullable, in:open,closing_soon,closed |

**An unrecognised filter key is ignored, not errored** — a stale bookmark should still work.

## 6. Authorisation

None. Public. But `VacancyVisibility` is applied unconditionally, so an unpublished advertisement is
unreachable even by direct id.

## 7. UI

Ruled list, not cards (`../01-design/ux/design-system.md` §4.3). Each row: post title · designation ·
organisational unit (from the snapshot) · vacancies · pay level · appointment nature · closing date
with a **countdown when under 7 days**.

Filters in a left rail on desktop, a collapsible sheet on mobile. **State lives in the URL**, so a
filtered view is linkable and the back button works.

Mobile-first, `axe-core` clean, works with JavaScript disabled.

## 8. Worked example

A candidate opens `/vacancies?cadre=teaching&organisational_unit_id=11&status=open`.

The query resolves to posts under **Faculty of Arts and its subtree** — matched on
`ou_path_snapshot LIKE '/1/11/%'`, **no join** — that are teaching cadre, published, not withdrawn,
open now. Twelve results, sorted by closing date ascending; the two closing within 7 days show
*"Closes in 3 days"*.

She opens one, and the URL is shareable to a colleague with the filters intact.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M01-R01 | Given an unpublished advertisement, when its post URL is requested directly, then **404** |
| M01-R02 | Given a withdrawn post, when the listing renders, then it is absent |
| M01-R03 | Given a closed post, when requested, then it renders, marked closed, with no apply action |
| M01-R04 | Given nine filters applied together, when the query runs, then results satisfy all nine |
| M01-R05 | Given an unrecognised filter key, when supplied, then it is ignored and the page renders |
| M01-R06 | Given a filtered view, when the URL is copied and reopened, then the same results appear |
| M01-R07 | Given JavaScript disabled, when filtering and paginating, then both work |
| M01-R08 | Given a sortable or filterable column, when checked, then a matching index exists |
| M01-R09 | Given a post closing in under 7 days, when listed, then a countdown is shown |
| M01-R10 | Given the listing route, when `axe-core` runs, then no violation is reported |

## 10. Test cases

`tests/Feature/Public/VacancyVisibilityTest` — R01–R03 · `VacancyFilterTest` — R04, R05, R09 ·
`VacancyUrlStateTest` — R06 · `NoJavascriptTest` — R07 ·
`tests/Unit/Table/IndexCoverageTest` — R08 · `tests/Accessibility/PublicRoutesTest` — R10.

Fixtures: `PostFactory` with `open()`, `closed()`, `withdrawn()`, `unpublished()` states.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03 | `App\Domain\Public\VacancyVisibility` |
| R04–R06, R09 | `App\Domain\Public\VacancySearch`, `VacancyFilters` |
| R07 | `resources/views/public/vacancies/*` |
| R08 | `App\Support\Table\TableConfig` |
| R10 | CI `axe` job |
