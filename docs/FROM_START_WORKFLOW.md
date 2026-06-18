# From-start workflow

## Purpose

This workflow applies the repository process from the beginning in a repeatable order. It does not implement every idea automatically. It validates the project, plans work, ranks improvements, checks release readiness, builds the package manifest, and verifies installation readiness artifacts.

## Command

```bash
./repo-run.sh from-start
```

## Order

1. Documentation invariants
2. Approved spec planning
3. 250-item improvement backlog generation
4. Improvement candidate ranking
5. Full project checks
6. Release readiness
7. Package manifest generation
8. Installation readiness

## Planning outputs

The workflow writes planning artifacts under `build/planning/`:

- `spec-plan.md`
- `improvement-backlog.md`
- `improvement-plan.md`

CI uploads these files as the `logcleaner-planning` artifact.

## Package outputs

The workflow also builds package outputs under `build/`:

- `logcleaner-<version>.tar.gz`
- `logcleaner-<version>.tar.gz.sha256`
- `logcleaner-<version>.contents.txt`

CI uploads these files as the `logcleaner-package` artifact.

## Installation readiness

The workflow validates that installation documentation exists, package files exist, the checksum verifies, and the contents list includes required runtime entries. It does not copy files to a server or enable the app.

## Rules

- This workflow is safe orchestration, not automatic feature implementation.
- Installation remains a local administrator action.
- Risky or destructive features still need a focused spec first.
- Controller and frontend changes must remain separate reviewable patches.
- Generated frontend bundles must not be edited by hand.
- Run this workflow before promoting a larger feature batch.

## Expected outcome

A successful run means the repository is internally consistent, has an updated planning view, passes project checks, passes release readiness, can build package artifacts with checksum and contents list, and is ready for manual local installation review.
