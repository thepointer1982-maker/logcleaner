#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

bash tools/package-app.sh

version="$(python3 - <<'PY'
import xml.etree.ElementTree as ET
root = ET.parse('appinfo/info.xml').getroot()
node = root.find('version')
print('' if node is None or node.text is None else node.text.strip())
PY
)"

archive="build/logcleaner-${version}.tar.gz"

if [[ ! -s "$archive" ]]; then
  echo "Missing package archive: $archive" >&2
  exit 1
fi

contents="$(tar -tzf "$archive")"

required_entries=(
  "logcleaner/appinfo/info.xml"
  "logcleaner/appinfo/routes.php"
  "logcleaner/lib/Controller/SettingsController.php"
  "logcleaner/lib/Controller/Helper.php"
  "logcleaner/lib/Cron/Cleanup.php"
)

for entry in "${required_entries[@]}"; do
  if ! grep -qx "$entry" <<< "$contents"; then
    echo "Package is missing required entry: $entry" >&2
    exit 1
  fi
done

blocked_prefixes=(
  "logcleaner/.github/"
  "logcleaner/tools/"
  "logcleaner/docs/"
  "logcleaner/commands/"
  "logcleaner/rules/"
  "logcleaner/specs/"
  "logcleaner/build/"
)

for prefix in "${blocked_prefixes[@]}"; do
  if grep -q "^$prefix" <<< "$contents"; then
    echo "Package contains development-only path: $prefix" >&2
    exit 1
  fi
done

echo "Package archive OK: $archive"
