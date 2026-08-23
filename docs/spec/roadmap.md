# Implementation Roadmap

## Milestone 1: Foundation (Weeks 1-2)
*   Finalize specifications and repository setup.
*   Framework purge (Remove Bootstrap/jQuery, config Tailwind/Alpine).
*   Setup CI/CD pipelines, static analysis, and testing scaffolding.
*   Database schema normalization and initial migrations.

## Milestone 2: The Core Engines (Weeks 3-5)
*   **Scoring Engine:** Build the UGC declarative rules engine and sandbox.
*   **Master Data:** Implement models and seeding for core data (Departments, Posts, Degrees).
*   **Authentication Stack:** Implement OTP/TOTP integrations.

## Milestone 3: Applicant Experience (Weeks 6-8)
*   Build the Applicant Dashboard and Profile builder.
*   Implement the Application Wizard and Document Vault.
*   Payment Gateway integration.

## Milestone 4: Administrative Workbenches (Weeks 9-11)
*   Build the Scrutiny and Committee Workspace modules.
*   Implement Examination module (Admit cards, Centre allocation).
*   Implement Audit logging and reporting modules.

## Milestone 5: Launch & Migration (Week 12)
*   Execute dry-run data migrations.
*   Final UAT with University stakeholders.
*   Cutover to V2 and put V1 into read-only.
