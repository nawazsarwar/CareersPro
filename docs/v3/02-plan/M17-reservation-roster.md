# M17 — Reservation & Roster Engine

**Wave:** 3 · **Scope:** **v1-partial** — the mechanism ships; the policy data lands with DOC-003
**Depends on:** M24, M35, M16
**Blocked by:** **DOC-003** *(the reservation instruments)* · **OQ-013** *(applicability at AMU)*

## 1. Purpose and statutory basis

Category-wise vacancy determination and the roster register.

**Read `../01-design/regulatory/reservation-and-relaxation.md` first.** Its finding governs this
module: **neither the UGC Regulations 2018 nor the Model CRR 2022 contains substantive reservation
rules.** Both incorporate Government of India instructions by reference and stop.

| What the instruments give us | What they do not |
|---|---|
| UGC 2018 cl. 6.0 III — committee representation, *"norms of the Central Government"* | percentages · roster type · roster unit · backlog · carry-forward · interchange |
| CRR Rules 15.1, 31, 32 — GoI instructions apply *mutatis mutandis* | **EWS** (defined at Rule 2.19 and never used again) · **Ex-Servicemen** (relaxation cell **literally blank**) · PwBD percentages · fee concessions |
| UGC 2018 cl. 3.4 I, 3.5 — **the two 5% qualification relaxations** | age relaxation of any kind |

**So we build the mechanism and leave the policy as data.** That is not a deferral of difficulty; it
is the correct architecture, because **AMU's minority-institution status and the applicability of
reservation is live constitutional litigation** (*AMU v. Naresh Agarwal*, 7-judge Bench, November
2024, remitted). A hard-coded roster would be a liability.

## 2. Data

```
reservation_policies         id · slug · title · applies_to json
reservation_policy_versions  id · policy_id · version · status enum(draft, active, superseded)
                             effective_from · effective_to
                             vertical json          -- UR/SC/ST/OBC-NCL/EWS + citation each
                             horizontal json        -- PwBD/ESM/Women + citation each
                             age_relaxations json · fee_concessions json
                             qualification_relaxations json
                             roster_params json     -- type, unit, backlog, carry-forward
                             content_hash · second_reader_verified · verified_by · verified_at

roster_registers  id · organisational_unit_id · designation_id
                  reservation_policy_version_id · roster_type · total_points
roster_points     id · roster_register_id · point_number · category
                  horizontal_category NULL · is_reserved
                  status enum(vacant, filled, carried_forward)
                  filled_by_post_id NULL · filled_by_application_id NULL · sanction_ref
```

**`vertical` and `horizontal` are separate keys** because horizontal reservation (PwBD, ESM) cuts
*across* the vertical categories. Modelling horizontal as more vertical categories is the classic
roster defect and it produces wrong counts in every direction.

**Ships with no `active` policy version.** The tables exist and are empty.

## 3. Domain services

```
App\Domain\Reservation\ResolvePolicy::for(Advertisement): ?ReservationPolicyVersion
App\Domain\Reservation\ComputeVacancyBreakup::for(Post): VacancyBreakup
App\Domain\Reservation\ApplyAgeRelaxation::for(Candidate, Designation, Policy): int
App\Domain\Reservation\ApplyQualificationRelaxation::for(Candidate, Rule): float
App\Domain\Reservation\RosterRegister::allocate(Post): RosterPoint
```

**Invariants.**
- The policy version is **frozen at advertisement publish**, like the ruleset (I1). An advertisement
  published under the 2026 policy is evaluated under it for ever, whatever the litigation does next.
- **With no active policy version, `ComputeVacancyBreakup` returns null** and the advertisement
  builder falls back to **administrator-entered** category-wise vacancies. It does **not** guess.
- `ApplyQualificationRelaxation` implements the two UGC 2018 relaxations, which **are** available
  today — and **refuses to stack them to 45%** (cl. 3.4 I and cl. 3.5 are separate grounds).
- Grace marks are excluded from every relaxation computation (cl. 3.4 I, verbatim).

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/reservation/policies` | `admin.reservation.policies.index` | `ReservationPolicyPolicy@viewAny` |
| GET/POST/PATCH | `/admin/reservation/policies/{v?}` | `admin.reservation.policies.*` | `@*` |
| POST | `/admin/reservation/policies/{v}/activate` | `admin.reservation.policies.activate` | `@activate` |
| GET | `/admin/roster` | `admin.roster.index` | `RosterPolicy@viewAny` |
| GET | `/admin/roster/{register}` | `admin.roster.show` | `RosterPolicy@view` |
| GET | `/admin/roster/{register}/export` | `admin.roster.export` | `RosterPolicy@view` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `vertical[].category` | required, exists:categories | |
| `vertical[].percent` | required, numeric, between:0,100 · **the set must sum to ≤ 100** | Vertical reservation cannot exceed 100%. |
| `vertical[].citation` | **required, min:10** | Every percentage needs a citation to a sourced instrument. |
| `horizontal[].percent` | required, numeric, between:0,100 — **not summed with vertical** | |
| `age_relaxations[].years` | required, integer, between:0,20 · **citation required** | |
| `roster_params.type` | required, in:100_point,200_point,13_point | |
| `roster_params.unit` | required, in:institution,department | **DOC-003 row 1 decides this.** |
| `effective_from` | required, date · **must not overlap an existing active version** | Another policy version is already active from {date}. |

**The citation rule is the point.** No percentage, no age relaxation and no fee concession may be
saved without a citation to a sourced instrument. The customary figures — SC/ST +5, OBC +3,
PwBD +10 — are exactly the class of value that produced the fabricated `ugc-rules.yaml`.

## 6. Authorisation

`ReservationPolicyPolicy` — authoring by `rules_admin`; **activation by `rules_verifier` only, and
the verifier must differ from the author** (separation of duties,
`../01-design/security/security-model.md` §3.1). `RosterPolicy` — read for `recruitment_admin` and
`auditor`; **Dean's-office read within their subtree**, no mutation.

## 7. UI

Policy versions listed with status, effective dates and verification state. The editor shows
vertical and horizontal in **separate panels** — the visual separation reinforces the modelling one.
Every numeric field has a **required citation field beside it**, and the form cannot be saved with a
citation blank.

Roster register renders as a **numbered point grid**, which is what a roster physically is:
point number, category, reserved/unreserved, status, and what filled it.

**When no policy is active** — the state at ship — the roster screen says so plainly and links to
DOC-003 and OQ-013, rather than rendering an empty grid that implies zero reservation.

## 8. Worked example

**Today, with no active policy.**

A recruitment admin creates a post with 3 vacancies. `ComputeVacancyBreakup` returns `null`, so the
builder shows an **administrator-entered** category breakdown: UR 1, OBC-NCL 1, SC 1, entered by
hand and recorded as manually entered. The roster register is not created. The advertisement
publishes with `reservation_policy_version_id = NULL`, and that null is itself frozen — the record
shows there was no policy, rather than implying one.

**What already works.** A candidate with a Master's at **51%**, SC category, applying for Assistant
Professor: `ApplyQualificationRelaxation` applies **TCH-REL-01** (5% at Master's for SC) →
threshold 55% becomes **50%** → **51% ≥ 50% → eligible**, with the line citing cl. 3.4 I.

The same candidate at **46%**: TCH-REL-01 gives 50%. TCH-REL-02 (the pre-1991 PhD relaxation) is a
**separate ground**, and stacking is refused — the engine returns *not eligible*, citing that the
Regulations do not authorise a 45% floor.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M17-R01 | Given no active policy version, when a post is created, then vacancy breakup is administrator-entered and recorded as such |
| M17-R02 | Given no active policy version, when the roster screen renders, then it states the position and links to DOC-003 |
| M17-R03 | Given a policy percentage without a citation, when saved, then validation fails |
| M17-R04 | Given publication, when it completes, then `reservation_policy_version_id` is frozen — **including when null** |
| M17-R05 | Given a later policy activation, when a published advertisement is evaluated, then the **frozen** version (or null) applies |
| M17-R06 | Given an SC candidate at 51% at Master's, when eligibility runs, then the 5% relaxation applies and the line cites cl. 3.4 I |
| M17-R07 | Given a candidate attempting to stack both relaxations to 45%, when evaluated, then it is refused |
| M17-R08 | Given grace marks in a qualification, when relaxation is computed, then they are excluded |
| M17-R09 | Given a `rules_admin`, when activating a policy version, then it is refused |
| M17-R10 | Given the same user as author and verifier, when activating, then it is refused |
| M17-R11 | Given vertical percentages summing above 100, when saved, then validation fails |
| M17-R12 | Given horizontal percentages, when validated, then they are **not** summed with vertical |
| M17-R13 | Given two active versions with overlapping effective dates, when saved, then it is refused |

## 10. Test cases

`tests/Feature/Reservation/NoActivePolicyTest` — R01, R02 · `PolicyValidationTest` — R03, R11–R13 ·
`PolicyFreezeTest` — R04, R05 · `QualificationRelaxationTest` — R06–R08 ·
`SeparationOfDutiesTest` — R09, R10.

Fixtures: `ReservationPolicyVersionFactory` with a fully cited stub version used **only** in tests,
never seeded into a running system.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\Reservation\ComputeVacancyBreakup` |
| R03, R11–R13 | `App\Http\Requests\Reservation\StoreReservationPolicyVersionRequest` |
| R04, R05 | `App\Domain\Recruitment\PublishAdvertisement` |
| R06–R08 | `App\Domain\Reservation\ApplyQualificationRelaxation` |
| R09, R10 | `App\Policies\ReservationPolicyPolicy` |
