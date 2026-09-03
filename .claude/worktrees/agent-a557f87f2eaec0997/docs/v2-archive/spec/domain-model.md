# Domain Model and Entity-Relationship Design (ERD)

## Overview
The domain model reflects the core concepts of university recruitment, separating the static catalog (Posts, Departments) from the temporal instances (Advertisements, Applications) and the user claims (Profiles, Qualifications).

## Core Entities
1.  **User / Profile**
    *   Authenticates via User.
    *   Profile contains static demographic info (Category, Caste, DisabilityType).
    *   Has many AcademicQualifications, EmploymentHistories, Publications (OtherDetails).
2.  **Catalog (Master Data)**
    *   Post (Designation, Pay Level)
    *   Department, Subject, Specialization
    *   Category, Caste, Religion, Province, Country
3.  **Advertisement**
    *   Links a Post to a specific recruitment drive.
    *   Holds vacancy counts, dates, and applicable rule sets.
4.  **ApplicationForm**
    *   Links a User to an Advertisement.
    *   Status driven (Draft -> Submitted -> Scrutinized -> Shortlisted -> Interview -> Selected).
    *   Locks a snapshot of User's qualifications at the time of submission to prevent post-submission edits.
5.  **AuditLog**
    *   Immutable ledger of all state changes, score overrides, and document accesses.

## Relationships
*   User 1:1 Profile
*   Profile 1:N ApplicationForm
*   Advertisement 1:N ApplicationForm
*   Advertisement N:1 Post
*   ApplicationForm 1:N AuditLog

## Soft Deletes & Versioning
*   All user claim entities (AcademicQualification, EmploymentHistory) utilize soft deletes.
*   Once an ApplicationForm is submitted, the associated claims are versioned (locked) via relationship snapshots, ensuring historical accuracy.
