# Security Model and Authorisation

**Status:** live · **Owner:** implementation team · **Created:** 2026-08-27
**Supersedes:** `docs/spec/security.md` — 14 lines whose three load-bearing claims (hash-chained
audit logs, encryption at rest for PII, DPDP compliance) are each contradicted by the schema
document in the same repository.

---

## 1. What we are actually protecting

Not an abstraction. Four concrete assets:

| Asset | Scale | Exposure if lost |
|---|---|---|
| **Candidate PII** | 55,050 users — caste, religion, disability type and percentage, marital status, spouse name, identity marks, **biometric thumb impressions**, Aadhaar, criminal-record declarations, medical fitness | Special-category data under DPDP 2023. Caste and disability data on 55,000 people is a serious breach |
| **Documents** | 92,064 uploads | Degree certificates, experience letters, identity proof |
| **Money** | ₹2.29 crore collected, ₹93.14 lakh in failed transactions | Direct financial loss; reconciliation fraud |
| **Statutory decisions** | 78,232 applications | Eligibility, scoring and selection decisions that are litigable for years |

---

## 2. The defects this design closes

Every one verified in the current codebase on 2026-08-27.

| # | Defect | Severity |
|---|---|---|
| 1 | **No row-level authorisation anywhere.** The `frontend.` route group has **no `auth` middleware**, its 35 controllers are verbatim admin CRUD with no `where('user_id', …)`, and the seeder grants the `User` role `profile_edit`, `application_form_edit`, `academic_qualification_delete` and `photo_show` on **every row**. Any authenticated candidate can read and modify any other candidate's dossier | **Critical** |
| 2 | **Every new registration is permanently locked out.** `VerificationMiddleware` logs out any user with `verified = 0`; registration defaults it to `0`; `VerifyUserNotification` is imported but **never sent** | Critical |
| 3 | **`.env` committed** with live MySQL credentials; `debug_error.html` committed with a **live CSRF/session token**; a 245 KB SQLite DB committed | Critical |
| 4 | **Password reset dead** — `config/auth.php` expects `password_reset_tokens`, the migration creates `password_resets` | High |
| 5 | **API inert and unthrottled** — `auth:sanctum` on 27 endpoints with no `sanctum` guard, no `personal_access_tokens` table, `User` lacking `HasApiTokens`, and **no rate limiting** (`throttle:api` lives only in the dead `Http/Kernel.php`) | High |
| 6 | **Audit skips the sensitive models** — `Auditable` omits `User`, `Role`, `Permission`, `ResearchPublication`. No hash chain. `properties` stores whole rows unredacted | High |
| 7 | **Unrestricted upload** — `MediaUploadingTrait::storeMedia` accepts any file type; extension/MIME checks run only if the **client volunteers** `width`/`height`/`size` query params | High |
| 8 | **162 gate closures rebuilt on every request** — `AuthGates` loads all roles and permissions and calls `Gate::define()` 162 times per request, uncached | Medium |
| 9 | **`is_admin` hardcodes role id 1** | Medium |

---

## 3. Authorisation — two orthogonal scopes

This is the heart of the model and it is what the current system lacks entirely.

```
        ┌───────────────── permission ─────────────────┐
        │   can this ROLE perform this ACTION on this  │
        │   RESOURCE TYPE at all?                      │
        └──────────────────────┬───────────────────────┘
                               │  AND
        ┌──────────────────────┴───────────────────────┐
        │  SCOPE 1 — OWNERSHIP                         │
        │  is this row the actor's own?                │
        │  (candidates)                                │
        ├──────────────────────────────────────────────┤
        │  SCOPE 2 — ORGANISATIONAL UNIT               │
        │  is this row inside the actor's OU subtree?  │
        │  (Dean's-office staff — DR-010)              │
        └──────────────────────────────────────────────┘
```

**A permission alone never authorises access to a row.** Every policy method evaluates permission
**and** the applicable scope. This is enforced by a base policy that scoped resources must extend —
the check cannot be forgotten by omission.

### 3.1 Roles

| Role | Scope | Reaches |
|---|---|---|
| `candidate` | **ownership** | Only their own profile, documents, applications |
| `dean_office` | **OU subtree** | **Local** advertisements, posts, applications and scrutiny within their faculty and its children |
| `scrutiny_officer` | OU subtree **or** university-wide | Assigned scrutiny queue |
| `recruitment_admin` | university-wide | General advertisements and posts, all master data |
| `exam_admin` | university-wide | Centres, roll numbers, admit cards, attendance |
| `finance_admin` | university-wide | Orders, transactions, reconciliation. **No PII beyond name and application number** |
| `committee_member` | per-committee | Only applications for their committee's post, only during its window |
| `rules_admin` | university-wide | Ruleset authoring. **Cannot activate** — that needs `rules_verifier` |
| `rules_verifier` | university-wide | Second-reader verification and activation. **Must differ from the authoring user** |
| `auditor` | read-only, university-wide | Audit chain, snapshots, reports. **No mutation** |
| `super_admin` | university-wide | Everything. **Every action audited; impersonation always audited** |

**`rules_admin` and `rules_verifier` are deliberately separated.** Separation of duties on the
statutory ruleset is what would have stopped `ugc-rules.yaml` reaching production.

### 3.2 OU scope resolution

`role_user.organisational_unit_id` — `NULL` = university-wide, non-null = that unit **and its
subtree**.

```php
public function scopeVisibleTo(Builder $q, User $user): Builder
{
    $paths = $user->scopedOrganisationalUnitPaths();   // ['/1/11/']
    if ($paths === null) return $q;                    // university-wide
    return $q->where(fn ($q) => collect($paths)
        ->each(fn ($p) => $q->orWhere('ou_path_snapshot', 'like', $p.'%')));
}
```

**Uses `ou_path_snapshot` on the post row** — no join, one indexed range scan. See
`../domain/organisational-units.md` §3.

### 3.3 The authorisation matrix

`✓` = permitted subject to scope · `—` = denied · **bold** = the scope that applies.

| Resource | candidate | dean_office | scrutiny | recruit_admin | exam | finance | committee | auditor |
|---|---|---|---|---|---|---|---|---|
| Own profile | ✓ **own** | — | — | — | — | — | — | read |
| Other profile | **—** | ✓ **OU** read | ✓ read | ✓ read | — | — | ✓ read (window) | read |
| Own application | ✓ **own** | — | — | — | — | — | — | read |
| Other application | **—** | ✓ **OU** | ✓ | ✓ read | ✓ read | name+no only | ✓ (window) | read |
| Documents | ✓ **own** | ✓ **OU** | ✓ | ✓ | — | — | ✓ (window) | read |
| Eligibility decision | — | ✓ **OU** | ✓ | — | — | — | — | read |
| Advertisement (general) | read published | read | read | ✓ | read | read | read | read |
| Advertisement (local) | read published | ✓ **OU** | ✓ **OU** | ✓ | read | read | read | read |
| Master data | — | read | read | ✓ | read | — | — | read |
| Designations / sanctioned strength | — | read | — | ✓ | — | — | — | read |
| Orders / transactions | ✓ **own** | — | — | read | — | ✓ | — | read |
| Ruleset authoring | — | — | — | — | — | — | — | read |
| Ruleset activation | — | — | — | — | — | — | — | read |
| Audit chain | — | — | — | — | — | — | — | ✓ read |
| Impersonation | — | — | — | — | — | — | — | — |

**Every cell is a test.** `AuthorisationMatrixTest` iterates role × resource × action and asserts the
matrix — including the negatives, which is where defect #1 lives.

---

## 4. Authentication

Per **DR-008**: applicants by **email**; staff by **email or employee ID**; **no external SSO in v1**.

| Control | Specification |
|---|---|
| Identifier resolution | From the submitted value, not a fixed column. Employee IDs validated to exclude `@` |
| Password | `Rules\Password::defaults()` — min 12, mixed case, numbers, symbols, **not compromised** (`uncompromised()`). Argon2id. *(Current `StoreUserRequest` has `'password' => ['required']` — no length, no strength)* |
| Rate limiting | **5** login attempts per `email\|ip` per minute; **6** verification and OTP requests per minute; **60** API requests per minute per token. `RateLimiter::for('api')` defined in a real service provider |
| Email verification | `User implements MustVerifyEmail`. **One flow, not two.** The current dual system — Laravel's inert flow plus QuickAdminPanel's unsent notification — is deleted, along with `VerificationMiddleware` |
| Mobile | OTP, 6 digits, 10-minute TTL, 5 per hour, **hashed at rest**, single-use |
| **TOTP** | RFC 6238, mandatory for every staff role, optional for candidates. Recovery codes hashed. *(`MEMORY.md` mandates it; `PROGRESS.md` claims it; grep for `totp\|two_factor\|2fa` across the codebase returns **zero hits**)* |
| Session | Regenerate on login, invalidate on logout, 2-hour idle timeout for staff, absolute 12-hour cap |
| Impersonation | One-time expiring token, invalidates the existing session, records the actor's IP, **always audited**, never available to `super_admin` silently |
| Password reset | Against **`password_reset_tokens`** — the table `config/auth.php` actually expects |

---

## 5. Uploads

The current trait accepts any file type. Replacement:

| Control | Rule |
|---|---|
| Extension allow-list | `pdf, jpg, jpeg, png` only |
| **MIME sniffing** | Server-side content inspection. **Never trust the client** — the current code checks only if the client volunteers `width`/`height`/`size` params |
| Size | Photo/signature/thumb 10–100 KB; documents ≤ 500 KB, **≤ 2 MB where legibility requires** — CU-Chayan's 500 KB cap forcing illegible scans is a documented complaint we are choosing not to reproduce |
| Image specs | Photo 350×450 px ratio 7:9; signature and thumb 300×150 px ratio 6:3, validated server-side |
| Virus scan | ClamAV before the file leaves quarantine. A failed scan quarantines and alerts |
| Storage | **Outside the web root**, private disk, served only through a signed, authorised, **audited** route |
| Filename | Never the client's. UUID + validated extension |
| PDF | Reject embedded JavaScript and embedded files |

**Every document read emits a `document.accessed` audit event** carrying actor, IP and document id.

---

## 6. Application security

| Area | Control |
|---|---|
| OWASP A01 Broken access control | §3 — two scopes, base policy, full matrix test |
| A02 Cryptographic failures | Argon2id; TLS 1.2+ only; field encryption per `data-protection.md` |
| A03 Injection | Eloquent bindings throughout. `GlobalSearchController`'s `'App\Models\\'.$model` pattern replaced by an explicit map |
| A04 Insecure design | Threat model reviewed per module in `02-plan/` |
| A05 Misconfiguration | `APP_DEBUG=false` in production (currently `true` with `APP_ENV=local` on a production URL); `.env` out of git and **credentials rotated** — they are in the public history |
| A06 Vulnerable components | `composer audit` + `npm audit` in CI, failing the build. *(`codebase-audit.md` notes "7 security advisories" with no action)* |
| A07 Auth failures | §4 |
| A08 Integrity failures | Hash-chained audit; content-addressed snapshots |
| A09 Logging failures | `snapshot-and-audit.md` §3 |
| A10 SSRF | No user-supplied URL fetching. DOI/CrossRef lookup goes through a **fixed allow-list** of hosts |
| CSRF | Framework default, all state-changing routes |
| Headers | CSP, HSTS, `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options: DENY` |
| Secrets | `gitleaks` in CI, blocking |

---

## 7. Gate performance

`AuthGates` currently runs **2 queries and 162 `Gate::define()` closures on every request**,
uncached.

Replacement: permissions resolved per user, **cached for 15 minutes**, invalidated on any role or
permission change. Policies replace ad-hoc gates for row-level checks — `app/Policies/` does not
exist today, and `AuthServiceProvider::$policies` is empty with a comment claiming auto-discovery
handles it, with nothing to discover.

`is_admin` stops meaning `roles()->where('id', 1)` and becomes a named-role check.

---

## 8. Test strategy

| Test | Asserts |
|---|---|
| **Authorisation matrix** | Every role × resource × action in §3.3, **including negatives** |
| **Ownership isolation** | Candidate A receives **403** on every one of candidate B's resources — profile, documents, application, order, snapshot |
| **OU isolation** | Dean's office of Faculty X receives **403** on every local advertisement, post, application and scrutiny action of Faculty Y |
| Separation of duties | A `rules_admin` cannot activate a ruleset; the same user cannot author **and** verify |
| Registration completes | A new user can register, verify and log in — **the flow that is broken today** |
| Password reset | End-to-end against `password_reset_tokens` |
| Rate limits | Lockout after 5 failures; API 429 after 60/min |
| Upload | Executable rejected; MIME-spoofed file rejected; oversized rejected; virus quarantined |
| Document access | Direct URL without authorisation ⇒ 403, **and an audit event is written** |
| No secrets | `gitleaks` clean; `.env` absent from the tree |
| TOTP | Staff login requires the second factor |

---

## 9. Traceability

| Section | Feeds |
|---|---|
| §3 | M25 RBAC · every policy in `02-plan/` |
| §4 | M03 Registration & Profile |
| §5 | M07 Document Vault |
| §6 | Wave 0 CI · all modules |
| §7 | M25 |

---

## 10. Change log

| Date | Change | By |
|---|---|---|
| 2026-08-27 | Created. Two orthogonal authorisation scopes with a full matrix; 11 roles with author/verifier separation of duties; DR-008 authentication; upload controls; the 9 verified defects this closes. | Implementation team |
