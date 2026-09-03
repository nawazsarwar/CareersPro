# DOC-001 — AMU Ordinances (Executive): what they resolved

**Status:** obtained and analysed · **Date:** 2026-08-27
**Source:** `docs/AMU_Ordinances_Executive.pdf` — *Aligarh Muslim University, Ordinances (Executive),
amended up to July 2025*, 3.6 MB, obtained from `api.amu.ac.in`
**Extraction:** `docs/research/amu_ordinances_executive.txt` (716,659 characters)

> **Note on the download.** `api.amu.ac.in` presents a valid GlobalSign OV certificate for
> `*.amu.ac.in` but **does not send its intermediate certificate**, so the chain cannot be built and
> standard clients refuse the connection. It was fetched by supplying the GlobalSign RSA OV SSL CA
> 2018 intermediate explicitly, with verification intact — not by disabling it. **This is a server
> misconfiguration worth reporting to the AMU API team**; it will break other clients too.

---

## 1. Headline: the Ordinances adopt UGC 2018 verbatim

**Chapter IV A — Minimum Qualifications for Appointment of Teachers and Other Academic Staff for
Direct Recruitment** (pages 98–140), adopted by **E.C. Res. No. 3 dated 14.03.2019**
(ref. O.M.D. No. (C)/1112 dated 22.01.2019), amended by **E.C. Res. No. 5(28) dated 14/15.12.2024**.

It reproduces UGC 2018 as the University's operative instrument, with AMU's own appendix numbering:

| AMU | UGC |
|---|---|
| **Appendix-I** | Table 3A — criteria for short-listing |
| **Appendix-II** | Table 2 — research score methodology |
| **Appendix-III** | Table 1 — assessment criteria and methodology |

**§1.3 states the frozen-ruleset principle in AMU's own words:**

> *"These ordinances shall be applicable for posts of Teaching and other Academic staff **advertised
> after** that date of notification… The posts **already advertised before 18.07.2018 will be
> governed by the qualifications as advertised earlier**."*

That is scoring-engine invariant **I1**, stated by the University itself. The design was right.

**§1.1 scope:** applies to teaching and academic staff *"**other than school teachers**"* — so
DOC-005 remains genuinely open.

---

## 2. Confirmed — three independent sources now agree

Chapter IV A's Appendix-II matches the Gazette transcription and FN-1 **exactly**. Every regression
test in `rules-catalogue.yaml` is now confirmed against a third source:

| Rule | Ordinances say | Confirms |
|---|---|---|
| Research papers | **08** per paper (Col I) · **10** per paper (Col II) | REG-05 |
| Impact factor | **6 bands**: no IF 5 · **<1 → 10** · 1–2 → 15 · 2–5 → 20 · 5–10 → 25 · >10 → 30 | **REG-02** |
| Two authors | **70% of total value for each author** | REG-01 |
| More than two | 70% first/principal/corresponding · **30% each** joint | REG-01 |
| **Joint projects** | *"Principal investigator and Co-investigator would get **50% each**"* | **REG-01** |
| Projects | Completed **10 / 05** · **Ongoing 05 / 02** | **REG-03** |
| Joint supervision | *"70% … Supervisor and Co-supervisor, both shall get **7 marks each**"* | T2-AMB-05 |
| Cap | *"combined … 5(b) Policy Document **and** 6 … upper capping of **thirty percent**"* | **REG-04** |
| Floor | *"minimum of **three categories out of six**"* | T2-FLOOR-01 |

**The fabricated `ugc-rules.yaml` value (PI 1.00 / Co-PI 0.50) is now disproven three ways over.**

---

## 3. Resolved — the AMU faculty column mapping

**`faculty_columns.amu_override` moves from `pending_document` to active.** Chapter IV A, Appendix-II
header (as amended 14/15.12.2024):

| Column | AMU faculties |
|---|---|
| **I** — 08 per paper | Sciences · Life Sciences · Engineering · Agriculture · **Medicine** · **Unani Medicine** |
| **II** — 10 per paper | **International Studies** · Law · Arts · Social Sciences · Commerce · Management · **Theology** |

**FN-1's gloss was genuine AMU text, not a transcription error.** And the list is exactly **13
faculties**, matching the **13 `Faculty`-typed organisational units** in Data Lake — an independent
cross-validation of both.

> ⚠️ **New gap (OQ-017).** AMU's mapping names only its 13 faculties. UGC's Column II explicitly
> includes **Library, Education and Physical Education**; AMU's does not list them. **Which column
> applies to the Librarian and DPES cadres is undetermined**, and it changes their per-paper score
> from 8 to 10.

---

## 4. Resolved — AMU's own amendments to Table 3A

Appendix-I reproduces UGC Table 3A with identical scores, **plus one AMU extension** (E.C. Res.
No. 5(28), 14/15.12.2024):

- **Row 3 widened** from *"M.Phil."* to **"M.Phil / LLM / M.Tech / M.Arch / M.E. / M.V.Sc. / M.D. etc."**
  — a materially broader qualifying set than the Gazette's, and it must be encoded as an AMU override.
- **Note (A)(ii): *"JRF/NET/SET (U.P. state) Maximum 07 Marks"*** — confirming FN-1's *"(UP states)"*
  gloss is genuine AMU wording. **T3-UND-04 resolved.**
- **Note (D)** uses the intelligible phrasing — *"SLET/SET score shall be valid…"* — confirming the
  recommendation to adopt Table 3B's wording for both. **T3-UND-03 resolved.**
- **Note (B):** *"Number of candidate to be called for interview shall be decided by the concerned
  universities."* **AMU has not fixed a teaching shortlisting ratio** — see OQ-018.

---

## 5. New rules found — Chapter V, Selection Committee procedure

Chapter V (pages 279–280) supplies procedure the design did not have. **All of it lands in M19.**

| # | Rule | Effect |
|---|---|---|
| 1 | *"The Registrar shall issue such notice **not less than ten days before** each of the meeting, with the prior consent of the Visitor nominee or the experts"* | **A third SLA clock**, and a validation rule on committee scheduling |
| 2 | The Committee recommends to the **Executive Council** under Statute 29 | Confirms the CRR Rule 6 chain |
| 3 | The Committee *"may in fit cases recommend … **waiving of probationary period, grant of advance increment(s)**, and may also indicate the **order of preference**"* | Three new recommendation fields on the merit list (M14, M19) |
| 4 | *"The Chairman shall be entitled to vote … and shall have and exercise a **casting vote in the case of a tie**"* | **This is AMU's tie-break rule.** It supersedes the provisional "higher qualification score, then earlier submission" default in M21/M14 |
| 5 | *"The Chairman shall have the power to lay down the procedure in respect of any matter not mentioned in these Ordinances"* | A recorded discretion |
| 6 | **Recusal — a 16-relation list** (E.C. Res. No. 12 dated 14.07.2007): father, mother, son/daughter, son/daughter-in-law, grandparents, grandchildren and their spouses, siblings, siblings-in-law, spouse's father, spouse's sister, niece/nephew of either spouse, **first cousin** of either spouse, uncle/aunt — **each including step-relations** | **A hard validation rule in M19, far broader than "must not have applied to this post."** Requires a declared-relationship check on every committee member against every applicant |

---

## 6. Not resolved — and now precisely located

| Gap | Where it actually lives |
|---|---|
| **Selection Committee composition for Registrar, Finance Officer, Controller of Examinations** | **The AMU Statutes**, not the Ordinances. Chapter XI §5 says appointment is *"by the duly constituted selection committee **as provided in the Statutes** for the post of Registrar/Controller of Examinations"*; Chapter III covers only emoluments and service conditions, under Statutes 2, 4, 5, 5A and 6. Chapter V cites **Statute 27** and **Statute 29** |
| **School-teacher recruitment** (DOC-005) | Not in the Ordinances. Chapter IV A §1.1 **excludes school teachers**; Chapter XI covers only the Directorate's management structure and names **8 schools** |
| **Non-teaching cadre qualifications** (DOC-004) | Chapter XL is service conditions — probation, pay, conduct, leave. It carries almost no recruitment content and **no cadre schedule** |
| **Reservation, roster, age relaxation, EWS, Ex-Servicemen** (DOC-003) | **Entirely absent.** Zero occurrences of *roster*, *age relaxation*, *EWS* or *Ex-Serviceman* across all 716,659 characters |
| **The six Table 2 ambiguities** (OQ-009) | **The Ordinances reproduce UGC's ambiguous wording verbatim and add no interpretation.** AMU has adopted the text without resolving it, so ratification is still required — see §7 |
| **CGPA conversion** (OQ-010) | *"equivalent grade"* appears 10 times; **no conversion formula anywhere**. Zero occurrences of "CGPA" in Chapter IV A |
| **Part B2 / B3 contents** (DOC-006) | Not in the Ordinances |

---

## 7. The decisive negative finding

**AMU's Ordinances do not resolve OQ-009.** They reproduce UGC 2018's wording word for word,
including every ambiguous phrase:

- *"The Research score for research papers would be **augmented** as follows"* — still silent on
  replace-vs-add (**T2-AMB-01**).
- *"between 1 and 2 / between 2 and 5 / between 5 and 10 / >10"* — the boundaries still overlap and
  IF exactly 10 still falls in no band (**T2-AMB-02**).
- *"as per **Thomson Reuters** list"* — still no edition specified (**T2-AMB-03**).
- *"UGC-listed"* — still undefined against the CARE timeline; **"UGC-CARE" appears nowhere in the
  Ordinances** (**T2-AMB-04**).
- *"both shall get 7 marks each"* against a base of 10 (**T2-AMB-05**).
- The circular 30% cap and the three-of-six floor (**T2-AMB-06**).

**This is a useful answer, not a disappointing one.** It establishes that the interpretation is
**AMU's to make**, that no existing instrument has made it, and that the Executive Council is the
body to do so. The engine's `refuse` posture is therefore correct and not over-cautious.

---

## 8. Register updates

| Item | Change |
|---|---|
| **DOC-001** | **Closed.** Obtained, analysed, findings recorded here |
| `faculty_columns.amu_override` | `pending_document` → **active**, per §3 |
| T3-UND-03, T3-UND-04 | **Closed**, per §4 |
| M21/M14 tie-break | Provisional default **superseded** by the Chairman's casting vote (§5.4) |
| M19 recusal | Extended from *"has not applied"* to the **16-relation list** (§5.6) |
| **OQ-009** | **Remains open.** Owner unchanged: Executive Council |
| **OQ-017** *(new)* | Which Table 2 column applies to Librarian and DPES cadres? |
| **OQ-018** *(new)* | Teaching shortlisting ratio — AMU has not fixed one (Note B) |
| **DOC-008** *(new)* | **AMU Statutes** — committee composition for Registrar/FO/COE; Statutes 27 and 29 |
| DOC-003, DOC-004, DOC-005, DOC-006 | **Remain open**, now with their absence positively confirmed |

---

## 9. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. DOC-001 obtained and analysed. 9 Table 2 values confirmed against a third source; AMU faculty columns and two Table 3A notes resolved; 6 new committee rules found; 7 gaps precisely located; OQ-009 confirmed still open. | Implementation team |
