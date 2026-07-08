$ErrorActionPreference = 'Stop'
. (Join-Path (Split-Path $PSScriptRoot -Parent | Split-Path -Parent) 'shop\scripts\deploy.config.local.ps1')
Import-Module Posh-SSH -ErrorAction Stop
$sec = ConvertTo-SecureString $Password -AsPlainText -Force
$cred = New-Object PSCredential ($User, $sec)
$s = New-SSHSession -ComputerName $DeployHost -Port $Port -Credential $cred -AcceptKey
try {
    $cmd = @'
cd /home/u762384583/domains/bilohash.com/public_html/booking
test -f data/installed.lock && echo LOCK_OK || echo LOCK_MISSING
php -r "require 'includes/database.php'; echo bk_is_installed() ? 'MYSQL_OK' : 'MYSQL_FAIL';"
'@
    $r = Invoke-SSHCommand -SessionId $s.SessionId -Command $cmd -TimeOut 30
    $r.Output
} finally {
    Remove-SSHSession -SessionId $s.SessionId | Out-Null
}