---
id: TASK-0006-saved-admin-filters
status: proposed
priority: medium
scope_in:
  - saved filter schema
  - server-side validation
  - app config storage design
scope_out:
  - storing raw log lines
  - destructive cleanup presets
  - generated frontend bundle edits
---

# Saved admin filters

## Goal

Let administrators save named log filter configurations for repeated use.

## Motivation

Administrators may repeatedly inspect the same combinations of log level, app, and search terms. Saving parameters reduces repetitive manual input.

## Requirements

- Store filter parameters only, never raw log entries.
- Limit filter name length.
- Validate level, app, and search values server-side.
- Deleting a saved filter must not affect log contents.
- Saved filters must remain administrator-only.

## First implementation slice

1. Define a small serialized filter schema.
2. Add validation for allowed fields and length limits.
3. Add helper-level create/list/delete behavior.
4. Wire UI later after backend shape is stable.

## Acceptance checks

- `./repo-run.sh ci` passes.
- Invalid filter shapes are rejected.
- Stored filters contain no raw log lines.
