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

3. Build, validate, and identify the app package:

   ```bash
   ./repo-run.sh package-manifest
   ```

4. Verify app metadata:

   - `appinfo/info.xml` has the intended `<version>`.
   - App id remains `logcleaner`.
   - Namespace remains `LogCleaner`.
   - Supported Nextcloud range remains `31..33` unless intentionally changed.

5. Verify documentation:

   - `CHANGELOG.md` contains a section for the release version.
   - `SECURITY.md` mentions the current repair line.
   - `docs/OPERATIONS.md` still matches deployment expectations.
   - `docs/PACKAGING.md` describes the package contents, validation, checksum, and contents list.

6. Verify security-sensitive behavior:

   - Destructive and mutating operations remain administrator operations.
   - Route parameters are validated before use.
   - Logfile access handles missing, unreadable, and unwritable files.
   - Malformed log lines do not crash runtime paths.

## Packaging notes

Package only the app files needed by Nextcloud. Do not include local secrets, temporary files, development logs, or private environment files. The packaging command stages runtime app paths under `build/package/logcleaner` and writes the archive to `build/logcleaner-<version>.tar.gz`. The package-manifest command verifies required runtime entries, rejects development-only paths, and writes checksum plus contents-list files next to the archive.

## After tagging

- Confirm CI is green for the tag or release commit.
- Confirm the release archive does not contain local-only development files.
- Confirm the checksum and contents-list files match the archive you publish.
- Keep a rollback path: backups or the previous working app package should remain available before production rollout.
