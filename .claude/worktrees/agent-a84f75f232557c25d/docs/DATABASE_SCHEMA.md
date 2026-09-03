# Comprehensive Database Schema & Migration Reference Guide

> **Generated at:** 2026-08-22 17:41:24 UTC
> **Primary Application Context:** AMU Careers Online Employment Application Portal
> **Development Database (`betacareers_db`):** 37 Tables | Connected via `DB_*` (Read/Write)
> **Production Database (`careers_db`):** 43 Tables | Connected via `CAREERS_DB_*` (Read-Only)

---

## Table of Contents
1. [Executive Summary](#1-executive-summary)
2. [Database Connection Details](#2-database-connection-details)
3. [Architecture & Schema Comparison Analysis](#3-architecture--schema-comparison-analysis)
   - [Key Conceptual Evolutions (Prod -> Beta Dev)](#key-conceptual-evolutions-prod---beta-dev)
   - [Entity & Table Mapping Matrix](#entity--table-mapping-matrix)
   - [Production-Only Tables Breakdown](#production-only-tables-breakdown)
   - [Development-Only Tables Breakdown](#development-only-tables-breakdown)
4. [Production Database (`careers_db`) - Complete Schema (43 Tables)](#4-production-database-careers_db---complete-schema-43-tables)
   - [`careers_db.academic_qualifications`](#prod-table-academic-qualifications)
   - [`careers_db.additionaldetails`](#prod-table-additionaldetails)
   - [`careers_db.addresses`](#prod-table-addresses)
   - [`careers_db.advertisements`](#prod-table-advertisements)
   - [`careers_db.applicationforms`](#prod-table-applicationforms)
   - [`careers_db.applicationforms_20102025_1856`](#prod-table-applicationforms-20102025-1856)
   - [`careers_db.applicationforms_24072026_0300`](#prod-table-applicationforms-24072026-0300)
   - [`careers_db.applicationforms_backup_27012025_1709`](#prod-table-applicationforms-backup-27012025-1709)
   - [`careers_db.boards`](#prod-table-boards)
   - [`careers_db.courses`](#prod-table-courses)
   - [`careers_db.eligibility_tests`](#prod-table-eligibility-tests)
   - [`careers_db.failed_jobs`](#prod-table-failed-jobs)
   - [`careers_db.foreignvisits`](#prod-table-foreignvisits)
   - [`careers_db.institutionsattended`](#prod-table-institutionsattended)
   - [`careers_db.jobs`](#prod-table-jobs)
   - [`careers_db.media`](#prod-table-media)
   - [`careers_db.migrations`](#prod-table-migrations)
   - [`careers_db.module_posttype`](#prod-table-module-posttype)
   - [`careers_db.modules`](#prod-table-modules)
   - [`careers_db.orders`](#prod-table-orders)
   - [`careers_db.otherdetails`](#prod-table-otherdetails)
   - [`careers_db.otps`](#prod-table-otps)
   - [`careers_db.password_resets`](#prod-table-password-resets)
   - [`careers_db.pincodes`](#prod-table-pincodes)
   - [`careers_db.posts`](#prod-table-posts)
   - [`careers_db.posttypes`](#prod-table-posttypes)
   - [`careers_db.profile_academicqualifications`](#prod-table-profile-academicqualifications)
   - [`careers_db.profile_address`](#prod-table-profile-address)
   - [`careers_db.profile_eligibilitytest`](#prod-table-profile-eligibilitytest)
   - [`careers_db.profile_teachingresearch_experiences`](#prod-table-profile-teachingresearch-experiences)
   - [`careers_db.profile_workexperiences`](#prod-table-profile-workexperiences)
   - [`careers_db.profiles`](#prod-table-profiles)
   - [`careers_db.receivables`](#prod-table-receivables)
   - [`careers_db.referees`](#prod-table-referees)
   - [`careers_db.scholarshipfellowship`](#prod-table-scholarshipfellowship)
   - [`careers_db.scrutiny_reports_all_it_posts`](#prod-table-scrutiny-reports-all-it-posts)
   - [`careers_db.services`](#prod-table-services)
   - [`careers_db.sessions`](#prod-table-sessions)
   - [`careers_db.teachingresearch_experiences`](#prod-table-teachingresearch-experiences)
   - [`careers_db.transactions`](#prod-table-transactions)
   - [`careers_db.uploads`](#prod-table-uploads)
   - [`careers_db.users`](#prod-table-users)
   - [`careers_db.work_experiences`](#prod-table-work-experiences)
5. [Development Database (`betacareers_db`) - Complete Schema (37 Tables)](#5-development-database-betacareers_db---complete-schema-37-tables)
   - [`betacareers_db.academic_qualifications`](#dev-table-academic-qualifications)
   - [`betacareers_db.adresses`](#dev-table-adresses)
   - [`betacareers_db.advertisement_types`](#dev-table-advertisement-types)
   - [`betacareers_db.advertisements`](#dev-table-advertisements)
   - [`betacareers_db.application_forms`](#dev-table-application-forms)
   - [`betacareers_db.audit_logs`](#dev-table-audit-logs)
   - [`betacareers_db.boards`](#dev-table-boards)
   - [`betacareers_db.castes`](#dev-table-castes)
   - [`betacareers_db.categories`](#dev-table-categories)
   - [`betacareers_db.countries`](#dev-table-countries)
   - [`betacareers_db.disability_types`](#dev-table-disability-types)
   - [`betacareers_db.eligibility_tests`](#dev-table-eligibility-tests)
   - [`betacareers_db.employment_histories`](#dev-table-employment-histories)
   - [`betacareers_db.faq_categories`](#dev-table-faq-categories)
   - [`betacareers_db.faq_questions`](#dev-table-faq-questions)
   - [`betacareers_db.foreign_visits`](#dev-table-foreign-visits)
   - [`betacareers_db.institutions_attendeds`](#dev-table-institutions-attendeds)
   - [`betacareers_db.marital_statuses`](#dev-table-marital-statuses)
   - [`betacareers_db.media`](#dev-table-media)
   - [`betacareers_db.migrations`](#dev-table-migrations)
   - [`betacareers_db.other_details`](#dev-table-other-details)
   - [`betacareers_db.password_resets`](#dev-table-password-resets)
   - [`betacareers_db.permission_role`](#dev-table-permission-role)
   - [`betacareers_db.permissions`](#dev-table-permissions)
   - [`betacareers_db.photos`](#dev-table-photos)
   - [`betacareers_db.post_types`](#dev-table-post-types)
   - [`betacareers_db.postal_codes`](#dev-table-postal-codes)
   - [`betacareers_db.posts`](#dev-table-posts)
   - [`betacareers_db.profiles`](#dev-table-profiles)
   - [`betacareers_db.provinces`](#dev-table-provinces)
   - [`betacareers_db.qualification_levels`](#dev-table-qualification-levels)
   - [`betacareers_db.referees`](#dev-table-referees)
   - [`betacareers_db.religions`](#dev-table-religions)
   - [`betacareers_db.role_user`](#dev-table-role-user)
   - [`betacareers_db.roles`](#dev-table-roles)
   - [`betacareers_db.traeds`](#dev-table-traeds)
   - [`betacareers_db.users`](#dev-table-users)
6. [Data Migration & Alignment Strategies](#6-data-migration--alignment-strategies)

---

## 1. Executive Summary

This document serves as the authoritative data dictionary and schema cross-reference between:
1. **Production Portal Database (`careers_db`)**: The legacy live production system holding all real-world applicant data, transaction logs, scrutiny reports, and submitted job applications.
2. **Development / Beta Portal Database (`betacareers_db`)**: The modern refactored Laravel architecture adopting standardized naming conventions (`snake_case`), standardized RBAC (Roles & Permissions), normalized master tables (religions, castes, disability types, marital statuses, provinces, countries), and restructured application entities.

---

## 2. Database Connection Details

The application is configured with two active MySQL connection profiles:

| Parameter | Development Connection (`mysql`) | Production Read-Only Connection (`mysql_readonly`) |
| :--- | :--- | :--- |
| **Environment Prefix** | `DB_*` | `CAREERS_DB_*` |
| **Host** | `127.0.0.1` | `127.0.0.1` |
| **Port** | `3306` | `3306` |
| **Database Name** | `betacareers_db` | `careers_db` |
| **Username** | `betacareers_user` | `careers_user_readonly` |
| **Access Level** | **Read / Write (DDL & DML)** | **Strict Read-Only** |
| **Laravel Connection** | `DB::connection('mysql')` (default) | `DB::connection('mysql_readonly')` |

---

## 3. Architecture & Schema Comparison Analysis

### Key Conceptual Evolutions (Prod -> Beta Dev)

1. **Table Naming Normalization**:
   - In `careers_db`, table names were often merged words without delimiters (e.g. `applicationforms`, `otherdetails`, `foreignvisits`, `institutionsattended`, `posttypes`, `pincodes`).
   - In `betacareers_db`, standard Laravel pluralized snake_case convention is adopted (e.g. `application_forms`, `other_details`, `foreign_visits`, `institutions_attendeds`, `post_types`, `postal_codes`).

2. **Master Lookup & Normalization Tables**:
   - In `careers_db`, categories, religions, castes, marital statuses, and disability types were largely stored as raw string literals or loosely coupled codes.
   - In `betacareers_db`, dedicated normalized lookup tables exist: `categories`, `religions`, `castes`, `marital_statuses`, `disability_types`, `provinces`, `countries`, `qualification_levels`, `advertisement_types`, and `traeds`.

3. **Experience & Employment History Consolidation**:
   - In `careers_db`, experience was split between `teachingresearch_experiences` and `work_experiences` along with pivot tables `profile_teachingresearch_experiences` and `profile_workexperiences`.
   - In `betacareers_db`, employment history is consolidated into `employment_histories`.

4. **Authentication & RBAC**:
   - `betacareers_db` integrates full ACL tables: `roles`, `permissions`, `role_user`, `permission_role`, and `audit_logs`.

### Entity & Table Mapping Matrix

| Entity Domain | Production Table (`careers_db`) | Beta Development Table (`betacareers_db`) | Notes / Migration Action |
| :--- | :--- | :--- | :--- |
| **Users / Auth** | `users` | `users` | Core user accounts; check hash types and column extensions. |
| **User Profiles** | `profiles` | `profiles` | Demographic & personal profile info. |
| **Applications** | `applicationforms` | `application_forms` | Main job application form submissions. |
| **Advertisements** | `advertisements` | `advertisements` | Job notifications / recruitment drives. |
| **Job Posts** | `posts` | `posts` | Specific posts under advertisements. |
| **Post Types** | `posttypes` | `post_types` | Academic / Non-Academic / Administrative categorization. |
| **Academic Qualifications** | `academic_qualifications` | `academic_qualifications` | Degrees, high school, PG qualifications. |
| **Boards / Universities** | `boards` | `boards` | Recognized education boards & universities. |
| **Eligibility Tests** | `eligibility_tests` | `eligibility_tests` | NET, SLET, GATE, JRF test records. |
| **Institutions Attended** | `institutionsattended` | `institutions_attendeds` | School / college attendance records. |
| **Foreign Visits** | `foreignvisits` | `foreign_visits` | International conference / study visits. |
| **Referees** | `referees` | `referees` | Candidate professional references. |
| **Other / Misc Details** | `otherdetails` / `additionaldetails` | `other_details` | Co-curricular, awards, disciplinary declarations. |
| **Addresses** | `addresses` / `profile_address` | `adresses` | Candidate permanent and correspondence addresses. |
| **Postal / PIN Codes** | `pincodes` | `postal_codes` | Master PIN code reference database. |
| **Work & Teaching Exp** | `work_experiences`, `teachingresearch_experiences` | `employment_histories` | Consolidated past work/teaching experiences. |
| **Media / Uploads** | `media`, `uploads` | `media`, `photos` | Candidate uploaded documents, signatures, photos. |
| **Roles & Permissions** | *Legacy/Ad-hoc* | `roles`, `permissions`, `role_user`, `permission_role` | Granular permission system in Beta. |
| **System & FAQs** | *None* | `faq_categories`, `faq_questions`, `audit_logs` | Help desk and audit tracking. |
| **Finance / Orders** | `orders`, `transactions`, `receivables`, `services` | *Pending/Staged* | Payment gateway and fee tracking tables. |

---

## 4. Production Database (`careers_db`) - Complete Schema (43 Tables)

### <a id="prod-table-academic-qualifications"></a>Table: `careers_db`.`academic_qualifications`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `135808`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`course`** | `varchar(191)` | NO | `` | *NULL* | `` | Ex: BA(Geography) or BSc(Geography) |
| 4 | **`board`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 5 | **`year`** | `year` | NO | `` | *NULL* | `` |  |
| 6 | **`division`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 7 | **`percentage`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 8 | **`cgpa`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 9 | **`subjects`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 10 | **`title`** | `varchar(191)` | YES | `` | *NULL* | `` | Title of Ph.D and MPhils. |
| 11 | **`document_url`** | `varchar(500)` | YES | `` | *NULL* | `` |  |
| 12 | **`remarks`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 13 | **`added_for`** | `varchar(191)` | NO | `` | *NULL* | `` | Will contain profile_id of the user |
| 14 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `academic_qualifications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ex: BA(Geography) or BSc(Geography)',
  `board` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `division` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentage` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cgpa` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subjects` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title of Ph.D and MPhils.',
  `document_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_for` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Will contain profile_id of the user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135817 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-additionaldetails"></a>Table: `careers_db`.`additionaldetails`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `339504`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `bigint` | YES | `` | *NULL* | `` |  |
| 4 | **`module_uid`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 5 | **`A`** | `varchar(500)` | YES | `` | *NULL* | `` | 1 |
| 6 | **`B`** | `varchar(500)` | YES | `` | *NULL* | `` | 2 |
| 7 | **`C`** | `varchar(500)` | YES | `` | *NULL* | `` | 3 |
| 8 | **`D`** | `varchar(500)` | YES | `` | *NULL* | `` | 4 |
| 9 | **`E`** | `varchar(500)` | YES | `` | *NULL* | `` | 5 |
| 10 | **`F`** | `varchar(500)` | YES | `` | *NULL* | `` | 6 |
| 11 | **`G`** | `varchar(500)` | YES | `` | *NULL* | `` | 7 |
| 12 | **`H`** | `varchar(500)` | YES | `` | *NULL* | `` | 8 |
| 13 | **`I`** | `varchar(500)` | YES | `` | *NULL* | `` | 9 |
| 14 | **`J`** | `varchar(500)` | YES | `` | *NULL* | `` | 10 |
| 15 | **`K`** | `varchar(500)` | YES | `` | *NULL* | `` | 11 |
| 16 | **`L`** | `varchar(500)` | YES | `` | *NULL* | `` | 12 |
| 17 | **`M`** | `varchar(500)` | YES | `` | *NULL* | `` | 13 |
| 18 | **`N`** | `varchar(500)` | YES | `` | *NULL* | `` | 14 |
| 19 | **`O`** | `varchar(500)` | YES | `` | *NULL* | `` | 15 |
| 20 | **`P`** | `varchar(500)` | YES | `` | *NULL* | `` | 16 |
| 21 | **`document_url`** | `varchar(500)` | YES | `` | *NULL* | `` |  |
| 22 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 23 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 24 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 25 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 26 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `additionaldetails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `profile_id` bigint DEFAULT NULL,
  `module_uid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `A` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1',
  `B` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '2',
  `C` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '3',
  `D` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '4',
  `E` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '5',
  `F` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '6',
  `G` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '7',
  `H` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '8',
  `I` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '9',
  `J` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '10',
  `K` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '11',
  `L` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '12',
  `M` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '13',
  `N` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '14',
  `O` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '15',
  `P` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '16',
  `document_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=339512 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-addresses"></a>Table: `careers_db`.`addresses`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `53250`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`house_no`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`street`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 4 | **`landmark`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 5 | **`locality`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 6 | **`city`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 7 | **`district`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 8 | **`state`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 9 | **`pin_code`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 10 | **`country`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 11 | **`added_as`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 12 | **`status`** | `int unsigned` | NO | `` | `1` | `` |  |
| 13 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 14 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`user_id`** | `bigint unsigned` | YES | `` | *NULL* | `` | Temp: For building belongs to relationship |
| 17 | **`profile_id`** | `bigint unsigned` | YES | `` | *NULL* | `` | Temp: For building belongs to relationship |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `house_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landmark` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locality` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_as` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Temp: For building belongs to relationship',
  `profile_id` bigint unsigned DEFAULT NULL COMMENT 'Temp: For building belongs to relationship',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-advertisements"></a>Table: `careers_db`.`advertisements`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `1049`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`slug`** | `mediumtext` | NO | `` | *NULL* | `` |  |
| 4 | **`description`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 5 | **`dated`** | `date` | NO | `` | *NULL* | `` |  |
| 6 | **`type`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 7 | **`advertisement_url`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`status`** | `varchar(50)` | NO | `` | *NULL* | `` |  |
| 9 | **`default_fee`** | `int` | NO | `` | *NULL* | `` |  |
| 10 | **`default_open_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 11 | **`default_reg_end_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 12 | **`default_payment_end_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 13 | **`added_by`** | `int` | NO | `` | *NULL* | `` | User who added the advertisement |
| 14 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `advertisements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `dated` date NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `advertisement_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default_fee` int NOT NULL,
  `default_open_date` datetime DEFAULT NULL,
  `default_reg_end_date` datetime DEFAULT NULL,
  `default_payment_end_date` datetime DEFAULT NULL,
  `added_by` int NOT NULL COMMENT 'User who added the advertisement',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1050 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-applicationforms"></a>Table: `careers_db`.`applicationforms`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `78232`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`roll_no`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 4 | **`random_no`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 5 | **`advertisement_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` | Regenerated from basic_details |
| 6 | **`advertisement_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 7 | **`post_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 8 | **`post_serial_no`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 9 | **`post_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 10 | **`name`** | `varchar(191)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 11 | **`email`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 12 | **`gender`** | `varchar(10)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 13 | **`dob`** | `date` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 14 | **`mobile`** | `varchar(25)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 15 | **`disability`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 16 | **`disability_type`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 17 | **`disability_percent`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 18 | **`aadhaar_no`** | `varchar(20)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 19 | **`category`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 20 | **`caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 21 | **`sub_caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 22 | **`nationality`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 23 | **`permanent_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 24 | **`correspondence_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 25 | **`domicile_district`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 26 | **`domicile_state`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 27 | **`basic_details`** | `longtext` | NO | `` | *NULL* | `` |  |
| 28 | **`additional_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 29 | **`order_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 30 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 31 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 32 | **`review`** | `int` | NO | `` | `1` | `` | review = 1 & submitted = 0 means Application form is under review and has not been submitted yet |
| 33 | **`submitted`** | `int` | NO | `` | `0` | `` | submitted = 1 & review = 0 means user has reviewed and submitted the Application form |
| 34 | **`paid`** | `int` | YES | `` | *NULL* | `` | paid = 1 means Application form with successful payment |
| 35 | **`paid2`** | `int` | YES | `` | *NULL* | `` | Temporary |
| 36 | **`hardcopy_received`** | `int` | NO | `` | `0` | `` | This will set bet set to 1 if hardcopy receipt is received at concerned office |
| 37 | **`hardcopy_received_by_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 38 | **`scrutinized`** | `int` | YES | `` | *NULL* | `` | 1=scrutiny completed, 0=not done |
| 39 | **`scrutiny_updated_by`** | `bigint` | YES | `` | *NULL* | `` | User ID who last updated scrutiny status |
| 40 | **`scrutiny_eligible`** | `int` | YES | `` | *NULL* | `` | 1=eligible, 0=rejected, NULL=pending |
| 41 | **`scrutiny_remark`** | `text` | YES | `` | *NULL* | `` | Reason/note for scrutiny decision |
| 42 | **`scrutiny_updated_at`** | `timestamp` | YES | `` | *NULL* | `` | Timestamp of last scrutiny decision |
| 43 | **`written_test_eligible`** | `int` | YES | `` | *NULL* | `` | 1=eligible, 0=rejected, NULL=pending/NA. Blank if post is interview-only |
| 44 | **`written_test_eligibility_remark`** | `text` | YES | `` | *NULL* | `` | Reason/note for written-test eligibility decision |
| 45 | **`written_test_eligibility_updated_by`** | `int` | YES | `` | *NULL* | `` | User ID who last updated this decision |
| 46 | **`written_test_eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` | Timestamp of last written-test eligibility decision |
| 47 | **`interview_eligible`** | `int` | YES | `` | *NULL* | `` | 1=eligible, 0=rejected, NULL=pending |
| 48 | **`interview_eligibility_remark`** | `text` | YES | `` | *NULL* | `` | Reason/note for interview eligibility decision |
| 49 | **`interview_eligibility_updated_by`** | `bigint` | YES | `` | *NULL* | `` | User ID who last updated this decision |
| 50 | **`interview_eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` | Timestamp of last interview eligibility decision |
| 51 | **`order_uid`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 52 | **`admitcard_downloaded_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 53 | **`interview_letter_downloaded_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 54 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 55 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 56 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 57 | **`centre_name`** | `varchar(150)` | YES | `` | *NULL* | `` | Centre Name for Written Test |
| 58 | **`centre_code`** | `varchar(150)` | YES | `` | *NULL* | `` | Centre Code for Written Test |
| 59 | **`centre_address`** | `varchar(150)` | YES | `` | *NULL* | `` | Centre Address for Written Test |
| 60 | **`centre_city`** | `varchar(150)` | YES | `` | *NULL* | `` | Centre City for Written Test |
| 61 | **`room_no`** | `varchar(50)` | YES | `` | *NULL* | `` | Centre Room No. for Written Test |
| 62 | **`seat_no`** | `varchar(50)` | YES | `` | *NULL* | `` | Centre Seat No. for Written Test |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `advertisement_id` | `advertisement_id` | NO | `BTREE` |
| `hardcopy_received_by_id` | `hardcopy_received_by_id` | NO | `BTREE` |
| `order_id` | `order_id` | NO | `BTREE` |
| `post_id` | `post_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `random_no` | `random_no` | NO | `BTREE` |
| `roll_no` | `roll_no` | NO | `BTREE` |
| `user_id` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `applicationforms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `roll_no` bigint DEFAULT NULL,
  `random_no` bigint DEFAULT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `advertisement_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_id` bigint NOT NULL,
  `post_serial_no` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `dob` date DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `mobile` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_type` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_percent` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `aadhaar_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `caste` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `sub_caste` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `nationality` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `permanent_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `correspondence_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_district` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_state` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `basic_details` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_details` longtext COLLATE utf8mb4_unicode_ci,
  `order_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `review` int NOT NULL DEFAULT '1' COMMENT 'review = 1 & submitted = 0 means Application form is under review and has not been submitted yet',
  `submitted` int NOT NULL DEFAULT '0' COMMENT 'submitted = 1 & review = 0 means user has reviewed and submitted the Application form',
  `paid` int DEFAULT NULL COMMENT 'paid = 1 means Application form with successful payment',
  `paid2` int DEFAULT NULL COMMENT 'Temporary',
  `hardcopy_received` int NOT NULL DEFAULT '0' COMMENT 'This will set bet set to 1 if hardcopy receipt is received at concerned office',
  `hardcopy_received_by_id` bigint DEFAULT NULL,
  `scrutinized` int DEFAULT NULL COMMENT '1=scrutiny completed, 0=not done',
  `scrutiny_updated_by` bigint DEFAULT NULL COMMENT 'User ID who last updated scrutiny status',
  `scrutiny_eligible` int DEFAULT NULL COMMENT '1=eligible, 0=rejected, NULL=pending',
  `scrutiny_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Reason/note for scrutiny decision',
  `scrutiny_updated_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp of last scrutiny decision',
  `written_test_eligible` int DEFAULT NULL COMMENT '1=eligible, 0=rejected, NULL=pending/NA. Blank if post is interview-only',
  `written_test_eligibility_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Reason/note for written-test eligibility decision',
  `written_test_eligibility_updated_by` int DEFAULT NULL COMMENT 'User ID who last updated this decision',
  `written_test_eligibility_updated_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp of last written-test eligibility decision',
  `interview_eligible` int DEFAULT NULL COMMENT '1=eligible, 0=rejected, NULL=pending',
  `interview_eligibility_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Reason/note for interview eligibility decision',
  `interview_eligibility_updated_by` bigint DEFAULT NULL COMMENT 'User ID who last updated this decision',
  `interview_eligibility_updated_at` timestamp NULL DEFAULT NULL COMMENT 'Timestamp of last interview eligibility decision',
  `order_uid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admitcard_downloaded_at` timestamp NULL DEFAULT NULL,
  `interview_letter_downloaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `centre_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre Name for Written Test',
  `centre_code` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre Code for Written Test',
  `centre_address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre Address for Written Test',
  `centre_city` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre City for Written Test',
  `room_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre Room No. for Written Test',
  `seat_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Centre Seat No. for Written Test',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `post_id` (`post_id`),
  KEY `advertisement_id` (`advertisement_id`),
  KEY `roll_no` (`roll_no`),
  KEY `random_no` (`random_no`),
  KEY `hardcopy_received_by_id` (`hardcopy_received_by_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10097904 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-applicationforms-20102025-1856"></a>Table: `careers_db`.`applicationforms_20102025_1856`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `71671`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`advertisement_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` | Regenerated from basic_details |
| 4 | **`advertisement_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 5 | **`post_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 6 | **`post_serial_no`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 7 | **`post_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 8 | **`name`** | `varchar(191)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 9 | **`email`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 10 | **`gender`** | `varchar(10)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 11 | **`dob`** | `date` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 12 | **`mobile`** | `varchar(25)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 13 | **`disability`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 14 | **`disability_type`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 15 | **`disability_percent`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 16 | **`aadhaar_no`** | `varchar(20)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 17 | **`category`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 18 | **`caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 19 | **`sub_caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 20 | **`nationality`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 21 | **`permanent_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 22 | **`correspondence_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 23 | **`domicile_district`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 24 | **`domicile_state`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 25 | **`basic_details`** | `longtext` | NO | `` | *NULL* | `` |  |
| 26 | **`additional_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 27 | **`order_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 28 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 29 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 30 | **`review`** | `int` | NO | `` | `1` | `` |  |
| 31 | **`submitted`** | `int` | NO | `` | `0` | `` | Submitted is the final state where 1 means user has reviewed and paid the application fee. |
| 32 | **`paid`** | `int` | YES | `` | *NULL* | `` | Application with successful payment |
| 33 | **`paid2`** | `int` | YES | `` | *NULL* | `` | Temporary |
| 34 | **`hardcopy_received`** | `int` | NO | `` | `0` | `` | This will set bet set to 1 if hardcopy receipt is received at concerned office |
| 35 | **`scrutinized`** | `int` | YES | `` | *NULL* | `` |  |
| 36 | **`scrutinized_by`** | `int` | YES | `` | *NULL* | `` |  |
| 37 | **`scrutiny_remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 38 | **`eligible`** | `int` | YES | `` | *NULL* | `` |  |
| 39 | **`eligibility_remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 40 | **`eligibility_updated_by`** | `int` | YES | `` | *NULL* | `` |  |
| 41 | **`eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 42 | **`order_uid`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 43 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 44 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 45 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 46 | **`roll_no`** | `bigint` | YES | `` | *NULL* | `` |  |
| 47 | **`random_no`** | `bigint` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `advertisement_id` | `advertisement_id` | NO | `BTREE` |
| `order_id` | `order_id` | NO | `BTREE` |
| `post_id` | `post_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_id` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `applicationforms_20102025_1856` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `advertisement_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_id` bigint NOT NULL,
  `post_serial_no` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `dob` date DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `mobile` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_percent` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `aadhaar_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `sub_caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `nationality` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `permanent_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `correspondence_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_district` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `basic_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `review` int NOT NULL DEFAULT '1',
  `submitted` int NOT NULL DEFAULT '0' COMMENT 'Submitted is the final state where 1 means user has reviewed and paid the application fee.',
  `paid` int DEFAULT NULL COMMENT 'Application with successful payment',
  `paid2` int DEFAULT NULL COMMENT 'Temporary',
  `hardcopy_received` int NOT NULL DEFAULT '0' COMMENT 'This will set bet set to 1 if hardcopy receipt is received at concerned office',
  `scrutinized` int DEFAULT NULL,
  `scrutinized_by` int DEFAULT NULL,
  `scrutiny_remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligible` int DEFAULT NULL,
  `eligibility_remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligibility_updated_by` int DEFAULT NULL,
  `eligibility_updated_at` timestamp NULL DEFAULT NULL,
  `order_uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `roll_no` bigint DEFAULT NULL,
  `random_no` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `post_id` (`post_id`),
  KEY `advertisement_id` (`advertisement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10087216 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-applicationforms-24072026-0300"></a>Table: `careers_db`.`applicationforms_24072026_0300`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `78568`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`roll_no`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 4 | **`random_no`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 5 | **`advertisement_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` | Regenerated from basic_details |
| 6 | **`advertisement_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 7 | **`post_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 8 | **`post_serial_no`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 9 | **`post_title`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 10 | **`name`** | `varchar(191)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 11 | **`email`** | `varchar(255)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 12 | **`gender`** | `varchar(10)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 13 | **`dob`** | `date` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 14 | **`mobile`** | `varchar(25)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 15 | **`disability`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 16 | **`disability_type`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 17 | **`disability_percent`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 18 | **`aadhaar_no`** | `varchar(20)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 19 | **`category`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 20 | **`caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 21 | **`sub_caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 22 | **`nationality`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 23 | **`permanent_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 24 | **`correspondence_address`** | `varchar(1000)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 25 | **`domicile_district`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 26 | **`domicile_state`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 27 | **`basic_details`** | `longtext` | NO | `` | *NULL* | `` |  |
| 28 | **`additional_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 29 | **`order_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 30 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 31 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 32 | **`review`** | `int` | NO | `` | `1` | `` | review = 1 & submitted = 0 means Application form is under review and has not been submitted yet |
| 33 | **`submitted`** | `int` | NO | `` | `0` | `` | submitted = 1 & review = 0 means user has reviewed and submitted the Application form |
| 34 | **`paid`** | `int` | YES | `` | *NULL* | `` | paid = 1 means Application form with successful payment |
| 35 | **`paid2`** | `int` | YES | `` | *NULL* | `` | Temporary |
| 36 | **`hardcopy_received`** | `int` | NO | `` | `0` | `` | This will set bet set to 1 if hardcopy receipt is received at concerned office |
| 37 | **`hardcopy_received_by_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 38 | **`scrutinized`** | `int` | YES | `` | *NULL* | `` |  |
| 39 | **`scrutinized_by`** | `int` | YES | `` | *NULL* | `` |  |
| 40 | **`scrutiny_eligible`** | `int` | YES | `` | *NULL* | `` |  |
| 41 | **`scrutiny_remark`** | `text` | YES | `` | *NULL* | `` |  |
| 42 | **`eligible`** | `int` | YES | `` | *NULL* | `` |  |
| 43 | **`eligibility_remark`** | `text` | YES | `` | *NULL* | `` |  |
| 44 | **`eligibility_updated_by`** | `int` | YES | `` | *NULL* | `` |  |
| 45 | **`eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 46 | **`interview_eligible`** | `int` | YES | `` | *NULL* | `` | Set to 1 if candidate is eligible for interview after written test, NULL = Not Eligible, 0 = Rejected |
| 47 | **`interview_eligibility_remark`** | `text` | YES | `` | *NULL* | `` |  |
| 48 | **`interview_eligibility_updated_by`** | `bigint` | YES | `` | *NULL* | `` |  |
| 49 | **`interview_eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 50 | **`order_uid`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 51 | **`admitcard_downloaded_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 52 | **`interview_letter_downloaded_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 53 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 54 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 55 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 56 | **`centre_name`** | `varchar(150)` | YES | `` | *NULL* | `` |  |
| 57 | **`centre_code`** | `varchar(150)` | YES | `` | *NULL* | `` |  |
| 58 | **`centre_address`** | `varchar(150)` | YES | `` | *NULL* | `` |  |
| 59 | **`centre_city`** | `varchar(150)` | YES | `` | *NULL* | `` |  |
| 60 | **`room_no`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 61 | **`seat_no`** | `varchar(50)` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `advertisement_id` | `advertisement_id` | NO | `BTREE` |
| `hardcopy_received_by_id` | `hardcopy_received_by_id` | NO | `BTREE` |
| `order_id` | `order_id` | NO | `BTREE` |
| `post_id` | `post_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `random_no` | `random_no` | NO | `BTREE` |
| `roll_no` | `roll_no` | NO | `BTREE` |
| `user_id` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `applicationforms_24072026_0300` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `roll_no` bigint DEFAULT NULL,
  `random_no` bigint DEFAULT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `advertisement_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_id` bigint NOT NULL,
  `post_serial_no` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `dob` date DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `mobile` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_percent` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `aadhaar_no` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `sub_caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `nationality` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `permanent_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `correspondence_address` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_district` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `basic_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `review` int NOT NULL DEFAULT '1' COMMENT 'review = 1 & submitted = 0 means Application form is under review and has not been submitted yet',
  `submitted` int NOT NULL DEFAULT '0' COMMENT 'submitted = 1 & review = 0 means user has reviewed and submitted the Application form',
  `paid` int DEFAULT NULL COMMENT 'paid = 1 means Application form with successful payment',
  `paid2` int DEFAULT NULL COMMENT 'Temporary',
  `hardcopy_received` int NOT NULL DEFAULT '0' COMMENT 'This will set bet set to 1 if hardcopy receipt is received at concerned office',
  `hardcopy_received_by_id` bigint DEFAULT NULL,
  `scrutinized` int DEFAULT NULL,
  `scrutinized_by` int DEFAULT NULL,
  `scrutiny_eligible` int DEFAULT NULL,
  `scrutiny_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligible` int DEFAULT NULL,
  `eligibility_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligibility_updated_by` int DEFAULT NULL,
  `eligibility_updated_at` timestamp NULL DEFAULT NULL,
  `interview_eligible` int DEFAULT NULL COMMENT 'Set to 1 if candidate is eligible for interview after written test, NULL = Not Eligible, 0 = Rejected',
  `interview_eligibility_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `interview_eligibility_updated_by` bigint DEFAULT NULL,
  `interview_eligibility_updated_at` timestamp NULL DEFAULT NULL,
  `order_uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admitcard_downloaded_at` timestamp NULL DEFAULT NULL,
  `interview_letter_downloaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `centre_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_code` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_address` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_city` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `post_id` (`post_id`),
  KEY `advertisement_id` (`advertisement_id`),
  KEY `roll_no` (`roll_no`),
  KEY `random_no` (`random_no`),
  KEY `hardcopy_received_by_id` (`hardcopy_received_by_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10094915 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-applicationforms-backup-27012025-1709"></a>Table: `careers_db`.`applicationforms_backup_27012025_1709`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `65707`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`advertisement_id`** | `bigint unsigned` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 4 | **`post_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 5 | **`name`** | `varchar(191)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 6 | **`gender`** | `varchar(10)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 7 | **`dob`** | `date` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 8 | **`mobile`** | `varchar(25)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 9 | **`disability`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 10 | **`disability_type`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 11 | **`disability_percent`** | `varchar(5)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 12 | **`aadhaar`** | `varchar(20)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 13 | **`category`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 14 | **`caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 15 | **`sub_caste`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 16 | **`nationality`** | `varchar(100)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 17 | **`permanent_address`** | `varchar(250)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 18 | **`correspondence_address`** | `varchar(250)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 19 | **`domicile_district`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 20 | **`domicile_state`** | `varchar(50)` | YES | `` | *NULL* | `` | Regenerated from basic_details |
| 21 | **`basic_details`** | `longtext` | NO | `` | *NULL* | `` |  |
| 22 | **`additional_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 23 | **`order_id`** | `bigint` | YES | `MUL` | *NULL* | `` |  |
| 24 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 25 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 26 | **`review`** | `int` | NO | `` | `1` | `` |  |
| 27 | **`submitted`** | `int` | NO | `` | `0` | `` |  |
| 28 | **`paid`** | `int` | YES | `` | *NULL* | `` | Application with successful payment |
| 29 | **`hardcopy_received`** | `int` | NO | `` | `0` | `` | This will set bet set to 1 if hardcopy receipt is received at concerned office |
| 30 | **`scrutinized`** | `int` | YES | `` | *NULL* | `` |  |
| 31 | **`scrutinized_by`** | `int` | YES | `` | *NULL* | `` |  |
| 32 | **`scrutiny_remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 33 | **`eligible`** | `int` | YES | `` | *NULL* | `` |  |
| 34 | **`eligibility_remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 35 | **`eligibility_updated_by`** | `int` | YES | `` | *NULL* | `` |  |
| 36 | **`eligibility_updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 37 | **`order_uid`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 38 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 39 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 40 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `order_id` | `order_id` | NO | `BTREE` |
| `post_id` | `post_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_id` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `applicationforms_backup_27012025_1709` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `post_id` bigint NOT NULL,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `dob` date DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `mobile` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `disability_percent` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `aadhaar` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `sub_caste` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `nationality` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `permanent_address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `correspondence_address` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_district` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `domicile_state` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Regenerated from basic_details',
  `basic_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `additional_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_id` bigint DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `review` int NOT NULL DEFAULT '1',
  `submitted` int NOT NULL DEFAULT '0',
  `paid` int DEFAULT NULL COMMENT 'Application with successful payment',
  `hardcopy_received` int NOT NULL DEFAULT '0' COMMENT 'This will set bet set to 1 if hardcopy receipt is received at concerned office',
  `scrutinized` int DEFAULT NULL,
  `scrutinized_by` int DEFAULT NULL,
  `scrutiny_remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligible` int DEFAULT NULL,
  `eligibility_remark` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `eligibility_updated_by` int DEFAULT NULL,
  `eligibility_updated_at` timestamp NULL DEFAULT NULL,
  `order_uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10080519 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-boards"></a>Table: `careers_db`.`boards`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `656`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(1000)` | NO | `` | *NULL* | `` |  |
| 3 | **`code`** | `varchar(1000)` | NO | `` | *NULL* | `` |  |
| 4 | **`active`** | `int` | NO | `` | `1` | `` |  |
| 5 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `boards` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=657 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-courses"></a>Table: `careers_db`.`courses`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `406`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `courses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=407 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-eligibility-tests"></a>Table: `careers_db`.`eligibility_tests`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `19928`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`agency`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 4 | **`year`** | `year` | NO | `` | *NULL* | `` |  |
| 5 | **`subject`** | `varchar(250)` | YES | `` | *NULL* | `` |  |
| 6 | **`added_for`** | `int` | NO | `` | *NULL* | `` | Will contain profile_id |
| 7 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `eligibility_tests` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year NOT NULL,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_for` int NOT NULL COMMENT 'Will contain profile_id',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19930 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-failed-jobs"></a>Table: `careers_db`.`failed_jobs`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`uuid`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`connection`** | `varchar(255)` | NO | `MUL` | *NULL* | `` |  |
| 4 | **`queue`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`payload`** | `longtext` | NO | `` | *NULL* | `` |  |
| 6 | **`exception`** | `longtext` | NO | `` | *NULL* | `` |  |
| 7 | **`failed_at`** | `timestamp` | NO | `` | `CURRENT_TIMESTAMP` | `DEFAULT_GENERATED` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `failed_jobs_connection_queue_failed_at_index` | `connection`, `queue`, `failed_at` | NO | `BTREE` |
| `failed_jobs_uuid_unique` | `uuid` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-foreignvisits"></a>Table: `careers_db`.`foreignvisits`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `9566`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`profile_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 3 | **`country`** | `varchar(191)` | NO | `` | *NULL* | `` | Country visited |
| 4 | **`date`** | `date` | NO | `` | *NULL* | `` | Date of leaving the country |
| 5 | **`duration`** | `varchar(191)` | NO | `` | *NULL* | `` | Duration of stay in the foreign country |
| 6 | **`purpose`** | `varchar(191)` | NO | `` | *NULL* | `` | Pupose of the visit |
| 7 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `foreignvisits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` bigint NOT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Country visited',
  `date` date NOT NULL COMMENT 'Date of leaving the country',
  `duration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Duration of stay in the foreign country',
  `purpose` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Pupose of the visit',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9571 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-institutionsattended"></a>Table: `careers_db`.`institutionsattended`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `103270`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`profile_id`** | `bigint` | YES | `` | *NULL* | `` |  |
| 3 | **`school`** | `varchar(191)` | YES | `` | *NULL* | `` | School Attended |
| 4 | **`college`** | `varchar(191)` | YES | `` | *NULL* | `` | College Attended |
| 5 | **`university`** | `varchar(191)` | YES | `` | *NULL* | `` | University/Board Attended |
| 6 | **`joining_year`** | `year` | NO | `` | *NULL* | `` | Year of Joining |
| 7 | **`leaving_year`** | `year` | YES | `` | *NULL* | `` | Year of Leaving |
| 8 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 9 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `institutionsattended` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` bigint DEFAULT NULL,
  `school` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'School Attended',
  `college` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'College Attended',
  `university` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'University/Board Attended',
  `joining_year` year NOT NULL COMMENT 'Year of Joining',
  `leaving_year` year DEFAULT NULL COMMENT 'Year of Leaving',
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103272 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-jobs"></a>Table: `careers_db`.`jobs`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`queue`** | `varchar(255)` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`payload`** | `longtext` | NO | `` | *NULL* | `` |  |
| 4 | **`attempts`** | `smallint unsigned` | NO | `` | *NULL* | `` |  |
| 5 | **`reserved_at`** | `int unsigned` | YES | `` | *NULL* | `` |  |
| 6 | **`available_at`** | `int unsigned` | NO | `` | *NULL* | `` |  |
| 7 | **`created_at`** | `int unsigned` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `jobs_queue_index` | `queue` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-media"></a>Table: `careers_db`.`media`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`model_type`** | `varchar(255)` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`model_id`** | `bigint unsigned` | NO | `` | *NULL* | `` |  |
| 4 | **`uuid`** | `char(36)` | YES | `UNI` | *NULL* | `` |  |
| 5 | **`collection_name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 7 | **`file_name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 8 | **`mime_type`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 9 | **`disk`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 10 | **`conversions_disk`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 11 | **`size`** | `bigint unsigned` | NO | `` | *NULL* | `` |  |
| 12 | **`manipulations`** | `longtext` | NO | `` | *NULL* | `` |  |
| 13 | **`custom_properties`** | `longtext` | NO | `` | *NULL* | `` |  |
| 14 | **`generated_conversions`** | `longtext` | NO | `` | *NULL* | `` |  |
| 15 | **`responsive_images`** | `longtext` | NO | `` | *NULL* | `` |  |
| 16 | **`order_column`** | `int unsigned` | YES | `` | *NULL* | `` |  |
| 17 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 18 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `media_model_type_model_id_index` | `model_type`, `model_id` | NO | `BTREE` |
| `media_uuid_unique` | `uuid` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-migrations"></a>Table: `careers_db`.`migrations`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `51`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`migration`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`batch`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-module-posttype"></a>Table: `careers_db`.`module_posttype`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`posttype_id`** | `int` | NO | `` | *NULL* | `` |  |
| 3 | **`module_id`** | `int` | NO | `` | *NULL* | `` |  |
| 4 | **`order`** | `int` | NO | `` | *NULL* | `` |  |
| 5 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `module_posttype` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `posttype_id` int NOT NULL,
  `module_id` int NOT NULL,
  `order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-modules"></a>Table: `careers_db`.`modules`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `33`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`uid`** | `varchar(100)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`name`** | `varchar(100)` | NO | `` | *NULL* | `` |  |
| 4 | **`description`** | `varchar(500)` | YES | `` | *NULL* | `` |  |
| 5 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `uid` | `uid` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-orders"></a>Table: `careers_db`.`orders`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `45280`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`service_id`** | `bigint` | NO | `MUL` | *NULL* | `` | This is requesting service's ID such as Employment Form ID |
| 4 | **`key`** | `varchar(191)` | NO | `` | *NULL* | `` | This client's key issued by AMU PG |
| 5 | **`payers_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 6 | **`payers_email`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 7 | **`payers_mobile`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 8 | **`client_ref_no`** | `varchar(191)` | YES | `` | *NULL* | `` | This is the field which will be sent as client_ref_no which is Order ID now |
| 9 | **`transaction_id`** | `varchar(191)` | YES | `` | *NULL* | `` | This is the field which will be storing AMU PG's Transaction ID formerly (Order ID) which is the actual client_ref_no sent to external PG meant for reconciliation purposes |
| 10 | **`txn_amt`** | `double(8,2)` | NO | `` | *NULL* | `` |  |
| 11 | **`payment_status`** | `int` | YES | `` | *NULL* | `` |  |
| 12 | **`txn_msg`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 13 | **`txn_date`** | `date` | YES | `` | *NULL* | `` |  |
| 14 | **`pg_ref_no`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 15 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 16 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 17 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 18 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `service_id` | `service_id` | NO | `BTREE` |
| `user_id` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `service_id` bigint NOT NULL COMMENT 'This is requesting service''s ID such as Employment Form ID',
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'This client''s key issued by AMU PG',
  `payers_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payers_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payers_mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'This is the field which will be sent as client_ref_no which is Order ID now',
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'This is the field which will be storing AMU PG''s Transaction ID formerly (Order ID) which is the actual client_ref_no sent to external PG meant for reconciliation purposes',
  `txn_amt` double(8,2) NOT NULL,
  `payment_status` int DEFAULT NULL,
  `txn_msg` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_date` date DEFAULT NULL,
  `pg_ref_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `service_id` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47146 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-otherdetails"></a>Table: `careers_db`.`otherdetails`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `13787`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`profile_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 3 | **`fellowship_undergraduate`** | `varchar(191)` | YES | `` | *NULL* | `` | Scholarships and fellowships at Undergraduate level |
| 4 | **`fellowship_graduate`** | `varchar(191)` | YES | `` | *NULL* | `` | Scholarships and fellowships at Graduate level |
| 5 | **`fellowship_postgraduate`** | `varchar(191)` | YES | `` | *NULL* | `` | Scholarships and fellowships at Postgraduate level |
| 6 | **`phd_thesis_title`** | `varchar(250)` | YES | `` | *NULL* | `` | Title of Ph.D. Thesis |
| 7 | **`research_phd_awarded`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 8 | **`research_phd_thesis`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 9 | **`research_phd_total_scholars`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 10 | **`research_mphil_awarded`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 11 | **`research_mphil_thesis`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 12 | **`research_mphil_total_scholars`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 13 | **`research_other_awarded`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 14 | **`research_other_thesis`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 15 | **`research_other_total_scholars`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 16 | **`eminent_scholar`** | `mediumtext` | YES | `` | *NULL* | `` | Details of being an eminent scholar |
| 17 | **`contribution_to_knowledge`** | `mediumtext` | YES | `` | *NULL* | `` | Details of significant contribution to knowledge |
| 18 | **`engaged_in_research`** | `mediumtext` | YES | `` | *NULL* | `` | Evidence of being actively engaged in research or innovation or in teaching methods or production of teaching material |
| 19 | **`industry_experience`** | `mediumtext` | YES | `` | *NULL* | `` | Experience of Industry or Professional Field which should include innovation and/or Research development |
| 20 | **`current_pay_level`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Pay Level |
| 21 | **`current_pay_range`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Pay Range |
| 22 | **`current_basic_pay`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Basic Pay |
| 23 | **`current_pay_band`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Pay Band |
| 24 | **`current_grade_pay`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Grade Pay |
| 25 | **`current_basic_pay_old`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Basic Pay |
| 26 | **`current_allowances`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Allowances |
| 27 | **`current_allowances_total`** | `varchar(191)` | YES | `` | *NULL* | `` | Current Total |
| 28 | **`increment_date`** | `varchar(191)` | YES | `` | *NULL* | `` | Date of next increment |
| 29 | **`minimum_initial_pay`** | `mediumtext` | YES | `` | *NULL* | `` | Is he/she willing to accept the minimum initial pay in the scale? If not, the pay expected with reason |
| 30 | **`joining_time`** | `varchar(191)` | YES | `` | *NULL* | `` | If appointed, joining time required from the date of appointment |
| 31 | **`books_published`** | `varchar(191)` | YES | `` | *NULL* | `` | Books Published |
| 32 | **`books_accepted`** | `varchar(191)` | YES | `` | *NULL* | `` | books_accepted |
| 33 | **`papers_published`** | `varchar(191)` | YES | `` | *NULL* | `` | papers_published |
| 34 | **`papers_accepted`** | `varchar(191)` | YES | `` | *NULL* | `` | papers_accepted |
| 35 | **`articles_published`** | `varchar(191)` | YES | `` | *NULL* | `` | articles_published |
| 36 | **`articles_accepted`** | `varchar(191)` | YES | `` | *NULL* | `` | articles_accepted |
| 37 | **`papers_read_published`** | `varchar(191)` | YES | `` | *NULL* | `` | Papers read at conferences |
| 38 | **`papers_read_accepted`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 39 | **`eca_university_administration`** | `varchar(191)` | YES | `` | *NULL* | `` | University administration |
| 40 | **`eca_student`** | `varchar(191)` | YES | `` | *NULL* | `` | Extra-curricular activities of students |
| 41 | **`eca_residential_student`** | `varchar(191)` | YES | `` | *NULL* | `` | (c) Residential life of students |
| 42 | **`eca_cultural`** | `varchar(191)` | YES | `` | *NULL* | `` | (d) Literary, cultural or other activities |
| 43 | **`relevant_work`** | `mediumtext` | YES | `` | *NULL* | `` | Any other work relevant to the qualification for the post applied for done since leaving college with dates. |
| 44 | **`previous_applications`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 45 | **`testimonial1`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 46 | **`testimonial2`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 47 | **`testimonial3`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 48 | **`remark_essential_qualification`** | `longtext` | YES | `` | *NULL* | `` |  |
| 49 | **`remark_essential_qualification_doc`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 50 | **`remark_desirable_qualification`** | `longtext` | YES | `` | *NULL* | `` |  |
| 51 | **`remark_desirable_qualification_doc`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 52 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 53 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 54 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `otherdetails` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` bigint NOT NULL,
  `fellowship_undergraduate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Scholarships and fellowships at Undergraduate level',
  `fellowship_graduate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Scholarships and fellowships at Graduate level',
  `fellowship_postgraduate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Scholarships and fellowships at Postgraduate level',
  `phd_thesis_title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Title of Ph.D. Thesis',
  `research_phd_awarded` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_phd_thesis` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_phd_total_scholars` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_mphil_awarded` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_mphil_thesis` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_mphil_total_scholars` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_other_awarded` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_other_thesis` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_other_total_scholars` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eminent_scholar` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Details of being an eminent scholar',
  `contribution_to_knowledge` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Details of significant contribution to knowledge',
  `engaged_in_research` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Evidence of being actively engaged in research or innovation or in teaching methods or production of teaching material',
  `industry_experience` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Experience of Industry or Professional Field which should include innovation and/or Research development',
  `current_pay_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Pay Level',
  `current_pay_range` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Pay Range',
  `current_basic_pay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Basic Pay',
  `current_pay_band` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Pay Band',
  `current_grade_pay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Grade Pay',
  `current_basic_pay_old` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Basic Pay',
  `current_allowances` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Allowances',
  `current_allowances_total` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Current Total',
  `increment_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Date of next increment',
  `minimum_initial_pay` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Is he/she willing to accept the minimum initial pay in the scale? If not, the pay expected with reason',
  `joining_time` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'If appointed, joining time required from the date of appointment',
  `books_published` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Books Published',
  `books_accepted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'books_accepted',
  `papers_published` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'papers_published',
  `papers_accepted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'papers_accepted',
  `articles_published` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'articles_published',
  `articles_accepted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'articles_accepted',
  `papers_read_published` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Papers read at conferences',
  `papers_read_accepted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eca_university_administration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'University administration',
  `eca_student` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Extra-curricular activities of students',
  `eca_residential_student` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(c) Residential life of students',
  `eca_cultural` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(d) Literary, cultural or other activities',
  `relevant_work` mediumtext COLLATE utf8mb4_unicode_ci COMMENT 'Any other work relevant to the qualification for the post applied for done since leaving college with dates.',
  `previous_applications` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial3` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark_essential_qualification` longtext COLLATE utf8mb4_unicode_ci,
  `remark_essential_qualification_doc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark_desirable_qualification` longtext COLLATE utf8mb4_unicode_ci,
  `remark_desirable_qualification_doc` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13788 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-otps"></a>Table: `careers_db`.`otps`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `25527`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |
| 3 | **`gateway`** | `varchar(25)` | YES | `` | *NULL* | `` |  |
| 4 | **`service`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 5 | **`otp`** | `varchar(10)` | YES | `` | *NULL* | `` |  |
| 6 | **`text`** | `varchar(1000)` | YES | `` | *NULL* | `` |  |
| 7 | **`mobile`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 8 | **`status`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 9 | **`response`** | `text` | YES | `` | *NULL* | `` |  |
| 10 | **`gateway_message_id`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 11 | **`gateway_status`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 12 | **`verified`** | `tinyint(1)` | YES | `` | *NULL* | `` |  |
| 13 | **`remark`** | `text` | YES | `` | *NULL* | `` |  |
| 14 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `otps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `gateway` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response` text COLLATE utf8mb4_unicode_ci,
  `gateway_message_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `remark` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37749 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-password-resets"></a>Table: `careers_db`.`password_resets`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `1265`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`email`** | `varchar(191)` | NO | `MUL` | *NULL* | `` |  |
| 2 | **`token`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `datetime` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `password_resets_email_index` | `email` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-pincodes"></a>Table: `careers_db`.`pincodes`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | YES | `MUL` | *NULL* | `` | Village/Locality name |
| 3 | **`locality`** | `varchar(191)` | YES | `` | *NULL* | `` | (BO/SO/HO) |
| 4 | **`pincode`** | `double` | YES | `MUL` | *NULL* | `` |  |
| 5 | **`sub_district`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 6 | **`district`** | `varchar(191)` | YES | `MUL` | *NULL* | `` |  |
| 7 | **`state`** | `varchar(191)` | YES | `MUL` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `DistrictName` | `district` | NO | `BTREE` |
| `LocalityName` | `name` | NO | `BTREE` |
| `PinCode` | `pincode` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `StateName` | `state` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `pincodes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Village/Locality name',
  `locality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(BO/SO/HO)',
  `pincode` double DEFAULT NULL,
  `sub_district` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `LocalityName` (`name`),
  KEY `PinCode` (`pincode`),
  KEY `DistrictName` (`district`),
  KEY `StateName` (`state`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-posts"></a>Table: `careers_db`.`posts`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `3173`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`advertisement_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 3 | **`posttype_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 4 | **`serial_no`** | `int` | YES | `` | *NULL* | `` |  |
| 5 | **`title`** | `varchar(200)` | NO | `` | *NULL* | `` |  |
| 6 | **`subject`** | `varchar(200)` | YES | `` | *NULL* | `` | Temporary |
| 7 | **`slug`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 8 | **`description`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 9 | **`vacancies`** | `int` | NO | `` | *NULL* | `` |  |
| 10 | **`location`** | `varchar(300)` | NO | `` | *NULL* | `` |  |
| 11 | **`pay_level`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 12 | **`pay_range`** | `varchar(200)` | NO | `` | *NULL* | `` |  |
| 13 | **`fee`** | `double` | NO | `` | *NULL* | `` |  |
| 14 | **`open_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 15 | **`reg_end_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 16 | **`payment_end_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 17 | **`withdrawn`** | `int` | NO | `` | `0` | `` |  |
| 18 | **`age_limit`** | `int` | YES | `` | *NULL* | `` | Age as on the advertisement closing date |
| 19 | **`experience`** | `int` | YES | `` | *NULL* | `` | Total experience up to the advertisement closing date |
| 20 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 21 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 22 | **`added_by`** | `int` | NO | `` | *NULL* | `` | User who added the advertisement |
| 23 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 24 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 25 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 26 | **`selection_method`** | `enum('Written Test + Interview','Interview Only')` | YES | `` | *NULL* | `` |  |
| 27 | **`admit_card_opening_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 28 | **`admit_card_closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 29 | **`test_date`** | `date` | YES | `` | *NULL* | `` |  |
| 30 | **`test_reporting_time`** | `varchar(50)` | YES | `` | *NULL* | `` | Reporting Time |
| 31 | **`gate_closing_time`** | `varchar(50)` | YES | `` | *NULL* | `` | Gate Closing Time at Centre |
| 32 | **`scheduled_test_start`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 33 | **`test_duration`** | `varchar(50)` | YES | `` | *NULL* | `` |  |
| 34 | **`interview_letter_opening_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 35 | **`interview_letter_closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 36 | **`interview_date`** | `date` | YES | `` | *NULL* | `` |  |
| 37 | **`interview_time`** | `time` | YES | `` | *NULL* | `` |  |
| 38 | **`interview_venue`** | `varchar(150)` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `slug` | `slug` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `advertisement_id` bigint NOT NULL,
  `posttype_id` bigint NOT NULL,
  `serial_no` int DEFAULT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Temporary',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `vacancies` int NOT NULL,
  `location` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_level` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_range` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` double NOT NULL,
  `open_date` datetime DEFAULT NULL,
  `reg_end_date` datetime DEFAULT NULL,
  `payment_end_date` datetime DEFAULT NULL,
  `withdrawn` int NOT NULL DEFAULT '0',
  `age_limit` int DEFAULT NULL COMMENT 'Age as on the advertisement closing date',
  `experience` int DEFAULT NULL COMMENT 'Total experience up to the advertisement closing date',
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `added_by` int NOT NULL COMMENT 'User who added the advertisement',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `selection_method` enum('Written Test + Interview','Interview Only') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admit_card_opening_date` datetime DEFAULT NULL,
  `admit_card_closing_date` datetime DEFAULT NULL,
  `test_date` date DEFAULT NULL,
  `test_reporting_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Reporting Time',
  `gate_closing_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Gate Closing Time at Centre',
  `scheduled_test_start` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `test_duration` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_letter_opening_date` datetime DEFAULT NULL,
  `interview_letter_closing_date` datetime DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` time DEFAULT NULL,
  `interview_venue` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2882 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-posttypes"></a>Table: `careers_db`.`posttypes`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `7`
- **Comment:** Configuration Table

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`pdf_template`** | `varchar(191)` | NO | `` | *NULL* | `` | Application Form PDF Template |
| 4 | **`default_selection_method`** | `enum('Written Test + Interview','Interview Only')` | YES | `` | *NULL* | `` |  |
| 5 | **`admit_card_template`** | `varchar(200)` | YES | `` | *NULL* | `` | Admit Card Template for written test |
| 6 | **`interview_letter_template`** | `varchar(200)` | YES | `` | *NULL* | `` | Interview Call Letter Template |
| 7 | **`submission_venue`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 8 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 9 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `posttypes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_template` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Application Form PDF Template',
  `default_selection_method` enum('Written Test + Interview','Interview Only') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admit_card_template` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Admit Card Template for written test',
  `interview_letter_template` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Interview Call Letter Template',
  `submission_venue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Configuration Table'
```
</details>

---

### <a id="prod-table-profile-academicqualifications"></a>Table: `careers_db`.`profile_academicqualifications`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `110528`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`profile_id`** | `int` | NO | `` | *NULL* | `` |  |
| 2 | **`academic_qualification_id`** | `int` | NO | `` | *NULL* | `` |  |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profile_academicqualifications` (
  `profile_id` int NOT NULL,
  `academic_qualification_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-profile-address"></a>Table: `careers_db`.`profile_address`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `45843`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`address_id`** | `int` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profile_address` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `address_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53251 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-profile-eligibilitytest"></a>Table: `careers_db`.`profile_eligibilitytest`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `16879`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`profile_id`** | `int` | NO | `` | *NULL* | `` |  |
| 2 | **`eligibility_test_id`** | `int` | NO | `` | *NULL* | `` |  |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profile_eligibilitytest` (
  `profile_id` int NOT NULL,
  `eligibility_test_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-profile-teachingresearch-experiences"></a>Table: `careers_db`.`profile_teachingresearch_experiences`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `12127`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`teachingresearch_experiences_id`** | `int` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profile_teachingresearch_experiences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teachingresearch_experiences_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-profile-workexperiences"></a>Table: `careers_db`.`profile_workexperiences`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `33544`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`work_experiences_id`** | `int` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profile_workexperiences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `work_experiences_id` int NOT NULL,
  `profile_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52629 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-profiles"></a>Table: `careers_db`.`profiles`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `35412`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `int` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`first_name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 4 | **`middle_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 5 | **`last_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 6 | **`spouse_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 7 | **`marital_status`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 8 | **`fathers_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 9 | **`mothers_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 10 | **`dob`** | `date` | YES | `` | *NULL* | `` | Date of Birth |
| 11 | **`gender`** | `varchar(10)` | NO | `` | *NULL* | `` |  |
| 12 | **`mobile`** | `varchar(25)` | YES | `` | *NULL* | `` |  |
| 13 | **`mobile_verified_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 14 | **`alternate_mobile`** | `varchar(25)` | YES | `` | *NULL* | `` |  |
| 15 | **`pwd`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 16 | **`pwd_type`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 17 | **`pwd_percent`** | `int` | YES | `` | *NULL* | `` |  |
| 18 | **`aadhaar_no`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 19 | **`religion`** | `varchar(25)` | YES | `` | *NULL* | `` |  |
| 20 | **`category`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 21 | **`caste`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 22 | **`sub_caste`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 23 | **`nationality`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 24 | **`place_of_birth`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 25 | **`district_of_birth`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 26 | **`state_of_birth`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 27 | **`domicile_state`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 28 | **`domicile_district`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 29 | **`identity_marks`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 30 | **`remarks`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 31 | **`verified`** | `int unsigned` | NO | `` | `0` | `` |  |
| 32 | **`lock`** | `int` | NO | `` | `0` | `` |  |
| 33 | **`---------------------`** | `int` | YES | `` | *NULL* | `` |  |
| 34 | **`conviction`** | `varchar(50)` | YES | `` | *NULL* | `` | Have you been ever been arrested/prosecuted/kept in detention/bound down/fined convicted by a court of Law or whether any case is pending against you in a Court of Law? |
| 35 | **`conviction_reason`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 36 | **`debarred`** | `varchar(50)` | YES | `` | *NULL* | `` | Have you ever been debarred from any exam/rusticated by any University or any other educational institution or whether any case is pending against you in any University or any other educational institution. |
| 37 | **`debarred_reason`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 38 | **`vigilance`** | `varchar(50)` | YES | `` | *NULL* | `` | Any vigilance/Disciplinary case is pending against you? |
| 39 | **`vigilance_reason`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 40 | **`phd_title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 41 | **`-----------------------`** | `int` | YES | `` | *NULL* | `` |  |
| 42 | **`photo_id`** | `int` | YES | `` | *NULL* | `` |  |
| 43 | **`signature_id`** | `int` | YES | `` | *NULL* | `` |  |
| 44 | **`thumb_id`** | `int` | YES | `` | *NULL* | `` |  |
| 45 | **`---------------`** | `int` | YES | `` | *NULL* | `` |  |
| 46 | **`permanant_address_id`** | `int` | YES | `` | *NULL* | `` |  |
| 47 | **`corresponding_address_id`** | `int` | YES | `` | *NULL* | `` |  |
| 48 | **`-----`** | `int` | YES | `` | *NULL* | `` |  |
| 49 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 50 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 51 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_id` | `user_id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profiles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `alternate_mobile` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pwd` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pwd_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pwd_percent` int DEFAULT NULL,
  `aadhaar_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `religion` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caste` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_caste` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district_of_birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_of_birth` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicile_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicile_district` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_marks` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` mediumtext COLLATE utf8mb4_unicode_ci,
  `verified` int unsigned NOT NULL DEFAULT '0',
  `lock` int NOT NULL DEFAULT '0',
  `---------------------` int DEFAULT NULL,
  `conviction` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Have you been ever been arrested/prosecuted/kept in detention/bound down/fined convicted by a court of Law or whether any case is pending against you in a Court of Law?',
  `conviction_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debarred` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Have you ever been debarred from any exam/rusticated by any University or any other educational institution or whether any case is pending against you in any University or any other educational institution.',
  `debarred_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vigilance` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Any vigilance/Disciplinary case is pending against you?',
  `vigilance_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phd_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `-----------------------` int DEFAULT NULL,
  `photo_id` int DEFAULT NULL,
  `signature_id` int DEFAULT NULL,
  `thumb_id` int DEFAULT NULL,
  `---------------` int DEFAULT NULL,
  `permanant_address_id` int DEFAULT NULL,
  `corresponding_address_id` int DEFAULT NULL,
  `-----` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=35413 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-receivables"></a>Table: `careers_db`.`receivables`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `22584`
- **Comment:** The owner of this table is OAPS, It will never contain direct relation with orders table & must have client id.

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`narration`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`description`** | `longtext` | YES | `` | *NULL* | `` |  |
| 4 | **`amount`** | `decimal(15,2)` | NO | `` | *NULL* | `` |  |
| 5 | **`currency`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`raised_on`** | `datetime` | YES | `` | *NULL* | `` |  |
| 7 | **`settlement_status`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`settled_on`** | `datetime` | YES | `` | *NULL* | `` |  |
| 9 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 13 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 14 | **`raised_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 15 | **`client_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 16 | **`order_uid`** | `varchar(36)` | YES | `MUL` | *NULL* | `` |  |
| 17 | **`collectable_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `client_id` | `client_id` | NO | `BTREE` |
| `order_uid` | `order_uid` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `raised_by_fk_6101115` | `raised_by_id` | NO | `BTREE` |
| `user_fk_6101111` | `user_id` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `receivables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `narration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `currency` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `raised_on` datetime DEFAULT NULL,
  `settlement_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settled_on` datetime DEFAULT NULL,
  `remarks` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `raised_by_id` bigint unsigned DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  `order_uid` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collectable_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_6101111` (`user_id`),
  KEY `raised_by_fk_6101115` (`raised_by_id`),
  KEY `client_id` (`client_id`),
  KEY `order_uid` (`order_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=25831 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='The owner of this table is OAPS, It will never contain direct relation with orders table & must have client id.'
```
</details>

---

### <a id="prod-table-referees"></a>Table: `careers_db`.`referees`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `42431`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `int` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `int` | YES | `` | *NULL* | `` |  |
| 4 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 5 | **`designation`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 6 | **`mobile`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 7 | **`email`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 8 | **`address`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 9 | **`period`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `referees` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `profile_id` int DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42433 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-scholarshipfellowship"></a>Table: `careers_db`.`scholarshipfellowship`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`user_id`** | `bigint` | NO | `` | *NULL* | `` |  |
| 3 | **`profile_id`** | `bigint` | YES | `` | *NULL* | `` |  |
| 4 | **`undergraduate_level`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 5 | **`graduate_level`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 6 | **`postgraduate_level`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 7 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 8 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 9 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `scholarshipfellowship` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `profile_id` bigint DEFAULT NULL,
  `undergraduate_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `graduate_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postgraduate_level` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-scrutiny-reports-all-it-posts"></a>Table: `careers_db`.`scrutiny_reports_all_it_posts`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `382`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`Post`** | `varchar(62)` | YES | `` | *NULL* | `` |  |
| 3 | **`S_No`** | `int` | YES | `` | *NULL* | `` |  |
| 4 | **`Application_No`** | `int` | YES | `MUL` | *NULL* | `` |  |
| 5 | **`name`** | `varchar(200)` | YES | `` | *NULL* | `` |  |
| 6 | **`ELIGIBLE`** | `varchar(8)` | YES | `` | *NULL* | `` | 1=eligible, 0=rejected, NULL=pending |
| 7 | **`Reason_of_Ineligibility`** | `varchar(101)` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `Application_No` | `Application_No` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `scrutiny_reports_all_it_posts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `Post` varchar(62) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `S_No` int DEFAULT NULL,
  `Application_No` int DEFAULT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ELIGIBLE` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '1=eligible, 0=rejected, NULL=pending',
  `Reason_of_Ineligibility` varchar(101) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Application_No` (`Application_No`)
) ENGINE=InnoDB AUTO_INCREMENT=383 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-services"></a>Table: `careers_db`.`services`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`client_key`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 4 | **`fee`** | `double(8,2)` | YES | `` | *NULL* | `` |  |
| 5 | **`with_late_fee`** | `double(8,2)` | YES | `` | *NULL* | `` |  |
| 6 | **`status`** | `tinyint(1)` | NO | `` | *NULL* | `` |  |
| 7 | **`remark`** | `text` | YES | `` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_key` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` double(8,2) DEFAULT NULL,
  `with_late_fee` double(8,2) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-sessions"></a>Table: `careers_db`.`sessions`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `87`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `varchar(191)` | NO | `PRI` | *NULL* | `` |  |
| 2 | **`user_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |
| 3 | **`ip_address`** | `varchar(45)` | YES | `` | *NULL* | `` |  |
| 4 | **`user_agent`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 5 | **`payload`** | `mediumtext` | NO | `` | *NULL* | `` |  |
| 6 | **`last_activity`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `sessions_id_unique` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` mediumtext COLLATE utf8mb4_unicode_ci,
  `payload` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-teachingresearch-experiences"></a>Table: `careers_db`.`teachingresearch_experiences`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `21263`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`teaching_bach_level`** | `int` | YES | `` | *NULL* | `` | In years. |
| 3 | **`teaching_mast_level`** | `int` | YES | `` | *NULL* | `` | In years. |
| 4 | **`research_master_level`** | `int` | YES | `` | *NULL* | `` | In years. |
| 5 | **`research_post_doctoral_level`** | `int` | YES | `` | *NULL* | `` | In years. |
| 6 | **`experience_educational_administration`** | `int` | YES | `` | *NULL* | `` | In years. |
| 7 | **`any_other_administrative_experience`** | `int` | YES | `` | *NULL* | `` | In years. |
| 8 | **`added_for`** | `varchar(191)` | NO | `` | *NULL* | `` | Will contain profile_id of the user |
| 9 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `teachingresearch_experiences` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `teaching_bach_level` int DEFAULT NULL COMMENT 'In years.',
  `teaching_mast_level` int DEFAULT NULL COMMENT 'In years.',
  `research_master_level` int DEFAULT NULL COMMENT 'In years.',
  `research_post_doctoral_level` int DEFAULT NULL COMMENT 'In years.',
  `experience_educational_administration` int DEFAULT NULL COMMENT 'In years.',
  `any_other_administrative_experience` int DEFAULT NULL COMMENT 'In years.',
  `added_for` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Will contain profile_id of the user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21266 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-transactions"></a>Table: `careers_db`.`transactions`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`client_id`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`merchant_id`** | `int` | NO | `` | *NULL* | `` |  |
| 4 | **`psp`** | `varchar(255)` | YES | `` | *NULL* | `` | Payment Service Provider, Such as BANK POS |
| 5 | **`payers_name`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 6 | **`payers_email`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 7 | **`payers_mobile`** | `varchar(20)` | YES | `` | *NULL* | `` |  |
| 8 | **`itc`** | `text` | YES | `` | *NULL* | `` | As given in Ingenico Integration kit with key clnt_rqst_meta |
| 9 | **`client_ref_no`** | `varchar(191)` | NO | `MUL` | *NULL* | `` |  |
| 10 | **`txn_amt`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 11 | **`txn_currency`** | `varchar(50)` | NO | `` | `INR` | `` |  |
| 12 | **`return_url`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 13 | **`redirection_to_pg`** | `varchar(255)` | YES | `` | `Self` | `` | Handling of redirection to PG, Self -> Client, AMUPG ->AMUPG |
| 14 | **`client_request_time`** | `datetime` | NO | `` | `CURRENT_TIMESTAMP` | `DEFAULT_GENERATED` |  |
| 15 | **`txn_mode`** | `varchar(255)` | YES | `` | *NULL* | `` | ONLINE/POS |
| 16 | **`payment_status`** | `int` | NO | `` | `0` | `` |  |
| 17 | **`status_code`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 18 | **`txn_msg`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 19 | **`txn_err_msg`** | `varchar(500)` | YES | `` | *NULL* | `` |  |
| 20 | **`txn_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 21 | **`pg_ref_no`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 22 | **`bank_ref_no`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 23 | **`pg_response`** | `text` | YES | `` | *NULL* | `` |  |
| 24 | **`raw_response`** | `text` | YES | `` | *NULL* | `` |  |
| 25 | **`reconciled`** | `tinyint` | YES | `` | *NULL* | `` |  |
| 26 | **`reconciled_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 27 | **`reconciliation_data`** | `text` | YES | `` | *NULL* | `` |  |
| 28 | **`status`** | `int` | NO | `` | `1` | `` |  |
| 29 | **`remark`** | `text` | YES | `` | *NULL* | `` |  |
| 30 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 31 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 32 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `client_ref_no` | `client_ref_no` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `merchant_id` int NOT NULL,
  `psp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Payment Service Provider, Such as BANK POS',
  `payers_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payers_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payers_mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `itc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'As given in Ingenico Integration kit with key clnt_rqst_meta',
  `client_ref_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `txn_amt` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `txn_currency` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INR',
  `return_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirection_to_pg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Self' COMMENT 'Handling of redirection to PG, Self -> Client, AMUPG ->AMUPG',
  `client_request_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `txn_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ONLINE/POS',
  `payment_status` int NOT NULL DEFAULT '0',
  `status_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_msg` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_err_msg` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `txn_date` datetime DEFAULT NULL,
  `pg_ref_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_ref_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pg_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `raw_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reconciled` tinyint DEFAULT NULL,
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `reconciliation_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '1',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_ref_no` (`client_ref_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-uploads"></a>Table: `careers_db`.`uploads`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `92064`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`disk`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 3 | **`category`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 4 | **`file_path`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 5 | **`file_name`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 6 | **`cdn_url`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 7 | **`status`** | `int` | NO | `` | `0` | `` |  |
| 8 | **`remark`** | `mediumtext` | YES | `` | *NULL* | `` |  |
| 9 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `uploads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `disk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cdn_url` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` int NOT NULL DEFAULT '0',
  `remark` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=92065 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-users"></a>Table: `careers_db`.`users`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `55050`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(191)` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`email`** | `varchar(191)` | NO | `UNI` | *NULL* | `` |  |
| 4 | **`email_verified_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 5 | **`password`** | `varchar(191)` | NO | `` | *NULL* | `` |  |
| 6 | **`remember_token`** | `varchar(100)` | YES | `` | *NULL* | `` |  |
| 7 | **`role_id`** | `int` | YES | `` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`internal`** | `int` | YES | `` | *NULL* | `` | Temporary field |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `name` | `name` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `users_email_unique` | `email` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `internal` int DEFAULT NULL COMMENT 'Temporary field',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=55078 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="prod-table-work-experiences"></a>Table: `careers_db`.`work_experiences`

- **Engine:** `MyISAM`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `52621`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`organisation_name`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 3 | **`status_of_organisation`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 4 | **`designation`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 5 | **`period_from`** | `datetime` | YES | `` | *NULL* | `` |  |
| 6 | **`period_to`** | `datetime` | YES | `` | *NULL* | `` |  |
| 7 | **`responsibities`** | `varchar(1100)` | YES | `` | *NULL* | `` |  |
| 8 | **`reason_for_leaving`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 9 | **`pay_band`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 10 | **`basic_pay`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 11 | **`gross_pay`** | `varchar(191)` | YES | `` | *NULL* | `` |  |
| 12 | **`added_for`** | `varchar(191)` | NO | `` | *NULL* | `` | Will contain profile_id of the user |
| 13 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 14 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `work_experiences` (
  `id` int NOT NULL AUTO_INCREMENT,
  `organisation_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_of_organisation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period_from` datetime DEFAULT NULL,
  `period_to` datetime DEFAULT NULL,
  `responsibities` varchar(1100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_for_leaving` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pay_band` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basic_pay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gross_pay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_for` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Will contain profile_id of the user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=52624 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

## 5. Development Database (`betacareers_db`) - Complete Schema (37 Tables)

### <a id="dev-table-academic-qualifications"></a>Table: `betacareers_db`.`academic_qualifications`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`course`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`year`** | `date` | NO | `` | *NULL* | `` |  |
| 4 | **`division`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`percentage`** | `float` | YES | `` | *NULL* | `` |  |
| 6 | **`cgpa`** | `float` | YES | `` | *NULL* | `` |  |
| 7 | **`subjects`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 8 | **`title`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 9 | **`remarks`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 13 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 14 | **`name_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 15 | **`board_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `board_fk_8863714` | `board_id` | NO | `BTREE` |
| `name_fk_8863709` | `name_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_8863723` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `board_fk_8863714` | `board_id` | `boards`.`id` | `NO ACTION` | `NO ACTION` |
| `name_fk_8863709` | `name_id` | `qualification_levels`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_8863723` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `academic_qualifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `course` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` date NOT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` float DEFAULT NULL,
  `cgpa` float DEFAULT NULL,
  `subjects` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `name_id` bigint unsigned DEFAULT NULL,
  `board_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863723` (`user_id`),
  KEY `name_fk_8863709` (`name_id`),
  KEY `board_fk_8863714` (`board_id`),
  CONSTRAINT `board_fk_8863714` FOREIGN KEY (`board_id`) REFERENCES `boards` (`id`),
  CONSTRAINT `name_fk_8863709` FOREIGN KEY (`name_id`) REFERENCES `qualification_levels` (`id`),
  CONSTRAINT `user_fk_8863723` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-adresses"></a>Table: `betacareers_db`.`adresses`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`type`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`house_no`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`street`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`landmark`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`locality`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`city`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`district`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 9 | **`status`** | `int` | NO | `` | *NULL* | `` |  |
| 10 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 11 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 13 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 14 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 15 | **`postal_code_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 16 | **`province_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 17 | **`country_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `country_fk_8863688` | `country_id` | NO | `BTREE` |
| `postal_code_fk_8863685` | `postal_code_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `province_fk_8863687` | `province_id` | NO | `BTREE` |
| `user_fk_8863691` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `country_fk_8863688` | `country_id` | `countries`.`id` | `NO ACTION` | `NO ACTION` |
| `postal_code_fk_8863685` | `postal_code_id` | `postal_codes`.`id` | `NO ACTION` | `NO ACTION` |
| `province_fk_8863687` | `province_id` | `provinces`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_8863691` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `adresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `house_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `landmark` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `postal_code_id` bigint unsigned DEFAULT NULL,
  `province_id` bigint unsigned DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863691` (`user_id`),
  KEY `postal_code_fk_8863685` (`postal_code_id`),
  KEY `province_fk_8863687` (`province_id`),
  KEY `country_fk_8863688` (`country_id`),
  CONSTRAINT `country_fk_8863688` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `postal_code_fk_8863685` FOREIGN KEY (`postal_code_id`) REFERENCES `postal_codes` (`id`),
  CONSTRAINT `province_fk_8863687` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `user_fk_8863691` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-advertisement-types"></a>Table: `betacareers_db`.`advertisement_types`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `advertisement_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-advertisements"></a>Table: `betacareers_db`.`advertisements`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`slug`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`description`** | `longtext` | YES | `` | *NULL* | `` |  |
| 5 | **`dated`** | `date` | YES | `` | *NULL* | `` |  |
| 6 | **`advertisement_url`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`default_fee`** | `decimal(15,2)` | YES | `` | *NULL* | `` |  |
| 8 | **`default_opening_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 9 | **`default_closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 10 | **`default_payment_closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 11 | **`status`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 12 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 13 | **`approved_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 14 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 17 | **`type_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 18 | **`added_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 19 | **`approved_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `added_by_fk_8863536` | `added_by_id` | NO | `BTREE` |
| `approved_by_fk_8863537` | `approved_by_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `type_fk_10747638` | `type_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `added_by_fk_8863536` | `added_by_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |
| `approved_by_fk_8863537` | `approved_by_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |
| `type_fk_10747638` | `type_id` | `advertisement_types`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `advertisements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `dated` date DEFAULT NULL,
  `advertisement_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_fee` decimal(15,2) DEFAULT NULL,
  `default_opening_date` datetime DEFAULT NULL,
  `default_closing_date` datetime DEFAULT NULL,
  `default_payment_closing_date` datetime DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `type_id` bigint unsigned DEFAULT NULL,
  `added_by_id` bigint unsigned DEFAULT NULL,
  `approved_by_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `type_fk_10747638` (`type_id`),
  KEY `added_by_fk_8863536` (`added_by_id`),
  KEY `approved_by_fk_8863537` (`approved_by_id`),
  CONSTRAINT `added_by_fk_8863536` FOREIGN KEY (`added_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `approved_by_fk_8863537` FOREIGN KEY (`approved_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `type_fk_10747638` FOREIGN KEY (`type_id`) REFERENCES `advertisement_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-application-forms"></a>Table: `betacareers_db`.`application_forms`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`roll_no`** | `int` | YES | `` | *NULL* | `` |  |
| 3 | **`random_no`** | `int` | YES | `` | *NULL* | `` |  |
| 4 | **`advertisement_title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`post_serial_no`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`post_title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`email`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 9 | **`gender`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 10 | **`dob`** | `date` | YES | `` | *NULL* | `` |  |
| 11 | **`mobile`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 12 | **`disability`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 13 | **`disability_percent`** | `int` | YES | `` | *NULL* | `` |  |
| 14 | **`aadhaar_no`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 15 | **`sub_caste`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 16 | **`permanent_address`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 17 | **`correspondence_address`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 18 | **`domicile_district`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 19 | **`basic_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 20 | **`additional_details`** | `longtext` | YES | `` | *NULL* | `` |  |
| 21 | **`status`** | `int` | YES | `` | *NULL* | `` |  |
| 22 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 23 | **`review`** | `int` | YES | `` | *NULL* | `` |  |
| 24 | **`submitted`** | `int` | YES | `` | *NULL* | `` |  |
| 25 | **`paid`** | `int` | YES | `` | *NULL* | `` |  |
| 26 | **`hardcopy_received`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 27 | **`scrutinized`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 28 | **`scrutiny_remark`** | `longtext` | YES | `` | *NULL* | `` |  |
| 29 | **`eligible`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 30 | **`eligibility_remark`** | `longtext` | YES | `` | *NULL* | `` |  |
| 31 | **`eligibility_updated_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 32 | **`order_uid`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 33 | **`admitcard_downloaded_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 34 | **`interview_letter_downloaded_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 35 | **`centre_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 36 | **`centre_code`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 37 | **`centre_address`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 38 | **`centre_city`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 39 | **`room_no`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 40 | **`seat_no`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 41 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 42 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 43 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 44 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 45 | **`advertisement_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 46 | **`post_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 47 | **`disability_type_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 48 | **`religion_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 49 | **`category_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 50 | **`caste_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 51 | **`nationality_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 52 | **`domicile_state_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 53 | **`scrutinized_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 54 | **`eligibility_updated_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `advertisement_fk_10797845` | `advertisement_id` | NO | `BTREE` |
| `caste_fk_10797861` | `caste_id` | NO | `BTREE` |
| `category_fk_10797860` | `category_id` | NO | `BTREE` |
| `disability_type_fk_10797856` | `disability_type_id` | NO | `BTREE` |
| `domicile_state_fk_10797867` | `domicile_state_id` | NO | `BTREE` |
| `eligibility_updated_by_fk_10797884` | `eligibility_updated_by_id` | NO | `BTREE` |
| `nationality_fk_10797863` | `nationality_id` | NO | `BTREE` |
| `post_fk_10797847` | `post_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `religion_fk_10797859` | `religion_id` | NO | `BTREE` |
| `scrutinized_by_fk_10797880` | `scrutinized_by_id` | NO | `BTREE` |
| `user_fk_10797842` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `advertisement_fk_10797845` | `advertisement_id` | `advertisements`.`id` | `NO ACTION` | `NO ACTION` |
| `caste_fk_10797861` | `caste_id` | `castes`.`id` | `NO ACTION` | `NO ACTION` |
| `category_fk_10797860` | `category_id` | `categories`.`id` | `NO ACTION` | `NO ACTION` |
| `disability_type_fk_10797856` | `disability_type_id` | `disability_types`.`id` | `NO ACTION` | `NO ACTION` |
| `domicile_state_fk_10797867` | `domicile_state_id` | `provinces`.`id` | `NO ACTION` | `NO ACTION` |
| `eligibility_updated_by_fk_10797884` | `eligibility_updated_by_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |
| `nationality_fk_10797863` | `nationality_id` | `countries`.`id` | `NO ACTION` | `NO ACTION` |
| `post_fk_10797847` | `post_id` | `posts`.`id` | `NO ACTION` | `NO ACTION` |
| `religion_fk_10797859` | `religion_id` | `religions`.`id` | `NO ACTION` | `NO ACTION` |
| `scrutinized_by_fk_10797880` | `scrutinized_by_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_10797842` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `application_forms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `roll_no` int DEFAULT NULL,
  `random_no` int DEFAULT NULL,
  `advertisement_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_serial_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disability` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disability_percent` int DEFAULT NULL,
  `aadhaar_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_caste` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correspondence_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `domicile_district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `basic_details` longtext COLLATE utf8mb4_unicode_ci,
  `additional_details` longtext COLLATE utf8mb4_unicode_ci,
  `status` int DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `review` int DEFAULT NULL,
  `submitted` int DEFAULT NULL,
  `paid` int DEFAULT NULL,
  `hardcopy_received` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scrutinized` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scrutiny_remark` longtext COLLATE utf8mb4_unicode_ci,
  `eligible` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eligibility_remark` longtext COLLATE utf8mb4_unicode_ci,
  `eligibility_updated_at` datetime DEFAULT NULL,
  `order_uid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admitcard_downloaded_at` datetime DEFAULT NULL,
  `interview_letter_downloaded_at` datetime DEFAULT NULL,
  `centre_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `centre_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `room_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seat_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL,
  `post_id` bigint unsigned DEFAULT NULL,
  `disability_type_id` bigint unsigned DEFAULT NULL,
  `religion_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `caste_id` bigint unsigned DEFAULT NULL,
  `nationality_id` bigint unsigned DEFAULT NULL,
  `domicile_state_id` bigint unsigned DEFAULT NULL,
  `scrutinized_by_id` bigint unsigned DEFAULT NULL,
  `eligibility_updated_by_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_10797842` (`user_id`),
  KEY `advertisement_fk_10797845` (`advertisement_id`),
  KEY `post_fk_10797847` (`post_id`),
  KEY `disability_type_fk_10797856` (`disability_type_id`),
  KEY `religion_fk_10797859` (`religion_id`),
  KEY `category_fk_10797860` (`category_id`),
  KEY `caste_fk_10797861` (`caste_id`),
  KEY `nationality_fk_10797863` (`nationality_id`),
  KEY `domicile_state_fk_10797867` (`domicile_state_id`),
  KEY `scrutinized_by_fk_10797880` (`scrutinized_by_id`),
  KEY `eligibility_updated_by_fk_10797884` (`eligibility_updated_by_id`),
  CONSTRAINT `advertisement_fk_10797845` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisements` (`id`),
  CONSTRAINT `caste_fk_10797861` FOREIGN KEY (`caste_id`) REFERENCES `castes` (`id`),
  CONSTRAINT `category_fk_10797860` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `disability_type_fk_10797856` FOREIGN KEY (`disability_type_id`) REFERENCES `disability_types` (`id`),
  CONSTRAINT `domicile_state_fk_10797867` FOREIGN KEY (`domicile_state_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `eligibility_updated_by_fk_10797884` FOREIGN KEY (`eligibility_updated_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `nationality_fk_10797863` FOREIGN KEY (`nationality_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `post_fk_10797847` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `religion_fk_10797859` FOREIGN KEY (`religion_id`) REFERENCES `religions` (`id`),
  CONSTRAINT `scrutinized_by_fk_10797880` FOREIGN KEY (`scrutinized_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `user_fk_10797842` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-audit-logs"></a>Table: `betacareers_db`.`audit_logs`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`description`** | `text` | NO | `` | *NULL* | `` |  |
| 3 | **`subject_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |
| 4 | **`subject_type`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`user_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |
| 6 | **`properties`** | `text` | YES | `` | *NULL* | `` |  |
| 7 | **`host`** | `varchar(46)` | YES | `` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `properties` text COLLATE utf8mb4_unicode_ci,
  `host` varchar(46) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-boards"></a>Table: `betacareers_db`.`boards`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `boards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-castes"></a>Table: `betacareers_db`.`castes`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `castes_name_unique` | `name` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `castes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `castes_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-categories"></a>Table: `betacareers_db`.`categories`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `categories_name_unique` | `name` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-countries"></a>Table: `betacareers_db`.`countries`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`short_code`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-disability-types"></a>Table: `betacareers_db`.`disability_types`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `disability_types_name_unique` | `name` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `disability_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `disability_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-eligibility-tests"></a>Table: `betacareers_db`.`eligibility_tests`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`agency`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`year`** | `date` | NO | `` | *NULL* | `` |  |
| 5 | **`subject`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_8863732` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `user_fk_8863732` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `eligibility_tests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` date NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863732` (`user_id`),
  CONSTRAINT `user_fk_8863732` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-employment-histories"></a>Table: `betacareers_db`.`employment_histories`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`employer`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`type`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`designation`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`from`** | `date` | NO | `` | *NULL* | `` |  |
| 6 | **`to`** | `date` | YES | `` | *NULL* | `` |  |
| 7 | **`responsibilities`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 8 | **`reason_for_leaving`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 9 | **`pay_band`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 10 | **`basic_pay`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 11 | **`gross_pay`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 12 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 13 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 14 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_8863763` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `user_fk_8863763` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `employment_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `employer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL,
  `responsibilities` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason_for_leaving` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_band` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_pay` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gross_pay` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863763` (`user_id`),
  CONSTRAINT `user_fk_8863763` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-faq-categories"></a>Table: `betacareers_db`.`faq_categories`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`category`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `faq_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-faq-questions"></a>Table: `betacareers_db`.`faq_questions`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`question`** | `longtext` | YES | `` | *NULL* | `` |  |
| 3 | **`answer`** | `longtext` | YES | `` | *NULL* | `` |  |
| 4 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`category_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `category_fk_8863516` | `category_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `category_fk_8863516` | `category_id` | `faq_categories`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `faq_questions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` longtext COLLATE utf8mb4_unicode_ci,
  `answer` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_fk_8863516` (`category_id`),
  CONSTRAINT `category_fk_8863516` FOREIGN KEY (`category_id`) REFERENCES `faq_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-foreign-visits"></a>Table: `betacareers_db`.`foreign_visits`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`date`** | `date` | NO | `` | *NULL* | `` |  |
| 3 | **`duration`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`purpose`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 6 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 9 | **`country_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `country_fk_8863734` | `country_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_8863741` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `country_fk_8863734` | `country_id` | `countries`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_8863741` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `foreign_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863741` (`user_id`),
  KEY `country_fk_8863734` (`country_id`),
  CONSTRAINT `country_fk_8863734` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `user_fk_8863741` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-institutions-attendeds"></a>Table: `betacareers_db`.`institutions_attendeds`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name_of_school`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`name_of_college`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`year_of_joining`** | `int` | NO | `` | *NULL* | `` |  |
| 5 | **`year_of_leaving`** | `int` | NO | `UNI` | *NULL* | `` |  |
| 6 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 10 | **`university_board_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `institutions_attendeds_year_of_leaving_unique` | `year_of_leaving` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `university_board_fk_10797900` | `university_board_id` | NO | `BTREE` |
| `user_fk_10797897` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `university_board_fk_10797900` | `university_board_id` | `boards`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_10797897` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `institutions_attendeds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_of_school` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_of_college` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_of_joining` int NOT NULL,
  `year_of_leaving` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `university_board_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `institutions_attendeds_year_of_leaving_unique` (`year_of_leaving`),
  KEY `user_fk_10797897` (`user_id`),
  KEY `university_board_fk_10797900` (`university_board_id`),
  CONSTRAINT `university_board_fk_10797900` FOREIGN KEY (`university_board_id`) REFERENCES `boards` (`id`),
  CONSTRAINT `user_fk_10797897` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-marital-statuses"></a>Table: `betacareers_db`.`marital_statuses`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `marital_statuses_title_unique` | `title` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `marital_statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marital_statuses_title_unique` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-media"></a>Table: `betacareers_db`.`media`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`model_type`** | `varchar(255)` | NO | `MUL` | *NULL* | `` |  |
| 3 | **`model_id`** | `bigint unsigned` | NO | `` | *NULL* | `` |  |
| 4 | **`uuid`** | `char(36)` | YES | `UNI` | *NULL* | `` |  |
| 5 | **`collection_name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 7 | **`file_name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 8 | **`mime_type`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 9 | **`disk`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 10 | **`conversions_disk`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 11 | **`size`** | `bigint unsigned` | NO | `` | *NULL* | `` |  |
| 12 | **`manipulations`** | `json` | NO | `` | *NULL* | `` |  |
| 13 | **`custom_properties`** | `json` | NO | `` | *NULL* | `` |  |
| 14 | **`generated_conversions`** | `json` | NO | `` | *NULL* | `` |  |
| 15 | **`responsive_images`** | `json` | NO | `` | *NULL* | `` |  |
| 16 | **`order_column`** | `int unsigned` | YES | `MUL` | *NULL* | `` |  |
| 17 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 18 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `media_model_type_model_id_index` | `model_type`, `model_id` | NO | `BTREE` |
| `media_order_column_index` | `order_column` | NO | `BTREE` |
| `media_uuid_unique` | `uuid` | YES (Unique/Primary) | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-migrations"></a>Table: `betacareers_db`.`migrations`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `51`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `int unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`migration`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`batch`** | `int` | NO | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-other-details"></a>Table: `betacareers_db`.`other_details`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`fellowship_undergraduate`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`fellowship_graduate`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`fellowship_postgraduate`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`phd_thesis_title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`research_phd_awarded`** | `int` | YES | `` | *NULL* | `` |  |
| 7 | **`research_phd_thesis`** | `int` | YES | `` | *NULL* | `` |  |
| 8 | **`research_phd_total_scholars`** | `int` | YES | `` | *NULL* | `` |  |
| 9 | **`research_mphil_awarded`** | `int` | YES | `` | *NULL* | `` |  |
| 10 | **`research_mphil_thesis`** | `int` | YES | `` | *NULL* | `` |  |
| 11 | **`research_mphil_total_scholars`** | `int` | YES | `` | *NULL* | `` |  |
| 12 | **`research_other_awarded`** | `int` | YES | `` | *NULL* | `` |  |
| 13 | **`research_other_thesis`** | `int` | YES | `` | *NULL* | `` |  |
| 14 | **`research_other_total_scholars`** | `int` | YES | `` | *NULL* | `` |  |
| 15 | **`eminent_scholar`** | `longtext` | YES | `` | *NULL* | `` |  |
| 16 | **`contribution_to_knowledge`** | `longtext` | YES | `` | *NULL* | `` |  |
| 17 | **`engaged_in_research`** | `longtext` | YES | `` | *NULL* | `` |  |
| 18 | **`industry_experience`** | `longtext` | YES | `` | *NULL* | `` |  |
| 19 | **`current_pay_level`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 20 | **`current_pay_range`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 21 | **`current_basic_pay`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 22 | **`current_pay_band`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 23 | **`current_grade_pay`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 24 | **`current_basic_pay_old`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 25 | **`current_allowances`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 26 | **`current_allowances_total`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 27 | **`increment_date`** | `date` | YES | `` | *NULL* | `` |  |
| 28 | **`minimum_initial_pay`** | `longtext` | YES | `` | *NULL* | `` |  |
| 29 | **`joining_time`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 30 | **`books_published`** | `int` | YES | `` | *NULL* | `` |  |
| 31 | **`books_accepted`** | `int` | YES | `` | *NULL* | `` |  |
| 32 | **`papers_published`** | `int` | YES | `` | *NULL* | `` |  |
| 33 | **`papers_accepted`** | `int` | YES | `` | *NULL* | `` |  |
| 34 | **`articles_published`** | `int` | YES | `` | *NULL* | `` |  |
| 35 | **`articles_accepted`** | `int` | YES | `` | *NULL* | `` |  |
| 36 | **`papers_read_published`** | `int` | YES | `` | *NULL* | `` |  |
| 37 | **`papers_read_accepted`** | `int` | YES | `` | *NULL* | `` |  |
| 38 | **`eca_university_administration`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 39 | **`eca_student`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 40 | **`eca_residential_student`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 41 | **`eca_cultural`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 42 | **`relevant_work`** | `longtext` | YES | `` | *NULL* | `` |  |
| 43 | **`previous_applications`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 44 | **`testimonial_1`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 45 | **`testimonial_2`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 46 | **`testimonial_3`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 47 | **`remark_essential_qualification`** | `longtext` | YES | `` | *NULL* | `` |  |
| 48 | **`remark_desirable_qualification`** | `longtext` | YES | `` | *NULL* | `` |  |
| 49 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 50 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 51 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 52 | **`user_id`** | `bigint unsigned` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `other_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fellowship_undergraduate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fellowship_graduate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fellowship_postgraduate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phd_thesis_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `research_phd_awarded` int DEFAULT NULL,
  `research_phd_thesis` int DEFAULT NULL,
  `research_phd_total_scholars` int DEFAULT NULL,
  `research_mphil_awarded` int DEFAULT NULL,
  `research_mphil_thesis` int DEFAULT NULL,
  `research_mphil_total_scholars` int DEFAULT NULL,
  `research_other_awarded` int DEFAULT NULL,
  `research_other_thesis` int DEFAULT NULL,
  `research_other_total_scholars` int DEFAULT NULL,
  `eminent_scholar` longtext COLLATE utf8mb4_unicode_ci,
  `contribution_to_knowledge` longtext COLLATE utf8mb4_unicode_ci,
  `engaged_in_research` longtext COLLATE utf8mb4_unicode_ci,
  `industry_experience` longtext COLLATE utf8mb4_unicode_ci,
  `current_pay_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_pay_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_basic_pay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_pay_band` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_grade_pay` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_basic_pay_old` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_allowances` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_allowances_total` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `increment_date` date DEFAULT NULL,
  `minimum_initial_pay` longtext COLLATE utf8mb4_unicode_ci,
  `joining_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `books_published` int DEFAULT NULL,
  `books_accepted` int DEFAULT NULL,
  `papers_published` int DEFAULT NULL,
  `papers_accepted` int DEFAULT NULL,
  `articles_published` int DEFAULT NULL,
  `articles_accepted` int DEFAULT NULL,
  `papers_read_published` int DEFAULT NULL,
  `papers_read_accepted` int DEFAULT NULL,
  `eca_university_administration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eca_student` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eca_residential_student` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eca_cultural` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `relevant_work` longtext COLLATE utf8mb4_unicode_ci,
  `previous_applications` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `testimonial_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark_essential_qualification` longtext COLLATE utf8mb4_unicode_ci,
  `remark_desirable_qualification` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-password-resets"></a>Table: `betacareers_db`.`password_resets`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`email`** | `varchar(255)` | NO | `MUL` | *NULL* | `` |  |
| 2 | **`token`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `password_resets_email_index` | `email` | NO | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-permission-role"></a>Table: `betacareers_db`.`permission_role`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`role_id`** | `bigint unsigned` | NO | `MUL` | *NULL* | `` |  |
| 2 | **`permission_id`** | `bigint unsigned` | NO | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `permission_id_fk_8863484` | `permission_id` | NO | `BTREE` |
| `role_id_fk_8863484` | `role_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `permission_id_fk_8863484` | `permission_id` | `permissions`.`id` | `CASCADE` | `NO ACTION` |
| `role_id_fk_8863484` | `role_id` | `roles`.`id` | `CASCADE` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `permission_role` (
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  KEY `role_id_fk_8863484` (`role_id`),
  KEY `permission_id_fk_8863484` (`permission_id`),
  CONSTRAINT `permission_id_fk_8863484` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_id_fk_8863484` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-permissions"></a>Table: `betacareers_db`.`permissions`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-photos"></a>Table: `betacareers_db`.`photos`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 3 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_8863677` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `user_fk_8863677` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863677` (`user_id`),
  CONSTRAINT `user_fk_8863677` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-post-types"></a>Table: `betacareers_db`.`post_types`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`pdf_template`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`admit_card_template`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`interview_letter_template`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`submission_venue`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`status`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 9 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `post_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdf_template` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `admit_card_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_letter_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-postal-codes"></a>Table: `betacareers_db`.`postal_codes`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`locality`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`code`** | `int` | NO | `` | *NULL* | `` |  |
| 5 | **`sub_district`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`district`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 7 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`province_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `province_fk_8863625` | `province_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `province_fk_8863625` | `province_id` | `provinces`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `postal_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locality` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` int NOT NULL,
  `sub_district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `province_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `province_fk_8863625` (`province_id`),
  CONSTRAINT `province_fk_8863625` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-posts"></a>Table: `betacareers_db`.`posts`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`serial_no`** | `int` | YES | `` | *NULL* | `` |  |
| 3 | **`title`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`subject`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`slug`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 6 | **`description`** | `longtext` | YES | `` | *NULL* | `` |  |
| 7 | **`vacancies`** | `int` | NO | `` | *NULL* | `` |  |
| 8 | **`location`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 9 | **`pay_level`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 10 | **`pay_range`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 11 | **`fee`** | `decimal(15,2)` | NO | `` | *NULL* | `` |  |
| 12 | **`opening_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 13 | **`closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 14 | **`payment_closing_date`** | `datetime` | YES | `` | *NULL* | `` |  |
| 15 | **`withdrawn`** | `int` | NO | `` | *NULL* | `` |  |
| 16 | **`status`** | `int` | NO | `` | *NULL* | `` |  |
| 17 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 18 | **`test_date`** | `date` | YES | `` | *NULL* | `` |  |
| 19 | **`test_reporting_time`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 20 | **`gate_closing_time`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 21 | **`scheduled_test_start`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 22 | **`test_duration`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 23 | **`interview_date`** | `date` | YES | `` | *NULL* | `` |  |
| 24 | **`interview_time`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 25 | **`interview_venue`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 26 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 27 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 28 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 29 | **`advertisement_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 30 | **`posttype_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 31 | **`added_by_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `added_by_fk_8863568` | `added_by_id` | NO | `BTREE` |
| `advertisement_fk_8863551` | `advertisement_id` | NO | `BTREE` |
| `posts_slug_unique` | `slug` | YES (Unique/Primary) | `BTREE` |
| `posttype_fk_8863552` | `posttype_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `added_by_fk_8863568` | `added_by_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |
| `advertisement_fk_8863551` | `advertisement_id` | `advertisements`.`id` | `NO ACTION` | `NO ACTION` |
| `posttype_fk_8863552` | `posttype_id` | `post_types`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `serial_no` int DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `vacancies` int NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pay_range` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fee` decimal(15,2) NOT NULL,
  `opening_date` datetime DEFAULT NULL,
  `closing_date` datetime DEFAULT NULL,
  `payment_closing_date` datetime DEFAULT NULL,
  `withdrawn` int NOT NULL,
  `status` int NOT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `test_date` date DEFAULT NULL,
  `test_reporting_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gate_closing_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_test_start` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `test_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_venue` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `advertisement_id` bigint unsigned DEFAULT NULL,
  `posttype_id` bigint unsigned DEFAULT NULL,
  `added_by_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `advertisement_fk_8863551` (`advertisement_id`),
  KEY `posttype_fk_8863552` (`posttype_id`),
  KEY `added_by_fk_8863568` (`added_by_id`),
  CONSTRAINT `added_by_fk_8863568` FOREIGN KEY (`added_by_id`) REFERENCES `users` (`id`),
  CONSTRAINT `advertisement_fk_8863551` FOREIGN KEY (`advertisement_id`) REFERENCES `advertisements` (`id`),
  CONSTRAINT `posttype_fk_8863552` FOREIGN KEY (`posttype_id`) REFERENCES `post_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-profiles"></a>Table: `betacareers_db`.`profiles`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`first_name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`middle_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`last_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`spouse_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`fathers_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`mothers_name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`dob`** | `date` | YES | `` | *NULL* | `` |  |
| 9 | **`gender`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 10 | **`mobile`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 11 | **`mobile_verified_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 12 | **`alternate_mobile`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 13 | **`pwd`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 14 | **`disability_percent`** | `int` | YES | `` | *NULL* | `` |  |
| 15 | **`aadhaar_no`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 16 | **`sub_caste`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 17 | **`place_of_birth`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 18 | **`identity_marks`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 19 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 20 | **`verified`** | `int` | NO | `` | *NULL* | `` |  |
| 21 | **`locked`** | `int` | NO | `` | *NULL* | `` |  |
| 22 | **`conviction`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 23 | **`conviction_reason`** | `longtext` | YES | `` | *NULL* | `` |  |
| 24 | **`debarred`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 25 | **`debarred_reason`** | `longtext` | YES | `` | *NULL* | `` |  |
| 26 | **`vigilance`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 27 | **`vigilance_reason`** | `longtext` | YES | `` | *NULL* | `` |  |
| 28 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 29 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 30 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 31 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 32 | **`marital_status_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 33 | **`disability_type_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 34 | **`religion_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 35 | **`category_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 36 | **`caste_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 37 | **`nationality_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 38 | **`district_of_birth_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 39 | **`state_of_birth_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 40 | **`domicile_state_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |
| 41 | **`domicile_district_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `caste_fk_8863652` | `caste_id` | NO | `BTREE` |
| `category_fk_8863651` | `category_id` | NO | `BTREE` |
| `disability_type_fk_8863647` | `disability_type_id` | NO | `BTREE` |
| `district_of_birth_fk_8863666` | `district_of_birth_id` | NO | `BTREE` |
| `domicile_district_fk_8863669` | `domicile_district_id` | NO | `BTREE` |
| `domicile_state_fk_8863668` | `domicile_state_id` | NO | `BTREE` |
| `marital_status_fk_8863638` | `marital_status_id` | NO | `BTREE` |
| `nationality_fk_8863665` | `nationality_id` | NO | `BTREE` |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `religion_fk_8863650` | `religion_id` | NO | `BTREE` |
| `state_of_birth_fk_8863667` | `state_of_birth_id` | NO | `BTREE` |
| `user_fk_8863630` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `caste_fk_8863652` | `caste_id` | `castes`.`id` | `NO ACTION` | `NO ACTION` |
| `category_fk_8863651` | `category_id` | `categories`.`id` | `NO ACTION` | `NO ACTION` |
| `disability_type_fk_8863647` | `disability_type_id` | `disability_types`.`id` | `NO ACTION` | `NO ACTION` |
| `district_of_birth_fk_8863666` | `district_of_birth_id` | `postal_codes`.`id` | `NO ACTION` | `NO ACTION` |
| `domicile_district_fk_8863669` | `domicile_district_id` | `postal_codes`.`id` | `NO ACTION` | `NO ACTION` |
| `domicile_state_fk_8863668` | `domicile_state_id` | `provinces`.`id` | `NO ACTION` | `NO ACTION` |
| `marital_status_fk_8863638` | `marital_status_id` | `marital_statuses`.`id` | `NO ACTION` | `NO ACTION` |
| `nationality_fk_8863665` | `nationality_id` | `countries`.`id` | `NO ACTION` | `NO ACTION` |
| `religion_fk_8863650` | `religion_id` | `religions`.`id` | `NO ACTION` | `NO ACTION` |
| `state_of_birth_fk_8863667` | `state_of_birth_id` | `provinces`.`id` | `NO ACTION` | `NO ACTION` |
| `user_fk_8863630` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fathers_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mothers_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_verified_at` datetime DEFAULT NULL,
  `alternate_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pwd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disability_percent` int DEFAULT NULL,
  `aadhaar_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_caste` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identity_marks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `verified` int NOT NULL,
  `locked` int NOT NULL,
  `conviction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conviction_reason` longtext COLLATE utf8mb4_unicode_ci,
  `debarred` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debarred_reason` longtext COLLATE utf8mb4_unicode_ci,
  `vigilance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vigilance_reason` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `marital_status_id` bigint unsigned DEFAULT NULL,
  `disability_type_id` bigint unsigned DEFAULT NULL,
  `religion_id` bigint unsigned DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `caste_id` bigint unsigned DEFAULT NULL,
  `nationality_id` bigint unsigned DEFAULT NULL,
  `district_of_birth_id` bigint unsigned DEFAULT NULL,
  `state_of_birth_id` bigint unsigned DEFAULT NULL,
  `domicile_state_id` bigint unsigned DEFAULT NULL,
  `domicile_district_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_8863630` (`user_id`),
  KEY `marital_status_fk_8863638` (`marital_status_id`),
  KEY `disability_type_fk_8863647` (`disability_type_id`),
  KEY `religion_fk_8863650` (`religion_id`),
  KEY `category_fk_8863651` (`category_id`),
  KEY `caste_fk_8863652` (`caste_id`),
  KEY `nationality_fk_8863665` (`nationality_id`),
  KEY `district_of_birth_fk_8863666` (`district_of_birth_id`),
  KEY `state_of_birth_fk_8863667` (`state_of_birth_id`),
  KEY `domicile_state_fk_8863668` (`domicile_state_id`),
  KEY `domicile_district_fk_8863669` (`domicile_district_id`),
  CONSTRAINT `caste_fk_8863652` FOREIGN KEY (`caste_id`) REFERENCES `castes` (`id`),
  CONSTRAINT `category_fk_8863651` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `disability_type_fk_8863647` FOREIGN KEY (`disability_type_id`) REFERENCES `disability_types` (`id`),
  CONSTRAINT `district_of_birth_fk_8863666` FOREIGN KEY (`district_of_birth_id`) REFERENCES `postal_codes` (`id`),
  CONSTRAINT `domicile_district_fk_8863669` FOREIGN KEY (`domicile_district_id`) REFERENCES `postal_codes` (`id`),
  CONSTRAINT `domicile_state_fk_8863668` FOREIGN KEY (`domicile_state_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `marital_status_fk_8863638` FOREIGN KEY (`marital_status_id`) REFERENCES `marital_statuses` (`id`),
  CONSTRAINT `nationality_fk_8863665` FOREIGN KEY (`nationality_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `religion_fk_8863650` FOREIGN KEY (`religion_id`) REFERENCES `religions` (`id`),
  CONSTRAINT `state_of_birth_fk_8863667` FOREIGN KEY (`state_of_birth_id`) REFERENCES `provinces` (`id`),
  CONSTRAINT `user_fk_8863630` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-provinces"></a>Table: `betacareers_db`.`provinces`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`type`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 4 | **`iso_3166_2_in`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`vehicle_code`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`zone`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 7 | **`capital`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 8 | **`largest_city`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 9 | **`statehood`** | `int` | YES | `` | *NULL* | `` |  |
| 10 | **`population`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 11 | **`area`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 12 | **`official_languages`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 13 | **`additional_official_languages`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 14 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 15 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 16 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `provinces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iso_3166_2_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `largest_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statehood` int DEFAULT NULL,
  `population` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `official_languages` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_official_languages` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-qualification-levels"></a>Table: `betacareers_db`.`qualification_levels`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`value`** | `int` | NO | `` | *NULL* | `` |  |
| 4 | **`status`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 5 | **`remarks`** | `longtext` | YES | `` | *NULL* | `` |  |
| 6 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 7 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 8 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `qualification_levels_name_unique` | `name` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `qualification_levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qualification_levels_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-referees"></a>Table: `betacareers_db`.`referees`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 3 | **`designation`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 4 | **`mobile`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 5 | **`email`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 6 | **`address`** | `longtext` | NO | `` | *NULL* | `` |  |
| 7 | **`period_known`** | `varchar(255)` | NO | `` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_10797895` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `user_fk_10797895` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `referees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `period_known` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_10797895` (`user_id`),
  CONSTRAINT `user_fk_10797895` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-religions"></a>Table: `betacareers_db`.`religions`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | NO | `UNI` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `religions_name_unique` | `name` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `religions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `religions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-role-user"></a>Table: `betacareers_db`.`role_user`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`user_id`** | `bigint unsigned` | NO | `MUL` | *NULL* | `` |  |
| 2 | **`role_id`** | `bigint unsigned` | NO | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `role_id_fk_8863493` | `role_id` | NO | `BTREE` |
| `user_id_fk_8863493` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `role_id_fk_8863493` | `role_id` | `roles`.`id` | `CASCADE` | `NO ACTION` |
| `user_id_fk_8863493` | `user_id` | `users`.`id` | `CASCADE` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `role_user` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  KEY `user_id_fk_8863493` (`user_id`),
  KEY `role_id_fk_8863493` (`role_id`),
  CONSTRAINT `role_id_fk_8863493` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_id_fk_8863493` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-roles"></a>Table: `betacareers_db`.`roles`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`title`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 4 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 5 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-traeds"></a>Table: `betacareers_db`.`traeds`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`teaching_at_bachelors_level_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 3 | **`teaching_at_masters_level_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 4 | **`research_at_masters_level_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 5 | **`research_at_post_doctorals_level_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 6 | **`experience_in_educational_administration_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 7 | **`any_other_administrative_experience_in_years`** | `int` | YES | `` | *NULL* | `` |  |
| 8 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 9 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 10 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`user_id`** | `bigint unsigned` | YES | `MUL` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `user_fk_10797907` | `user_id` | NO | `BTREE` |

#### Foreign Key Constraints

| Constraint Name | Local Column | Referenced Table & Column | On Delete | On Update |
| :--- | :--- | :--- | :--- | :--- |
| `user_fk_10797907` | `user_id` | `users`.`id` | `NO ACTION` | `NO ACTION` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `traeds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teaching_at_bachelors_level_in_years` int DEFAULT NULL,
  `teaching_at_masters_level_in_years` int DEFAULT NULL,
  `research_at_masters_level_in_years` int DEFAULT NULL,
  `research_at_post_doctorals_level_in_years` int DEFAULT NULL,
  `experience_in_educational_administration_in_years` int DEFAULT NULL,
  `any_other_administrative_experience_in_years` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_fk_10797907` (`user_id`),
  CONSTRAINT `user_fk_10797907` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

### <a id="dev-table-users"></a>Table: `betacareers_db`.`users`

- **Engine:** `InnoDB`
- **Collation:** `utf8mb4_unicode_ci`
- **Estimated Rows:** `0`

#### Column Specifications

| # | Field | Type | Nullable | Key | Default | Extra | Comments |
| :- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| 1 | **`id`** | `bigint unsigned` | NO | `PRI` | *NULL* | `auto_increment` |  |
| 2 | **`name`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 3 | **`email`** | `varchar(255)` | YES | `UNI` | *NULL* | `` |  |
| 4 | **`email_verified_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 5 | **`password`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 6 | **`verified`** | `tinyint(1)` | YES | `` | `0` | `` |  |
| 7 | **`verified_at`** | `datetime` | YES | `` | *NULL* | `` |  |
| 8 | **`verification_token`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 9 | **`remember_token`** | `varchar(255)` | YES | `` | *NULL* | `` |  |
| 10 | **`created_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 11 | **`updated_at`** | `timestamp` | YES | `` | *NULL* | `` |  |
| 12 | **`deleted_at`** | `timestamp` | YES | `` | *NULL* | `` |  |

#### Indexes & Keys

| Index Name | Columns | Unique | Index Type |
| :--- | :--- | :--- | :--- |
| `PRIMARY` | `id` | YES (Unique/Primary) | `BTREE` |
| `users_email_unique` | `email` | YES (Unique/Primary) | `BTREE` |

<details>
<summary><b>View MySQL DDL statement</b></summary>

```sql
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `verified_at` datetime DEFAULT NULL,
  `verification_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```
</details>

---

## 6. Data Migration & Alignment Strategies

When implementing migrations or synchronizing test data from Production (`careers_db`) to Beta Development (`betacareers_db`):

1. **Read-Only Safety**: The production connection `mysql_readonly` is read-only. Data migration commands or ETL scripts should SELECT from `mysql_readonly` and INSERT/UPDATE into `mysql` (default connection).
2. **Key Mapping & Foreign Key Checks**: Disable foreign key checks (`SET FOREIGN_KEY_CHECKS=0;`) during batch historical migrations and re-enable afterwards.
3. **Password & Hashing Compatibility**: Verify if production password hashes match Laravel's default bcrypt/argon2 hashing algorithms.
4. **Attachments & Media Storage**: Map legacy `uploads` and `media` paths to the Laravel `storage/app/public` filesystem disk.
