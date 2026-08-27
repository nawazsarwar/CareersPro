# Glossary

**Status:** live · **Owner:** project lead · **Created:** 2026-08-27
**Supersedes:** `docs/MEMORY.md` §1 (6 terms, no sources)

---

## 1. What this document is

Every domain term used in `docs/v3/`, with its **statutory or observed source**. If a term appears
in a specification, it appears here with one meaning. Where the legacy system, the regulations and
CU-Chayan use different words for the same thing, this file names the winner and records the
synonyms, so a reader encountering the old word in old code knows what it maps to.

**Rule for authors:** if you need a word that is not here, add it here first. Do not introduce a
synonym for a term that already exists — that is how `Scrutiny`, `Screening` and `Eligibility` came
to be used interchangeably in the previous specs for three genuinely different things.

---

## 2. Recruitment domain

| Term | Definition | Source |
|---|---|---|
| **Advertisement** | A published recruitment notification owning one or more **Posts**. Carries default fee and default opening / closing / payment-closing dates that individual posts may override. Has a type (General or Local). **One advertisement has many posts** — the reverse, as stated in `docs/spec/domain-model.md`, is wrong | `advertisements` table; advertisement 884 owns posts 2599–2602 |
| **Post** | A single advertised vacancy line: serial number, title, subject, vacancy count, location, pay level, pay range, fee, its own date windows, and a **Post Type**. **Applications are made to a post, not to an advertisement** (`applicationforms.post_id` is `NOT NULL`) | `posts` table |
| **Post Type** | The polymorphism driver. One row determines the application form template, the selection method, the admit-card and interview-letter templates, and the physical submission venue. **7 live rows** covering the five tracks | `post_types` table |
| **Track** | One of the five application variants: Teaching, School Teacher, Non-Teaching, Librarian, Physical Education & Sports. Determines which form parts a candidate completes | DR-007 |
| **Corrigendum** | An amendment to a published advertisement — typically a date extension or an eligibility correction. Must be a first-class object with its own publication date, not an in-place edit | CU-Chayan §6 declaration; `docs/v3/01-design/domain/` |
| **Application** | One candidate's submission against one post. Snapshot-locked at submission | `application_forms` |
| **Snapshot** | The immutable copy of a candidate's profile, qualifications and claims taken at submission. All scoring runs against the snapshot, never against the live profile — this is what makes re-scoring reproducible and point-in-time reconstruction possible | ADR-001 rationale; M27 |
| **Withdrawal** | A candidate retracting an application, or the University withdrawing a post (`posts.withdrawn`). Two different things — never conflate them | `posts.withdrawn`; CU-Chayan candidate action |
| **Organisational Unit** (OU) | Any node in the University's structure — Campus, Faculty, Department, Academic Centre, School, Institute, Polytechnic, or an administrative office. **A self-referential tree**: a Department's parent is a Faculty, whose parent is a Campus. Held **locally and autonomously** (DR-009); imported once from Data Lake, never read from it at runtime. 301 units across 29 types | DR-009; decision register §6 |
| **Organisational Unit Type** | Categorises a unit and is **itself a tree**. Carries `category` ∈ {academic, administrative} and `is_recruitment_eligible` — only some types can host a vacancy. Recruitment-relevant: Faculty (13), Department (111), Academic Centre (18), AMU Schools (11) | decision register §6.1 |
| **OU snapshot** | The denormalised copy of an organisational unit (`id`, `code`, `title`, `type`) frozen onto an Advertisement or Post **at publish time**, so a later rename cannot rewrite what a published advertisement said | DR-009 |
| **Designation** | The definition of a post type — cadre, group (A/B/C), pay level and range, essential and desirable qualifications, age criteria, experience rules, selection method, method of recruitment, and the governing ruleset. **Fully local, no ERP link** (DR-012). *Do not confuse with Data Lake's `designations`, which is a bare 346-row name list with every criteria column NULL* | DR-012; M35 |
| **Post = Designation × Organisational Unit × Advertisement** | The spine of the model. A Post is an instance of a Designation, in an OU, under an Advertisement. This is what lets the rules engine bind to a stable entity instead of a free-text title | DR-012 |
| **Sanctioned Strength** | The approved headcount for a Designation in an Organisational Unit (`organisational_unit_designation.sanctioned_count`), with its sanction order reference and roster breakup. Created only with UGC/EC approval; abolished or converted likewise. **Exists in no system today** | CRR Rules 8, 9.1; M35 |
| **Appointment Nature** | `general` or `local`. **General** = permanent to superannuation, General Selection Committee, administered centrally. **Local** = temporary 6–12 months (`tenure_months`), Local Selection Committee chaired by the Dean, administered in the Dean's office. **Eligibility, Research Score, fee, roster and the 30-day window are identical** — what differs is the committee, the tenure and who administers it | DR-010 |

## 3. Assessment and decision

| Term | Definition | Source |
|---|---|---|
| **Scrutiny** | Verification of a candidate's *claims against their documents* by a scrutiny officer. Produces the **scrutiny gate**. Not the same as Screening | `scrutiny_eligible`; M18 |
| **Screening** | Applying the shortlisting criteria (Table 3A/3B for teaching) to decide who is called for interview. Performed by the Screening Committee | UGC 2018 cl. 5.2; Table 3A |
| **Eligibility Gate** | One of **three independent decisions** — scrutiny, written test, interview — each holding `1` (eligible) / `0` (rejected) / `NULL` (pending) with its own remark, reviewer and timestamp. **The active set is driven by `post_types.default_selection_method`**; on an interview-only post the written-test gate does not exist | `careers_db.applicationforms` 12 columns; M34 |
| **Research Score** | The UGC 2018 Appendix II Table 2 total: 6 categories, 33 sub-rows, two faculty columns, 6 impact-factor bands, apportionment rules, a 30% combined cap on 5(b)+6, and a floor of three categories out of six. Thresholds: Associate Professor **75**, Professor **120**, College Principal **110** | UGC 2018 App. II Table 2; cl. 4.1 |
| **API** — *Academic Performance Indicator* | The legacy name for the research-scoring system. **Deprecated in this project**: use **Research Score**. `API` in `docs/v3/` always means a web API | `MEMORY.md` §1 used the old sense |
| **Shortlisting Score** | The Table 3A (Universities) or 3B (Colleges) total out of 100, used **only** to decide who is interviewed. **Hard invariant: it must never enter a teaching merit list** | UGC 2018 cl. 4.1 I Note, cl. 5.3 |
| **Merit Strategy** | The post-type-bound, versioned policy that produces the final ranked list. Two incompatible models: **teaching = interview performance alone**; **non-teaching = Paper I + Paper II + interview at 20%**, subject to qualifying the skill test | UGC 2018 cl. 5.3; CRR Rule 11 III(g) |
| **Deficiency** | A defect in an application identified at scrutiny, raised to the candidate with a **time-bound rectification window**. The legacy system has no such concept — applications lock irreversibly at payment | `pain-points.md`; M18 |
| **Selection Committee** | The statutory body that interviews and recommends. Composition and quorum vary by cadre; **the process must complete on the day of the meeting** | UGC 2018 cl. 5.1, 5.3 |
| **Roster** | The statutory register mapping post numbers to reserved categories, with backlog and carry-forward. **No substantive rules exist in either source document** — built as a configurable plug-in | CRR Rule 15.1; DOC-003 |

## 4. Regulatory instruments

| Term | What it is | Status here |
|---|---|---|
| **UGC Regulations 2018** | *Minimum Qualifications for Appointment of Teachers and other Academic Staff…*, No. F.1-2/2017(EC/PS), 18 July 2018, superseding the 2010 Regulations | **Active teaching ruleset** |
| **UGC Draft Regulations 2025** | Released 6 January 2025 in supersession of 2018; consultation closed 28 February 2025 | **Authored, inactive** — notification status is OQ-005 |
| **UGC Model CRR 2022** | *Central University Non-teaching and Other Academic Posts Model Recruitment Rules 2022*. **58 cadres**, Groups A/B/C, Pay Levels 1–14 | **Active non-teaching ruleset** |
| **PhD Regulations 2009 / 2016** | *UGC (Minimum Standards and Procedure for Award of M.Phil./PhD Degree) Regulations*. Compliance with these is the **NET/SET exemption gateway** | Encoded as a compliance flag on the qualification |
| **PhD Regulations 2022** | Superseded the 2016 Regulations and abolished M.Phil. **Whether a 2022-compliant PhD triggers NET exemption is unresolved** — the 2018 clause names only 2009 and 2016 | DOC-002 |
| **AMU Ordinances (Executive)** | AMU's own instrument, framed in light of the 2018 Regulations. **FN-1 names it as the operative document.** Not in the repository | DOC-001 — highest-priority acquisition |
| **Appendix map** | AMU numbering differs from UGC's: **AMU Appendix-I = UGC Table 3A · AMU Appendix-II (FN-1 "Part B") = UGC Table 2 · AMU Appendix-III (FN-1 "Part C") = UGC Table 1** | FN-1 |

## 5. Forms

| Term | What it is |
|---|---|
| **FN-1** | The teaching application form. Part A (25 items) + Appendix-I self-score + **Part B** (Table 2, 33 scored rows, 13 evidence sub-tables ≈ 70 columns) + **Part C** (Table 1, 2 graded activities, 6 evidence sub-forms). Template code `fn1` |
| **F-3** | The non-teaching form (`F3GeneralNT`, doc code AMUP-393). Part A only, 28 items. Template codes `fn3_general_nt`, `fn3_general_st`, `fn3_phe` |
| **Part A** | The 11-tab shared bio-data section: profile, photos, addresses, institutions attended, qualifications, eligibility tests, employment, teaching/research experience, foreign visits, referees, other details |
| **Part B1 / B2 / B3** | Track-specific score-bearing sections — B1 teaching (16 sub-forms), B2 Librarian, B3 Physical Education. **B2 and B3 contents are not in the repository** (DOC-006) |
| **Part C** | Teaching-only. Table 1 grading of teaching load and institutional involvement, verified by the departmental head |

## 6. Systems

| Term | What it is |
|---|---|
| **CareersPro v2** | This project. `betacareers.amuonline.ac.in` |
| **Legacy portal** | `careers.amuonline.ac.in` — the candidate-facing system being replaced |
| **Academic ERP / Datalake** | `datalake.amuonline.ac.in`. Hosts the Careers admin module (`careers.*` tables) and AMU's identity provider at `api.amu.ac.in/api/v1/auth/login` |
| **Manage Careers** | `mcareers.amuonline.ac.in` — the second admin application (Dashboard, Advertisements, Reports, Scrutiny, Application Receipt, Attendance Sheets, Bulk Documents, Profiles). **v1 consolidates this and the ERP Careers module into one application** |
| **CU-Chayan** | The UGC/MoE unified recruitment portal on the Samarth eGov platform. The benchmark, not a integration target |

---

## 7. Naming conventions

Binding on all new schema and code. The current schema violates several of these.

| Rule | Correct | Currently wrong |
|---|---|---|
| Tables are snake_case plural, spelled correctly | `addresses` | **`adresses`** (propagated into routes, API paths and a spec document) |
| No double pluralisation | `institutions_attended` | **`institutions_attendeds`** |
| No unexplained acronyms | `teaching_research_experiences` | **`traeds`** |
| Users have a username | `users.username` | column absent, though the sign-in design requires *"username or email"* |
| Enumerations are PHP enums, not loose integers or strings | `ApplicationStatus` enum | `status` is a nullable `integer` that the wizard writes the **string** `'Submitted'` into |
| Dynamic payloads use `json`, never `longtext` | `json` columns per ADR-001 | `basic_details`, `additional_details` are `longtext` |
| Boolean-ish tri-states are explicit | `1` / `0` / `NULL` with a documented meaning per column | the eligibility dropdown renders a merged label *"Pending / Not Eligible"* over three distinct values, on a legally consequential decision |
| **British spelling throughout**, matching the rest of this project | `organisational_units`, `organisational_unit_types` | Data Lake uses **`organizational_units`** (z) and **`organizational_units_types`** (double-pluralised). Both are renamed on import. `datalake_id` preserves the link |
| Login identifier is resolved, not fixed | a credential resolver picks `email` or `username` from the submitted value | Laravel's `username()` override returns one fixed column and cannot express "email for applicants, email-or-employee-ID for staff" (DR-008) |
| Tree tables carry a materialised path | `organisational_units.path` = `/1/10/27/` | Dean-scoped authorisation runs on every admin request; a recursive `parent_id` walk per request is not viable (DR-010) |

---

## 8. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. ~50 terms sourced. `API` deprecated in favour of `Research Score`; Scrutiny / Screening / Eligibility Gate disambiguated; 7 naming conventions fixed. | Implementation team |
| 2026-08-27 | Added Organisational Unit, OU Type, OU snapshot, Designation, the Post spine, Sanctioned Strength and Appointment Nature (DR-009, DR-010, DR-012). Three naming conventions added, including the British-spelling rename of Data Lake's `organizational_units`. | Implementation team |
