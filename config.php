<?php
/**
 * Bilohash Booking — universal demo platform
 * /booking
 */
require_once __DIR__ . '/includes/version.php';

define('BK_BASE_PATH', '/booking');
define('BK_DOMAIN', 'bilohash.com');
define('BK_SITE_NAME', 'Booking CMS');
define('BK_CURRENCY', 'NOK');
define('BK_DEMO_MODE', true);

$detected = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_path = (strpos($host, BK_DOMAIN) !== false) ? BK_BASE_PATH : ($detected ?: BK_BASE_PATH);

$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
) ? 'https' : 'http';

$site_url   = rtrim($protocol . '://' . $host . $base_path, '/');
$assets_url = $base_path . '/assets';

function bk_url(string $path = ''): string
{
    global $base_path;
    return rtrim($base_path, '/') . '/' . ltrim($path, '/');
}

function bk_asset(string $file): string
{
    global $assets_url;
    return $assets_url . '/' . ltrim($file, '/');
}

function bk_price(int $amount): string
{
    return number_format($amount, 0, ',', ' ') . ' kr';
}