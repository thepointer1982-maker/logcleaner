# Validation rules

Use one command as the normal project check:

```bash
./repo-run.sh ci
```

Focused checks:

```bash
./repo-run.sh lint
./repo-run.sh routes
./repo-run.sh json
./repo-run.sh metadata
```

Required expectations:

- PHP source files must pass syntax checks.
- Route entries must match existing controller methods.
- Localization catalogs must parse as JSON.
- `appinfo/info.xml` must keep app id `logcleaner`, namespace `LogCleaner`, a non-empty version, and Nextcloud compatibility range `31..33`.
- CI must use the same validation path as local development.
