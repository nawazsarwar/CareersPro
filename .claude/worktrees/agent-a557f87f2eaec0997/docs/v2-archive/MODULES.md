# CareersPro v2 — Module Catalogue

This catalogue defines the mandatory modules and features mapped out from Phase 1 research, forming the target state of the system.

## 1. Public / Applicant Modules
1. **Public Vacancy Listing:** Rich filters (post, department, category, pay level, location, dates), full-text search, and saved searches with alerts.
2. **Advertisement Detail:** View notifications, eligibility summaries, reservation breakdown, and download PDFs.
3. **Registration & Profile:** One-time profile creation ("apply once, reuse everywhere") with OTP/TOTP based authentication.
4. **Editable Academic & Work History:** Applicants can add, edit, and update their academic qualifications and employment history. Locked per application after submission.
5. **Application Wizard:** Resumable, auto-saving application flow with conditional logic based on post type and dynamic eligibility pre-checks.
6. **Publication & Research Claims:** Capture structured metadata (DOI, indexing, authors) mapped to UGC/model rules for auto-scoring.
7. **Document Vault:** Secure upload, virus scanning, image cropping for photo/signature, OCR checks, and self-attestation workflow.
8. **Fee Module:** Category-based fee calculation, exemptions (e.g., PwBD), payment gateway integrations, refunds, and receipts.
9. **Application PDF Generation:** Statutory print format with QR verification and digital signature readiness.
10. **Applicant Dashboard:** Real-time stage tracking, timeline view, deficiency rectification, and action items.
11. **Admit Card & Centre Allotment:** Roll number generation and clash-preventing centre allocation based on applicant preferences.
12. **Examination Delivery Module:** Support for computer-based tests, secure question delivery, answer key publication, and objection handling.
13. **Interview Scheduling:** Slot management, video-interview integration, and travel-allowance claims.
14. **Results & Merit Lists:** Merit, waitlists, offer generation, and joining formalities.
15. **Grievance & Notification Center:** SLA-tracked grievance desk, templated SMS/Email/WhatsApp alerts.

## 2. Administrative / Institutional Modules
16. **Advertisement Builder:** Post creation linked to sanctioned strength and reservation rosters. Handles corrigendums and date extensions.
17. **Reservation & Roster Engine:** Post-based roster registers, backlog tracking, and statutory reporting.
18. **Scrutiny Workbench:** Queue-based, side-by-side verification of applicant claims and documents with deficiency raising logic.
19. **Committee Workspace:** Secure space for Screening/Selection Committees to view applicants, score confidentially, and sign off digitally.
20. **Scoring Engine Admin:** Rules authoring (UGC compliance), versioning, effective dating, and sandbox simulation modes for scoring.
21. **Shortlisting & Cut-offs:** Ranked lists and category-wise lists based on dynamic criteria.
22. **Examination Admin:** Centre master, capacity management, attendance tracking, and incident logging.
23. **Analytics & Reporting:** Funnel tracking, turnaround times, category compliance, and statutory exports.
24. **Master Data Management:** Centralized tables for departments, subjects, degrees, designations, and pay levels.
25. **RBAC & Impersonation:** Fine-grained permissions, delegation, and audited impersonation.
26. **Audit & Traceability:** Immutable hash-chained audit logs for state changes, scoring overrides, and document access.
27. **RTI / Legal Support:** Point-in-time reconstruction of application states and decisions.
28. **System Administration:** Theme manager, feature flag controls, background job monitoring, and backup controls.
29. **Public API / Integration Layer:** Interoperability with ERPs and external portals (e.g., CU-Chayan sync).
