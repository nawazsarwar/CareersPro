# M04 — Editable Academic & Work History

**Wave:** 4 · **Scope:** v1
**Depends on:** M03, M24
**Blocked by:** **OQ-010** *(percentage ↔ CGPA conversion policy — capture proceeds; the conversion
rule is applied by M20.)*

## 1. Purpose and statutory basis

The reusable dossier: qualifications, employment, institutions attended, eligibility tests, experience
and referees. Entered once, reused for every application — WCAG 2.2 **3.3.7 Redundant entry**.

**Every field here exists because a regulation or a legacy form requires it.** The inventory is
`../01-design/regulatory/ugc-teaching-2018.md` §1 and the FN-1 / F-3 field lists.

Two statutory rules shape the model directly:

- **UGC 2018 cl. 3.11** — time spent acquiring M.Phil/PhD **does not count** as experience; active
  service pursuing a research degree **while teaching without any leave** **does**.
- **UGC 2018 cl. 3.3** — NET/SET exemption turns on the PhD being compliant with the **M.Phil./PhD
  Regulations 2009 or 2016**. This is the most-used eligibility pathway in the system.

## 2. Data

`academic_qualifications` · `eligibility_tests` · `employment_histories` · `institutions_attended` ·
`teaching_research_experiences` · `foreign_visits` · `referees` · `other_details` — full schema in
`../01-design/domain/domain-model.md` §6.

**Three fields that carry disproportionate weight:**

| Field | Why |
|---|---|
| `ncrf_level` | Nullable under UGC 2018, **required under the 2025 draft**. Added now because retrofitting means re-opening submitted applications |
| `is_phd_regulations_compliant` enum(`2009`,`2016`,`2022`,`none`) | The NET-exemption gateway. Modelled as an enum so DOC-002's answer applies without a schema change |
| `phd_registration_date` / `submission_date` / `award_date` | Makes cl. 3.11 **computable** rather than self-declared |

**Fixed from the legacy schema:** `institutions_attended.year_of_leaving` is **not unique** — the
scaffolding marked it `->unique()`, which makes it impossible for two candidates to leave in the
same year.

## 3. Domain services

```
App\Domain\Dossier\ComputeExperience::for(User, CarbonInterface $asAt): ExperienceBreakdown
App\Domain\Dossier\NetExemption::assess(User): ExemptionResult
App\Domain\Dossier\NormalisePercentage::from(Qualification): ?float
App\Domain\Dossier\AssertDossierUnlocked::check(User, string $section): void
```

**Invariants.**
- `ComputeExperience` **subtracts** M.Phil/PhD acquisition periods (cl. 3.11) unless the row is
  flagged *simultaneous teaching without leave* **and** carries a service certificate.
- `NormalisePercentage` returns **null** rather than guessing when only a CGPA is present and no
  conversion is declared — OQ-010 is open, and a guessed conversion is a wrong eligibility decision.
- **A section belonging to a submitted application is locked.** Editing is possible only through a
  deficiency window (M18), which writes a new snapshot.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/dossier/{section}` | `dossier.index` | `auth`, `verified` | `DossierPolicy@view` |
| POST | `/dossier/{section}` | `dossier.store` | as above | `@create` |
| PATCH/DELETE | `/dossier/{section}/{id}` | `dossier.update`, `.destroy` | as above | `@update`, `@delete` |

`{section}` ∈ qualifications · eligibility-tests · employment · institutions · experience ·
foreign-visits · referees · other-details.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `qualification_level_id` | required, exists | |
| `course` | required, max:191 | |
| `year_of_passing` | required, integer, `between:1950,{{current year}}` | Enter a valid year of passing. |
| `percentage` | `required_without:cgpa`, numeric, between:0,100 | Enter the percentage or the CGPA. |
| `cgpa` | `required_without:percentage`, numeric, `lte:cgpa_scale` | The CGPA cannot exceed the scale. |
| `cgpa_scale` | `required_with:cgpa`, numeric, in:4,5,7,10 | |
| `conversion_declaration` | **required_with:cgpa** — the awarding university's formula + proof | Attach the conversion formula from the awarding university. |
| `ncrf_level` | nullable, numeric, in:5.5,6,6.5,7,8 | |
| `is_phd_regulations_compliant` | **required when the qualification is a PhD**, in:2009,2016,2022,none | State which PhD Regulations your degree complies with. |
| `phd_award_date` | `required_if:…is_phd`, date, **after:`phd_registration_date`** | The award date must follow registration. |
| `from` / `to` (employment) | required / nullable, date, **`to` after `from`** | The end date must follow the start date. |
| | **no overlap with another full-time row** | This overlaps your employment at {employer}. |
| `is_permanent` | required, boolean | |
| `basic_pay`, `gross_pay` | nullable, numeric, min:0, **`gross_pay ≥ basic_pay`** | Gross pay cannot be below basic pay. |
| `pay_level` | nullable, exists:pay_levels | |
| referee `email`, `mobile` | required, email / `regex:/^[6-9]\d{9}$/` | |
| | **a referee may not be the applicant** | You cannot list yourself as a referee. |

**Cross-field, and each catches a real error class:** at least **two** referees (FN-1 item 23) ·
qualifications must be chronologically consistent (a PG award date cannot precede a UG award date) ·
`simultaneous_teaching_no_leave` requires a service certificate document.

## 6. Authorisation

`DossierPolicy` — **ownership scope only**. `$user->id === $row->user_id` for every ability. Staff
reach a candidate's dossier through `ApplicationPolicy` at scrutiny, never through this policy.

`AssertDossierUnlocked` runs before every mutation: a row referenced by a submitted application's
snapshot is immutable.

## 7. UI

The **spine** (`../01-design/ux/design-system.md` §4.2): 11 Part-A sections, completion state shown,
**no sequential gating**. Repeating rows are ruled records with inline add and edit.

**Computed values are labelled as computed, with their reference date** —
*"Total experience 6 years 2 months, as at 7 Mar 2026."* An officer must never have to work out
which date a figure was calculated against.

## 8. Worked example

Dr Farooqui enters: Master's 2009, 58%; PhD registered 2010-08, submitted 2014-01, awarded 2014-11,
`is_phd_regulations_compliant: 2009`; Assistant Professor at another university 2015-01 → present.

- `NetExemption::assess` → **exempt**, because the PhD is 2009-compliant and registration postdates
  11 July 2009, so the five pre-2009 conditions do not apply. The result cites cl. 3.3 I.
- `ComputeExperience` as at 2026-03-07 → **11 years 2 months**. Nothing is subtracted: the PhD was
  awarded in 2014, before the employment began, so no overlap exists.
- Had he registered for the PhD in 2016 **while employed**, the acquisition period would be
  subtracted **unless** he flagged simultaneous teaching without leave and attached a service
  certificate — in which case it counts, per cl. 3.11.
- He also holds a Master's recorded only as **CGPA 6.28 / 10** with no conversion declaration.
  `NormalisePercentage` returns **null**, and the wizard blocks submission with:
  *"Attach the conversion formula from Biju Patnaik University of Technology, or enter the
  percentage."* It does not assume 62.8%.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M04-R01 | Given a PhD qualification, when saved without `is_phd_regulations_compliant`, then validation fails |
| M04-R02 | Given a 2009-compliant PhD registered after 11 July 2009, when assessed, then NET exemption applies, citing cl. 3.3 I |
| M04-R03 | Given a PhD registered before 11 July 2009, when assessed, then **all five** cl. 3.3 conditions are required |
| M04-R04 | Given a PhD acquired during employment, when experience is computed, then the acquisition period is **subtracted** |
| M04-R05 | Given the same, flagged simultaneous-without-leave **with** a service certificate, then it **counts** |
| M04-R06 | Given the same flag **without** a certificate, when saved, then validation fails |
| M04-R07 | Given a CGPA with no conversion declaration, when normalised, then the result is **null** — not a guess |
| M04-R08 | Given overlapping full-time employment rows, when saved, then validation fails naming the other employer |
| M04-R09 | Given candidate A, when editing candidate B's qualification, then **403** |
| M04-R10 | Given a row referenced by a submitted snapshot, when edited, then it is refused |
| M04-R11 | Given fewer than two referees, when submitting, then it is refused |
| M04-R12 | Given a PG award date before the UG award date, when saved, then validation fails |
| M04-R13 | Given two candidates leaving an institution in the same year, when both save, then both succeed |

## 10. Test cases

`tests/Feature/Dossier/QualificationValidationTest` — R01, R07, R12 ·
`NetExemptionTest` — R02, R03 · `ExperienceComputationTest` — R04–R06 ·
`EmploymentOverlapTest` — R08 · `Authz/DossierOwnershipTest` — R09 ·
`DossierLockTest` — R10 · `RefereeTest` — R11 · `InstitutionUniquenessTest` — **R13, the legacy bug** ·

Fixtures: `QualificationFactory` with `phd()`, `masters()`, `cgpaOnly()` states.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R07, R11–R13 | `App\Http\Requests\Dossier\*` |
| R02, R03 | `App\Domain\Dossier\NetExemption` |
| R04–R06 | `App\Domain\Dossier\ComputeExperience` |
| R08 | `App\Rules\NoEmploymentOverlap` |
| R09 | `App\Policies\DossierPolicy` |
| R10 | `App\Domain\Dossier\AssertDossierUnlocked` |
