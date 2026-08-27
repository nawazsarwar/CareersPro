# Software Requirements Specification (SRS)

## 1. Introduction
CareersPro v2 is an end-to-end recruitment automation engine for universities and HEIs, built to handle teaching, academic non-teaching, and administrative staff recruitments.

## 2. Functional Requirements

### 2.1 Public & Applicant Facing
*   **REQ-APP-01:** The system shall list public vacancies with rich filters (category, post, department).
*   **REQ-APP-02:** The system shall allow applicants to register once and maintain a reusable profile.
*   **REQ-APP-03:** The system shall allow applicants to add and edit academic and employment histories prior to application submission.
*   **REQ-APP-04:** The system shall evaluate basic eligibility (age, qualifications) dynamically based on post requirements during the application wizard.
*   **REQ-APP-05:** The system shall compute fee exemptions dynamically (e.g., PwBD, SC/ST) and integrate with a payment gateway.
*   **REQ-APP-06:** The system shall generate a statutory PDF with a QR code upon successful application.
*   **REQ-APP-07:** The system shall provide an applicant dashboard tracking the application state and any scrutiny deficiencies.

### 2.2 Administrative & Institutional
*   **REQ-ADM-01:** The system shall allow admins to create advertisements linked to sanctioned strength and roster rules.
*   **REQ-ADM-02:** The system shall provide a scrutiny workbench for experts to perform side-by-side verification of claims vs documents.
*   **REQ-ADM-03:** The system shall feature a dynamic Scoring Engine that evaluates research/academic APIs based on versioned UGC rules.
*   **REQ-ADM-04:** The system shall support examination administration (roll numbers, admit cards, centres).
*   **REQ-ADM-05:** The system shall maintain an immutable audit log of all scoring adjustments and overrides.

## 3. Non-Functional Requirements
*   **NFR-SEC-01:** System shall encrypt sensitive PII and documents at rest.
*   **NFR-ACC-01:** System shall conform to WCAG 2.2 AA and GIGW standards.
*   **NFR-PERF-01:** System shall respond to general queries in < 500ms and support N+1 query optimized structures.
*   **NFR-ARCH-01:** The application will be server-rendered using Laravel Blade, Tailwind CSS 4, and Alpine.js.

### 2.3 Strict Implementation Mandates (UI & Logic)
*   **REQ-MAND-01:** The UI must strictly utilize Tailwind CSS 4 and Alpine.js. Bootstrap, jQuery, and heavy SPA frameworks are explicitly prohibited.
*   **REQ-MAND-02:** All tables (e.g., DataTables equivalents) must be rebuilt natively using server-side pagination (Blade) and Alpine.js for interactivity (sorting/filtering).
*   **REQ-MAND-03:** The Scoring Engine must fully account for API calculations including granular research parameters (UGC-CARE status, impact factors, PI roles, PhD regulation compliance).
