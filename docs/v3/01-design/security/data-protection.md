# Data Protection — DPDP 2023, RTI and Retention

**Status:** live · **Owner:** implementation team · **Accountable:** Legal cell
**Created:** 2026-08-27

`docs/v2-archive/research/compliance-matrix.md` — the **entire file** — covers OWASP, WCAG 2.2 AA and GIGW.
There is no data-protection, RTI or retention content anywhere in the previous document set, and
`docs/v2-archive/spec/security.md` disposes of DPDP 2023 in one line with no retention period, no
data-principal rights, and no consent artefact.

Meanwhile the portal handles caste, religion, disability type and percentage, marital status, spouse
name, identity marks, **biometric thumb impressions**, Aadhaar, criminal-record declarations and
medical fitness data — for **55,050 people**.

---

## 1. The central tension, stated plainly

**DR-011 decided: nothing is ever deleted electronically.** Applications are archived; snapshots and
the audit chain persist indefinitely; only **physical** dossiers are destroyed, five years after
process close, for unsuccessful candidates.

**That is not data minimisation, and this document does not pretend otherwise.**

Indefinite retention of special-category personal data requires a lawful basis that is **not**
consent, because consent can be withdrawn and this data cannot then be erased. The basis is:

| Ground | Justification |
|---|---|
| **Statutory obligation** | Recruitment records of a Central University are public records. CRR Rule 22.4 permits verification of a candidate's claims *"at any point of time **even after joining**"* — a right that is meaningless if the claims have been erased |
| **Legal claims** | Service appeals and writ petitions arising from a selection can be filed years later. AMU's own reservation applicability is under live litigation (*AMU v. Naresh Agarwal*, 7-judge Bench, Nov 2024, remitted). Destroying the record destroys the defence |
| **RTI** | Applications under the RTI Act 2005 routinely seek category-wise counts, roster status, shortlists and scoring rationale. The obligation to furnish presupposes retention |

**This must be reviewed and signed off by the Legal cell.** It is the single largest data-protection
exposure in the design, it is deliberate, and it is recorded here rather than buried.

> **What would change the answer:** a DPDP ruling or a University policy imposing electronic
> erasure. DR-011's reversal trigger. If that happens, §5 describes the anonymisation path that
> preserves the audit chain — designed but **not implemented**, because it is not currently the
> decision.

---

## 2. Data classification

| Class | Fields | At rest | In audit `properties` | In exports |
|---|---|---|---|---|
| **S1 — Special category** | caste, sub-caste, religion, disability type and percentage, medical fitness, criminal-record declarations, biometric thumb impression | **Field-level encryption** | **hash only** | **never**, except statutory aggregate |
| **S2 — Identifier** | Aadhaar, mobile, email, date of birth, address | **Field-level encryption** (Aadhaar, mobile); rest at-disk | hash only | masked |
| **S3 — Personal** | name, father's/mother's/spouse name, gender, marital status, photograph, signature | at-disk | value | permitted to authorised roles |
| **S4 — Academic** | qualifications, experience, publications, claims | at-disk | value | permitted |
| **S5 — Operational** | application number, roll number, status, decisions, scores | at-disk | value | permitted |
| **S6 — Financial** | order, transaction, gateway references | at-disk; **no card data ever stored** | value | finance role only |

**Field-level encryption** uses Laravel's `encrypted` cast with a key distinct from `APP_KEY`,
rotatable independently. Encrypted fields are **not searchable** — where lookup is required (Aadhaar
duplicate detection) a **blind index** is stored: `HMAC-SHA256(value, index_key)`, which permits
equality matching without decryption.

**`aadhaar_no` is currently a plain `varchar`.** So is every other PII column in the schema, despite
`security.md` claiming *"encryption at rest for PII"*.

---

## 3. Data-principal rights

DPDP 2023 rights, and how each is served given §1.

| Right | Served how |
|---|---|
| **Access** | The candidate dashboard shows the full dossier. A **"download my data"** export produces the complete profile, applications, documents and decisions as a signed archive |
| **Correction** | Editable until submission. After submission, via the **deficiency rectification window** (M18) — which writes a **new snapshot**, leaving the earlier one intact. Corrections after process close require an administrative correction with a recorded reason |
| **Erasure** | **Not available** for submitted applications, on the statutory-obligation and legal-claims grounds in §1. **The refusal is stated to the candidate at the point of submission**, not discovered later. Draft applications never submitted **are** erasable |
| **Nomination** | Recorded on the profile; the nominee may exercise access on death or incapacity |
| **Grievance** | M15, SLA-tracked, with a named data-protection contact distinct from the recruitment grievance desk |

### 3.1 Notice and consent

**A consent artefact is recorded at registration and at each submission**, storing: version of the
notice, timestamp, IP, and the specific purposes. The notice must state, in plain language and
before the fee is paid:

- what is collected, and that it includes special-category data;
- that **submitted applications are retained indefinitely** and **cannot be erased**, and why;
- that hard copies of unsuccessful applications are destroyed after five years;
- who the data may be disclosed to — selection committees, external subject experts, RTI applicants
  in aggregate, courts;
- the data-protection contact.

**Consent is not the lawful basis for retention** (§1). It is recorded for transparency and for the
processing that *is* consent-based — for example optional communications.

---

## 4. Retention schedule

| Record | Retention | Basis |
|---|---|---|
| Draft application, never submitted | **Purge after 24 months** of inactivity | No statutory interest. The only genuine erasure in the system |
| Submitted application, snapshots, score runs | **Indefinite** | §1 |
| Audit chain | **Indefinite** | Integrity — a broken chain is unverifiable |
| Uploaded documents | **Indefinite** | Bound to the snapshot they evidence |
| Hard copy — selected, joined | **Permanent** | Central record section |
| **Hard copy — unsuccessful** | **5 years** after process close | Government mandate (DR-011) |
| OTP codes | **24 hours** | Legacy holds 25,527 rows with no expiry |
| Password reset tokens | **60 minutes** | Legacy holds 1,265 rows |
| Session data | 12 hours absolute | |
| Mail logs | 3 years | Delivery disputes |
| Gateway reconciliation files | **8 years** | Financial audit |

**Purge and destruction are jobs with dry-run modes and audit events.** The only automated *deletion*
in the system is the 24-month draft purge, and it emits `application.draft_purged`.

---

## 5. The anonymisation path — designed, not implemented

Recorded because DR-011 has a reversal trigger and because a reader will ask.

If electronic erasure were ever imposed, the correct implementation is **anonymisation, not
deletion**, to preserve the audit chain and the statutory aggregate counts:

```
S1/S2 fields  → NULL, and the blind index dropped
S3 fields     → NULL
documents     → deleted from storage; the snapshot keeps the hash, so the
                record still proves WHAT was assessed without holding it
snapshot      → payload rewritten with the same field set, PII removed,
                and a `redacted_at` marker; content_hash RECOMPUTED and the
                original hash preserved as `pre_redaction_hash`
audit chain   → UNTOUCHED. Redaction is a NEW event, never a rewrite
retained      → application_no, post, category, decisions, dates, scores
```

**The audit chain is never rewritten.** Redaction is an appended event. That is the only way a chain
survives an erasure obligation, and it is why the chain design in
`../domain/snapshot-and-audit.md` keeps PII out of `properties` in the first place.

**Not built in v1.** Building it would imply a decision that has not been taken.

---

## 6. RTI

| Request type | Response |
|---|---|
| Aggregate counts — applications, category-wise, gender-wise, per post | **Furnished.** M23 reports produce them directly |
| Roster status | Furnished once M17 holds policy data (DOC-003) |
| Shortlists and merit lists | Furnished as published |
| **A candidate's own scoring rationale** | Furnished — this is exactly what `score_lines` with citations produce |
| **Another candidate's personal information** | **Refused** under RTI s.8(1)(j), unless larger public interest is established by the appellate authority |
| Selection committee deliberations | Refused under s.8(1) as applicable; the **decision** and its recorded reasons are furnished |

**Design implication:** every report that could answer an RTI request must be **reproducible for a
historical date**, not just current. That is `../domain/snapshot-and-audit.md` §4, and it is why
point-in-time reconstruction is a v1 module (M27) rather than a nice-to-have.

---

## 7. Third parties

| Party | Data | Basis |
|---|---|---|
| Payment gateway | Name, email, mobile, amount, order reference. **No card data touches us** | Contract |
| External subject experts | Application dossiers for their committee, **for the duration of the committee only** | Statutory — UGC 2018 cl. 5.1 |
| Email/SMS provider | Email, mobile, message content | Contract; DPA required |
| Data Lake ERP | **None.** Data flows *in* only, at import, and only organisational structure | DR-009 — no PII leaves |
| DigiLocker | Deferred to v2 (DR-005) | — |

**A data-processing agreement is required with every contracted party** before go-live. None exists
today.

---

## 8. Test strategy

| Test | Asserts |
|---|---|
| Encryption | S1/S2 fields are ciphertext in the database; a raw `SELECT` reveals no Aadhaar |
| Blind index | Duplicate Aadhaar detected without decryption |
| Audit redaction | `aadhaar_no` never appears as a **value** in `audit_logs.properties` |
| Export masking | A finance-role export contains no S1 field |
| No deletion | No code path hard-deletes an application, document or audit row — **enumerated, not sampled** |
| Draft purge | A draft inactive 24 months is purged and the event audited; a **submitted** application in the same state is **not** |
| OTP expiry | Codes older than 24 hours are removed |
| Consent artefact | Registration and submission both write one, with the notice version |
| Right of access | The export contains every field the dashboard shows |
| Document access | Every read emits `document.accessed` with actor and IP |

---

## 9. Open items

| Item | Owner | Reference |
|---|---|---|
| **Sign off the §1 retention argument** | **Legal cell** | The largest exposure in the design |
| Reservation applicability given AMU's litigation | Legal cell | OQ-013 |
| CRR Rule 33.3 as a validation rule | Legal cell | OQ-012 |
| Data-processing agreements with gateway, email and SMS providers | Registrar's Office | — |
| Appoint the data-protection contact for §3 | Registrar's Office | — |
| Confirm the RTI appellate authority for §6 | Registrar's Office | — |

---

## 10. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. States and argues the DR-011 retention position rather than assuming it; six-class data classification with field-level encryption and blind indexes; DPDP data-principal rights including an explicit, disclosed refusal of erasure; retention schedule; the designed-but-unbuilt anonymisation path; RTI disclosure matrix. | Implementation team |
