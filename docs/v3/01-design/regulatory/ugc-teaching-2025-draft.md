# UGC Draft Regulations 2025 — Teaching Cadres (delta against 2018)

**Instrument:** Draft UGC (Minimum Qualifications for Appointment and Promotion of Teachers and
Academic Staff in Universities and Colleges and Measures for the Maintenance of Standards in Higher
Education) Regulations, **2025**
**Notification number:** No. F.1-2/2017(EC/PS) — *"in supersession of the UGC Regulations on Minimum
Qualifications … 2018"*
**Released:** 6 January 2025 · **Consultation closed:** 28 February 2025
**Status: DRAFT. NOT NOTIFIED.** UGC 2018 remains the sole active teaching ruleset (DR-006).

**Source:** `docs/UGC_Draft_Regulations_Teaching_2025.pdf` (ugc.gov.in ref. 3045759), extracted to
`docs/research/ugc_draft_regulations_2025.txt`.

**Status of this document:** live · **Owner:** implementation team · **Created:** 2026-08-27

---

## 0. Why this document exists now

Per DR-006 the 2025 ruleset is **authored now and loaded inactive**, so that:

- if 2025 is notified **as-is**, it applies to **new advertisements** with **no code change**;
- if it is **modified** before notification, we amend this ruleset in place;
- if it is **never notified**, nothing is lost — the ruleset simply stays `status: draft`.

Advertisements already published stay bound to their frozen ruleset for ever. That is the whole
point of versioning (see `../domain/scoring-engine.md`).

> ⚠️ **This is a draft.** Nothing here may be applied to a live advertisement. The catalogue entry
> carries `status: draft, notified: false`, and the engine will refuse to resolve it for any
> advertisement until an explicit activation decision is recorded.

---

## 1. The headline change: the Research Score is abolished

**Verified by exhaustive search of the full draft text:**

| Term | Occurrences in the 2025 draft |
|---|---:|
| `research score` | **0** |
| `academic score` | **0** |
| `API` | **0** |
| `appendix` | **0** |
| `table 2` / `table 3A` / `table 3B` | **0** |
| `selection committee` | **49** |

**The entire Appendix II apparatus — Table 2 (Research Score) and Tables 3A/3B (shortlisting) — does
not exist in the 2025 draft.** There is no points table, no impact-factor band, no authorship
apportionment, no 30% cap, no three-of-six floor, and no numeric threshold of 75 / 120 / 110.

It is replaced by a **qualitative, committee-judged standard**:

> **cl. 3.11:** *"The research publications should be in peer-reviewed journals, and the book chapter
> or book must be published by a recognized academic or professional publisher with a reputation for
> rigorous peer review and quality editing. **Self-published book chapters or books will not be
> considered. The selection committee shall decide** whether the research publications are in a
> peer-reviewed journal, whether the publication of a book/book chapter is by a reputed publisher,
> and whether the contributions are notable, **based on the recommendations of the three external
> subject experts** of the selection committee."*

### 1.1 What this means for the architecture

**This is the strongest possible vindication of DR-006 and of the versioned rules engine.** A system
that hard-coded UGC 2018's Table 2 would need a rewrite — not a data change — to support 2025,
because 2025 does not compute a score at all. It counts, and then it judges.

Three concrete design consequences:

1. **`ScoringStrategy` must be polymorphic per ruleset**, not a single Table-2 calculator with
   configurable numbers. 2018 = `WeightedPointsStrategy`. 2025 = `ThresholdCountStrategy` — count
   qualifying publications against a minimum, then hand a structured dossier to the committee.
2. **The "notable contributions" areas (§3) are a claim-capture requirement, not a scoring one.**
   The candidate declares contributions in ≥4 of 9 named areas; the committee decides whether they
   are *notable*. M06 must capture them as first-class, evidenced claims even though no engine
   assigns them points.
3. **M19 Committee Workspace becomes load-bearing under 2025.** Under 2018 the committee receives a
   computed score; under 2025 it *makes* the determination that decides eligibility. The three
   external subject experts' recommendation is the operative artefact and must be recorded, attributed
   and audited.

---

## 2. Delta table — 2018 vs 2025

| Aspect | UGC 2018 (active) | Draft 2025 | Impact |
|---|---|---|---|
| **Qualification framework** | Bare percentages at "Master's level" | **NCrF levels** — UG 5.5/6, PG 6.5/7, PhD 8 | **Schema change.** Qualifications need an `ncrf_level` field |
| **Research Score** | Appendix II Table 2, 6 categories, 33 sub-rows, thresholds 75/120/110 | **Abolished.** No score anywhere | `ScoringStrategy` must be polymorphic |
| **Shortlisting** | Tables 3A/3B, 8 rows, 100 marks | **Abolished.** No shortlisting table | `MeritStrategy` and shortlisting both change |
| **Publication requirement** | Count only (7 / 10) | **Count, with substitution** — publications *or* book chapters *or* books *or* **granted patents**, combinable to the total | Claim model must allow heterogeneous units toward one total |
| **Notable contributions** | — | **≥4 of 9 named areas**, per cadre (teaching / librarian / DPES) | New claim type in M06 |
| **Asst. Prof. entry** | Master's 55% + NET/SET, or PhD exemption | **Three routes** — see §4.1 | New eligibility branch |
| **M.E./M.Tech direct entry** | Not available | **PG at NCrF Level 7 with 55% — no NET, no PhD** | The widely reported change |
| **Subject-agnostic entry** | Not stated | **cl. 3.2 / 3.3** — the **PhD subject**, or the **NET/SET subject**, governs the discipline of appointment | Eligibility no longer keyed to the UG/PG subject |
| **EWS** | **Absent entirely** from the 2018 Regulations | **cl. 3.4 includes EWS** in the 5% relaxation | Category master must carry EWS regardless — DOC-003 |
| **Relaxation levels** | Bachelor's and Master's | **UG (NCrF 5.5/6) and PG (NCrF 6.5/7)** | Same effect, NCrF vocabulary |
| **Pre-1991 relaxation** | cl. 3.5 — PhD holders, Master's before 19.09.1991 | **cl. 3.7 — unchanged in substance** | No change |
| **Experience exclusion** | cl. 3.11 | **cl. 3.13 — same text**, plus a new **study-leave provision (up to 20% of faculty strength)** | Rule survives; new provision is out of recruitment scope |
| **PhD for promotion** | cl. 3.10, direct recruitment, deferred | **cl. 3.12 — mandatory for promotion** to L12, L13A, L14 | Affects CAS (v2 scope) |
| **Professor experience** | 10 years | **10 years, of which ≥3 at Associate Professor level** | Tighter |
| **Professor supervision** | "evidence of having successfully guided a doctoral candidate" | **Explicit: 1 PhD awarded as sole supervisor, OR 2 as co-supervisor** | Now countable, not judged |
| **Selection committee** | 3 subject experts; quorum 4 incl. 2 external | **3 external subject experts; quorum 5 incl. ≥2 external** | Quorum up by one |
| **Arts/Music/Yoga route** | Separate clauses 4.2–4.4 with their own thresholds | **Unified alternative route** — UG + 5/10/15 years' professional experience + committee determination | Simpler, more discretionary |

---

## 3. Notable contributions — the nine areas per cadre

**cl. 3.8 — teachers** (recruitment and promotion):

1. Innovative Teaching Contribution
2. Research or Teaching Lab Development
3. Consultancy / Sponsored Research funding as **Principal Investigator or Co-Principal Investigator**
4. Teaching contributions in **Indian languages**
5. Teaching-Learning and Research in **Indian Knowledge System**
6. Student Internship / Project Supervision
7. Digital Content Creation for **MOOCs**
8. Community Engagement and Service
9. **Startup** — per the HEI's IP policy, registered with the Registrar of Companies as a founding
   promoter, successfully raising funding through government, angel or venture funds

**cl. 3.9 — Librarian cadre:** innovative information service · user interaction activities ·
leveraging web-based resources · consortium management · MOOC digital content · digital library
management · **services for persons with disabilities** · multilingual collections · **green library
spaces**.

**cl. 3.10 — Director Physical Education & Sports cadre:** inclusion of persons with disabilities in
sport · partnerships with schools/clubs/community organisations · specialised training programmes ·
health and fitness workshops · mentoring students into sports careers · performance-enhancement
strategies for inter-college/inter-university/State/National competitions · **promotion of indigenous
Indian games** · MOOC digital content · organising competitions and coaching camps of at least two
weeks.

> **Design note.** Note how many of these are *institutional* rather than *bibliometric* — startups,
> community engagement, Indian Knowledge System, indigenous games, disability services. The claim
> model in M06 must accommodate narrative claims with evidence, not just structured publication
> metadata. Building M06 for Table 2 alone would not survive the 2025 transition.

---

## 4. Cadre criteria as drafted

### 4.1 Assistant Professor — Academic Level 10 (cl. 4.1 I)

**Three alternative routes.** Any one suffices:

| Route | Requirement |
|---|---|
| **A** | UG (NCrF 6) with **≥75%** **or** PG (NCrF 6.5) with **≥55%**, **and** a **PhD** (NCrF 8) |
| **B** | PG (NCrF 6.5) with **≥55%** **and** **NET** (UGC/CSIR/ICAR etc.) or SLET/SET |
| **C** | **PG at NCrF Level 7** (e.g. **M.E., M.Tech.**) with **≥55%** — **no NET, no PhD** |

**Alternative for Yoga, Music, Performing Arts, Visual Arts, Drama and other traditional Indian art
forms:** UG (NCrF 5.5/6) + **5 years' professional experience**, plus (i) commendable professional
achievement at State or National level with authenticated proof and (ii) adequate knowledge of theory
and ability to teach. **Determined by the selection committee on the recommendation of the three
external subject experts.**

### 4.2 Associate Professor — Academic Level 13A (cl. 4.1 II)

- UG (NCrF 6) **≥75%** or PG (NCrF 6.5/7) **≥55%**, **and a PhD** (NCrF 8)
- **8 years** teaching and/or research in a University/College, or equivalent position in a research
  institution or comparable Indian/foreign institution. Research experience counted per cl. 3.13
- **8 research publications** in peer-reviewed journals **or** 8 book chapters **or** 1 book as
  author **or** 2 books as co-author **or** 8 **granted patents** — *"A combination … totalling eight
  can be considered"*
- **Notable contributions in at least four of the nine areas** at cl. 3.8

**Alternative arts route:** UG + **10 years'** professional experience, committee-determined.

### 4.3 Professor — Academic Level 14 (cl. 4.1 III)

- UG (NCrF 6) **≥75%** or PG (NCrF 6.5/7) **≥55%**, **and a PhD** (NCrF 8)
- **10 years** teaching and/or research at Assistant/Associate Professor level, **of which at least
  three years at Associate Professor level or equivalent**
- **10 research publications** **or** 10 book chapters **or** **4 books as author** **or** 8 books as
  co-author **or** 10 granted patents — combinable to ten
- **Supervision:** *"As a sole supervisor, one doctoral candidate was awarded a Ph.D. degree, or as a
  co-supervisor, two doctoral candidates were awarded Ph.D. degrees."*
- **Notable contributions in at least four of the nine areas** at cl. 3.8

**Alternative arts route:** UG + **15 years'** professional experience, with achievement at
**National or International** level.

### 4.4 Other cadres

Assistant Librarian (cl. 4.1 IV) and Assistant Director of Physical Education & Sports follow the
same NCrF-based pattern with discipline-specific degrees; eligibility criteria for those cadres also
appear at cl. 5.4 and 5.5. **Transcription of these sections is outstanding** — see §7.

---

## 5. Selection Committee — cl. 5.7

**Universities**, for Assistant Professor / Assistant Librarian / Assistant DPES (L10→11, L11→12),
Associate Professor / Deputy Librarian / Deputy DPES (L13A), Professor / Additional Librarian /
Additional DPES (L14) and Professor (L15):

| Role | |
|---|---|
| **Chairperson** | **The Vice-Chancellor** — for *all* these levels, not only Professor |
| Member | A teacher not below the rank of Professor, nominated by the Visitor/Chancellor, where applicable |
| Members | **Three external subject experts** nominated by the VC from the panel approved by the relevant statutory bodies |
| Member | Dean of the Faculty, where applicable |
| Member | Head/Chairperson of the Department/School |
| Member | A teacher representing SC/ST/OBC/Minority/Women/Persons with disabilities, nominated by the VC, **(a)** if a candidate from those categories has applied **and (b)** if no committee member belongs to them |

> **Quorum: five members, including at least two external subject experts.**

**Two changes from 2018:** the VC chairs **all** these committees (2018 permitted a nominee for
Assistant and Associate Professor), and the quorum rises from **4 to 5**.

---

## 6. Other clauses of note

| Clause | Content |
|---|---|
| **1.7** | The overall selection procedure for direct recruitment and promotion is prescribed by the regulations |
| **3.1** | Direct recruitment to Asst./Assoc./Professor *"shall be based on merit through an **all-India advertisement**, followed by selection by a duly constituted Selection Committee"* |
| **3.5 / 3.6** | Qualifications in **Indian language medium**, and publication of books and chapters **in Indian languages**, *"may be encouraged"* |
| **3.12** | **PhD mandatory for promotion** to Assistant Professor L12, Associate Professor L13A and Professor L14 |
| **3.13** | Experience exclusion, as 2018 cl. 3.11 — **plus** up to **20% of total faculty strength** (excluding medical/maternity leave) may take study leave to pursue a PhD |
| **3.15** | The reserved-category committee representative provision |
| **3.16** | Provision for candidates registered for the PhD *(transcription outstanding)* |
| **3.17** | Colleges under State Governments / UT administrations |
| **11.0** | **Consequences of violation of UGC Regulations** — a compliance-enforcement clause with no 2018 equivalent |

---

## 7. Outstanding transcription

Before this ruleset could ever be activated, the following must be transcribed from the PDF and
verified by a second reader:

1. **cl. 4.1 IV–VI** — Assistant Librarian, Assistant DPES and remaining cadres in full.
2. **cl. 5.0–5.6** — promotion pathways under CAS, and cl. 5.4 / 5.5 eligibility criteria.
3. **cl. 5.7 II–IV** — selection committee composition for **colleges** and remaining levels.
4. **cl. 6.0** — counting of past service.
5. **cl. 7.0–7.3** — tenure and selection procedure.
6. **cl. 10.0–10.1** — Vice-Chancellor qualifications and selection.
7. **cl. 11.0** — consequences of violation.
8. **cl. 3.16, 3.17** — full text.

**Extraction method for the record.** The PDF has no text layer accessible to the tools on this
host (no `poppler-utils`, no Python PDF library). Text was recovered by inflating the PDF's
FlateDecode content streams and parsing the `Tj` / `TJ` text-showing operators directly —
63,597 characters, saved to `docs/research/ugc_draft_regulations_2025.txt`. **That extraction is
mechanical and unvalidated**: it drops layout, may reorder text across columns, and loses table
structure. It is adequate for identifying *what changed*; it is **not** adequate as the authority for
a figure. DOC-007 applies with full force — verify against the PDF.

---

## 8. What we do now, and what we do not

**Do now:**

- Keep `ugc-teaching-2025` in the catalogue as `status: draft, notified: false`.
- **Design M06 claim capture to satisfy both regimes** — structured publication metadata for 2018's
  Table 2 *and* narrative evidenced claims for 2025's notable contributions. This costs little now
  and is very expensive to retrofit.
- **Add `ncrf_level` to the qualification model now.** It is nullable under 2018 and required under
  2025.
- Make `ScoringStrategy` polymorphic per ruleset from the first design, not parameterised.

**Do not:**

- Do not apply any 2025 value to a live advertisement.
- Do not populate the 2025 ruleset's numeric fields in `rules-catalogue.yaml` from this document
  until §7 is complete and second-reader verified.
- Do not treat the abolition of the Research Score as licence to weaken Table 2's implementation.
  2018 is live, and every advertisement published before notification is bound to it **for ever**.

---

## 9. Traceability

| This document | Feeds |
|---|---|
| §1 abolition of the Research Score | `../domain/scoring-engine.md` → polymorphic `ScoringStrategy` |
| §2 delta table | `rules-catalogue.yaml` → `ugc-teaching-2025` |
| §3 notable contributions | M06 claim capture · M19 Committee Workspace |
| §4 cadre criteria | M35 `designations` — 2025 variant |
| §5 committee | M19 |
| §7 outstanding transcription | DOC-007 |

---

## 10. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Source PDF obtained and text extracted. **Confirmed the Research Score, Table 2 and Tables 3A/3B are abolished in the draft.** 15-row delta table, the nine notable-contribution areas per cadre, Assistant/Associate/Professor criteria and the selection committee transcribed. 8 sections recorded as outstanding transcription. | Implementation team |
