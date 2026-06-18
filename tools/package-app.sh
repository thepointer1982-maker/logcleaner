#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT"

app_id="$(python3 - <<'PY'
import xml.etree.ElementTree as ET
root = ET.parse('appinfo/info.xml').getroot()
node = root.find('id')
print('' if node is None or node.text is None else node.text.strip())
PY
)"

version="$(python3 - <<'PY'
import xml.etree.ElementTree as ET
root = ET.parse('appinfo/info.xml').getroot()
node = root.find('version')
print('' if node is None or node.text is None else node.text.strip())
PY
)"

if [[ "$app_id" != "logcleaner" ]]; then
  echo "Unexpected app id: $app_id" >&2
  exit 1
fi

if [[ -z "$version" ]]; then
  echo "Cannot package without app version" >&2
  exit 1
fi

build_root="build/package"
stage="$build_root/$app_id"
archive="build/${app_id}-${version}.tar.gz"

rm -rf "$build_root" "$archive"
mkdir -p "$stage"

copy_if_exists() {
  local path="$1"
  if [[ -e "$path" ]]; then
    mkdir -p "$(dirname "$stage/$path")"
    cp -R "$path" "$stage/$path"
  fi
}

for path in appinfo css img js l10n lib templates CHANGELOG.md COPYING LICENSE README.md; do
  copy_if_exists "$path"
done

mkdir -p build

tar -C "$build_root" -czf "$archive" "$app_id"

echo "Created $archive"
