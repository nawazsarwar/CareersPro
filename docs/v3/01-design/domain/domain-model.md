# Domain Model

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Supersedes:** `docs/v2-archive/spec/domain-model.md` — which states `Advertisement N:1 Post` (backwards),
omits PostType, Order, Scrutiny, RollNo, Centre, Committee, Grievance and RuleSet entirely,
and describes ApplicationForm as *"links a User to an Advertisement"* when applications are made
**to a post**.

---

## 1. The spine

Everything else hangs off this. Get it wrong and nothing downstream is recoverable.

```
                        RuleSetVersion         RelaxationPolicyVersion
                              │                            │
                              │ governs                    │ age · qualification · fee
                              ▼                            ▼
   OrganisationalUnit ──< Designation >── OrganisationalUnitDesignation
        (tree)                                  (sanctioned_count)
            │                    │
            └────────┐           │
                     ▼           ▼
   Advertisement ──< Post >── PostType
        │             │
        │             └──< Application >── Candidate (User)
        │                       │
        │                       ├──< ApplicationSnapshot     (immutable, scored against)
        │                       ├──< EligibilityDecision ×3  (scrutiny · written · interview)
        │                       ├──< Order ──< Transaction
        │                       ├──< ScoreRun ──< ScoreLine  (each cites a rule id)
        │                       └──< HardcopyReceipt
        │
        └──< Corrigendum
```

**Read it as:** a **Post** is an instance of a **Designation**, in an **Organisational Unit**, under
an **Advertisement**. An **Application** is one candidate against one post.

### 1.1 Six modelling decisions, and why

| # | Decision | Why | Wrong alternative |
|---|---|---|---|
| 1 | **`Advertisement 1:N Post`** | `posts.advertisement_id`; advertisement 884 owns posts 2599–2602 | The old spec's `N:1`. Fee, dates and eligibility all hang off Post, so reversing it invalidates the model |
| 2 | **Post references a Designation, not a title string** | The rules engine must bind to a stable entity. Legacy `careers_db.posts` has only free text — *"Assistant Professor, Dept of Conservative Dentistry & Endodontics"* | Free-text title. Nothing connects a vacancy to the regulation governing it |
| 3 | **Three independent eligibility gates, not one status** | Production carries 12 columns across 3 gates; collapsing them breaks M31, M32 and the M23 pipeline widgets | One `status` integer. The current code writes the *string* `'Submitted'` into it |
| 4 | **Scoring runs against an immutable snapshot** | Reproducibility years later, for RTI and service appeals | Scoring the live profile. The candidate edits, and last year's score silently changes |
| 5 | **Ruleset frozen at advertisement publish** | UGC 2025 abolishes the Research Score entirely — an advertisement published under 2018 must score under 2018 for ever | Resolving the *active* ruleset at scoring time |
| 6 | **Organisational units local, tree, with a materialised path** | DR-009 autonomy; Dean-scoped authorisation runs on every admin request | Reading Data Lake at runtime, or a recursive `parent_id` walk per request |

---

## 2. Identity and access

```
users
  id · username (nullable, unique where present — the employee ID, staff only)
  email (unique) · email_verified_at · password
  status enum(active, suspended, locked)
  must_change_password bool · last_login_at
  soft deletes, timestamps

roles                     id · name · slug · is_system bool
permissions               id · name · slug · resource · action
permission_role           role_id · permission_id
role_user                 role_id · user_id · organisational_unit_id (NULLABLE)
```

**`role_user.organisational_unit_id` is the second authorisation scope (DR-010).** `NULL` = the role
applies university-wide (central administration). Non-null = the role is confined to that unit **and
its subtree** — how a Dean's-office user reaches the Faculty of Arts and its 3 child departments and
nothing else.

Supporting: `impersonation_tokens` (one-time, expiring, records the actor's IP) · `otp_codes`
(purpose, rate-limited) · `two_factor_secrets` (TOTP) · `password_reset_tokens` (**the table name
`config/auth.php` actually expects** — the current schema creates `password_resets` and password
reset is dead).

### 2.1 Login — DR-008

Applicants authenticate by **email**; staff by **email or employee ID**. The identifier field is
**resolved from the submitted value**, not fixed:

```php
$field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
Auth::attempt([$field => $login, 'password' => $password], $remember);
```

Laravel's `username()` override returns one fixed column and cannot express this. Employee IDs are
validated on creation to exclude `@`.

---

## 3. Organisation and establishment

```
organisational_unit_types
  id · title · code (unique) · category enum(academic, administrative)
  parent_id → self · is_recruitment_eligible bool · status · sort_order
  datalake_id (nullable, unique — import provenance only)

organisational_units
  id · title · title_hindi · title_urdu
  code (unique, NOT NULL)
  type_id → organisational_unit_types
  parent_id → self
  path (materialised, e.g. '/1/10/27/')  ← indexed
  status enum(draft, published)
  datalake_id (nullable, unique)

designations
  id · code (unique, NOT NULL) · name
  cadre enum(teaching, non_teaching, library, physical_education, school_teacher)
  group enum(A, B, C) nullable            -- non-teaching only
  pay_level · pay_range · retirement_age
  min_age · max_age · age_reference enum(application_closing_date)
  essential_qualification json · desirable_qualification json
  experience_rules json · method_of_recruitment json
  selection_method enum(interview_only, written_interview,
                        written_skill_interview, trade_test, driving_test)
  rule_set_id → rule_sets                 -- which regulation governs it
  status · soft deletes

organisational_unit_designation                    -- SANCTIONED STRENGTH REGISTER
  id · organisational_unit_id · designation_id
  sanctioned_count · sanction_order_ref · sanctioned_on
  UNIQUE(organisational_unit_id, designation_id)
```

**`path` is not an optimisation — it is a correctness requirement.** Dean-scoped authorisation runs
on every admin request. `WHERE path LIKE '/1/10/%'` is one indexed scan; a recursive `parent_id`
walk per request is not viable at 301 units × every page load.

`age_reference` exists solely to encode **CRR Rule 14**: age is computed against
`posts.reg_end_date` — never the submission, payment or interview date.

See `organisational-units.md` for the import provider contract.

---

## 4. Regulation

```
rule_sets           id · slug · title · applies_to json · design_doc
rule_set_versions   id · rule_set_id · version · status enum(draft, active, superseded)
                    effective_from · effective_to
                    payload json          ← the transcribed catalogue
                    content_hash          ← integrity + determinism
                    second_reader_verified bool · verified_by · verified_at

relaxation_policies          id · slug · title
relaxation_policy_versions   id · policy_id · version · effective_from · effective_to
                             age_relaxations json · qualification_relaxations json
                             fee_exemptions json
                             content_hash · second_reader_verified
```

**`second_reader_verified` is a gate, not a note.** A `rule_set_version` cannot move to `active`
while it is false. This is the mechanism that would have caught the fabricated
`ugc-rules.yaml` — PI 1.0 / Co-PI 0.5 against a Gazette that says 50% each.

**Vertical and horizontal reservation are separate JSON keys** because horizontal reservation
(PwBD, ESM) cuts *across* the vertical categories. Modelling horizontal as more vertical categories
is the classic roster defect.

```
roster_registers  id · organisational_unit_id · designation_id
                  relaxation_policy_version_id · roster_type · total_points
roster_points     id · roster_register_id · point_number · category
                  is_reserved · status enum(vacant, filled, carried_forward)
                  filled_by_post_id · sanction_ref
```

Ships unpopulated pending DOC-003 / OQ-013 — the schema is correct, the policy data is absent.

---

## 5. Recruitment

```
advertisement_types   id · title · slug
advertisements
  id · advertisement_no · title · slug · description
  type_id · organisational_unit_id + OU SNAPSHOT (code, title, type)
  appointment_nature enum(general, local)
  dated · default_fee
  default_opening_date · default_closing_date · default_payment_closing_date
  rule_set_version_id          ← FROZEN AT PUBLISH
  relaxation_policy_version_id ← FROZEN AT PUBLISH
  status enum(draft, pending_approval, published, paused, closed, withdrawn)
  published_at · approved_by_id · approved_at · added_by_id
  document_id (media)

corrigenda
  id · advertisement_id · corrigendum_no · issued_on · description
  changes json · published_at
```

**Corrigenda are objects, not edits.** A date extension or eligibility correction is published, dated
and auditable. The legacy system appends a unix timestamp to the slug as a de-dup hack; this replaces
it.

```
post_types
  id · name · code
  pdf_template · admit_card_template · interview_letter_template
  default_selection_method · submission_venue · status

posts
  id · advertisement_id · post_types_id
  designation_id · organisational_unit_id + OU SNAPSHOT
  serial_no · title · subject · slug (unique)
  appointment_nature enum(general, local) · tenure_months (nullable; local only)
  vacancies · location · pay_level · pay_range · fee
  opening_date · closing_date · payment_closing_date
  age_limit · min_experience_months           ← restored; the redesign dropped these
  selection_method
  admit_card_opening_date · admit_card_closing_date       ← restored
  interview_letter_opening_date · interview_letter_closing_date  ← restored
  test_date · test_reporting_time · gate_closing_time
  scheduled_test_start · test_duration
  interview_date · interview_time · interview_venue
  withdrawn bool · status · remark

post_vacancy_breakup
  id · post_id · category · horizontal_category (nullable) · count
```

**Four column groups are restored** that `betacareers_db` dropped but production carries:
`age_limit`, `min_experience_months`, `selection_method`, and the admit-card / interview-letter
window columns. Without them `isAgeOverLimit()` and download-window enforcement have no backing data.

---

## 6. Candidate

One reusable profile — *apply once, reuse everywhere*.

```
profiles          user_id · first/middle/last_name · fathers_name · mothers_name
                  spouse_name · dob · gender · nationality_id
                  marital_status_id · religion_id · category_id · caste_id · sub_caste
                  place_of_birth · district_of_birth_id · state_of_birth_id
                  domicile_state_id · domicile_district_id
                  mobile · mobile_verified_at · alternate_mobile
                  aadhaar_no (encrypted) · identity_marks
                  is_pwd · disability_type_id · disability_percent
                  disability_certificate_authority
                  is_ex_serviceman · esm_discharge_date
                  conviction / debarred / vigilance (+ reasons)
                  locked bool · verified bool

addresses         user_id · type enum(permanent, correspondence) · ... · postal_code_id
documents         user_id · collection · provenance enum(self_attested,
                  digilocker_verified, office_verified) · media_id
```

**`documents.provenance` exists from the first migration** though only `self_attested` is reachable
in v1 (DR-005). Adding a provenance concept later means re-opening submitted applications.

```
academic_qualifications
  user_id · qualification_level_id · board_id · course · subjects
  year_of_passing · division · percentage · cgpa · cgpa_scale
  ncrf_level            ← nullable under 2018, REQUIRED under 2025
  is_phd_regulations_compliant enum(2009, 2016, 2022, none)   ← the NET-exemption gateway
  phd_registration_date · phd_submission_date · phd_award_date
  conversion_declaration json     ← OQ-010, the CGPA problem

eligibility_tests      user_id · name (NET/JRF/SLET/SET/GATE) · agency · subject
                       year · roll_no · certificate_no
employment_histories   user_id · employer · type · designation · is_permanent
                       from · to · nature_of_duties · reason_for_leaving
                       pay_level · pay_range · pay_band · grade_pay
                       basic_pay · gross_pay · duration_days (computed)
institutions_attended  user_id · school · college · university_board_id
                       year_of_joining · year_of_leaving   ← NOT unique (a legacy bug)
teaching_research_experiences   user_id · 6 year-count columns   (was `traeds`)
foreign_visits         user_id · country_id · date · duration · purpose
referees               user_id · name · designation · mobile · email · address · period_known
other_details          user_id · 47 declaration columns
```

### 6.1 The claim model — built for both regimes

This is where the 2025 draft changes the design. UGC 2018 scores **structured publication metadata**;
UGC 2025 abolishes scoring and asks the committee to judge **narrative contributions** in ≥4 of 9
areas. **A claim model built only for Table 2 would not survive the transition.**

```
research_claims                   -- polymorphic, covers all of Table 2 categories 1-6
  id · user_id
  category enum(journal_paper, book, book_chapter, book_editor, translation,
                ict_pedagogy, mooc, econtent, research_guidance,
                project_completed, project_ongoing, consultancy,
                patent, policy_document, award, invited_lecture)
  title · year · detail json          -- category-specific fields
  -- journal_paper: journal, volume, issue, pages, issn, doi,
  --                is_peer_reviewed, is_ugc_listed, impact_factor,
  --                impact_factor_source_year, coauthor_count, authorship_role
  -- patent: patent_no, scope(international|national), granted bool
  -- project: agency, period, grant_amount, pi_role(pi|co_pi)
  evidence_document_id      ← MANDATORY. Table 2 header requires it
  verified_at · verified_by_id · verification_remark

notable_contributions             -- UGC 2025 cl. 3.8 / 3.9 / 3.10
  id · user_id · cadre · area_number (1-9) · area_label
  narrative text · evidence_document_id
  assessed_as_notable bool nullable · assessed_by_committee_id
```

**A claim with no `evidence_document_id` scores zero.** Not "is flagged" — scores zero. UGC 2018
Appendix II Table 2's header makes evidence a precondition of assessment, so it is a precondition of
scoring.

---

## 7. Application

```
applications
  id · application_no (unique) · user_id · post_id · advertisement_id
  submitted_at · submitted bool
  rule_set_version_id             ← copied from the advertisement at submit
  relaxation_policy_version_id   ← copied from the advertisement at submit
  applied_under_category · applied_under_horizontal_category
  is_internal_candidate bool
  paid bool · order_id
  lifecycle_state enum(draft, submitted, under_scrutiny, deficient,
                       scrutiny_cleared, rejected, shortlisted,
                       test_scheduled, interviewed, selected, waitlisted,
                       not_selected, withdrawn, archived)
  archived_at            ← DR-011. NEVER deleted.
  roll_no · centre_id · room_no · seat_no
  admit_card_downloaded_at · interview_letter_downloaded_at
  withdrawn_at · withdrawal_reason

application_snapshots
  id · application_id · taken_at · reason enum(submit, correction_window, rescore)
  payload json          ← canonical serialisation of the whole dossier
  content_hash          ← SHA-256 of the canonical form
  APPEND ONLY. No update, no delete.

eligibility_decisions                          ← THE THREE GATES
  id · application_id
  gate enum(scrutiny, written_test, interview)
  decision enum(eligible, rejected) nullable   ← NULL = pending
  remark · decided_by_id · decided_at
  UNIQUE(application_id, gate)

deficiencies
  id · application_id · raised_by_id · raised_at
  field_reference · description
  rectification_window_closes_at         ← the differentiator CU-Chayan lacks
  rectified_at · rectified_by_id · resolution

application_status_history
  id · application_id · from_state · to_state · actor_id · at · reason
```

**`eligibility_decisions` is a table, not three column groups**, so a post type with only one active
gate simply has one row. The **active gate set is derived from `post_types.default_selection_method`**
— an interview-only post has no `written_test` gate, and the UI must not offer one. The legacy modal
enables all three regardless, on a legally consequential decision.

**`decision` is nullable with three meanings** — `eligible` / `rejected` / `NULL = pending`. The
legacy UI renders a merged label *"Pending / Not Eligible"* over these. That ambiguity is not
reproduced.

---

## 8. Money

```
fee_rules      id · post_id (nullable) · advertisement_id (nullable)
               category · horizontal_category · amount · is_exempt
orders         id · order_uid (unique) · application_id · user_id
               amount · currency · idempotency_key (unique)
               gateway · merchant_id · pg_ref_no · pg_response json
               gateway_status · status enum(created, pending, paid,
                                            failed, refunded, double_payment)
               created_at · paid_at
transactions   id · order_id · gateway_txn_id · amount · status · raw json · occurred_at
reconciliations id · gateway · file_ref · uploaded_by_id · uploaded_at
               matched_count · unmatched_count · double_payment_count
receipts       id · order_id · receipt_no (unique) · issued_at · document_id
refunds        id · order_id · amount · reason · status · processed_at
```

**`idempotency_key` derived from `(user_id, post_id, attempt)` is the fix for the observed ~29%
failure rate.** If the browser dies after the bank debits but before the callback, reconciliation
matches on `pg_ref_no` and marks the order paid **without creating a second charge**. Under the
legacy design the candidate pays again — that is what ₹93.14 lakh of "failed transactions" against
₹2.29 crore received represents.

`status: double_payment` is a first-class outcome, not an exception.

---

## 9. Assessment

```
committees          id · post_id · type enum(screening, selection, dpc, dcc)
                    constituted_on · quorum_required · min_external_experts
committee_members   id · committee_id · user_id (nullable) · external_name
                    role enum(chairperson, subject_expert, dean, hod,
                              reserved_category_nominee, visitor_nominee, member)
                    is_external bool · attended bool

score_runs   id · application_id · rule_set_version_id
             strategy enum(weighted_points, threshold_count)
             snapshot_id · total · computed_at · computed_by
             input_hash · output_hash        ← determinism proof
score_lines  id · score_run_id · rule_id · citation · claim_id
             raw_value · apportionment_factor · points · explanation

shortlists   id · post_id · rule_set_version_id · generated_at
             ratio_applied · cutoff · generated_by_id
shortlist_entries  id · shortlist_id · application_id · rank · score · category

exam_centres      id · name · code · address · city · capacity
seat_allocations  id · application_id · centre_id · room_no · seat_no · allocated_at
roll_number_sequences  id · post_id · prefix · next_value

merit_lists       id · post_id · strategy · generated_at · approved_by_id
merit_entries     id · merit_list_id · application_id · rank · category
                  outcome enum(selected, waitlisted, not_selected)
```

**`score_lines.citation` is mandatory.** A total without per-line citations is not a valid output —
it is what a candidate is entitled to see and what a court will ask for.

**`roll_number_sequences` replaces a free-text integer.** Legacy `roll_no` is a nullable integer
validated only as `min:-2147483648`, with no sequence, no per-post prefix and no uniqueness.

---

## 10. Operations

```
hardcopy_receipts                              ← DR-011 physical custody register
  id · application_id · received_at · received_by_id · storage_location
  destruction_due_on                           ← close + 5 years, unsuccessful only
  destroyed_at · destroyed_by_id · destruction_batch_ref

grievances        id · application_id · user_id · category · description
                  raised_at · sla_due_at · assigned_to_id
                  status · resolution · resolved_at · resolved_by_id
mail_templates    id · code · subject · body · variables json
mail_campaigns    id · advertisement_id (nullable) · post_id (nullable)
                  template_id · segment json · scheduled_at · sent_at · created_by_id
mail_logs         id · campaign_id · application_id · to · status · sent_at · error

audit_logs                                     ← HASH-CHAINED, append-only
  id · sequence (unique) · previous_hash · hash
  event · subject_type · subject_id · actor_id · actor_ip
  properties json · created_at
  -- NO updated_at. NO soft delete.
```

`audit_logs` covers **every** model including `User`, `Role`, `Permission` — which the current
`Auditable` trait omits, so the security-sensitive models are precisely the unaudited ones.

---

## 11. Worked example

**Advertisement 2/2026/NT, post 2599 — System Manager, Pay Level-12, fee ₹500, 1 vacancy.**

1. **Master data.** `designations` has `SYS-MGR` (cadre `non_teaching`, group A, level 12,
   max_age 50, `age_reference: application_closing_date`, selection_method
   `written_skill_interview`, `rule_set_id` → `ugc-crr-non-teaching-2022`).
   `organisational_unit_designation` records `sanctioned_count = 1` for the Computer Centre.
2. **Publish.** The advertisement freezes `rule_set_version_id` = CRR-2022 v1 and
   `relaxation_policy_version_id` = *(none active — OQ-013)*. The post snapshots the OU as
   `{code: CCENTRE, title: 'Computer Centre', type: 'Services and Other Offices'}`.
   **Rule 34.3 guard fires:** sanctioned_count is 1, so promotion is not an available method.
3. **Apply.** A candidate with DOB 1984-11-26 applies. Age is computed against
   `posts.reg_end_date` = 2026-03-07 → **41y 3m**, under max_age 50 → eligible to apply.
4. **Submit.** An `application_snapshot` is written with `content_hash`. The application copies the
   advertisement's frozen versions. `lifecycle_state → submitted`.
5. **Pay.** An `Order` is created with `idempotency_key = sha256(user:48760|post:2599|attempt:1)`.
   The gateway debits but the callback is lost. Reconciliation matches `pg_ref_no` → `status: paid`.
   **No second charge.**
6. **Scrutinise.** A scrutiny officer opens the workbench. Because `selection_method` is
   `written_skill_interview`, **all three gates are active**. They set `scrutiny → eligible`.
   `eligibility_decisions` gains one row; `audit_logs` gains a hash-chained entry.
7. **Score.** Non-teaching, so no research score. `MeritStrategy` resolves from the designation to
   the CRR rule: Paper I (100, ≥40%) → Paper II (100, ≥50%) → skill test (50, ≥25, **qualifying
   only**) → interview (20%). **OQ-008 is open, so the engine refuses to finalise the merit
   arithmetic** and reports `PendingRatificationError` rather than guessing between 240 and 100.
8. **Shortlist.** `Rule 16` caps at **1:15** → 15 candidates for 1 vacancy.
9. **Exam.** `roll_number_sequences` issues a roll number; `seat_allocations` assigns centre, room
   and seat; the admit card generates only inside the `admit_card_opening/closing` window.
10. **Outcome.** Unsuccessful. `lifecycle_state → not_selected`, later `archived`.
    **Nothing is deleted.** `hardcopy_receipts.destruction_due_on` is set to process close + 5 years;
    the physical dossier is weeded then, the electronic record and the hash chain persist.

---

## 12. Naming corrections

| Legacy | Ours | Why |
|---|---|---|
| `adresses` | **`addresses`** | Misspelled, and propagated into routes, API paths and a spec |
| `institutions_attendeds` | **`institutions_attended`** | Double-pluralised |
| `traeds` | **`teaching_research_experiences`** | Unexplained acronym |
| `application_forms` | **`applications`** | It is not a form |
| `organizational_units` | **`organisational_units`** | British spelling throughout; `datalake_id` preserves the link |
| `basic_details` / `additional_details` `longtext` | **`json` columns** | ADR-001 mandates JSON; the current schema has **zero** JSON columns |
| `status int` | **PHP enums** | The wizard writes the *string* `'Submitted'` into an integer column |
| `institutions_attendeds.year_of_leaving UNIQUE` | **not unique** | A scaffolding bug — it makes it impossible for two candidates to leave in the same year |

---

## 13. Traceability

| Section | Feeds |
|---|---|
| §2 | M03 · M25 |
| §3 | M24 · M35 · `organisational-units.md` |
| §4 | M17 relaxation · M20 · `../regulatory/rules-catalogue.yaml` |
| §5 | M01 · M02 · M16 |
| §6 | M04 · M06 · M07 |
| §7 | M05 · M10 · M18 · M34 · `state-machine.md` · `snapshot-and-audit.md` |
| §8 | M08 |
| §9 | M19 · M20 · M21 · M22 · M11 · M14 · `scoring-engine.md` |
| §10 | M15 · M26 · M30 · M33 |

---

## 14. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Corrects `Advertisement 1:N Post`; adds the Designation→Post→OrganisationalUnit spine, the sanctioned-strength register, three independent eligibility gates, immutable snapshots, versioned rulesets and reservation policies, the full payment domain, and the hash-chained audit log. Claim model built for both the 2018 and 2025 regimes. | Implementation team |
