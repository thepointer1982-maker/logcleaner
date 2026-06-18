#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

echo "Checking PHP syntax..."
find . -type f -name '*.php' -print0 | xargs -0 -n 1 php -l

echo "Validating route targets..."
php tools/validate-routes.php

echo "Validating localization JSON..."
find l10n -type f -name '*.json' -print0 | xargs -0 -n 1 python3 -m json.tool > /dev/null

echo "Validating app metadata XML..."
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

expected = {
    'id': 'logcleaner',
    'namespace': 'LogCleaner',
}
for tag, value in expected.items():
    actual = text(tag)
    if actual != value:
        print(f'appinfo/info.xml: expected <{tag}>{value}</{tag}>, got {actual!r}', file=sys.stderr)
        sys.exit(1)

version = text('version')
if not version:
    print('appinfo/info.xml: missing <version>', file=sys.stderr)
    sys.exit(1)

nextcloud = root.find('./dependencies/nextcloud')
if nextcloud is None:
    print('appinfo/info.xml: missing dependencies/nextcloud entry', file=sys.stderr)
    sys.exit(1)

if nextcloud.attrib.get('min-version') != '31' or nextcloud.attrib.get('max-version') != '33':
    print('appinfo/info.xml: unexpected Nextcloud dependency range', file=sys.stderr)
    sys.exit(1)

print(f'appinfo/info.xml OK for logcleaner {version}')
PY

echo "Checking documentation invariants..."
bash tools/check-docs.sh

echo "All checks passed."
