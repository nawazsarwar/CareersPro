# V1 to V2 Migration Plan

## Context
Migrating 5-6 years of legacy data from the AMU Careers v1 portal without data loss.

## Strategy
1.  **Dual Run Strategy:** V1 and V2 will run concurrently during the final validation phase. V1 will be put into read-only mode for historical views.
2.  **ETL Pipeline (Extract, Transform, Load):**
    *   *Extract:* Secure dump from v1 MySQL instances.
    *   *Transform:* Map legacy static form data into the new normalized V2 models (e.g., mapping raw strings to Master Data IDs).
    *   *Load:* Insert into V2 using dedicated Migration Seeders to ensure hooks and audit logs are not falsely triggered.
3.  **Data Integrity Checks:** Pre- and Post-migration hashes must match for critical fields (e.g., Application IDs, PDF URLs).

## Rollback Plan
*   In the event of failure, V2 DNS is rolled back, and V1 read-only mode is lifted. V2 databases are purged and migration scripts are patched.
