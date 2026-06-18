#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

bash tools/check-package.sh

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

sha256sum "$archive" > "$checksum"
tar -tzf "$archive" > "$contents"

echo "Created $checksum"
echo "Created $contents"
