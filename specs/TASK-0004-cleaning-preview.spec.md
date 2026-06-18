---
id: TASK-0004-cleaning-preview
status: approved
priority: critical
scope_in:
  - backend preview calculation
  - duplicate cleanup preview
  - level cleanup preview
  - app cleanup preview
scope_out:
  - changing existing destructive cleanup behavior
  - generated frontend bundle edits
  - automatic cleanup scheduling
---

# Cleaning preview / dry run

## Goal

Add a non-mutating preview path that tells an administrator how many log lines would be affected by a cleanup operation before that operation is executed.

## Motivation

Cleanup operations are destructive. A preview reduces operational risk and gives administrators a safer decision point.

## Requirements

- Preview must not write to the log file.
- Preview must remain administrator-only.
- Route parameters must be validated before file access.
- Malformed log lines must be ignored or counted without crashing the response.
- The response must expose counts and filter metadata, not full sensitive log contents.

## First implementation slice

1. Add helper-level preview calculation for duplicate, level, and app cleanup modes.
2. Add focused fixtures or small inline examples for malformed and valid log lines.
3. Add backend validation before connecting any UI.
4. Run focused PHP syntax checks and `./repo-run.sh ci`.

## Acceptance checks

- `./repo-run.sh ci` passes.
- Preview logic has no file write calls.
- Invalid level/app parameters are rejected or return safe zero-result responses.
- Malformed log lines do not crash preview calculation.
