#!/usr/bin/env bash
set -euo pipefail

required_files=(
  "appinfo/info.xml"
  "appinfo/routes.php"
  "CHANGELOG.md"
  "SECURITY.md"
  "docs/RELEASE.md"
  "lib/AppInfo/Application.php"
  "lib/Controller/SettingsController.php"
  "lib/Controller/Helper.php"
  "lib/Cron/Cleanup.php"
)

missing=0
for file in "${required_files[@]}"; do
  if [[ ! -s "$file" ]]; then
    echo "Missing or empty release file: $file" >&2
    missing=1
  fi
done

if [[ "$missing" -ne 0 ]]; then
  exit 1
fi

version="$(python3 - <<'PY'
import xml.etree.ElementTree as ET
root = ET.parse('appinfo/info.xml').getroot()
node = root.find('version')
print('' if node is None or node.text is None else node.text.strip())
PY
)"

if [[ -z "$version" ]]; then
  echo "Cannot determine app version from appinfo/info.xml" >&2
  exit 1
fi

if ! grep -q "## $version" CHANGELOG.md; then
  echo "CHANGELOG.md must contain a section for version $version" >&2
  exit 1
fi

if ! grep -q "current repair line is \`$version\`" SECURITY.md; then
  echo "SECURITY.md must mention current repair line $version" >&2
  exit 1
fi

if ! grep -q "<id>logcleaner</id>" appinfo/info.xml; then
  echo "appinfo/info.xml must keep id logcleaner" >&2
  exit 1
fi

if ! grep -q "<namespace>LogCleaner</namespace>" appinfo/info.xml; then
  echo "appinfo/info.xml must keep namespace LogCleaner" >&2
  exit 1
fi

if ! grep -q "<nextcloud min-version=\"31\" max-version=\"33\"" appinfo/info.xml; then
  echo "appinfo/info.xml must keep Nextcloud compatibility range 31..33" >&2
  exit 1
fi

echo "Release readiness OK for LogCleaner $version."
