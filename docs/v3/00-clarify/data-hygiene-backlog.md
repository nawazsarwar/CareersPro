# Source-Data Hygiene Backlog

**Status:** live · **Owner:** Registrar's Office (data) / implementation team (detection)
**Created:** 2026-08-27

---

## 1. What this document is

Defects in **source data**, not in this project's code, schema or plan. They are corrected in the
system of record — principally Data Lake — and re-verified, on a timeline separate from the build.

**Why they are tracked here rather than fixed in code:** silently repairing bad source data during
import hides the problem and guarantees it recurs on the next import. The import instead **detects,
reports and refuses to guess**. Each item below states what the import does when it encounters the
condition.

**Nothing here blocks the build.** Every item degrades gracefully.

---

## 2. Open items

### DH-001 — Organisational unit hierarchy needs verification

**Severity:** medium · **Detected:** 2026-08-27 · **Owner:** Registrar's Office

Some organisational units are parented under the wrong node. The confirmed instance:

| Unit | Currently parented under | Expected |
|---|---|---|
| Controller of Examinations (id 3, type 2 *Campus*) | Faculty of Engineering & Technology (id 13) | Campus |
| Accounts Section COE (id 222, type 13) | Faculty of Engineering & Technology (id 13) | Office of Controller of Examinations |
| Controller of Examinations Secretariat (id 226, type 13) | Faculty of Engineering & Technology (id 13) | Office of Controller of Examinations |

**Why it matters here.** Dean-scoped authorisation (DR-010, M25) resolves *"everything under Faculty
X"* by subtree. Any unit wrongly parented under a Faculty becomes visible to that Faculty's Dean's
office. In the confirmed instance, Dean's-office staff of Engineering & Technology would see units
belonging to the Controller of Examinations.

**What the import does.** Flags every unit whose `type_id` is inconsistent with its parent's type —
for example a `Campus`-typed or `Office of COE`-typed unit whose parent is a `Faculty` — and writes
them to an import exception report. It does **not** re-parent them automatically.

**Recommended action.** A full hierarchy review of all 301 units against the intended organigram,
alongside the general verification pass the Registrar's Office has scheduled. Re-run the import
afterwards; it is idempotent on `datalake_id`.

---

### DH-002 — 10 organisational units have no `code`

**Severity:** low · **Detected:** 2026-08-27 · **Owner:** Registrar's Office

291 of 301 units have a `code`; 10 do not.

**Why it matters here.** `code` is the stable human-readable identifier carried in the OU snapshot
frozen onto advertisements and posts (DR-009). It is what survives a later rename, so our schema
declares it `NOT NULL`.

**What the import does.** Assigns a deterministic placeholder — `TMP-{datalake_id}` — and lists every
affected unit in the exception report. The placeholder is stable across re-imports, so no snapshot
churns, and it is visibly temporary rather than silently plausible.

**Recommended action.** Assign real codes at source, then re-import. Units still carrying a `TMP-`
code should be excluded from selection as an advertisement's organisational unit until resolved —
a validation rule in M16.

---

### DH-003 — 2 organisational units remain in `Draft` status

**Severity:** low · **Detected:** 2026-08-27 · **Owner:** Registrar's Office

299 of 301 are `Published`; 2 are `Draft`.

**What the import does.** Imports them with `status = draft`. Draft units are **not selectable** as
an advertisement's organisational unit.

**Recommended action.** Publish or retire them.

---

### DH-004 — `title_hindi` and `title_urdu` are empty across all 301 units

**Severity:** low · **Detected:** 2026-08-27 · **Owner:** Registrar's Office

Both columns exist in Data Lake and are populated in **zero** rows.

**Why it matters here.** AMU is a multilingual institution and GIGW compliance is a stated
requirement. We retain both columns (decision register §6.2, item 7) — the columns are right; only
the data was never entered.

**What the import does.** Carries the NULLs. The UI falls back to `title`.

**Recommended action.** Populate at source when a multilingual interface is scheduled. No urgency
while the portal is English-only.

---

## 3. Closed items

### DH-000 — Legacy organigram tables — **not a defect**

`faculties` (22), `departments` (123), `centres` (22) and `campuses` (4) disagree with
`organizational_units` (13 Faculties, 111 Departments).

**Resolution, 2026-08-27:** not a discrepancy. These are **leftovers from the first generation of
Data Lake**, when the organigram was modelled as separate tables. That model was superseded by
`organizational_units` + `organizational_units_types`, which is authoritative. **We import from the
tree and ignore all four legacy tables.** Recorded in decision register §6.3; closed OQ-016.

---

## 4. How to add an item

```markdown
### DH-0NN — <one-line description>

**Severity:** high / medium / low · **Detected:** <date> · **Owner:** <named role>

<What is wrong in the source data, with real identifiers and counts.>

**Why it matters here.** <The concrete effect on this project. If there is none, this does not
belong in the backlog.>

**What the import does.** <Detect and report — never silently repair. State the exact behaviour.>

**Recommended action.** <What the data owner should do, and what unblocks afterwards.>
```

---

## 5. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created from the Data Lake schema review. DH-001…DH-004 raised; DH-000 closed as not-a-defect. | Implementation team |
