# M08 — Fee & Payment

**Wave:** 5 · **Scope:** v1
**Depends on:** DR-004 · M05, M16
**Blocked by:** **OQ-001** *(gateway vendor — the adapter only. The domain is vendor-agnostic, so
design and build proceed.)*

## 1. Purpose and statutory basis

Collect the application fee, reconcile it, and **never charge twice**.

| Obligation | Source |
|---|---|
| Fee **payable through online/offline payment**, in the prescribed format | CRR Rule 11 III(a)–(b) |
| The schedule of charges is **determined by the Vice-Chancellor** | CRR Rule 11 III(c) |
| **Concessions… as per Govt. of India norms** | CRR Rule 11 III(c) — DOC-003 |
| The fee is **non-refundable** | AMU manual, step 6 |

**The business case, from AMU's own dashboard:** **₹2,29,94,500 received** against **₹93,14,500 in
failed transactions** — a **~29% failure ratio**. CU-Chayan's seven documented weakness categories
include *"double deductions at deadline hours: money debited, status stays Unpaid"* and *"slow
auto-reconciliation forcing duplicate payments."*

**And there is no payment schema at all today.** `DATABASE_SCHEMA.md` §3 marks Finance/Orders
*"Pending/Staged"*; `betacareers_db` has no `orders`, `transactions`, `receivables` or `services`.
The only vestiges are `application_forms.paid` and `.order_uid`, both unwritten.

## 2. Data

`fee_rules` · `orders` · `transactions` · `reconciliations` · `receipts` · `refunds` — schema in
`../01-design/domain/domain-model.md` §8.

**The load-bearing column is `orders.idempotency_key`**, unique, derived from
`sha256(user_id|post_id|attempt)`.

**Indexes:** `orders.idempotency_key` unique · `orders.pg_ref_no` · `orders(application_id)` ·
`orders(status, created_at)` · `transactions(gateway_txn_id)` unique.

**No card data is ever stored.** Not masked, not hashed, not at all.

## 3. Domain services

```
App\Domain\Payment\PaymentGateway                interface
App\Domain\Payment\Gateways\{Vendor}Gateway      adapter — OQ-001
App\Domain\Payment\Gateways\MockGateway          local and test
App\Domain\Payment\ComputeFee::for(User, Post): Money
App\Domain\Payment\CreateOrder::handle(Application): Order      // idempotent
App\Domain\Payment\HandleCallback::handle(array $payload): void
App\Domain\Payment\ReconcileMisFile::handle(UploadedFile, string $gateway): ReconciliationReport
```

```php
interface PaymentGateway {
    public function initiate(Order $o): RedirectPayload;
    public function verify(array $payload): VerificationResult;
    public function status(Order $o): OrderStatus;      // server-to-server, authoritative
}
```

**Invariants — each one prevents a specific observed failure:**

- **`CreateOrder` is idempotent.** Calling it twice for the same `(user, post, attempt)` returns the
  **same** order. This is the double-deduction fix.
- **The callback is never trusted alone.** Every callback triggers a server-to-server `status()`
  check before an order is marked paid. A forged callback cannot mark an order paid.
- **Reconciliation is authoritative.** Where the MIS file and local state disagree, the gateway's
  record wins and the discrepancy is recorded.
- **`double_payment` is a first-class status**, not an exception path.
- **Payment never mutates the application snapshot.** It sets `paid` and `order_id` only.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET | `/applications/{application}/pay` | `payment.start` | `auth`, `verified` | `PaymentPolicy@pay` |
| POST | `/applications/{application}/pay` | `payment.initiate` | as above, `throttle:10,60` | `@pay` |
| GET/POST | `/payment/callback/{gateway}` | `payment.callback` | `throttle:120,1`, **signature verification** | — |
| GET | `/applications/{application}/receipt.pdf` | `payment.receipt` | `auth` | `@view` |
| GET | `/admin/payments` | `admin.payments.index` | `auth`, `2fa` | `PaymentPolicy@viewAny` |
| POST | `/admin/payments/reconcile` | `admin.payments.reconcile` | as above | `@reconcile` |
| GET | `/admin/payments/discrepancies` | `admin.payments.discrepancies` | as above | `@viewAny` |
| POST | `/admin/payments/{order}/refund` | `admin.payments.refund` | as above | `@refund` |

The callback route is **outside `auth`** — the gateway is not a logged-in user — and is protected by
signature verification, rate limiting and the server-to-server confirmation.

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| application | **submitted**, **unpaid**, **within `payment_closing_date`** | The payment window for this post has closed. |
| `fee_rules.amount` | required, numeric, min:0 | |
| `fee_rules.category` | required, exists — **one rule per (post, category, horizontal)** | A fee rule already exists for this combination. |
| callback `signature` | required, **verified against the gateway secret** | |
| callback `order_uid` | required, exists | |
| reconciliation file | required, `mimes:csv,xlsx`, max 10 MB, **expected column set** | The file does not match the expected format. |
| refund `reason` | required, min:20 · **`super_admin` only** | Record why this refund is being made. |

**Fee exemption** is applied from the reservation policy version frozen on the advertisement. With no
active policy (M17), exemptions are **administrator-entered** and recorded as such.

## 6. Authorisation

`PaymentPolicy` — **ownership** for `pay` and `view`. `viewAny`, `reconcile` for `finance_admin`;
`refund` for `super_admin` only, always audited.

**`finance_admin` sees no PII beyond name and application number** — the payments table exposes
order, amount, gateway reference and status, and nothing else
(`../01-design/security/security-model.md` §3.1).

## 7. UI

**Candidate:** fee breakdown with any concession and its basis, the non-refundable notice, then the
gateway redirect. On return: a clear state — `Paid`, `Pending confirmation` or `Failed` — with
**"Do not pay again. If money has been debited, it will be reconciled within 24 hours"** on the
pending state. That sentence is the whole difference from the legacy behaviour.

**Admin:** the reconciliation queue as the dashboard financial strip's destination, showing matched,
unmatched and double-payment counts, with per-row drill-down.

## 8. Worked example — the failure this module exists for

Aisha pays ₹500 for post 2599 at 23:47 on the closing date.

1. `CreateOrder` → `idempotency_key = sha256(48760|2599|1)` → order `ORD-2026-0041207`,
   `status: created`.
2. Gateway redirect. The bank debits ₹500. **Her connection drops before the callback.**
3. Her order is still `pending`. She reloads and sees:
   *"Payment pending confirmation. Do not pay again."*
4. She clicks Pay again anyway. `CreateOrder` returns **the same order** — no second gateway session,
   **no second charge**.
5. At 02:00 the reconciliation job ingests the gateway MIS file, matches on `pg_ref_no`, and sets
   `status: paid`, `paid_at`. A receipt is issued. `applications.paid = true`.
6. Under the legacy design she pays twice and joins the ₹93.14 lakh.

**And if she had somehow completed two distinct gateway sessions:** reconciliation detects two
successful transactions against one application, marks the second `double_payment`, and it appears in
the discrepancy queue for refund — rather than being silently absorbed.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M08-R01 | Given an order exists, when `CreateOrder` runs again for the same key, then the **same** order is returned |
| M08-R02 | Given a lost callback, when reconciliation runs, then the order is marked paid **without a second charge** |
| M08-R03 | Given a forged callback, when received, then it is rejected on signature verification |
| M08-R04 | Given a valid callback, when processed, then a **server-to-server** status check occurs before marking paid |
| M08-R05 | Given two successful transactions for one application, when reconciled, then the second is `double_payment` |
| M08-R06 | Given a closed payment window, when paying, then it is refused |
| M08-R07 | Given a paid application, when paying again, then it is refused |
| M08-R08 | Given a fee rule per category, when a candidate pays, then the correct amount is charged |
| M08-R09 | Given `finance_admin`, when viewing payments, then no PII beyond name and application number is exposed |
| M08-R10 | Given a refund, when processed by anyone but `super_admin`, then **403** |
| M08-R11 | Given any payment state change, when it commits, then an audit entry is written |
| M08-R12 | Given a payment, when it completes, then the application **snapshot is unchanged** |
| M08-R13 | Given a MIS file with an unexpected column set, when uploaded, then it is rejected |
| M08-R14 | Given concurrent duplicate `CreateOrder` calls, when they race, then the unique index prevents a second row |

## 10. Test cases

`tests/Feature/Payment/IdempotencyTest` — **R01, R14 (concurrent)** ·
`ReconciliationTest` — R02, R05, R13 · `CallbackSecurityTest` — R03, R04 ·
`PaymentWindowTest` — R06, R07 · `FeeComputationTest` — R08 ·
`Authz/PaymentScopeTest` — R09, R10 · `AuditTest` — R11 · `SnapshotIntegrityTest` — R12.

Fixtures: `MockGateway` with `succeeds()`, `dropsCallback()`, `doubleCharges()` and
`forgesSignature()` behaviours; a sample MIS file and a malformed one.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01, R14 | `App\Domain\Payment\CreateOrder`, `orders.idempotency_key` unique index |
| R02, R05, R13 | `App\Domain\Payment\ReconcileMisFile` |
| R03, R04 | `App\Domain\Payment\HandleCallback` |
| R06–R08 | `App\Domain\Payment\ComputeFee`, `App\Http\Requests\Payment\*` |
| R09, R10 | `App\Policies\PaymentPolicy` |
| R12 | `App\Domain\Payment\CreateOrder` — asserts no snapshot write |
