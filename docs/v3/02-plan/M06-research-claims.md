# M06 — Publication & Research Claims

**Wave:** 4 · **Scope:** v1
**Depends on:** M04, M07
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Capture every score-bearing claim, with its evidence.

**UGC 2018 Appendix II Table 2 header, verbatim** — and this is a design requirement, not prose:

> *"Assessment must be based on evidence produced by the teacher such as: copy of publications,
> project sanction letter, utilization and completion certificates issued by the University and
> acknowledgements for patent filing and approval letters, students' Ph.D. award letter, etc."*

**A claim without evidence scores zero.** Not "is flagged" — scores zero.

**The model must serve two regimes.** UGC 2018 scores structured publication metadata across 6
categories and 33 sub-rows. The **2025 draft abolishes scoring entirely** and asks the committee to
judge *notable contributions* in ≥4 of 9 named areas, many of them institutional rather than
bibliometric — startups, community engagement, Indian Knowledge System, indigenous games, disability
services. **A claim model built only for Table 2 would not survive the transition.**

## 2. Data

```
research_claims
  id · user_id
  category enum(journal_paper, book, book_chapter, book_editor, translation,
                ict_pedagogy, mooc, econtent, research_guidance,
                project_completed, project_ongoing, consultancy,
                patent, policy_document, award, invited_lecture)
  title · year · detail json
  evidence_document_id → documents        -- MANDATORY
  verified_at · verified_by_id · verification_remark
  soft deletes

notable_contributions                     -- UGC 2025 cl. 3.8 / 3.9 / 3.10
  id · user_id · cadre · area_number (1..9) · area_label
  narrative text · evidence_document_id
  assessed_as_notable bool NULL · assessed_by_committee_id NULL
```

`detail` is the **only** JSON blob in the candidate model, and it is justified: 16 categories with
genuinely different shapes. ADR-001 sanctions exactly this. Per-category shapes are declared in
`App\Domain\Claims\Schemas` and validated against, so it is **structured JSON, not a bag**.

**Indexes:** `research_claims(user_id, category)` · a generated column on
`detail->>'$.doi'` with a unique index per user, for duplicate detection.

## 3. Domain services

```
App\Domain\Claims\ClaimSchema::for(ClaimCategory): array
App\Domain\Claims\LookupDoi::handle(string $doi): ?PublicationMetadata   // CrossRef
App\Domain\Claims\VerifyUgcListing::handle(string $issn, int $year): ListingResult
App\Domain\Claims\ResolveFacultyColumn::for(User, Post): FacultyColumn   // I or II
```

**Invariants.**
- A claim cannot be created without `evidence_document_id`.
- **DOI lookup pre-fills but never overwrites** a value the candidate has already entered — it is an
  assistant, not an authority.
- `VerifyUgcListing` returns `unresolved` where the applicable list is ambiguous (**T2-AMB-04**:
  UGC-CARE replaced the Approved List in 2019 and was discontinued in 2024) and **never guesses**.
- `ResolveFacultyColumn` uses the **Gazette** mapping; AMU's divergent mapping is a **pending
  override** until DOC-001 arrives.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/dossier/claims` | `claims.index` | `auth`, `verified` | `ClaimPolicy@viewAny` |
| GET/POST | `/dossier/claims/{category}` | `claims.create`, `.store` | as above | `@create` |
| PATCH/DELETE | `/dossier/claims/{claim}` | `claims.update`, `.destroy` | as above | `@update`, `@delete` |
| POST | `/dossier/claims/lookup-doi` | `claims.lookup` | as above, `throttle:30,1` | `@create` |
| POST | `/dossier/claims/import` | `claims.import` | as above, `throttle:5,60` | `@create` |
| GET/POST | `/dossier/contributions` | `contributions.*` | as above | `ContributionPolicy@*` |

## 5. Validation

Per category, from `ClaimSchema`. Journal paper, the highest-volume case:

| Field | Rules | Message |
|---|---|---|
| `title` | required, max:500 | |
| `year` | required, integer, `between:1960,{{current year}}` | |
| `detail.journal` | required, max:300 | |
| `detail.issn` | nullable, `regex:/^\d{4}-\d{3}[\dXx]$/` | Enter an ISSN as 1234-567X. |
| `detail.doi` | nullable, `regex:/^10\.\d{4,9}\/\S+$/`, **unique per user** | You have already claimed this DOI. |
| `detail.is_peer_reviewed` | required, boolean | |
| `detail.is_ugc_listed` | required, boolean | |
| `detail.impact_factor` | nullable, numeric, between:0,100 | |
| `detail.impact_factor_source_year` | **required_with:impact_factor**, integer | State which JCR edition this impact factor comes from. |
| `detail.coauthor_count` | required, integer, min:0 | |
| `detail.authorship_role` | required, in:sole,first,corresponding,principal,joint | |
| `evidence_document_id` | **required**, exists, **owned by the user** | Attach the publication as evidence. |

Other categories, the load-bearing rules: **project** — `grant_amount` required, `pi_role` in
`pi,co_pi` (the 50/50 split hangs on it) · **patent** — `granted` boolean, `scope` in
`international,national`; **an ungranted patent is not claimable** under the 2025 draft's
*"granted patents"* wording · **research guidance** — `degree_awarded` vs `thesis_submitted` are
**different point values** (10 vs 5) and must be distinguished · **invited lecture** — `scope` in
`international_abroad,international_within_country,national,state_or_university`.

**Cross-field.** `authorship_role: sole` requires `coauthor_count = 0`. A paper claimed as both a
journal paper and a conference proceeding is rejected — Table 2 says it *"can be claimed only once."*

## 6. Authorisation

`ClaimPolicy`, `ContributionPolicy` — **ownership scope only**. `verified_at` and `verified_by_id`
are writable **only** by a scrutiny officer through M18, never by the candidate. A candidate who
could self-verify could self-score.

## 7. UI

One ruled section per category, with counts. **DOI lookup is the headline affordance:** paste a DOI,
fields populate, the candidate confirms. CU-Chayan's documented worst data-entry complaint is
entering 20+ publications by hand with no import and no verification.

**A live Table 2 preview** shows the candidate their provisional score as they enter claims, with
per-line citations — subject to §8's caveat.

`notable_contributions` renders as 9 named areas per cadre with a narrative field and an evidence
slot, marked *"assessed by the selection committee"* so nobody expects a number.

## 8. Worked example

Dr Farooqui, Faculty of Arts → **Column II** (10 points per paper).

He pastes `10.1080/00223980.2019.1667321`. CrossRef returns the journal, volume, pages and 4
authors. He confirms, sets `authorship_role: corresponding`, attaches the PDF.

The live preview shows:

> Research paper, Column II (10). Four authors; corresponding author → **70%** = **7.0**
> <span class="citation">App. II Table 2 row 1 · apportionment :8585-8594</span>

He then enters `impact_factor: 2.4`. The preview does **not** add an impact-factor score. It says:

> **Impact-factor scoring is blocked** pending Executive Council ratification of whether the
> impact-factor value replaces or supplements the base score (T2-AMB-01), and of the band
> boundaries (T2-AMB-02). Your claim is recorded in full.

That is the honest state, and it is `PendingRatificationError` surfaced rather than swallowed.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M06-R01 | Given a claim without evidence, when saved, then validation fails |
| M06-R02 | Given a claim without evidence, when scored, then it contributes **0** |
| M06-R03 | Given a DOI already claimed by the user, when re-entered, then validation fails |
| M06-R04 | Given a DOI, when looked up, then fields pre-fill and **do not overwrite** existing entries |
| M06-R05 | Given a paper claimed as both journal paper and conference proceeding, when saved, then it is refused |
| M06-R06 | Given `authorship_role: sole` with `coauthor_count > 0`, when saved, then validation fails |
| M06-R07 | Given an impact factor without a source year, when saved, then validation fails |
| M06-R08 | Given a Column II candidate, when a paper is scored, then it is worth **10**, not 8 |
| M06-R09 | Given a corresponding author of 4, when apportioned, then the factor is **0.70** |
| M06-R10 | Given a Co-PI project, when apportioned, then the factor is **0.50** — not 0.50 against a PI 1.00 |
| M06-R11 | Given an ungranted patent, when claimed, then it is refused |
| M06-R12 | Given a candidate, when attempting to set `verified_at`, then it is refused |
| M06-R13 | Given an ambiguous UGC listing year, when verified, then the result is `unresolved`, not a guess |
| M06-R14 | Given a notable contribution, when saved, then it carries a narrative **and** evidence |

## 10. Test cases

`tests/Feature/Frontend/Claims/EvidenceRequiredTest` — R01, R02 · `DoiTest` — R03, R04 ·
`DoubleCountTest` — R05 · `AuthorshipValidationTest` — R06, R07 ·
`tests/Unit/Scoring/FacultyColumnTest` — R08 · `ApportionmentTest` — **R09, R10 (REG-01)** ·
`PatentTest` — R11 · `Authz/ClaimVerificationTest` — R12 · `UgcListingTest` — R13 ·
`ContributionTest` — R14.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R03, R05–R07, R11, R14 | `App\Domain\Claims\ClaimSchema`, `App\Http\Requests\Claims\*` |
| R02, R08–R10 | `App\Domain\Scoring\WeightedPointsStrategy` (M20) |
| R04 | `App\Domain\Claims\LookupDoi` |
| R12 | `App\Policies\ClaimPolicy` |
| R13 | `App\Domain\Claims\VerifyUgcListing` |
