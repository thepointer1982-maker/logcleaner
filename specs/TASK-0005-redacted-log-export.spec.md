---
id: TASK-0005-redacted-log-export
status: proposed
priority: high
scope_in:
  - redaction helper
  - export size limit
  - filtered-view export design
scope_out:
  - public export links
  - unauthenticated access
  - raw production log sharing
---

# Redacted log export

## Goal

Allow administrators to export a filtered diagnostic view with conservative masking for sensitive-looking values.

## Motivation

Administrators often need to share diagnostic context. Raw logs may contain paths, identifiers, tokens, and user-specific data. A redacted export can support support workflows while reducing accidental disclosure risk.

## Requirements

- Export remains administrator-only.
- Export must have an explicit size limit.
- Redaction must happen server-side.
- Redaction is documented as best-effort, not a full security boundary.
- Raw log contents must never be written into project documentation or CI output.

## First implementation slice

1. Implement a helper-level redaction function.
2. Add fixture examples for common sensitive-looking patterns.
3. Return masked text for a limited filtered view.
4. Add UI wiring only after helper behavior is reviewed.

## Acceptance checks

- `./repo-run.sh ci` passes.
- Fixture examples show masking behavior.
- Oversized export attempts are rejected or truncated safely.
