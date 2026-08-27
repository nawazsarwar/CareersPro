# Stage / State Machine Specification

## Application Lifecycle States

1.  **Draft:** Application initiated but not paid/submitted. Edits to profile/qualifications allowed.
2.  **Submitted:** Fee paid (or exempted), PDF generated, QR code active. Profile claims locked for this application.
3.  **Scrutiny (Under Review):** Initial checks by institutional staff.
    *   *Sub-state:* Deficiency Raised (Applicant notified to upload missing docs in a window).
4.  **Expert Screening (Scoring):** API calculation running against UGC rules. Committee review in progress.
5.  **Shortlisted (or Rejected):** Cut-offs applied. Eligible candidates pushed to the next phase.
6.  **Exam/Interview Call:** Admit cards generated, centres allotted, schedules confirmed.
7.  **Result Declared:** Final marks tabulated.
8.  **Offer/Joining:** Document verification and final joining formalities.

## State Transitions & Guards
*   `Draft -> Submitted`: Requires fee gateway success or valid exemption logic.
*   `Submitted -> Scrutiny`: Automatic upon submission timestamp.
*   `Scrutiny -> Deficient`: Manual admin action with required comment/reason.
*   `Expert Screening -> Shortlisted`: Requires quorum sign-off from the Screening Committee.

## Side Effects
*   State changes trigger Notification Center events (Email/SMS/WhatsApp).
*   State changes push a hash-chained entry to the `AuditLog` table.
