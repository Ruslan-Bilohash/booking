<?php
require_once dirname(__DIR__) . '/init.php';
require_once dirname(__DIR__) . '/includes/admin-auth.php';
require_once dirname(__DIR__) . '/includes/storage.php';
require_once dirname(__DIR__) . '/includes/site-settings.php';

$ta = $t['admin'] ?? [];
$admin_page = $admin_page ?? 'dashboard';

$guides_all = require dirname(__DIR__) . '/lang/payment-guides.php';
$ta['payments_page']['guides'] = $guides_all[$lang] ?? $guides_all['en'] ?? [];

function bk_admin_lang_url(string $code): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: bk_admin_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    if ($code === 'no') {
        unset($q['lang']);
    } else {
        $q['lang'] = $code;
    }
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}