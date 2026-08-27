# Security Architecture

## Threat Model (STRIDE)
*   **Spoofing:** Mitigated via multi-factor authentication (OTP/TOTP) and session timeouts.
*   **Tampering:** Mitigated via hash-chained audit logs on all document uploads and scoring decisions.
*   **Repudiation:** Mitigated via comprehensive, unalterable system logs tracking `user_id` and IP.
*   **Information Disclosure:** Mitigated by strict RBAC, explicit API policies, and encryption at rest for PII.
*   **Denial of Service:** Mitigated by rate limiting (Throttle middleware) on authentication, OTP generation, and heavy endpoints.
*   **Elevation of Privilege:** Mitigated by mapping all actions to Laravel Policies; default deny structure.

## Data Protection & Compliance
*   **DPDP Act 2023:** Data retention policies enforced. Explicit consent captured during registration.
*   **Encryption:** Sensitive attachments (e.g. Caste certificates) are stored privately and delivered via short-lived signed URLs.
*   **OWASP Top 10:** Input sanitized, CSRF enabled, strict CSP headers.
