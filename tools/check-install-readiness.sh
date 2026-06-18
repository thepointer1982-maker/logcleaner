#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

bash tools/package-manifest.sh

version="$(python3 - <<'PY'
import xml.etree.ElementTree as ET
root = ET.parse('appinfo/info.xml').getroot()
node = root.find('version')
print('' if node is None or node.text is None else node.text.strip())
PY
)"

archive="build/logcleaner-${version}.tar.gz"
checksum="${archive}.sha256"
contents="build/logcleaner-${version}.contents.txt"

for file in "$archive" "$checksum" "$contents" docs/INSTALL.md; do
  if [[ ! -s "$file" ]]; then
    echo "Missing install readiness file: $file" >&2
    exit 1
  fi
done

sha256sum -c "$checksum"

if ! grep -qx "logcleaner/appinfo/info.xml" "$contents"; then
  echo "Contents list must include app metadata" >&2
  exit 1
fi

if ! grep -qx "logcleaner/lib/Controller/SettingsController.php" "$contents"; then
  echo "Contents list must include settings controller" >&2
  exit 1
fi

if ! grep -q "./repo-run.sh from-start" docs/INSTALL.md; then
  echo "docs/INSTALL.md must reference the from-start workflow" >&2
  exit 1
fi

echo "Install readiness OK for LogCleaner ${version}."
