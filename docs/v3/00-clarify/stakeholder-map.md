# Stakeholder Map & Decision Ownership

**Status:** live · **Owner:** project lead · **Created:** 2026-08-27

---

## 1. What this document is

Who decides what, and who has to be able to *do* what. It exists because the previous planning round
recorded no owners at all — `docs/open-questions.md` lists four ambiguities and assigns none of them
to anybody, which is why they were resolved by default assumption rather than by decision.

Two things live here:

- **§2 — Actors.** Every human role the system serves, what they do in it, and the evidence that
  they exist. These become the RBAC roles in M25, so getting the list right is a prerequisite for
  the authorisation matrix.
- **§3 — Decision ownership.** For each open question and each policy that must be ratified, the
  role that owns the answer. **An unowned question is not an open question; it is an assumption
  waiting to become a defect.**

---

## 2. Actors

### 2.1 External

| Actor | What they do | Volume | Evidence |
|---|---|---|---|
| **Candidate** | Registers once, builds a reusable profile, applies to one or more posts, pays, tracks status, downloads admit card and interview letter, raises grievances | **55,050 registered; 78,232 applications** | Legacy production counts |
| **Referee** | Named by the candidate (two per application); may be contacted for a reference | 2 × applications | FN-1 item 23, F-3 item 23 |
| **Forwarding authority** | Countersigns applications from candidates already in employment; stamps and dates the form | subset | FN-1 / F-3 closing block |
| **External subject expert** | Sits on Selection Committees; must not be connected with the institution | 2–3 per committee | UGC 2018 cl. 5.1 |

### 2.2 Internal — AMU

| Actor | What they do in the system | Evidence |
|---|---|---|
| **Registrar's Office** | Owns the Careers module today (`careers.*` tables sit under Registrar's Office in the ERP sidebar). Creates advertisements and posts, owns sanctioned strength, owns organisational master data (Campuses, Faculties, Departments, Centres, Employees, Designation Types) | ERP navigation; CRR Rules 8, 9.1 |
| **Registrar** | Appointing authority for **all permanent Group 'C'** posts. Applications are addressed to *"The Registrar"* | CRR Rule 6; Rule 11 III(e) |
| **Joint/Deputy Registrar, Selection Committee Section (Teaching)** | Receives and processes teaching-track dossiers | FN-1 submission venue; `post_types` rows 5, 6 |
| **Joint Registrar, Selection Committee (Non-Teaching)** | Receives and processes non-teaching, school-teacher, librarian and PE dossiers | F-3 submission venue; `post_types` rows 1, 2, 3, 4 |
| **Dean of Faculty** | **Chairs the Local Selection Committee.** Local (temporary, 6–12 month) appointments are administered in the Dean's office, not centrally | DR-010 |
| **Dean's office staff** | Create local advertisements and posts, scrutinise, and process appointments **for their own faculty only**. This is the second row-level authorisation scope in M25 — resolved by subtree over `organisational_units`, so a Dean of Arts reaches the Faculty of Arts and its child departments and nothing else. There are **13 Faculties** and **111 Departments** in the tree | DR-010; decision register §6.1 |
| **Directorate of School Education** | Receives school-teacher dossiers; owns school recruitment rules (which are **not in the repository** — DOC-005) | `post_types` row 7 |
| **Controller of Examinations** | Exam logistics: centres, roll numbers, admit cards, attendance sheets. The sign-in design is branded *"Office of the Controller of Examinations"* | `auth_signin_page_design.png`; M11, M22, M31 |
| **Finance Office** | Fee collection, gateway relationship, reconciliation of the ~29% failure tail | ERP sidebar; dashboard financial strip |
| **Vice-Chancellor** | Appointing authority for permanent Group 'B' and temporary Group 'A'/'B'. **Determines the application fee schedule.** Frames test syllabi, modalities and evaluation. May admit a late application on proof of timely posting. Chairs Professor and Senior Professor Selection Committees **in person — no nominee permitted** | CRR Rules 6, 11 III(c), 11 III(d), 11 III(h); UGC 2018 cl. 5.1 |
| **Executive Council** | Appointing authority for **all permanent Group 'A'**. Approves the panel of subject experts. Ratifies University policy — including the Table 2 ambiguities and any grievance regime, neither of which has UGC backing | CRR Rules 6, 19.6, 22.15(v) |
| **Scrutiny officer** | Verifies claims against uploaded documents; sets the scrutiny gate with a remark | `updateEligibility()`; M18 |
| **Screening Committee member** | Verifies Proforma grades; shortlists for interview | UGC 2018 cl. 5.2 |
| **Selection Committee member** | Confidential scoring and sign-off, **completed on the day of the meeting** | UGC 2018 cl. 5.1 VIII(c), 5.3 |
| **Departmental head** (Chairman / Director / Principal) | **Verifies Part-C claims.** This duty is repeated after every sub-table in FN-1 and is currently unmodelled | FN-1 Part C |
| **Chief Vigilance Officer** | May investigate at any stage | CRR Rule 34.2 |
| **Legal cell** | Owns the questions with litigation exposure: Rule 33.3, the Group B/C interview conflict, reservation applicability | OQ-008, OQ-012, OQ-013 |
| **Academic ERP owner** (`datalake.amuonline.ac.in`) | Runs the identity provider at `api.amu.ac.in/api/v1/auth/login` and masters the organisational hierarchy. **Counterparty for OQ-002 and OQ-003** | `datalake_developer_auth_docs.md` |
| **Portal administrator** | Master data, feature flags, job monitoring, audited impersonation | M25, M28 |

### 2.3 Note on the two-application split

The admin experience today is spread across **two separate applications** — configuration and CRUD
inside the Academic ERP (`datalake.amuonline.ac.in`), operations and reporting inside "Manage
Careers" (`mcareers.amuonline.ac.in`, with its own 8-item sidebar). Staff move between them to
complete a single task.

**No document in `docs/spec/` mentions this split.** Consolidating it into one application is a
primary goal of v1 and a change-management burden on the Registrar's Office and the Controller of
Examinations — both of whom need to be in the design review for M23, M31 and M32.

---

## 3. Decision ownership

**Rule: every row below has exactly one accountable owner.** Consulted parties advise; they do not
decide. If an owner cannot be identified, escalate to the project sponsor rather than proceeding.

### 3.1 Open questions

| ID | Question | **Accountable** | Consulted | Needed by |
|---|---|---|---|---|
| OQ-001 | Payment gateway vendor | **Finance Office** | Registrar's Office | Wave 5 |
| OQ-004 | Legacy cut-over and financial history | **Project sponsor** | Finance Office, Registrar's Office | Wave 10 |
| OQ-008 | Group B/C interview conflict | **Legal cell** | Registrar's Office, Executive Council | Wave 6 |
| OQ-009 | The six Table 2 ambiguities | **Executive Council** | Registrar's Office, Legal cell | Wave 7 |
| OQ-010 | Percentage ↔ CGPA conversion | **Registrar's Office** | Controller of Examinations | Wave 4 |
| OQ-012 | CRR Rule 33.3 as a validation rule | **Legal cell** | Executive Council | Wave 4 |
| OQ-013 | Reservation applicability at AMU | **Legal cell** | Registrar's Office, Executive Council | Wave 3 |
| **OQ-015** | Dean's-office role granularity — one OU-scoped role, or separate scrutiny and appointment roles? | **Registrar's Office** | Deans, portal admin | Wave 1 |

**Closed 2026-08-27:** OQ-002 (→ DR-008), OQ-003 (→ DR-009), OQ-005 (→ DR-006), OQ-006 (→ DR-007 +
DR-010), OQ-007 and OQ-011 (→ DR-011), OQ-014 and OQ-016 (→ decision register §6).

### 3.2 Documents to obtain

| ID | Document | **Accountable** |
|---|---|---|
| DOC-001 | AMU Ordinances (Executive) | **Registrar's Office** |
| DOC-002 | Post-2018 UGC amendment chain | **Registrar's Office** |
| DOC-003 | Reservation framework (CEI Act 2019, DoPT OMs, RPwD 2016, EWS OM) | **Registrar's Office** + Legal cell |
| DOC-004 | AMU's own CRR for medical, paramedical and other absent cadres | **Registrar's Office** |
| DOC-005 | AMU school-teacher recruitment rules | **Directorate of School Education** |
| DOC-006 | Part B2 and Part B3 form contents | **Registrar's Office** |
| DOC-007 | Re-extraction from the source Gazette PDFs | **Implementation team** |

### 3.3 Standing ownership of policy that the system encodes

These are not one-off questions. Each is a policy the system will apply repeatedly, and each has a
statutory owner who must be able to change it **without a code change**.

| Policy | Owner per the regulations | Where it lives in the system |
|---|---|---|
| Application fee schedule and concessions | **Vice-Chancellor** (CRR Rule 11 III(c)) | M08 fee rules, admin-editable |
| Test syllabi, modalities, evaluation | **Vice-Chancellor** (CRR Rule 11 III(h)) | M22 |
| Panel of approved subject experts | **Executive Council** (UGC 2018 cl. 5.1) | M19 |
| Sanctioned strength per cadre | **Executive Council**, with UGC approval to create, abolish or convert (CRR Rules 8, 9.1) | **M35** — `organisational_unit_designation.sanctioned_count` |
| Local (temporary) recruitment | **Dean of the Faculty**, in the Dean's office | M16, M18, scoped by M25 |
| Shortlisting ratio for teaching posts | **The university** (UGC 2018 Table 3A Note B) | M21, per post type |
| Reservation roster and relaxations | **GoI instructions**, adopted *mutatis mutandis* (CRR Rules 15.1, 32) | M17, as a configurable plug-in |
| Ruleset version bound to an advertisement | **Registrar's Office** at publish time | M16 → M20 |

**Two supremacy clauses constrain all of the above and must be stated in the design documents:**

> **CRR Rule 38:** *"In case any particular provision in these Rules is in conflict with any
> provision of the UGC Regulations/guidelines or Govt. of India Orders, the provisions of the UGC
> Regulations/guidelines or Govt. of India Orders shall prevail."*

> **CRR Rule 22.11:** eligibility and Selection Committee constitution for Registrar, Finance
> Officer, Controller of Examinations, Librarian, Deputy Registrar, Assistant Registrar and
> Assistant Librarian *"shall be governed strictly as per the UGC's guidelines/regulations"*, and
> future amendments are *"adopted mutatis-mutandis … in supersession of the existing provisions."*

Practically: **University-level configuration can never override a UGC rule.** The rules engine must
enforce that precedence rather than trusting administrators to respect it — which is another reason
rules are versioned data with citations rather than editable numbers.

---

## 4. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. 4 external and 16 internal actors identified; 13 open questions and 7 document acquisitions assigned owners; 7 standing policies mapped to their statutory owners. | Implementation team |
| 2026-08-27 | **Dean of Faculty and Dean's-office staff added as actors** (DR-010) — the second row-level authorisation scope. 7 questions closed, OQ-015 and OQ-016 added. Local recruitment added to the standing-policy map. | Implementation team |
