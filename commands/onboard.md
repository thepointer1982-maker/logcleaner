# onboard

Goal: make a new maintainer or coding agent productive on LogCleaner.

Steps:

1. Read `AGENTS.md`.
2. Read `CLAUDE.md` for the project map.
3. Inspect `SECURITY.md` before touching controllers, routes, cron, or dashboard widgets.
4. Run `./repo-run.sh help` to see available validation commands.
5. Run `./repo-run.sh ci` before making changes.
6. For route or controller changes, inspect `appinfo/routes.php` and `lib/Controller/*.php` together.
7. For destructive or mutating behavior, verify admin-only behavior and route parameter validation.
8. After each connector write, re-fetch the changed file before declaring the change complete.

Expected ready state:

- The app metadata still identifies `logcleaner`.
- The route validator passes.
- PHP syntax checks pass.
- Localization JSON is valid.
- Security-sensitive operations remain protected.
