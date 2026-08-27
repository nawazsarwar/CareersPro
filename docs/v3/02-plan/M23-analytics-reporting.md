# M23 — Analytics & Reporting

**Wave:** 9 · **Scope:** v1
**Depends on:** M34, M08, M25, M26

## 1. Purpose and statutory basis

The admin dashboard and the statutory exports.

| Obligation | Source |
|---|---|
| Aggregate counts furnished on RTI request — applications, category-wise, gender-wise, per post | RTI Act 2005 · `../01-design/security/data-protection.md` §6 |
| **30-day** advertisement window and **6-month** process cap must be monitored | CRR · DoPT O.M. Misc.14017/15/2015-Estt.(RR) |
| Roster compliance reporting | CRR Rule 15.1 — DOC-003 |
| Every report reproducible **for a historical date**, not only current | M27 |

**The dashboard's business purpose, from AMU's own production figures:** ₹2,29,94,500 received
against ₹93,14,500 failed — a **~29% failure ratio**. The financial strip is not decoration; it is
the entry point to the reconciliation queue.

## 2. Data

No new domain tables. Reads across the system, plus:

```
report_definitions  id · code UNIQUE · title · category · query_key
                    parameters json · columns json · is_statutory bool
report_runs         id · report_definition_id · parameters json
                    as_at_date NULL              -- historical reconstruction
                    row_count · media_id NULL
                    run_at · run_by_id · status enum(queued, ready, failed)
```

**Counter columns** on `posts` and `advertisements`, maintained by observers:
`applications_count` · `submitted_count` · `paid_count` · `internal_count` ·
`scrutiny_eligible_count` · `written_test_eligible_count` · `interview_eligible_count`.

**Indexes:** `report_runs(report_definition_id, run_at)`.

## 3. Domain services

```
App\Domain\Reporting\Dashboard::for(User): DashboardData
App\Domain\Reporting\RunReport::handle(ReportDefinition, array $params, User): ReportRun
App\Domain\Reporting\SlaMonitor::breaches(): Collection
App\Domain\Reporting\HistoricalReport::asAt(ReportDefinition, CarbonInterface): ReportRun
```

**Invariants.**
- **Every query applies `visibleTo($user)` first.** A Dean's-office dashboard shows that faculty's
  local recruitment only, and the page says so.
- **Counters are read, never aggregated per render.** The composite `106/63/58/13` cell at 100 rows
  is four aggregates × 100 without them.
- **`HistoricalReport` reconstructs from snapshots and the audit chain**, not from current state —
  otherwise an RTI answer about 2026 would be computed from 2029 data.
- Reports over 5,000 rows are **queued**.
- Every export writes `export.generated` with parameters and row count.

## 4. Routes and controllers

| Verb | URI | Name | Policy |
|---|---|---|---|
| GET | `/admin` | `admin.dashboard` | `ReportPolicy@viewDashboard` |
| GET | `/admin/reports` | `admin.reports.index` | `ReportPolicy@viewAny` |
| POST | `/admin/reports/{definition}/run` | `admin.reports.run` | `@run` |
| GET | `/admin/reports/runs/{run}` | `admin.reports.show` | `@view` |
| GET | `/admin/reports/runs/{run}/download` | `admin.reports.download` | `@view` |
| GET | `/admin/sla` | `admin.sla.index` | `@viewAny` |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `advertisement_id` / `post_id` | nullable, exists · **within scope** | |
| `date_from` / `date_to` | nullable, date, `to` after `from`, **range ≤ 5 years** | Choose a range of five years or less. |
| `as_at_date` | nullable, date, `before_or_equal:today` | |
| | **must be on or after the earliest snapshot** | No records exist before {date}. |
| `format` | required, in:csv,xlsx,pdf | |
| statutory report parameters | **must match the definition's declared schema** | |

## 6. Authorisation

`ReportPolicy` extends `ScopedPolicy`.

| Actor | Sees |
|---|---|
| `recruitment_admin`, `exam_admin` | university-wide |
| `dean_office` | **their subtree only**, stated in the page subtitle |
| `finance_admin` | payment reports; **no PII beyond name and application number** |
| `auditor` | all reports, read-only |

## 7. UI

Dashboard per `../01-design/ux/screens.md` §2 — four figures, the 12-month submitted-vs-paid trend,
three goal-completion bars, the financial strip, latest applications and members. **Ruled rows and
inline SVG charts**, not tiles and ApexCharts.

**The financial strip is clickable** and opens the reconciliation queue. A 29% failure rate that
only reports itself is a number; one that opens the work is a control.

**SLA monitor:** advertisements approaching or breaching the 30-day window and the 6-month cap, with
days remaining and the extension reference where one exists.

Report list grouped by category, statutory reports marked. Historical runs take an **as-at date**,
with the reconstruction basis stated on the output.

## 8. Worked example

A Dean's-office user of the Faculty of Arts opens `/admin`.

The subtitle reads *"Faculty of Arts and 3 departments — local recruitment."* Figures: 4
advertisements, 11 posts, 312 applications, all scoped by `ou_path_snapshot LIKE '/1/11/%'`. The
financial strip shows only their posts' orders.

A `recruitment_admin` sees the full figures: 1,045 advertisements, 2,874 posts, 79,659 applications,
55,050 users, and ₹93.14 lakh failed — which they click, landing in the reconciliation queue with
the failed filter applied.

**RTI request, October 2029:** *"category-wise applications for advertisement 2/2026/NT."* The
statutory report runs with `as_at_date = 2026-08-12` (process close). `HistoricalReport` reconstructs
from the snapshots as they stood, not from 2029 state, and the output states the basis and the
as-at date. A candidate archived since then still appears in the counts, because **nothing was
deleted** (DR-011).

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M23-R01 | Given a Dean's-office user, when the dashboard renders, then all figures are subtree-scoped and the subtitle says so |
| M23-R02 | Given a Dean's-office user, when any report is run, then out-of-scope rows never appear |
| M23-R03 | Given `finance_admin`, when a payment report runs, then no PII beyond name and application number appears |
| M23-R04 | Given 100 posts listed, when composite counts render, then they read counter columns — **query count within budget** |
| M23-R05 | Given an as-at date, when a historical report runs, then it reconstructs from snapshots, not current state |
| M23-R06 | Given data changed after the as-at date, when reconstructed, then the output is unaffected |
| M23-R07 | Given a report over 5,000 rows, when run, then it is queued |
| M23-R08 | Given any export, when generated, then `export.generated` records parameters and row count |
| M23-R09 | Given an advertisement within 5 days of the 6-month cap, when SLA runs, then it appears as approaching breach |
| M23-R10 | Given a breach with a recorded extension, when displayed, then the VC approval reference is shown |
| M23-R11 | Given the dashboard, when the financial strip is clicked, then the reconciliation queue opens filtered |
| M23-R12 | Given the dashboard route, when `axe-core` runs, then no violation is reported |

## 10. Test cases

`tests/Feature/Reporting/DashboardScopeTest` — R01, R02 · `FinanceRedactionTest` — R03 ·
`CounterPerformanceTest` — R04 · `HistoricalReportTest` — **R05, R06** · `QueueTest` — R07 ·
`ExportAuditTest` — R08 · `SlaMonitorTest` — R09, R10 · `tests/Accessibility/DashboardTest` — R12.

R06 scores an application, mutates the dossier, then reconstructs at the earlier date and asserts the
original figures.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R03 | `App\Domain\Reporting\Dashboard`, `App\Policies\ReportPolicy` |
| R04 | counter columns and their observers |
| R05, R06 | `App\Domain\Reporting\HistoricalReport` |
| R07, R08 | `App\Jobs\RunReport` |
| R09, R10 | `App\Domain\Reporting\SlaMonitor` |
