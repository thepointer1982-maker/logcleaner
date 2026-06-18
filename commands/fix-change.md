# fix-change

Goal: apply a small LogCleaner fix safely.

Steps:

1. Read `AGENTS.md`, `SECURITY.md`, and `CLAUDE.md`.
2. Identify the smallest file set that can fix the problem.
3. For controller or route changes, inspect `appinfo/routes.php` and the matching controller together.
4. Make one focused change.
5. Run focused checks:
   - PHP change: `./repo-run.sh lint`
   - Route/controller change: `./repo-run.sh routes`
   - Translation change: `./repo-run.sh json`
   - Metadata change: `./repo-run.sh metadata`
6. Run the full check: `./repo-run.sh ci`.
7. Update `CHANGELOG.md` when the change affects behavior, validation, or release state.
8. Re-fetch changed files after connector writes before declaring completion.

Commit style:

- `fix(controller): validate log cleanup input`
- `fix(ci): check route targets`
- `docs(agent): update recovery instructions`
