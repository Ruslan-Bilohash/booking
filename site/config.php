<?php
/**
 * BiloBook CMS — product marketing site
 * /booking/site
 */
require_once dirname(__DIR__) . '/includes/version.php';

define('BKS_BASE_PATH', '/booking/site');
define('BKS_PRODUCT_NAME', 'Booking CMS');
define('BKS_PARENT_PATH', '/booking');

$detected = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base_path = (strpos($host, 'bilohash.com') !== false) ? BKS_BASE_PATH : ($detected ?: BKS_BASE_PATH);

$protocol = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
) ? 'https' : 'http';

$site_url   = rtrim($protocol . '://' . $host . $base_path, '/');
$assets_url = $base_path . '/assets';
$screen_url = BKS_PARENT_PATH . '/screen';

function bks_url(string $path = ''): string
{
    global $base_path;
    return rtrim($base_path, '/') . '/' . ltrim($path, '/');
}

function bks_asset(string $file): string
{
    global $assets_url;
    return $assets_url . '/' . ltrim($file, '/');
}

function bks_screen(string $file): string
{
    global $screen_url;
    return $screen_url . '/' . ltrim($file, '/');
}

function bks_demo_url(string $path = '', ?string $langCode = null): string
{
    global $host, $protocol, $lang;
    $lng = $langCode ?? $lang ?? 'no';
    $url = rtrim($protocol . '://' . $host . BKS_PARENT_PATH, '/') . '/' . ltrim($path, '/');
    if ($lng === 'no') {
        return $url;
    }
    $sep = str_contains($path, '?') || str_contains($url, '?') ? '&' : '?';
    return $url . $sep . 'lang=' . urlencode($lng);
}