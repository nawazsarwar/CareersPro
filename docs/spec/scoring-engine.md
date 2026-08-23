# Scoring Engine Specification

## Overview
The heart of the system is a versioned, declarative rules engine for calculating Academic Performance Indicators (API) and shortlisting marks based on UGC guidelines.

## Requirements
1.  **Declarative Ruleset:** No hard-coded logic. Rules are defined in data (e.g., JSON/YAML payloads managed by admins).
2.  **Versioning & Effective Dating:** An advertisement published under UGC 2018 rules must forever calculate scores using 2018 rules, even if a 2025 rule set is active for new posts.
3.  **Explainability:** Every score output must include a line-item breakdown (e.g., "PhD: 30 marks [Rule 3.1.a], Post-Doc: 5 marks [Rule 3.1.b]").
4.  **Overrides:** Committees can manually override auto-calculated marks, requiring a mandatory reason which is written to the immutable audit log.
5.  **Sandbox Mode:** Administrators can test changes to rule weights against historical application data before publishing the ruleset.

## Mechanism
- The `ScoringEngine` service evaluates a candidate's snapshot data against the linked `RuleSet`.
- Re-running the engine on the same snapshot and ruleset is guaranteed to be idempotent and byte-identical.
