# M14 — Results & Merit Lists

**Wave:** 8 · **Scope:** v1 *(joining formalities are out of scope — the Academic ERP owns them)*
**Depends on:** M20, M21, M19, M13, M22
**Blocked by:** **OQ-008** *(non-teaching merit arithmetic — teaching merit is unaffected)*

## 1. Purpose and statutory basis

Produce the ranked merit list, waitlist and offers.

| Obligation | Source |
|---|---|
| **Teaching: selection is based only on interview performance** | UGC 2018 cl. 4.1 I Note, cl. 5.3 |
| **Non-teaching: merit from Paper I + Paper II + interview (20%), subject to qualifying the skill test** | CRR Rule 11 III(g) |
| **No interview for Group B and C** per MHRD letter 19-50/2015-Desk-U | CRR Rule 22.8 — **conflicts with the above; OQ-008** |
| Seniority determined by **position in the merit list recommended by the Selection Committee** | CRR Rule 20 |
| Recommendation signed by all committee members on the day | UGC 2018 cl. 5.3 |
| EC is the appointing authority for Group A; VC for Group B; Registrar for Group C | CRR Rule 6 |

## 2. Data

```
merit_lists    id · post_id · rule_set_version_id · relaxation_policy_version_id NULL
               strategy enum(teaching_interview_only, non_teaching_composite)
               committee_id · generated_at · generated_by_id
               approved_by_id NULL · approved_at NULL · published_at NULL
               status enum(draft, approved, published, superseded)
               tie_break_rule
merit_entries  id · merit_list_id · application_id
               rank · category · horizontal_category NULL
               score decimal(8,2) NULL · component_breakdown json
               outcome enum(selected, waitlisted, not_selected)
               waitlist_position NULL
               UNIQUE (merit_list_id, application_id)
               UNIQUE (merit_list_id, rank)

-- merit_lists additionally records the committee's Ch. V §3 recommendations:
--   recommend_waive_probation bool · recommend_advance_increments int NULL
--   order_of_preference json NULL

offers         id · merit_entry_id · issued_at · valid_until
               status enum(issued, accepted, declined, lapsed, withdrawn)
               responded_at · document_id
```

**Indexes:** `merit_entries(merit_list_id, rank)` · `offers(status, valid_until)`.

## 3. Domain services

```
App\Domain\Merit\GenerateMeritList::handle(Post, Committee, User): MeritList
App\Domain\Merit\ResolveStrategy::for(Designation, RuleSetVersion): MeritStrategy
App\Domain\Merit\IssueOffer::handle(MeritEntry, User): Offer
App\Domain\Merit\PromoteFromWaitlist::handle(MeritList): int
```

**The invariant that matters most:**

```php
final class TeachingMeritStrategy implements MeritStrategy {
    public function rank(array $inputs): MeritList {
        if (isset($inputs['shortlisting_score'])) {
            throw new StatutoryViolation(
                'UGC 2018 cl. 4.1 I Note: shortlisting score must not enter a teaching merit list'
            );
        }
        // ranks on interview_score alone
    }
}
```

**It throws rather than ignoring.** A silent drop would let a caller believe the score was
considered. **REG-08.**

**Other invariants.** The strategy resolves from the **frozen ruleset**, never a runtime flag ·
only candidates with **all active gates `eligible`** are rankable · `NonTeachingMeritStrategy`
**refuses to finalise while OQ-008 is open** and reports `PendingRatificationError` rather than
choosing between a 240 and a 100 composite · **ties resolve by the Chairperson's casting vote** (AMU Ordinances Ch. V §4),
recorded on the merit list — never by database order · a merit list is **superseded, never edited** after approval.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| POST | `/admin/posts/{post}/merit-lists` | `admin.merit.store` | `MeritPolicy@generate` |
| GET | `/admin/merit-lists/{list}` | `admin.merit.show` | `@view` |
| POST | `/admin/merit-lists/{list}/approve` | `admin.merit.approve` | `@approve` |
| POST | `/admin/merit-lists/{list}/publish` | `admin.merit.publish` | `@publish` |
| POST | `/admin/merit-entries/{entry}/offer` | `admin.merit.offer` | `@issueOffer` |
| POST | `/offers/{offer}/respond` | `offers.respond` | `OfferPolicy@respond` |
| GET | `/posts/{post:slug}/result` | `results.public` | — *(published only)* |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| generation | **committee must be concluded** | Conclude the selection committee first. |
| | **all attending members signed off** | |
| | **every candidate has all active gates decided** | {n} candidates have undecided gates. |
| | **teaching: interview scores recorded for all attendees** | |
| `tie_break_rule` | required · **must be `chairperson_casting_vote`** for a selection-committee merit list (AMU Ord. Ch. V §4) | Ties are broken by the Chairperson's casting vote. |
| approve | **status `draft`**, approver ≠ generator | The approver must differ from the generator. |
| | **approver matches the appointing authority for the group** (CRR Rule 6) | |
| publish | **status `approved`** | |
| offer `valid_until` | required, date, `after:today` | |
| offer | **entry outcome must be `selected`** | |
| waitlist promotion | **an offer must have lapsed, declined or been withdrawn** | |

## 6. Authorisation

`MeritPolicy` extends `ScopedPolicy`. `generate` for `recruitment_admin` and `dean_office` (subtree,
local posts). **`approve` is restricted by CRR Rule 6** — Group A requires the Executive Council
role, Group B the Vice-Chancellor role, Group C the Registrar role. Approver ≠ generator, always.

`OfferPolicy@respond` — **ownership only**; the candidate responds to their own offer.

## 7. UI

**Generation** shows the resolved strategy and its source **before** running:

> Strategy: **Teaching — interview performance only** · UGC 2018 cl. 4.1 I Note · ruleset
> `ugc-teaching-2018@1`

**Merit list:** rank, application number, name, category, score, component breakdown, outcome.
Category-wise tabs. The **shortlisting-only notice** appears on any teaching list.

**Approval** names the required appointing authority and refuses if the actor does not hold it.

**Public result** shows rank, application number and outcome — **no personal data beyond the name**,
consistent with the RTI position in `../01-design/security/data-protection.md` §6.

## 8. Worked example

**Post 884, Assistant Professor (teaching), 1 vacancy.** Committee concluded 22 May; 13 interviewed.

`ResolveStrategy` → `TeachingMeritStrategy` from `ugc-teaching-2018@1`. Ranking uses **interview
scores alone**. The Table 3A shortlisting scores — which decided who was interviewed — play **no
part**. A developer who passes them gets `StatutoryViolation`.

Rank 1 selected; ranks 2–4 waitlisted; 5–13 not selected. Approval requires the **Executive Council**
role (Academic Level 10 is Group A), and the approver differs from the generator.

Rank 1 declines within the offer window → `PromoteFromWaitlist` issues to rank 2, records the
promotion and the reason. **The merit list itself is untouched** — the ranking is a statutory record.

**Post 2599, System Manager (non-teaching).** Papers I and II computed; skill test qualified for 8.
`NonTeachingMeritStrategy` **refuses to finalise**: CRR-AMB-01 (whether Group A gets an interview at
all) and CRR-AMB-02 (240 or 100 composite) are unratified. It reports `PendingRatificationError`,
and the UI states which legal question is outstanding and who owns it.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M14-R01 | Given a teaching merit list, when a shortlisting score is passed, then `StatutoryViolation` — **REG-08** |
| M14-R02 | Given a teaching post, when ranked, then the order derives from interview scores alone |
| M14-R03 | Given OQ-008 open, when a non-teaching merit list is finalised, then `PendingRatificationError` |
| M14-R04 | Given an unconcluded committee, when generating, then it is refused |
| M14-R05 | Given undecided gates, when generating, then it is refused with the count |
| M14-R06 | Given the same generator and approver, when approving, then it is refused |
| M14-R07 | Given a Group A post, when approved by a non-EC role, then it is refused, citing Rule 6 |
| M14-R08 | Given tied scores, when ranked, then the **Chairperson's casting vote** resolves them and is recorded on the merit list |
| M14-R09 | Given an approved list, when edited, then it is refused; superseding creates a new version |
| M14-R10 | Given a declined offer, when the waitlist is promoted, then the merit list is **unchanged** |
| M14-R11 | Given a draft list, when the public result is requested, then **404** |
| M14-R12 | Given a published result, when rendered, then no personal data beyond the name appears |
| M14-R13 | Given candidate A, when responding to B's offer, then **403** |
| M14-R14 | Given a skill-test mark, when merit is computed, then it is **never added** |

## 10. Test cases

**`tests/Unit/Merit/TeachingMeritStrategyTest` — R01, R02 (REG-08)** ·
`NonTeachingMeritTest` — R03, R14 · `GenerationGuardTest` — R04, R05 ·
`ApprovalAuthorityTest` — **R06, R07** · `CastingVoteTieBreakTest` — R08 · `SupersessionTest` — R09, R10 ·
`PublicResultTest` — R11, R12 · `Authz/OfferTest` — R13.

Fixtures: a concluded committee with 13 interview scores including a deliberate tie; posts in
Groups A, B and C for R07.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02 | `App\Domain\Merit\TeachingMeritStrategy` |
| R03, R14 | `App\Domain\Merit\NonTeachingMeritStrategy` |
| R04, R05, R08 | `App\Domain\Merit\GenerateMeritList` |
| R06, R07 | `App\Policies\MeritPolicy@approve` |
| R09, R10 | `App\Domain\Merit\PromoteFromWaitlist` |
| R11–R13 | public controller, `App\Policies\OfferPolicy` |
