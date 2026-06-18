# Release checklist

## Purpose

This checklist keeps LogCleaner releases reproducible and safe.

## Before tagging

1. Run the full project check:

   ```bash
   ./repo-run.sh ci
   ```

2. Run the release readiness check:

   ```bash
   ./repo-run.sh release
   ```

3. Verify app metadata:

   - `appinfo/info.xml` has the intended `<version>`.
   - App id remains `logcleaner`.
   - Namespace remains `LogCleaner`.
   - Supported Nextcloud range remains `31..33` unless intentionally changed.

4. Verify documentation:

   - `CHANGELOG.md` contains a section for the release version.
   - `SECURITY.md` mentions the current repair line.
   - `docs/OPERATIONS.md` still matches deployment expectations.

5. Verify security-sensitive behavior:

   - Destructive and mutating operations remain administrator operations.
   - Route parameters are validated before use.
   - Logfile access handles missing, unreadable, and unwritable files.
   - Malformed log lines do not crash runtime paths.

## Packaging notes

Package only the app files needed by Nextcloud. Do not include local secrets, temporary files, development logs, or private environment files.

## After tagging

- Confirm CI is green for the tag or release commit.
- Confirm the release archive does not contain `.env`, `secrets/`, local logs, or temporary files.
- Keep a rollback path: backups or the previous working app package should remain available before production rollout.
