# LogCleaner architecture

## Purpose

LogCleaner is a Nextcloud app for administrators. It reads the configured Nextcloud log file, displays recent or filtered entries, and provides controlled cleanup operations.

## Main components

- `appinfo/info.xml` declares the app id, namespace, version, supported Nextcloud range, navigation entry, and background job.
- `appinfo/routes.php` maps HTTP routes to controller methods.
- `lib/AppInfo/Application.php` registers dashboard widgets and navigation.
- `lib/Controller/SettingsController.php` exposes log viewing, filtering, settings, and cleanup endpoints.
- `lib/Controller/Helper.php` centralizes log file reading, line deletion, and log entry shaping.
- `lib/Cron/Cleanup.php` runs scheduled duplicate cleanup when enabled.
- `lib/Dashboard/LogCleanerWidget.php` and `lib/Dashboard/LogCleanerWidget2.php` provide dashboard integration.
- `templates/` provides the app entry containers.
- `js/` and `css/` contain built frontend assets.
- `l10n/` contains localization catalogs.
- `tools/` contains repository validation scripts.

## Data flow

1. The app reads log configuration from Nextcloud system/app config.
2. Controller or widget code asks `Helper` to load the log file.
3. Each log line is parsed defensively.
4. Parsed entries are returned to the frontend as structured response data.
5. Cleanup operations modify the log file only after resolving the configured file path and validating parameters.

## Validation flow

Use the same entry point locally and in CI:

```bash
./repo-run.sh ci
```

This runs PHP syntax checks, route target validation, localization JSON validation, and app metadata checks.

## Security boundaries

The app is intended for administrators. Log cleanup, config mutation, loglevel changes, and deletion by app or level are sensitive operations. Keep parameter validation and file access checks close to the controller/helper boundary.
