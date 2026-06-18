# Cleanup preview

## Purpose

The cleanup preview tool is a non-mutating helper for estimating how many log lines would be affected by a cleanup mode.

It is intentionally introduced before any controller or UI wiring.

## Command

```bash
./repo-run.sh preview --file=tests/fixtures/logs/basic.log --mode=duplicate
./repo-run.sh preview --file=tests/fixtures/logs/basic.log --mode=level --value=warning
./repo-run.sh preview --file=tests/fixtures/logs/basic.log --mode=app --value=files
```

## Modes

- `duplicate`: counts repeated parsed log entries after the first occurrence
- `level`: counts parsed entries with a matching `level`
- `app`: counts parsed entries with a matching `app`

## Output

The tool emits JSON with:

- `total_lines`
- `parsed_lines`
- `malformed_lines`
- `affected_lines`
- `writes_performed`

`writes_performed` must remain `0`. Preview mode must not modify the input file.

## Fixture

`tests/fixtures/logs/basic.log` is synthetic. It is safe for tests and documentation.

## Next step

Promote this helper into backend logic only after route hardening and request validation are reviewed.
