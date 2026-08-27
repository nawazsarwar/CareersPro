# M05 — Application Wizard

**Wave:** 4 · **Scope:** v1
**Depends on:** M04, M06, M07, M35, M16
**Blocked by:** **OQ-012** *(CRR Rule 33.3 as a validation rule — the declaration is captured; no
automatic disqualification is applied without legal sign-off.)*

## 1. Purpose and statutory basis

Turn a dossier into a submitted, snapshot-locked application against one post.

| Obligation | Source |
|---|---|
| Applications only in the prescribed format, with the prescribed fee | CRR Rule 11 III(a)–(c) |
| **Incomplete applications shall not be entertained** | CRR Rule 11 III(d); FN-1 instructions |
| Five track variants — Teaching, School, Non-teaching, Librarian, PE&S | DR-007 · `post_types` |
| Declaration on oath, with disciplinary consequence for false entry | FN-1 closing declaration |
| Disqualifications: conviction or pending proceedings, citizenship, medical fitness | CRR Rule 33 |

**The two legacy failures this exists to fix:** the wizard currently **500s** (its routes reference
`AppHttpControllersFrontendApplicationWizardController::class` — namespace backslashes stripped), and
the legacy system locks applications **irreversibly at payment** with *"applicants are not allowed to
update/modify… in any circumstances."*

## 2. Data

`applications` · `application_snapshots` · `eligibility_decisions` — schema in
`../01-design/domain/domain-model.md` §7.

**Critically:** `applications.rule_set_version_id` and `.relaxation_policy_version_id` are **copied
from the advertisement at submit** and are read-only thereafter. `lifecycle_state` is a **PHP enum**,
not a nullable integer that the code writes the string `'Submitted'` into.

**Indexes:** `applications(user_id, post_id)` **unique** — one application per candidate per post ·
`applications(post_id, lifecycle_state)` · `applications(application_no)` unique.

## 3. Domain services

```
App\Domain\Application\ResolveTrack::for(Post): Track                    // from post_type
App\Domain\Application\AssertCompleteness::check(User, Post): Deficiencies
App\Domain\Application\PreflightEligibility::check(User, Post): PreflightResult
App\Domain\Application\SubmitApplication::handle(User, Post): Application
App\Domain\Application\AllocateApplicationNumber::next(Post): string
```

**Invariants.**
- `SubmitApplication` is **atomic**: snapshot written, hash computed, versions copied, gate rows
  created **for the active gates only**, dossier locked, application number allocated — or nothing.
- **`PreflightEligibility` runs before payment.** Age is computed against `posts.reg_end_date`
  (CRR Rule 14), never today.
- The **active gate set** comes from `post_types.default_selection_method`. An interview-only post
  gets **two** gate rows, not three.
- One application per candidate per post, enforced by a unique index — not by a check.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/apply/{post:slug}` | `apply.start` | `auth`, `verified` | `ApplicationPolicy@create` |
| GET | `/apply/{post:slug}/{section}` | `apply.section` | as above | `@update` |
| PATCH | `/apply/{post:slug}/{section}` | `apply.save` | as above, `throttle:120,1` | `@update` |
| GET | `/apply/{post:slug}/preview` | `apply.preview` | as above | `@update` |
| POST | `/apply/{post:slug}/submit` | `apply.submit` | as above, `throttle:10,60` | `@submit` |
| POST | `/applications/{application}/withdraw` | `applications.withdraw` | as above | `@withdraw` |

**Route names are unique.** The current `routes/web.php` registers `apply` and then
`Route::resource('application-forms')`, both claiming `application-forms.store` — the later
registration wins, so the wizard's own `store()` is unreachable even once the class name is fixed.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `post` | exists, **published**, **open** (`now` between opening and closing), **not withdrawn** | This post is not open for applications. |
| | **no existing application** by this user for this post | You have already applied for this post. |
| `declaration` | required, accepted | You must accept the declaration to submit. |
| `applied_under_category` | required, exists:categories · **matches the profile category or is UR** | You may apply under UR or your recorded category. |
| `applied_under_horizontal_category` | nullable, exists · **requires the corresponding profile flag** | Your profile does not record a {category} claim. |
| `is_internal_candidate` | required, boolean | |
| `noc_document_id` | **required when currently employed**, exists, owned | Attach a No Objection Certificate from your employer. |
| conviction / debarment declarations | required, boolean · reason **required when true**, min:20 | Describe the circumstances. |
| `citizenship` | **must be Indian** (CRR Rule 33.4) | Only Indian citizens are eligible for this post. |

**Completeness, enforced at submit** — *"incomplete applications shall not be entertained"*:
all Part-A sections complete · at least **two referees** · photo, signature and thumb impression
present · every score-bearing claim carrying evidence · for teaching tracks, Part B and Part C
present · every qualification either a percentage or a **declared** CGPA conversion (M04-R07).

**Rule 33.3** — the marriage bar — is **captured as a declaration and applies no automatic
disqualification** pending OQ-012.

## 6. Authorisation

`ApplicationPolicy` — **ownership scope** for the candidate. `create` additionally requires the post
to be open. `update` requires `lifecycle_state ∈ {draft}` **or** an open deficiency window.
`submit` requires `AssertCompleteness` to pass. `withdraw` requires not-yet-`selected` and before
the closing date.

Staff read access is granted here, scoped by OU (DR-010) — this is the policy through which a
scrutiny officer reaches a candidate's dossier and documents.

## 7. UI

The **spine** (`../01-design/ux/design-system.md` §4.2), sections resolved by track.

**Four deliberate departures from the legacy flow**, each a recorded pain point:

1. **No sequential gating.** Every section reachable; completion shown; submission validates the
   whole.
2. **Auto-save**, with a visible saved-at time.
3. **Eligibility pre-check before payment**, stating exactly which criterion fails and citing it.
4. **Preview renders the statutory format** exactly as it will print (M09).

Submit is a confirmation dialogue that states what becomes immutable:

> Submitting locks your dossier for this application and records a permanent snapshot. Corrections
> afterwards are possible only if scrutiny raises a deficiency. **The fee is non-refundable.**

## 8. Worked example

Aisha applies to **post 2599, System Manager** (non-teaching, `written_skill_interview`).

1. `ResolveTrack` → **Non-teaching**: Part A only. Part B and C are not rendered.
2. `PreflightEligibility`: DOB 1991-04-02 → age at **`reg_end_date` 2026-03-07** = **34y 11m**,
   under the designation's max 50 → pass. Experience 4y 2m against a 3-year minimum → pass.
3. `AssertCompleteness` finds no thumb impression → submission blocked, with a direct link to A2.
4. She uploads it. Preview renders the `fn3_general_nt` format.
5. **Submit.** Atomically: snapshot #1 written, `content_hash 7c21…`; `rule_set_version_id =
   ugc-crr-non-teaching-2022@1` copied; **three** gate rows created (scrutiny, written_test,
   interview) because the post type is `written_skill_interview`; dossier locked; application number
   `2599/2026/00412` allocated. `lifecycle_state → submitted`.
6. She edits a qualification in her dossier. It is **refused** — the row is referenced by a submitted
   snapshot. Her *other* draft application to a different post is unaffected, because it holds no
   snapshot yet.

Had the post been `interview_only`, step 5 would have created **two** gate rows.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M05-R01 | Given a closed post, when applying, then it is refused |
| M05-R02 | Given an existing application for the post, when applying again, then it is refused |
| M05-R03 | Given an incomplete dossier, when submitting, then it is refused, **naming each missing item with a link** |
| M05-R04 | Given submission, when it completes, then a snapshot exists with a content hash |
| M05-R05 | Given submission, when it completes, then the ruleset and policy versions are copied and read-only |
| M05-R06 | Given an `interview_only` post, when submitted, then **two** gate rows are created |
| M05-R07 | Given a `written_skill_interview` post, when submitted, then **three** gate rows are created |
| M05-R08 | Given a submitted application, when a referenced dossier row is edited, then it is refused |
| M05-R09 | Given a failure mid-submit, when it rolls back, then **no** snapshot, gate rows or application number are left behind |
| M05-R10 | Given an application number, when allocated, then it is unique per post and gapless |
| M05-R11 | Given age evaluation, when computed, then it uses `posts.reg_end_date`, **not today** |
| M05-R12 | Given a candidate ineligible on age, when reaching the payment step, then they are stopped **before** paying |
| M05-R13 | Given candidate A, when opening candidate B's application, then **403** |
| M05-R14 | Given a non-Indian citizenship declaration, when submitting, then it is refused, citing Rule 33.4 |
| M05-R15 | Given a Rule 33.3 declaration of `true`, when submitting, then it is **recorded** and submission **proceeds** (OQ-012 open) |
| M05-R16 | Given a teaching post, when the track resolves, then Part B and Part C sections are rendered |

## 10. Test cases

`tests/Feature/Application/EligibilityToApplyTest` — R01, R02, R11, R12, R14 ·
`CompletenessTest` — R03 · `SubmitTest` — R04, R05, R09, R10 ·
`GateCreationTest` — **R06, R07** · `DossierLockTest` — R08 ·
`Authz/ApplicationOwnershipTest` — R13 · `Rule333Test` — R15 · `TrackResolutionTest` — R16.

Fixtures: `PostFactory` with `interviewOnly()` and `writtenSkillInterview()` states;
a complete-dossier `UserFactory` state and a deliberately incomplete one.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R14 | `App\Http\Requests\Application\SubmitApplicationRequest` |
| R03 | `App\Domain\Application\AssertCompleteness` |
| R04–R07, R09, R10 | `App\Domain\Application\SubmitApplication` |
| R08 | `App\Domain\Dossier\AssertDossierUnlocked` (M04) |
| R11, R12 | `App\Domain\Application\PreflightEligibility` |
| R13 | `App\Policies\ApplicationPolicy` |
| R16 | `App\Domain\Application\ResolveTrack` |
