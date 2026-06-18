#!/usr/bin/env bash
set -euo pipefail

required_files=(
  "AGENTS.md"
  "CLAUDE.md"
  "SECURITY.md"
  "docs/ARCHITECTURE.md"
  "docs/SPEC_WORKFLOW.md"
  "docs/OPERATIONS.md"
  "docs/RELEASE.md"
  "docs/PACKAGING.md"
  "docs/FEATURE_EXPLORATION.md"
  "docs/HARDWARE_PLANNING.md"
  "commands/review.md"
  "commands/onboard.md"
  "commands/fix-change.md"
  "rules/security.md"
  "rules/validation.md"
  "repo-run.sh"
  "tools/plan-specs.php"
  "tools/generate-improvement-backlog.php"
  "tools/check-release.sh"
  "tools/package-app.sh"
  "tools/check-package.sh"
  "tools/package-manifest.sh"
)

missing=0
for file in "${required_files[@]}"; do
  if [[ ! -s "$file" ]]; then
    echo "Missing or empty required project file: $file" >&2
    missing=1
  fi
done

if [[ "$missing" -ne 0 ]]; then
  exit 1
fi

if ! grep -q "./repo-run.sh ci" AGENTS.md; then
  echo "AGENTS.md must document ./repo-run.sh ci" >&2
  exit 1
fi

if ! grep -q "AGENTS.md" CLAUDE.md; then
  echo "CLAUDE.md must point to AGENTS.md" >&2
  exit 1
fi

if ! grep -q "LogCleaner" docs/ARCHITECTURE.md; then
  echo "docs/ARCHITECTURE.md must describe LogCleaner" >&2
  exit 1
fi

if ! grep -q "repo-run.sh specs" docs/SPEC_WORKFLOW.md; then
  echo "docs/SPEC_WORKFLOW.md must document repo-run.sh specs" >&2
  exit 1
fi

if ! grep -q "Nextcloud" docs/OPERATIONS.md; then
  echo "docs/OPERATIONS.md must document Nextcloud operations" >&2
  exit 1
fi

if ! grep -q "repo-run.sh release" docs/RELEASE.md; then
  echo "docs/RELEASE.md must document repo-run.sh release" >&2
  exit 1
fi

if ! grep -q "repo-run.sh package-manifest" docs/PACKAGING.md; then
  echo "docs/PACKAGING.md must document repo-run.sh package-manifest" >&2
  exit 1
fi

if ! grep -q "Feature exploration" docs/FEATURE_EXPLORATION.md; then
  echo "docs/FEATURE_EXPLORATION.md must document feature exploration" >&2
  exit 1
fi

if ! grep -q "Hardware planning" docs/HARDWARE_PLANNING.md; then
  echo "docs/HARDWARE_PLANNING.md must document hardware planning" >&2
  exit 1
fi

if ! grep -q "Generated 250 improvement backlog" tools/generate-improvement-backlog.php; then
  echo "tools/generate-improvement-backlog.php must generate the 250-item backlog" >&2
  exit 1
fi

echo "Documentation invariants OK."
