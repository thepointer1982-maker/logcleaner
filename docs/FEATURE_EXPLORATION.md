# Feature exploration

## Purpose

This document collects new LogCleaner feature candidates before implementation. It is intentionally planning-focused: high-value ideas are described, scoped, and risk-rated before controller or frontend changes are made.

## Evaluation criteria

Feature candidates are evaluated by:

- administrator value
- implementation risk
- security impact
- compatibility with existing Nextcloud app behavior
- testability through `./repo-run.sh ci`
- whether the feature can be introduced without hand-editing generated frontend bundles

## Generated backlog

The repository can generate a 250-item improvement backlog locally:

```bash
./repo-run.sh backlog
```

This output is exploratory. It is not an implementation plan until individual items are promoted into focused specs.

## Candidate matrix

| Candidate | Value | Risk | First safe slice |
| --- | --- | --- | --- |
| Cleaning preview / dry run | High | Medium | Show how many lines would be affected before a cleanup action runs. |
| Redacted log export | High | Medium | Export a filtered view with obvious sensitive values masked. |
| Saved admin filters | Medium | Low | Store named level/app/search filters in app config. |
| Retention policy presets | Medium | Medium | Provide explicit presets, not automatic deletion by default. |
| Cleanup audit trail | High | Medium | Record what cleanup operation ran, when, and by which administrator identity if available. |
| Malformed-line diagnostics | Medium | Low | Count malformed JSON/log lines and expose a non-sensitive diagnostic summary. |
| Dashboard health summary | Medium | Low | Show log size, duplicate count, and most frequent levels/apps. |
| Importable test fixtures | High | Low | Add small synthetic log fixtures for manual and automated validation paths. |

## Immediate implementation candidates

### 1. Cleaning preview / dry run

Problem: Current cleanup operations are destructive and should be easier to reason about before execution.

First slice:

- add a non-mutating preview endpoint or helper path
- compute affected-line counts for duplicate, level, and app cleanup operations
- return counts and filter metadata only, not raw sensitive log payloads
- keep the real cleanup operation unchanged until preview is validated

Acceptance direction:

- preview routes must remain administrator-only
- route parameters must be validated before file access
- malformed log lines must not crash preview calculation
- no write operation may happen in preview mode

### 2. Redacted log export

Problem: Administrators may need to share diagnostics without exposing full paths, tokens, user IDs, or other sensitive values.

First slice:

- add a backend redaction helper with conservative masking rules
- export only the currently filtered view
- add limits to avoid exporting unexpectedly large payloads
- document that redaction is best-effort, not a security boundary

Acceptance direction:

- export remains administrator-only
- export has an explicit size limit
- redaction helper has fixture-based examples before it is wired to UI

### 3. Saved admin filters

Problem: Repeated level/app/search combinations are tedious for administrators.

First slice:

- define the filter configuration shape
- validate names and values server-side
- store only filter parameters, not log contents
- expose a simple list/create/delete workflow later

Acceptance direction:

- filter names are length-limited
- stored filters must not contain raw log lines
- deleting a saved filter must not affect log contents

## Deferred candidates

Retention policies and cleanup audit trail should wait until controller route hardening and CSRF/post-route migration are completed. They touch destructive operations and should not be implemented as a broad first change.

## Hardware-aware exploration

Use `docs/HARDWARE_PLANNING.md` to choose a focused, standard, or extended local test profile. Stronger hardware can justify larger synthetic fixtures and repeated package checks, but it does not approve risky controller changes.

## Next work

1. Generate the 250-item backlog with `./repo-run.sh backlog`.
2. Promote only high-value items into specs.
3. Keep new specs small enough for focused review.
4. Prefer helper-level prototypes and validation tools before controller mutation.
5. Run `./repo-run.sh ci` before merging any implementation.
