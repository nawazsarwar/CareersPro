# Authentication & Authorization System Developer Documentation

## Overview
The Authentication & Authorization System in the AcademicERP application is designed to manage secure access and role-based permissions across the platform. It handles the different types of login mechanisms such as Username/Password, OTP, OAuth, and Impersonation. From the authorization standpoint, it encompasses an RBAC-based authorization system that uses dynamic Gates to evaluate user permissions across various application endpoints.

---

## 1. Username & Password Login

### Architecture
*   **Controller**: `app/Http/Controllers/Auth/LoginController.php`
*   **Views Directory**: `resources/views/auth/`
*   **Route**: `GET /login` & `POST /login`

### Description
The standard authentication channel, heavily based on the default Laravel Auth system but modified to allow dual-credential login (email or username) and CAPTCHA support.

### Key Fields & Behaviors
*   **`credentials()`**: Dynamically checks if the input is an email (using `FILTER_VALIDATE_EMAIL`) or a username, allowing users to log in with either.
*   **CAPTCHA Check**: Applied dynamically via `CaptchaService` during `validateLogin()` if required.
*   **`revoked` Status**: The system retrieves the user and checks their `revoked` status. If `revoked > 0`, the login is denied.
*   **2FA Redirect**: Handled in the `authenticated()` method. If the user has Two-Factor Authentication (`$user->two_factor`) enabled, a 2FA code is dispatched via `TwoFactorCodeNotification`, and the user is redirected to the `twoFactor.show` route.

---

## 2. OTP (One-Time Password) Login

### Architecture
*   **Controller**: `app/Http/Controllers/Auth/ForgotPasswordController.php`
*   **Controller**: `app/Http/Controllers/Admin/OtpsController.php`
*   **Model**: `app/Models/Otp.php`
*   **Database Table**: `otps`
*   **Views Directory**: `resources/views/auth/otp/`

### Description
Allows users to securely log in using their registered mobile numbers. This flow involves searching for the user, verifying their mobile number if enabled, and dispatching an OTP.

### Flow & Behaviors
*   **Initiation (`searchUser`)**: The user enters their Enrolment No. or Employee ID. The system validates the user and enforces a rate limit (default 5 OTP requests per hour) to prevent abuse.
*   **Mobile Verification (`verifyMobile`)**: Depending on `auth_channels` configuration (`mobile_verification`), the user may be prompted to enter their full 10-digit mobile number, which is validated against the database.
*   **OTP Dispatch (`sendOtpToUser`)**: An OTP is generated and sent via `OtpsController`. The OTP ID is temporarily stored in the session.
*   **Verification (`otpVerify`)**: The system validates the submitted OTP against the `otps` table's `valid_till` and `status` fields. Upon success, the user is directly logged in (bypassing 2FA, as OTP satisfies this requirement) and the session is regenerated to prevent session fixation.

---

## 3. OAuth Login (External API)

### Architecture
*   **Controller**: `app/Http/Controllers/Auth/ChangePasswordController.php`
*   **Model**: `app/Models/OAuth.php`
*   **Database Table**: `oauths`
*   **Route**: `POST /authLogin`

### Description
Enables Single Sign-On (SSO) by authenticating users against an external API endpoint (AMU), bridging external credentials with the internal user management system.

### Key Fields & Behaviors
*   **`OAuthLogin()`**: The user selects the provider and a POST request is sent to `https://api.amu.ac.in/api/v1/auth/login`.
*   **OAuth Record**: Stores the access token, token type, and expiration details upon successful API response.
*   **User Provisioning**: If a corresponding internal user (`username`/`eid`) doesn't exist, a new user is created on-the-fly and assigned the `Personal` type.
*   **Role Syncing**: The `Examination Superintendent` role is automatically attached or synced to the user, who is then authenticated locally.

---

## 4. Impersonation

### Architecture
*   **Controller**: `app/Http/Controllers/Auth/LoginController.php`
*   **Model**: `app/Models/Impersonation.php`
*   **Route**: `GET /impersonate/{token}`

### Description
Allows administrators to temporarily log in as another user for troubleshooting and support purposes without requiring their credentials.

### Flow & Behaviors
*   **Token Validation**: The URL token is validated against the `Impersonation` model to ensure it hasn't expired.
*   **Session Invalidation**: Any existing authenticated session is completely invalidated.
*   **One-Time Use**: The token is immediately marked as used, recording the IP address of the administrator.
*   **Login**: Authenticates the target user via `Auth::loginUsingId()`.

---

## 5. Password Management & Two-Factor Authentication

### Architecture
*   **Controllers**: `app/Http/Controllers/Auth/ResetPasswordController.php`, `app/Http/Controllers/Auth/ChangePasswordController.php`
*   **Middleware**: `app/Http/Middleware/TwoFactorMiddleware.php`

### Description
Handles password updates for both unauthenticated and authenticated users, alongside enforcing global Two-Factor Authentication policies.

### Key Behaviors
*   **Password Reset**: Handled by the standard Laravel `ResetsPasswords` trait (`ResetPasswordController`), facilitating email-based recovery links.
*   **Change Password**: Authenticated users can modify their passwords and manage their profiles via `ChangePasswordController`.
*   **Toggle 2FA**: Users can independently toggle their Two-Factor Authentication preference (`toggleTwoFactor`).
*   **Two-Factor Enforcement (`TwoFactorMiddleware`)**: Intercepts every request to check for a pending `two_factor_code`. If expired, logs the user out. If valid, redirects exclusively to `twoFactor.show` until verified.

---

## 6. Role-Based Access Control (RBAC) & Gates

### Architecture
*   **Middleware**: `app/Http/Middleware/AuthGates.php`
*   **Provider**: `app/Providers/AppServiceProvider.php`
*   **Models**: `app/Models/Role.php`, `app/Models/Permission.php`

### Description
A robust authorization system that dynamically evaluates user privileges against specific route and action gates, ensuring users can only access permitted resources.

### Flow & Implementations
*   **Dynamic Gates (`AuthGates.php`)**: On every request, the middleware fetches all roles and their associated permissions. It creates an array mapping permission names to arrays of authorized Role IDs. It then uses `Gate::define()` to verify if the authenticated user possesses any of the required Role IDs for a given permission (e.g., `abort_if(Gate::denies('profile_password_edit'), 403);`).
*   **Static Gates**: Specific complex rules are defined in `AppServiceProvider`. For instance, the `viewLogViewer` gate explicitly evaluates `$user->roles->contains('name', 'Admin')`.
*   **Polymorphic Content Categorization**: Managed via `Relation::morphMap` in `AppServiceProvider`, mapping string identifiers (e.g., `programme_level`) to Models, which prevents hardcoding class names in the database for role-based content access.
