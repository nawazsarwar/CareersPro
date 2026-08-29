# M32 — Bulk Document Generator

**Wave:** 8 · **Scope:** v1
**Depends on:** M11, M13, M34
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Generate admit cards and interview letters in bulk, from the templates configured on the post type.

A **live admin screen** today (`media_1787422606175.png`) and **absent from the previous module
catalogue.** Its three filters — `All applicants` · `Eligible only` · `Interview eligible only` —
read the eligibility gates directly; two of them are unbuildable on the collapsed schema.

Templates come from `post_types.admit_card_template` and `.interview_letter_template`, which have
held values in production for years while **no PDF library exists in the project at all**.

## 2. Data

No new tables beyond `generated_reports` (M31) and `generated_documents` (M09).

```
generated_documents
  id · application_id · type enum(application_form, admit_card, interview_letter)
  template · snapshot_id · content_hash · media_id
  generated_at · superseded_by_id NULL
  batch_ref NULL                       -- links a bulk run
```

## 3. Domain services

```
App\Domain\Documents\BulkGenerate::handle(BulkParams, User): GeneratedReport
App\Domain\Documents\ResolveDocumentTemplate::for(Post, DocumentType): string
App\Domain\Examination\AssertDownloadWindow::check(Post, DocumentType): void   // shared with M11
```

**Invariants.**
- **`AssertDownloadWindow` gates every generation.** Admit cards only within
  `admit_card_opening_date … closing_date`; interview letters within
  `interview_letter_opening_date … closing_date`. These are the columns the previous redesign dropped.
- **Idempotent.** Re-running a batch **regenerates and supersedes**; it does not duplicate. Both
  generations are retained.
- Rendering reads the **application snapshot**, so a document regenerated in 2031 is identical.
- Every document carries a **QR verification stamp** encoding a signed URL, never personal data.
- The cohort query applies `visibleTo($user)` first.
- **Queued**, always — 58 admit cards is not a synchronous response.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/documents/bulk` | `admin.documents.bulk` | `DocumentGenerationPolicy@viewAny` |
| POST | `/admin/documents/bulk/preview` | `admin.documents.bulk.preview` | `@generate` |
| POST | `/admin/documents/bulk` | `admin.documents.bulk.generate` | `@generate` |
| GET | `/admin/documents/batches/{batch}` | `admin.documents.batch` | `@view` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `advertisement_id`, `post_id` | required, exists, related · **within scope** | |
| `document_type` | required, in:admit_card,interview_letter | |
| | **the post type must define a template for it** | No {type} template is configured for {post type}. |
| `filter` | required, in:all,eligible,interview_eligible | |
| | **the named gate must be active on this post** | The {gate} gate does not apply to this post. |
| window | **`AssertDownloadWindow` must pass** | {Type}s can be generated from {open} to {close}. |
| cohort | **non-empty** | No candidates match these criteria. |
| admit card | **roll numbers and seats allocated** | Allocate roll numbers and seats first. |
| interview letter | **interview date, time and venue set on the post** | Set the interview date, time and venue first. |

## 6. Authorisation

`DocumentGenerationPolicy` extends `ScopedPolicy`. `exam_admin` and `recruitment_admin`
university-wide; `dean_office` within their subtree for local posts. Batch downloads re-check the
policy.

## 7. UI

The reference flow, preserved: advertisement → post → document type → filter → **Generate**.

**Three additions.**

1. **A dry-run count before generating** — *"This will generate 58 admit cards."* The reference
   screen offers no such confirmation, and a bulk run is expensive to undo.
2. **Window state shown inline** — if the window is closed, the generate action is disabled with the
   dates, rather than failing after submission.
3. Progress, then a **single ZIP** plus a merged print-ready PDF.

Each document carries per-candidate details, schedule, venue, reporting instructions and the QR
stamp.

## 8. Worked example

Post 2599, admit-card window 1–10 Apr 2026, 58 candidates with `scrutiny = eligible`.

1. Operator selects advertisement → post → **Admit Card** → filter **Eligible only**.
2. Preview: *"This will generate 58 admit cards."* Template resolves to `admit_card` from the post
   type.
3. Generate on 3 Apr → window open → queued. Progress reported; a ZIP and a merged PDF result. Each
   card carries roll number, centre, room, seat, reporting time, gate-closing time and a QR.
4. Re-run on 5 Apr after a room change: 58 documents **regenerate**, the first batch is marked
   `superseded_by`, and both remain retrievable.
5. On 11 Apr generation is refused: *"Admit cards can be generated from 1 Apr 2026 to 10 Apr 2026."*
6. Filter **Interview eligible only** returns 0 → refused, because interviews have not been held.
7. Post 2881 (`interview_only`) offers **Interview Letter** only; `admit_card_template` is blank on
   that post type, so the option is not presented.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M32-R01 | Given a closed window, when generating, then it is refused with the dates |
| M32-R02 | Given a post type with no template for the type, when selected, then it is refused |
| M32-R03 | Given filter `eligible`, when generated, then only candidates with that gate `eligible` are included |
| M32-R04 | Given a gate inactive on the post, when that filter is chosen, then it is refused |
| M32-R05 | Given a re-run, when it completes, then documents are superseded, **not duplicated**, and both are retained |
| M32-R06 | Given a regeneration years later, when compared, then the output matches the original snapshot rendering |
| M32-R07 | Given admit cards without allocated seats, when generated, then it is refused |
| M32-R08 | Given interview letters without a venue, when generated, then it is refused |
| M32-R09 | Given a preview, when requested, then a count is returned and **nothing is generated** |
| M32-R10 | Given a Dean's-office user of Faculty X, when generating for Faculty Y, then **403** |
| M32-R11 | Given a QR stamp, when scanned, then it verifies without revealing personal data |
| M32-R12 | Given a bulk run, when it completes, then an audit event records type, filter and row count |

## 10. Test cases

`tests/Feature/Admin/Documents/BulkWindowTest` — R01, R07, R08 · `TemplateResolutionTest` — R02 ·
`CohortFilterTest` — R03, R04 · `IdempotenceTest` — **R05, R06** · `PreviewTest` — R09 ·
`Authz/BulkGenerationTest` — R10 · `QrTest` — R11 · `AuditTest` — R12.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R07, R08 | `App\Domain\Examination\AssertDownloadWindow` |
| R02 | `App\Domain\Documents\ResolveDocumentTemplate` |
| R03, R04 | `App\Domain\Reports\ResolveCohort` (M31) |
| R05, R06, R09 | `App\Domain\Documents\BulkGenerate` |
| R10 | `App\Policies\DocumentGenerationPolicy` |
| R11 | `App\Domain\Documents\QrStamp` (M09) |
| R12 | `App\Domain\Audit\*` (M26), `App\Domain\Documents\BulkGenerate` |
