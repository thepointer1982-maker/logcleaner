#!/usr/bin/env bash
set -euo pipefail

required_files=(
  "AGENTS.md"
  "CLAUDE.md"
  "SECURITY.md"
  "docs/ARCHITECTURE.md"
  "commands/review.md"
  "commands/onboard.md"
  "rules/security.md"
  "rules/validation.md"
  "repo-run.sh"
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

echo "Documentation invariants OK."
