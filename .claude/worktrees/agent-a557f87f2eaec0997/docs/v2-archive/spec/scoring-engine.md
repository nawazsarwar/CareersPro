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

## API Parameters & Scrutiny Alignment
The rules engine must specifically handle the following granular inputs gathered from the candidate:
1.  **Research Publications:** Must ingest `is_ugc_care`, `is_peer_reviewed`, `impact_factor`, and `authorship_position` to calculate varying weights (e.g., 8 marks for peer-reviewed, 10 for IF < 1, 15 for IF 1-2).
2.  **PhD Compliance:** Must ingest `is_ugc_2009_compliant` to validate if the PhD can exempt the candidate from NET/SLET.
3.  **Experience:** Must calculate exact days of experience, divided by 365, multiplied by 2, capped at a maximum as per UGC rules.
4.  **Projects:** Must differentiate between PI and Co-PI to split marks appropriately.
