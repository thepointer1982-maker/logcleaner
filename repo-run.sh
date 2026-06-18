#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

usage() {
  cat <<'EOF'
Usage: ./repo-run.sh <command>

Commands:
  help             Show this help
  check            Run the complete project validation suite
  ci               Alias for check
  lint             Run PHP syntax checks
  routes           Validate route targets
  json             Validate localization JSON files
  metadata         Validate app metadata XML invariants
  docs             Validate documentation invariants
  release          Validate release readiness
  package          Build a Nextcloud app archive
  package-check    Build and validate a Nextcloud app archive
  package-manifest Build, validate, checksum, and list package contents
  install-check    Validate local installation readiness artifacts
  backlog          Generate 250 improvement candidates
  improvements     Rank next improvement candidates
  specs            Plan approved specs without executing them
  from-start       Run the complete from-start workflow
EOF
}

command="${1:-help}"

case "$command" in
  help|-h|--help)
    usage
    ;;
  check|ci)
    bash tools/check-all.sh
    ;;
  lint)
    find . -type f -name '*.php' -print0 | xargs -0 -n 1 php -l
    ;;
  routes)
    php tools/validate-routes.php
    ;;
  json)
    find l10n -type f -name '*.json' -print0 | xargs -0 -n 1 python3 -m json.tool > /dev/null
    ;;
  metadata)
    python3 - <<'PY'
from pathlib import Path
import sys
import xml.etree.ElementTree as ET

info_path = Path('appinfo/info.xml')
try:
    root = ET.parse(info_path).getroot()
except Exception as exc:
    print(f'appinfo/info.xml is not valid XML: {exc}', file=sys.stderr)
    sys.exit(1)

def text(tag: str) -> str:
    node = root.find(tag)
    return '' if node is None or node.text is None else node.text.strip()

if text('id') != 'logcleaner':
    print('appinfo/info.xml: invalid app id', file=sys.stderr)
    sys.exit(1)
if text('namespace') != 'LogCleaner':
    print('appinfo/info.xml: invalid namespace', file=sys.stderr)
    sys.exit(1)
if not text('version'):
    print('appinfo/info.xml: missing version', file=sys.stderr)
    sys.exit(1)

nextcloud = root.find('./dependencies/nextcloud')
if nextcloud is None:
    print('appinfo/info.xml: missing dependencies/nextcloud entry', file=sys.stderr)
    sys.exit(1)
if nextcloud.attrib.get('min-version') != '31' or nextcloud.attrib.get('max-version') != '33':
    print('appinfo/info.xml: unexpected Nextcloud dependency range', file=sys.stderr)
    sys.exit(1)

print('appinfo/info.xml OK')
PY
    ;;
  docs)
    bash tools/check-docs.sh
    ;;
  release)
    bash tools/check-release.sh
    ;;
  package)
    bash tools/package-app.sh
    ;;
  package-check)
    bash tools/check-package.sh
    ;;
  package-manifest)
    bash tools/package-manifest.sh
    ;;
  install-check)
    bash tools/check-install-readiness.sh
    ;;
  backlog)
    php tools/generate-improvement-backlog.php
    ;;
  improvements)
    php tools/plan-improvements.php
    ;;
  specs)
    php tools/plan-specs.php
    ;;
  from-start)
    bash tools/run-from-start.sh
    ;;
  *)
    echo "Unknown command: $command" >&2
    usage >&2
    exit 1
    ;;
esac
