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
- Current repair release line: `1.3.6`

## Codex recovery status

Codex support is restored through this file and the GitHub Actions workflow in `.github/workflows/php-lint.yml`.

Current validated guardrails:
- PHP files are syntax-checked by CI with `php -l`.
- Localization JSON files are validated by CI with `python3 -m json.tool`.
- Route/controller changes must be reviewed against `appinfo/routes.php` and `lib/Controller/*.php`.
- App metadata changes must preserve app id, namespace, license, author metadata, and the supported Nextcloud range unless the task explicitly requires otherwise.

Primary agent responsibility:
- Keep this app installable, admin-only, and safe around log deletion.
- Prefer small commits that isolate one fix at a time.
- Re-fetch changed files from GitHub after every connector write before claiming completion.

## Security rules

- Treat log deletion, duplicate deletion, empty-log, level deletion, app deletion, loglevel changes, and settings changes as administrative operations.
- Validate all route parameters before using them.
- Avoid adding `NoAdminRequired` or `NoCSRFRequired` to administrative or destructive endpoints unless there is a documented reason.
- Destructive or mutating endpoints should move toward non-GET verbs with CSRF protection. If frontend compatibility blocks that change, document the blocker and keep the server-side validation strict.
- Prefer `DataResponse` payloads with scalar/array values. Do not return nested `DataResponse` objects as response values.
- When setting defaults, write the default to app config and return the scalar default value to the frontend.
- Use strict comparisons where practical and cast route parameters intentionally.
- Guard file access with `file_exists`, `is_file`, `is_readable`, and `is_writable` checks where relevant.
- Do not log sensitive log contents, user data, tokens, or full filesystem paths unless the existing UI/API already exposes them deliberately.

## Common checks before finishing

Run or reason through these checks for changed PHP files:

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

For localization changes, inspect and validate:

```bash
l10n/*.json
python3 -m json.tool l10n/de.json > /dev/null
```

If a Nextcloud dev instance is available, also verify:

```bash
occ app:enable logcleaner
occ app:check-code logcleaner
```

## Known risk areas

- Administrative routes must remain admin-only.
- Configuration default initialization must return scalar values, not response objects.
- Loglevel changes must reject values outside `0..4` and must not write invalid system config.
- Duplicate route entries should be avoided.
- Filter methods should not reference undefined variables.
- UI calls that delete or mutate logs should be reviewed carefully for method, CSRF, and admin-only behavior.
- Minified frontend bundles should not be hand-edited unless no source is available and the change is small, reviewed, and tested.
