# CareersPro v2 — Documentation Set

**This directory supersedes `docs/v2-archive/spec/`, `docs/v2-archive/MODULES.md`, `docs/v2-archive/traceability.csv`,
`docs/v2-archive/open-questions.md` and `docs/v2-archive/PROGRESS.md`.** Those files are retained for the audit trail.
**Do not build from them** — see `00-clarify/decision-register.md` §5 for the twelve corrections
that make several of them actively wrong.

---

## The rule

Work proceeds **Clarify → Design → Plan → Code → Verify**. Each phase's documents are complete and
signed off before the next begins.

**Before writing code for any module:**

1. Open its build spec, `02-plan/M{NN}-{slug}.md`.
2. Check its **Depends on** line against `00-clarify/decision-register.md`.
3. Every listed decision must read `DECIDED`. If any reads `OPEN` or `PROPOSED`, **stop and
   escalate** — do not pick the option that looks obvious.

A `PROPOSED` decision is a recommendation awaiting sign-off, not permission to proceed.

---

## Contents

### `00-clarify/` — decisions recorded, not assumed ✅ complete

| File | What it holds |
|---|---|
| [`decision-register.md`](00-clarify/decision-register.md) | **12 decisions, all decided**; 9 open questions (none blocking Design); 7 documents to obtain; 12 binding corrections; **§6 Data Lake schema review** |
| [`scope-boundary.md`](00-clarify/scope-boundary.md) | The canonical module catalogue `M01`–`M35` with in/out/deferred scope and reasons |
| [`stakeholder-map.md`](00-clarify/stakeholder-map.md) | 22 actors; every open question and standing policy assigned an accountable owner |
| [`glossary.md`](00-clarify/glossary.md) | ~60 terms with statutory sources; 10 naming conventions |
| [`data-hygiene-backlog.md`](00-clarify/data-hygiene-backlog.md) | Defects in **source data**, not in this project — what the import detects and reports rather than silently repairing |

**The load-bearing decisions:** DR-002 clean-slate rebuild · DR-003 MySQL + JSON (ADR-001) ·
DR-006 versioned rules engine, UGC 2018 active and 2025 authored-but-inactive · DR-008
dual-identifier login · **DR-009 organisational units local and autonomous — no runtime dependency
on Data Lake** · DR-010 General/Local appointment nature and the second row-level authorisation
scope · DR-011 nothing deleted electronically, hard copies weeded at five years · **DR-012 the
`designations` master and the sanctioned-strength register**.

### `01-design/regulatory/` — the compliance spine ✅ complete

| File | What it holds |
|---|---|
| [`ugc-teaching-2018.md`](01-design/regulatory/ugc-teaching-2018.md) | **The active teaching ruleset.** 11 cadres, 5 discipline variants, the NET-exemption gateway, both 5% relaxations, Appendix II **Table 2** in full (33 sub-rows × 2 faculty columns, 6 IF bands, apportionment verbatim) and **Tables 3A/3B** in full. 6 ambiguities and 4 source defects recorded **unresolved** |
| [`ugc-crr-non-teaching-2022.md`](01-design/regulatory/ugc-crr-non-teaching-2022.md) | **The active non-teaching ruleset.** All **58 cadres** with group, pay level, method and age; Paper I → Paper II → skill test → interview; the 1:15 screening ratio; committees and quorum; 14 cross-cutting obligations |
| [`ugc-teaching-2025-draft.md`](01-design/regulatory/ugc-teaching-2025-draft.md) | **Draft, not notified.** 15-row delta against 2018. **Confirms the Research Score and Tables 2/3A/3B are abolished** and replaced by committee determination over 9 "notable contribution" areas |
| [`reservation-and-relaxation.md`](01-design/regulatory/reservation-and-relaxation.md) | Records that **neither instrument carries substantive reservation rules**; specifies the versioned policy plug-in and the candidate-side capture that proceeds without it |
| **[`rules-catalogue.yaml`](01-design/regulatory/rules-catalogue.yaml)** | **The machine-readable ruleset.** Every value carries a `citation`. Ambiguous rules carry `pending_ratification: true` and **the engine refuses to score rather than guessing**. 8 regression tests defined from the 5 known errors |

### `01-design/domain/` — the corrected model ✅ complete

| File | What it holds |
|---|---|
| [`domain-model.md`](01-design/domain/domain-model.md) | The full ER model. Corrects `Advertisement 1:N Post`; establishes the **`Designation → Post → OrganisationalUnit`** spine, the sanctioned-strength register, three independent eligibility gates, immutable snapshots, versioned rulesets, the whole payment domain |
| [`state-machine.md`](01-design/domain/state-machine.md) | **Four orthogonal dimensions, not a linear chain.** 13 transitions with actor, guard and side-effect; real quorum figures; the two statutory SLA clocks |
| [`scoring-engine.md`](01-design/domain/scoring-engine.md) | **Polymorphic strategies per ruleset** — justified by the 2025 draft abolishing the Research Score. Five invariants with mechanisms; the `PendingRatificationError` refusal path |
| [`snapshot-and-audit.md`](01-design/domain/snapshot-and-audit.md) | Append-only snapshots, canonical serialisation, the **genuinely hash-chained** audit log, and the reconstruct-and-verify procedure |
| [`organisational-units.md`](01-design/domain/organisational-units.md) | Local, autonomous, provider-fed. Seven improvements over the source; the materialised `path`; detect-and-report import |

### `01-design/security/` ✅ complete

[`security-model.md`](01-design/security/security-model.md) — **two orthogonal authorisation scopes**
with a full role × resource × action matrix, 11 roles with author/verifier separation of duties, and
the 9 verified defects it closes. [`data-protection.md`](01-design/security/data-protection.md) —
**argues** the DR-011 retention position rather than assuming it; six-class data classification; DPDP
rights including an explicit, disclosed refusal of erasure.

### `01-design/ux/` ✅ complete

[`design-system.md`](01-design/ux/design-system.md) — the *Register* direction, ruled records over
cards, full light/dark tokens with verified contrast, WCAG 2.2 AA + GIGW.
[`data-table.md`](01-design/ux/data-table.md) — **the largest single UI work item**, which no previous
document mentioned. [`screens.md`](01-design/ux/screens.md) — all 12 reference screens plus the
candidate journey, and what is deliberately not copied.

### `02-plan/` — one build spec per module ✅ complete

**36 specs, 441 requirement IDs.** Every spec carries the same eleven sections, none empty: purpose &
statutory basis · data · domain services · routes & controllers · validation · authorisation · UI ·
**worked example** · acceptance criteria · **test cases** · traceability ID. Index, dependency graph
and wave plan: [`02-plan/README.md`](02-plan/README.md).

M12 (computer-based test delivery) is the single module deferred to v2, with its reason recorded.

---

## Standard for every document here

Set by the project sponsor: rigorous enough that *"even if these finalized documents are given to a
junior dev, they don't have scope to mess"*. Concretely, every specification carries:

- every rule with its **statutory citation** — regulation, clause, and line reference;
- every field with its **type, constraints and validation**, including the exact error message;
- every state transition with its **actor, guard and side-effect**;
- every acceptance criterion with a **worked example using real values**;
- the **test case** that proves it, named by file and method.

---

## Two rules that exist because they were broken before

**Statutory values are transcribed, never recalled.** No number enters `rules-catalogue.yaml`
without a clause reference, and every number is verified against the source PDF by a second reader.
The previous ruleset stated that a Principal Investigator scores 100% and a Co-Investigator 50%; the
Gazette says **50% each**. That single error would have made every Associate Professor and Professor
determination wrong, and wrong in a direction a rejected candidate can challenge in court.

**Traceability is generated, never asserted.** The previous matrix reported *"Mapped Requirements:
29, Unmapped: 0"* while all 29 rows pointed at document sections that do not exist and carried
`TODO` for both code and test. The new matrix is derived from the specs, and CI fails if any
requirement ID lacks a code artefact and a test.
