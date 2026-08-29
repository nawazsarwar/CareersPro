# M20 — Scoring Engine

**Wave:** 7 · **Scope:** v1
**Depends on:** DR-006, **DR-013**, **DR-014** · M06, M05, M25, M26
**Blocked by:** **OQ-009** *(six Table 2 ambiguities — **DR-013** settles the posture: referred to the
Executive Council, engine refuses meanwhile)* · **DOC-002**
*(DOC-001 **closed** — the Ordinances reproduce UGC's wording verbatim and resolve none of the six.)*
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Compute the UGC Research Score and the shortlisting score, **explainably, reproducibly, and from
versioned rules bound to the advertisement**.

Design: `../01-design/domain/scoring-engine.md`. Rules:
`../01-design/regulatory/rules-catalogue.yaml`.

| Obligation | Source |
|---|---|
| Research Score thresholds — Associate Professor **75**, Professor **120**, Principal **110** | UGC 2018 cl. 4.1 II, III, V |
| Table 2 methodology, apportionment, the 30% cap, the three-of-six floor | UGC 2018 App. II Table 2 |
| Tables 3A/3B **for shortlisting only** — merit from **interview alone** | cl. 4.1 I Note, cl. 5.3 |
| Assessment **based on evidence produced by the teacher** | App. II Table 2 header |
| Point-in-time reconstruction for RTI and service appeals | M27 · CRR Rule 22.4 |

**Why this is not a calculator with configurable numbers.** The UGC 2025 draft **abolishes the
Research Score entirely** — verified: zero occurrences of *research score*, *academic score*, *API*,
*appendix* or *table 2/3A/3B* — and replaces it with committee judgement over nine notable-contribution
areas. A parameterised Table-2 calculator would need a **rewrite**, not a data change.

## 2. Data

```
rule_sets           id · slug · title · applies_to json · design_doc
rule_set_versions   id · rule_set_id · version · status enum(draft, active, superseded)
                    effective_from · effective_to · payload json · content_hash
                    second_reader_verified bool · authored_by_id · verified_by_id · verified_at

score_runs   id · application_id · snapshot_id · rule_set_version_id
             strategy enum(weighted_points, threshold_count, non_teaching_test, null_strategy)
             total decimal(8,2) NULL · status enum(computed, blocked)
             blocked_by_rule NULL · input_hash · output_hash
             is_sandbox bool · computed_at · computed_by_id
score_lines  id · score_run_id · rule_id · citation · claim_id NULL
             raw_value · apportionment_factor · points · explanation
             UNIQUE (score_run_id, rule_id, claim_id)
```

**Indexes:** `score_runs(application_id, computed_at)` · `score_runs(input_hash)` ·
`rule_set_versions(rule_set_id, status)`.

`score_lines.citation` is **`NOT NULL`**. A total without per-line citations is not a valid output.

## 3. Domain services

```
interface ScoringStrategy { public function score(ApplicationSnapshot, RuleSetVersion): ScoreRun; }

App\Domain\Scoring\WeightedPointsStrategy      // ugc-teaching-2018
App\Domain\Scoring\ThresholdCountStrategy      // ugc-teaching-2025
App\Domain\Scoring\NonTeachingTestStrategy     // ugc-crr-non-teaching-2022
App\Domain\Scoring\NullStrategy                // cadres with no scoring

App\Domain\Scoring\ResolveStrategy::for(RuleSetVersion): ScoringStrategy
App\Domain\Scoring\Apportion::for(Claim, RuleSetVersion): float
App\Domain\Scoring\ApplyCaps::to(CategoryTotals, RuleSetVersion): CategoryTotals
App\Domain\Scoring\AssertCategoryFloor::check(CategoryTotals, RuleSetVersion): void
App\Domain\Scoring\Sandbox::run(RuleSetVersion, Collection<Snapshot>): SandboxReport
```

**The five invariants** (`../01-design/domain/scoring-engine.md` §2):

| # | Invariant | Mechanism |
|---|---|---|
| I1 | **Frozen ruleset** | `score_runs.rule_set_version_id` from the application, never the active version |
| I2 | **Immutable input** | `score_runs.snapshot_id`; snapshots are append-only |
| I3 | **Determinism** | `input_hash = H(snapshot.content_hash ‖ ruleset.content_hash)`; re-running asserts `output_hash` equality |
| I4 | **Explainability** | `score_lines.rule_id` and `.citation`, both `NOT NULL` |
| I5 | **Refuse, never guess** | `PendingRatificationError` |

**Plus:** a claim without `evidence_document_id` contributes **0**. And `ResolveStrategy` selects on
the **frozen ruleset**, never on a runtime flag.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| POST | `/admin/scoring/{application}/run` | `admin.scoring.run` | `ScoringPolicy@run` |
| GET | `/admin/scoring/{application}` | `admin.scoring.show` | `@view` |
| GET | `/admin/rulesets` | `admin.rulesets.index` | `RuleSetPolicy@viewAny` |
| GET/POST/PATCH | `/admin/rulesets/{version?}` | `admin.rulesets.*` | `@author` |
| POST | `/admin/rulesets/{version}/verify` | `admin.rulesets.verify` | `@verify` |
| POST | `/admin/rulesets/{version}/activate` | `admin.rulesets.activate` | `@activate` |
| POST | `/admin/rulesets/{version}/sandbox` | `admin.rulesets.sandbox` | `@author` |

## 5. Validation

**Ruleset authoring — three rules that exist because of `ugc-rules.yaml`:**

| Field | Rules | Message |
|---|---|---|
| every rule value | **`citation` required, min:8** | Every value needs a clause citation. |
| `version` | required, unique per rule set · **an existing version may not be edited** | Create a new version; existing versions are immutable. |
| `effective_from` | required, date · **no overlap with another active version** | Another version is already active from {date}. |
| activation | **`second_reader_verified = true`** | This version has not been verified by a second reader. |
| activation | **`verified_by_id ≠ authored_by_id`** | The verifier must be a different person from the author. |
| `payload` | **must validate against the catalogue schema** | |

**Scoring run:** the application must be `submitted` with a snapshot; the snapshot must match the
frozen ruleset's `applies_to`.

## 6. Authorisation

`ScoringPolicy@run` for `scrutiny_officer` and `recruitment_admin`, scoped by OU.
`RuleSetPolicy@author` for `rules_admin`; **`@verify` and `@activate` for `rules_verifier` only, and
never the same user as the author.**

**Separation of duties on the statutory ruleset is what would have stopped `ugc-rules.yaml` reaching
production.**

## 7. UI

**Score view:** per-line table — rule, citation, claim, raw value, apportionment, points — with the
total and the threshold. Where blocked, a clear panel naming the rule and the ambiguity, and **no
partial total for that rule**.

**Ruleset authoring:** every numeric field has a **required citation field beside it**; the form
cannot save with one blank. Version history shows author, verifier and activation, with a diff
between versions.

**Sandbox:** select a candidate version and a cohort of historical snapshots; the report shows who
changes eligibility, by how much, with names. This answers *"if we ratify T2-AMB-01 as additive, what
happens?"* **before** the Executive Council decides.

**Interaction pattern (DR-021).** A sandbox run over a historical cohort is **a queued job**. The
form is a plain `POST`; Alpine polls `GET /admin/rulesets/sandbox/{run}/status` and renders the diff
when ready. **No Livewire.** With JavaScript off, the run still queues and the report is at its own
URL when complete.

## 8. Worked example

Dr Farooqui, Associate Professor, **Faculty of Arts → Column II** (10 per paper), frozen ruleset
`ugc-teaching-2018@1`.

| Claim | Rule | Raw | Factor | Points |
|---|---|---|---|---|
| 5 sole-authored papers | `T2-1` | 5 × 10 | 1.00 | **50** |
| 1 paper, 3 authors, corresponding | `T2-1` + `T2-AUTH` | 10 | **0.70** | **7** |
| 1 paper, 3 authors, joint | `T2-1` + `T2-AUTH` | 10 | **0.30** | **3** |
| 1 book, national publisher | `T2-2a` | 10 | 1.00 | **10** |
| 1 completed project ₹8 lakh, **Co-PI** | `T2-4b` + `T2-PI` | 5 | **0.50** | **2.5** |
| 2 PhDs awarded, sole supervisor | `T2-4a` | 2 × 10 | 1.00 | **20** |
| | | | **Total** | **92.5** |

Categories 1, 2, 4 → three of six → floor satisfied. No 5(b) or 6 claims → cap not engaged.
**92.5 ≥ 75 → eligible.**

**Now the failure this module prevents.** Had `T2-PI` carried the fabricated **PI 1.00 / Co-PI
0.50**, a *Principal* Investigator on that project would score **5** instead of **2.5**. Across a
Professor applicant's portfolio that is the difference between clearing 120 and not — and it is
wrong in a direction a rejected candidate can challenge. **REG-01.**

**And the refusal.** He adds `impact_factor: 2.4`. The run returns `status: blocked`,
`blocked_by_rule: T2-AMB-01`, **no total**:

> Scoring is blocked pending Executive Council ratification of whether the impact-factor value
> replaces or supplements the base score (T2-AMB-01) and of the band boundaries (T2-AMB-02).

A default here is a **160–200 point swing** for a 20-paper Professor applicant against a 120-point
threshold. Guessing is not a convenience; it decides careers.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M20-R01 | Given the same snapshot and ruleset, when scored twice, then `output_hash` is identical |
| M20-R02 | Given a scored application, when the live dossier changes, then re-scoring the same snapshot is **unchanged** |
| M20-R03 | Given a rule with `pending_ratification`, when required, then `PendingRatificationError` is raised and **no partial score is emitted** |
| M20-R04 | Given a `recommended` value on a pending rule, when scored, then it is **not** applied |
| M20-R05 | Given a claim without evidence, when scored, then it contributes **0** |
| M20-R06 | Given a Co-PI project, when apportioned, then the factor is **0.50** — **REG-01** |
| M20-R07 | Given two authors, when apportioned, then **each** receives 0.70 — **REG-01** |
| M20-R08 | Given a Column II candidate, when a paper is scored, then it is **10**, not 8 — **REG-05** |
| M20-R09 | Given the IF bands, when loaded, then there are **six**, including *"less than 1" → 10* — **REG-02** |
| M20-R10 | Given completed and ongoing projects, when scored, then they use **{10, 5}** and **{5, 2}** — **REG-03** |
| M20-R11 | Given the 30% cap, when applied, then it applies to **combined 5(b) + 6** — **REG-04** |
| M20-R12 | Given claims in only two categories, when scored, then the three-of-six floor is evaluated per the ratified reading |
| M20-R13 | Given a version without second-reader verification, when activated, then it is refused — **REG-07** |
| M20-R14 | Given the same user as author and verifier, when activating, then it is refused |
| M20-R15 | Given an existing version, when edited, then it is refused — a new version is required |
| M20-R16 | Given the golden corpus, when scored, then every hand-computed total matches |
| M20-R17 | Given a sandbox run, when it completes, then no application record is written |
| M20-R18 | Given `ugc-teaching-2025`, when the strategy resolves, then it is `ThresholdCountStrategy`, **not** `WeightedPointsStrategy` |
| M20-R19 | Given an Assistant Librarian or DPES candidate, when a paper is scored, then it is **Column II at 10 points** — **DR-014** |
| M20-R20 | Given AMU's faculty mapping, when a Faculty of Theology candidate is scored, then Column II applies; a Faculty of Unani Medicine candidate gets Column I |
| M20-R21 | Given JavaScript disabled, when a sandbox run is submitted, then it queues and the report is reachable at its own URL |

## 10. Test cases

**`tests/Feature/Admin/Scoring/GoldenCorpusTest`** — ~30 real profiles with hand-computed totals, each
citing the clause it exercises. **This is the test that would have caught the fabricated catalogue.**

`DeterminismTest` — R01, R02 · `RefusalTest` — R03, R04 · `EvidenceTest` — R05 ·
`ApportionmentTest` — R06, R07 · `FacultyColumnTest` — R08 · `RuleCatalogueRegressionTest` —
**R09–R11, asserting REG-01…REG-08 directly against the YAML** · `CategoryFloorTest` — R12 ·
`SeparationOfDutiesTest` — R13–R15 · `SandboxTest` — R17 · `StrategyResolutionTest` — R18 ·
`AmuFacultyColumnTest` — **R19, R20** · `NoJavascriptTest` — R21.

**Coverage gate: 100% on `app/Domain/Scoring`.** Today a coverage driver is not even installed.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\Scoring\ScoreRun`, `input_hash`/`output_hash` |
| R03, R04 | `App\Domain\Scoring\PendingRatificationError` |
| R05–R12 | `WeightedPointsStrategy`, `Apportion`, `ApplyCaps`, `AssertCategoryFloor` |
| R13–R15 | `App\Policies\RuleSetPolicy` |
| R16 | `tests/Fixtures/golden-corpus/*.json` |
| R17 | `App\Domain\Scoring\Sandbox` |
| R18 | `App\Domain\Scoring\ResolveStrategy` |
| R19, R20 | `App\Domain\Scoring\WeightedPointsStrategy`, `rules-catalogue.yaml` faculty column mapping |
| R21 | `App\Jobs\RunSandbox`, `resources/views/admin/rulesets/sandbox/*` — DR-021 |
