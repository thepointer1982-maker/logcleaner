#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

planning_dir="build/planning"
mkdir -p "$planning_dir"

echo "== LogCleaner from-start workflow =="

echo "1/7 Documentation invariants"
bash tools/check-docs.sh

echo "2/7 Spec planning"
php tools/plan-specs.php | tee "$planning_dir/spec-plan.md"

echo "3/7 Improvement backlog"
php tools/generate-improvement-backlog.php > "$planning_dir/improvement-backlog.md"
wc -l "$planning_dir/improvement-backlog.md"

echo "4/7 Improvement ranking"
php tools/plan-improvements.php | tee "$planning_dir/improvement-plan.md"

echo "5/7 Full project checks"
bash tools/check-all.sh

echo "6/7 Release readiness"
bash tools/check-release.sh

echo "7/7 Package manifest"
bash tools/package-manifest.sh

echo "Planning artifacts written to $planning_dir"
echo "From-start workflow completed."
