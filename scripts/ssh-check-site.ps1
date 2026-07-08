. 'C:\bilohash\shop\scripts\deploy.config.local.ps1'
Import-Module Posh-SSH
$cred = New-Object PSCredential ($User, (ConvertTo-SecureString $Password -AsPlainText -Force))
$p = @{ ComputerName = $DeployHost; Port = $Port; Credential = $cred; AcceptKey = $true }
$s = New-SSHSession @p
$cmds = @(
    'grep -n billing /home/u762384583/domains/bilohash.com/public_html/booking/site/includes/header.php',
    'grep -n pricing /home/u762384583/domains/bilohash.com/public_html/booking/site/index.php | head -5',
    'test -f /home/u762384583/domains/bilohash.com/public_html/booking/includes/billing-pricing.php && echo billing-ok',
    'test -f /home/u762384583/domains/bilohash.com/public_html/includes/ecosystem-pricing.php && echo eco-ok',
    'curl -s https://bilohash.com/booking/site/?lang=uk | grep -o "bks-billing\|bks-pricing\|wordpress" | head -5'
)
foreach ($c in $cmds) {
    Write-Host "--- $c"
    $r = Invoke-SSHCommand -SessionId $s.SessionId -Command $c
    Write-Host $r.Output
}
Remove-SSHSession -SessionId $s.SessionId | Out-Null