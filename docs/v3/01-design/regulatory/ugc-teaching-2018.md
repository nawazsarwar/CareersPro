# UGC Regulations 2018 — Teaching Cadres

**Instrument:** UGC (Minimum Qualifications for Appointment of Teachers and other Academic Staff in
Universities and Colleges and Measures for the Maintenance of Standards in Higher Education)
Regulations, **2018**
**Notification:** No. F.1-2/2017(EC/PS) · Gazette of India, Part III–Sec. 4, No. 271, **18 July 2018**
**Supersedes:** UGC Regulations 2010 (F.3-1/2009, 30 June 2010) and all its amendments
**Status:** the **sole active** teaching ruleset (DR-006)

**Status of this document:** live · **Owner:** implementation team · **Created:** 2026-08-27

---

## 0. How to read this document

### 0.1 Rule of transcription

Every figure here is **transcribed, never recalled**. Citations take the form
`cl. 4.1 II` (clause) and `:4550-4563` (line range in `docs/research/ugc_regulations_2018.txt`).

> ⚠️ **DOC-007 applies.** `ugc_regulations_2018.txt` is OCR-derived and demonstrably lossy —
> Appendix I fitment tables missing, a clause truncated mid-sentence, `"awarded"` substituted for
> `"evaluated"`, Table A columns mis-aligned. **`docs/UGC_Regulations_Teaching_Staff_2018.pdf`
> (4.9 MB) is the authority of last resort.** Every figure in this document must be confirmed
> against the PDF by a second reader before `rules-catalogue.yaml` is frozen.

Where the Gazette is ambiguous, this document **records the ambiguity and does not resolve it**.
Resolution is the Executive Council's (OQ-009). A specification that quietly picks a reading is how
a rejected candidate wins an appeal.

### 0.2 The one invariant that overrides everything else

> **cl. 4.1 I Note** (`:4547-4549`): *"The Academic score as specified in Appendix II (Table 3A) for
> Universities, and Appendix II (Table 3B) for Colleges, shall be considered for **short-listing of
> the candidates for interview only**, and the **selections shall be based only on the performance
> in the interview**."*
>
> **cl. 5.3** (`:5769-5773`): *"The selection process shall be completed on the day/last day of the
> selection committee meeting, wherein the minutes are recorded and recommendation made **on the
> basis of the performance of the interview** are duly signed by all members of the selection
> committee."*

**A teaching merit list is built from interview performance alone.** The shortlisting score decides
*who is interviewed* and then plays no further part. Letting it leak into the merit list is a
statutory violation, not a bug — enforce it at the type level in `MeritStrategy`, never by
convention. See `../domain/scoring-engine.md`.

The second clause also constrains the grievance design: because selection completes **on the day of
the meeting**, there is no room for a post-committee objection window. A pre-interview,
screening-stage window is the only compatible slot (M15).

---

## 1. Cadre eligibility matrix

| Cadre | Clause | Min. qualification | NET/SET | PhD | Experience | Publications | Research Score |
|---|---|---|---|---|---|---|---|
| **Assistant Professor** | 4.1 I `:4483-4549` | Master's **55%** (or equivalent grade) in concerned/relevant/allied subject | **Required**, unless exempt (§1.1) | Not mandatory¹ | — | — | — |
| **Associate Professor** | 4.1 II `:4550-4563` | Good academic record; Master's **≥55%** | n/a | **Mandatory** (cl. 3.8) | **8 years** teaching and/or research at a level equivalent to Assistant Professor | **7** | **75** |
| **Professor — Option A** | 4.1 III `:4564-4585` | Eminent scholar; published work of high quality | n/a | **Mandatory** (cl. 3.7) | **10 years** as Asst./Assoc./Professor, **with evidence of having successfully guided a doctoral candidate** | **10** | **120** |
| **Professor — Option B** | 4.1 III B `:4588-4595` | Outstanding professional from an academic institution not in A, **or from industry**; contribution **supported by documentary evidence** | n/a | Mandatory | **10 years** | not stated | not stated |
| **Senior Professor** | 4.1 IV `:4596-4611` | Eminent scholar. Capped at **10% of the sanctioned strength of Professors** | n/a | — | **10 years as Professor** or equivalent | **10 best**; **PhD awarded to ≥2 candidates under supervision in the last 10 years** | reviewed by **3 eminent experts** |
| **College Principal & Professor** | 4.1 V `:4612-4639` | — | n/a | **Mandatory** | Professor/Associate Professor with **≥15 years** total | **10** | **110** |
| **Assistant Librarian / College Librarian** | 4.7 I `:5094-5134` | Master's in Library/Information/Documentation Science **≥55%**; consistently good academic record **with knowledge of library computerisation** | **Required**, unless exempt | Not mandatory | — | — | — |
| **Deputy Librarian** | 4.7 II `:5142-5151` | Master's **≥55%**; **evidence of innovative library services including ICT integration** | n/a | **Required** — in Lib./Info./Doc. Science, archives and manuscript keeping, or library computerisation | **8 years** as Assistant University Librarian / College Librarian | — | — |
| **University Librarian** | 4.7 III `:5152-5161` | Master's **≥55%**; evidence of innovative library services including ICT | n/a | **Required** | **10 years** as Librarian at any level in a University Library **OR** 10 years teaching Library Science as Asst./Assoc. Professor **OR** 10 years as College Librarian | — | — |
| **Assistant Director PE&S / College DPES** | 4.8 I `:5168-5231` | Master's in Physical Education and Sports / PE / Sports Science **55%**; **record of having represented the university/college at inter-university/inter-collegiate competitions or State/national championships**; **passed the physical fitness test** | **Required**, unless exempt | Not mandatory² | — | — | — |
| **Deputy Director PE&S** | 4.8 II `:5232-5264` | **PhD** in PE/PE&S/Sports Science; candidates **from outside the university system must additionally hold ≥55% at Master's**; evidence of **organising competitions and conducting coaching camps of at least two weeks**; evidence of producing good team/athlete performance; **passed the physical fitness test** | n/a | **Required** | **8 years** as University Asst. DPES / College DPES³ | — | — |
| **Director PE&S** | 4.8 III `:5265-5283` | **PhD** in PE/PE&S/Sports Science; evidence of organising competitions and coaching camps **≥2 weeks**; evidence of good team/athlete performance | n/a | **Required** | **10 years** as Univ. Asst./Deputy DPES **OR** 10 years as College DPES **OR** 10 years teaching PE&S as Asst./Assoc. Professor | — | — |

¹ Not mandatory at notification, but **mandatory for direct recruitment to Assistant Professor in
Universities w.e.f. 01.07.2021** (cl. 3.10, `:4451-4454`). ⚠️ **The local text shows the
un-deferred date; a 2021 amendment deferred it — see DOC-002.** Alternative route B: PhD from a
foreign university ranked **in the top 500** of QS, THE or ARWU (Shanghai) at any time (cl. 4.1 I B).

² Alternative route B: **Asian Games or Commonwealth Games medal winner** with a degree at least at
postgraduate level.

³ Alternative route B: **Olympic Games / World Cup / World Championship medal winner** with a
postgraduate-level degree.

### 1.1 NET/SET exemption — the gateway clause

Exemption is granted where the PhD was awarded **in compliance with the UGC (Minimum Standards and
Procedure for Award of M.Phil./PhD Degree) Regulations, 2009 or 2016**, *"and their subsequent
amendments from time to time"* (cl. 3.3 I, 4.1 I A(ii)).

> ⚠️ **The single largest unresolved eligibility question in the system.** The **PhD Regulations
> 2022** superseded the 2016 Regulations and abolished M.Phil. The 2018 clause names only **2009 and
> 2016**. Whether a **2022-compliant PhD triggers exemption is not resolvable from the documents
> held** — and this is the most-used eligibility pathway in the portal. **DOC-002.** Until resolved,
> the rules catalogue models the compliance flag as an enum (`2009` / `2016` / `2022` / `none`) so a
> decision can be applied without a schema change.

**For candidates who registered for the PhD before 11 July 2009**, exemption requires **all five**
conditions of cl. 3.3 (`:4512-4536`), verbatim:

> (a) The Ph.D. degree of the candidate has been **awarded in a regular mode**;
> (b) The Ph.D. thesis has been **evaluated by at least two external examiners**;
> (c) An **open Ph.D. viva voce** of the candidate has been conducted;
> (d) The candidate has **published two research papers** from his/her Ph.D. work, **out of which at
> least one is in a refereed journal**;
> (e) The candidate has **presented at least two papers** based on his/her Ph.D. work in
> **conferences/seminars sponsored/funded/supported by the UGC/ICSSR/CSIR or any similar agency**.

**Certifying authority:** *"The fulfilment of these conditions is to be certified by the **Registrar
or the Dean (Academic Affairs)** of the University concerned."*

> ⚠️ **Conflict with the legacy form.** FN-1 item 20 says the exemption certificate comes from the
> *"Registrar/Controller"*. The Gazette says *"Registrar or the Dean (Academic Affairs)"*. A wrong
> issuing authority is a common ground for rejection, so **AMU must settle which it accepts** and
> the form must say so. Raise with the Registrar's Office.

**Also exempt:** disciplines in which NET/SLET/SET is **not conducted**.

**Note on the DPES variant:** at cl. 4.8 I the fifth condition (e) **omits the funding-agency
requirement** present in every other instance (`:5209-5212`). Transcribe as printed; flag for
verification against the PDF.

### 1.2 Discipline variants — exact figures

| Discipline | Clause | Associate Professor | Professor |
|---|---|---|---|
| **Music, Performing Arts, Visual Arts** | 4.2 | 8 years; **no research-score threshold stated** | **6 publications** (not 10); score **120** |
| **Drama** | 4.3 | 8 years; no threshold stated | **6 publications**; score **120** |
| **Yoga** | 4.4 | **7 publications**; score **75** | **10 publications**; score **120** |
| **Occupational Therapy** | 4.5 | Master's + **8 years as Assistant Professor** | Master's + **10 years** |
| **Physiotherapy** | 4.6 | Master's + **8 years as Assistant Professor** | Master's + **10 years** |

Occupational Therapy and Physiotherapy: **no NET, no publication minimum, no research score.**
Assistant Professor = Bachelor's + Master's in the discipline **≥55%**. Principal/Director/Dean =
Master's + **15 years including 5 years as Professor**, with the **senior-most Professor designated**.

### 1.3 Experience — what counts and what does not

> **cl. 3.11** (`:4456-4463`), verbatim: *"The **time taken by candidates to acquire M.Phil. and/or
> Ph.D. Degree shall not be considered as teaching/research experience** to be claimed for
> appointment to the teaching positions. Further the period of **active service spent on pursuing
> Research Degree simultaneously with teaching assignment without taking any kind of leave, shall be
> counted as teaching experience** for the purpose of direct recruitment/promotion."*

Two consequences for the data model:

1. Time spent acquiring M.Phil/PhD must be **netted out** of claimed experience. The application
   must capture PhD registration, submission and award dates so this is computable rather than
   self-declared.
2. The exception — simultaneous teaching **without taking any kind of leave** — has **no defined
   evidence standard**. Flag as an open ratification item; provisionally require a service
   certificate from the employer stating no leave was taken.

**Unresolved (OQ-010 adjacent):** whether **post-doctoral** experience counts at the same rate as
teaching, and whether **ad-hoc / guest / contractual** teaching is countable. Not stated in the
Gazette.

### 1.4 Physical fitness test — DPES cadres only

cl. 4.8 IV (`:5284-5331`). A medical fitness certificate is required first.

| Men — 12-minute run/walk | ≤30 yrs | ≤40 yrs | ≤45 yrs | ≤50 yrs |
|---|---|---|---|---|
| Distance | **1800 m** | **1500 m** | **1200 m** | **800 m** |

| Women — 8-minute run/walk | ≤30 yrs | ≤40 yrs | ≤45 yrs | ≤50 yrs |
|---|---|---|---|---|
| Distance | **1000 m** | **800 m** | **600 m** | **400 m** |

**Under-specified, and needs University policy:** who conducts the test, whether it precedes or
follows shortlisting, whether it is qualifying or scored, what happens on failure, re-test rights,
and whether age-band relaxation extends to PwBD candidates. Also note cl. 4.8 II(v) requires it for
Deputy DPES but it is **not listed** for the Director post — likely deliberate; confirm.

---

## 2. The 55% rule and the two 5% relaxations

**Base standard — cl. 3.4** (`:4410-4414`), verbatim:

> *"A minimum of **55% marks (or an equivalent grade in a point-scale, wherever the grading system is
> followed) at the Master's level** shall be the essential qualification for direct recruitment of
> **teachers and other equivalent cadres at any level**."*

**Category relaxation — cl. 3.4 I** (`:4416-4425`), verbatim:

> *"A **relaxation of 5% shall be allowed at the Bachelor's as well as at the Master's level** for
> the candidates belonging to **Scheduled Caste/Scheduled Tribe/Other Backward Classes
> (OBC)(Non-creamy Layer)/Differently-abled** ((a) Blindness and low vision; (b) Deaf and Hard of
> Hearing; (c) Locomotor disability including cerebral palsy, leprosy cured, dwarfism, acid-attack
> victims and muscular dystrophy; (d) Autism, intellectual disability, specific learning disability
> and mental illness; (e) Multiple disabilities from amongst persons under (a) to (d) including
> deaf-blindness) for the purpose of **eligibility and assessing good academic record for direct
> recruitment**. The eligibility marks of 55% marks … and the relaxation of 5% to the categories
> mentioned above are **permissible, based only on the qualifying marks without including any grace
> mark procedure**."*

**Pre-1991 relaxation — cl. 3.5** (`:4427-4430`), verbatim:

> *"A **relaxation of 5% shall be provided, (from 55% to 50% of the marks) to the Ph.D. Degree
> holders who have obtained their Master's Degree prior to 19 September, 1991**."*

**Grade equivalence — cl. 3.6** (`:4432-4435`), verbatim:

> *"A relevant grade which is regarded as **equivalent of 55%**, wherever the grading system is
> followed by a recognized university, at the Master's level shall also be considered valid."*

### 2.1 Three decoding rules for the engine

1. **cl. 3.5 applies only to PhD holders** whose Master's predates 19 September 1991. It is **not** a
   blanket pre-1991 relaxation.
2. The two 5% relaxations are **separate grounds**. The Regulations **do not authorise stacking them
   to 45%**. The engine must reject any path that reaches 45%.
3. **Grace marks are excluded** — eligibility is computed on qualifying marks only.

### 2.2 What is absent

**No EWS, no age limit, no age relaxation and no fee concession appear anywhere in the 2018
Regulations.** They are incorporated by reference to Government of India instructions
(cl. 6.0 III). See `reservation-and-relaxation.md` and **DOC-003**.

### 2.3 The CGPA problem

cl. 3.6 defers to *"a recognized university"*'s own equivalence — i.e. **the awarding university's
conversion formula governs**. This is not implementable as a single algorithm.

**Required design:** a per-university conversion register, or a documented default with a mandatory
applicant declaration plus documentary proof of the awarding institution's formula. **OQ-010.**

---

## 3. Appendix II Table 2 — Research Score

`:8250-8619`. Applies to Associate Professor, Professor, Senior Professor and College Principal.

**Table header, verbatim:**

> *"Methodology for University and College Teachers for calculating Academic/Research Score.
> (Assessment must be based on evidence produced by the teacher such as: copy of publications,
> project sanction letter, utilization and completion certificates issued by the University and
> acknowledgements for patent filing and approval letters, students' Ph.D. award letter, etc.)"*

**This header is a design requirement, not prose.** Every score-bearing claim carries a **mandatory
evidence artefact**; the upload is a hard precondition of the claim, not a soft one. See M06/M07.

### 3.1 The two faculty columns

| Column | Faculties |
|---|---|
| **I** | Sciences · Engineering · Agriculture · Medical · Veterinary Sciences |
| **II** | Languages · Humanities · Arts · Social Sciences · **Library** · **Education** · **Physical Education** · Commerce · Management & other related disciplines |

> ⚠️ **AMU's own mapping diverges from the Gazette.** FN-1 Part B adds **Unani Medicine** to Column I
> and **Theology** and **International Studies** to Column II, and **drops Library, Education and
> Physical Education from Column II entirely**. The divergence must be resolved against **AMU's
> Ordinances (DOC-001)**, not guessed. Until then the catalogue encodes the **Gazette** mapping and
> records AMU's as a pending override.

### 3.2 The table

| S.N. | Activity | Col. I | Col. II |
|---|---|---|---|
| **1** | **Research papers in peer-reviewed or UGC-listed journals** | **08** per paper | **10** per paper |
| **2(a)** | Books authored — **international publisher** | 12 | 12 |
| 2(a) | Books authored — **national publisher** | 10 | 10 |
| 2(a) | **Chapter in edited book** | 05 | 05 |
| 2(a) | **Editor** of book — international publisher | 10 | 10 |
| 2(a) | **Editor** of book — national publisher | 08 | 08 |
| **2(b)** | Translation work — chapter or research paper | 03 | 03 |
| 2(b) | Translation work — **book** | 08 | 08 |
| **3(a)** | Development of **innovative pedagogy** | 05 | 05 |
| **3(b)** | **Design of new curricula and courses** | **02** per curriculum/course | 02 |
| **3(c)** | **MOOCs** — complete MOOC in 4 quadrants (4-credit course) *(lesser credits: **05 marks/credit**)* | 20 | 20 |
| 3(c) | MOOCs — **per module/lecture** | 05 | 05 |
| 3(c) | **Content writer / subject-matter expert** per MOOC module (≥1 quadrant) | 02 | 02 |
| 3(c) | **Course coordinator** for MOOCs (4-credit) *(lesser credits: **02 marks/credit**)* | 08 | 08 |
| **3(d)** | **E-content** — 4 quadrants for a complete course / e-book | 12 | 12 |
| 3(d) | E-content — **per module** | 05 | 05 |
| 3(d) | **Contribution** to an e-content module (≥1 quadrant) | 02 | 02 |
| 3(d) | **Editor** of e-content for a complete course/paper/e-book | 10 | 10 |
| **4(a)** | **Research guidance — PhD** | **10** per degree awarded · **05** per thesis submitted | same |
| 4(a) | Research guidance — **M.Phil / PG dissertation** | **02** per degree awarded | same |
| **4(b)** | **Research projects completed** — **> ₹10 lakh** | 10 | 10 |
| 4(b) | Research projects completed — **< ₹10 lakh** | 05 | 05 |
| **4(c)** | **Research projects ongoing** — **> ₹10 lakh** | **05** | 05 |
| 4(c) | Research projects ongoing — **< ₹10 lakh** | **02** | 02 |
| **4(d)** | **Consultancy** | 03 | 03 |
| **5(a)** | **Patents — international** | 10 | 10 |
| 5(a) | Patents — **national** | 07 | 07 |
| **5(b)** \* | **Policy document — international** *(UNO/UNESCO/World Bank/IMF etc., **or Central or State Government**)* | 10 | 10 |
| 5(b) \* | Policy document — **national** | 07 | 07 |
| 5(b) \* | Policy document — **state** | 04 | 04 |
| **5(c)** | **Awards / fellowship — international** | 07 | 07 |
| 5(c) | Awards / fellowship — **national** | 05 | 05 |
| **6** \* | **Invited lecture / resource person / paper presentation** — **international (abroad)** | 07 | 07 |
| 6 \* | **International (within country)** | 05 | 05 |
| 6 \* | **National** | 03 | 03 |
| 6 \* | **State / University** | 02 | 02 |

*Category 6 note, verbatim:* a paper presented at a seminar/conference **and** published as a full
paper in the conference proceedings **is counted only once**.

**Correction on record:** categories 4(b) *completed* and 4(c) *ongoing* are **two separate rows**
with different values. `UGC_TEACHING_RECRUITMENT_REGULATIONS.md:188-189` merges them. See decision
register §5, correction 4.

### 3.3 Impact-factor augmentation

`:8535-8583`, verbatim: *"The Research score for research papers would be **augmented** as follows:
Peer-Reviewed or UGC-listed Journals (**Impact factor to be determined as per Thomson Reuters
list**)"*

| | Band | Points |
|---|---|---|
| i | Refereed journal **without** impact factor | **5** |
| ii | Impact factor **less than 1** | **10** |
| iii | Impact factor **between 1 and 2** | **15** |
| iv | Impact factor **between 2 and 5** | **20** |
| v | Impact factor **between 5 and 10** | **25** |
| vi | Impact factor **> 10** | **30** |

**Correction on record:** `UGC_TEACHING_RECRUITMENT_REGULATIONS.md:203-207` omits the
*"less than 1"* band and shifts every band down one. See decision register §5, correction 2.

> ⚠️ **Three unresolved ambiguities live in this sub-table.** See §5.

### 3.4 Apportionment — verbatim

`:8585-8594`:

> **(a) Two authors: 70% of total value of publication for each author.**
>
> **(b) More than two authors: 70% of total value of publication for the First/Principal/Corresponding
> author and 30% of total value of publication for each of the joint authors.**
>
> **Joint Projects: Principal Investigator and Co-investigator would get 50% each.**

**Two authors sum to 140% of the publication's value.** That is what the text says and what FN-1
reproduces. It is presumably a deliberate co-authorship incentive, but it is counter-intuitive to
auditors — **confirm and record, do not "correct" it.**

**Correction on record:** `ugc-rules.yaml:28-30` encodes PI 100% / Co-PI 50%. The Gazette says
**50% each**. See decision register §5, correction 3. **This is the error that would have made every
Associate Professor and Professor determination wrong.**

### 3.5 Notes, caps and maxima — verbatim

`:8602-8619`:

> • **Paper presented if part of edited book or proceeding then it can be claimed only once.**
>
> • **For joint supervision of research students, the formula shall be 70% of the total score for
> Supervisor and Co-supervisor. Supervisor and Co-supervisor, both shall get 7 marks each.**
>
> • ***For the purpose of calculating research score of the teacher, the combined research score from
> the categories of 5(b). Policy Document and 6. Invited lectures/Resource Person/Paper presentation
> shall have an upper capping of thirty percent of the total research score of the teacher
> concerned.***
>
> • **The research score shall be from the minimum of three categories out of six categories.**

**There is no other numeric maximum anywhere in Table 2** — no per-category ceilings and no overall
ceiling. The 30% cap is on the **combined** 5(b) + 6, not on 6 alone.

**Correction on record:** `UGC_TEACHING_RECRUITMENT_REGULATIONS.md:217` applies the cap to category 6
alone. See decision register §5, correction 5.

### 3.6 Thresholds

| Context | Required score | Clause |
|---|---:|---|
| Associate Professor (direct recruitment) | **75** | 4.1 II |
| Professor (direct recruitment) | **120** | 4.1 III |
| College Principal & Professor | **110** | 4.1 V |
| CAS: Asst. Prof. L12 → Assoc. Prof. L13A (Universities) | **70** | 6.4 C III |
| CAS: Assoc. Prof. L13A → Professor L14 | **110** | 6.4 C IV, B IV |
| Assistant Professor, all Librarian and all DPES cadres | **none** | — |

---

## 4. Appendix II Tables 3A and 3B — shortlisting

`:8620-8897`. **Shortlisting only** — see §0.2.

### 4.1 Table 3A — Assistant Professor in **Universities**

| S.N. | Criterion | Score |
|---|---|---|
| 1 | **Graduation** | 80% & above = **15** · 60 to <80% = **13** · 55 to <60% = **10** · 45 to <55% = **05** |
| 2 | **Post-Graduation** | 80% & above = **25** · 60 to <80% = **23** · **55% (50% for SC/ST/OBC-NCL/PWD)** to <60% = **20** |
| 3 | **M.Phil.** | 60% & above = **07** · 55 to <60% = **05** |
| 4 | **Ph.D.** | **30** |
| 5 | **NET with JRF = 07** · NET = **05** · SLET/SET = **03** | |
| 6 | **Research publications** — 2 marks each, peer-reviewed or UGC-listed | **10** |
| 7 | **Teaching / post-doctoral experience** — 2 marks per year # | **10** |
| 8 | **Awards** — International/National = **03** · State-level = **02** | |

**# verbatim:** *"However, if the period of teaching/Post-doctoral experience is **less than one
year** then the marks shall be **reduced proportionately**."*

**Caps (Note A):** M.Phil + Ph.D **max 30** · JRF/NET/SET **max 07** · Awards **max 03**.
**Totals (Note C):** Academic 80 + Publications 10 + Experience 10 = **100**.
**Note B:** the number called for interview is **decided by the university**.
**Note D:** *"Score shall be valid for appointment in respective State SLET/SET Universities/Colleges/Institutions only."*

### 4.2 Table 3B — Assistant Professor in **Colleges**

| S.N. | Criterion | Score |
|---|---|---|
| 1 | **Graduation** | 80% & above = **21** · 60 to <80% = **19** · 55 to <60% = **16** · 45 to <55% = **10** |
| 2 | **Post-Graduation** | 80% & above = **25** · 60 to <80% = **23** · **55% (50% for SC/ST/OBC-NCL/PWD)** to <60% = **20** |
| 3 | **M.Phil.** | 60% & above = **07** · 55 to <60% = **05** |
| 4 | **Ph.D.** | **25** |
| 5 | **NET with JRF = 10** · NET = **08** · SLET/SET = **05** | |
| 6 | **Research publications** — 2 marks each | **06** |
| 7 | **Teaching / post-doctoral experience** — 2 marks per year # | **10** |
| 8 | **Awards** — International/National = **03** · State-level = **02** | |

**Caps (Note A):** M.Phil + Ph.D **max 25** · JRF/NET/SET **max 10** · Awards **max 03**.
**Totals (Note C):** Academic 84 + Publications 06 + Experience 10 = **100**.

### 4.3 Points the engine must handle

1. **The 50% carve-out is on the PG row only.** Tables 3A/3B do **not** extend the 50% floor to the
   Graduation row. Note the tables use **PWD** where cl. 3.4 I enumerates five disability categories.
2. **Which Graduation, which PG** where a candidate holds several? Not stated. Provisional rule:
   the qualification that establishes eligibility for the advertised subject; record and flag.
3. **"Government of India recognised National Level Bodies"** (awards) is undefined. Requires a
   curated master list — a `designations`-adjacent lookup, admin-maintained.
4. **Note D is garbled in 3A** relative to 3B. Table 3B's phrasing (*"SLET/SET score shall be
   valid…"*) is the intelligible one; adopt it for both and flag.
5. **FN-1's AMU gloss** *"JRF/NET/SET (UP states) Maximum – 07 Marks"* has no Gazette equivalent and
   needs explanation from the Registrar's Office.

---

## 5. Unresolved ambiguities — OQ-009

**These are not implementation details.** Each changes outcomes materially and requires a recorded
Executive Council decision before `rules-catalogue.yaml` is frozen. **Do not resolve them by picking
the sensible reading.**

| # | Ambiguity | Materiality |
|---|---|---|
| 1 | **"Augmented" — replace or add?** Whether the impact-factor value **replaces** the base 8/10 per paper or is **added** to it is not stated. | For a Professor applicant with 20 papers: a **160–200 point** swing against a 120-point threshold. Determinative. |
| 2 | **Band boundaries overlap and are non-exhaustive.** *"less than 1 / between 1 and 2 / between 2 and 5 / between 5 and 10 / >10"* — IF exactly 1, 2 or 5 falls in **two** bands; **IF exactly 10 falls in none.** | Recommended tie-break, **pending ratification**: upper-inclusive — `(0,1)→10, [1,2]→15, (2,5]→20, (5,10]→25, >10→30`. |
| 3 | **Which impact factor, which year?** *"as per Thomson Reuters list"* = Clarivate JCR, but the edition (year of publication vs year of application) is unstated. | Changes the band for borderline journals. |
| 4 | **"UGC listed" is a moving target.** UGC-CARE replaced the Approved List in 2019 and was itself discontinued in 2024. Which list governs a 2015 paper? | Determines whether a paper scores at all. |
| 5 | **Joint supervision is self-contradictory.** *"70% of the total score for Supervisor and Co-supervisor. Supervisor and Co-supervisor, both shall get 7 marks each."* 7 + 7 = 14 against a base of 10. | Encode the literal *"7 each"*; flag. |
| 6 | **The 30% cap is circular.** The cap on 5(b)+6 is *"thirty percent of the **total** research score"*, but the total includes 5(b) and 6. | Recommended solved form, **pending ratification**: `capped = min(raw_5b6, (3/7) × other_categories)`. Related: *"minimum of three categories out of six"* — is a candidate scoring in only two **ineligible**, or is the excess **disregarded**? |

**DOC-001 may resolve several of these.** FN-1 Part B states it applies *"where the qualifications
are advertised as per **Ordinances (Executive)** framed in the light of the UGC Regulations, 2018"*
and directs the reader to *"Appendix II of the **Ordinances**"*. **AMU's Ordinances are the operative
instrument.** Obtain them before freezing the catalogue.

---

## 6. Selection Committees — cl. 5.1 (`:5337-5756`)

| Cadre | Chairperson | Quorum |
|---|---|---|
| Assistant Professor, University | **VC or nominee** with ≥10 years as Professor | 4, incl. **2 outside subject experts** |
| Associate Professor, University | **VC or nominee** (≥10 yrs as Professor) | ≥4, incl. 2 outside subject experts |
| **Professor, University** | **Vice-Chancellor — no nominee** | ≥4, incl. 2 outside subject experts |
| **Senior Professor** | **Vice-Chancellor** | 4, incl. 2 outside subject experts |
| Assistant Professor, Colleges | Chairperson of the Governing Body or nominee from GB members | 5, incl. 2 outside subject experts |
| Associate Professor, Colleges | as above | 5, incl. 2 subject experts |
| Professor, Colleges | as above | 5, incl. 2 subject experts |
| College Principal & Professor | **Chairperson of the Governing Body** | 5, incl. 2 experts |

**Correction on record:** `UGC_TEACHING_RECRUITMENT_REGULATIONS.md:225` says the Professor committee
is chaired by *"the VC **or their nominee**"*. **For Professor and Senior Professor the chair is the
Vice-Chancellor in person, no nominee permitted** (`:5399-5401`, `:5430-5432`). The nominee formula
applies **only** to Assistant and Associate Professor (`:5346-5349`, `:5369-5372`). See decision
register §5, correction 6.

**University committee membership** (Asst./Assoc./Professor): Visitor's/Chancellor's nominee not
below Professor where applicable · **3 subject experts nominated by the VC from the panel approved by
the relevant statutory body** · Dean of Faculty where applicable · Head/Chairperson of
Department/School · **a reserved-category academician** (SC/ST/OBC/Minority/Women/Differently-abled)
where such a candidate has applied and no member belongs to that category.

**Senior Professor:** the same six roles, but **every member must be ≥ Senior Professor/Professor
with at least ten years' experience**.

**Librarians and DPES — cl. 5.1 IX, verbatim:**

> *"Selection Committees for the posts of Directors, Deputy Directors, Assistant Directors of
> Physical Education and Sports, Librarians, Deputy Librarians and Assistant Librarians shall be the
> **same as that of Professor, Associate Professor and Assistant Professor, respectively**, except
> that … a **practicing Librarian / Director Physical Education and Sports, as the case may be, shall
> be associated with the Selection Committee as one of the subject experts**."*

**Two binding constraints:**

- **cl. 5.4** (`:5775-5778`): *"Head of Department / Teacher-Incharge should be either in the same or
  higher rank/position than the rank/position for which the interview is to be held."*
- **cl. 6.0 III** (`:5800-5811`): the reserved-category nominee *"shall be **one level above the
  cadre level of the applicant**"* and *"shall ensure that the norms of the Central Government or
  concerned State Government … are strictly followed during the selection process."*

> ⚠️ **DOC-001 blocker.** The composition for **Registrar, Finance Officer and Controller of
> Examinations** is not in either instrument — CRR Schedule-1 column 12 reads only *"As per
> Act/Statutes/UGC Notification"*. **M19 cannot be fully specified without AMU's Statutes and
> Ordinances.**

---

## 7. Internal defects in the Gazette text

Reconcile each against the PDF before freezing the catalogue (**DOC-007**).

| # | Defect | Note |
|---|---|---|
| 1 | **cl. 3.12** requires qualifications *"as provided in the **Schedule 1** of these Regulations"* — **there is no Schedule 1**. The document has an Annexure (cl. 2) and Appendix I / Appendix II (Tables 1–5) | Numbering is inconsistent throughout |
| 2 | **cl. 3.3 I(b)** reads *"The Ph.D. thesis has been **awarded** by at least two external examiners"* vs **"evaluated"** in all five parallel instances | OCR or drafting error; **"evaluated"** is correct |
| 3 | **Table A** (`:6055-6078`) column ordering is Screening/**Selection**/Screening/Selection, out of sequence with the structurally identical Tables C and E | Probable OCR column mis-alignment |
| 4 | **Appendix I (Fitment Tables)** is referenced (`:8133-8138`, `:8160-8162`) but **the tables are absent from the text dump** | Source from the PDF if pay fixation is in scope |

---

## 8. Traceability

| This document | Feeds |
|---|---|
| §1 cadre matrix, §2 relaxations | `rules-catalogue.yaml` → M20 eligibility · M35 `designations` |
| §3 Table 2 | `rules-catalogue.yaml` → M20 research score · M06 claim capture |
| §4 Tables 3A/3B | `rules-catalogue.yaml` → M21 shortlisting |
| §0.2 invariant | `../domain/scoring-engine.md` → `MeritStrategy` |
| §5 ambiguities | decision register OQ-009 |
| §6 committees | M19 Committee Workspace |

---

## 9. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. **11 cadres** (12 rows — Professor has two entry routes, modelled in the catalogue as `professor` + `alt_route: outstanding_professional`), 5 discipline variants, Table 2 (33 sub-rows, 2 faculty columns, 6 IF bands), Tables 3A/3B, selection committees and relaxations, all transcribed with clause citations. 6 ambiguities and 4 source defects recorded unresolved. | Implementation team |
