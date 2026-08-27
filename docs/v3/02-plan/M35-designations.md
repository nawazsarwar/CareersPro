# M35 — Designation & Sanctioned Strength Register

**Wave:** 2 · **Scope:** v1
**Depends on:** DR-012 · M24
*(DOC-004 closed — the **AMU Cadre Recruitment Rules** are obtained and are the seed source.)*
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

**The missing spine.** Today a post carries a free-text title, pay level and pay range, so **nothing
connects a vacancy to the regulation that governs it** — legacy `careers_db.posts` holds
*"Assistant Professor, Dept of Conservative Dentistry & Endodontics"* as a string.

A `Designation` is the definition of a post: cadre, group, pay, qualifications, age, experience,
selection method, and the ruleset that governs it. A **Post becomes an instance of a Designation, in
an Organisational Unit, under an Advertisement** — which is what lets M20 bind rules to a stable
entity.

**Statutory basis for the sanctioned-strength half:**

- **CRR Rule 8, 9.1** — authorised strength per Schedule-1; new posts added with EC approval; the EC
  may abolish under intimation to UGC; **conversion requires prior UGC approval**.
- **CRR Rule 34.3** — *"wherever there is only **one sanctioned post in any cadre**, the post shall be
  filled **through direct recruitment only**."*
- **CRR Rule 34.4** — adopt the CRR only for posts **sanctioned by the UGC**.

`MODULES.md` #16 promised *"post creation linked to sanctioned strength"* with **no backing data
anywhere in either database**. This supplies it.

## 2. Data

```
designations
  id · code UNIQUE NOT NULL · name · name_short
  cadre enum(teaching, non_teaching, library, physical_education, school_teacher)
  group enum(A, B, C) NULL                     -- non-teaching only
  pay_level · pay_range · retirement_age
  min_age NULL · max_age NULL
  age_reference enum(application_closing_date) NOT NULL DEFAULT 'application_closing_date'
  essential_qualification json · desirable_qualification json
  experience_rules json · method_of_recruitment json
  selection_method enum(interview_only, written_interview,
                        written_skill_interview, trade_test, driving_test)
  rule_set_id → rule_sets
  status enum(active, inactive) · remarks
  soft deletes, timestamps

organisational_unit_designation
  id · organisational_unit_id · designation_id
  sanctioned_count unsigned NOT NULL
  sanction_order_ref · sanctioned_on
  UNIQUE(organisational_unit_id, designation_id)
```

**Indexes:** `designations(cadre, status)` · `designations(group)` ·
`organisational_unit_designation(organisational_unit_id)`.

**Seed: the AMU Cadre Recruitment Rules** (`docs/AMU_Cadre_Recruitment_Rules.pdf`, 1.07M chars) —
**Schedule-1, pages 1–358**, which lists every post **organisational unit by organisational unit**:
Common Pool of Non-Teaching Posts (pages 1–18), then each Faculty and Department, and — critically —
**JNMC Hospital (137–164), College of Nursing (165), Modern Trauma Centre (166–175) and
Dr. Ziauddin Ahmad Dental College (176–178)**, which are the medical and paramedical cadres the UGC
model rules omit.

**AMU's Schedule-1 is keyed *organisational unit × post*, which is exactly this module's model** —
`designations` × `organisational_unit_designation`. Each entry carries 14 columns including number of
posts, Group, Pay Level, selection/non-selection, age limit, essential and desirable qualifications,
probation, method of recruitment, **and the Selection Committee composition inline** (which feeds
M19).

Cross-referenced against the 58 UGC model cadres (`../01-design/regulatory/ugc-crr-non-teaching-2022.md` §2)
and the 11 UGC teaching cadres (`ugc-teaching-2018.md` §1); **where AMU's rules differ, AMU's govern**
for non-teaching. Data Lake's 346 designation names are used
**only as a vocabulary cross-check** — that table has `code`, `pay_grade`, `retirement_age` and
`type_id` **NULL on every one of its 346 rows**, and `designation_types` is empty.

## 3. Domain services

```
App\Domain\Establishment\SanctionedStrength::for(OrganisationalUnit, Designation): int
App\Domain\Establishment\AvailableVacancies::for(OrganisationalUnit, Designation): int
App\Domain\Establishment\AssertRule343::check(OrganisationalUnit, Designation, Method): void
App\Domain\Establishment\ResolveRuleSet::for(Designation, Advertisement): RuleSetVersion
```

**Invariants.**
- `AvailableVacancies` = `sanctioned_count` − filled − advertised-and-open. **It never returns a
  negative**; an over-advertisement is a hard error, not a warning.
- `AssertRule343` throws when `sanctioned_count === 1` and the method is anything but direct
  recruitment.
- `age_reference` is **always** `application_closing_date` (CRR Rule 14). The column exists to make
  the rule explicit and greppable, not to make it configurable.
- A designation's `rule_set_id` is resolved to a **version** at advertisement publish and frozen
  there — never at scoring time.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/designations` | `admin.designations.index` | `DesignationPolicy@viewAny` |
| GET/POST/PATCH | `/admin/designations/{d?}` | `admin.designations.*` | `DesignationPolicy@*` |
| GET | `/admin/establishment` | `admin.establishment.index` | `EstablishmentPolicy@viewAny` |
| POST/PATCH | `/admin/establishment/{ou}/{designation}` | `admin.establishment.upsert` | `EstablishmentPolicy@update` |
| GET | `/admin/establishment/export` | `admin.establishment.export` | `EstablishmentPolicy@viewAny` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `code` | required, unique:designations, `regex:/^[A-Z0-9\-]+$/`, max:50 | Use capitals, digits and hyphens only. |
| `name` | required, max:191 | |
| `cadre` | required, in:teaching,non_teaching,library,physical_education,school_teacher | |
| `group` | **required_if:cadre,non_teaching**, in:A,B,C · **must be null otherwise** | Group applies to non-teaching cadres only. |
| `pay_level` | required, max:50 | |
| `min_age` / `max_age` | nullable, integer, between:18,70 · **`max_age` ≥ `min_age`** | Maximum age cannot be below minimum age. |
| `selection_method` | required, in:… | |
| `rule_set_id` | required, exists:rule_sets,id · **the ruleset's `applies_to` must include this cadre** | That ruleset does not govern this cadre. |
| `sanctioned_count` | required, integer, min:0 · **may not be reduced below the filled count** | Reducing to {n} would leave {m} filled posts unsanctioned. |
| `sanction_order_ref` | **required when `sanctioned_count` changes** | Record the sanction order reference. |

**Cross-field.** `group` consistent with `pay_level` per CRR Rule 4 — A ⇒ level ≥ 10, B ⇒ 6–9,
C ⇒ 1–5. A mismatch is a warning with an override that requires a remark, because Schedule-1 has
academic-level exceptions (Librarian at Academic 14).

## 6. Authorisation

`DesignationPolicy`, `EstablishmentPolicy` — **university-wide**. Read for all staff. Mutation for
`recruitment_admin` and `super_admin` only, always audited. **Dean's-office users have read access
only** — sanctioned strength is vested in the Executive Council (CRR Rule 8), not in a faculty.

## 7. UI

Designation list: code · name · cadre · group · pay level · selection method · ruleset · status.
Filters on cadre, group and ruleset. The editor shows the essential and desirable qualifications
side by side with their **`.citation`** treatment, so the author can see the clause the rule comes
from while editing.

**Establishment matrix:** organisational units down, designations across, `sanctioned_count` in the
cell, with filled and available beneath. It should read like a register page.

## 8. Worked example

**Designation `ASST-PROF`** — cadre `teaching`, no group, Academic Level 10, `rule_set_id` →
`ugc-teaching-2018`, `selection_method: interview_only`, `age_reference:
application_closing_date`, `max_age: NULL` (UGC 2018 states no age limit).

**Designation `SYS-MGR`** — cadre `non_teaching`, group **A**, Level 12, `max_age: 50`,
`selection_method: written_skill_interview`, `rule_set_id` → `ugc-crr-non-teaching-2022`,
`method_of_recruitment: {direct: 100, note: "DR failing which deputation"}`.

**Establishment.** Department of English (`/1/11/56/`) is sanctioned **3** × `ASST-PROF`. Two are
filled. `AvailableVacancies` returns **1**.

- An advertisement for **2** vacancies is **refused**: *"Only 1 vacancy is available against a
  sanctioned strength of 3 (2 filled)."*
- Computer Centre is sanctioned **1** × `SYS-MGR`. Creating that post **by promotion** is refused by
  `AssertRule343`: *"Only one post is sanctioned in this cadre, so it must be filled by direct
  recruitment (CRR Rule 34.3)."*

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M35-R01 | Given a fresh seed, when designations are counted, then every post in AMU CRR Schedule-1 is present, including the JNMC Hospital, Nursing, Trauma Centre and Dental College cadres |
| M35-R13 | Given a designation seeded from Schedule-1, when inspected, then its Selection Committee composition is recorded for M19 |
| M35-R02 | Given a non-teaching designation without a group, when saved, then validation fails |
| M35-R03 | Given a teaching designation with a group, when saved, then validation fails |
| M35-R04 | Given a ruleset that does not govern the cadre, when linked, then validation fails |
| M35-R05 | Given sanctioned 3, filled 2, when advertising 2 vacancies, then it is refused with the counts stated |
| M35-R06 | Given sanctioned 1, when creating a post by promotion, then `AssertRule343` refuses, **citing Rule 34.3** |
| M35-R07 | Given a `sanctioned_count` change, when no sanction order reference is given, then validation fails |
| M35-R08 | Given filled 2, when reducing sanctioned strength to 1, then it is refused |
| M35-R09 | Given any designation, when `age_reference` is read, then it is `application_closing_date` |
| M35-R10 | Given an age check, when computed, then it uses `posts.reg_end_date`, **never today's date** |
| M35-R11 | Given a Dean's-office user, when editing sanctioned strength, then **403** |
| M35-R12 | Given a designation change, when committed, then an audit entry records before and after |

## 10. Test cases

`tests/Feature/Admin/Establishment/DesignationSeedTest` — R01, R13 · `DesignationValidationTest` — R02–R04,
R07 · `SanctionedStrengthTest` — R05, R08 · `Rule343Test` — R06 · `AgeReferenceTest` — R09, R10 ·
`Authz/EstablishmentScopeTest` — R11 · `AuditTest` — R12.

Fixtures: `DesignationFactory` with `teaching()` and `nonTeaching()` states;
`EstablishmentFactory` seeding a unit with a known sanctioned/filled split.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01 | `database/seeders/DesignationSeeder` |
| R02–R04, R07, R08 | `App\Http\Requests\Establishment\*` |
| R05 | `App\Domain\Establishment\AvailableVacancies` |
| R06 | `App\Domain\Establishment\AssertRule343` |
| R09, R10 | `App\Domain\Establishment\AgeCalculator` |
| R11 | `App\Policies\EstablishmentPolicy` |
