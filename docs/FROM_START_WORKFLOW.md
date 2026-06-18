# From-start workflow

## Purpose

This workflow applies the repository process from the beginning in a repeatable order. It does not implement every idea automatically. It validates the project, plans work, ranks improvements, checks release readiness, and builds the package manifest.

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

## Rules

- This workflow is safe orchestration, not automatic feature implementation.
- Risky or destructive features still need a focused spec first.
- Controller and frontend changes must remain separate reviewable patches.
- Generated frontend bundles must not be edited by hand.
- Run this workflow before promoting a larger feature batch.

## Expected outcome

A successful run means the repository is internally consistent, has an updated planning view, passes project checks, passes release readiness, and can build package artifacts with checksum and contents list.
