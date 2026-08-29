# Build Specifications

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27

One spec per module, `M{NN}-{slug}.md`, numbered per
[`../00-clarify/scope-boundary.md`](../00-clarify/scope-boundary.md).

**No module's implementation begins before its spec exists and every decision on its `Depends on`
line reads `DECIDED`.**

---

## 1. The template

Eleven sections. **None may be empty.** A section with nothing to say says so and explains why —
that is information; a blank heading is not.

```markdown
# M{NN} — {Module name}

**Wave:** {n} · **Scope:** v1 | v1-partial | v2
**Depends on:** DR-00N, DR-00M · M{NN} · DOC-00N
**Blocked by:** OQ-0NN  *(omit if none)*

## 1. Purpose and statutory basis
What it does, for whom, and the clause that requires it. Citations mandatory.

## 2. Data
Tables, columns, types, constraints, indexes. The migration.

## 3. Domain services
Class names, method signatures, invariants. What must never happen.

## 4. Routes and controllers
Verb · URI · name · middleware · policy method. One line each.

## 5. Validation
Every field, every rule, every cross-field rule, and the exact error message.

## 6. Authorisation
The policy method and the scope it applies — ownership, OU subtree, or both.

## 7. UI
Screens, states, components. Reference `../01-design/ux/`.

## 8. Worked example
Real data through the whole flow, showing expected output at each step.

## 9. Acceptance criteria
`M{NN}-R{NN}` · Given / When / Then.

## 10. Test cases
File, test method name, fixtures. One per acceptance criterion, minimum.

## 11. Traceability
Requirement ID → code artefact → test. Generated, not hand-maintained.
```

### 1.1 Why section 8 is not optional

A worked example with real values is how a reader checks they understood the same thing the author
meant. It is the cheapest defect-detector in the set, and its absence is why the previous
specification could assert `Advertisement N:1 Post` for months without anyone noticing.

### 1.2 Requirement IDs

`M{NN}-R{NN}` — e.g. `M20-R07`. **One scheme, and it resolves.**

The previous `docs/v2-archive/traceability.csv` cited `MODULES.md §5.1`–`§5.29` (there is no §5) and
`SRS-001`–`SRS-029` (the SRS uses `REQ-APP-01`…`REQ-MAND-03`), with `CodeArtefact` and `TestCase`
reading `TODO` on all 29 rows — while `PROGRESS.md` reported *"Mapped Requirements: 29, Unmapped:
0"*.

**The new matrix is generated from the specs, and CI fails if any requirement ID lacks a code
artefact and a test.** A coverage figure that is asserted rather than derived is worse than none.

---

## 2. Module index

`✅` spec written · `⬜` outstanding

| ID | Module | Wave | Scope | Depends on | Spec |
|---|---|---:|---|---|---|
| **Wave 0 — foundation** |
| M00 | Purge, toolchain, CI, shared table | 0 | v1 | DR-002 | ✅ |
| **Wave 1 — identity** |
| M03 | Registration & Profile (auth) | 1 | v1 | DR-008, DR-022, DR-023, DR-024 | ✅ |
| M25 | RBAC & Impersonation | 1 | v1 | DR-008, DR-010, DR-015 | ✅ |
| M26 | Audit & Traceability | 1 | v1 | DR-011 | ✅ |
| **Wave 2 — master data** |
| M24 | Master Data Management | 2 | v1 | DR-009 | ✅ |
| M35 | Designation & Sanctioned Strength | 2 | v1 | DR-012 · AMU CRR | ✅ |
| **Wave 3 — advertisement** |
| M16 | Advertisement Builder | 3 | v1 | DR-006, DR-009, DR-010, DR-012 | ✅ |
| M01 | Public Vacancy Listing | 3 | v1 | M16 | ✅ |
| M02 | Advertisement Detail | 3 | v1 | M16 | ✅ |
| M17 | **Relaxation Engine** | 3 | v1 | DR-017 | ✅ |
| **Wave 4 — application** |
| M04 | Editable Academic & Work History | 4 | v1 | M03, DR-016 | ✅ |
| M06 | Publication & Research Claims | 4 | v1 | M04 | ✅ |
| M07 | Document Vault | 4 | v1-partial | DR-005 | ✅ |
| M05 | Application Wizard | 4 | v1 | M04, M06, M07, M35 | ✅ |
| M09 | Application PDF Generation | 4 | v1-partial | M05 | ✅ |
| M10 | Applicant Dashboard | 4 | v1 | M05 | ✅ |
| **Wave 5 — money** |
| M08 | Fee & Payment | 5 | v1 | DR-004, DR-018 | ✅ |
| **Wave 6 — scrutiny** |
| M34 | Eligibility Decision Gates | 6 | v1 | M05 | ✅ |
| M18 | Scrutiny Workbench | 6 | v1 | M34, M25 | ✅ |
| M19 | Committee Workspace | 6 | v1 | M18 · **DOC-008** | ✅ |
| M33 | Application Receipt & Hardcopy | 6 | v1-partial | DR-011 | ✅ |
| **Wave 7 — scoring** |
| M20 | Scoring Engine | 7 | v1 | DR-006, DR-013, DR-014 · OQ-009 | ✅ |
| M21 | Shortlisting & Cut-offs | 7 | v1 | M20, DR-019 · OQ-008 | ✅ |
| **Wave 8 — examination** |
| M11 | Admit Card & Centre Allotment | 8 | v1 | M21 | ✅ |
| M22 | Examination Admin | 8 | v1 | M11 | ✅ |
| M31 | Attendance Sheet Generator | 8 | v1 | M34, M22 | ✅ |
| M32 | Bulk Document Generator | 8 | v1 | M11 | ✅ |
| M13 | Interview Scheduling | 8 | v1-partial | M21 | ✅ |
| M14 | Results & Merit Lists | 8 | v1 | M20, M19 | ✅ |
| **Wave 9 — communication and reporting** |
| M30 | Mass Communication Engine | 9 | v1 | M16 | ✅ |
| M15 | Grievance Desk | 9 | v1 | M18 | ✅ |
| M23 | Analytics & Reporting | 9 | v1 | M34, M08 | ✅ |
| M27 | RTI / Legal Support | 9 | v1 | M26 | ✅ |
| M28 | System Administration | 9 | v1-partial | M25 | ✅ |
| M29 | Public API | 9 | v1-partial | M25 | ✅ |
| **Wave 10 — migration** |
| M40 | Legacy Migration & Cut-over | 10 | v1 | OQ-004 | ✅ |
| **Deferred** |
| M12 | Examination Delivery (CBT) | — | **v2** | — | — |

---

## 3. Dependency graph

```
M00 purge · CI · shared table
 │
 ├─► M03 auth ──┬─► M25 RBAC ──┬─► M18 scrutiny
 │              │              └─► M28, M29
 │              └─► M04 ─► M06 ─┐
 │                   M07 ───────┼─► M05 wizard ─► M09, M10
 ├─► M26 audit  (cross-cutting) │
 │                              │
 ├─► M24 master data ─► M35 designations ─► M16 advert ─┬─► M01, M02
 │                            │                         └─► M17 relaxation
 │                            └────────────────────────────► M05
 │
 └─► M08 payment ─► M05

M05 ─► M34 gates ─► M18 ─► M19 ─► M14
              └──► M31
M20 scoring ─► M21 shortlist ─► M11 admit card ─► M22, M32, M13 ─► M14
M34 + M08 ─► M23 reporting
M26 ─► M27 RTI
M40 depends on everything
```

**Critical path:** `M00 → M24 → M35 → M16 → M05 → M34 → M18 → M20 → M21 → M14`.

**Note M35 sits on it.** The designation master is the spine — nothing binds rules to a vacancy
without it, which is why it was the gap worth catching before code (DR-012).

---

## 4. Waves

Each wave ends green: migrations run clean, tests pass, coverage gate met, PHPStan and Pint clean.

| Wave | Theme | Exit condition |
|---|---|---|
| 0 | Purge, toolchain, CI, shared table | `migrate:fresh --seed` clean from empty. CI green. **No jQuery in the built output** |
| 1 | Identity, RBAC, audit | A user registers, verifies, logs in. **Candidate A gets 403 on candidate B's everything.** Chain verifies |
| 2 | Master data | 301 units and 29 types imported with an exception report. Designations seeded. **Suite passes with `datalake` removed from config** |
| 3 | Advertisement and post | An advertisement publishes with a frozen ruleset and OU snapshot; posts appear in the public listing |
| 4 | Profile and application | A candidate completes a dossier and submits. **Snapshot written with a content hash** |
| 5 | Payment | An order survives a lost callback with **no second charge** |
| 6 | Scrutiny | Three gates decided per post type. Deficiency raised, rectified, re-snapshotted |
| 7 | Scoring | Golden corpus passes. **Blocked rules raise `PendingRatificationError` and emit no partial score** |
| 8 | Examination and results | Roll numbers, seats, admit cards in-window, attendance sheets, merit lists |
| 9 | Communication and reporting | Bulk mail, grievances, dashboard, RTI reconstruction |
| 10 | Migration | Row counts and checksums reconcile; dual-run window observed |

---

## 5. Standing rules for every module

**Code style, layout and quality are governed by
[`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) (DR-020).** Read it
before writing a line. In short: Laravel conventions win; every HTTP artefact lives under `Admin` or
`Frontend`; **validation is Form Requests, strictly**; Pest 5; Larastan level 6; Tailwind 4.

The rules below are *domain* rules, and they apply everywhere too.

1. **Authorisation is permission AND scope.** Every row-returning query passes through
   `visibleTo($user)`. Never a permission check alone.
2. **Every mutation writes a hash-chained audit entry.** Including reads of documents.
3. **Nothing is hard-deleted.** DR-011.
4. **No statutory value in code.** It comes from `rules-catalogue.yaml` with its citation.
5. **No runtime reference to `datalake` or `mysql_readonly`.** Import only. Tested by removing them.
6. **Tables use the shared component.** `App\Support\Table`, built once in Wave 0.
7. **Every acceptance criterion has a named test.** CI fails on an unmapped requirement ID.
8. **`axe-core` clean** on every rendered route.
9. **No `n+1`.** `assertQueryCount` budgets on list routes.
10. **Blade + Alpine + Tailwind 4** (DR-021). **No Livewire, no Inertia, no SPA, no jQuery.**
    Alpine is progressive enhancement — **every screen works with JavaScript disabled.** Interactive
    screens use a JSON endpoint + Alpine `fetch` with a non-JS form fallback on the same route.

---

## 6. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Template, requirement-ID scheme, 36-module index with waves and dependencies, dependency graph, wave exit conditions, 10 standing rules. | Implementation team |
| 2026-08-29 | **M03 extended for OTP login and a multi-channel second factor** (DR-022, DR-023, DR-024) — M03-R13…R29. Supporting amendments: M00 (config surface, R09–R10), M23 (shared-mobile report, R13), M25 (impersonation and second factors, R15), M26 (redaction list, new `auth.*` events), M28 (authentication settings, R13–R15), M30 (transactional boundary restated). Second-factor middleware alias standardised from `2fa` to **`two-factor`** across M08, M18, M25 and M26. | Implementation team |
