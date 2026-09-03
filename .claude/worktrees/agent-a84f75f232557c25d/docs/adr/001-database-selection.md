# ADR 001: Database Selection (MySQL vs Alternatives)

## Context
The user questioned whether MySQL is the appropriate database choice given the complexity of the fields and nested structure of the data (especially within academic qualifications and research publications).

## Options Considered
1.  **MySQL / PostgreSQL (Relational):** Strict schema enforcement, ACID compliance, excellent support for complex joins (e.g., filtering posts by department, roster, category, and eligibility).
2.  **MongoDB / Document Store (NoSQL):** Excellent for highly nested, schemaless data (like dynamic API scoring parameters or varied publication metadata).
3.  **Hybrid Approach (MySQL + JSON Columns):** Using MySQL for core relational entities but leveraging native JSON columns for dynamic, deeply nested payloads.

## Decision
We will proceed with **MySQL (Relational)** as the primary database, utilizing a **Hybrid Approach (MySQL + JSON Columns)** for specific dynamic modules.

## Rationale
*   **Statutory Rigor:** Recruitment, reservation rosters, and financial transactions require absolute data integrity, foreign key constraints, and ACID compliance. A purely relational model is non-negotiable for these aspects.
*   **Auditability:** Reconstructing historical application states for RTI/legal challenges is significantly more reliable with normalized, versioned relational tables.
*   **Handling Nesting:** For modules with high variance and nesting (e.g., the declarative UGC Rulesets, or dynamic extra fields imported from the CU Chayan benchmark), we will utilize MySQL's native `JSON` column types. This allows us to store arbitrary, nested metadata without sacrificing the core relational structure.
