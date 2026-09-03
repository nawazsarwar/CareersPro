# Comprehensive Field-Level Comparative Analysis

This table maps every granular field across the major applicant profile modules. Fields originating from CU Chayan that do not exist in AMU v1 will be built as **configurable (optional visibility)**.

## 1. Personal Profile Module

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| First/Middle/Last Name | Yes | Yes | Mandatory | Include | No |
| Date of Birth | Yes | Yes | Mandatory | Include | No |
| Gender | Yes | Yes | Mandatory | Include | No |
| Father's Name | Yes | Yes | Administrative | Include | No |
| Mother's Name | Yes | Yes | Administrative | Include | No |
| Nationality | Yes | Yes | Mandatory | Include | No |
| Marital Status | No | Yes | Administrative | Include | Yes |
| Religion | Yes | Yes | Demographic | Include | No |
| Category (UR/SC/ST/OBC/EWS) | Yes | Yes | **Mandatory** | Include | No |
| PwBD Status & Benchmark Type | Yes | Yes | **Mandatory** | Include | No |
| PwBD % of Disability | No | Yes | **Mandatory** | Include | Yes |
| Ex-Serviceman Status | Yes | Yes | **Mandatory** | Include | No |
| Government ID Type & Number | No | Yes | Security | Include | Yes |

## 2. Contact & Address Module

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Email Address | Yes | Yes | Mandatory | Include (Verified) | No |
| Mobile Number | Yes | Yes | Mandatory | Include (Verified) | No |
| Permanent Address (Line 1, 2) | Yes | Yes | Mandatory | Include | No |
| Permanent City, State, Pincode | Yes | Yes | Mandatory | Include | No |
| Correspondence Address | Yes | Yes | Mandatory | Include | No |

## 3. Academic Qualifications Module
*Note: Sequential validation mandated (10th -> 12th -> UG -> PG -> PhD).*

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Qualification Level (10th/12th/UG/PG) | Yes | Yes | Mandatory | Include (Enforce sequence) | No |
| Degree/Examination Name | Yes | Yes | Mandatory | Include | No |
| Subject/Specialization | Yes | Yes | Mandatory | Include | No |
| Board/University/Institution | Yes | Yes | Mandatory | Include | No |
| Year of Passing | Yes | Yes | Mandatory | Include | No |
| Max Marks / CGPA Scale | Yes | Yes | Mandatory | Include | No |
| Marks Obtained / CGPA Obtained | Yes | Yes | Mandatory | Include | No |
| Percentage (Auto-calc or Manual) | Yes | Yes | Mandatory | Include | No |
| Division / Class | Yes | Yes | Mandatory | Include | No |
| Upload: Final Marksheet | Yes | Yes | Mandatory | Include (PDF/JPG, <= 2MB) | No |
| Upload: Degree Certificate | No | Yes | Mandatory | Include (PDF/JPG, <= 2MB) | Yes |

## 4. Doctoral / Research Qualifications (PhD/M.Phil)

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Degree Type (PhD/M.Phil) | Yes | Yes | Mandatory | Include | No |
| Thesis Title | Yes | Yes | Administrative | Include | No |
| University | Yes | Yes | Mandatory | Include | No |
| Date of Award / Notification | Yes | Yes | **Mandatory API** | Include | No |
| Subject | Yes | Yes | Mandatory | Include | No |
| Compliant with UGC 2009/2016 Regs | No | Yes | **Mandatory API** | Include (Crucial for NET exemption) | Yes |

## 5. Eligibility Tests (NET/SLET/SET/GATE)

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Test Type (NET with JRF, NET, SET) | Yes | Yes | **Mandatory API** | Include | No |
| Subject | Yes | Yes | Mandatory | Include | No |
| Year of Passing | Yes | Yes | Mandatory | Include | No |
| Roll Number / Certificate No. | No | Yes | Security | Include | Yes |
| Upload: Certificate | Yes | Yes | Mandatory | Include (PDF/JPG, <= 2MB) | No |

## 6. Employment / Work Experience Module

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Designation / Post Held | Yes | Yes | Mandatory | Include | No |
| Organization / Employer | Yes | Yes | Mandatory | Include | No |
| Nature of Appointment (Temp/Perm) | No | Yes | **Mandatory API** | Include | Yes |
| From Date | Yes | Yes | **Mandatory API** | Include | No |
| To Date (or Present) | Yes | Yes | **Mandatory API** | Include | No |
| Duration (Auto-calculated Days/Yrs) | Yes | Yes | **Mandatory API** | Include (Auto-calc for API) | No |
| Pay Band / Pay Level / Salary | Yes | Yes | Mandatory | Include | No |
| Upload: Experience Certificate | Yes | Yes | Mandatory | Include | No |
| NOC Required / Upload | No | Yes | Administrative | Include | Yes |

## 7. Research Publications & API Outputs

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Publication Type (Journal, Book, etc.) | Yes | Yes | **Mandatory API** | Include | No |
| Title of Paper/Book | Yes | Yes | Mandatory | Include | No |
| Journal / Publisher Name | Yes | Yes | Mandatory | Include | No |
| ISSN / ISBN | Yes | Yes | Mandatory | Include | No |
| Peer-Reviewed Flag | No | Yes | **Mandatory API** | Include | Yes |
| UGC-CARE Listed Flag | No | Yes | **Mandatory API** | Include | Yes |
| Impact Factor | No | Yes | **Mandatory API** | Include | Yes |
| Authorship Position (First/Corr/Co) | No | Yes | **Mandatory API** | Include | Yes |
| Number of Co-Authors | No | Yes | **Mandatory API** | Include | Yes |
| Link/DOI | No | Yes | Verification | Include | Yes |

## 8. Projects, Consultancy & Patents

| Field Name | AMU v1 | CU Chayan | UGC Mandate | Target Implementation | Configurable? |
| :--- | :--- | :--- | :--- | :--- | :--- |
| Project Title | Yes | Yes | Mandatory | Include | No |
| Funding Agency | Yes | Yes | Mandatory | Include | No |
| Grant Amount | Yes | Yes | **Mandatory API** | Include | No |
| Role (PI, Co-PI) | No | Yes | **Mandatory API** | Include | Yes |
| Patent Level (National/International) | No | Yes | **Mandatory API** | Include | Yes |
