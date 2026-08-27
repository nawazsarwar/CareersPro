# M17 — Relaxation Engine

*(Renamed from "Reservation & Roster Engine". **DR-017: no posts are reserved at AMU**, so there is
no roster to build.)*

**Wave:** 3 · **Scope:** v1
**Depends on:** DR-017 · M24, M35, M16
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Age, qualification and fee relaxations. **Not a roster.**

**DR-017 settles this module's scope: no posts are reserved by category at AMU.** No roster, no
category-wise vacancy split, no backlog, no carry-forward. Corroborated by the source — across
**1,076,754 characters** of the AMU Cadre Recruitment Rules, `reservation` appears **twice**,
`roster` twice, `EWS` **zero** times and `Ex-Serviceman` **zero** times, and neither *reservation*
occurrence establishes a category reservation.

> **Reservation ≠ relaxation, and conflating them is the trap.** No vacancy is set aside for any
> category. But category-linked **relaxations** — age, qualification, fee — very much apply, and this
> module implements them.

| Source | Gives |
|---|---|
| **Advt. 1/2024/NT** dated 07.11.2024 | The age-relaxation table, verbatim (§2.1) |
| **AMU CRR Rule 14.3** | +5 years for ≥3 years regular service in Govt / statutory / autonomous / university / PSU |
| **AMU CRR Rule 14.4** | Relaxation in age and qualification for SC/ST/OBC/PwBD *"as per Government of India norms as adopted by the University"* |
| **AMU CRR Rule 14.5** | **No age limit for DPC posts** |
| **AMU CRR Rule 2.15** | PwD and **PwBD** as defined in the **RPwD Act 2016** |
| **UGC 2018 cl. 3.4 I, 3.5** | The two 5% qualification relaxations |
| Advertisements | **PwD fee exemption**, on a valid certificate |

Full findings: [`../00-clarify/amu-source-documents-findings.md`](../00-clarify/amu-source-documents-findings.md) §3.

## 2. Data

```
relaxation_policies         id · slug · title
relaxation_policy_versions  id · policy_id · version · status enum(draft, active, superseded)
                            effective_from · effective_to
                            age_relaxations json · qualification_relaxations json
                            fee_exemptions json
                            content_hash · second_reader_verified · verified_by_id · verified_at

applied_relaxations         id · application_id
                            type enum(age, qualification, fee)
                            ground · value_applied · evidence_document_id
                            policy_version_id · citation
                            verified_at · verified_by_id
```

**No `roster_registers`. No `roster_points`. No category split on `post_vacancy_breakup`** — a post
has a vacancy count and nothing more.

**Retained:** the `categories` master keeps SC / ST / OBC-NCL / **EWS** — EWS because candidates may
hold the certificate and RTI reporting may ask, even though it grants nothing here.
`horizontal_categories` keeps PwD, ESM and Women, which is how the relaxations are keyed.
`applications.applied_under_category` is retained **for relaxation and statutory reporting**, never
for allocation.

**Indexes:** `relaxation_policy_versions(policy_id, status)` ·
`applied_relaxations(application_id, type)`.

### 2.1 The seeded policy — transcribed, not assumed

| Ground | Age relaxation |
|---|---|
| Employee of AMU Schools | **no upper age bar** |
| SC / ST | **+5 years** |
| OBC | **+3 years** |
| J&K domicile 01.01.1980 – 31.12.1989 | **+5 years** |
| SC/ST serving as a Government employee | **+10 years** |
| OBC serving as a Government employee | **+8 years** *(employer certificate, as at the advertisement date)* |
| **Persons with Disability** | **+10 years** |
| **Women**, including SC/ST/OBC | **+10 years** |
| Ex-Serviceman | per Govt. of India Rules |
| ≥3 years regular service in Govt / statutory / autonomous / university / PSU | **+5 years** *(CRR Rule 14.3)* |
| DPC posts | **no age limit** *(CRR Rule 14.5)* |

**Qualification:** working candidates of AMU Schools, per AMU Rules · plus **UGC cl. 3.4 I** (5% at
Bachelor's and Master's for SC/ST/OBC-NCL/Differently-abled) and **cl. 3.5** (55% → 50% for PhD
holders whose Master's predates 19.09.1991). **Separate grounds; they do not stack to 45%.**

**Fee:** **PwD only** — full exemption on a valid Certificate of Disability on the prescribed
proforma (Appendix-I) issued by a Medical or Competent Authority under the RPwD Act.

## 3. Domain services

```
App\Domain\Relaxation\ResolvePolicy::for(Advertisement): ?RelaxationPolicyVersion
App\Domain\Relaxation\ApplyAgeRelaxation::for(Candidate, Post, Policy): AgeResult
App\Domain\Relaxation\ApplyQualificationRelaxation::for(Candidate, Rule): float
App\Domain\Relaxation\ResolveFeeExemption::for(Candidate, Post, Policy): FeeResult
```

**Invariants.**

- The policy version is **frozen at advertisement publish**, like the ruleset (I1). An advertisement
  published under the 2026 policy is evaluated under it for ever.
- **Relaxations are not cumulative across grounds.** The engine applies the **single most favourable**
  applicable ground and **records which one and why**, so the candidate can see it.
- **Every relaxation requires evidence** — category certificate, disability certificate, employer
  certificate for the Govt-employee grounds, domicile certificate. **No evidence, no relaxation**;
  the claim is still recorded.
- `ApplyQualificationRelaxation` **refuses to stack** cl. 3.4 I and cl. 3.5 to 45%.
- **Grace marks are excluded** (cl. 3.4 I, verbatim).
- **Age is computed against `posts.reg_end_date`** (CRR Rule 14), never today.
- `ResolveFeeExemption` grants **PwD only**. It is not a general concession mechanism.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/relaxation/policies` | `admin.relaxation.index` | `RelaxationPolicyPolicy@viewAny` |
| GET/POST/PATCH | `/admin/relaxation/policies/{v?}` | `admin.relaxation.*` | `@author` |
| POST | `/admin/relaxation/policies/{v}/verify` | `admin.relaxation.verify` | `@verify` |
| POST | `/admin/relaxation/policies/{v}/activate` | `admin.relaxation.activate` | `@activate` |
| GET | `/admin/applications/{application}/relaxations` | `admin.relaxation.applied` | `ApplicationPolicy@view` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `age_relaxations[].ground` | required, in the declared ground list, unique per policy | |
| `age_relaxations[].years` | required, integer, between:0,20 · **or** `no_upper_bar` | |
| `age_relaxations[].evidence_type` | **required** | Every relaxation must name the evidence that proves it. |
| every entry `.citation` | **required, min:10** | Every relaxation needs a citation to a sourced instrument. |
| `fee_exemptions[].category` | required · **PwD only in the seeded policy** | |
| `qualification_relaxations[]` | percent between 0 and 10 · **stacking flag must be false** | The Regulations do not authorise stacking to 45%. |
| `effective_from` | required, date · **must not overlap an active version** | Another policy version is already active from {date}. |
| activation | **`second_reader_verified = true`**, verifier ≠ author | |

**The citation rule is the point.** SC/ST +5, OBC +3, PwD +10 are exactly the customary figures that
"everyone knows" — and exactly the class of value that produced the fabricated `ugc-rules.yaml`.
**None of them may be saved without a citation.**

## 6. Authorisation

`RelaxationPolicyPolicy` — authoring by `rules_admin`; **verification and activation by
`rules_verifier` only, and the verifier must differ from the author**
(`../01-design/security/security-model.md` §3.1). Applied relaxations are read through
`ApplicationPolicy`, so they respect ownership and OU scope.

## 7. UI

Policy versions listed with status, effective dates and verification state. **Every numeric field has
a required citation field beside it**; the form cannot save with one blank.

On the candidate side, the relaxation is **stated on the eligibility pre-check**, not hidden:

> Upper age limit **40 years**. You have declared **SC** and attached a category certificate →
> **+5 years** applied → effective limit **45**. Your age at the closing date is **44**. **Eligible.**
> <span class="citation">Advt. 1/2024/NT age relaxation · computed at 07.03.2026 per CRR Rule 14</span>

At scrutiny the officer sees the ground, the value applied and the evidence, side by side.

## 8. Worked example

**Post 2599**, upper age limit **40**, closing **07.03.2026**, fee **₹500**.

1. A candidate declares **SC** and attaches a category certificate. `ApplyAgeRelaxation` finds two
   applicable grounds — SC (+5) and, because they hold 4 years' service in a Central Government
   office, CRR Rule 14.3 (+5). **They do not add.** The engine applies the single most favourable
   (both are +5, so SC by declared order) → effective limit **45**, and records the ground.
2. Age at `reg_end_date` 07.03.2026 = **44y 2m** → **eligible**, with the line cited.
3. Had they been a **woman** as well, the +10 ground applies → effective limit **50** — still one
   ground, the most favourable, not 5 + 10.
4. **Fee.** They are not PwD → **₹500 payable**. A PwD candidate uploading a valid Appendix-I
   certificate → **₹0**, recorded as an `applied_relaxations` row of type `fee` with the evidence
   document.
5. Had they claimed SC with **no certificate attached**, `ApplyAgeRelaxation` returns **no
   relaxation** — the claim is recorded, the effective limit stays 40, and they are **not eligible**.
   The pre-check says exactly that, before payment.
6. **No vacancy is reserved for them.** They compete in one open merit list (DR-017).

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M17-R01 | Given the schema, when inspected, then **no roster table exists** |
| M17-R02 | Given a post, when created, then vacancies are a single count with **no category split** |
| M17-R03 | Given an SC candidate aged 44 against a limit of 40, when evaluated, then +5 applies and they are **eligible** |
| M17-R04 | Given two applicable age grounds, when evaluated, then the **single most favourable** applies — never the sum — and the ground is recorded |
| M17-R05 | Given a category claim with no evidence, when evaluated, then **no relaxation** applies and the claim is still recorded |
| M17-R06 | Given a PwD candidate with a valid certificate, when the fee is computed, then it is **₹0** |
| M17-R07 | Given a PwD candidate with no certificate, when the fee is computed, then it is **₹500** |
| M17-R08 | Given a non-PwD candidate of any category, when the fee is computed, then it is **₹500** |
| M17-R09 | Given a policy value without a citation, when saved, then validation fails |
| M17-R10 | Given a candidate attempting to stack cl. 3.4 I and cl. 3.5 to 45%, when evaluated, then it is refused |
| M17-R11 | Given grace marks in a qualification, when relaxation is computed, then they are excluded |
| M17-R12 | Given age evaluation, when computed, then it uses `posts.reg_end_date`, **never today** |
| M17-R13 | Given publication, when it completes, then `relaxation_policy_version_id` is frozen |
| M17-R14 | Given a later policy activation, when a published advertisement is evaluated, then the **frozen** version applies |
| M17-R15 | Given a `rules_admin`, when activating a policy, then it is refused |
| M17-R16 | Given an AMU Schools employee, when age is evaluated, then **no upper age bar** applies |
| M17-R17 | Given a DPC post, when age is evaluated, then **no age limit** applies (CRR Rule 14.5) |

## 10. Test cases

`tests/Architecture/NoRosterTest` — **R01, R02** · `AgeRelaxationTest` — R03, R04, R12, R16, R17 ·
`EvidenceRequiredTest` — R05 · `FeeExemptionTest` — **R06–R08** ·
`PolicyValidationTest` — R09 · `QualificationRelaxationTest` — R10, R11 ·
`PolicyFreezeTest` — R13, R14 · `SeparationOfDutiesTest` — R15.

Fixtures: `RelaxationPolicyVersionFactory` seeded with the **§2.1 table**, each entry carrying its
citation — so a change to the transcribed figure fails a test rather than passing silently.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | schema; `tests/Architecture/NoRosterTest` |
| R03–R05, R12, R16, R17 | `App\Domain\Relaxation\ApplyAgeRelaxation` |
| R06–R08 | `App\Domain\Relaxation\ResolveFeeExemption` → M08 `ComputeFee` |
| R09 | `App\Http\Requests\Relaxation\StoreRelaxationPolicyVersionRequest` |
| R10, R11 | `App\Domain\Relaxation\ApplyQualificationRelaxation` |
| R13, R14 | `App\Domain\Recruitment\PublishAdvertisement` (M16) |
| R15 | `App\Policies\RelaxationPolicyPolicy` |
