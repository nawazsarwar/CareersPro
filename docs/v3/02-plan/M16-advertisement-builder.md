# M16 — Advertisement Builder

**Wave:** 3 · **Scope:** v1
**Depends on:** DR-006, DR-009, DR-010, DR-012, DR-017, DR-018 · M24, M35, M17

## 1. Purpose and statutory basis

Create and publish advertisements and their child posts, binding each to a designation, an
organisational unit, a sanctioned strength and — decisively — **a frozen ruleset version**.

| Obligation | Source |
|---|---|
| *"Direct recruitment … based on merit through an **all-India advertisement**"* | UGC 2025 draft cl. 3.1 |
| **30-day minimum** advertisement window | CRR |
| **6-month process cap**, extendable **once to 12** | DoPT O.M. Misc.14017/15/2015-Estt.(RR) |
| Closing date falling on a holiday moves to **the next working day** | CRR Rule 11 III(d) |
| Fee schedule determined by the **Vice-Chancellor** | CRR Rule 11 III(c) |
| **Hard-copy deadline is separate from the online deadline** and is set per advertisement — 10 days in Advt. 1/2024/NT, 16 days in 1/2025/NT — with a **17:00 cut-off** against the online 23:59 | Advertisements |
| Only posts **sanctioned by the UGC** may be advertised | CRR Rule 34.4 |
| Single sanctioned post ⇒ **direct recruitment only** | CRR Rule 34.3 |

## 2. Data

`advertisements`, `corrigenda`, `posts`, `post_vacancy_breakup`, `post_types` — full schema in
`../01-design/domain/domain-model.md` §5.

**The columns this restores**, present in production and dropped by the previous redesign:
`posts.age_limit` · `min_experience_months` · `selection_method` · `admit_card_opening_date` ·
`admit_card_closing_date` · `interview_letter_opening_date` · `interview_letter_closing_date`.
Without them `isAgeOverLimit()` and download-window enforcement have no backing data.

**The columns this adds:** `appointment_nature` · `tenure_months` · `designation_id` ·
`organisational_unit_id` **plus the four snapshot columns** · `rule_set_version_id` ·
`relaxation_policy_version_id` · four counter columns for the composite cell.

## 3. Domain services

```
App\Domain\Recruitment\PublishAdvertisement::handle(Advertisement): void
App\Domain\Recruitment\FreezeRuleSet::handle(Advertisement): RuleSetVersion
App\Domain\Recruitment\SnapshotOrganisationalUnit::handle(Post|Advertisement): void
App\Domain\Recruitment\IssueCorrigendum::handle(Advertisement, CorrigendumData): Corrigendum
App\Domain\Recruitment\AssertWindow::check(Advertisement): void
App\Domain\Recruitment\NextWorkingDay::from(CarbonInterface): CarbonInterface
```

**Invariants.**
- **Publishing freezes** `rule_set_version_id`, `relaxation_policy_version_id`, the **payment gateway** and the OU snapshot. All become read-only. This is scoring-engine invariant **I1**.
- A published advertisement **cannot be edited**. Changes are **corrigenda**.
- `AssertWindow` refuses a closing date fewer than **30 days** after publication.
- A closing date on a declared holiday is moved to the **next working day** and the move is recorded.
- **Counter columns are maintained by observers**, never computed per render.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/advertisements` | `admin.advertisements.index` | `AdvertisementPolicy@viewAny` |
| GET/POST/PATCH | `/admin/advertisements/{a?}` | `admin.advertisements.*` | `AdvertisementPolicy@*` |
| POST | `/admin/advertisements/{a}/publish` | `admin.advertisements.publish` | `@publish` |
| POST | `/admin/advertisements/{a}/corrigenda` | `admin.corrigenda.store` | `@issueCorrigendum` |
| POST | `/admin/advertisements/{a}/extend` | `admin.advertisements.extend` | `@extend` |
| GET/POST/PATCH | `/admin/posts/{p?}` | `admin.posts.*` | `PostPolicy@*` |
| POST | `/admin/posts/{p}/withdraw` | `admin.posts.withdraw` | `PostPolicy@withdraw` |
| GET | `/admin/advertisements/{a}/export/{scope}` | `admin.advertisements.export` | `@export` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `advertisement_no` | required, unique, max:100 | That advertisement number already exists. |
| `title` | required, max:500 | |
| `type_id` | required, exists | |
| `appointment_nature` | required, in:general,local | |
| `organisational_unit_id` | required, exists, **`SelectableOrganisationalUnit`** | Select a published unit with a permanent code. |
| `default_closing_date` | required, date, **`after_or_equal:default_opening_date`**, **`at_least_30_days_after:publish`** | The advertisement must remain open for at least 30 days. |
| `default_payment_closing_date` | required, **`after_or_equal:default_closing_date`** | Payment cannot close before applications close. |
| `default_hardcopy_closing_date` | required, **`after:default_closing_date`**, time **17:00** | Hard copies must be due after the online deadline, at 5:00 pm. |
| `gateway` | required, in the registered adapter list (DR-018) | Select a payment gateway for this advertisement. |
| `designation_id` (post) | required, exists, **`RuleSetGovernsCadre`** | |
| `vacancies` | required, integer, min:1, **`WithinSanctionedStrength`** | Only {n} vacancies are available against a sanctioned strength of {s} ({f} filled). |
| `tenure_months` | **required_if:appointment_nature,local**, integer, between:1,12 · **null when general** | A local appointment runs for 1 to 12 months. |
| `fee` | required, numeric, min:0 | |
| `max_age` | nullable, integer — **defaults from the designation** | |
| `admit_card_opening_date` | **required_if:selection_method,written_interview,written_skill_interview** | Set the admit card download window. |
| `admit_card_closing_date` | **after:admit_card_opening_date**, **before_or_equal:test_date** | The admit card window must close on or before the test date. |
| `interview_letter_*` | as above, relative to `interview_date` | |
| corrigendum `description` | required, min:20 | Describe what has changed. |
| extension `reason` + `vc_approval_ref` | **both required** | An extension needs the Vice-Chancellor's approval reference. |

## 6. Authorisation

`AdvertisementPolicy` and `PostPolicy` extend `ScopedPolicy`.

| Actor | May |
|---|---|
| `recruitment_admin` | create, edit and publish **General** advertisements, university-wide |
| `dean_office_admin` | create, edit and publish **Local** advertisements **within their OU subtree only** |
| `dean_office_*` | **403** on any General advertisement — centrally administered (DR-010) |
| everyone | read published |

Scope resolves on `ou_path_snapshot`, so no join is needed
(`../01-design/domain/organisational-units.md` §5).

## 7. UI

List and detail per `../01-design/ux/screens.md` §3–§4, including the child-post sub-grid with its
composite `106 / 63 / 58 / 13⚑` cell and the **frozen ruleset shown with its citation treatment**.

The publish action is a **confirmation dialogue** that states exactly what is being frozen:

> Publishing binds this advertisement to **ugc-teaching-2018 @ v1** and snapshots
> **Department of English (DENG)**. After publishing, changes require a corrigendum.

## 8. Worked example

Advertisement **2/2026/NT**, General, Registrar's Office.

1. Draft created. `appointment_nature: general`, OU = Computer Centre.
2. Post added: designation `SYS-MGR`, 1 vacancy. `WithinSanctionedStrength` checks 1 against
   sanctioned 1, filled 0 → allowed. `AssertRule343` allows direct recruitment.
   `age_limit` defaults to **50** from the designation; `selection_method` to
   `written_skill_interview`, so the admit-card window becomes required.
3. Opening 2026-01-22, closing **2026-03-07** → 44 days → window satisfied.
4. **Publish.** Freezes `rule_set_version_id = ugc-crr-non-teaching-2022@1`, snapshots the OU as
   `{CCENTRE, 'Computer Centre', 'Services and Other Offices', '/1/6/'}`, sets `published_at`, and
   starts the 6-month process clock.
5. On 2026-02-20 the closing date is extended to 2026-03-21. It is **not an edit** — a corrigendum is
   issued, dated and published, and the extension records the VC approval reference.
6. In 2028 the Computer Centre is renamed. Advertisement 2/2026/NT **still reads "Computer Centre"**,
   because the snapshot is frozen. A new advertisement picks up the new name.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M16-R01 | Given publication, when it completes, then `rule_set_version_id` and the OU snapshot are set and read-only |
| M16-R02 | Given a published advertisement, when edited directly, then it is refused and a corrigendum is offered |
| M16-R03 | Given a closing date 20 days after publication, when publishing, then it is refused, **citing the 30-day rule** |
| M16-R04 | Given a closing date on a declared holiday, when saved, then it moves to the next working day and the move is recorded |
| M16-R05 | Given sanctioned 1 and filled 0, when advertising 2 vacancies, then it is refused with counts |
| M16-R06 | Given a single sanctioned post, when the method is promotion, then it is refused **citing Rule 34.3** |
| M16-R07 | Given `appointment_nature: local` without `tenure_months`, when saved, then validation fails |
| M16-R08 | Given a Dean's-office user of Faculty X, when creating a Local advertisement for Faculty Y, then **403** |
| M16-R09 | Given a Dean's-office user, when opening any General advertisement, then **403** |
| M16-R10 | Given a renamed organisational unit, when a published advertisement is viewed, then the **original** name is shown |
| M16-R11 | Given a `written_interview` post without an admit-card window, when saved, then validation fails |
| M16-R12 | Given an extension without a VC approval reference, when saved, then validation fails |
| M16-R13 | Given 100 posts listed, when the composite cell renders, then counts come from counter columns — **query count within budget** |
| M16-R14 | Given a `draft` or `TMP-` coded unit, when selecting the advertisement's unit, then it is not offered |
| M16-R15 | Given a hard-copy deadline before the online deadline, when saved, then validation fails |
| M16-R16 | Given publication, when it completes, then the selected payment gateway is frozen on the advertisement |

## 10. Test cases

`tests/Feature/Recruitment/PublishTest` — R01, R02, R10 · `WindowTest` — R03, R04, R11, R12 ·
`SanctionedStrengthGuardTest` — R05, R06 · `AppointmentNatureTest` — R07 ·
`Authz/AdvertisementScopeTest` — R08, R09 · `CompositeCountPerformanceTest` — R13 ·
`UnitSelectabilityTest` — R14 · `HardcopyWindowTest` — R15 · `GatewaySelectionTest` — R16.

Fixtures: `AdvertisementFactory` with `general()` / `local()` / `published()` states;
a holiday calendar fixture for R04.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R10 | `App\Domain\Recruitment\PublishAdvertisement`, `FreezeRuleSet`, `SnapshotOrganisationalUnit` |
| R03, R04 | `App\Domain\Recruitment\AssertWindow`, `NextWorkingDay` |
| R05, R06 | `App\Domain\Establishment\*` (M35) |
| R07, R11, R12, R14 | `App\Http\Requests\Recruitment\*` |
| R08, R09 | `App\Policies\AdvertisementPolicy` |
| R13 | `PostObserver` counter columns |
