$urls = @(
    @{ Name = 'demo'; Url = 'https://bilohash.com/booking/?lang=uk'; Checks = @('bk-billing-strip', 'wordpress.html', [char]0x0434 + 'емо 30') },
    @{ Name = 'site'; Url = 'https://bilohash.com/booking/site/?lang=uk'; Checks = @('bks-billing-strip', 'bks-pricing', 'wordpress.html') },
    @{ Name = 'order'; Url = 'https://bilohash.com/booking/order.php?lang=uk'; Checks = @('bk-billing-strip', 'cta_license', 'wordpress.html') }
)
foreach ($u in $urls) {
    Write-Host "=== $($u.Name) ==="
    $html = (Invoke-WebRequest -Uri $u.Url -UseBasicParsing).Content
    foreach ($c in $u.Checks) {
        $found = $html.Contains($c)
        Write-Host "  $c : $found"
    }
}