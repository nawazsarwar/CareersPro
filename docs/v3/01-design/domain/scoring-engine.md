# Scoring Engine

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Supersedes:** `docs/spec/scoring-engine.md` — 22 lines which requires *"No hard-coded logic … rules
defined in data … managed by admins"* and then hard-codes point values in prose four lines later;
asserts idempotence with no mechanism; and depends on a `rule_sets` table that exists in neither
database.

---

## 1. Why this is polymorphic, not parameterised

The obvious design is one Table-2 calculator with configurable numbers. **It is wrong, and we know it
is wrong because we have the next regulation in hand.**

| | UGC 2018 (active) | UGC 2025 (draft) |
|---|---|---|
| Mechanism | Weighted points across 6 categories, 33 sub-rows | **No score at all** |
| Thresholds | 75 / 120 / 110 | **None** |
| Determination | Arithmetic | **Committee judgement** on the recommendation of 3 external subject experts |
| Publication test | Count, scored per faculty column | Count against a minimum, **substitutable** across publications / chapters / books / **granted patents** |
| Contributions | — | **Notable in ≥4 of 9 named areas** |

Verified by exhaustive search of the 2025 draft: **zero** occurrences of *research score*, *academic
score*, *API*, *appendix*, *table 2*, *table 3A*, *table 3B*.

**A parameterised Table-2 calculator would need a rewrite, not a data change, to support 2025.** So:

```
interface ScoringStrategy {
    public function score(ApplicationSnapshot $s, RuleSetVersion $v): ScoreRun;
}

WeightedPointsStrategy   → ugc-teaching-2018        (Table 2 arithmetic)
ThresholdCountStrategy   → ugc-teaching-2025        (count against minima, then dossier)
NonTeachingTestStrategy  → ugc-crr-non-teaching-2022 (Paper I + II + skill + interview)
NullStrategy             → cadres with no scoring (Assistant Professor, all Librarian, all DPES)
```

The strategy is **selected by the frozen `rule_set_version`**, never by a runtime flag.

---

## 2. The five invariants

| # | Invariant | Mechanism |
|---|---|---|
| **I1** | **Frozen ruleset.** A ruleset version is resolved at *advertisement publish* and never re-resolved | `advertisements.rule_set_version_id`, copied to `applications` at submit, read-only after |
| **I2** | **Immutable input.** Scoring runs against an `application_snapshot`, never the live profile | `score_runs.snapshot_id`; snapshots are append-only |
| **I3** | **Determinism.** Same snapshot + same ruleset ⇒ byte-identical output | `input_hash = H(snapshot.content_hash ‖ ruleset.content_hash)`; `output_hash` over canonical JSON. Re-running asserts equality |
| **I4** | **Explainability.** Every point traces to a rule id and its citation | `score_lines.rule_id` + `.citation`, both `NOT NULL` |
| **I5** | **Refuse, never guess.** An unratified ambiguity halts the computation | `PendingRatificationError`. See §4 |

**I3 is guaranteed by construction, not by assertion** — the old spec asserted "byte-identical" with
no mechanism. Both inputs are immutable and content-addressed, so identical inputs cannot produce
different outputs unless the code changed, which `rule_set_version` pins.

---

## 3. `WeightedPointsStrategy` — UGC 2018

### 3.1 Pipeline

```
snapshot
  └─ collect research_claims (category 1–6)
       └─ FILTER  evidence_document_id IS NOT NULL      ← no evidence, no score
            └─ MAP each claim → base points  (faculty column I or II)
                 └─ APPLY impact-factor augmentation    (category 1 only) ⚠ blocked
                      └─ APPLY apportionment            (authorship / PI / supervision)
                           └─ SUM per category
                                └─ APPLY cap 5(b)+6 ≤ 30% of total   ⚠ blocked
                                     └─ ASSERT ≥3 of 6 categories    ⚠ blocked
                                          └─ total
```

### 3.2 Worked example — Associate Professor, threshold 75

Faculty of Arts → **Column II** (10 points per paper).

| Claim | Rule | Raw | Apportionment | Points |
|---|---|---|---|---|
| 5 sole-authored papers, peer-reviewed | `T2-1` | 5 × 10 | 1.00 | **50** |
| 1 paper, 3 authors, candidate is corresponding | `T2-1` + `T2-AUTH` | 10 | **0.70** | **7** |
| 1 paper, 3 authors, candidate is joint author | `T2-1` + `T2-AUTH` | 10 | **0.30** | **3** |
| 1 book, national publisher | `T2-2a` | 10 | 1.00 | **10** |
| 1 completed project, ₹8 lakh, **Co-PI** | `T2-4b` + `T2-PI` | 5 | **0.50** | **2.5** |
| 2 PhDs awarded, sole supervisor | `T2-4a` | 2 × 10 | 1.00 | **20** |
| | | | **Total** | **92.5** |

**Categories represented: 1, 2, 4** → three of six → floor satisfied. No 5(b) or 6 claims → cap not
engaged. **92.5 ≥ 75 → eligible.**

Now change one input: had `T2-PI` been encoded as the fabricated **PI 1.0 / Co-PI 0.5** — as
`ugc-rules.yaml` had it — a *Principal* Investigator on that project would score 5 instead of 2.5.
Across a Professor applicant's project portfolio that is the difference between clearing 120 and not.
**That is why REG-01 exists.**

### 3.3 Output

```json
{
  "score_run_id": 4471, "strategy": "weighted_points",
  "rule_set_version": "ugc-teaching-2018@1",
  "snapshot_hash": "a3f9…", "input_hash": "7c21…", "output_hash": "e8b4…",
  "total": 92.5,
  "lines": [
    { "rule_id": "T2-1", "citation": "App. II Table 2 row 1", "claim_id": 8821,
      "raw_value": 10, "apportionment_factor": 0.70, "points": 7,
      "explanation": "Research paper, Column II (10). Three authors; candidate is corresponding author → 70%." },
    { "rule_id": "T2-4b", "citation": "App. II Table 2 row 4(b)", "claim_id": 8834,
      "raw_value": 5, "apportionment_factor": 0.50, "points": 2.5,
      "explanation": "Completed project below ₹10 lakh (5). Co-Investigator → 50%." }
  ],
  "categories_represented": [1, 2, 4],
  "floor_satisfied": true, "cap_engaged": false
}
```

**This JSON is what the candidate sees.** Itemised rationale is the single loudest complaint against
CU-Chayan (*"candidates are rarely provided itemized rationale"*), and it is nearly free once every
line carries its rule and citation.

---

## 4. `PendingRatificationError` — the refusal path

**Six Table 2 ambiguities and two CRR ambiguities are unresolved (OQ-008, OQ-009).** Each carries
`pending_ratification: true`.

```php
if ($rule->pendingRatification) {
    throw new PendingRatificationError($rule->id, $rule->ambiguity, $rule->owner);
}
```

**No score is produced. No `recommended` value is applied.** A recommended value becomes usable only
when an Executive Council decision sets `pending_ratification: false` and records `ratified_by` and
`ratified_on`.

**Blocked today:** anything touching the **impact-factor augmentation** (T2-AMB-01 through 04), the
**30% cap** (T2-AMB-06), **joint supervision** (T2-AMB-05), and the **non-teaching merit arithmetic**
(CRR-AMB-01/02).

**Not blocked:** base per-paper points, books, projects, patents, awards, guidance, and the whole of
Tables 3A/3B. The example in §3.2 computes because it engages no blocked rule.

**Why refuse rather than default.** T2-AMB-01 — whether the impact-factor value *replaces* or is
*added to* the base — is a **160–200 point swing** for a Professor applicant with 20 papers, against
a 120-point threshold. A default here is not a convenience; it decides careers and would be
indefensible in court. The UI surfaces *"scoring blocked pending Executive Council ratification of
[rule]"*, which is the honest state.

---

## 5. `NonTeachingTestStrategy` — CRR 2022

| Stage | Marks | Qualifying | Additive |
|---|---:|---|---|
| Paper I (objective) | 100 | ≥40% | yes |
| Paper II (descriptive) | 100 | ≥50%, **evaluated only if Paper I qualified** | yes |
| Skill test | 50 | ≥25 | **NO — qualifying only** |
| Interview | 20% of total | — | yes |

**The skill test is qualifying and never additive.** A common and expensive error.

**Blocked by CRR-AMB-01/02:** whether the interview applies at all to Group B/C (Rule 11 III(g) vs
Rule 22.8), and whether the composite total is 240 or 100. Until Legal rules, the strategy computes
Papers I and II and **refuses the final merit arithmetic**.

---

## 6. `MeritStrategy` — and the invariant that must never break

`MeritStrategy` is a **separate object from `ScoringStrategy`**, bound to the designation and
versioned.

| Cadre | Merit source |
|---|---|
| **Teaching** | **Interview performance alone** |
| Non-teaching | Paper I + Paper II + interview (20%), subject to qualifying the skill test |

> **UGC 2018 cl. 4.1 I Note:** the Table 3A/3B score is *"for **short-listing of the candidates for
> interview only**, and the selections shall be based **only on the performance in the interview**."*

```php
final class TeachingMeritStrategy implements MeritStrategy {
    public function rank(array $inputs): MeritList {
        if (isset($inputs['shortlisting_score'])) {
            throw new StatutoryViolation(
                'UGC 2018 cl. 4.1 I Note: shortlisting score must not enter a teaching merit list'
            );
        }
        // ...
    }
}
```

**It throws rather than ignoring.** A silent drop would let a caller believe the score was
considered. This is REG-08.

---

## 7. Admin authoring and the sandbox

**Authoring (M20).** Administrators edit rules **as data**, but three things are enforced:

1. **No value without a `citation`.** Rejected at validation.
2. **A new version is created, never an edit in place.** Existing versions are immutable.
3. **`second_reader_verified` gates activation.** A version cannot go `active` while false — the
   control that would have caught `ugc-rules.yaml`.

**Sandbox.** Run a candidate ruleset version against **historical snapshots** and diff. Answers
*"if we ratify T2-AMB-01 as additive, who changes eligibility?"* — with real names and real deltas —
before the decision is taken. Sandbox runs are marked `is_sandbox` and never written to an
application.

---

## 8. Test strategy

| Test | Asserts |
|---|---|
| **Golden corpus** | ~30 real profiles with hand-computed expected totals, each citing the clause it exercises. **The test that would have caught the fabricated catalogue** |
| REG-01…REG-08 | The 8 regression tests in `rules-catalogue.yaml` |
| Determinism | Same snapshot + ruleset scored twice ⇒ identical `output_hash` |
| Refusal | Any blocked rule ⇒ `PendingRatificationError`, **no partial score emitted** |
| Statutory | `TeachingMeritStrategy` throws on a shortlisting-score input |
| Evidence | A claim without `evidence_document_id` contributes **0** |
| Boundary | 74 / 75 / 76 against the Associate Professor threshold; 119 / 120 / 121 against Professor |
| Apportionment | Two authors → 0.70 **each** (140% total — intentional, confirm don't "fix"); >2 → 0.70 / 0.30; PI → 0.50 / 0.50 |
| Column | Same claim scores 8 in Column I and **10** in Column II |

**Coverage gate: 100% on `app/Domain/Scoring`.** Today a coverage driver is not even installed
(`docs/research/test-coverage.txt` reads `WARN No code coverage driver available`, `Tests: 4 passed`).

---

## 9. Traceability

| Section | Feeds |
|---|---|
| §1 | M20 · `../regulatory/ugc-teaching-2025-draft.md` |
| §2 | M20 · M27 RTI reconstruction · `snapshot-and-audit.md` |
| §3 | M20 · M06 claim capture |
| §4 | decision register OQ-008, OQ-009 |
| §5 | M21 · M22 |
| §6 | M21 · M14 |
| §7 | M20 admin UI |
| §8 | `../../02-plan/M20-scoring-engine.md` |

---

## 10. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Polymorphic strategies per ruleset (justified by the 2025 draft abolishing the Research Score). Five invariants with mechanisms. `PendingRatificationError` refusal path. `MeritStrategy` separation with a throwing guard on the teaching invariant. | Implementation team |
