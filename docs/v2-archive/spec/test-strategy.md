# Test Strategy

## Layers of Testing
1.  **Unit Tests (PHPUnit):** Focus on pure business logic, especially the `ScoringEngine` rules sets. These must assert marks against hand-worked UGC criteria examples.
2.  **Feature Tests (PHPUnit):** Test full endpoint lifecycles (e.g., submitting an application, updating a profile). Must assert proper session states, authorization (HTTP 403), and database side-effects.
3.  **Browser/E2E Tests (Laravel Dusk):** Focus on the critical path applicant journey. Must assert frontend behavior like progressive disclosure and Alpine.js interactions.
4.  **Static Analysis:** PHPStan set to maximum practical level running on every commit.

## Code Coverage
*   100% coverage on `app/Services/ScoringEngine` and related UGC rule evaluator classes.
*   Security Policies (Auth/RBAC) must have tests explicitly asserting horizontal and vertical privilege escalation failures.

## Mocking
*   Payment Gateways and SMS/Email dispatchers are heavily mocked in CI.
