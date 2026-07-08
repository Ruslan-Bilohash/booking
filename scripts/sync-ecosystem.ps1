# Copy shared Bilohash ecosystem includes into Booking CMS install package.
$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$bilohash = Split-Path $root -Parent
$ecoSrc = Join-Path $bilohash 'includes'
$targets = @(
    (Join-Path $root 'includes'),
    (Join-Path $root 'install\includes')
)
$files = @('cms-license.php', 'bh-mail.php', 'ecosystem-i18n.php', 'ecosystem-defs.php')
foreach ($dst in $targets) {
    if (-not (Test-Path $dst)) { New-Item -ItemType Directory -Path $dst -Force | Out-Null }
    foreach ($f in $files) {
        $src = Join-Path $ecoSrc $f
        if (Test-Path $src) {
            Copy-Item $src (Join-Path $dst $f) -Force
        }
    }
}