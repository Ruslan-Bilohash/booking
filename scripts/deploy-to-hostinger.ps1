# Deploy Booking CMS files to Hostinger production (bilohash.com/booking/).
param(
    [string[]]$Files = @(),
    [switch]$LangOnly,
    [switch]$SubscriptionBundle
)

$ErrorActionPreference = 'Stop'
$root = Split-Path $PSScriptRoot -Parent
$shopConfig = 'C:\bilohash\shop\scripts\deploy.config.local.ps1'
if (-not (Test-Path $shopConfig)) {
    Write-Host "Missing $shopConfig - copy deploy.config.example.ps1 from shop."
    exit 1
}
. $shopConfig
$RemoteRoot = '/home/u762384583/domains/bilohash.com/public_html/booking'

if ($LangOnly) {
    $Files = @(
        'lang/en.php','lang/no.php','lang/uk.php','lang/ru.php','lang/sv.php',
        'site/lang/en.php','site/lang/no.php','site/lang/uk.php','site/lang/ru.php','site/lang/sv.php','site/lang/lt.php'
    )
}

if ($SubscriptionBundle) {
    $Files = @(
        'includes/subscription-links.php',
        'includes/billing-pricing.php',
        'includes/header.php',
        'includes/site-settings.php',
        'site/includes/header.php',
        'site/includes/seo.php',
        'order.php',
        'site/index.php',
        'assets/css/style.css',
        'site/assets/css/site.css',
        'lang/en.php','lang/no.php','lang/uk.php','lang/ru.php','lang/sv.php',
        'site/lang/en.php','site/lang/no.php','site/lang/uk.php','site/lang/ru.php','site/lang/sv.php','site/lang/lt.php'
    )
}

if (-not $Files -or $Files.Count -eq 0) {
    Write-Host 'No files. Pass -Files, -LangOnly, or -SubscriptionBundle.'
    exit 1
}

if (-not (Get-Module -ListAvailable -Name Posh-SSH)) {
    Install-Module Posh-SSH -Scope CurrentUser -Force -AllowClobber
}
Import-Module Posh-SSH -ErrorAction Stop

$keyFile = Join-Path $env:USERPROFILE '.ssh\id_ed25519'
if ($Password) {
    $secPass = ConvertTo-SecureString $Password -AsPlainText -Force
    $cred = New-Object System.Management.Automation.PSCredential ($User, $secPass)
} else {
    $cred = New-Object System.Management.Automation.PSCredential ($User, (New-Object System.Security.SecureString))
}

$scpParams = @{
    ComputerName = $DeployHost
    Port         = $Port
    Credential   = $cred
    AcceptKey    = $true
}
if (-not $Password) { $scpParams.KeyFile = $keyFile }

$session = New-SSHSession @scpParams -ErrorAction Stop
$sessionId = $session.SessionId
$ok = 0
$fail = 0

foreach ($rel in $Files) {
    $rel = $rel -replace '\\', '/'
    $src = Join-Path $root $rel
    if (-not (Test-Path $src)) {
        Write-Warning "SKIP missing local: $rel"
        $fail++
        continue
    }
    $remote = ($RemoteRoot.TrimEnd('/')) + '/' + $rel
    $remoteDir = ($remote -replace '/[^/]+$', '')
    $remoteName = Split-Path $remote -Leaf
    Invoke-SSHCommand -SessionId $sessionId -Command "mkdir -p '$remoteDir'" | Out-Null
    Set-SCPItem @scpParams -Path $src -Destination $remoteDir -NewName $remoteName
    Write-Host "OK $rel"
    $ok++
}

# Shared ecosystem pricing (parent includes/)
$ecoSrc = 'C:\bilohash\includes\ecosystem-pricing.php'
if (Test-Path $ecoSrc) {
    $ecoRemote = '/home/u762384583/domains/bilohash.com/public_html/includes'
    Invoke-SSHCommand -SessionId $sessionId -Command "mkdir -p '$ecoRemote'" | Out-Null
    Set-SCPItem @scpParams -Path $ecoSrc -Destination $ecoRemote -NewName 'ecosystem-pricing.php'
    Write-Host 'OK ../includes/ecosystem-pricing.php'
    $ok++
}

Remove-SSHSession -SessionId $sessionId | Out-Null
Write-Host "Deploy done: $ok uploaded, $fail skipped."