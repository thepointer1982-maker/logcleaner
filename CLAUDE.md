# LogCleaner

## One-line summary

LogCleaner is a Nextcloud administrator app for reading, filtering, and cleaning the server log file.

## Source of truth

Use `AGENTS.md` for repository-wide coding rules, security guardrails, and validation requirements.

## Directory roles

- `appinfo/` - Nextcloud app metadata and route definitions.
- `lib/AppInfo/` - Application bootstrap and navigation registration.
- `lib/Controller/` - HTTP controllers and log-processing helpers.
- `lib/Cron/` - Background jobs.
- `lib/Dashboard/` - Dashboard widgets.
- `templates/` - Server-rendered entry templates.
- `js/` and `css/` - Built frontend assets.
- `l10n/` - Translation catalogs.
- `tools/` - Repository validation scripts.
- `.github/workflows/` - CI checks.

## Development commands

- Full check: `./repo-run.sh ci`
- PHP lint only: `./repo-run.sh lint`
- Route validation: `./repo-run.sh routes`
- Translation JSON validation: `./repo-run.sh json`
- Metadata validation: `./repo-run.sh metadata`

## Critical rules

- Keep destructive and mutating operations admin-only.
- Validate route parameters before using them.
- Treat log lines as untrusted input.
- Do not hand-edit minified bundles unless there is no source alternative and the change is small and reviewed.
- Re-fetch changed files from GitHub after connector writes before claiming completion.
