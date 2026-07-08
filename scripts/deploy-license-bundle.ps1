# Deploy Booking CMS license stack to Hostinger production.
param([switch]$Verify)

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent

$Files = @(
    'includes/license-runtime.php',
    'includes/cms-license.php',
    'includes/shop-license.php',
    'includes/subscription-links.php',
    'includes/admin-auth.php',
    'admin/init.php',
    'admin/license.php',
    'admin/includes/license-admin-panel.php',
    'admin/includes/license-sites-manager.php',
    'admin/includes/layout.php',
    'admin/api/_bootstrap.php',
    'admin/api/license-sites.php',
    'admin/api/license-status.php',
    'assets/js/admin-license-sites.js',
    'assets/js/admin-license-panel.js',
    'assets/css/admin.css',
    'lang/en.php',
    'lang/uk.php'
)

& (Join-Path $PSScriptRoot 'deploy-to-hostinger.ps1') -Files $Files
if ($Verify) {
    $urls = @(
        'https://bilohash.com/booking/admin/license.php',
        'https://bilohash.com/booking/admin/login.php'
    )
    foreach ($u in $urls) {
        try {
            $r = Invoke-WebRequest -Uri $u -UseBasicParsing -TimeoutSec 20
            Write-Host "OK $($r.StatusCode) $u"
        } catch {
            Write-Warning "FAIL $u : $_"
        }
    }
}