# M07 — Document Vault

**Wave:** 4 · **Scope:** **v1-partial** *(OCR checks deferred to v2; DigiLocker deferred per DR-005)*
**Depends on:** DR-005 · M03, M26
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Secure custody of every uploaded document, with the image specifications the statutory forms require
and the access logging the audit obligation requires.

| Obligation | Source |
|---|---|
| Assessment must be **based on evidence produced by the teacher** | UGC 2018 App. II Table 2 header |
| Self-attested copies; originals produced at interview | FN-1 / F-3 instructions |
| Every **document access** logged with an immutable audit entry | `MEMORY.md` §2 · M26 |
| Photo 350×450 px, 10–100 KB, ratio 7:9; signature and thumb impression 300×150 px, ratio 6:3, jpg | AMU Part-A tab A2 |

**Current state:** `MediaUploadingTrait::storeMedia` accepts **any file type**, moving it to
`storage/tmp/uploads`, and performs extension or MIME validation **only if the client volunteers
`width`, `height` and `size` query parameters**. That is defect #7 in
`../01-design/security/security-model.md` §2.

## 2. Data

```
documents
  id · user_id · collection enum(photo, signature, thumb_impression,
        qualification, experience, publication, project, patent, noc,
        category_certificate, disability_certificate, service_certificate, other)
  media_id → media (Spatie)
  original_name · mime · size_bytes · content_hash char(64)
  provenance enum(self_attested, digilocker_verified, office_verified)
  scan_status enum(pending, clean, infected, failed) · scanned_at
  verified_at · verified_by_id
  soft deletes
```

**`provenance` exists from the first migration** though only `self_attested` is reachable in v1
(DR-005). Adding a provenance concept later means re-opening submitted applications.

**`content_hash` is what snapshots reference** — the snapshot stays small and the hash proves the
file behind the reference is the one that was assessed
(`../01-design/domain/snapshot-and-audit.md` §2.2).

**Indexes:** `documents(user_id, collection)` · `documents(content_hash)`.

## 3. Domain services

```
App\Domain\Documents\StoreDocument::handle(User, UploadedFile, Collection): Document
App\Domain\Documents\ScanDocument::handle(Document): ScanResult      // queued, ClamAV
App\Domain\Documents\ServeDocument::handle(Document, User): StreamedResponse
App\Domain\Documents\CropImage::handle(UploadedFile, ImageSpec): UploadedFile
```

**Invariants.**
- Files land in **quarantine** and are not servable until `scan_status = clean`.
- Storage is **outside the web root**, on a private disk. There is no public URL, ever.
- Filenames are **UUID + validated extension**. The client's filename is stored for display only and
  never used on disk.
- `ServeDocument` **always** writes a `document.accessed` audit entry with actor and IP — no
  bypass path.
- MIME is determined by **server-side content inspection**, never from the client.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| POST | `/documents` | `documents.store` | `auth`, `verified`, `throttle:60,1` | `DocumentPolicy@create` |
| GET | `/documents/{document}` | `documents.show` | `auth`, `signed` | `DocumentPolicy@view` |
| GET | `/documents/{document}/thumb` | `documents.thumb` | `auth`, `signed` | `@view` |
| DELETE | `/documents/{document}` | `documents.destroy` | `auth` | `@delete` |

**No public route.** The `signed` middleware means a URL cannot be shared beyond its expiry, and the
policy means a shared URL still fails for the wrong actor.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `file` | required, file, **`mimes:pdf,jpg,jpeg,png`**, **`MimeMatchesContent`** | Upload a PDF, JPG or PNG. |
| `collection` | required, in:… | |
| photo | `dimensions:width=350,height=450`, **10–100 KB** | The photograph must be 350×450 pixels and between 10 KB and 100 KB. |
| signature, thumb impression | `dimensions:width=300,height=150`, 10–100 KB, **jpg only** | The signature must be 300×150 pixels, in JPG format. |
| document collections | **max 2048 KB** | The file must be 2 MB or smaller. |
| pdf | **`NoEmbeddedJavascript`**, **`NoEmbeddedFiles`** | This PDF contains active content and cannot be accepted. |

**On the size cap.** CU-Chayan's **500 KB** limit forcing illegible compression of theses and books
is one of its seven documented weakness categories. We allow **2 MB** where legibility requires it,
deliberately, while keeping the statutory 100 KB cap on photo and signature because those are
prescribed.

## 6. Authorisation

`DocumentPolicy` — **ownership scope** for candidates. Staff access is granted **through the
application**, not through the document: a scrutiny officer may view a document **only** if they may
view the application that references it, which itself resolves through their OU scope (DR-010).

**Every grant path writes an audit entry.** A document is the most sensitive object in the system and
the one most likely to be exfiltrated.

## 7. UI

Drag-and-drop with **client-side cropping to the exact statutory specification** — the candidate
should not have to resize a photograph in another tool, which is a documented friction point.

**Inline viewer.** PDFs and images render in-place. CU-Chayan's committees *"download hundreds of
loose PDFs/ZIPs"*; that is a documented weakness and it is avoidable.

Upload states are explicit: `Uploading` → `Scanning` → `Ready`, or `Rejected` with the reason. A
document still scanning is visibly not yet usable.

## 8. Worked example

A candidate uploads a 2.3 MB scan of a degree certificate.

1. Rejected client-side and server-side: *"The file must be 2 MB or smaller."* She rescans at 1.4 MB.
2. Accepted → stored as `9f2c…-a3.pdf` on the **private** disk, quarantined, `scan_status: pending`.
3. `ScanDocument` runs on the queue → ClamAV clean → `scan_status: clean`, `content_hash` computed.
4. She attaches it to a qualification claim. The claim now satisfies M06-R01.
5. On submission, the snapshot records `{document_id: 8821, content_hash: "4e88…"}` — **not the
   file**.
6. Six weeks later a scrutiny officer opens it. `ServeDocument` checks `ApplicationPolicy`, streams
   it, and writes `document.accessed` with actor 331, IP 10.4.22.19.
7. Someone attempts the same signed URL from another account → **403**, and the attempt is audited.
8. A candidate uploads a `.pdf` that is actually a PHP script. `MimeMatchesContent` rejects it before
   it reaches disk.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M07-R01 | Given an executable renamed `.pdf`, when uploaded, then it is rejected on content inspection |
| M07-R02 | Given a file whose declared MIME differs from its content, when uploaded, then it is rejected |
| M07-R03 | Given a photograph not 350×450, when uploaded, then it is rejected with the specification stated |
| M07-R04 | Given a document over 2 MB, when uploaded, then it is rejected |
| M07-R05 | Given an uploaded file, when stored, then it is outside the web root with a UUID filename |
| M07-R06 | Given a document pending scan, when requested, then it is not served |
| M07-R07 | Given an infected file, when scanned, then it is quarantined and an alert is raised |
| M07-R08 | Given any document read, when served, then a `document.accessed` audit entry exists |
| M07-R09 | Given candidate A, when requesting candidate B's document, then **403** |
| M07-R10 | Given a signed URL, when used by a different account, then **403**, and the attempt is audited |
| M07-R11 | Given a PDF with embedded JavaScript, when uploaded, then it is rejected |
| M07-R12 | Given a stored document, when its hash is compared to the snapshot reference, then they match |
| M07-R13 | Given a document referenced by a submitted snapshot, when deleted, then it is refused |

## 10. Test cases

`tests/Feature/Admin/Documents/UploadValidationTest` — R01–R04, R11 · `StorageTest` — R05, R12 ·
`ScanTest` — R06, R07 · `AccessAuditTest` — R08 · `Authz/DocumentOwnershipTest` — R09, R10 ·
`RetentionTest` — R13.

Fixtures: a PHP script renamed `.pdf`; a MIME-spoofed JPEG; a PDF with an embedded `/JavaScript`
entry; the EICAR test string for the scanner.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R04, R11 | `App\Http\Requests\Documents\StoreDocumentRequest`, `App\Rules\MimeMatchesContent` |
| R05, R12 | `App\Domain\Documents\StoreDocument` |
| R06, R07 | `App\Domain\Documents\ScanDocument` |
| R08 | `App\Domain\Documents\ServeDocument` |
| R09, R10 | `App\Policies\DocumentPolicy` |
| R13 | `App\Domain\Documents\AssertNotSnapshotted` |
