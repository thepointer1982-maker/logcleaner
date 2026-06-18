# Packaging

## Purpose

This document describes how to build and validate a Nextcloud app archive for LogCleaner from the repository state.

## Commands

Build an archive:

```bash
./repo-run.sh package
```

Build and validate an archive:

```bash
./repo-run.sh package-check
```

The command creates an archive under `build/` named like:

```text
logcleaner-<version>.tar.gz
```

The version is read from `appinfo/info.xml`.

## Included runtime paths

The package script copies only runtime app paths when they exist:

- `appinfo/`
- `css/`
- `img/`
- `js/`
- `l10n/`
- `lib/`
- `templates/`
- selected release files such as `CHANGELOG.md`, `COPYING`, `LICENSE`, and `README.md`

Development-only paths such as `.github/`, `tools/`, `docs/`, `commands/`, `rules/`, `specs/`, and local build output are not copied into the staged app directory.

## Package validation

`./repo-run.sh package-check` verifies that the archive exists, contains required runtime files, and does not contain development-only paths.

## Recommended release flow

```bash
./repo-run.sh ci
./repo-run.sh release
./repo-run.sh package-check
```

Inspect the resulting archive before production rollout.
