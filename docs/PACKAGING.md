# Packaging

## Purpose

This document describes how to build, validate, and identify a Nextcloud app archive for LogCleaner from the repository state.

## Commands

Build an archive:

```bash
./repo-run.sh package
```

Build and validate an archive:

```bash
./repo-run.sh package-check
```

Build, validate, checksum, and list archive contents:

```bash
./repo-run.sh package-manifest
```

The commands create files under `build/` named like:

```text
logcleaner-<version>.tar.gz
logcleaner-<version>.tar.gz.sha256
logcleaner-<version>.contents.txt
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

`./repo-run.sh package-manifest` runs package validation first, then writes a SHA-256 checksum file and a text file with the archive contents.

## Recommended release flow

```bash
./repo-run.sh ci
./repo-run.sh release
./repo-run.sh package-manifest
```

Inspect the resulting archive, checksum, and contents list before production rollout.
