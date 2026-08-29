# M03 — Registration & Profile (Authentication)

**Wave:** 1 · **Scope:** v1
**Depends on:** DR-008 · DR-022 · DR-023 · DR-024 · M00
**Conforms to:** [`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) — Laravel conventions · Admin/Frontend namespaces · Form Requests strictly · Pest · Larastan level 6

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
                 preferred_login_channel enum(password, otp) NULL   -- NULL = class default
                 last_login_at · soft deletes
profiles         see ../01-design/domain/domain-model.md §6
otp_codes        user_id · purpose enum(mobile_verify, login, two_factor)
                 channel enum(sms, email) · code_hash · destination_hash
                 ip · expires_at · consumed_at · attempts · created_at
two_factor_methods  user_id · type enum(totp, sms, email)
                 secret (encrypted, NULL for sms and email) · confirmed_at
                 is_default bool · last_used_at
two_factor_recovery_codes  user_id · code_hash · used_at
password_reset_tokens  email · token · created_at
consent_records  user_id · notice_version · purposes json · ip · recorded_at
```

**Indexes:** `users.email` unique · `users.username` unique · `otp_codes(user_id, purpose,
expires_at)` · `otp_codes(destination_hash, created_at)` · `two_factor_methods(user_id, type)`
unique.

`two_factor_methods` replaces the TOTP-only `two_factor_secrets` table. One row per enrolled method,
so a user may hold TOTP and SMS at once; `secret` is populated for `totp` only.

Aadhaar is **encrypted** with a blind index for duplicate detection
(`../01-design/security/data-protection.md` §2). `profiles.mobile` is likewise S2 and encrypted, so
`otp_codes.destination_hash` is a **blind index over the delivery target** — it lets the hourly cap
be keyed on the destination without decrypting a single row.

**No uniqueness constraint on `profiles.mobile`** (DR-023). A shared family handset is legitimate.
Accounts sharing a mobile are surfaced in the M23 data-quality report, not blocked.

## 3. Domain services

```
App\Domain\Identity\CredentialResolver::resolve(string $login): string   // 'email' | 'username'
App\Domain\Identity\RegisterCandidate::handle(RegisterData): User
App\Domain\Identity\ResolveLoginChannel::for(User): LoginChannel
App\Domain\Identity\StartOtpLogin::handle(string $login): OtpLoginTicket
App\Domain\Identity\CompleteOtpLogin::handle(OtpLoginTicket, string $code): User
App\Domain\Identity\IssueOtp::handle(User, OtpPurpose, OtpChannel): OtpIssueResult
App\Domain\Identity\VerifyOtp::handle(User, OtpPurpose, string $code): bool
App\Domain\Identity\TwoFactorPolicy::requiredFor(User): bool
App\Domain\Identity\SecondFactor\ResolveRequiredFactor::for(User, AuthFactor $used): ?AuthFactor
App\Domain\Identity\SecondFactor\ChallengeSecondFactor::handle(User, AuthFactor): void
App\Domain\Identity\SecondFactor\VerifySecondFactor::handle(User, string $code): bool
App\Domain\Identity\SecondFactor\Totp\{EnrolTotp, ConfirmTotp, VerifyTotp}
App\Domain\Notification\Sms\SendSms::handle(string $mobile, string $body): SmsResult
App\Domain\Notification\Sms\SmsGateway                                   // contract, DR-024
```

Enums in `app/Enums/`: `LoginChannel`, `OtpPurpose`, `OtpChannel`, `AuthFactor`.

**Invariants.** A candidate's `username` is always `NULL`. A staff `username` never contains `@`.
OTP codes are stored **hashed**, single-use, and rate-limited.

**OTP login never enumerates an account.** The response to *Send me a code instead* is identical for
an unknown identifier, a known one with a verified mobile, and a known one without. The messages in
§5 are shown only once the identifier has established a pending login, never on the first response.

**An OTP is bound to its purpose.** `purpose` is part of the lookup, so a `login` code can never
satisfy a `two_factor` challenge, nor the reverse.

**An OTP counts as the second factor after a password login; never after an OTP login** (DR-023).
`ResolveRequiredFactor` receives the factor already used and excludes its channel from what it
returns.

**A failed SMS dispatch fails closed.** No session, no partial login, no deferred verification. The
user is returned to the password path and `auth.otp.failed` is audited.

**TOTP crypto is not hand-written** (DR-022). `pragmarx/google2fa` and `bacon/bacon-qr-code` are
reached only from `App\Domain\Identity\SecondFactor\Totp\*`; an architecture test asserts they
appear nowhere else.

## 4. Routes and controllers

| Verb | URI | Name | Middleware | Policy |
|---|---|---|---|---|
| GET/POST | `/register` | `register` | `guest`, `throttle:6,1` | — |
| GET/POST | `/login` | `login` | `guest`, `throttle:6,1` | — |
| GET/POST | `/login/otp` | `login.otp.request` | `guest`, `throttle:6,1` | — |
| POST | `/login/otp/resend` | `login.otp.resend` | `guest`, `throttle:3,60` | — |
| GET/POST | `/login/otp/verify` | `login.otp.verify` | `guest`, `throttle:6,1` | — |
| GET/POST | `/two-factor/challenge` | `two-factor.challenge` | `auth.pending`, `throttle:6,1` | — |
| POST | `/two-factor/challenge/resend` | `two-factor.challenge.resend` | `auth.pending`, `throttle:3,60` | — |
| POST | `/logout` | `logout` | `auth` | — |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | `auth`, `signed`, `throttle:6,1` | — |
| POST | `/email/verification-notification` | `verification.send` | `auth`, `throttle:6,1` | — |
| GET/POST | `/forgot-password`, `/reset-password` | `password.*` | `guest`, `throttle:6,1` | — |
| GET/PATCH | `/profile` | `profile.*` | `auth`, `verified` | `ProfilePolicy@update` |
| POST | `/profile/mobile/otp` · `/verify` | `profile.mobile.*` | `auth`, `throttle:5,60` | own |
| GET | `/settings/two-factor` | `two-factor.index` | `auth`, `password.confirm` | own |
| POST/DELETE | `/settings/two-factor/{type}` | `two-factor.store` · `.destroy` | `auth`, `password.confirm` | own |
| POST | `/settings/two-factor/{type}/confirm` | `two-factor.confirm` | `auth`, `password.confirm` | own |
| GET | `/profile/export` | `profile.export` | `auth`, `throttle:3,60` | own |

`auth.pending` is a middleware for the half-authenticated session between the first factor and the
challenge: the user is identified but not yet logged in, so `auth` would reject them and `guest`
would admit anyone.

**Middleware alias.** The second-factor gate registers as **`two-factor`**, matching
[`../01-design/engineering-standards.md`](../01-design/engineering-standards.md) §3. The alias `2fa`
used in earlier drafts of the M08, M18, M25 and M26 route tables is corrected to it.

`password.email` additionally carries the named limiter **`password-reset`** — 5 per hour per
`email|ip` — defined alongside `RateLimiter::for('api')`. Laravel's password broker already throttles
the same address to one request per 60 seconds (`config/auth.php`); the broker stops rapid repeats,
the named limiter stops slow enumeration across an hour.

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
| `factor` | required, `in:totp,sms,email` · **`email` refused for any user holding a staff role** | That method is not available for your account. |

**Cross-field.** `disability_type_id` required when `is_pwd`. `spouse_name` required when
`marital_status` is married. `esm_discharge_date` required when `is_ex_serviceman`.

**OTP login messages.** These are shown on the pending-login screen, after the identifier has been
established — never in response to the first submit, which would enumerate accounts. Exact strings:

| Condition | Message |
|---|---|
| No mobile on the profile | This account has no verified mobile number, so we cannot send a code. Sign in with your password, then add and verify a mobile number in your profile. |
| Mobile present, unverified | The mobile number on this account has not been verified yet. Sign in with your password to verify it. |
| Resend before the cooldown | You can request another code in 2 minutes 14 seconds. |
| Code expired | That code has expired. Request a new one. |
| Hourly cap reached | Too many codes requested. Try again after 04:12 pm. |
| Gateway unavailable | We could not send a code just now. Sign in with your password, or try again in a few minutes. |
| Wrong code | That code is not correct. You have 2 attempts left. |

The retry times are **stated, never "try later"** — the same rule the lockout notice already follows
(`../01-design/ux/screens.md` §1).

## 6. Authorisation

`ProfilePolicy` — **ownership scope only**. `view`, `update`, `export` all require
`$user->id === $profile->user_id`. Staff read access to a candidate profile comes through
`ApplicationPolicy`, never through `ProfilePolicy`.

**This is defect #1 in `../01-design/security/security-model.md` §2**: today the `frontend.` route
group has no `auth` middleware at all and the seeder grants every candidate `profile_edit` on every
row.

## 7. UI

Sign-in per `../01-design/ux/screens.md` §1 — split pane, one identifier field, generic errors.
The OTP path is a **secondary submit on the same card**, *Send me a code instead*, below the primary
button. Not a tab, not a second screen, not a toggle: the password field stays the default and the
code path is one click away.

Code entry, the resend countdown and the second-factor channel picker are **real
`<form method="POST">` elements**, per DR-021. Alpine adds digit auto-advance between the six boxes
and a live countdown; with JavaScript disabled, the six boxes are one `inputmode="numeric"` field and
resend is a plain submit that re-renders the countdown server-side. Nothing in this module requires
JavaScript to complete.

The destination is **masked** on every screen that names it — `•••••• 4821` — so a shoulder-surfer
learns nothing and the user still recognises their own handset.

Second-factor management lives at `/settings/two-factor`: one row per enrolled method, an *Add*
control per available type, and the recovery codes shown **once** at enrolment. Where an
administrator has enforced 2FA for the user's role, the *Remove* control on the last remaining method
is disabled with the reason stated.

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
6. Six months later Aisha returns for a fresh advertisement and does not remember her password. She
   types her email, chooses *Send me a code instead*, and receives a six-digit code on the handset
   she verified in step 3. She signs in. No second factor is enforced for candidates and she has
   enrolled none, so she lands on her dashboard.
7. The Deputy Registrar does the same with `EMP04821`. The code arrives — and because 2FA is enforced
   for his role **and the SMS channel has just served as the first factor**, `ResolveRequiredFactor`
   excludes SMS and he is challenged for TOTP. Two prompts.
8. Next time he signs in with his password instead. Now the SMS code **is** the second factor, so
   there is one prompt, not two. Same account, same methods, different arithmetic.
9. A colleague whose only enrolled method is SMS chooses *Send me a code instead*. There is no
   second factor left to ask for, so OTP login is refused for her and the password path is offered.

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
| M03-R13 | Given a verified mobile, when OTP login is requested, then a code is sent and sign-in completes |
| M03-R14 | Given an account with no mobile, when OTP login is requested, then the stated message is shown, no code is sent, and the response does not reveal whether the account exists |
| M03-R15 | Given an unverified mobile, when OTP login is requested, then it is refused and the verification path is offered |
| M03-R16 | Given a code just sent, when a resend is requested before `OTP_DELAY_MINUTES`, then it is refused and the remaining time is stated |
| M03-R17 | Given a code older than `OTP_VALID_MINUTES`, when submitted, then it is rejected |
| M03-R18 | Given `AUTH_OTP_MAX_PER_HOUR` codes issued to one destination, when another is requested, then it is throttled and the retry time is stated |
| M03-R19 | Given a staff account with a second factor active, when signing in with a password, then the second factor is demanded |
| M03-R20 | Given a staff account with a second factor active, when signing in by OTP, then a second factor is demanded **and the SMS channel just used is not offered** |
| M03-R21 | Given a staff account whose only enrolled method is SMS, when OTP login is attempted, then it is refused and the password path is offered |
| M03-R22 | Given 2FA enforced for a role, when a member of that role has not enrolled, then routes behind `two-factor` are refused until they do |
| M03-R23 | Given 2FA not enforced, when a user removes their last method, then it succeeds and is audited; given it is enforced, then it is refused |
| M03-R24 | Given a mobile supplied for the first time, when its OTP is confirmed, then `mobile_verified_at` is set — and not before |
| M03-R25 | Given a user holding a staff role, when `email` is selected as a second factor, then it is refused |
| M03-R26 | Given an SMS gateway failure, when OTP login is attempted, then no session is created, the password path is offered, and `auth.otp.failed` is audited |
| M03-R27 | Given any SMS dispatch, when it is logged or throws, then the gateway user and password do not appear in the output |
| M03-R28 | Given repeated password-reset requests, when the `password-reset` limiter is exceeded, then they are throttled and the retry time is stated |
| M03-R29 | Given JavaScript disabled, when any OTP or second-factor screen is used, then it completes |

## 10. Test cases

`tests/Feature/Frontend/Auth/RegistrationTest` — R01, R10 · `CredentialResolverTest` — R02, R03, R04 ·
`ThrottleTest` — R05 · `PasswordResetTest` — R06 · `Authz/ProfileOwnershipTest` — R07 ·
`TwoFactorTest` — R08 · `OtpTest` — R09 · `ProfileExportTest` — R11 ·
`Unit/AadhaarBlindIndexTest` — R12 · `Auth/OtpLoginTest` — R13, R14, R15 ·
`Auth/OtpThrottleTest` — R16, R17, R18 · `Auth/TwoFactorChallengeTest` — R19, R20, R21 ·
`Auth/TwoFactorPolicyTest` — R22, R23, R25 · `MobileVerificationTest` — R24 ·
`Auth/SmsGatewayFailureTest` — R26 · `Unit/SmsCredentialRedactionTest` — R27 ·
`Auth/PasswordResetThrottleTest` — R28 · `Auth/NoJavaScriptAuthTest` — R29.

Fixtures: `UserFactory` with `candidate()` and `staff()` states, plus `withVerifiedMobile()`,
`withTotp()` and `withSmsFactor()`. `LogSmsGateway` is bound in the test environment; a failing
double covers R26.

## 11. Traceability

| Requirement | Artefact |
|---|---|
| R01–R06, R09 | `App\Domain\Identity\*`, `App\Http\Controllers\Auth\*` |
| R07 | `App\Policies\ProfilePolicy` |
| R08 | `App\Http\Middleware\RequireTwoFactor` |
| R10–R12 | `App\Domain\Identity\*`, `App\Support\BlindIndex` |
| R13–R18 | `App\Domain\Identity\{StartOtpLogin, CompleteOtpLogin, IssueOtp, VerifyOtp}` |
| R19–R21 | `App\Domain\Identity\SecondFactor\ResolveRequiredFactor`, `App\Http\Middleware\RequirePendingAuth` |
| R22, R23, R25 | `App\Domain\Identity\TwoFactorPolicy`, `App\Http\Middleware\RequireTwoFactor` |
| R24 | `App\Domain\Identity\VerifyOtp` |
| R26, R27 | `App\Domain\Notification\Sms\*` |
| R28 | `App\Providers\AppServiceProvider` — `RateLimiter::for('password-reset')` |
| R29 | `resources/views/auth/*` |
