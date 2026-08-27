# Reservation, Roster and Relaxation

**Status of this document:** live · **Owner:** implementation team
**Accountable for the policy:** Legal cell + Registrar's Office (OQ-013, DOC-003)
**Created:** 2026-08-27

---

> ## ⚠️ SUPERSEDED IN PART BY DR-017 — read this first
>
> **AMU applies no post reservation.** No roster, no category-wise vacancy split, no backlog.
> **PwD is the only reservation-adjacent concept, and it operates as a fee exemption**, not a
> reserved vacancy. Confirmed by the sponsor and corroborated by the AMU Cadre Recruitment Rules:
> across **1,076,754 characters**, `reservation` appears twice, `roster` twice, `EWS` and
> `Ex-Serviceman` **zero** times.
>
> **What survives from this document:** §1's analysis of what the UGC instruments do and do not say,
> and the two 5% qualification relaxations — which remain live. **What does not:** §2's DOC-003
> acquisition list, §3.1–3.2's roster design, and §4's litigation contingency, all of which assumed a
> reservation regime that AMU does not operate.
>
> **The operative documents are now** [`../../00-clarify/amu-source-documents-findings.md`](../../00-clarify/amu-source-documents-findings.md) §3
> and [`../../02-plan/M17-relaxation-engine.md`](../../02-plan/M17-relaxation-engine.md).

## 0. Read this first

**Neither the UGC Regulations 2018 nor the Model CRR 2022 contains substantive reservation rules.**
Both incorporate Government of India instructions by reference and stop there.

What that means in practice:

> **We cannot write the reservation rules, because they are not in any document we hold.** What we
> can do — and what this document specifies — is build the **mechanism** so that when DOC-003 arrives
> and OQ-013 is answered, the policy is loaded as **versioned, effective-dated data** rather than
> written into code.

**This is not a deferral of difficulty. It is the correct architecture**, for a reason specific to
AMU: the applicability of SC/ST/OBC reservation at AMU is **live constitutional litigation**
(*AMU v. Naresh Agarwal*, 7-judge Constitution Bench, November 2024, remitted). A hard-coded roster
would be a liability. See §4.

---

## 1. What the two instruments actually say

### 1.1 UGC 2018

| Provision | Text | Clause |
|---|---|---|
| Reservation | Incorporated by reference to *"the norms of the Central Government or concerned State Government"* | cl. 6.0 III |
| Committee representation | A reserved-category academician joins the Selection Committee **(a)** where a candidate of that category has applied **and (b)** no member belongs to that category. The nominee *"shall be **one level above the cadre level of the applicant**"* and *"shall **ensure that the norms … are strictly followed during the selection process**"* | cl. 6.0 III, cl. 5.1 |
| **EWS** | **Does not appear anywhere in the 2018 Regulations** | — |
| Age limit | **Not stated anywhere** | — |
| Age relaxation | **Not stated anywhere** | — |
| Fee concession | **Not stated anywhere** | — |
| Roster | **Not stated anywhere** | — |

**The only substantive relaxations in UGC 2018 are the two 5% qualification relaxations** — see
`ugc-teaching-2018.md` §2. They are reproduced here for completeness because they are the *only*
category-linked rules the engine can implement today:

- **cl. 3.4 I** — 5% at Bachelor's **and** Master's for **SC / ST / OBC-NCL / Differently-abled**
  (five enumerated disability categories), for eligibility and for assessing good academic record.
  **Based only on qualifying marks, excluding any grace-mark procedure.**
- **cl. 3.5** — 5% (55% → 50%) for **PhD holders** whose Master's was obtained **before
  19 September 1991**.
- **These are separate grounds. Stacking to 45% is not authorised.**
- Tables 3A/3B carry an independent carve-out on the **post-graduation row only**:
  *"55% (50% in case of SC/ST/OBC (non-creamy layer)/PWD) to less than 60% = 20"*. It does **not**
  extend to the graduation row.

### 1.2 Model CRR 2022

| Provision | Text | Rule |
|---|---|---|
| Reservation | *"instructions issued by the Government of India / UGC … shall apply **mutatis mutandis** with due approval of the Executive Council"* | 3.2, 15.1, 31, 38 |
| Fee concession | *"**Concessions in application/processing fee, wherever provided, shall be as per Govt. of India norms.**"* The fee schedule itself is set by the **Vice-Chancellor** | 11 III(c) |
| Test-mark relaxation | *"**Relaxation in qualifying marks** or any other relaxation in the test, if any for the reserved categories shall be extended **as per the Govt. of India guidelines**"* | 11 III(i) |
| Committee representation | A **reserved-category representative** sits on every Selection Committee, DPC and DCC, and is part of the quorum. **A minority representative is associated only where the number of vacancies is 10 or more** | Schedule-II |
| **EWS** | **Defined at Rule 2.19 — and then never used again anywhere in the instrument** | 2.19 |
| **Ex-Servicemen** | Rule 32.2 has a row for Ex-Servicemen, and the *"Extent of age relaxation"* cell is **literally blank in the source** | 32.2 |
| Age limits | Stated **per cadre** in Schedule-1 (32 / 35 / 40 / 45 / 50 / 56 / 57) — see `ugc-crr-non-teaching-2022.md` §2 | Sch-1 |
| Age reference date | **The closing date of the application** | 14 |
| Roster | **Not stated anywhere** | — |

### 1.3 The compliance matrix in the repository

`docs/research/compliance-matrix.md` — **the entire file** — covers only:

- OWASP Top 10
- WCAG 2.2 AA
- GIGW compliance

**There is no reservation, RTI, DPDP or record-retention content in it at all.**

---

## 2. What is missing — DOC-003

Every item below is required before the roster engine (M17) can be populated. **None of it exists in
the repository.**

| # | Instrument | What it supplies |
|---|---|---|
| 1 | **Central Educational Institutions (Reservation in Teachers' Cadre) Act, 2019** and its Rules | The statutory basis for teaching-cadre reservation, and **the unit of the roster** — the Act makes the *institution* the unit rather than the department, which is the single most consequential parameter in the whole roster design |
| 2 | **DoPT reservation OMs** | Percentages, backlog and carry-forward, interchange, de-reservation |
| 3 | **RPwD Act 2016** and identification-of-posts notifications | **4% horizontal** PwBD reservation, benchmark-disability definitions, and which posts are identified for which disability |
| 4 | **EWS OM** (DoPT No. 36039/1/2019-Estt(Res) and successors) | 10% EWS, income and asset ceiling, certificate format and validity |
| 5 | **OBC creamy-layer OM** | Income ceiling, certificate validity period, the *"as on"* date |
| 6 | **Ex-Servicemen orders** | The age relaxation the CRR leaves blank, and the reckoning method |
| 7 | **Age-relaxation table** | SC/ST +5, OBC +3, PwBD +10/+13/+15, ESM — *the customary figures, which must be sourced not assumed* |
| 8 | **Certificate formats** | SC/ST, OBC-NCL, EWS, PwBD, ESM — the prescribed proformas and issuing authorities |

> ⚠️ **The figures in row 7 are the ones everyone "knows".** They are exactly the class of value that
> produced the fabricated `ugc-rules.yaml`. **No reservation percentage or age relaxation enters
> `rules-catalogue.yaml` without a citation to a sourced instrument.**

---

## 3. What we build now

The mechanism, with the policy left as data.

### 3.1 Reservation is a versioned, effective-dated policy plug-in

Same contract as the rules catalogue: a `reservation_policy` version is **resolved at advertisement
publish time and frozen onto the advertisement**. An advertisement published under the 2026 policy
is evaluated under the 2026 policy for ever, whatever happens later in litigation or in a DoPT
circular.

Each policy version carries:

- **Vertical categories** with percentages (UR / SC / ST / OBC-NCL / EWS), each citing its source.
- **Horizontal categories** with percentages (PwBD by disability type, ESM, Women where applicable) —
  horizontal reservation cuts *across* the vertical categories and must be modelled as a distinct
  dimension, not as more vertical categories. Getting this wrong is the classic roster defect.
- **Age relaxations** per category, in years, with the reference date fixed to the **application
  closing date** (CRR Rule 14).
- **Fee concessions** per category.
- **Qualification relaxations** — the two 5% grounds already implementable from UGC 2018.
- **Roster parameters** — roster type (100-point / 200-point / 13-point), **roster unit**, backlog
  and carry-forward rules, interchange rules.

### 3.2 The roster register

`M17` maintains a roster register per **roster unit** (per DOC-003 row 1 — institution or department,
**not yet decided**). Each register point records: point number, category, reserved/unreserved,
filled/vacant, the post that filled it, and the sanction reference.

It reads `organisational_unit_designation.sanctioned_count` (M35) as its denominator. **Until DOC-003
arrives, the register is built and left unpopulated** — the schema is correct, the data is absent,
and the advertisement builder simply does not enforce a roster constraint.

### 3.3 The candidate side works today

None of the above blocks the application flow. What M04/M05 must capture from day one:

| Field | Why |
|---|---|
| Vertical category + certificate + issue date + issuing authority | Every category claim needs evidence |
| **OBC-NCL creamy-layer certificate validity date** | These expire; a stale certificate is a rejection ground |
| **EWS** as a first-class category | Absent from UGC 2018, present in the 2025 draft (cl. 3.4), and required by DoPT regardless |
| **PwBD type (all five UGC 2018 categories) and percentage** | F-3 omits disability entirely — a legacy defect not to reproduce |
| Disability certificate issuing authority | |
| **Ex-Serviceman** status and discharge details | The CRR has a row for it with a blank relaxation cell |
| Whether the candidate is claiming relaxation, and on which ground | The two 5% grounds are separate and non-stackable |

**Design rule:** capture the claim and its evidence now; apply the policy when the policy exists.
A category claim recorded without evidence cannot be retro-verified, and re-opening submitted
applications to add a field is exactly what DR-011's immutability model forbids.

---

## 4. The AMU-specific overlay — OQ-013

**AMU's minority-institution status, and with it the applicability of SC/ST/OBC reservation, is
live litigation.** *AMU v. Naresh Agarwal* was decided by a **7-judge Constitution Bench in November
2024** and **remitted** — the question of AMU's minority character was answered in principle and the
application to AMU sent back for determination on the criteria laid down.

**Consequences for the design, all of which the plug-in architecture already handles:**

1. The applicable policy **may differ from the standard central-university policy**, and **may
   change** during the life of the system.
2. It may differ **between cadres** or between **General and Local** recruitment.
3. Advertisements published under one policy **must not be retroactively re-evaluated** under another
   — which is precisely why the policy version is frozen at publish (§3.1).

**Therefore: no reservation rule is ever hard-coded, and no default is assumed.** Until Legal
answers OQ-013, the roster engine ships with **no active policy version**, and the advertisement
builder records category-wise vacancy breakdown as **administrator-entered data** rather than as a
roster-derived computation.

This is a deliberate, stated limitation — not an omission. It is recorded in `scope-boundary.md`
as **M17 v1-partial: the mechanism ships, the policy data lands when DOC-003 and OQ-013 close.**

---

## 5. Related gaps in the same territory

Recorded here because they surface with reservation and share the same owner.

| Gap | Status |
|---|---|
| **No record-retention schedule in either instrument** | **Closed by DR-011** — electronic retention is indefinite; hard copies are weeded at five years for unsuccessful candidates. The DPDP position this creates must be *argued* in `../security/`, not assumed |
| **No RTI reference anywhere** in either instrument | Open. The portal handles data that will attract RTI requests — category-wise counts, roster status, shortlists |
| **No DPDP 2023 treatment anywhere**, despite the portal handling caste, religion, disability type and percentage, marital status, spouse name, identity marks, **biometric thumb impressions**, criminal-record declarations and medical fitness data | Open — `../security/` must author it |
| **No candidate-facing transparency, grievance or appeal regime** in either instrument | The only finality clauses run the *other* way (CRR Rules 19.6, 22.15(v)). The features that beat CU-Chayan must be sanctioned by the **Executive Council as University policy** and must **not** be presented as UGC compliance. And UGC 2018 cl. 5.3 means the only compatible slot is a **pre-interview, screening-stage** window |
| **CRR Rule 33.3** — bar on marrying a person with a living spouse | OQ-012. Obvious equality-law exposure; **no automatic disqualification without written legal sign-off** |

---

## 6. Traceability

| This document | Feeds |
|---|---|
| §1 the two 5% relaxations | `rules-catalogue.yaml` → `TCH-REL-01`, `TCH-REL-02` (implementable today) |
| §3.1 policy plug-in | M17 Reservation & Roster Engine |
| §3.2 roster register | M17 · M35 sanctioned strength |
| §3.3 candidate capture | M04 · M05 |
| §4 AMU overlay | decision register OQ-013 |
| §2 missing instruments | decision register DOC-003 |
| §5 | `../security/` · M15 Grievance Desk |

---

## 7. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Recorded that **neither instrument contains substantive reservation rules**; specified the versioned effective-dated policy plug-in and the candidate-side capture that proceeds without it; listed the 8 missing instruments (DOC-003) and the AMU litigation overlay (OQ-013). | Implementation team |
