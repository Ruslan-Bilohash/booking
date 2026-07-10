#!/usr/bin/env bash
# 30-day demo ZIP for GitHub Release / Packages (MySQL install wizard).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
VER="${1:-dev}"
OUT="dist/booking-cms-demo-30d-${VER}.zip"
mkdir -p dist
rm -f "$OUT"
zip -rq "$OUT" . \
  -x ".git/*" \
  -x "screenshot/*" \
  -x "dist/*" \
  -x "*.zip" \
  -x "scripts/deploy.config.local.ps1" \
  -x "**/mail-config.php" \
  -x "**/settings-local.php" \
  -x "**/.env" \
  -x "**/.env.*"
echo "Created $OUT ($(du -h "$OUT" | cut -f1))"