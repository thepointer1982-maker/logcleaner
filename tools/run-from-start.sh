#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "== LogCleaner from-start workflow =="

echo "1/7 Documentation invariants"
bash tools/check-docs.sh

echo "2/7 Spec planning"
php tools/plan-specs.php

echo "3/7 Improvement backlog"
php tools/generate-improvement-backlog.php > build-improvements.tmp
wc -l build-improvements.tmp
rm -f build-improvements.tmp

echo "4/7 Improvement ranking"
php tools/plan-improvements.php

echo "5/7 Full project checks"
bash tools/check-all.sh

echo "6/7 Release readiness"
bash tools/check-release.sh

echo "7/7 Package manifest"
bash tools/package-manifest.sh

echo "From-start workflow completed."
