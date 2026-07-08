# Booking CMS — 30-day demo package
$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$version = '1.3.0'
if (Test-Path (Join-Path $root 'includes\version.php')) {
    $vContent = Get-Content (Join-Path $root 'includes\version.php') -Raw
    if ($vContent -match "define\('BK_VERSION',\s*'([^']+)'\)") {
        $version = $Matches[1]
    }
}
$stamp = Get-Date -Format 'yyyyMMdd-HHmm'
$outVersioned = Join-Path $root ("booking-demo-30d-v{0}-{1}.zip" -f $version, $stamp)
$outAlias = Join-Path $root 'booking.zip'

& (Join-Path $PSScriptRoot 'build-install.ps1') | Out-Null

foreach ($out in @($outVersioned, $outAlias)) {
    if (Test-Path $out) { Remove-Item $out -Force }
    Compress-Archive -Path (Join-Path $root 'install\*') -DestinationPath $out -CompressionLevel Optimal
    Write-Host "Created: $out"
}

$dlDir = Join-Path $root 'downloads'
if (-not (Test-Path $dlDir)) { New-Item -ItemType Directory -Path $dlDir -Force | Out-Null }
Copy-Item $outVersioned (Join-Path $dlDir (Split-Path $outVersioned -Leaf)) -Force
Write-Host "Staged in downloads/"