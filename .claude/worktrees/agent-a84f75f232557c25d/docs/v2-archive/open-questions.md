# Open Questions & Ambiguities

| Ambiguity | Context | Default Assumption | Alternative | Config Switch |
| :--- | :--- | :--- | :--- | :--- |
| **UGC Regulations Scope** | The master directive mentions both 2018 rules and Draft 2025 rules. | We will support the 2018 regulations as default since 2025 is a draft, but build the rules engine to support a 2025 toggle. | Only support 2018 until 2025 is formally ratified. | `UGC_RULESET_VERSION` |
| **Post-submission Edits** | Can an applicant edit qualifications after generating a PDF but before paying the fee? | Allowed until final submission/payment. | Locked at PDF generation. | `ALLOW_EDIT_BEFORE_PAYMENT` |
| **Payment Gateway** | Which specific payment gateway is used? | Default to a mocked/sandbox gateway interface for testing. | Integrate specific endpoints if provided later. | `PAYMENT_GATEWAY_DRIVER` |
| **Laravel 13 Compatibility** | Master directive mandates Laravel 13, but the ecosystem (Composer packages) is currently compatible up to Laravel 11. | Fallback to Laravel 11.56 to maintain stability and security. | Force bleeding-edge Laravel 13 dev branches. | N/A (Handled via Composer) |
