# M09 — Application PDF Generation

**Wave:** 4 · **Scope:** **v1-partial** *(digital signature deferred to v2 — needs a DSC/eSign decision)*
**Depends on:** M05, M07
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Render the statutory print format. **The printed form is the legal artefact** — CRR Rule 11 III(e)
requires it addressed to *"The Registrar"* in a closed cover super-scribing the post, and DR-011
confirms hard-copy submission is retained.

**Five templates**, driven by `post_types.pdf_template`: `fn1` (teaching) · `fn2` (librarian) ·
`fn3_general_nt` (non-teaching) · `fn3_general_st` (school teacher) · `fn3_phe` (physical education).

**No PDF library exists in the project today** — no dompdf, snappy, wkhtmltopdf or mpdf in
`composer.json` — while `post_types.pdf_template` has held template names in production for years.

## 2. Data

No new tables. Reads the **application snapshot**, never the live dossier — the printed form must
match what was submitted.

```
generated_documents
  id · application_id · type enum(application_form, admit_card, interview_letter)
  template · snapshot_id · content_hash · media_id
  generated_at · superseded_by_id NULL
```

## 3. Domain services

```
App\Domain\Documents\RenderApplicationPdf::handle(Application): GeneratedDocument
App\Domain\Documents\ResolveTemplate::for(Post): string
App\Domain\Documents\QrStamp::for(Application): string
```

**Invariants.** Rendering reads the snapshot, so **regenerating a form for a 2026 application in
2031 produces the identical document**. The QR encodes a **signed verification URL**, not the
candidate's data. Regeneration supersedes rather than duplicating, and both are retained.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/applications/{application}/form.pdf` | `applications.pdf` | `auth`, `verified`, `throttle:20,60` | `ApplicationPolicy@view` |
| GET | `/verify/{signature}` | `documents.verify` | `signed` | — |

## 5. Validation

No user input. Guard conditions: the application must be **submitted**; the snapshot must exist; the
template named by the post type must be registered — an unregistered template name **throws**, so a
typo in configuration fails loudly rather than producing a blank form.

## 6. Authorisation

`ApplicationPolicy@view` — ownership for the candidate, OU scope for staff. The public
`/verify/{signature}` route confirms **only** that a document is genuine and names the post and
application number; it reveals no personal data.

## 7. UI

Print-fidelity HTML rendered to PDF. A4, statutory field order preserved exactly, photograph and
signature in their prescribed boxes, the declaration verbatim, and the forwarding-authority block.

The download page states the submission instructions from `post_types.submission_venue`, the
deadline, and **"a separate envelope for each post applied for."**

## 8. Worked example

Application `2599/2026/00412`, post type *GENERAL (Non Teaching Post)* → template `fn3_general_nt`.

The PDF renders Part A items 1–28 from **snapshot #1**, the photograph and signature from the
document store by `content_hash`, the declaration verbatim, and a QR encoding
`/verify/eyJhbGciOi…`. Scanning it returns: *"Genuine. Application 2599/2026/00412, System Manager,
Advertisement 2/2026/NT, submitted 23 Jan 2026."*

The candidate later rectifies a deficiency, producing snapshot #2. Regenerating renders **snapshot
#2** and marks the first generation `superseded_by`. Both remain retrievable — the record shows what
was printed and when.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M09-R01 | Given a submitted application, when the PDF is generated, then it renders from the **snapshot**, not the live dossier |
| M09-R02 | Given a dossier edited after submission, when the PDF is regenerated, then the output is **unchanged** |
| M09-R03 | Given a post type, when the template resolves, then it matches `post_types.pdf_template` |
| M09-R04 | Given an unregistered template name, when resolved, then it throws |
| M09-R05 | Given a generated PDF, when the QR is scanned, then verification succeeds and reveals no personal data |
| M09-R06 | Given a draft application, when a PDF is requested, then it is refused |
| M09-R07 | Given candidate A, when requesting candidate B's PDF, then **403** |
| M09-R08 | Given a new snapshot, when regenerated, then the previous generation is marked superseded and retained |
| M09-R09 | Given the PDF, when inspected, then the submission venue matches the post type |

## 10. Test cases

`tests/Feature/Admin/Documents/ApplicationPdfTest` — R01, R02, R06, R08, R09 ·
`TemplateResolutionTest` — R03, R04 · `QrVerificationTest` — R05 ·
`Authz/PdfAccessTest` — R07.

Fixtures: golden PDFs per template, compared on extracted text rather than bytes.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R08 | `App\Domain\Documents\RenderApplicationPdf` |
| R03, R04 | `App\Domain\Documents\ResolveTemplate` |
| R05 | `App\Domain\Documents\QrStamp` |
| R06, R07 | `App\Policies\ApplicationPolicy` |
