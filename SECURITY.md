# Security Policy

## Scope

LogCleaner is an administrative Nextcloud app for reading, filtering, and modifying the server log file. Treat all endpoints that mutate app config or log content as administrative security boundaries.

## Supported line

Security fixes are currently tracked on the `1.3.x` line. The current repair line is `1.3.6`.

## Security invariants

- The app is intended for administrators.
- Log deletion, duplicate deletion, app deletion, level deletion, loglevel updates, and settings updates are sensitive operations.
- Route parameters must be validated before use.
- Logfile paths must be checked before reading or writing.
- Invalid or malformed JSON log lines must not crash controllers, widgets, or cron jobs.
- Sensitive log content should not be copied into server logs during error handling.

## Current hardening status

Implemented hardening includes:

- PHP syntax checks in GitHub Actions.
- Localization JSON validation in GitHub Actions.
- Removal of duplicate and unimplemented routes.
- Safer app default handling.
- Loglevel validation for values outside `0..4`.
- Defensive helper handling for log reading, line deletion, and JSON parsing.
- Dashboard widget guards for missing log files and anonymous sessions.
- Cron error isolation and use of the injected app config service.

## Follow-up audit area

The remaining security-sensitive area is HTTP method and CSRF hardening for destructive or mutating routes in `appinfo/routes.php`. These routes currently preserve frontend compatibility. Before changing verbs, verify the frontend bundle calls and update the UI together with the route definitions.

Preferred direction:

- Keep read-only routes as `GET`.
- Move destructive or mutating routes to `POST`.
- Keep CSRF protection enabled for mutating routes.
- Keep administrative routes admin-only.

## Reporting

Do not publish sensitive log contents, tokens, user data, or full production paths in public issues. Report security-relevant bugs with a minimal reproduction and redacted log excerpts.
