# M27 — RTI / Legal Support

**Wave:** 9 · **Scope:** v1
**Depends on:** M26, M20, M23
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

## 1. Purpose and statutory basis

Point-in-time reconstruction of an application, its score and the decisions taken on it — with proof
that nothing has been altered.

| Obligation | Source |
|---|---|
| Claims verifiable *"at any point of time **even after joining**"* | **CRR Rule 22.4** |
| CVO may investigate **at any stage** | **CRR Rule 34.2** |
| RTI applications for counts, roster status, shortlists and scoring rationale | RTI Act 2005 |
| Third-party personal information exempt | RTI s.8(1)(j) |
| Retention indefinite; nothing deleted electronically | **DR-011** |

**This is the capability ADR-001 used to justify the relational choice**, and it is currently
unimplementable: there is no snapshot table, and `audit_logs` has no hash chain.

## 2. Data

No new domain tables. Reads `application_snapshots`, `score_runs`, `score_lines`, `audit_logs`,
`audit_checkpoints`, `rule_set_versions`.

```
rti_requests   id · reference UNIQUE · received_on · applicant_name
               subject · scope json · due_on
               status enum(received, in_progress, furnished, refused, appealed)
               response NULL · furnished_on NULL · handled_by_id
               refusal_ground NULL          -- e.g. 's.8(1)(j)'
rti_disclosures id · rti_request_id · report_run_id NULL · document_id NULL
                disclosed_at · disclosed_by_id
```

## 3. Domain services

```
App\Domain\Legal\ReconstructApplication::asAt(Application, CarbonInterface): Reconstruction
App\Domain\Legal\VerifyReconstruction::handle(Reconstruction): VerificationReport
App\Domain\Legal\BuildDisclosurePack::handle(RtiRequest, User): DisclosurePack
App\Domain\Legal\AssertDisclosable::check(RtiRequest, string $field): void
```

**The reconstruction procedure**, from `../01-design/domain/snapshot-and-audit.md` §4:

```
1. snapshot  = latest application_snapshots WHERE taken_at <= :date  ORDER BY sequence DESC
2. run       = latest score_runs WHERE snapshot_id = snapshot.id AND computed_at <= :date
3. ruleset   = rule_set_versions[run.rule_set_version_id]        (immutable)
4. VERIFY    recompute(snapshot, ruleset).output_hash == run.output_hash
5. TIMELINE  audit_logs WHERE subject = application AND occurred_at <= :date ORDER BY sequence
6. INTEGRITY verify_chain(range) against audit_checkpoints
```

**Step 4 is the point.** The output is not *"here is what we stored"* but *"here is the input, here
is the rule, and re-running them now reproduces the same result."* That is what survives
cross-examination.

**Invariants.** Reconstruction is **read-only** — it never writes to an application, and any score
run it performs is marked `is_sandbox`. `AssertDisclosable` refuses third-party personal information
under s.8(1)(j) unless a recorded appellate decision overrides it. Every disclosure writes an audit
entry naming the recipient.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin/legal/applications/{application}/reconstruct` | `admin.legal.reconstruct` | `LegalPolicy@reconstruct` |
| POST | `/admin/legal/applications/{application}/verify` | `admin.legal.verify` | `@reconstruct` |
| GET/POST | `/admin/rti/{request?}` | `admin.rti.*` | `RtiPolicy@*` |
| POST | `/admin/rti/{request}/pack` | `admin.rti.pack` | `@disclose` |
| POST | `/admin/rti/{request}/furnish` | `admin.rti.furnish` | `@disclose` |
| GET | `/admin/legal/chain/verify` | `admin.legal.chainVerify` | `LegalPolicy@verifyChain` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `as_at_date` | required, date, `before_or_equal:today` · **on or after the first snapshot** | No records exist for this application before {date}. |
| RTI `reference` | required, unique, max:100 | |
| `received_on` | required, date, `before_or_equal:today` | |
| `due_on` | required · **default `received_on + 30 days`** | |
| `scope` | required, json · keys from the declared disclosure schema | |
| furnish | **status `in_progress`**, response required, min:50 | Record what was furnished. |
| refuse | **`refusal_ground` required** | Cite the exemption relied on. |
| disclosure | **`AssertDisclosable` must pass for every field** | {field} is third-party personal information, exempt under s.8(1)(j). |

## 6. Authorisation

`LegalPolicy` — `reconstruct` and `verifyChain` for `auditor`, `super_admin` and a `legal_officer`
role. **University-wide and read-only** — legal reconstruction cannot be OU-scoped, because a service
appeal may concern any post.

`RtiPolicy` — `disclose` and `furnish` for the designated Public Information Officer role only. Every
action audited.

## 7. UI

**Reconstruction view:** as-at date picker, then three panels — the dossier as it stood, the score
run with per-line citations, and the timeline. A **verification banner** states the result plainly:

> **Verified.** Recomputing snapshot `#1` (`a3f9…`) under `ugc-teaching-2018@1` reproduces
> `output_hash e8b4…`, matching the run of 14 Apr 2026. Audit chain intact, sequences 1–184,502.

Or, if not:

> **Chain broken at sequence 92,118.** This is a security incident. Report immediately.

**RTI register:** requests with due dates and days remaining, the disclosure pack builder showing
what is included and **what is withheld with its ground**, so a refusal is never silent.

## 8. Worked example

**October 2029.** A service appeal is filed about advertisement 2/2026/NT: the appellant claims their
scrutiny rejection of 14 March 2026 was wrong.

1. A legal officer opens application `2599/2026/00412`, as-at **2026-03-20**.
2. **Dossier:** snapshot `#2` (the rectified version, 14 Mar) — snapshot `#1` also listed, so the
   change is visible rather than hidden.
3. **Score:** none — non-teaching, no research score.
4. **Timeline:** Submitted 23 Jan · Paid 23 Jan · Under scrutiny 11 Mar · **Deficiency raised 12 Mar**
   · Rectified 14 Mar · **Scrutiny rejected 14 Mar**, remark *"experience certificate does not
   establish 3 years of continuous service"*, by actor 331, role `dean_office@/1/11/`, IP recorded.
5. **Verification:** chain intact across 184,502 entries; checkpoints match.
6. The pack is generated for the appellant: **their own** snapshots, decisions, remarks and timeline.
   The scrutiny officer's **internal notes** and other candidates' records are withheld under
   s.8(1)(j), and the pack lists what was withheld and why.

**Because nothing was deleted (DR-011), this works three years later even though the physical
dossier was destroyed in 2031.**

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M27-R01 | Given an as-at date, when reconstructed, then the snapshot in force at that date is used |
| M27-R02 | Given data changed after the as-at date, when reconstructed, then the output is unaffected |
| M27-R03 | Given a reconstruction, when verified, then the recomputed `output_hash` matches the stored run |
| M27-R04 | Given a tampered audit row, when verified, then the first divergent sequence is reported |
| M27-R05 | Given a reconstruction, when it runs, then **no** application record is written and any score run is `is_sandbox` |
| M27-R06 | Given a disclosure pack, when built, then third-party personal information is excluded **and listed as withheld with its ground** |
| M27-R07 | Given an RTI refusal, when recorded without a ground, then validation fails |
| M27-R08 | Given a disclosure, when furnished, then an audit entry names the recipient |
| M27-R09 | Given an as-at date before the first snapshot, when requested, then it is refused |
| M27-R10 | Given a non-PIO role, when disclosing, then **403** |
| M27-R11 | Given an archived application, when reconstructed, then it succeeds — **retention proves out** |
| M27-R12 | Given a destroyed physical dossier, when reconstructed, then the electronic record is complete |

## 10. Test cases

`tests/Feature/Admin/Legal/ReconstructionTest` — **R01, R02, R09, R11, R12** ·
`VerificationTest` — R03, R04 · `ReadOnlyTest` — R05 · `DisclosureTest` — R06, R07, R08 ·
`Authz/RtiTest` — R10.

R11 and R12 archive an application, run the DR-011 destruction batch (M33), then reconstruct — the
retention guarantee tested end to end rather than assumed.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R02, R09, R11, R12 | `App\Domain\Legal\ReconstructApplication` |
| R03, R04 | `App\Domain\Legal\VerifyReconstruction`, `VerifyAuditChain` (M26) |
| R05 | `score_runs.is_sandbox` |
| R06–R08 | `App\Domain\Legal\BuildDisclosurePack`, `AssertDisclosable` |
| R10 | `App\Policies\RtiPolicy` |
