# API Specification

The API layer is built on RESTful principles and is documented via OpenAPI 3.1. It exposes endpoints for mobile clients, ERP integrations, and cross-university interoperability (e.g., CU-Chayan data syncs).

## Core Scopes
*   `applicant:*` - Read/Write profile and application data for the authenticated user.
*   `admin:read` - Read-only access to anonymized aggregates and reporting.
*   `admin:write` - Full administrative capability (gated heavily by IP/MFA).
*   `integration:sync` - Dedicated scope for CU-Chayan or internal University ERP data sync.

## Authentication
*   External integrations utilize Laravel Sanctum API tokens with strict expiration policies.
*   Internal calls utilize session cookies with CSRF validation.

## Key Endpoints
*   `GET /api/v2/vacancies` - Lists active advertisements.
*   `POST /api/v2/applications` - Submit an application (requires `applicant:write`).
*   `GET /api/v2/applications/{uuid}/status` - Check state machine status.
*   `POST /api/v2/integrations/cu-chayan/push` - Push shortlisted candidate payloads (requires `integration:sync`).
