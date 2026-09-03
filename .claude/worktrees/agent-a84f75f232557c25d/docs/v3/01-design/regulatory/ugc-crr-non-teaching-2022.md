# UGC Model Cadre Recruitment Rules 2022 — Non-Teaching Cadres

**Instrument:** Central University Non-teaching and Other Academic Posts **Model Recruitment Rules,
2022** (`:812-813`)
**Enacted by:** each university's **Executive Council / Board of Management**, *"in supersession of
all existing recruitment rules and Ordinances"* (`:802-807`)
**Status:** the **active** non-teaching ruleset (DR-006)

**Status of this document:** live · **Owner:** implementation team · **Created:** 2026-08-27

Citations are `Rule N` and `:NNNN` (line in `docs/research/ugc_model_non_teaching.txt`).
**DOC-007 applies** — verify every figure against
`docs/UGC_Model_Cadre_Recruitment_Rules_Non_Teaching.pdf` (3.5 MB) before freezing the catalogue.

---

## 0. Two supremacy clauses that constrain everything

> **Rule 38, verbatim:** *"In case any particular provision in these Rules is **in conflict with any
> provision of the UGC Regulations/guidelines or Govt. of India Orders, the provisions of the UGC
> Regulations/guidelines or Govt. of India Orders shall prevail**."*

> **Rule 22.11:** minimum qualifications, experience, Selection Committee constitution, prescribed
> quota and method of recruitment for **Registrar, Finance Officer, Controller of Examinations,
> Librarian, Deputy Registrar, Assistant Registrar and Assistant Librarian** *"shall be governed
> **strictly as per the UGC's guidelines/regulations**. Any amendment … in future shall be **adopted
> mutatis-mutandis** by the University … **in supersession of the existing provisions**."*

**Design consequence.** University-level configuration can **never** override a UGC rule. The rules
engine enforces that precedence — it is not left to administrators to respect. This is a second
reason rules are versioned data carrying citations rather than editable numbers.

**Rule 37:** amendment, modification, withdrawal, suspension or relaxation of any provision requires
**prior approval of the Government of India / UGC**.

---

## 1. Classification and authority

**Groups — Rule 4** (`:918-929`):

| Group | Pay Levels |
|---|---|
| **A** | **10 and above** |
| **B** | **6 to 9** |
| **C** (incl. MTS) | **1 to 5** |

**Appointing authority — Rule 6** (`:938-954`):

| Authority | Scope |
|---|---|
| **Executive Council** | Permanent appointment to **all Group 'A'** |
| **Vice-Chancellor** | Permanent Group 'B'; temporary Group 'A' and 'B' |
| **Registrar** | Permanent and temporary **Group 'C'** |

**Methods — Rule 7:** 7.1 Direct Recruitment · 7.2 Promotion · 7.3 Deputation/Absorption ·
7.4 Tenure Appointment.

**Age crucial date — Rule 14** (`:1145-1147`), verbatim: *"The crucial date for determining the age
shall be **the closing date of the application**."*

> This is a **hard engine rule**, not a convention. `designations.age_reference` exists solely to
> encode it, and age is computed against `posts.reg_end_date` — never against the submission date,
> the payment date or the interview date.

---

## 2. The 58 cadres

Pay levels from the Summary of Posts (`:14-796`); age and method from each post's Schedule-1 entry.

### 2.1 Administration

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 1 | **Registrar** | A | 14 | Direct/Deputation. **Tenure 5 years or until superannuation at 62**, whichever is earlier; reappointment after due selection. Deputation: analogous post or **8 years at Level 12** | Preferably **below 57** |
| 2 | **Finance Officer** | A | 14 | As Registrar. Deputation *preferably from Indian Audit & Accounts Services or similar organised Services not below Level 12* | Preferably **below 57** |
| 3 | **Controller of Examinations** | A | 14 | As Registrar | Preferably **below 57** |
| 4 | **Deputy Registrar** | A | 12 | **75% direct / 25% promotion**, failing which deputation *(MHRD Letter 1-7/2015-U.II(2) dt 02.11.2017)*. Promotion: Assistant Registrar with **5 years at Level 11** | **50** |
| 5 | **Assistant Registrar** | A | 10 | **50% direct / 50% promotion**, failing which deputation/DR. DR **through All-India open competition by written test and interview**. Promotion: **5 years as SO/PS (L7 and above)** on written test | **40** |
| 6 | **Administrative Officer (Colleges)** | A | 10 | Identical to Assistant Registrar | **40** |
| 7 | **Section Officer** | B | 7 | **75% promotion** from Assistant **subject to qualifying the departmental test**, failing which deputation; **25% direct (written + skill test)**. Promotion: **5 years as Assistant at Level 6** | **35** |
| 8 | **Assistant** | B | 6 | **75% promotion / 25% direct** (written + skill test). Promotion: UDC with **5 years at Level 4** | **35** |
| 9 | **Upper Division Clerk** | C | 4 | **75% promotion / 25% direct** (written + skill test). Promotion: LDC/Hindi Typist with **5 years at Level 2** | **32** |
| 10 | **Lower Division Clerk** | C | 2 | **85% direct** (written + skill test in MS Office, esp. Word/Excel); **10% departmental qualifying exam** from Group-C L1 with 10+2 and 3 years' service (**max age 45**, panel valid 1 year); **5% promotion** from MTS with 5 years at L1 + 10+2 | **32** |
| 11 | **Multi Tasking Staff** | C | 1 | **Direct through written and trade test.** EQ: **10th pass or ITI pass** | **32** |
| 12 | **Internal Audit Officer** | A | 12 | **Deputation only** — Audit & Accounts Services holding an analogous post, or 3 years at L11, or 5 years at L10 | **56** |
| 13 | **Public Relation Officer** | A | 10 | Direct. EQ: Master's **55%** (or **grade B on the UGC 7-point scale**) in **Journalism and Mass Communication** + **5 years** editorial experience | **40** |
| 14 | **Law Officer** | A | 10 | Direct | **40** |
| 15 | **Training and Placement Officer** | A | 10 | Direct; experience in training/placement in a university or large corporate | **40** |
| 16 | **Estates Officer** | B | 7 | **Direct through written test** | **35** |
| 17 | **Statistical Officer / Research-cum-Statistical Officer** | B | 7 | Direct; Applied Statistics + Govt./PSU/statutory body experience | **35** |
| 18 | **Senior Statistical Assistant** | B | 6 | **50% promotion / 50% direct.** Promotion: 5 years at L5. Deputation: analogous/L5 | **35** |
| 19 | **Statistical Assistant** | C | 5 | **Direct through written test** | **32** |
| 20 | **Private Secretary** | B | 7 | Promotion + deputation. *Promotion requires a typing/skill test with computer knowledge (Rule 25.5)* | **35** |
| 21 | **Personal Assistant** | B | 6 | Direct / promotion / deputation | **35** |
| 22 | **Stenographer** | C | 4 | **100% direct** | **32** |

### 2.2 Library

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 23 | **Librarian** | A | **Academic 14** | **Direct, failing which deputation.** Probation 1 year. Selection Committee *"As per UGC Regulations 2018"*. EQ mirrors UGC 2018 cl. 4.7 III (**10 years + PhD**) | Preferably **below 57** |
| 24 | **Deputy Librarian** | A | **Academic 13A** | Direct | **50** |
| 25 | **Assistant Librarian** | A | **Academic 10** | Direct. EQ mirrors UGC 2018 cl. 4.7 I including NET/SLET/SET and the PhD exemption proviso | **40** |
| 26 | **Information Scientist** | A | 10 | Direct, failing which deputation | **40** |
| 27 | **Professional Assistant** | B | 6 | Promotion (Semi Professional Assistant, 5 years) / deputation | **35** |
| 28 | **Semi Professional Assistant** | C | 5 | **25% direct / 75% promotion** (Library Assistant, 5 years at L4) | **32** |
| 29 | **Library Assistant** | C | 3 | **25% direct**; promotion from Library Attendant possessing 10+2 | **32** |
| 30 | **Library Attendant** | C | 1 | Direct | **32** |

### 2.3 Technical and computing

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 31 | **Technical Officer / Maintenance Engineer** | A | 10 | Direct; feeder ref. Senior Technical Assistant | **40** |
| 32 | **Senior Technical Assistant** | B | 6 | **25% direct** (written + skill test) / promotion (Technical Assistant, 5 years) | **35** |
| 33 | **Technical Assistant** | C | 5 | **25% direct** (written + skill test) / promotion (Laboratory Assistant, 5 years) | **32** |
| 34 | **Laboratory Assistant** | C | 4 | **75% direct** / promotion (Laboratory Attendant, 8 years at L1) | **32** |
| 35 | **Laboratory Attendant** | C | 1 | Direct | **32** |
| 36 | **System Manager / Senior System Analyst** | A | 12 | Direct, failing which deputation | **50** |
| 37 | **System Engineer / Senior Maintenance Engineer** | A | 12 | Direct, failing which deputation | **50** |
| 38 | **Junior Maintenance Engineer / Networking Engineer** | A | 10 | Direct | **40** |
| 39 | **System Analyst / Programmer / Computer Programmer / System Programmer** | A | 10 | Direct | **40** |
| 40 | **Sr. Technical Assistant (Computer) / Junior Programmer / Assistant Programmer** | B | 6 | Direct + promotion | **35** |
| 41 | **Technical Assistant (Computer)** | C | 5 | Direct | **32** |

### 2.4 Engineering

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 42 | **Superintendent Engineer (Civil) / University Engineer** | A | 13 | **Deputation** — analogous post or 8 years' specified service | **56** |
| 43 | **Executive Engineer (Civil)** | A | 11 | Promotion, failing which deputation/direct. Promotion: Assistant Engineer with a degree in the relevant branch. ⚠️ *Source truncated mid-sentence: a level-upgrade clause "after 5 years of service as Executive Engineer with Level 11" exists but is unreadable — see §5* | **45** |
| 44 | **Executive Engineer (Electrical)** | A | 11 | As above | **45** |
| 45 | **Assistant Engineer (Civil / Electrical / Mechanical)** | B | 7 | Promotion (Junior Engineer, 5 years) / deputation | **35** |
| 46 | **Junior Engineer** | B | 6 | Direct | **35** |

### 2.5 Hindi cell

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 47 | **Hindi Officer** | A | 10 | Direct | **40** |
| 48 | **Hindi Translator** | B | 6 | Direct | **35** |
| 49 | **Hindi Typist** | C | 2 | Direct. *"To be clubbed with LDC for career progression with bottom seniority; inter-se-seniority between the two cadres shall be maintained"* | **32** |

### 2.6 Security and support services

| # | Post | Grp | Level | Method | Age (DR) |
|---|---|---|---|---|---|
| 50 | **Security Officer** | B | 7 | Direct, failing which deputation | **35** |
| 51 | **Assistant Security Officer** | B | 6 | Direct, failing which deputation | **35** |
| 52 | **Security Inspector** | C | 5 | **100% direct** | ⚠️ **not stated in source** (all sibling Group-C posts state 32) |
| 53 | **Security Assistant** | C | 2 | Direct | **32** |
| 54 | **Guest House Manager** | B | 6 | Direct | **35** |
| 55 | **Cook** | C | 2 | Direct | **32** |
| 56 | **Kitchen Attendant** | C | 1 | Direct | **32** |
| 57 | **Hostel Attendant** | C | 1 | Direct | **32** |
| 58 | **Driver** | C | 2 | **Direct through driving test**, knowledge of traffic rules | **32** |

### 2.7 Two structural rules

- **Rule 34.3:** *"wherever there is only **one sanctioned post in any cadre**, the post shall be
  filled **through direct recruitment only**."*
- **Rule 34.4:** the University *"shall adopt the CRR **only for such posts which are sanctioned to
  them by the UGC**"* and may not create posts merely because they appear in the model.

> Both rules operate on `organisational_unit_designation.sanctioned_count` (M35). Rule 34.3 in
> particular is a **guard on the advertisement builder**: a designation with a sanctioned count of 1
> in a given unit cannot be advertised by promotion.

### 2.8 Cadres AMU has that the model rules omit — DOC-004

The 58 contain **no Medical Officer, Nursing, Pharmacist, Paramedical, Radiographer, hospital
Laboratory Technician or Physiotherapist** cadre — despite **Rule 28(a)** expressly providing
**DACP for Medical Officers** (`:1640-1642`). AMU operates **JNMC Hospital, the Ajmal Khan Tibbiya
College Hospital and a Dental College**. Also absent: Horticulture, Press/Printing, Farm, Veterinary
support, Sports coaches, Curator/Museum and Archives.

**Rule 19.1 authorises the University to frame its own rules where UGC guidelines do not exist.**
Those AMU rules are not in the repository. **DOC-004.**

---

## 3. Selection process — Rule 11 III(f)–(j) (`:1063-1113`)

**Trigger, verbatim:** *"While filling up the posts under direct recruitment, the University **shall
hold the written and/or Skill tests for all Group 'B' and 'C' Non-Teaching posts**."*

**Syllabus scope:** Reasoning Ability · Simple Arithmetic · General Knowledge · Domain Knowledge of
the Establishment · Accounts · Examinations · Language proficiency in English and Hindi · noting and
drafting · and/or skill tests, or any other test depending on job requirements (Technical/Laboratory,
Engineering, ICT, Library services).

### 3.1 Stages and marks

| Stage | Marks | Qualifying rule |
|---|---:|---|
| **Paper I — objective** | **100** | **minimum 40%** to qualify |
| **Paper II — descriptive** | **100** | **evaluated only for candidates who qualify Paper I**; **50%** required to proceed to skill test/interview |
| **Skill test** (where applicable) | **50** | **minimum 25** — **qualifying only, NOT additive** |
| **Interview** (where applicable) | **20% of the total marks** | **added to** Paper I + Paper II for the merit list |

**Merit list, verbatim:** *"The merit list of the candidates shall be drawn based on the performance
in **Paper I (Objective Type Test) and Paper II (Descriptive test) and Interview (wherever
applicable) subject to qualifying the skill test**, wherever applicable."*

**Single-test option:** the University may hold a **single written (descriptive) test** plus skill
test, at its discretion, depending on candidate numbers and job requirements.

**Rule 11 III(h):** the competent authority to frame **syllabi, modalities and evaluation** is the
**Vice-Chancellor**.
**Rule 11 III(i):** relaxation in qualifying marks for reserved categories is **as per Government of
India guidelines** (see `reservation-and-relaxation.md`, DOC-003).
**Rule 11 III(j):** for **Group 'A'** non-teaching posts the University *"may at its discretion adopt
appropriate procedures … on similar lines"* — **discretionary, not mandatory**.

### 3.2 ⚠️ Unreconciled conflict — OQ-008

| | |
|---|---|
| **Rule 11 III(f)/(g)** | Builds a **20%-weighted interview** into the Group B/C merit list |
| **Rule 22.8** (`:1384-1386`) | *"there shall be **no interview for appointment to the Group 'C' and 'B' posts**"*, per **MHRD Letter No. 19-50/2015-Desk-U dated 22.12.2015** |

**The source does not reconcile them.** The reading that the later, more specific, externally-mandated
rule prevails — making interview a **Group 'A'-only stage** — is plausible but **requires written
legal ratification before it is encoded**, because it determines whether an entire selection stage
exists.

**A second, dependent ambiguity:** if the interview is *"20% of the total marks"* and is *"added to"*
Paper I + Paper II, is the total **240** (200 + 40 added on top) or **100** (composite normalised
with interview = 20)? **Not derivable from the text.** Both must be settled together.

**Until OQ-008 closes:** `designations.selection_method` supports all variants and the merit strategy
is resolved from the designation, so encoding either outcome is a data change, not a code change.

### 3.3 Other test regimes

| Cadre | Regime |
|---|---|
| MTS | **Written + trade test** |
| LDC | Written + **skill test in MS Office** |
| LDC / UDC (essential qualification) | **English typing @ 35 wpm OR Hindi typing @ 30 wpm** *(= 10,500 / 9,000 key depressions per hour at 5 depressions per word)* |
| Driver | **Driving test** + knowledge of traffic rules |

**Rule 25.5** (`:1605-1613`): a **typing/skill test with computer knowledge is compulsory for
PROMOTION** to LDC, UDC, Assistant, Semi-Professional Assistant and Library Assistant, and for all
Personal Assistants being promoted to Private Secretary. MTS→LDC skill-test relaxation per **DoPT OM
No. F.No.14020/1/2014-Estt.(D) dated 22 April 2015**.

### 3.4 Screening ratio — Rule 16 (`:1199-1214`)

> *"**Except for the posts of Registrar, Finance Officer, Controller of Examination, Librarian,
> Director of Physical Education**, it must be ensured that the **ratio of the number of vacant posts
> to be filled and the number of candidates to be called for interview does not exceed 1:15**."*

- The Screening Committee **may fix higher criteria** to comply.
- If there are **fewer than 3 eligible applicants**, the post **shall be re-advertised at least
  twice**.
- Where a common written test is held, **all eligible candidates (minimum 3) may be called**
  notwithstanding the ratio; successful candidates are then called for interview in order of merit
  **subject to 1:15**.

### 3.5 Committees — Schedule-II (`:1888-2226`)

| Body | Chair | External experts |
|---|---|---|
| Selection Committee, Group A | **VC / Pro-VC** | **2**, nominated by the VC from the EC-approved panel |
| Selection Committee, Group B | **Pro-VC / Registrar** | 2 |
| Selection Committee, Group C | **Registrar** | 2 |
| **DPC** | same chairs | **1** |
| **DCC** | same chairs | **none** |

Membership also includes the Head of Unit/Department, a **reserved-category representative**, and —
Group A only — **one Executive Council member nominated by it**; plus Registrar / Registrar-JR-DR /
JR-DR respectively.

**Quorum:** **two-thirds of members**, including the Chairperson, the VC's nominee where applicable,
**at least one of the two external experts**, and **one reserved-category representative**.
**A minority representative is associated only where the number of vacancies is 10 or more.**

---

## 4. Cross-cutting obligations

| Obligation | Text | Rule |
|---|---|---|
| **Absolute disqualifications** | Convicted by any court **or with criminal proceedings pending** (33.1) · of unsound mind, questionable conduct, or **not medically fit** (33.2) · *"who has entered into or **contracted a marriage with a person having a living spouse**"* — with a proviso permitting exemption where such marriage is **permissible under the personal law applicable to both parties** and there are other grounds (33.3) · **not a citizen of India** (33.4) · any other category disqualified by GoI/State/UGC (33.5) | 33 |
| **Application channel** | Candidates *"shall be required to **download the application forms from the website** … **or submit the applications in the prescribed format online**"*; entertained **only** in the prescribed format with the prescribed fee **payable through online/offline payment** | 11 III(a),(b) |
| **Fee setting** | *"The schedule of charges … shall be determined by the **Vice-Chancellor**, from time to time. **Concessions in application/processing fee, wherever provided, shall be as per Govt. of India norms.**"* | 11 III(c) |
| **Late applications** | Incomplete and late applications **shall not be entertained**. The **VC may admit a late application on proof, to his/her satisfaction, that it was posted on or before the closing date.** **If the closing date is a holiday, the next working day is the closing date.** | 11 III(d) |
| **Addressing** | *"Application should be addressed to '**The Registrar**' … in a closed cover **super-scribing "Application for the post of ………"**"* | 11 III(e) |
| **Seniority** | Determined by *"**position in the merit list recommended by the Selection Committee**"* | 20 |
| **Perpetual verification** | Claims may be verified *"**at any point of time even after joining**"* | 22.4 |
| **Vigilance** | The CVO may investigate **at any stage** | 34.2 |
| **Liability to serve** | Non-teaching and other academic staff *"shall be **liable to serve anywhere in India within the jurisdiction of the University**"* | 39 |
| **Sanctioned strength** | Authorised strength per Schedule-1; new UGC/MoE-sanctioned posts added **with EC approval**; the EC may **abolish** a post under intimation to UGC; **conversion of any post requires prior UGC approval** | 8, 9.1 |
| **Repeal & savings** | Existing rules on covered matters **stand repealed**; action already taken is **deemed taken under these rules** | 41 |
| **Initial constitution** | Existing regular incumbents **deemed appointed**; prior regular continuous service **counts for probation, qualifying service, confirmation and pension** | 10 |
| **Conduct regime** | **CCS (Conduct) Rules 1964** and **CCS (CCA) Rules 1965** | 22.5 |
| **Financial upgradation** | **MACP**, effective **01.09.2008**; **DACP** for Medical Officers | 28(a) |

### 4.1 Rule 33.3 — flagged for legal sign-off (OQ-012)

The bar on applicants who *"contracted a marriage with a person having a living spouse"* carries a
personal-law proviso and **obvious equality-law exposure**. It must **not** become a validation rule
without written legal sign-off. Until then the application captures the declaration but the engine
applies no automatic disqualification.

### 4.2 Two statutory SLA clocks

- **30-day minimum** advertisement window.
- **6-month cap** on the whole process, extendable **once to 12 months**, per **DoPT O.M.
  Misc.14017/15/2015-Estt.(RR)**.

Both require breach alerting; the extension requires a **recorded VC approval artefact** (M16, M23).

---

## 5. Source defects — verify against the PDF (DOC-007)

| # | Defect |
|---|---|
| 1 | **Executive Engineer (Civil) and (Electrical), Schedule-1:** *"Level 11 (after 5 years of service as Executive Engineer with level 11, an…"* — **sentence truncated**. A level-upgrade rule exists but its terms are unreadable |
| 2 | **Rule 32.2, row 2 (Ex-Servicemen):** the *"Extent of age relaxation"* cell is **blank** |
| 3 | **Security Inspector, Schedule-1:** **no age limit stated**, unlike every sibling Group-C post |
| 4 | **Rules 34.3 and 34.4** are substantive recruitment rules **mis-filed under the heading "34. VIGILANCE CLEARANCE"** — a drafting slip; treat them as free-standing |
| 5 | **Rule 2.19** defines **EWS** but the term is **never used again** anywhere in the instrument |
| 6 | Schedule-1 repeatedly uses *"reputed private Companies/corporate banks with a minimum annual turnover of at least Rs. 200/- Crores or more"* as an experience-equivalence test (Section Officer, Assistant, UDC) — **needs a verification procedure and evidence standard** |

---

## 6. What this means for the build

1. **`designations` is populated from §2.** All 58 cadres, each with group, pay level, method of recruitment with its percentage split, age limit, and selection method. This is the seed data for M35.
2. **Two merit models coexist and must not be conflated.** Teaching = interview alone (see `ugc-teaching-2018.md` §0.2). Non-teaching = Paper I + Paper II + 20% interview, subject to qualifying the skill test. `MeritStrategy` is bound to the designation, versioned, and enforced at the type level.
3. **The skill test is qualifying, never additive.** A common and expensive error to get wrong.
4. **Age is computed against the application closing date** (Rule 14) — never any other date.
5. **Rule 34.3 guards the advertisement builder:** a single sanctioned post in a cadre is direct-recruitment only.
6. **Rule 16 guards shortlisting:** 1:15, with the five named exemptions, and forced re-advertisement below 3 eligible applicants.

---

## 7. Traceability

| This document | Feeds |
|---|---|
| §2 the 58 cadres | `rules-catalogue.yaml` → M35 `designations` seed |
| §3.1 stages and marks | `rules-catalogue.yaml` → M21 `MeritStrategy` · M22 Examination Admin |
| §3.4 screening ratio | M21 Shortlisting |
| §3.5 committees | M19 Committee Workspace |
| §4 obligations | M05 declarations · M16 SLA clocks · M26 audit |
| §3.2, §4.1 | decision register OQ-008, OQ-012 |

---

## 8. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. All 58 cadres transcribed with group, level, method and age. Selection stages, screening ratio, committees and 14 cross-cutting obligations recorded. The Rule 11 III(g) vs 22.8 conflict and 6 source defects recorded unresolved. | Implementation team |
