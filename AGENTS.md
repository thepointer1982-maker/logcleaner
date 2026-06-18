# Repository Instructions for Codex

Scope: this file applies to the entire repository.

## Project context

This repository contains the Nextcloud app `logcleaner`. The app is an administrator tool for reading, filtering, and cleaning the Nextcloud log file.

Important project facts:
- App id: `logcleaner`
- PHP namespace: `OCA\LogCleaner`
- Main route configuration: `appinfo/routes.php`
- App metadata: `appinfo/info.xml`
- Supported Nextcloud versions in metadata: min `31`, max `33`

## Working rules

- Keep changes small and focused. Do not rewrite unrelated files.
- Preserve existing public route names unless the task explicitly requires a breaking change.
- Do not change the app id, namespace, author metadata, license, or supported Nextcloud version range unless explicitly requested.
- Treat log deletion, duplicate deletion, empty-log, and level/app deletion actions as destructive operations. Validate route parameters before using them.
- Avoid adding `NoAdminRequired` or `NoCSRFRequired` to destructive or administrative endpoints unless there is a documented reason.
- Prefer `DataResponse` payloads with scalar/array values. Do not return nested `DataResponse` objects as response values.
- When setting defaults, write the default to app config and return the scalar default value to the frontend.
- Use strict comparisons where practical and cast route parameters intentionally.
- Guard file access with `file_exists`, readable/writable checks where relevant, and clear failure responses.
- Do not log sensitive log contents, user data, tokens, or full filesystem paths unless the existing UI/API already exposes them deliberately.

## Common checks before finishing

Run or reason through these checks for changed files:

```bash
php -l path/to/changed.php
```

For route/controller changes, inspect:

```bash
appinfo/routes.php
lib/Controller/*.php
```

For metadata changes, inspect:

```bash
appinfo/info.xml
```

If a Nextcloud dev instance is available, also verify:

```bash
occ app:enable logcleaner
occ app:check-code logcleaner
```

## Known risk areas

- Configuration default initialization must return scalar values, not response objects.
- Loglevel changes must reject values outside `0..4` and must not write invalid system config.
- Duplicate route entries should be avoided.
- Filter methods should not reference undefined variables.
- UI calls that delete or mutate logs should be reviewed carefully for method, CSRF, and admin-only behavior.
