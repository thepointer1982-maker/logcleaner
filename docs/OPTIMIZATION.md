# Optimization plan

## Purpose

This document lists safe optimization directions for LogCleaner. It is planning guidance, not an automatic code change.

## Priority areas

### Parsing

Prefer single-pass counters before adding multi-pass log scans. Large files should be handled with bounded memory assumptions.

### Preview

Preview paths should expose counts and summaries first. They should avoid raw payload output and should have explicit limits.

### Dashboard

Dashboard features should use lazy aggregate helpers. Expensive counters should not be computed repeatedly without a reason.

### Fixtures

Synthetic fixtures should be used for performance tests. Production log data should not be copied into tests or documentation.

### Export

Export paths should reject oversized requests early and document masking as best-effort.

### Packaging

Packaging should keep a small explicit runtime path list and continue rejecting development-only paths.

### Installation

Installation optimization means fewer manual steps, not automatic server mutation. Readiness checks should validate package, checksum, contents list, and install documentation.

## Next safe steps

1. Keep `./repo-run.sh from-start` as the main orchestration command.
2. Add implementation only through focused specs.
3. Start with preview and fixture improvements before destructive features.
4. Use synthetic inputs for performance experiments.
