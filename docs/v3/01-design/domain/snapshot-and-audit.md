# Snapshots and the Audit Chain

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27

Three documents in the previous set assert hash-chained immutable audit logs — `docs/spec/security.md`,
`docs/spec/state-machine.md` and `docs/MEMORY.md`. The table they describe is a stock Spatie
activity-log table: `id, description, subject_id, subject_type, user_id, properties, host,
created_at, **updated_at**`. No hash. No previous_hash. No sequence. A mutable `updated_at`.

And `MEMORY.md`'s *"time-travelling data"* invariant, `MODULES.md` #27 *"point-in-time
reconstruction"*, `domain-model.md`'s *"relationship snapshots"* and `scoring-engine.md`'s
*"idempotent re-scoring"* all depend on a snapshot table **that exists in neither database**.

This document specifies both, for real.

---

## 1. What must be true, and why

| Requirement | Source |
|---|---|
| Reconstruct an application **exactly as it was scored on any historical date** | `MEMORY.md` invariant; M27 RTI/Legal |
| Re-scoring is **byte-identical** | `scoring-engine.md` I3 |
| Every state change, score override and document access is **provably unaltered** | M26 |
| Claims verifiable *"at any point of time **even after joining**"* | **CRR Rule 22.4** |
| **Nothing is ever deleted electronically** | DR-011 |

That last one is what makes the rest tractable: with no deletion, an append-only design has no
tension to resolve.

---

## 2. Snapshots

### 2.1 Table

```
application_snapshots
  id                bigint PK
  application_id    → applications
  sequence          int          -- 1, 2, 3… per application
  reason            enum(submit, correction_window, rescore, admin_correction)
  payload           json         -- the canonical serialisation
  content_hash      char(64)     -- SHA-256 over the canonical form
  taken_at          timestamp
  taken_by_id       → users

  UNIQUE (application_id, sequence)
  -- APPEND ONLY: no UPDATE, no DELETE, no soft delete, NO updated_at
```

Enforced three ways: no `update`/`delete` methods on the repository; a model `saving` guard that
throws on a dirty existing row; and a **database trigger** rejecting `UPDATE` and `DELETE`. Belt,
braces and a lock on the door — because this is the evidence.

### 2.2 What goes in

Everything the score and the decision depend on:

profile · addresses · academic qualifications (with `ncrf_level` and the PhD-Regulations compliance
flag) · eligibility tests · employment history · institutions attended · teaching/research
experience · foreign visits · referees · **research claims with their evidence document ids and
hashes** · notable contributions · other details · the applied-under category and horizontal
category · the post and its OU snapshot · **`rule_set_version_id`** · **`reservation_policy_version_id`**.

**Documents are referenced by id *and content hash*, not embedded.** The snapshot stays small; the
hash proves the file behind the reference is the one that was assessed.

### 2.3 Canonical serialisation — the part that is easy to get wrong

`content_hash` is meaningless unless serialisation is canonical. Non-negotiable rules:

1. **Object keys sorted** lexicographically, at every depth.
2. **Arrays ordered** by a declared stable key — `id` unless stated otherwise. Never by insertion.
3. **Timestamps** ISO-8601 UTC with seconds precision: `2026-01-23T21:24:55Z`.
4. **Decimals** as strings with fixed scale — `"92.50"`, never a float. Float formatting is
   platform-dependent, and the whole determinism guarantee dies on it.
5. **`null` omitted**, never emitted as `null`.
6. **UTF-8 NFC** normalisation. Unescaped non-ASCII — AMU records carry Hindi and Urdu titles.
7. **No whitespace** between tokens.

```
content_hash = SHA-256( canonical_json( payload ) )
```

A `CanonicalJson` value object owns this. **It has its own test suite**, including a fixture
round-tripped on two PHP versions — because a serialisation change silently invalidates every
existing hash.

### 2.4 When taken

| Trigger | Reason | Note |
|---|---|---|
| T1 submit | `submit` | Sequence 1. The dossier locks |
| T4 deficiency rectified | `correction_window` | New sequence. **Earlier snapshots untouched** |
| Ruleset ratification forces a rescore | `rescore` | Same dossier, new score run |
| Administrative correction | `admin_correction` | Requires reason + elevated permission |

---

## 3. The audit chain

### 3.1 Table

```
audit_logs
  id             bigint PK
  sequence       bigint UNIQUE       -- global, gapless, monotonic
  previous_hash  char(64)            -- hash of sequence-1; genesis = 64 zeros
  hash           char(64)            -- SHA-256 over this row's canonical form
  event          varchar             -- application.submitted, eligibility.decided, …
  subject_type   varchar             -- morph alias, NOT a class name
  subject_id     bigint
  actor_id       → users (nullable — system actions)
  actor_ip       varchar
  actor_role     varchar             -- the role in effect, incl. OU scope
  properties     json                -- before/after, redacted per §3.4
  occurred_at    timestamp(6)
  -- NO updated_at. NO soft delete.
```

### 3.2 The chain

```
hash(n) = SHA-256( canonical_json({
    sequence, previous_hash, event, subject_type, subject_id,
    actor_id, actor_ip, actor_role, properties, occurred_at
}) )
```

Altering row *n* changes `hash(n)`, which breaks `previous_hash` at *n+1*, and so on to the head.
Rewriting history means rewriting **every subsequent row** — detectable by a verifier that recomputes
the chain.

**`VerifyAuditChain` runs nightly** and on demand, reporting the first divergent sequence. **A chain
break is a P1 security incident**, not a data-quality warning.

**Sequence allocation** is serialised — a dedicated counter row taken `FOR UPDATE`, or a single-writer
queue. Gapless is a property we assert, so it must be a property we enforce.

### 3.3 What is audited

**Every model, without exception** — including `User`, `Role`, `Permission` and `ResearchPublication`,
which the current `Auditable` trait omits. **The security-sensitive models are precisely the
unaudited ones today.**

Plus events with no model change:

| Event | Why |
|---|---|
| `document.accessed` | M26 requires document-access logging. Who read a candidate's Aadhaar scan |
| `auth.login` / `.failed` / `.locked_out` | |
| `impersonation.started` / `.ended` | With the actor's IP |
| `score.overridden` | With before, after and reason |
| `export.generated` | Bulk PII leaving the system |
| `ruleset.activated` | With `second_reader_verified` and the verifier |
| `hardcopy.destroyed` | DR-011 — the physical custody event |

### 3.4 Redaction

`properties` stores whole rows today, unredacted — including Aadhaar, mobile and address. Under DPDP
that turns the audit log into a second, less-protected copy of the PII.

**Rule:** sensitive fields are recorded as `{ "changed": true, "hash": "…" }`, never as values. The
hash proves *what* changed without storing it. Redacted: `aadhaar_no`, `password`, `remember_token`,
`otp_code`, `two_factor_secret`, bank and gateway credentials.

### 3.5 Also fixed

- `subject_type` uses a **morph alias** (`application`), not `"App\Models\X#123"` as today — class
  names in a permanent log break on refactor.
- **`restored` and `forceDeleted` hooks**, absent today.
- `occurred_at` at **microsecond** precision — ordering matters in a chain.

---

## 4. Point-in-time reconstruction

**Question:** *"Show me application 10087779 exactly as it was scored on 2026-04-15."*

```
1. snapshot  = latest application_snapshots
                 WHERE application_id = 10087779 AND taken_at <= '2026-04-15'
                 ORDER BY sequence DESC LIMIT 1
2. run       = latest score_runs WHERE snapshot_id = snapshot.id
                 AND computed_at <= '2026-04-15'
3. ruleset   = rule_set_versions[run.rule_set_version_id]      (immutable)
4. VERIFY    recompute(snapshot, ruleset).output_hash == run.output_hash
5. TIMELINE  audit_logs WHERE subject = application
                 AND occurred_at <= '2026-04-15' ORDER BY sequence
6. INTEGRITY verify_chain(1 .. head)
```

Step 4 is the point. **The reconstruction is not "here is what we stored" but "here is the input, here
is the rule, and re-running them now reproduces the same output."** That is what survives
cross-examination.

---

## 5. Retention — DR-011

| Record | Retention |
|---|---|
| Applications, snapshots, score runs, audit chain | **Indefinite.** `archived`, never deleted |
| Hard copies — selected candidates who joined | **Permanent**, central record section |
| Hard copies — unsuccessful candidates | **Destroyed 5 years** after process close |

```
hardcopy_receipts
  application_id · received_at · received_by_id · storage_location
  destruction_due_on          -- process close + 5 years, unsuccessful only
  destroyed_at · destroyed_by_id · destruction_batch_ref
```

Destruction writes a `hardcopy.destroyed` audit event. **The electronic record is untouched.**

**This must be argued, not assumed.** Indefinite electronic retention is *not* data minimisation. It
is defensible under DPDP 2023 only as a **statutory-obligation and legal-claims** basis, reinforced by
CRR Rule 22.4's perpetual verification right and RTI exposure. `../security/` carries that argument
in full; it is the single largest DPDP exposure in the design and it is deliberate.

---

## 6. Performance

At 78,232 applications the chain will be large. Three notes:

1. **Snapshots are written once and read rarely.** Compress `payload` at rest; index only
   `(application_id, sequence)` and `taken_at`.
2. **The audit chain is append-only and never updated** — no lock contention beyond the sequence
   counter. Partition by month.
3. **Full-chain verification is O(n).** Nightly it runs whole; on demand it verifies a range and
   the checkpoint hashes either side. **Checkpoint rows every 10,000 sequences** carry a signed
   cumulative hash, so a targeted verification is bounded.

---

## 7. Test strategy

| Test | Asserts |
|---|---|
| Canonical JSON | Key order, array order, decimal-as-string, null omission, NFC — fixture-based, cross-version |
| Snapshot immutability | `UPDATE` and `DELETE` both rejected at repository, model **and database trigger** |
| Hash stability | The same payload hashes identically across processes and PHP versions |
| Chain integrity | Tamper with row *n* ⇒ verifier reports **exactly** sequence *n* |
| Gapless sequence | 1,000 concurrent writes ⇒ no gaps, no duplicates |
| Redaction | `aadhaar_no` never appears as a value in `properties` |
| Reconstruction | Score, mutate the profile, reconstruct at the earlier date ⇒ **original** total |
| Determinism | Recompute from a snapshot ⇒ identical `output_hash` |
| Coverage of models | Every model in `app/Models` emits audit events — enumerated, not sampled |

---

## 8. Traceability

| Section | Feeds |
|---|---|
| §2 | M05 submit · M18 rectification · M27 |
| §2.3 | `CanonicalJson` — its own suite |
| §3 | M26 Audit & Traceability |
| §3.4 | `../security/` DPDP |
| §4 | M27 RTI / Legal Support |
| §5 | M33 Hardcopy custody · DR-011 |

---

## 9. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Specifies the append-only snapshot table, canonical serialisation, the genuinely hash-chained audit log with gapless sequence and checkpoints, redaction, the reconstruct-and-verify procedure, and the DR-011 retention split. | Implementation team |
