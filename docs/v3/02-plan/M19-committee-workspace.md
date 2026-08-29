# M19 — Committee Workspace

**Wave:** 6 · **Scope:** v1
**Depends on:** M18, M25
**Blocked by:** **DOC-008** *(AMU **Statutes** — DOC-001 is closed, and it located the composition
for Registrar, Finance Officer and Controller of Examinations in the Statutes, not the Ordinances.)*
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

A confidential space for Screening and Selection Committees to review applicants, score, and sign off.

| Obligation | Source |
|---|---|
| Committee composition and quorum per cadre | UGC 2018 cl. 5.1 · CRR Schedule-II |
| **Selection completes on the day of the meeting**, minutes recorded and signed by all members | UGC 2018 cl. 5.1 VIII(c), cl. 5.3 |
| Screening Committee verifies Proforma grades | UGC 2018 cl. 5.2 |
| Reserved-category nominee **one level above the applicant's cadre**, ensuring norms are followed | UGC 2018 cl. 6.0 III |
| HoD must be **of the same or higher rank** than the post | UGC 2018 cl. 5.4 |
| Minority representative associated **only where vacancies ≥ 10** | CRR Schedule-II |
| **Under UGC 2025 the committee makes the determination**, not an arithmetic engine | 2025 draft cl. 3.11 |
| **Notice not less than 10 days** before each meeting, with the prior consent of the Visitor nominee or experts | **AMU Ordinances Ch. V §1** |
| Committee recommends to the **Executive Council** | AMU Ord. Ch. V §2, Statute 29 |
| May recommend **waiving probation, advance increments, an order of preference** | AMU Ord. Ch. V §3 |
| **Chairperson has a casting vote in a tie** | **AMU Ord. Ch. V §4** |
| **Recusal on 24 declared relations**, including step-relations | **AMU Ord. Ch. V §6** |
| A **written undertaking** is obtained from every member **before the selection process commences**, covering relationship *and any conflict of interest* | **AMU CRR Rule 16** |
| Selection Committee and DPC constitution is specified **per post in Schedule-1** of the AMU CRR | **AMU CRR Rule 16** |

**Composition is still missing for three posts, but now precisely located.** CRR Schedule-1 column 12
for Registrar, Finance Officer and Controller of Examinations reads *"As per Act/Statutes/UGC
Notification"*, and AMU Ordinances Chapter XI §5 confirms appointment is *"by the duly constituted
selection committee **as provided in the Statutes**"*. **DOC-008 — the AMU Statutes — is the document
needed.** Every other cadre can be fully specified now.

## 2. Data

```
committees        id · post_id · type enum(screening, selection, dpc, dcc)
                  constituted_on · meeting_at NULL
                  quorum_required · min_external_experts · minority_rep_required bool
                  status enum(constituted, in_session, concluded)
committee_members id · committee_id · user_id NULL · external_name NULL · external_email NULL
                  role enum(chairperson, subject_expert, dean, hod,
                            reserved_category_nominee, visitor_nominee, member)
                  is_external bool · attended bool NULL · signed_off_at NULL
committee_notes   id · committee_id · application_id · member_id
                  note text · created_at        -- append only
```

**Indexes:** `committee_members(committee_id, role)` · `committee_notes(committee_id, application_id)`.

## 3. Domain services

```
App\Domain\Committee\ConstituteCommittee::handle(Post, CommitteeData): Committee
App\Domain\Committee\AssertQuorum::check(Committee): void
App\Domain\Committee\AssertComposition::check(Committee): void
App\Domain\Committee\RecordSignOff::handle(Committee, CommitteeMember): void
App\Domain\Committee\ConcludeCommittee::handle(Committee): void
```

**Invariants.**
- `AssertComposition` enforces the cadre's rule from the frozen ruleset — including that **for
  Professor and Senior Professor the chair is the Vice-Chancellor in person, no nominee**
  (`ugc-teaching-2018.md` §6, correction 6).
- `AssertQuorum` enforces the transcribed figures: UGC 2018 university **4 incl. 2 external**;
  colleges **5 incl. 2**; UGC 2025 **5 incl. 2**; CRR **two-thirds including the chair, ≥1 external
  and 1 reserved-category representative**.
- **`ConcludeCommittee` requires every attending member's sign-off** and records the conclusion the
  same day (cl. 5.3).
- `committee_notes` is **append-only**. Deliberations are exempt from RTI, but they are not
  rewritable.
- **A member may not sit on a committee for a post they have applied to.** Guarded.
- **Recusal on 24 declared relations** (AMU Ord. Ch. V §6): father, mother, son, daughter, son- and
  daughter-in-law, all four grandparents, grandchildren and their spouses, siblings, siblings-in-law,
  spouse's father, spouse's sister, niece or nephew of either spouse, **first cousin** of either
  spouse, uncle, aunt — **each including step-relations**. Every member declares against every
  applicant for the post; a declared relation blocks constitution and is audited.
- **Notice at least 10 days before the meeting**, with the prior consent of the Visitor nominee or
  the external experts recorded before the notice issues (Ch. V §1).
- **A signed undertaking from every member is a precondition of moving to `in_session`** (CRR Rule
  16). It covers both relationship and any other conflict of interest, and it is obtained **before**
  the process commences — not at the meeting.
- For non-teaching posts, **composition is read from the AMU CRR Schedule-1 entry for that post**,
  which specifies it inline.
- **Ties are broken by the Chairperson's casting vote** (Ch. V §4) — not by score order.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/committees` | `admin.committees.index` | `CommitteePolicy@viewAny` |
| GET/POST | `/admin/committees/{c?}` | `admin.committees.*` | `@*` |
| POST | `/admin/committees/{c}/members` | `admin.committees.members.store` | `@manage` |
| GET | `/committee/{c}` | `committee.workspace` | `CommitteePolicy@participate` |
| GET | `/committee/{c}/applications/{a}` | `committee.application` | `@participate` |
| POST | `/committee/{c}/notes` | `committee.notes.store` | `@participate` |
| POST | `/committee/{c}/sign-off` | `committee.signoff` | `@participate` |
| POST | `/admin/committees/{c}/conclude` | `admin.committees.conclude` | `@conclude` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `type` | required, in:screening,selection,dpc,dcc | |
| `members[]` | required, **composition must satisfy the cadre rule** | This committee does not satisfy {rule} for {cadre}. |
| chairperson | **exactly one**; for Professor and Senior Professor **must be the Vice-Chancellor** | The Vice-Chancellor must chair in person for this cadre. |
| external experts | **≥ the cadre minimum**, each `is_external = true` | At least {n} external subject experts are required. |
| reserved-category nominee | **required** where a candidate of those categories has applied and no member belongs to them | A reserved-category nominee is required for this committee. |
| minority representative | **required where `vacancies ≥ 10`** | |
| HoD member | **rank ≥ the advertised post** | The Head of Department must be of the same or higher rank. |
| member | **must not have applied to this post** | This person has applied for this post. |
| `meeting_at` | required to move to `in_session` | |
| conclude | **all attending members signed off** | {n} members have not signed off. |

## 6. Authorisation

`CommitteePolicy` — `manage` for `recruitment_admin` and `super_admin`; `participate` for
**members of that committee only**, and **only while `status = in_session`**. Access closes when the
committee concludes.

External experts get a **time-boxed account** valid for the committee's window only — no standing
credentials, and access revoked on conclusion.

## 7. UI

**Constitution screen:** role slots with the cadre rule shown alongside and **live validation**, so a
non-compliant committee cannot be saved and the reason is visible while building it.

**Workspace:** applicant list with dossier and documents inline (no downloads), a private note field
per member per applicant, and a sign-off action. A **quorum indicator** is always visible.

**Under a 2025-style ruleset** the workspace additionally presents the **notable-contributions
dossier** with the three external experts' recommendation field — because there the committee makes
the determination rather than reviewing a computed score.

## 8. Worked example

Post 884, **Associate Professor**, teaching, `ugc-teaching-2018@1`.

Constitution: VC's nominee (Professor, 12 years) as chair — **permitted for Associate Professor** —
Visitor's nominee, **3 external subject experts**, Dean, HoD (a Professor, so rank ≥ post), and a
reserved-category nominee because an SC candidate has applied and no member belongs to that category.
`AssertComposition` passes.

Meeting day: 5 attend, including 2 external experts → `AssertQuorum` passes (4 incl. 2 required).
Members review 12 shortlisted candidates, each recording private notes. **The merit list is built
from interview performance alone** — `TeachingMeritStrategy` throws if a shortlisting score is
passed (M20 §6).

All 5 sign off. `ConcludeCommittee` records the same-day conclusion, closes workspace access, and
writes an audit entry per sign-off.

Had one external expert not attended, quorum would fail at 1 external and the workspace would refuse
to conclude, naming the deficiency.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M19-R01 | Given a Professor committee chaired by a nominee, when saved, then it is refused, citing cl. 5.1 |
| M19-R02 | Given fewer than the required external experts, when saved, then it is refused |
| M19-R03 | Given an SC applicant and no reserved-category member, when saved without a nominee, then it is refused |
| M19-R04 | Given `vacancies ≥ 10` with no minority representative, when saved, then it is refused |
| M19-R05 | Given an HoD of lower rank than the post, when added, then it is refused |
| M19-R06 | Given a member who has applied to the post, when added, then it is refused |
| M19-R07 | Given quorum unmet, when concluding, then it is refused, naming the shortfall |
| M19-R08 | Given a member who has not signed off, when concluding, then it is refused |
| M19-R09 | Given a concluded committee, when a member opens the workspace, then **403** |
| M19-R10 | Given a non-member, when opening the workspace, then **403** |
| M19-R11 | Given a committee note, when edited or deleted, then it is refused |
| M19-R12 | Given a conclusion, when recorded, then it carries the meeting date, per cl. 5.3 |
| M19-R13 | Given a member with any of the 24 declared relations to an applicant, when constituted, then it is refused |
| M19-R14 | Given an unsigned undertaking, when moving to `in_session`, then it is refused, naming the member |
| M19-R15 | Given notice issued fewer than 10 days before the meeting, when scheduled, then it is refused |
| M19-R16 | Given a non-teaching post, when composition resolves, then it comes from the CRR Schedule-1 entry for that post |

## 10. Test cases

`tests/Feature/Admin/Committee/CompositionTest` — R01–R06 · `QuorumTest` — R07 ·
`SignOffTest` — R08, R12 · `Authz/WorkspaceAccessTest` — R09, R10 ·
`NoteImmutabilityTest` — R11 ·
`RecusalTest` — **R13** · `UndertakingTest` — **R14** · `NoticePeriodTest` — R15 ·
`CrrScheduleCompositionTest` — R16.

Fixtures: committee compositions per cadre drawn from `rules-catalogue.yaml`, so a change to the
transcribed rule fails the test rather than passing silently.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R06 | `App\Domain\Committee\AssertComposition` |
| R07 | `App\Domain\Committee\AssertQuorum` |
| R08, R12 | `App\Domain\Committee\ConcludeCommittee` |
| R09, R10 | `App\Policies\CommitteePolicy` |
| R11 | append-only guard on `committee_notes` |
| R13 | `App\Domain\Committee\AssertComposition` — declared-relations check |
| R14 | `App\Domain\Committee\ConstituteCommittee`, `committee_members.undertaking_signed_at` |
| R15 | `App\Rules\MinimumNoticePeriod` |
| R16 | `App\Domain\Establishment\*` (M35), `designations.committee_composition` |
