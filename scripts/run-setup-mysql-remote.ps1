$ErrorActionPreference = 'Stop'
. (Join-Path (Split-Path $PSScriptRoot -Parent | Split-Path -Parent) 'shop\scripts\deploy.config.local.ps1')
Import-Module Posh-SSH -ErrorAction Stop
$sec = ConvertTo-SecureString $Password -AsPlainText -Force
$cred = New-Object PSCredential ($User, $sec)
$s = New-SSHSession -ComputerName $DeployHost -Port $Port -Credential $cred -AcceptKey
try {
    $cmd = 'cd /home/u762384583/domains/bilohash.com/public_html/booking && php scripts/setup-mysql.php 2>&1'
    $r = Invoke-SSHCommand -SessionId $s.SessionId -Command $cmd -TimeOut 90
    $r.Output
    exit [int]($r.ExitStatus)
} finally {
    Remove-SSHSession -SessionId $s.SessionId | Out-Null
}