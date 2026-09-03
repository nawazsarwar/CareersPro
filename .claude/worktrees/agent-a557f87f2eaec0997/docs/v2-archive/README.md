# v2 Archive — superseded planning artefacts

**Archived:** 2026-08-27 · **Superseded by:** [`../v3/`](../v3/)

**Nothing here is a live source. Do not build from any of it.**

These are the v2 planning artefacts. They are retained for one reason: **`docs/v3/` cites them as
evidence** — the correction table in `v3/00-clarify/decision-register.md` §5 and the regression tests
in `v3/01-design/regulatory/rules-catalogue.yaml` both point at specific defects in specific files
here. Deleting them would leave v3 asserting defects whose evidence no longer exists.

---

## What is here, and what it is evidence of

| File | Cited in v3 as |
|---|---|
| `research/ugc-rules.yaml` | **The fabricated ruleset.** `pi: 1.0, co_pi: 0.5` against a Gazette that says **50% each**; a flat `base_marks: 8` ignoring Column II's 10; **no Table 3A at all**. This file is why **REG-01…REG-06** exist |
| `UGC_TEACHING_RECRUITMENT_REGULATIONS.md` | Four more transcription errors — the missing *"IF less than 1"* band with every band shifted down one, merged completed/ongoing projects, the 30% cap applied to category 6 alone, and a Professor committee chaired by *"the VC or their nominee"* when the Gazette permits no nominee |
| `spec/domain-model.md` | States `Advertisement N:1 Post`. The real relationship is **1:N** |
| `spec/api.md` | Specifies four `/api/v2` endpoints and a CU-Chayan push integration, **none of which exist or ever did** |
| `spec/security.md` | Mandates hash-chained audit logs and PII encryption against a schema that has neither |
| `spec/scoring-engine.md` | Requires *"no hard-coded logic"* and then hard-codes point values four lines later |
| `spec/test-strategy.md` | Demands 100% coverage on a `ScoringEngine` class that does not exist |
| `spec/state-machine.md`, `srs.md`, `roadmap.md`, `migration-plan.md`, `ui-ux.md` | The rest of the 219-line specification set |
| `traceability.csv` | 29 rows citing `MODULES.md §5.x` (there is no §5) and `SRS-0xx` (the SRS uses a different scheme), with `TODO` for every code artefact and test |
| `PROGRESS.md` | Reports *"Mapped Requirements: 29, Unmapped: 0"* and *"Implemented Auth Module with TOTP stubs"* — both false |
| `MODULES.md` | The 29-module catalogue, superseded by `v3/00-clarify/scope-boundary.md` (M01–M35) |
| `MEMORY.md`, `open-questions.md` | Superseded by the decision register and glossary |
| `research/codebase-audit.md` | 17 lines, half of it a paste of `composer.json`, with *"N+1 queries: To be determined"* |
| `research/pain-points.md` | **3 lines, 2 bullets**, for a 78,000-application system |
| `research/compliance-matrix.md` | Covers OWASP, WCAG and GIGW only — no reservation, RTI, DPDP or retention content |
| `research/comparative-field-analysis.md` | Superseded by the regulatory transcriptions in `v3/01-design/regulatory/` |
| `login-tailwind.png` | The delivered v2 login screen — an unstyled card **with no submit button rendered**. Sits beside the desired design at `../images/media_1787424976961.png` |

---

## Also removed in the same pass, and not archived

**13 byte-identical duplicates** (md5-verified) — five `admin_*.png` aliases of the `media_*.png`
screenshots, two extra copies of the sign-in design, and five mis-named files that were raw
`pdftotext` dumps rather than the synthesised documents their names claimed:
`regulatory-baseline.md` (= `ugc_regulations_2018.txt`), the two `as-is-journey-map-*.md`
(= the vendor manuals), `ugc_model_non_teaching.md` (= the `.txt`), and the two
`as-is-field-inventory-*.md` (= `FN1.txt` and `F3GeneralNT.txt`).

**4 dead working files** — `php_files.txt`, `migrations_list.txt` and `routes.txt` all describe v2
code that Wave 0 deletes, and `adv_officers_1.txt` was a failed extraction containing only a binary
signature blob.

All are recoverable from git history at `ec24d33` and earlier.

---

## One v2 document was *not* archived

**[`../adr/001-database-selection.md`](../adr/001-database-selection.md) stays in `docs/`.** It is
the one sound artefact of the v2 planning round — it states a real question, weighs three real
options and gives three defensible reasons. It is **adopted unchanged as DR-003**.
