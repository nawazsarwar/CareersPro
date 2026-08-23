# Comparative Field Analysis: AMU Careers vs. CU Chayan vs. UGC Requirements

## 1. Candidate Profile & Demographics
| Field | AMU Careers Portal (v1) | CU Chayan Portal | Target Target / UGC Mandate | Coverage |
| :--- | :--- | :--- | :--- | :--- |
| **Personal Details** | Name, DOB, Gender, Father/Mother Name, Nationality | Name, DOB, Gender, Parents Name, Marital Status, Nationality | Core identity required for all GOI processes. | Both |
| **Category/Reservation** | SC/ST/OBC/General, PwBD type, Ex-Serviceman | Extensive reservation mapping (backlogs, specific PwBD benchmarks). | **Mandatory** for statutory roster calculation (Post-Based Roster). | CU Chayan superior. Target must adopt CU Chayan depth. |
| **Contact Info** | Address, Email, Phone | Address (Permanent/Corr), Email (Verified), Phone (OTP) | Required for Admit Cards / Offer letters. | Both |

## 2. Academic Qualifications
| Field | AMU Careers Portal (v1) | CU Chayan Portal | Target Target / UGC Mandate | Coverage |
| :--- | :--- | :--- | :--- | :--- |
| **Degrees (10th to PG)** | Board/Univ, Year, Marks %, Division, Subjects | Institution, Year, CGPA/%, Subjects, Document Proof | Required for basic eligibility check. | Both |
| **PhD / M.Phil** | Title, Year, University | Detailed PhD metadata (Date of Award, Compliance with UGC 2009/2016 regulations). | **Mandatory** for Assistant Professor screening (UGC API rules). | CU Chayan captures specific regulation compliance needed for API. |
| **Eligibility Tests** | NET/SLET/SET details, Gate | NET/JRF/SLET details with cert numbers. | **Mandatory** minimum qualification. | Both |

## 3. Experience & Employment History
| Field | AMU Careers Portal (v1) | CU Chayan Portal | Target Target / UGC Mandate | Coverage |
| :--- | :--- | :--- | :--- | :--- |
| **Work History** | Designation, Employer, From/To, Salary | Grade Pay / Pay Level, temporary/permanent nature, NOC status. | **Mandatory** for API (2 marks per year of teaching/post-doc). | CU Chayan superior for API mapping. |

## 4. Research, Publications & API Parameters (The Scoring Engine)
| Field | AMU Careers Portal (v1) | CU Chayan Portal | Target Target / UGC Mandate | Coverage |
| :--- | :--- | :--- | :--- | :--- |
| **Publications** | Title, Journal, ISSN/ISBN | Title, Journal (UGC-CARE list boolean), Peer-reviewed flag, Impact Factor, Authorship position (First/Corresponding). | **Strict UGC API Mandate** (Table 2 of 2018 Regs). Marks depend heavily on peer-review status, impact factor, and authorship role. | AMU v1 entirely lacks the granular metadata required to auto-compute API. |
| **Books/Chapters** | Title, Publisher | Publisher type (International/National), ISBN. | Required for API. | Target must add publisher type. |
| **Projects/Consultancy** | Title, Agency, Amount | Principal Investigator vs Co-PI, exact grant amounts. | Required for API. | Target must distinguish PI roles. |
| **Patents/Awards** | Name, Year | Patent level (International/National), Awarding body type. | Required for API. | Target must add levels. |

## 5. Conclusion & Gap Analysis
The AMU v1 portal functioned as a digital filing cabinet (simple digitization of Word forms). It **cannot** fulfill the UGC mandate for automated scoring because it lacks the structured, granular metadata (e.g., UGC-CARE listing status, impact factor, precise PhD regulation compliance, PI vs Co-PI roles).

To achieve maximum automation, CareersPro v2 must adopt the CU Chayan data model for Academic and Research fields, ensuring every parameter defined in **UGC Regulations 2018 (Table 2 & 3)** is captured as a distinct, scorable boolean or enum field.
