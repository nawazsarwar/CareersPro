# Decision Register

**Status:** live · **Owner:** project lead · **Created:** 2026-08-27
**Supersedes:** `docs/open-questions.md` (4 entries, none owned, none dated, and it omits AMU SSO,
reservation policy and every Table 2 ambiguity)

---

## 1. What this document is

This is the single authoritative record of **every decision that shapes the CareersPro v2 build**,
and of every question still open. It exists because the previous planning round produced
specifications that contradicted each other and the schema, with no record of who decided what or
why — so nobody could tell a decision from an assumption.

**Three rules govern this file.**

1. **Nothing gets built on an assumption.** If a design document depends on a choice, that choice
   has an entry here. If the entry is `OPEN`, the dependent module does not start.
2. **Every entry names an owner and a date.** "We decided" is not a decision; "the Registrar's
   Office decided on 2026-09-04" is.
3. **Every entry states what would reverse it.** A decision you cannot un-make is a trap. Writing
   the reversal trigger down at decision time is what makes it safe to move fast.

### 1.1 How to use it if you are implementing

Before you write code for a module, open its build spec in `docs/v3/02-plan/`. Every spec has a
**Depends on** line listing decision IDs. Check each one here.

- All `DECIDED` → proceed.
- Any `OPEN` or `PROPOSED` → stop and escalate. Do **not** pick the option that looks obvious.
  A `PROPOSED` entry is a recommendation awaiting sign-off, not a green light.

### 1.2 How to add an entry

Copy the template below. Do not abbreviate it — a half-filled entry is worse than none, because it
looks settled.

```markdown
### DR-0NN — <short imperative title>

| | |
|---|---|
| **Status** | OPEN / PROPOSED / DECIDED / SUPERSEDED |
| **Owner** | <named role, not "the team"> |
| **Decided on** | <YYYY-MM-DD, or "—" while open> |
| **Blocks** | <module or document IDs that cannot start until this closes> |

**Question.** <One sentence. What is actually being asked?>

**Why it matters.** <The concrete consequence of getting it wrong. Name the module, the table, the
statutory clause, or the rupee amount at stake.>

**Options considered.**
1. **<Option A>** — <what it means in practice> · *Cost:* <effort/risk> · *Benefit:* <what it buys>
2. **<Option B>** — …

**Decision.** <The chosen option, stated so a junior developer could implement it without asking a
follow-up question.>

**Rationale.** <Why this option beat the others. Cite evidence — a file, a clause, a row count.>

**Worked example.** <A concrete case running through the decision, with real values. This is not
optional: it is how the reader checks they understood the same thing you meant.>

**Reversal trigger.** <What would make us revisit this.>
```

### 1.3 Status legend

| Status | Meaning | May implementation proceed? |
|---|---|---|
| `OPEN` | Question raised, no recommendation yet | **No** |
| `PROPOSED` | Recommendation made, awaiting sign-off from the named owner | **No** |
| `DECIDED` | Signed off. Binding on all design and build documents | Yes |
| `SUPERSEDED` | Replaced by a later entry. Kept for the audit trail, never deleted | No — follow the successor |

---

## 2. Decisions

### DR-001 — Adopt Clarify → Design → Plan → Code → Verify

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | Everything. This is the meta-decision. |

**Question.** In what order is work produced, and what is the required standard of the written
artefacts?

**Why it matters.** The previous round wrote 219 lines of specification for a 29-module statutory
platform and then coded against it. The result: `docs/spec/domain-model.md` states
`Advertisement N:1 Post` when the real relationship is `1:N`; `docs/spec/api.md` specifies four
`/api/v2` endpoints of which zero exist; `docs/spec/security.md` mandates hash-chained audit logs
against a table with no hash column. None of these were caught, because there was no phase in which
catching them was somebody's job.

**Options considered.**
1. **Code-first, document as you go** — *Cost:* the failure mode above, repeated. *Benefit:* early
   visible progress.
2. **Clarify → Design → Plan → Code → Verify, documentation-first** — *Cost:* no running code for
   the first phases. *Benefit:* every ambiguity is surfaced and owned before it becomes a defect
   embedded in a statutory decision.

**Decision.** Option 2. Clarification, design and planning are recorded as documentation **before**
any code is written. Each phase's artefacts live under `docs/v3/00-clarify/`, `docs/v3/01-design/`
and `docs/v3/02-plan/` respectively. **No module's implementation begins before its build spec in
`docs/v3/02-plan/` exists and its `Depends on` decisions are all `DECIDED`.**

The required standard, stated by the sponsor: the documents must be rigorous enough that *"even if
these finalized documents are given to a junior dev, they don't have scope to mess"*. Concretely,
every specification must carry:

- every rule with its **statutory citation** (regulation, clause number, and line reference);
- every field with its **type, constraints and validation rule**, including the exact error message;
- every state transition with its **actor, guard and side-effect**;
- every acceptance criterion with a **worked example using real values**;
- the **test case** that proves it, named by file and method.

**Rationale.** The defects listed above are not coding errors. Each is a specification that was
never checked against the thing it described. Documentation-first only helps if the documentation
is checkable — hence the five mandatory elements, each of which can be verified by a reader who
was not present at the decision.

**Worked example.** A junior developer is assigned "implement Associate Professor eligibility".
Under this decision they receive:

> `docs/v3/02-plan/M20-scoring-engine.md` §4.2 — *Research score threshold, Associate Professor.*
> **Rule:** minimum research score **75**. **Citation:** UGC Regulations 2018, cl. 4.1 II
> (`ugc_regulations_2018.txt:4550-4563`). **Input:** the candidate's Table 2 total for the frozen
> ruleset version bound to the advertisement. **Guard:** `score >= 75`. **Worked example:** a
> candidate with 7 papers in Column II faculty (7 × 10 = 70), one nationally-published authored book
> (10), and one completed project under ₹10 lakh (5) scores 85 → **eligible**. Change the book to a
> chapter (5) and the score is 80 → still eligible. Remove the project and it is 75 → eligible at the
> boundary; 74 → **not eligible**. **Test:** `tests/Feature/Eligibility/AssociateProfessorTest.php::test_research_score_boundary_at_75`.

They cannot get this wrong without contradicting something written down. That is the standard.

**Reversal trigger.** None anticipated. If schedule pressure makes a phase feel skippable, the
correct response is to narrow scope (DR-002 §Options), not to skip a phase.

---

### DR-002 — Clean-slate rebuild inside the existing repository

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | Wave 0 of implementation; `docs/v3/01-design/domain/domain-model.md` |

**Question.** Repair the existing scaffolding in place, rebuild from scratch in this repository, or
start a new repository?

**Why it matters.** It determines whether the new schema inherits the current one. The current
schema has defects that break four of the nine reference admin screens (see
`docs/v3/01-design/domain/` and Part 2.4 of the approved plan) — most importantly it collapses the
production system's **three independent eligibility gates** into four generic columns.

**Options considered.**
1. **Incremental repair** — fix the 3 broken migrations, add the 5,702 missing translation keys, add
   ownership scoping to 35 controllers, convert 263 Bootstrap views to Tailwind, then layer domain
   logic on top. *Cost:* inherits the flawed schema and the Admin/Frontend controller duplication
   permanently. *Benefit:* nothing is thrown away.
2. **Clean-slate rebuild in this repo** — keep repo, history, `docs/`, deployment config and
   ADR-001; delete the generated scaffolding; rebuild domain-first. *Cost:* the deletion commit is
   large. *Benefit:* the schema is designed from the corrected domain model rather than patched
   toward it.
3. **New repository** — *Cost:* loses git history, PR trail, deploy wiring. *Benefit:* marginal over
   option 2.

**Decision.** Option 2.

**Keep:** the repository and its history · `docs/` in full · `Dockerfile`, `docker/`,
`nixpacks.toml`, `start.sh` · `composer.json` / `vite.config.js` toolchain ·
**`docs/adr/001-database-selection.md`** (see DR-003).

**Delete, on a branch, in one reviewable commit:**

| Path | Files | Why |
|---|---:|---|
| `app/Http/Controllers/{Admin,Frontend,Api}/**` | 99 | `Frontend/*` are byte-for-byte copies of `Admin/*`; none scope to the owner |
| `app/Http/Controllers/Auth_backup/**` | 7 | Superseded, unrouted |
| `app/Http/Controllers/Frontend*.php` (top level) | 5 | Shadowed by `use … as` aliases in `routes/web.php`; unreachable |
| `app/Http/Requests/**` | 99 | Generated type-echoes with no cross-field validation |
| `resources/views/{admin,frontend,auth_backup}/**` | 271 | Two incompatible design systems; 33 of 34 admin lists render empty |
| `database/migrations/**` | 56 | Chain is half-applied and unrecoverable |
| `tests/Browser/**` | 33 | Cannot run: no `DuskTestCase`, `laravel/dusk` not installed, excluded from `phpunit.xml` |
| `laravel` (SQLite), `debug_error.html`, `verify-auth.cjs`, `verify-profile.cjs`, `.phpunit.result.cache` | 5 | Debugging litter; `debug_error.html` contains a live session token |
| npm deps: `@caveman-ai/cli`, `playwright`, `@fullcalendar/*` ×5, `jsvectormap`, `swiper`, `prismjs`, `@floating-ui/dom`, `@popperjs/core` | 9 of 13 | Zero source references |

**Rationale.** This is safe because **there is no data to preserve**: `betacareers_db` holds 1 user,
162 permissions, 2 roles, and **0 rows in every domain table** (verified 2026-08-27 via
`php artisan db:show` and per-table counts). Migrations are already stuck — the pending
`2026_08_24_000001_update_employment_histories_table` added `is_permanent` before failing on
`->after('salary')`, so re-running it now dies on `Duplicate column name`. There is nothing to
repair *toward*; the choice is only whether the new schema starts clean or starts compromised.

**Worked example.** Under option 1, `application_forms` keeps `eligible / eligibility_remark /
eligibility_updated_at / eligibility_updated_by_id`. The production system it replaces has
`scrutiny_eligible`, `written_test_eligible` and `interview_eligible`, each with its own remark,
reviewer and timestamp. Building the three-stage scrutiny modal on the collapsed schema means
either inventing a fourth column set later (a migration on live data) or encoding stage into a
string — the exact class of shortcut that produced `status = 'Submitted'` in an integer column.
Under option 2 the table is designed with the three gates from the first migration.

**Reversal trigger.** Discovery of production data already loaded into `betacareers_db`, or a
requirement to ship a working screen before the design phase completes.

---

### DR-003 — MySQL relational core with native JSON columns (adopt ADR-001)

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 (ratifying the existing ADR) |
| **Blocks** | All schema work |

**Question.** What database architecture underpins the rebuild?

**Why it matters.** Recruitment carries statutory data-integrity obligations — reservation rosters,
fee transactions, and decisions that must be reconstructible for RTI and litigation years later.

**Decision.** Adopt `docs/adr/001-database-selection.md` **unchanged**: MySQL as the primary store
with full referential integrity, plus **native `json` columns** for genuinely dynamic, nested
payloads — the versioned rule sets, per-post additional fields, and application snapshots.

**Rationale.** The ADR is the one sound artefact in the previous planning set: it states a real
question, weighs three real options, and gives three defensible reasons. It was simply never
implemented — **there is not a single `json` column in the current 37-table schema**, and the two
dynamic payloads (`basic_details`, `additional_details`) were carried over from the legacy system as
unindexed `longtext`, which is the anti-pattern the ADR was written to fix.

**Worked example.** An advertisement published under the UGC 2018 ruleset stores
`advertisements.regulation_snapshot` as a `json` column holding the frozen ruleset — rule ids, point
values and citations as they stood on the publish date. When the 2025 Regulations are notified and
the active ruleset changes, this advertisement continues to score against its own frozen copy, and
a scrutiny officer in 2029 can see exactly which rule produced which point. The relational side
still enforces `posts.advertisement_id` as a foreign key, so the roster and fee joins remain exact.

**Reversal trigger.** A demonstrated query-performance failure on JSON columns at the 78,000-row
application scale that indexing and generated columns cannot resolve.

---

### DR-004 — Online payment is in scope for v1

| | |
|---|---|
| **Status** | **DECIDED** (vendor still open — see OQ-001) |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | M08 Fee & Payment |

**Question.** Does v1 collect application fees online, or defer payment to a later phase?

**Decision.** Online fee collection is in scope for v1, built against a **driver-based gateway
abstraction** so the vendor choice (OQ-001) affects only the adapter, not the domain.

**Rationale.** The system being replaced collects fees online today and the dashboard shows
**₹2,29,94,500 received against ₹93,14,500 in failed transactions — a ~29% failure ratio**. A
replacement that cannot take payment is not a replacement; and reconciliation quality is one of the
clearest differentiators available, since double-deduction at deadline hours is one of CU-Chayan's
seven documented weakness categories.

Legacy `orders` already carries `merchant_id`, `gateway`, `pg_ref_no`, `pg_response`,
`gateway_message_id` and `gateway_status` — a multi-gateway abstraction. Preserve that shape.

**Worked example.** A candidate pays ₹500 for post 2599. The domain creates an `Order` with an
idempotency key derived from `(user_id, post_id, attempt)`, hands it to
`PaymentGateway::initiate()`, and records the response. If the candidate's browser dies after the
bank debits but before the callback, the reconciliation job — reading the gateway's MIS file —
matches on `pg_ref_no` and marks the order paid **without creating a second charge**. Under the
legacy design the candidate pays again and joins the ₹93 lakh.

**Reversal trigger.** Sponsor decision to launch with offline/challan payment only.

---

### DR-005 — DigiLocker / Aadhaar eKYC deferred beyond v1

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | Nothing in v1. Constrains M07 Document Vault design. |

**Question.** Does v1 verify candidate documents through DigiLocker / Aadhaar eKYC?

**Decision.** No. v1 relies on uploaded documents with self-attestation, per the current statutory
process. DigiLocker is recorded as a **deferred compliance improvement, not a rejected one**.

**Rationale.** Not selected as an in-scope integration for v1. It remains attractive — it would
address the illegible-scan problem directly — but it adds an external dependency and a
consent/DPDP surface to a phase that already carries the payment integration.

**Design constraint this places on v1.** The Document Vault must model a document's **provenance**
(`self_attested` | `digilocker_verified` | `office_verified`) from the first migration, even though
only the first value is reachable in v1. Adding a provenance concept later means re-opening
submitted applications.

**Reversal trigger.** A statutory requirement for verified documents, or sponsor prioritisation.

---

### DR-006 — Versioned, effective-dated rules engine carrying both UGC 2018 and 2025

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor, advised by Registrar's Office |
| **Decided on** | 2026-08-27 |
| **Blocks** | M20 Scoring Engine, M21 Shortlisting & Cut-offs, `regulatory/rules-catalogue.yaml` |

**Question.** Does the system encode UGC Regulations 2018 only, or a versioned engine able to carry
2018, the Draft 2025 Regulations, and the non-teaching CRR 2022 side by side?

**Why it matters.** UGC released the Draft **UGC (Minimum Qualifications for Appointment and
Promotion of Teachers and Academic Staff…) Regulations, 2025** on 6 January 2025, expressly in
supersession of the 2018 Regulations; consultation closed 28 February 2025 and the final
notification status could not be confirmed from public sources as of this writing (see OQ-005).
The 2025 regime materially changes eligibility. An advertisement published under 2018 must
**forever** score under 2018 — a candidate rejected in 2026 may litigate in 2029, and the court
will ask which rules applied on the publication date.

**Options considered.**
1. **UGC 2018 hardcoded** — *Cost:* a code change, re-verification and a data migration when 2025 is
   notified; no way to score an old advertisement under old rules once the code changes. *Benefit:*
   simplest to build.
2. **Versioned, effective-dated engine** — rulesets are data, each rule carrying `id`, `citation`,
   `effective_from`, `effective_to`; the advertisement freezes its ruleset version at publish.
   *Cost:* more design work up front. *Benefit:* new regulations load without a code change;
   historical reconstruction is exact.

**Decision.** Option 2. Ship `ugc-teaching-2018` as the **sole active** teaching ruleset and
`ugc-crr-non-teaching-2022` as the active non-teaching ruleset, with `ugc-teaching-2025` **authored
now** and loadable but inactive.

Confirmed by the sponsor: the 2025 Regulations remain **draft and unnotified**; 2018 is what is live.
Both rulesets are built now. If 2025 is notified as-is it applies to **new advertisements only**; if
it is modified before notification, the 2025 ruleset is amended in place. Either way, **no code
change.**

Source obtained and committed: **`docs/UGC_Draft_Regulations_Teaching_2025.pdf`**
(ugc.gov.in, ref. 3045759).

`MODULES.md` #27 (RTI / point-in-time reconstruction) and `MEMORY.md`'s "time-travelling data"
invariant are unimplementable under option 1.

**Worked example.** Advertisement 884 is published 2026-01-22 and binds
`regulation_version = ugc-teaching-2018`. In March 2027 the 2025 Regulations are notified and the
active version becomes `ugc-teaching-2025`. Advertisement 884's applications continue to score
under 2018 — the frozen snapshot is used, not the active version. A new advertisement published in
April 2027 binds 2025. Both are simultaneously correct, and re-running either produces byte-identical
output because the input snapshot and the ruleset are both immutable.

**Reversal trigger.** Confirmation that the 2025 Regulations will not be notified, *and* a sponsor
decision that historical reconstruction is not required — the second is unlikely given RTI exposure.

---

### DR-007 — Recruitment tracks in v1

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Registrar's Office |
| **Decided on** | 2026-08-27 |
| **Blocks** | M16 Advertisement Builder, M05 Application Wizard, `domain/domain-model.md` |

**Question.** Which recruitment tracks must v1 support end to end?

**Decision.** All five existing tracks across **all 7 post types**; CAS/internal promotion deferred
to v2. Confirmed: the post types that look duplicated are not — they are the General and Local
appointment regimes, which is now **DR-010**.

| Track | Form | Selection | Post types (live rows) |
|---|---|---|---|
| Teaching | Part A + B1 + C | Interview only | GENERAL (TEACHING POST), LOCAL (TEACHING POST) |
| School teacher | Part A | Interview only / Written + Interview | GENERAL (School Teacher), LOCAL (School Teacher) |
| Non-teaching | Part A | Written + Interview | GENERAL (Non Teaching Post) |
| Librarian | Part A + B2 | Interview only | GENERAL (ASST/DY/UNIVERSITY LIBRARIAN) |
| Physical Education & Sports | Part A + B3 | Interview only | GENERAL (ASST/DY DIRECTOR, PHYSICAL EDUCATION & SPORTS) |

**Rationale.** This is not a preference — it is what the production system already does. The
`post_types` table holds exactly **7 live rows** covering these five tracks, each with its own
`pdf_template`, `default_selection_method`, `admit_card_template`, `interview_letter_template` and
`submission_venue`. Dropping a track means a class of vacancy the portal cannot advertise.

**Known gap blocking full specification.** The contents of **Part B2** (Librarian) and **Part B3**
(Physical Education) exist nowhere in `docs/` — the AMU manual says only that they "will be filled
up" like B1. And there is **no rule source in the repository at all** for AMU school-teacher
recruitment, which is governed by neither the UGC 2018 Regulations (universities and colleges) nor
the non-teaching CRR. See DOC-005 and DOC-006.

**Reversal trigger.** Sponsor decision to phase the launch by track.

---

### DR-008 — Dual-identifier login, no external SSO in v1

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | M03 Registration & Profile, M25 RBAC |

**Question.** What does a user type into the login field, and does the portal authenticate against
AMU's identity provider?

**Decision.** The portal owns its own credentials — **no external SSO in v1**. The login field
accepts:

- **Applicants:** their **email address**. Nothing else.
- **Staff:** their **email address or their employee ID**.

`users.username` holds the employee ID. It is **nullable** (applicants have none) and **unique where
present**.

**Rationale.** Applicants are the public; they have no AMU identity. Staff already know their
employee ID and the sign-in design specifies *"username or email"* / *"e.g. user@amu.ac.in or
username"*. Owning the credentials keeps the portal autonomous, consistent with DR-009.

**Implementation note — do not reach for Laravel's `username()` override.** That method returns a
*single fixed* column name, which cannot express "email for one class of user, email-or-employee-ID
for another". Use a **credential resolver** that inspects the submitted value and builds the
`attempt()` array accordingly:

```php
// Resolve the identifier field from the submitted value, not from a constant.
$login = $request->input('login');
$field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

Auth::attempt([$field => $login, 'password' => $request->input('password')], $remember);
```

**Worked example.** A candidate types `aisha.khan@gmail.com` → resolver picks `email` → matches a
user with `username = NULL`. A Deputy Registrar types `EMP04821` → resolver picks `username` →
matches a staff user. The same Deputy Registrar types `dyregistrar@amu.ac.in` → resolver picks
`email` → matches the *same* row. All three succeed; no branch in the UI, one login form.

**Edge case that must be tested:** a staff member whose employee ID happens to contain an `@` would
be routed to the `email` branch and fail. Employee IDs are validated on creation to exclude `@`.

**Reversal trigger.** A decision to adopt AMU SSO (`api.amu.ac.in/api/v1/auth/login`). The resolver
design makes this additive — an SSO guard alongside the local one — not a rewrite.

---

### DR-009 — Organisational units are local and autonomous

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | M16 Advertisement Builder, M24 Master Data, M25 RBAC |
| **Supersedes** | An earlier remark that no migration was needed for organisational units |

**Question.** Does the portal read faculties, departments and centres live from the Data Lake ERP, or
hold its own?

**Why it matters.** It decides whether the portal keeps working when Data Lake does not.

**Decision.** *"Keep all the tables local here… let this app be autonomous and independent totally."*

- **`organisational_units` and `organisational_unit_types` are local tables with local migrations**,
  and are the sole runtime source of truth. **No runtime code reads Data Lake.**
- Data Lake is a **one-time import source**, run through a pluggable provider so it can later be a
  DB read, an API call, or nothing at all.
- Advertisements and Posts store a **denormalised OU snapshot** (`id`, `code`, `title`, `type`) at
  publish time.

**Rationale.** A model bound to a `datalake` connection fails hard the moment that connection is
gone — which contradicts the autonomy requirement outright. The snapshot is wanted independently:
without it, renaming a department in 2028 silently rewrites what a 2026 advertisement said, which is
exactly the kind of retroactive change an RTI request or a service appeal will expose.

**Worked example.** Advertisement 884 is published naming *Department of Conservative Dentistry &
Endodontics* (`code = DCONSDENT`). In 2028 the department is renamed. The OU row updates; the
advertisement's snapshot does not. A scrutiny officer reviewing the 2026 cohort still sees the name
as advertised, while a new advertisement picks up the new name. Neither query touches Data Lake.

**Reversal trigger.** None. A future API-based sync is a provider swap, not a reversal.

---

### DR-010 — Appointment nature is a first-class concept

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Registrar's Office |
| **Decided on** | 2026-08-27 |
| **Blocks** | M16, M18 Scrutiny, M25 RBAC |

**Question.** Several of the 7 post types look like duplicates differing only by "GENERAL" vs
"LOCAL". Are they?

**Decision.** They are not duplicates. Two distinct appointment regimes:

| | General | Local |
|---|---|---|
| Nature of appointment | Permanent, to superannuation | **Temporary, 6–12 months** |
| Committee | General Selection Committee | Local Selection Committee |
| **Administered by** | **Central university administration** | **The Dean's office** — the Dean chairs, and scrutiny and appointment are done there |
| Eligibility / Research Score | UGC minimums | **identical** |
| Fee, roster, 30-day advertisement window | standard | **identical** |

Posts carry `appointment_nature` (`general` \| `local`) and, for local, `tenure_months`.

**The significant consequence is authorisation, not data.** Because local recruitment is administered
at faculty level, RBAC needs **two orthogonal row-level scopes**:

1. **Ownership** — a candidate reaches only their own records. *(Absent today: any authenticated
   candidate can modify any other's dossier.)*
2. **Organisational unit** — a Dean's-office user reaches only their own faculty's local
   advertisements, posts, applications and scrutiny actions. *(Absent today entirely.)*

**Worked example.** The Dean's office of the Faculty of Arts opens the scrutiny queue. They see
applications for local posts in the Faculty of Arts **and its 3 child departments**, resolved by
subtree. They do not see the Faculty of Commerce's local posts, and they do not see any General
post — those belong to the central administration. A central Deputy Registrar sees all General
posts across all faculties, and no local ones unless separately granted.

**Reversal trigger.** A change in how local selection committees are constituted.

---

### DR-011 — Nothing is destroyed electronically; hard copies are weeded at five years

| | |
|---|---|
| **Status** | **DECIDED** — also closes OQ-011 |
| **Owner** | Registrar's Office |
| **Decided on** | 2026-08-27 |
| **Blocks** | M26 Audit, M33 Hardcopy Tracking, `security/` |

**Question.** What is retained, for how long, and what is destroyed?

**Decision.**

| Record | Retention |
|---|---|
| **All electronic records** | **Indefinite. Nothing is ever deleted.** Applications move to an `archived` lifecycle state; no PII is nulled; the hash chain stays unbroken |
| **Hard copies — selected candidates who joined** | **Permanent**, in the central record section |
| **Hard copies — unsuccessful candidates** | **Destroyed five years** after the process closes, per government mandate |

So the weeding is a **physical-custody process, not a data-deletion process**. M33 gains a custody
register: receipt → storage location → destruction due date → destruction record (date, authorising
officer, batch).

**Rationale.** Stated directly: *"Electronically nothing gets destroyed, just that they are old and
treated as archives, whereas the physical documents… are destroyed after five years for the
non-successful candidates."* This keeps the "no deletion, immutability preserved" invariant intact
instead of colliding with it.

**Worked example.** An unsuccessful applicant to post 2599 (process closed 2026-06-30). On
2031-06-30 their **physical dossier** appears in the destruction batch; an officer confirms
destruction and the register records who and when. Their **electronic** application, uploaded
documents and audit trail remain fully intact and queryable, marked `archived`.

**This changes the data-protection position, and the security document must argue it, not assume
it.** Indefinite electronic retention is *not* data minimisation. It is defensible under DPDP 2023
only as a **statutory-obligation and legal-claims basis**, reinforced by CRR Rule 22.4's perpetual
verification right (*"at any point of time even after joining"*) and RTI exposure. That argument
must be written down explicitly in `docs/v3/01-design/security/`.

**Reversal trigger.** A DPDP ruling or a University policy imposing electronic erasure.

---

### DR-012 — `designations` is a fully local master with no ERP link

| | |
|---|---|
| **Status** | **DECIDED** |
| **Owner** | Project sponsor |
| **Decided on** | 2026-08-27 |
| **Blocks** | M16, M17 Roster, M20 Scoring Engine, M35 |

**Question.** Where does the definition of a post — its cadre, pay level, eligibility and age
criteria — live?

**Why it matters.** Today a Post carries free-text title, pay level and pay range, so **nothing
connects a vacancy to the regulation that governs it**. The rules engine has no stable entity to
bind to.

**Decision.** A **fully local `designations` master, with no reference to the ERP's Designation
Types.** It holds the definition of each post type: cadre, group (A/B/C), pay level and range,
essential and desirable qualifications, age criteria, experience rules, selection method, method of
recruitment, and the governing ruleset version.

A **Post becomes an instance of a Designation, in an Organisational Unit, under an Advertisement**:

```
Designation ──< Post >── OrganisationalUnit
     │                          │
RuleSetVersion       OrganisationalUnitDesignation
(governs eligibility)  (sanctioned_count → roster)
```

Plus `organisational_unit_designation` (OU × designation × `sanctioned_count`) — **the sanctioned
strength register** required by CRR Rules 8 and 9.1, and the backing data that `MODULES.md` #16's
*"post creation linked to sanctioned strength"* assumed and never had.

**Rationale.** Confirmed by the schema review in §6: Data Lake's `designations` is a bare name list
with no recruitment semantics at all, and no sanctioned-strength table exists anywhere in its 175
tables. **There is nothing to link to.** The 346 names become a seed vocabulary.

**Worked example.** Designation `ASST-PROF` (cadre `teaching`, no group, Academic Level 10, governed
by `ugc-teaching-2018`) is sanctioned 3 times to the Department of English. An advertisement creates
a Post: designation `ASST-PROF`, OU `DENG`, `appointment_nature = general`, 2 vacancies. The rules
engine resolves eligibility from the designation's ruleset — never from the post's title string —
and the roster engine checks 2 against the sanctioned 3.

**Reversal trigger.** Data Lake populating `designations` with real recruitment criteria, which
would make a link worth reconsidering.

---

## 3. Open questions

Each blocks the named work. Ordered by the date by which an answer is needed.

| ID | Question | Blocks | Owner | Needed by |
|---|---|---|---|---|
| **OQ-001** | Which payment gateway does AMU use? | M08 adapter (not M08 design) | Finance Office | Wave 5 |
| **OQ-004** | Legacy cut-over: dual-run window; disposition of the 215,946 orphan backup rows; destination schema for ₹2.29 crore of financial history | Migration (Wave 10) | Project sponsor | Wave 10 |
| **OQ-008** | Group B/C interview: CRR Rule 11 III(g) vs Rule 22.8 | M18 Scrutiny, M21 merit | Legal + Registrar | Wave 6 |
| **OQ-009** | The six Table 2 ambiguities (§3.1 below) | M20 Scoring Engine | Executive Council | Wave 7 |
| **OQ-010** | Percentage ↔ CGPA conversion policy | M04, M20 | Registrar's Office | Wave 4 |
| **OQ-012** | CRR Rule 33.3 (bar on marrying a person with a living spouse) — encode as a validation rule? | M05 declarations | **Legal sign-off required** | Wave 4 |
| **OQ-013** | Reservation policy applicable at AMU given its minority-institution litigation | M17 Roster | Legal + Registrar | Wave 3 |
| **OQ-015** | Dean's-office role granularity — one OU-scoped role, or separate scrutiny and appointment roles? | M25 RBAC | Registrar's Office | Wave 1 |

### 3.1 Closed

| ID | Question | Closed by | Date |
|---|---|---|---|
| OQ-002 | Staff authentication / AMU SSO | **DR-008** — dual-identifier login, no external SSO in v1 | 2026-08-27 |
| OQ-003 | Org master data: sync or re-master | **DR-009** — local and autonomous, Data Lake is import-only | 2026-08-27 |
| OQ-005 | UGC 2025 notification status | **DR-006** — 2025 remains draft; 2018 is the sole active ruleset; the 2025 ruleset is authored now and loads without a code change if notified. Draft obtained at `docs/UGC_Draft_Regulations_Teaching_2025.pdf` | 2026-08-27 |
| OQ-006 | Confirm the recruitment tracks | **DR-007 + DR-010** — all 7 post types ship; the apparent duplicates are the General/Local appointment regimes | 2026-08-27 |
| OQ-007 | Hard-copy submission retained? | **DR-011** — yes, retained | 2026-08-27 |
| OQ-011 | Record-retention schedule | **DR-011** — electronic indefinite, hard copies weeded at five years | 2026-08-27 |
| OQ-014 | Data Lake organisational-unit schema | **§6 schema review** — introspected directly | 2026-08-27 |
| OQ-016 | Faculty/Department count mismatch vs the legacy tables | **§6.3** — `faculties`/`departments`/`centres`/`campuses` are superseded first-generation tables; the tree is authoritative | 2026-08-27 |

### 3.2 OQ-009 in detail — the six Table 2 ambiguities

Each changes outcomes materially. Each needs a recorded Executive Council or legal decision before
`rules-catalogue.yaml` can be finalised. **Do not resolve these by picking the reading that seems
sensible** — a rejected candidate's counsel will read them the other way.

| # | Ambiguity | Gazette text | Why it is material |
|---|---|---|---|
| 1 | **"Augmented" — replace or add?** | *"The Research score for research papers **would be augmented** as follows"* then lists 5/10/15/20/25/30 | Whether the IF value replaces or adds to the base 8/10 per paper. For a Professor applicant with 20 papers: a **160–200 point** swing against a 120-point threshold |
| 2 | **Band boundaries overlap and are non-exhaustive** | *"less than 1 / between 1 and 2 / between 2 and 5 / between 5 and 10 / >10"* | IF exactly 1, 2 or 5 falls in **two** bands; IF exactly **10 falls in none**. Recommend upper-inclusive: `(0,1)→10, [1,2]→15, (2,5]→20, (5,10]→25, >10→30` — **needs ratification, not adoption** |
| 3 | **Which impact factor, which year?** | *"as per Thomson Reuters list"* | Clarivate JCR, but the edition is unstated — year of publication or year of application |
| 4 | **"UGC listed" is a moving target** | 2018 text says *"UGC listed"* | UGC-CARE replaced the Approved List in 2019 and was itself discontinued in 2024. Which list governs a 2015 paper? |
| 5 | **Joint supervision is self-contradictory** | *"70% of the total score for Supervisor and Co-supervisor. Supervisor and Co-supervisor, both shall get 7 marks each"* | 7 + 7 = 14 against a base of 10. Encode the literal "7 each", flag for ratification |
| 6 | **The 30% cap is circular** | cap on 5(b)+6 is *"thirty percent of the **total** research score"*, but the total includes 5(b) and 6 | Needs an explicit solved algorithm. Recommend `capped = min(raw_5b6, (3/7) × other_categories)`. Related: *"minimum of three categories out of six"* — is a candidate scoring in only two **ineligible**, or is the excess **disregarded**? |

---

## 4. Document acquisition register

These are not design problems. They are documents that must be obtained before the dependent
design can be completed, ranked by risk.

| ID | Document | Why it is needed | Blocks | Owner |
|---|---|---|---|---|
| **DOC-001** | **AMU Ordinances (Executive)** | *The single highest-value missing document.* FN-1 Part B states it applies *"where the qualifications are advertised as per Ordinances (Executive) framed in the light of the UGC Regulations, 2018"* and directs the reader to *"Appendix II of the Ordinances"* — **AMU's Ordinances are the operative instrument, not the UGC text alone.** Without them: Selection Committee composition for Registrar / Finance Officer / Controller of Examinations is undetermined (CRR Sch-1 col.12 reads only *"As per Act/Statutes/UGC Notification"*), as are teaching superannuation age, AMU's faculty→Table-2 column mapping, the shortlisting ratio, and the fee schedule. They may already resolve several OQ-009 ambiguities. | M20, M19, M16 | Registrar's Office |
| **DOC-002** | **Post-2018 UGC amendment chain** | The repo holds only the 18 July 2018 Gazette as originally notified. Missing: the 2021 deferral of cl. 3.10; the claimed 2023 amendment making PhD optional (asserted in `UGC_TEACHING_RECRUITMENT_REGULATIONS.md` with **no source document in the repo**); and the **PhD Regulations 2022**, which superseded the 2016 M.Phil./PhD Regulations and abolished M.Phil — while the 2018 NET-exemption clause names only 2009 and 2016, leaving unresolved whether a **2022-compliant PhD triggers exemption**. That is the single most-used eligibility pathway in the system. | M20, DR-006 | Registrar's Office |
| **DOC-003** | **Reservation framework** — Central Educational Institutions (Reservation in Teachers' Cadre) Act 2019 + Rules; DoPT reservation OMs; RPwD Act 2016; EWS OM; OBC creamy-layer ceiling and certificate validity | **No substantive reservation rules exist in either local document.** Both merely incorporate GoI instructions by reference. Missing: percentages, the roster (100/200/13-point), roster unit, backlog/carry-forward/interchange, EWS (absent from the 2018 Regulations entirely), Ex-Servicemen (the CRR relaxation cell is **literally blank**), PwBD horizontal reservation, fee concessions, and the age-relaxation table. | M17 Roster | Registrar's Office + Legal |
| **DOC-004** | **AMU's own CRR for cadres absent from the UGC model rules** | The model rules' 58 cadres contain **no Medical Officer, Nursing, Pharmacist, Paramedical, Radiographer, hospital Lab Technician or Physiotherapist** cadre — despite Rule 28(a) expressly providing DACP for Medical Officers. AMU operates JNMC Hospital, Ajmal Khan Tibbiya College Hospital and a Dental College. Also absent: Horticulture, Press, Farm, Veterinary, Sports coaches, Curator, Archives. CRR Rule 19.1 lets the University frame its own rules where UGC guidelines don't exist. | M16, M20 non-teaching | Registrar's Office |
| **DOC-005** | **AMU school-teacher recruitment rules** | AMU runs ~10 schools. Their recruitment is governed by neither the UGC 2018 Regulations nor the non-teaching CRR. **No rule source exists in the repository at all**, yet two live `post_types` rows depend on it. | DR-007 school track | Directorate of School Education |
| **DOC-006** | **Part B2 (Librarian) and Part B3 (Physical Education) form contents** | Referenced by the AMU manual but present nowhere in `docs/`. UGC Tables 4 and 5 are *CAS grading* instruments, not direct-recruitment scoring, so they cannot be substituted. | DR-007 librarian and PE tracks | Registrar's Office |
| **DOC-007** | **Source PDFs re-extraction** | Both `.txt` corpora in `docs/research/` are OCR-derived and demonstrably lossy: Appendix I fitment tables missing entirely, the Executive Engineer level-upgrade clause truncated mid-sentence, the Ex-Servicemen age-relaxation cell blank, Table A columns mis-aligned, *"awarded"* substituted for *"evaluated"*. **`docs/UGC_Regulations_Teaching_Staff_2018.pdf` (4.9 MB) and `docs/UGC_Model_Cadre_Recruitment_Rules_Non_Teaching.pdf` (3.5 MB) are the authority of last resort for every figure.** | `rules-catalogue.yaml` | Implementation team |

---

## 5. Corrections carried forward

Errors found in the previous planning artefacts that are **binding corrections** on all new work.
Any document repeating these is wrong.

| # | Previous artefact says | Correct position | Evidence |
|---|---|---|---|
| 1 | `domain-model.md`: `Advertisement N:1 Post` | **`Advertisement 1:N Post`** | `posts.advertisement_id` FK; advertisement 884 owns posts 2599–2602 |
| 2 | `UGC_TEACHING_RECRUITMENT_REGULATIONS.md:203-207`: IF bands 5/10/15/20/25 | **5 / 10 / 15 / 20 / 25 / 30** — the *"IF less than 1"* band is missing and every band is shifted down one | `ugc_regulations_2018.txt:8535-8583` |
| 3 | `ugc-rules.yaml:28-30`: `pi: 1.0, co_pi: 0.5` | **PI and Co-PI get 50% each** | `:8594`, and FN-1 reproduces it correctly |
| 4 | `…REGULATIONS.md:188-189`: projects merged as ">10 L = 10, <10 L = 5" | **Two separate rows** — Completed 10 / 05, **Ongoing 05 / 02** | `:8416-8438` |
| 5 | `…REGULATIONS.md:217`: 30% cap applies to category 6 alone | Cap is on the **combined** 5(b) **and** 6 | `:8613-8615` |
| 6 | `…REGULATIONS.md:225`: Professor committee chaired by *"VC or their nominee"* | **Professor and Senior Professor: the Vice-Chancellor, no nominee.** The nominee formula applies only to Assistant and Associate Professor | `:5399`, `:5430` vs `:5346`, `:5369` |
| 7 | `ugc-rules.yaml:7-10`: `base_marks: 8` flat | **Two faculty columns** — 8 per paper (Sciences/Engineering/Agriculture/Medical/Veterinary), **10** per paper (Languages/Humanities/Arts/Social Sciences/Library/Education/Physical Education/Commerce/Management) | Table 2 header |
| 8 | `ugc-rules.yaml` contains no Table 3A | **Tables 3A and 3B are mandatory** — without them no Assistant Professor recruitment can be shortlisted | cl. 4.1 I Note |
| 9 | `PROGRESS.md:12`: *"Implemented Auth Module with TOTP stubs"* | **No TOTP exists.** Exhaustive grep for `totp\|two_factor\|2fa\|google2fa` across `app/ routes/ config/ database/ resources/` returns zero hits | verified 2026-08-27 |
| 10 | `PROGRESS.md`: *"Mapped Requirements: 29, Unmapped: 0"* | **The traceability matrix maps nothing.** All 29 rows cite `MODULES.md §5.x` (no §5 exists) and `SRS-0xx` (the SRS uses `REQ-APP/ADM/MAND`); `CodeArtefact` and `TestCase` are `TODO` on all 29 | verified 2026-08-27 |
| 11 | `security.md`, `state-machine.md`, `MEMORY.md`: hash-chained immutable audit logs | **Not implemented.** `audit_logs` is a stock Spatie table with a mutable `updated_at` and no `hash` / `previous_hash` / sequence | schema DDL |
| 12 | `MEMORY.md:22`: *"Laravel 13 (currently running 11.56.0 compat layer)"* | **Laravel 13.26.1 on PHP 8.5.9.** The note is stale | `php artisan --version` |

**One correction in the other direction, and it matters:** the legacy **FN-1 form reproduces Table 2
verbatim and correctly** — all six IF bands, both authorship rules, the 50/50 PI split, the "7 marks
each" supervision note, the 30% combined cap and the three-of-six floor. **FN-1 is a more faithful
transcription than either derived file** and should be used as the cross-check when authoring
`rules-catalogue.yaml`.

---

## 6. Data Lake schema review

Read-only introspection of `datalakeamu_db` (175 tables) and `careers_db` (43 tables), 2026-08-27.
This closes OQ-014 and is the evidence behind DR-009 and DR-012.

### 6.1 What is there

**`organizational_units`** — 301 rows. Self-referential tree; all 301 have `parent_id` **and**
`type_id`. `title` and `code` are both unique.

```sql
id bigint unsigned PK · title varchar(255) NOT NULL UNIQUE · code varchar(255) NULL UNIQUE
title_hindi varchar(255) NULL · title_urdu varchar(255) NULL · category varchar(255) NULL
status varchar(255) NULL · remarks longtext · created_at/updated_at/deleted_at
parent_id bigint unsigned NULL · type_id bigint unsigned NULL
-- indexes named parent_fk_6156465 etc., but NO actual FOREIGN KEY constraints
```

**`organizational_units_types`** — 29 rows, itself a tree (`parent_id`), with
`category` ∈ {`Academic`, `Administrative`}. `Campus` (id 2) is the root.

Recruitment-relevant unit counts by type: **Faculty 13 → Department 111**, Academic Centre 18,
AMU Schools 11, Sections Registrar's Office 22, Office of COE 17, Finance & Accounts 24, Services &
Other Offices 39, Hall of Residence 20, College 3, Polytechnic 2, Institute 1.

**This is exactly the shape DR-010 needs** — Dean scoping is one subtree walk from a Faculty node.

**`designations`** — 346 rows, and **the table is a bare name list**:

| Column | Non-null rows (of 346) |
|---|---:|
| `code` | **0** |
| `pay_grade` | **0** |
| `retirement_age` | **0** |
| `type_id` | **0** |
| `remarks` | **0** |

`designation_types` has **0 rows**. Every designation was written in one bulk insert at
`2022-09-21 01:56:17`, uppercased (`ASSISTANT PROFESSOR`, `ASSISTANT REGISTRAR`, `ANIMAL KEEPER`).
**No sanctioned-strength table exists anywhere in the 175.** There is nothing to link to — hence
DR-012. The 346 names become a seed vocabulary, mapped to the 12 UGC teaching cadres and the 58
CRR non-teaching cadres.

**`careers_db.posts` carries no organisational-unit reference at all.** The department is embedded in
free text — `title` (*"Assistant Professor, Dept of Conservative Dentistry & Endodontics"*) and
`location varchar(300)`. Mapping ~2,874 legacy posts to units is therefore a Wave 10 task **with a
manual review step**, not an automatic join. *(The same query confirmed §5 correction context: legacy
`posts` does carry `age_limit`, `experience`, `selection_method` and the four admit-card /
interview-letter window columns that the betacareers redesign dropped.)*

### 6.2 What we copy, and the seven things we fix

| # | Data Lake | CareersPro | Why |
|---|---|---|---|
| 1 | `code` nullable — **10 of 301 NULL** | `code` **NOT NULL**, unique | It is the snapshot identifier that survives renames; it cannot be optional. Backfill the 10 at import |
| 2 | `category` on **units** — NULL in all 301 | dropped from units; kept on **types** | Dead column in the source; category genuinely lives on the type |
| 3 | `category`, `status` as `varchar(255)` | PHP enums — `academic\|administrative`, `draft\|published` | Only 2 and 2 distinct values exist; varchar invites drift |
| 4 | **No FK constraints** — only indexes *named* `parent_fk_*` | real FKs on `parent_id`, `type_id` | The naming shows they were intended and lost. ADR-001's integrity rationale |
| 5 | recursive `parent_id` walk only | add a materialised **`path`** (`/1/10/27/`) | Dean-scoped authorisation runs on **every** admin request; it must be one indexed `LIKE`, not a recursive query |
| 6 | every type can host anything | `is_recruitment_eligible` on the type | Only some of the 29 types can carry a vacancy |
| 7 | `title_hindi` / `title_urdu` exist, **0 populated** | keep both | AMU is multilingual and this is GIGW-relevant; the columns are right, the data was never entered |

Both tables also gain `datalake_id` (nullable, unique) — **import provenance for idempotent
re-import, not a runtime link. No request-path code may read it.**

### 6.3 The legacy tables are superseded — settled

`faculties` (22), `departments` (123), `centres` (22) and `campuses` (4) are **leftovers from the
first generation of Data Lake**, when the organigram was modelled as separate tables. That approach
was abandoned in favour of `organizational_units` + `organizational_units_types`, which is **the
prevailing and authoritative model**.

**Therefore: import from the tree only. Ignore all four legacy tables.** The 13-vs-22 and 111-vs-123
count differences are the expected residue of a superseded model, not a discrepancy to reconcile.
*(OQ-016 closed 2026-08-27.)*

### 6.4 Import data-quality issues

These are **source-data hygiene items, not design or code problems.** They are corrected in Data
Lake in due course and re-verified; the import must surface them rather than silently paper over
them. Tracked in [`data-hygiene-backlog.md`](data-hygiene-backlog.md).

1. **10 units have a NULL `code`**; 2 units are still `Draft`.
2. **Some units are mis-parented.** Faculty of Engineering & Technology (id 13) has
   `Controller of Examinations`, `Accounts Section COE` and `COE Secretariat` as children — COE
   offices under a Faculty. **Dean-scoped authorisation resolves by subtree, so it would inherit any
   such error.** The import reports these for correction at source.

### 6.5 Connection

`.env` carries `DATALAKE_DB_*` and `CAREERS_DB_*`. `config/database.php` defines `mysql_readonly`
(→ `CAREERS_DB_*`) at line 68 but **no `datalake` connection**; the introspection above ran on a
runtime-injected config. Add a `datalake` block following the same pattern.

**Both connections are import-only.** No runtime code may reference either. The verification suite
enforces this: the full test suite must pass with both connections removed from
`config/database.php` entirely (see the approved plan, Part 9).

---

## 7. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Register created. DR-001…DR-005 decided; DR-006, DR-007 proposed. OQ-001…OQ-013 and DOC-001…DOC-007 raised. 12 corrections recorded. | Implementation team |
| 2026-08-27 | DR-006 and DR-007 confirmed. **DR-008…DR-012 added and decided.** OQ-002/003/005/006/007/011/014 closed. OQ-015, OQ-016 raised. **§6 Data Lake schema review added.** | Implementation team |
| 2026-08-27 | OQ-016 closed — legacy organigram tables confirmed superseded. Source-data hygiene items moved to `data-hygiene-backlog.md`. | Implementation team |
