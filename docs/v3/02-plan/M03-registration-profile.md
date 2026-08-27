# M03 — Registration & Profile (Authentication)

**Wave:** 1 · **Scope:** v1
**Depends on:** DR-008 · M00

## 1. Purpose and statutory basis

One account per person, reused across every application — *"apply once, reuse everywhere"*
(`MODULES.md` #3). WCAG 2.2 **3.3.7 Redundant entry** is literally this: never ask twice.

Statutory context: CRR Rule 11 III(a)–(b) requires applications *"in the prescribed format
(Online/Offline)"*; the portal is the online format, so account integrity is a precondition of a
valid application.

## 2. Data

```
users            id · username (NULL, UNIQUE where present — employee ID, staff only)
                 email UNIQUE · email_verified_at · password
                 status enum(active, suspended, locked) · must_change_password
                 last_login_at · soft deletes
profiles         see ../01-design/domain/domain-model.md §6
otp_codes        user_id · purpose enum(mobile_verify, login) · code_hash
                 expires_at · consumed_at · attempts
two_factor_secrets  user_id · secret (encrypted) · confirmed_at
two_factor_recovery_codes  user_id · code_hash · used_at
password_reset_tokens  email · token · created_at
consent_records  user_id · notice_version · purposes json · ip · recorded_at
```

**Indexes:** `users.email` unique · `users.username` unique · `otp_codes(user_id, purpose,
expires_at)`.

Aadhaar is **encrypted** with a blind index for duplicate detection
(`../01-design/security/data-protection.md` §2).

## 3. Domain services

```
App\Domain\Identity\CredentialResolver::resolve(string $login): string   // 'email' | 'username'
App\Domain\Identity\RegisterCandidate::handle(RegisterData): User
App\Domain\Identity\IssueOtp::handle(User, OtpPurpose): void
App\Domain\Identity\VerifyOtp::handle(User, string $code): bool
App\Domain\Identity\EnrolTotp / ConfirmTotp / VerifyTotp
```

**Invariants.** A candidate's `username` is always `NULL`. A staff `username` never contains `@`.
OTP codes are stored **hashed**, single-use, and rate-limited. TOTP is **mandatory for every staff
role**.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET/POST | `/register` | `register` | `guest`, `throttle:6,1` | — |
| GET/POST | `/login` | `login` | `guest`, `throttle:6,1` | — |
| POST | `/logout` | `logout` | `auth` | — |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | `auth`, `signed`, `throttle:6,1` | — |
| POST | `/email/verification-notification` | `verification.send` | `auth`, `throttle:6,1` | — |
| GET/POST | `/forgot-password`, `/reset-password` | `password.*` | `guest`, `throttle:6,1` | — |
| GET/PATCH | `/profile` | `profile.*` | `auth`, `verified` | `ProfilePolicy@update` |
| POST | `/profile/mobile/otp` · `/verify` | `profile.mobile.*` | `auth`, `throttle:5,60` | own |
| GET/POST/DELETE | `/settings/two-factor` | `two-factor.*` | `auth`, `password.confirm` | own |
| GET | `/profile/export` | `profile.export` | `auth`, `throttle:3,60` | own |

## 5. Validation

| Field | Rules | Message |
|---|---|---|
| `name` | required, string, max:191 | Enter your full name. |
| `email` | required, email:rfc,dns, max:191, unique:users | That email is already registered. Sign in instead. |
| `password` | required, confirmed, `Password::min(12)->mixedCase()->numbers()->symbols()->uncompromised()` | Use at least 12 characters with upper and lower case, a number and a symbol. |
| `login` | required, string | Enter your email address or employee ID. |
| `username` (staff) | nullable, unique:users, `regex:/^[A-Za-z0-9._\-]+$/`, **not_regex:`/@/`** | An employee ID cannot contain @. |
| `mobile` | required, `regex:/^[6-9]\d{9}$/` | Enter a 10-digit Indian mobile number. |
| `dob` | required, date, `before:today`, `after:1940-01-01` | Enter a valid date of birth. |
| `aadhaar_no` | nullable, `digits:12`, **Verhoeff checksum rule**, unique via blind index | That Aadhaar number is not valid. |
| `disability_percent` | `required_if:is_pwd,true`, integer, between:40,100 | A benchmark disability is 40% or more. |
| `otp` | required, digits:6 | Enter the 6-digit code we sent. |

**Cross-field.** `disability_type_id` required when `is_pwd`. `spouse_name` required when
`marital_status` is married. `esm_discharge_date` required when `is_ex_serviceman`.

## 6. Authorisation

`ProfilePolicy` — **ownership scope only**. `view`, `update`, `export` all require
`$user->id === $profile->user_id`. Staff read access to a candidate profile comes through
`ApplicationPolicy`, never through `ProfilePolicy`.

**This is defect #1 in `../01-design/security/security-model.md` §2**: today the `frontend.` route
group has no `auth` middleware at all and the seeder grants every candidate `profile_edit` on every
row.

## 7. UI

Sign-in per `../01-design/ux/screens.md` §1 — split pane, one identifier field, generic errors.
Profile uses the **spine** (`../01-design/ux/design-system.md` §4.2), 11 sections, **no sequential
gating**.

## 8. Worked example

1. Aisha registers with `aisha.khan@gmail.com`. `username` is `NULL`. A `consent_record` is written
   with the notice version. A verification email is sent.
2. She clicks the signed link → `email_verified_at` set → she can sign in. **This is the flow that
   is broken today**: `VerificationMiddleware` logs out any user with `verified = 0`, nothing ever
   sets it to 1, and `VerifyUserNotification` is imported but never sent.
3. She adds her mobile → OTP issued (hashed, 10-minute TTL) → verified.
4. A Deputy Registrar signs in as `EMP04821` → `CredentialResolver` returns `username` → matched.
   TOTP is required and prompted.
5. The same officer signs in as `dyregistrar@amu.ac.in` → resolver returns `email` → **same user
   row**. One form, no branch.

## 9. Acceptance criteria

| ID | Given / When / Then |
|---|---|
| M03-R01 | Given a new registration, when the user follows the verification link, then they can sign in — **and are not logged out** |
| M03-R02 | Given an email in the login field, when submitted, then the `email` column is matched |
| M03-R03 | Given an employee ID in the login field, when submitted, then the `username` column is matched |
| M03-R04 | Given a staff account, when creating it with an `@` in the employee ID, then validation fails |
| M03-R05 | Given 5 failed logins, when a 6th is attempted, then it is throttled and the retry time is stated |
| M03-R06 | Given a forgotten password, when reset is requested, then the token is written to **`password_reset_tokens`** and the reset completes |
| M03-R07 | Given candidate A, when requesting candidate B's profile, then **403** |
| M03-R08 | Given a staff role, when signing in without TOTP, then access is refused |
| M03-R09 | Given an OTP, when reused, then it is rejected |
| M03-R10 | Given a registration, when it completes, then a `consent_record` exists with the notice version |
| M03-R11 | Given a profile export, when generated, then it contains every field the dashboard shows |
| M03-R12 | Given a duplicate Aadhaar, when submitted, then it is detected **without decrypting** any row |

## 10. Test cases

`tests/Feature/Auth/RegistrationTest` — R01, R10 · `CredentialResolverTest` — R02, R03, R04 ·
`ThrottleTest` — R05 · `PasswordResetTest` — R06 · `Authz/ProfileOwnershipTest` — R07 ·
`TwoFactorTest` — R08 · `OtpTest` — R09 · `ProfileExportTest` — R11 ·
`Unit/AadhaarBlindIndexTest` — R12.

Fixtures: `UserFactory` with `candidate()` and `staff()` states.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R06, R09 | `App\Domain\Identity\*`, `App\Http\Controllers\Auth\*` |
| R07 | `App\Policies\ProfilePolicy` |
| R08 | `App\Http\Middleware\RequireTwoFactor` |
| R10–R12 | `App\Domain\Identity\*`, `App\Support\BlindIndex` |
