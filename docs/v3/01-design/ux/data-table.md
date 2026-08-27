# The Data Table

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27

This is the **single largest UI work item in the project**, and no previous document mentions it.

`docs/v2-archive/spec/srs.md` REQ-MAND-02 requires *"All tables (e.g. DataTables equivalents) must be rebuilt
natively using server-side pagination (Blade) and Alpine.js"* — over a **78,232-row** applications
table, with per-column search, six export formats, select-all with bulk delete, 100-row pages and
**7-column dossier records**. `docs/v2-archive/spec/ui-ux.md` is 18 lines and does not mention tables at all.

**Current state:** 33 of 34 admin list views emit `class="… datatable datatable-Advertisement"` and
expect jQuery DataTables to initialise them. `layouts.app` loads **zero** jQuery and zero DataTables.
The yajra server-side endpoints are live and **nothing ever calls them**. Every admin list except
`admin/posts/index` renders an empty, uninitialised table.

---

## 1. Requirements, from the reference screenshots

These are AMU's real production screens, so this is a requirements list, not a wishlist.

| # | Requirement | Seen in |
|---|---|---|
| 1 | Server-side pagination, **100 rows** default | every admin list |
| 2 | **Per-column filters** in a second header row — text inputs and selects | advertisement list |
| 3 | Global search | all |
| 4 | Column sorting with a default sort | all |
| 5 | **Six exports** — Copy, CSV, Excel, PDF, Print, Columns | all |
| 6 | **Column visibility** toggle | all |
| 7 | Select all / deselect all / **bulk delete** | all |
| 8 | Row actions — View / Edit / Delete | all |
| 9 | **Composite cells** — `106 / 63 / 58 / 13⚑` in one column | advertisement detail |
| 10 | **Dossier records** — 7 columns, each a rich block with a photograph, computed age, nested lists and cross-application links | applicant list |
| 11 | Dependent filters — advertisement → post | attendance sheet, bulk documents |
| 12 | Scoped result sets by role | Dean's-office queues (DR-010) |

---

## 2. Architecture

**One Blade component, one Alpine store, one server contract.** Not a per-module reimplementation —
34 tables built 34 ways is how the current codebase got here.

```
<x-data.table :query="$query" :config="$config" />
        │
        ├── server:  App\Support\Table\TableQuery
        │              ->filters()  ->sort()  ->paginate()  ->export()
        │
        └── client:  Alpine component  x-data="dataTable({...})"
                       selection · column visibility · density · filter debounce
```

**State lives in the URL**, not in component memory: `?page=2&sort=-submitted_at&f[status]=submitted`.
A filtered view is therefore linkable, bookmarkable, back-button-correct and shareable with a
colleague — the last of which matters when a scrutiny officer escalates a case.

### 2.1 The server contract

```php
final class TableQuery
{
    public function __construct(
        private Builder $query,
        private TableConfig $config,
        private Request $request,
    ) {}

    public function results(): LengthAwarePaginator;   // ALWAYS paginated
    public function export(ExportFormat $f): Response; // streamed, queued above threshold
}
```

**Four rules, each preventing a specific failure:**

1. **Every column declares `sortable`, `filterable` and `filter_type`.** No sorting or filtering on
   an undeclared column — that is a SQL-injection vector and an unindexed-scan generator.
2. **Sorting and filtering only on indexed columns.** Enforced by a test that reads the config and
   asserts a matching index exists. At 78,232 rows an unindexed sort is a 30-second page.
3. **Scoping is applied before anything else.** `$query->visibleTo($user)` runs first, so a filter
   can never widen a result set beyond the actor's ownership or OU scope (DR-010). Filters narrow;
   they never widen.
4. **Eager-load declared per table.** The dossier record touches nine relations. Without declared
   eager-loading it is 100 rows × 9 queries.

### 2.2 Rendering

Server-rendered Blade, `wire`-free. Filter changes issue a normal `GET` with `x-on:input.debounce.400ms`
updating `window.location`. Progressive enhancement: **the table works with JavaScript disabled** —
filters are a plain `<form method="GET">`, pagination is plain links, sorting is a link. Alpine adds
selection, column visibility and density on top.

That is a GIGW expectation, and it is also what makes the table testable without a browser.

---

## 3. Performance at 78,232 rows

| Concern | Measure |
|---|---|
| Count query | `LengthAwarePaginator` runs `COUNT(*)` per page. Above 50,000 rows use **`SimplePaginator` plus a cached approximate count**, refreshed every 5 minutes. Exact counts appear in reports, not in a pager |
| Deep pagination | `OFFSET 78000` is a table scan. Beyond page 100 the UI **requires a filter** rather than offering the offset — with a message saying so, not a silent failure |
| Composite counts | `106 / 63 / 58 / 13` per post is four aggregates × 100 rows. Maintained as **counter columns** on `posts`, updated by observers on application state change. Never computed per render |
| Dossier records | 9 relations × 100 rows. Eager-loaded, and the record renders **lazily on expand** — the collapsed row shows identity and status only |
| Exports | Over 5,000 rows the export is **queued**, and the user is notified with a download link. The legacy code sets `memory_limit = -1` and `max_execution_time = -1` and hopes |
| Photographs | The dossier shows a passport photo per row. Served at **80×100 from a pre-generated conversion**, `loading="lazy"`, never the original upload |

---

## 4. Anatomy

```
┌──────────────────────────────────────────────────────────────────────┐
│ Application Forms                        [Density ⇕] [Columns ▾]     │
│ ┌─────────────────────────┐   Copy CSV Excel PDF Print   [Search  ]  │
│ │ 12 selected   Delete ▾  │                                          │
│ └─────────────────────────┘                                          │
├──┬──────────┬───────────────────┬──────────┬───────────┬─────────────┤
│☐ │ APP NO   │ CANDIDATE         │ CATEGORY │ SUBMITTED │ SCRUTINY    │  ← --t-label
├──┼──────────┼───────────────────┼──────────┼───────────┼─────────────┤
│  │ [search] │ [search         ] │ [All  ▾] │ [range  ] │ [All     ▾] │  ← filter row
├──┼──────────┼───────────────────┼──────────┼───────────┼─────────────┤
│☐ │ 10087779 │ MOHAMMAD B. ZAHID │ General  │ 23-01-2026│ ✓ Eligible  │
│☐ │ 10087780 │ AISHA KHAN        │ OBC-NCL  │ 23-01-2026│ ◦ Pending   │
├──┴──────────┴───────────────────┴──────────┴───────────┴─────────────┤
│ Showing 1–100 of 78,232            ‹ 1 2 3 … ›      100 per page ▾   │
└──────────────────────────────────────────────────────────────────────┘
```

Ruled, not tiled (`design-system.md` §4.3). Column rules `--rule-strong`, row rules `--rule`. Header
`--paper-sunk`, sticky. Identifiers in **IBM Plex Mono**, counts in **tabular** Plex Sans.

---

## 5. The dossier record

The applicant list is not a table of scalars. Each row is a **7-column record**, and it is what a
scrutiny officer actually works with.

| Column | Contents |
|---|---|
| **1 Identity** | User id, application no (mono), **passport photograph**, name, father's / mother's / spouse name, email, gender, DOB with **computed age** (`41 years 3 months`), mobile, disability type and %, religion, category, caste, **computed total experience**, submitted-at, and **"Other applications"** — a linked list of this candidate's other applications |
| **2 Address** | Correspondence and permanent, with PIN; domicile district and state |
| **3 Qualifications** | Chronological: degree, institution, year, %, CGPA |
| **4 Experience** | Organisation, designation, pay, duration, nature of duties |
| **5 Referees & testimonials** | Two sub-blocks |
| **6 Institutions attended** | Name, affiliating university, place, year range |
| **7 Action** | **Eligibility** — opens the gate control |

**Collapsed by default**, showing columns 1 and 7. Expanding fetches the rest. At 100 rows × 7 rich
columns, rendering everything up front is neither fast nor readable.

**Age and total experience are computed and labelled as computed** — `41 years 3 months` with a
tooltip naming the reference date. Age uses `posts.reg_end_date` per **CRR Rule 14**, never today.
An officer must never have to work out which date a figure was calculated against.

---

## 6. The gate control

Reached from column 7. This replaces the reference modal, with two defects deliberately fixed.

```
┌─ Update eligibility ─────────────────────────────────────────┐
│ Application 10087779 · MOHAMMAD BASIM ZAHID                  │
│ Post 2599 System Manager · Written test + Interview          │
├──────────────────┬──────────────────┬────────────────────────┤
│ SCRUTINY         │ WRITTEN TEST     │ INTERVIEW              │
│ ◉ ✓ Eligible     │ ○ ✓ Eligible     │ ○ ✓ Eligible           │
│ ○ ✕ Not eligible │ ○ ✕ Not eligible │ ○ ✕ Not eligible       │
│ ○ ◦ Pending      │ ◉ ◦ Pending      │ ◉ ◦ Pending            │
│ Remark           │ Remark           │ Remark                 │
│ [             ]  │ [             ]  │ [             ]        │
├──────────────────┴──────────────────┴────────────────────────┤
│                                    Cancel   Save decisions   │
└──────────────────────────────────────────────────────────────┘
```

**Fix 1 — three explicit options, not a merged label.** The reference control renders
`Pending / Not Eligible` over three distinct stored values (`1` / `0` / `NULL`). On a decision that
determines whether someone is considered for a job, that ambiguity is not acceptable.

**Fix 2 — only active gates are rendered.** The gate set comes from
`post_types.default_selection_method`. On an interview-only post the written-test column **does not
exist** — the reference control shows all three regardless, even where the schema comment says
written-test eligibility should be *"Blank if post is interview-only"*.

**Plus:** a rejection **requires** a remark, enforced server-side. Saving writes one
`eligibility_decisions` row per gate and one hash-chained audit entry per change, carrying the
previous value.

---

## 7. Exports

| Format | Implementation |
|---|---|
| Copy | Clipboard, visible columns, TSV |
| CSV | Streamed `fputcsv`, UTF-8 BOM for Excel |
| Excel | `maatwebsite/excel`, streamed, chunked |
| PDF | `barryvdh/laravel-dompdf`, landscape, repeating header |
| Print | Print stylesheet — no chrome, black on white, repeating `<thead>` |
| Columns | Visibility toggle, persisted in `localStorage` |

**Every export writes an `export.generated` audit event** with actor, row count and applied filters.
An export is bulk PII leaving the system; it is exactly the event an audit chain exists for.

**Exports respect scope.** They run the same `visibleTo($user)` query. A Dean's-office user cannot
export another faculty's candidates by hitting the export URL directly — tested explicitly.

---

## 8. Accessibility

| Requirement | Implementation |
|---|---|
| Semantics | Real `<table>`. `<caption>` naming the table and current filters. `<th scope="col">` |
| Sorting | `aria-sort="ascending\|descending\|none"`; the header is a `<button>` inside the `<th>` |
| Filter row | Each input labelled `aria-label="Filter by Category"` |
| Selection | Header checkbox `aria-label="Select all rows on this page"`. Selection count in an `aria-live="polite"` region |
| Row actions | Buttons with accessible names including the subject — `"Update eligibility for application 10087779"` |
| Composite cell | `aria-label="106 total, 63 submitted, 58 paid, 13 internal"` |
| **2.4.11 Focus not obscured** | The sticky header must not cover a focused row — `scroll-margin-block-start` equal to header height |
| **2.5.8 Target size** | 24×24px minimum, held even in compact density |
| Keyboard | Arrow keys move between rows, `Space` toggles selection, `Enter` opens the row action |
| Zoom | 200% without loss — the table scrolls in its own container, **the page body never scrolls sideways** |

---

## 9. Test strategy

| Test | Asserts |
|---|---|
| No jQuery | No rendered page loads jQuery or DataTables — grep the built output, fail the build |
| **No empty tables** | Every admin index renders **rows**, not just a `<thead>` — the defect on 33 screens today |
| Sort safety | Sorting by an undeclared column is **rejected**, not silently ignored |
| Index coverage | Every `sortable`/`filterable` column has a matching database index — read from config, asserted against the schema |
| **Scope before filter** | A Dean's-office user of Faculty X cannot reach Faculty Y's rows through **any** filter, sort or page combination |
| Export scope | The export URL returns the same scoped set — no privilege escalation by direct hit |
| Export audit | Every export writes `export.generated` with the applied filters |
| N+1 | `assertQueryCount` on a 100-row dossier page stays within the declared budget |
| Deep page | Page 200 without a filter returns the guidance message, not a timeout |
| Gate control | An interview-only post renders **two** gates; a rejection without a remark is refused |
| No-JS | Filters, sorting and pagination all work with JavaScript disabled |
| a11y | `axe-core` clean on every table route |

---

## 10. Traceability

| Section | Feeds |
|---|---|
| §2 | `App\Support\Table` — shared, built once in Wave 0 |
| §3 | M23 Analytics · every admin list |
| §5 | M18 Scrutiny Workbench |
| §6 | M18 · M34 Eligibility Gates |
| §7 | M23 · M26 audit |
| §8 | GIGW · WCAG 2.2 AA |

---

## 11. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Specifies the shared table component, the server contract with its four safety rules, performance measures for 78,232 rows, the 7-column dossier record, the corrected gate control, exports with scope and audit, and full keyboard/AT semantics. | Implementation team |
