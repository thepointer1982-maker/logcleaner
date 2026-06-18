# Spec workflow

## Purpose

The spec workflow ranks approved work items before implementation. It is a planning tool only: it does not execute agents, change files, or consume external model resources.

## Files

- `tools/plan-specs.php` scans approved `*.spec.md` files.
- `spec-priorities.csv.example` documents optional priority overrides.
- `repo-run.sh specs` runs the planner.

## Spec status

Specs are considered by the planner only when they contain:

```yaml
status: approved
```

Draft, done, and failed specs are ignored.

## Priority inputs

The planner can use three inputs:

1. Inline priority in the spec:

```yaml
priority: high
```

Supported values: `low`, `medium`, `high`, `critical`, or a number from `1` to `20`.

2. Optional CSV overrides copied from `spec-priorities.csv.example` to `spec-priorities.csv`.

3. Heuristic complexity from the number of `scope_in` entries.

## Output

The planner prints a table:

```text
score priority complexity profile open_deps path
```

Higher score means earlier attention. `open_deps` shows unresolved approved dependencies.

## Recommended use

```bash
./repo-run.sh specs
```

Use the output to choose the next manual change. Do not wire this planner to automatic code execution until the route, security, and validation guardrails stay green over several real changes.
