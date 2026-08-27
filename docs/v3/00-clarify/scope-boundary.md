# Scope Boundary & Module Catalogue

**Status:** live · **Owner:** project lead · **Created:** 2026-08-27
**Supersedes:** `docs/v2-archive/MODULES.md` (29 modules, no status column, no owner, no scope boundary, and it
omits five modules that exist in the production system being replaced)

---

## 1. What this document is

The authoritative list of **what CareersPro v2 will and will not do**, module by module, with the
reason for each exclusion. It also fixes the **canonical module IDs** (`M01`–`M34`) used by every
other document in `docs/v3/`.

**Module IDs `M01`–`M29` are numbered to match `docs/v2-archive/MODULES.md` exactly**, so the old catalogue and
the new one can be cross-read. `M30`–`M35` are additions: modules that exist in the live system and
in the reference screenshots, or that the domain requires, but were **missing from the previous
catalogue entirely**.

### 1.1 How to use it

- Building something? Find its module row. The **Scope** column tells you whether it is in v1.
- Asked to add a feature? If it is not in this table, it is not in scope. Add a row with a reason
  and get it signed off before writing code — do not widen scope by implication.
- Every module row links to its build spec in `docs/v3/02-plan/` once that spec exists.

### 1.2 Scope legend

| Scope | Meaning |
|---|---|
| **v1** | Ships in the first release. Has, or will have, a build spec in `docs/v3/02-plan/`. |
| **v1-partial** | A defined subset ships in v1. The row states exactly which subset and which part is deferred. |
| **v2** | Deliberately deferred. **Not** dropped — the reason and the trigger to revisit are recorded. |
| **out** | Explicitly out of scope. The reason is recorded so it is not re-litigated. |

---

## 2. The boundary in one paragraph

**In scope:** the complete recruitment lifecycle for AMU's five existing tracks — advertisement,
candidate registration and reusable profile, track-specific application, online fee payment,
scrutiny with three independent eligibility gates, UGC research-score computation, shortlisting,
written test and interview logistics, merit lists, and the statutory documents and reports that
surround them — as a **single application**, replacing the current split across
`datalake.amuonline.ac.in` and `mcareers.amuonline.ac.in`.

**Out of scope:** anything after the appointment offer (joining formalities, service records,
payroll, promotions) — those belong to the Academic ERP. Also out: CAS/internal promotion,
computer-based test delivery, and the invented CU-Chayan push integration.

---

## 3. Applicant-facing modules

| ID | Module | Scope | Notes |
|---|---|---|---|
| **M01** | **Public Vacancy Listing** | **v1** | Filters matching or exceeding CU-Chayan's nine: post, department, category, pay level, location, dates, post type, track, subject. Full-text search. **Saved searches with alerts deferred to v2** — an email-subscription surface with its own consent and unsubscribe obligations. |
| **M02** | **Advertisement Detail** | **v1** | Notification view, eligibility summary, reservation breakdown, PDF download, child-post list. Must handle **corrigenda and date extensions** as first-class objects, not edits — the legacy slug carries a unix-timestamp suffix as a de-dup hack, which this replaces. |
| **M03** | **Registration & Profile** | **v1** | Apply-once-reuse-everywhere profile. Username **or** email login (see OQ-002). Email verification that terminates — the current system logs every new user out permanently. Mobile OTP. **TOTP in v1** (`MEMORY.md` mandates it; the previous round claimed it and shipped nothing). |
| **M04** | **Editable Academic & Work History** | **v1** | Qualifications, employment, institutions attended, eligibility tests, foreign visits, referees. Editable until submission, then **snapshot-locked per application** — the legacy system's irreversible lock at payment, with no rectification path, is its single loudest complaint. |
| **M05** | **Application Wizard** | **v1** | Resumable, auto-saving, five track variants (DR-007). Conditional on post type. Dynamic eligibility pre-check **before** payment — the legacy system evaluates age and experience at the payment deadline, so ineligible candidates pay first. |
| **M06** | **Publication & Research Claims** | **v1** | The 13 evidence sub-tables of FN-1 Part B (~70 columns). **DOI/CrossRef lookup and UGC-CARE verification included** — CU-Chayan's documented worst data-entry complaint is entering 20+ publications by hand with no import and no verification. |
| **M07** | **Document Vault** | **v1-partial** | Upload, virus scan, image cropping to the statutory specs (Photo 350×450 px 10–100 KB ratio 7:9; Signature and Thumb Impression 300×150 px ratio 6:3), inline viewer, self-attestation. **Provenance modelled from the first migration** (`self_attested` / `digilocker_verified` / `office_verified`) though only the first is reachable in v1 — see DR-005. **OCR checks deferred to v2.** |
| **M08** | **Fee & Payment** | **v1** | Category-based fees, exemptions, driver-based gateway (DR-004), **idempotent order creation and MIS-file reconciliation**, receipts, refunds. Designed against the observed ~29% failure ratio. |
| **M09** | **Application PDF Generation** | **v1** | Statutory print format per `post_types.pdf_template` (`fn1`, `fn2`, `fn3_general_nt`, `fn3_general_st`, `fn3_phe`), with QR verification. **Digital signature deferred to v2** — needs a DSC/eSign decision. |
| **M10** | **Applicant Dashboard** | **v1** | Stage tracking, timeline, action items, **deficiency rectification within a time-bound window** (see M18). |
| **M11** | **Admit Card & Centre Allotment** | **v1** | Roll-number generation (per-post sequence, not the current free-text integer), centre master with capacity, clash-free allocation honouring candidate preference, download windows enforced by `posts.admit_card_opening_date` / `closing_date`. |
| **M12** | **Examination Delivery (CBT)** | **v2** | Computer-based test engine, secure question delivery, answer-key publication, objection handling. **Deferred:** it is a distinct product with its own security model and invigilation requirements, and it appeared in the previous catalogue but in **no roadmap**. v1 supports offline written tests via M22. |
| **M13** | **Interview Scheduling** | **v1-partial** | Slot management, interview letters, venue. **Video-interview integration and travel-allowance claims deferred to v2.** |
| **M14** | **Results & Merit Lists** | **v1** | Merit and waitlists, category-wise lists, offer generation. **Two merit models** — teaching (interview only) and non-teaching (written + 20% interview) — as a versioned `MeritStrategy` bound to the post type. **Joining formalities are out of scope** (Academic ERP owns them). |
| **M15** | **Grievance Desk** | **v1** | SLA-tracked grievance register with a named appellate authority. **Constraint:** UGC 2018 cl. 5.1 VIII(c) / 5.3 require selection to complete on the day of the committee meeting, so a **post-committee window is impossible**; the objection window sits at the **screening stage** (M18). Requires Executive Council sanction as University policy — it has no UGC backing (see decision register §3). |

---

## 4. Administrative modules

| ID | Module | Scope | Notes |
|---|---|---|---|
| **M16** | **Advertisement Builder** | **v1** | Advertisement → child posts, each a **Designation in an Organisational Unit** (M35), linked to sanctioned strength and roster. Posts carry `appointment_nature` (general \| local) and, for local, `tenure_months` (DR-010). **OU snapshot frozen at publish** so a later rename cannot rewrite a published advertisement (DR-009). Corrigenda and date extensions as first-class objects. **30-day minimum advertisement window enforced** with breach alerting. |
| **M17** | **Relaxation Engine** *(was Reservation & Roster)* | **v1** | **DR-017: no posts are reserved at AMU — no roster is built.** Age, qualification and fee relaxations only, versioned and effective-dated, frozen at advertisement publish. Seeded from Advt. 1/2024/NT and AMU CRR Rule 14.3. **PwD is the only fee exemption.** |
| **M18** | **Scrutiny Workbench** | **v1** | Queue-based, side-by-side claim-vs-document verification. **Three independent eligibility gates** — scrutiny, written test, interview — each with its own remark, reviewer and timestamp, with the **active gate set driven by `post_types.default_selection_method`**. Deficiency raising with a time-bound candidate rectification window. |
| **M19** | **Committee Workspace** | **v1** | Confidential scoring, digital sign-off, quorum enforcement per cadre. Blocked on DOC-001 for the composition of the Registrar / Finance Officer / Controller of Examinations committees. |
| **M20** | **Scoring Engine** | **v1** | Versioned, effective-dated rules (DR-006). Per-line explainable scores citing rule ids. Admin authoring UI. Sandbox simulation against historical snapshots. |
| **M21** | **Shortlisting & Cut-offs** | **v1** | Ranked and category-wise lists. Enforces the **1:15** screening ratio (CRR Rule 16) and the **re-advertise-twice** rule when fewer than three eligible applicants. **Hard invariant: a teaching screening score must never enter a teaching merit list** (UGC 2018 cl. 4.1 I Note) — enforced at the type level. |
| **M22** | **Examination Admin** | **v1** | Centre master, capacity, attendance tracking, incident logging. |
| **M23** | **Analytics & Reporting** | **v1** | Funnel, turnaround times, category compliance, statutory exports. Dashboard per the reference screenshot: four KPI tiles, 12-month submitted-vs-paid trend, goal completions, financial strip, latest applications and members. |
| **M24** | **Master Data Management** | **v1** | **Including the tables the previous catalogue named but never created.** Chief among them `organisational_units` + `organisational_unit_types` — **local and autonomous per DR-009**, a self-referential tree with a materialised `path`, imported once from Data Lake (301 units, 29 types) and never read from it at runtime. Plus pay levels, subjects, degrees, boards, categories, castes, religions, marital statuses, disability types, qualification levels, states, districts, PIN codes — **all seeded**, unlike today where every lookup table is empty after seeding. |
| **M25** | **RBAC & Impersonation** | **v1** | Fine-grained permissions with **two orthogonal row-level scopes**: **ownership** (a candidate reaches only their own records — the defect that currently lets any authenticated candidate modify any other's dossier) and **organisational unit** (a Dean's-office user reaches only their own faculty's local recruitment, resolved by subtree — DR-010). Delegation. **Audited impersonation** with one-time expiring tokens. |
| **M26** | **Audit & Traceability** | **v1** | **Genuinely hash-chained** append-only log (`hash`, `previous_hash`, sequence, no `updated_at`) covering state changes, scoring overrides and document access — including the sensitive models the current `Auditable` trait omits (`User`, `Role`, `Permission`). |
| **M27** | **RTI / Legal Support** | **v1** | Point-in-time reconstruction from immutable application snapshots. This is the capability ADR-001 used to justify the relational choice, and it is currently unimplementable — there is no snapshot table. |
| **M28** | **System Administration** | **v1-partial** | Theme manager, feature flags, background-job monitoring. **Backup controls deferred to v2** — infrastructure concern, and the legacy habit of `CREATE TABLE … SELECT` backups inside the production schema (215,946 orphan rows) is what this must replace, not reproduce. |
| **M29** | **Public API / Integration Layer** | **v1-partial** | A **real, documented, OpenAPI 3.1** API for the modules that need one, replacing the current 26 auto-generated CRUD endpoints that cannot authenticate (no sanctum guard, no `personal_access_tokens` table, no rate limiting). **The CU-Chayan push integration specified in `docs/v2-archive/spec/api.md` is `out`** — no ingestion endpoint, credentials or data contract exists in any document; it was invented. |

---

## 5. Modules missing from the previous catalogue

These exist in the live system and in the reference screenshots. `docs/v2-archive/MODULES.md` omits all five.

| ID | Module | Scope | Why it must exist |
|---|---|---|---|
| **M30** | **Mass Communication Engine** | **v1** | `docs/Intro.md` explicitly requires it: *"a mass emailing service where the administrators can select the post of a particular post or of a particular advertisement and send bulk emails."* Templated email/SMS with variable substitution, segment targeting by advertisement or post, delivery logs. Distinct from M15. |
| **M31** | **Attendance Sheet Generator** | **v1** | A live admin screen. Select advertisement → post → *"Has Roll No been Uploaded/Generated?"* → report type (`ALL` / `Scrutiny Eligible Only` / `Interview Eligible Only`) → with photo? → with barcode? → generate printable PDF register. **Depends on the three eligibility gates (M18); unbuildable on the collapsed schema.** |
| **M32** | **Bulk Document Generator** | **v1** | A live admin screen. Select advertisement → post → document type (`Admit Card` / `Interview Letter`) → filter (`All Applicants` / `Eligible Only` / `Interview Eligible Only`) → async PDF compilation with per-candidate details, schedule, venue, reporting instructions and QR stamp. Templates from `post_types.admit_card_template` / `.interview_letter_template`. |
| **M33** | **Application Receipt & Hardcopy Tracking** | **v1-partial** | A live admin sidebar section. Tracks physical dossier receipt against `hardcopy_received`. **Scope depends on OQ-007** — if hard-copy submission is dropped, this reduces to a migration-only concern. |
| **M34** | **Eligibility Decision Gates** | **v1** | Not a screen but the **core domain object** the whole operational half depends on: three independent gates, each `1` / `0` / `NULL` (eligible / rejected / pending) with remark, reviewer and timestamp, the active set driven by post type. Called out separately because collapsing it — as the current schema does — silently breaks M31, M32, and the pipeline widgets in M23. |
| **M35** | **Designation & Sanctioned Strength Register** | **v1** | The missing spine (DR-012). `designations` is a **fully local** master holding what makes a post a post: cadre, group (A/B/C), pay level and range, essential and desirable qualifications, age criteria with `age_reference` (CRR Rule 14 — the **closing date of the application**), experience rules, selection method, method of recruitment, and the governing ruleset version. Plus `organisational_unit_designation` (OU × designation × `sanctioned_count`, sanction order reference, roster breakup) — **the sanctioned-strength register required by CRR Rules 8 and 9.1, which exists in no system today.** This is what lets M20 bind rules to a stable entity instead of a free-text post title, and what finally gives M16's *"post creation linked to sanctioned strength"* something to link to. Seeded from Data Lake's 346 designation **names only** — that table carries no criteria whatsoever (see decision register §6.1). |

---

## 6. Explicitly out of scope

| Item | Why | Where it belongs |
|---|---|---|
| **Career Advancement Scheme (CAS) / internal promotion** | Different workflow — no advertisement, no fee, internal screening, and a distinct Screening-cum-Evaluation Committee under UGC 2018 cl. 5.1 X. Would need a sixth application variant and the CAS thresholds (70 for L12→L13A, 110 for L13A→L14). | v2 |
| **Joining formalities, service records, payroll, seniority** | Post-appointment. The Academic ERP already owns Employees, Designations and Pay Hub. | Academic ERP |
| **CU-Chayan push integration** | `docs/v2-archive/spec/api.md` specifies `POST /api/v2/integrations/cu-chayan/push`. **No such endpoint, credential set or data contract exists in any document.** It was invented. | Nowhere — deleted |
| **Computer-based test delivery** | See M12. | v2 |
| **DigiLocker / Aadhaar eKYC** | DR-005. Deferred, not rejected. | v2 |
| **Multi-university / multi-tenant operation** | `docs/v2-archive/spec/srs.md` mentions *"strict multi-tenant constraints"*. AMU is a single institution; multi-tenancy would be speculative complexity in every table. | Out unless sponsor directs otherwise |

---

## 7. Traceability

Every module above gets exactly one build spec at `docs/v3/02-plan/M{NN}-{slug}.md`, and every
requirement inside that spec gets an ID of the form `M{NN}-R{NN}`.

This replaces `docs/v2-archive/traceability.csv`, whose 29 rows cite `MODULES.md §5.1`–`§5.29` (there is no §5)
and `SRS-001`–`SRS-029` (the SRS uses `REQ-APP-01`…`REQ-MAND-03`), with `CodeArtefact` and
`TestCase` reading `TODO` on all 29 rows. **A traceability matrix whose references do not resolve is
worse than none, because it reports coverage that does not exist.**

The new matrix is generated from the specs, not maintained by hand, and CI fails if any requirement
ID lacks a code artefact and a test.

---

## 8. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. M01–M29 aligned to `MODULES.md`; M30–M34 added; 6 items placed out of scope. | Implementation team |
| 2026-08-27 | **M35 Designation & Sanctioned Strength Register added** (DR-012). M16 updated for the Designation→OU spine, appointment nature and the publish-time OU snapshot. M24 updated for local autonomous organisational units (DR-009). M25 updated for the second row-level scope (DR-010). | Implementation team |
