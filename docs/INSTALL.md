# Installation readiness

## Purpose

This document describes how to install a validated LogCleaner package manually after the repository checks have passed.

The assistant cannot install the app on a private server. Installation is a local administrator action.

## Required artifact

Use the archive produced by:

```bash
./repo-run.sh package-manifest
```

Expected files:

- `build/logcleaner-<version>.tar.gz`
- `build/logcleaner-<version>.tar.gz.sha256`
- `build/logcleaner-<version>.contents.txt`

## Manual installation outline

1. Run the full workflow:

   ```bash
   ./repo-run.sh from-start
   ```

2. Review the contents list:

   ```bash
   cat build/logcleaner-<version>.contents.txt
   ```

3. Verify the checksum file against the archive.

4. Back up the existing app installation and relevant Nextcloud configuration.

5. Extract the archive into the local Nextcloud apps location using the local administrator account.

6. Enable or upgrade the app using the normal Nextcloud administrator workflow.

7. Open the app as a Nextcloud administrator and perform a read-only smoke test first.

## Smoke test

- app page loads for an administrator
- non-administrator access remains blocked
- log metadata loads without crashing
- empty or missing log file handling remains safe
- no cleanup action is run before a preview or manual confirmation

## Rollback

Keep the previous app directory or previous package available until the new package is verified.
