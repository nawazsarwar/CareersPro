# AMU Cadre Recruitment Rules and Advertisements — findings

**Status:** obtained and analysed · **Date:** 2026-08-27
**Closes:** DOC-004 (non-teaching cadres) · DOC-005 (school teachers) · **OQ-013** (reservation) ·
**OQ-001** (payment gateway) · **OQ-018** (shortlisting ratio)

| Document | File | Extracted |
|---|---|---|
| **AMU Cadre Recruitment Rules** | `docs/AMU_Cadre_Recruitment_Rules.pdf` (14.2 MB) | `docs/research/amu_crr.txt` — **1,076,754 chars** |
| Advt. **1/2025/NT** dated 05.04.2025 — Registrar, Finance Officer, Controller of Examinations | `docs/advertisements/adv_officers_2.pdf` | `adv_officers_2.txt` |
| Advt. — officers (companion) | `docs/advertisements/adv_officers_1.pdf` | ⚠ **no text layer** — signature blob only; needs OCR or a re-issue |
| Advt. **1/2024/NT** dated 07.11.2024 — University School Teachers | `docs/advertisements/adv_school_1.pdf` | `adv_school_1.txt` |
| Advt. — school teachers (companion) | `docs/advertisements/adv_school_2.pdf` | `adv_school_2.txt` |

---

## 1. The AMU CRR validates the M35 model

**Structure:** General Rules (pages i–xviii) + **Schedule-1 (pages 1–358)**, organised as
**Common Pool of Non-Teaching Posts** (pages 1–18) followed by **every post, department by
department**: Faculty of Agriculture → Dept of Agricultural Economics, Dept of Agricultural
Microbiology…; Faculty of Medicine → 24 departments; **JNMC Hospital (pages 137–164), College of
Nursing (165), Modern Trauma Centre (166–175), Dr. Ziauddin Ahmad Dental College (176–178)**.

**Two consequences:**

1. **This is exactly `organisational_unit_designation`.** AMU's recruitment rules are keyed to
   *organisational unit × post*, which is the sanctioned-strength register in **M35**. The design was
   right, and the seed data now has a real source.
2. **DOC-004 is closed.** The medical, nursing, paramedical, trauma-centre and dental cadres that
   the UGC Model CRR omits **are all here**, under their own organisational units.

**Schedule-1 has 14 columns per post**, including — critically — **the Selection Committee
composition inline**. Example, Senior Curator (Group B, Level 6):

> 01 post · Group **B** · **L-6** · Selection Post · Upper age limit **40 years** *(relaxable for
> candidates already working in Central Universities / Central Govt. Offices / Central Govt. funded
> Autonomous Institutions)* · **Essential:** M.A. Medieval Indian History / Ancient Indian History
> and Archaeology / M.Sc. Museology **+ 2 years** experience in recording and preservation of
> archaeological artefacts · Probation **one year** · **100% by direct recruitment** ·
> **Selection Committee:** Pro-Vice-Chancellor (Chairman) *(Registrar chairs in the PVC's absence)* ·
> Dean of the Faculty · Chairman of the Department · Registrar or nominee · Vice-Chancellor's
> nominee · **two experts not in the service of the University**, nominated by the VC from the
> EC-approved panel

---

## 2. AMU's General Rules override the UGC model on three counts

**These are AMU's own rules and they govern non-teaching recruitment here.** Where they differ from
the UGC Model CRR 2022, the differences are deliberate and must be encoded.

| Rule | AMU CRR | UGC Model CRR 2022 |
|---|---|---|
| **Shortlisting ratio** (Rule 15) | **1:5** | 1:15 |
| **Minimum eligible applicants** (Rule 15) | **2** | 3 |
| **Re-advertisement on shortfall** (Rule 15) | **"at least once more"**, then proceed with selection | "at least twice" |
| Exempt posts | **Same five** — Registrar, Finance Officer, Controller of Examinations, Librarian, Director of Physical Education | same |

**Rule 15 verbatim:**

> *"Except for the posts of Registrar, Finance Officer, Controller of Examination, Librarian,
> Director of Physical Education, it must be ensured that the **ratio of the number of vacant posts
> to be filled and the number of candidates to be called for Interview does not exceed 1:5**."*
>
> *"If **minimum two eligible applicants** are not available for any vacancy to appear for the
> written test/interview, the post shall be **re-advertised at-least once more** after which the
> University shall proceed with the selection."*

**Scrutiny Committee composition (Rule 15)** — new, and it lands in M18:

- **A.** Head of the concerned Department/Office (**Chairman**)
- **B.** Two members from the concerned Department/Office
- **C.** One member nominated by the Vice-Chancellor **from outside the Department**
- **Members at B and C must not be lower in Pay Level than the post advertised.** Where none is
  available in the Department, the VC nominates from any other Department.
- The VC may constitute a **separate Scrutiny Committee for Common Pool posts**.
- The Committee **may recommend a candidate conditionally**; the conditions must be met **before**
  the written/skill test or interview, and the candidature stays **provisional** until then.

**Rule 16 — conflict of interest.** Selection and DPC constitution is **in Schedule-1 per post**. A
person is disqualified if *"related to any candidate or there would be any conflict of interest"*,
and **the University obtains a written undertaking from every member before the selection process
commences**. That is a workflow step in M19, not a checkbox.

**Other General Rules captured:** Rule **2.15** defines PwD and **PwBD** per the **RPwD Act 2016** ·
Rule **2.17** — Selection Committee per Act/Statutes/Ordinances/CRR, Screening Committee as approved
by the EC · Rule **14.3** — upper age limit relaxable by **up to 5 years** for candidates with
**≥3 years regular service** in Government departments, statutory or autonomous bodies, universities
or PSUs · Rule **14.5** — **no age limit for DPC posts** · Rules **22.5–22.7** — DPC procedure,
**posts reserved for PwDs**, extended zone of consideration for PwDs, and the **departmental test is
qualifying only**.

---

## 3. Reservation — the position, stated precisely

**Sponsor's direction:** *"No reservations are applicable for the appointments in AMU except for a
person with disability."*

**Corroborated by the source.** Across **1,076,754 characters** of the AMU CRR:
`reservation` appears **2 times** · `roster` **2** · `EWS` **0** · `Ex-Serviceman` **0**. Neither
occurrence establishes a category reservation.

> ⚠️ **The distinction that must not be lost: reservation ≠ relaxation.**
>
> **No posts are reserved by category** — there is no roster, no category-wise vacancy split, no
> backlog. **But category-linked relaxations do apply**, and the advertisements prove it.

**Age relaxation, from Advt. 1/2024/NT (school posts) — real figures:**

| Ground | Relaxation |
|---|---|
| Employee of AMU Schools | **No upper age bar** |
| SC/ST | **+5 years** |
| OBC | **+3 years** |
| Domiciled in J&K, 01.01.1980 – 31.12.1989 | **+5 years** |
| SC/ST serving as a Government employee | **+10 years** |
| OBC serving as a Government employee | **+8 years** *(employer certificate required, as at the advertisement date)* |
| **Persons with Disability** | **+10 years** |
| **Women**, including SC/ST/OBC | **+10 years** |
| Ex-Serviceman | **As per Govt. of India Rules** |
| *(CRR Rule 14.3)* ≥3 years regular service in Govt/statutory/autonomous/university/PSU | **+5 years** |

**Qualification relaxation:** *"for working candidates of AMU Schools will be given as per AMU
Rules."*

**Fee concession:** **PwD candidates are exempt from the processing fee**, on uploading a valid
Certificate of Disability on the prescribed proforma (Appendix-I) issued by a Medical/Competent
Authority under the RPwD Act.

**So the system needs:** a **relaxation engine** (age, qualification, fee) driven by declared
category and evidence — and **no roster engine**. See DR-017.

---

## 4. Fee and submission practice, from the advertisements

| Rule | Value |
|---|---|
| Processing fee | **₹500 per application form** |
| Fee exemption | **PwD only**, with a valid certificate on the prescribed proforma |
| Refunds | **"Application fee once received shall not be refunded"** |
| One form per post | *"Separate Application Forms are to be filled for each Post detailed under a different Serial Number"* |
| Mode | **Online only**, at the Careers Portal |
| Nationality | Indian nationals, **including OCI cardholders** under s.7A of the Citizenship Act 1955 |

**The hard-copy window is per advertisement, not fixed:**

| Advertisement | Online closes | Hard copy closes | Gap |
|---|---|---|---|
| 1/2024/NT (schools) | 07.12.2024, 23:59 | 17.12.2024, **17:00** | **10 days** |
| 1/2025/NT (officers) | 05.05.2025, 23:59 | 21.05.2025, **17:00** | **16 days** |

*"The Hard Copies will not be received after 05:00 P.M."* and *"The University will not be
responsible for any Postal delays."* → `posts.hardcopy_closing_date` is a **required, configurable
field**, defaulting from the advertisement, with a **17:00 cut-off**, distinct from the online
closing time of 23:59.

---

## 5. The three officers — Advt. 1/2025/NT

Registrar, Finance Officer and Controller of Examinations, **₹500 fee, Pay Level 14**:

- **Method:** *"Direct/Deputation for a tenure of **five years or till attaining the age of
  superannuation i.e. 62 years, whichever is earlier**."* Deputation requires the same
  qualifications while holding **Pay Level 12**.
- Appointed **on the recommendation of a Selection Committee constituted for the purpose**, on terms
  prescribed by the Ordinances, renewable for a similar term **by the Executive Council**.
- *"Notwithstanding attaining the age of sixty two years, he shall continue in office until his
  successor is appointed… or for a period of **one year**, whichever is earlier."*
- **Controller of Examinations essential experience:** 15 years as Assistant Professor at Academic
  Level 11+, **or** 8 years at Level 12+ including as Associate Professor with educational
  administration experience, **or** comparable research/HE experience, **or** 15 years'
  administrative experience of which **8 years as Deputy Registrar or equivalent**.

> ⚠️ **The Master's-degree line is garbled in extraction** — it reads *"1. an equivalent grade in a
> point scale wherever grading system is followed"* with the preceding requirement lost. **Verify
> against the PDF before encoding.** DOC-007 applies.

**DOC-008 is narrowed, not closed.** The advertisement confirms *"a Selection Committee constituted
for the purpose"* but does not give its **composition**. That remains in the **Statutes**.

---

## 6. Register updates

| Item | Change |
|---|---|
| **DOC-004** | **Closed** — AMU CRR obtained; medical, nursing, paramedical and dental cadres all present |
| **DOC-005** | **Closed for practice, open for rules** — the school advertisements give posts, qualifications, age relaxation and fee; the underlying service rules are not in the CRR index |
| **DOC-003** | **Superseded by DR-017** — no reservation framework is needed; a relaxation table is |
| **DOC-008** | **Still open**, narrowed to Selection Committee **composition** for the three officers |
| **OQ-013** | **Closed by DR-017** |
| **OQ-001** | **Closed by DR-018** |
| **OQ-018** | **Closed by DR-019** |
| **DOC-009** *(new)* | `adv_officers_1.pdf` has **no text layer** — OCR or re-issue required |

---

## 7. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. AMU CRR (1.07M chars) and four advertisements obtained and analysed. Rule 15 (1:5 ratio, 2-applicant minimum, Scrutiny Committee) and Rule 16 (conflict-of-interest undertaking) captured; the reservation-vs-relaxation distinction established with real figures; fee and hard-copy practice recorded. | Implementation team |
