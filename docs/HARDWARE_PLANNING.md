# Hardware planning

## Purpose

This document explains how local machine capacity should influence LogCleaner development work.

The project does not assume direct access to any local NAS, workstation, or private network. Local capacity is used only when the maintainer manually runs commands in their own environment.

## Local profiles

### Focused

Use this profile for small systems or quick edits.

Recommended work:

- PHP syntax checks
- route validation
- metadata validation
- documentation checks
- small fixture tests

### Standard

Use this profile for normal development machines.

Recommended work:

- full `./repo-run.sh ci`
- package validation
- package manifest generation
- moderate synthetic fixtures

### Extended

Use this profile for stronger local hardware.

Recommended work:

- large synthetic fixtures
- benchmark experiments
- repeated package checks
- feature comparison runs

## Rules

- Local capacity does not approve risky changes by itself.
- Feature implementation still requires a focused spec.
- Destructive behavior must be previewed before execution.
- Experiments should use synthetic fixtures first.
- Results from local machines should be summarized manually before becoming project documentation.

## Next use

Use `php tools/generate-improvement-backlog.php` to generate the full 250-item improvement list locally, then promote only the best candidates into specs.
