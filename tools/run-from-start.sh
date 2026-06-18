#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

planning_dir="build/planning"
mkdir -p "$planning_dir"

echo "== LogCleaner from-start workflow =="

echo "1/8 Documentation invariants"
bash tools/check-docs.sh

echo "2/8 Spec planning"
php tools/plan-specs.php | tee "$planning_dir/spec-plan.md"

echo "3/8 Improvement backlog"
php tools/generate-improvement-backlog.php > "$planning_dir/improvement-backlog.md"
wc -l "$planning_dir/improvement-backlog.md"

echo "4/8 Improvement ranking"
php tools/plan-improvements.php | tee "$planning_dir/improvement-plan.md"

echo "5/8 Full project checks"
bash tools/check-all.sh

echo "6/8 Release readiness"
bash tools/check-release.sh

echo "7/8 Package manifest"
bash tools/package-manifest.sh

echo "8/8 Installation readiness"
bash tools/check-install-readiness.sh

echo "Planning artifacts written to $planning_dir"
echo "From-start workflow completed."
