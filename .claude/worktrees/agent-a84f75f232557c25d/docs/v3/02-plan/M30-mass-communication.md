# M30 — Mass Communication Engine

**Wave:** 9 · **Scope:** v1
**Depends on:** M16, M25, M26
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Templated bulk email and SMS, targeted by advertisement or post.

**Explicitly required by `docs/Intro.md`:** *"There will be a mass emailing service where the
administrators can select the post of a particular post or of a particular advertisement and send
bulk emails for information to the users."*

**Absent from the previous module catalogue entirely**, despite being a stated requirement and a live
capability (`mail_logs` in the Manage Careers sidebar).

No direct statutory basis, but two obligations bear on it: **CRR Rule 11 III(d)** — deadline changes
must reach candidates — and **DPDP 2023**, since bulk messaging is processing of personal data at
scale (`../01-design/security/data-protection.md` §7).

## 2. Data

```
mail_templates   id · code UNIQUE · channel enum(email, sms)
                 subject · body · variables json · is_system bool
mail_campaigns   id · name · template_id
                 advertisement_id NULL · post_id NULL
                 segment json                    -- gate state, category, payment state
                 channel · scheduled_at NULL · started_at NULL · completed_at NULL
                 recipient_count · sent_count · failed_count
                 status enum(draft, approved, queued, sending, completed, cancelled)
                 created_by_id · approved_by_id NULL
mail_logs        id · campaign_id NULL · application_id NULL · user_id
                 channel · to · subject · status enum(queued, sent, failed, bounced)
                 provider_ref · error · sent_at
suppressions     id · email NULL · mobile NULL · reason · created_at
```

**Indexes:** `mail_logs(campaign_id, status)` · `mail_logs(user_id, sent_at)` ·
`suppressions(email)` · `suppressions(mobile)`.

## 3. Domain services

```
App\Domain\Communication\ResolveSegment::for(Campaign): Builder
App\Domain\Communication\RenderTemplate::handle(MailTemplate, Application): RenderedMessage
App\Domain\Communication\DispatchCampaign::handle(Campaign, User): void
App\Domain\Communication\PreviewCampaign::for(Campaign): CampaignPreview
```

**Invariants.**
- **`ResolveSegment` applies `visibleTo($user)` first.** A Dean's-office user cannot message another
  faculty's candidates.
- **A campaign requires approval by a second user before dispatch.** Bulk messaging 78,000 people is
  irreversible; a single mistaken send is a serious incident.
- **Suppressions are honoured absolutely** — bounced addresses and opt-outs are never re-attempted.
- Templates are rendered with **escaped** variables from a **declared** variable list. An undeclared
  variable throws at preview, never at send.
- **Transactional messages are not campaigns.** Verification, one-time codes for login and for the
  second factor, deficiency and admit-card notifications go through the notification layer and are
  **never suppressible** — a candidate who has opted out of bulk mail must still be able to sign in.
  `suppressions` is consulted by `DispatchCampaign` and by nothing else.
- **Both share one SMS adapter.** Campaigns and one-time codes send through
  `App\Domain\Notification\Sms\SmsGateway` (DR-024); this module owns neither the provider nor
  the credentials. `mail_logs.provider_ref` holds whatever reference the adapter returns.
- Every dispatch writes an audit event with the segment, recipient count and template.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET/POST/PATCH | `/admin/mail/templates/{t?}` | `admin.mail.templates.*` | `MailTemplatePolicy@*` |
| GET/POST | `/admin/mail/campaigns/{c?}` | `admin.mail.campaigns.*` | `CampaignPolicy@*` |
| POST | `/admin/mail/campaigns/{c}/preview` | `admin.mail.campaigns.preview` | `@create` |
| POST | `/admin/mail/campaigns/{c}/approve` | `admin.mail.campaigns.approve` | `@approve` |
| POST | `/admin/mail/campaigns/{c}/send` | `admin.mail.campaigns.send` | `@send` |
| POST | `/admin/mail/campaigns/{c}/cancel` | `admin.mail.campaigns.cancel` | `@cancel` |
| GET | `/admin/mail/logs` | `admin.mail.logs` | `CampaignPolicy@viewLogs` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `template_id` | required, exists · **channel must match the campaign** | |
| `advertisement_id` / `post_id` | **at least one required**, exists, **within scope** | Select an advertisement or a post. |
| `segment` | required, json · **keys from the declared segment schema** | Unknown segment key: {key}. |
| `body` | required · **every `{{variable}}` must be in `variables`** | Undeclared variable: {{name}}. |
| `subject` | `required_if:channel,email`, max:200 | |
| sms `body` | **max 480 characters** (3 segments) | An SMS may be at most 480 characters. |
| approve | **approver ≠ creator** | A second person must approve a bulk send. |
| send | **status `approved`**, recipient count > 0 | |
| | **recipient count ≤ 20,000 per campaign** | Split campaigns above 20,000 recipients. |

## 6. Authorisation

`CampaignPolicy` extends `ScopedPolicy`. `create` for `recruitment_admin` and `dean_office`
(subtree, local posts only). **`approve` and `send` for `super_admin` and `recruitment_admin`, and
never the campaign's creator.**

`viewLogs` shows recipient addresses to `super_admin` only; others see counts and statuses.

## 7. UI

**Template editor** with the declared variable list beside the body and a live rendered preview
against a sample application.

**Campaign builder:** advertisement → post → segment filters (gate state, category, payment state,
attendance) with a **live recipient count**. Preview renders **three real messages** with real
substitutions, and shows the suppression count separately.

Send is a confirmation stating the count and that it cannot be undone. Progress is live; cancel stops
the remaining queue but cannot recall what has gone.

## 8. Worked example

The closing date for post 2599 is extended by corrigendum. The Registrar's office needs to tell
everyone who has started but not submitted.

1. Campaign *"2599 — closing date extended"*, template `deadline_extended`, channel email.
2. Segment: `post_id = 2599`, `lifecycle_state = draft`. Live count: **41 recipients**, minus **2
   suppressed** → 39.
3. Preview renders three real messages with the candidate's name, the post title and the **new**
   closing date pulled from the corrigendum.
4. The creator cannot send. A second admin approves. Dispatch is queued.
5. 39 sent, 1 bounces → `suppressions` gains that address; it is never retried.
6. An audit entry records the segment, count and template.
7. A Dean's-office user builds the same campaign for a Faculty of Commerce post → the advertisement
   is not in their subtree → **403**.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M30-R01 | Given a campaign creator, when they attempt to send their own campaign, then it is refused |
| M30-R02 | Given a Dean's-office user of Faculty X, when segmenting Faculty Y candidates, then **403** |
| M30-R03 | Given a suppressed address, when a campaign runs, then it is not attempted |
| M30-R04 | Given a bounce, when recorded, then a suppression is created |
| M30-R05 | Given an undeclared template variable, when previewed, then it throws before any send |
| M30-R06 | Given a preview, when requested, then **nothing is sent** |
| M30-R07 | Given a segment, when the count is shown, then it matches the dispatched count minus suppressions |
| M30-R08 | Given a campaign above 20,000 recipients, when sent, then it is refused |
| M30-R09 | Given a cancellation mid-send, when issued, then the remaining queue stops |
| M30-R10 | Given a dispatch, when it completes, then an audit event records segment, count and template |
| M30-R11 | Given a transactional notification, when a suppression exists, then it is **still delivered** |
| M30-R12 | Given an SMS body over 480 characters, when saved, then validation fails |

## 10. Test cases

`tests/Feature/Admin/Communication/ApprovalTest` — R01, R08 · `SegmentScopeTest` — R02, R07 ·
`SuppressionTest` — R03, R04, **R11** · `TemplateTest` — R05, R12 · `PreviewTest` — R06 ·
`CancelTest` — R09 · `AuditTest` — R10.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R08 | `App\Policies\CampaignPolicy@send` |
| R02, R07 | `App\Domain\Communication\ResolveSegment` |
| R03, R04, R11 | `App\Domain\Communication\DispatchCampaign`, `suppressions` |
| R05, R06, R12 | `App\Domain\Communication\RenderTemplate`, `PreviewCampaign` |
| R09, R10 | `App\Jobs\DispatchCampaignBatch` |
