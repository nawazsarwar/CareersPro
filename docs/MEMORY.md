# CareersPro v2 — Project Memory & Invariants

This document captures the non-negotiable domain constraints, terminology, and key technical decisions for the CareersPro v2 implementation. **Every agent must read this file before acting.**

## 1. Terminology Glossary
*   **CU-Chayan:** The UGC unified recruitment portal benchmark.
*   **AMU (Aligarh Muslim University):** The host institution for the v1 portal.
*   **UGC (University Grants Commission):** The governing body dictating teaching staff recruitment regulations.
*   **API (Academic Performance Indicator):** The scoring system for shortlisting candidates based on UGC guidelines.
*   **Reservation Roster:** Statutory system mapping posts to specific categories (SC/ST/OBC/EWS/PwBD).
*   **Scrutiny/Screening Committee:** The body of experts validating and scoring candidate claims.

## 2. Regulatory & Domain Invariants
*   **Traceability:** Every requirement maps to code and test (`docs/traceability.csv`).
*   **No Deletion:** Real applications and documents are never hard-deleted. Soft deletes and immutability must be preserved.
*   **Auditability:** Every document access, score override, or state transition must be logged with an immutable audit entry.
*   **Time-Traveling Data:** A system administrator must be able to view an application *exactly* as it was scored on any specific historical date.
*   **Configurability First:** If a regulation is ambiguous (e.g. 2018 vs Draft 2025 UGC rules), build it as a configurable toggle rather than hardcoded logic.
*   **UGC Rules Engine:** The API calculation must be a versioned, declarative rules engine.

## 3. Technical & Architectural Mandate
*   **Stack:** Laravel 13 (currently running 11.56.0 compat layer), Tailwind CSS 4, Alpine.js, Blade.
*   **Framework Purge:** No Bootstrap, no jQuery, no SPAs.
*   **Accessibility:** Strict WCAG 2.2 AA and GIGW compliance.
*   **Security:** Encryption at rest for PII/documents, OWASP Top 10 defenses, and RBAC policies.
*   **Authentication:** Username/Password, OTP, and TOTP support are mandatory.

## 4. UI/UX Rules
*   Dark/Light mode support managed via a persistent Theme Manager.
*   Mobile-first forms with proper loading skeletons, disabled states, and clear inline validation.
*   No standard "admin panel" boilerplate look; must feel deliberate, thoughtful, and accessible.
