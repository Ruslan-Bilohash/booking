# Booking CMS — sync development tree into commercial install/ package
# Usage: powershell -File scripts/build-install.ps1

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$dest = Join-Path $root 'install'

if (Test-Path $dest) { Remove-Item $dest -Recurse -Force }
New-Item -ItemType Directory -Path $dest -Force | Out-Null

$dirs = @('admin', 'api', 'assets', 'includes', 'lang', 'site', 'data', 'screen')
$files = @(
    'init.php', 'config.php', 'index.php', 'search.php', 'property.php', 'book.php',
    'contact.php', 'order.php', 'solutions.php', 'vertical.php', '404.php',
    'sitemap.php', 'schema.sql', 'migrate-to-mysql.php', 'install.php',
    'robots.txt', 'llms.txt', 'readme.md', 'readme.txt',
    'README-uk.md', 'README-no.md', 'README-ru.md'
)

Write-Host "Booking CMS build-install: $root -> $dest"

foreach ($d in $dirs) {
    $srcDir = Join-Path $root $d
    if (-not (Test-Path $srcDir)) { continue }
    $dstDir = Join-Path $dest $d
    New-Item -ItemType Directory -Path $dstDir -Force | Out-Null
    Get-ChildItem $srcDir -Force | ForEach-Object {
        if ($d -eq 'data' -and $_.Name -in @('db.config.php', 'admin.config.php', 'installed.lock')) { return }
        if ($_.PSIsContainer) {
            Copy-Item $_.FullName (Join-Path $dstDir $_.Name) -Recurse -Force
        } else {
            Copy-Item $_.FullName (Join-Path $dstDir $_.Name) -Force
        }
    }
}

foreach ($f in $files) {
    $src = Join-Path $root $f
    if (Test-Path $src) {
        Copy-Item $src (Join-Path $dest $f) -Force
    }
}

$extraIncludes = @('license-runtime.php', 'cms-license.php', 'demo-package.php', 'mysql-migrate.php', 'database.php')
foreach ($f in $extraIncludes) {
    $src = Join-Path $root "includes\$f"
    if (Test-Path $src) {
        Copy-Item $src (Join-Path $dest "includes\$f") -Force
    }
}

& (Join-Path $PSScriptRoot 'sync-ecosystem.ps1') | Out-Null

# Generic config for client installs (no bilohash hardcode)
$configSrc = @"
<?php
require_once __DIR__ . '/includes/version.php';
define('BK_BASE_PATH', '');
define('BK_DOMAIN', '');
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', false);
`$detected = rtrim(str_replace('\\', '/', dirname(`$_SERVER['SCRIPT_NAME'] ?? '')), '/');
`$host = `$_SERVER['HTTP_HOST'] ?? 'localhost';
`$base_path = `$detected ?: '';
`$protocol = ((!empty(`$_SERVER['HTTPS']) && `$_SERVER['HTTPS'] !== 'off') || ((`$_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')) ? 'https' : 'http';
`$site_url = rtrim(`$protocol . '://' . `$host . `$base_path, '/');
`$assets_url = (`$base_path !== '' ? `$base_path : '') . '/assets';
function bk_url(string `$path = ''): string { global `$base_path; return rtrim(`$base_path, '/') . '/' . ltrim(`$path, '/'); }
function bk_asset(string `$file): string { global `$assets_url; return `$assets_url . '/' . ltrim(`$file, '/'); }
function bk_price(int `$amount): string { return number_format(`$amount, 0, ',', ' ') . ' kr'; }
"@
[IO.File]::WriteAllText((Join-Path $dest 'config.php'), $configSrc)

Write-Host 'Done. Install package at install/'