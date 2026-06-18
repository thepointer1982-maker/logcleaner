# GitHub Agent Instructions

Use the repository-level guidance in `AGENTS.md` as the source of truth for this project.

Critical defaults:

- Keep LogCleaner installable as a Nextcloud app.
- Keep administrative and destructive log operations protected.
- Validate route definitions with `php tools/validate-routes.php` after route or controller edits.
- Run `php -l` on changed PHP files.
- Validate localization JSON after translation changes.
- Do not hand-edit minified frontend bundles unless there is no source alternative and the change is small and reviewed.

Security-sensitive files:

- `appinfo/routes.php`
- `lib/Controller/SettingsController.php`
- `lib/Controller/Helper.php`
- `lib/Cron/Cleanup.php`
- `.github/workflows/php-lint.yml`
- `SECURITY.md`
