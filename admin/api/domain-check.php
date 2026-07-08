<?php
require_once __DIR__ . '/_bootstrap.php';

$rootLib = dirname(__DIR__, 3) . '/includes/bh-cms-domain-hostinger.php';
if (is_file($rootLib)) {
    require_once $rootLib;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    bk_json_response(['ok' => false, 'error' => 'POST required'], 405);
}

require_once dirname(__DIR__, 2) . '/includes/license-runtime.php';

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '', true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$domain = bh_cms_domain_normalize((string) ($payload['domain'] ?? ''));
$product = bh_cms_domain_normalize_product((string) ($payload['product'] ?? 'booking'));

if ($domain === '') {
    $domain = bh_cms_domain_normalize(bk_license_host());
}

$result = bh_cms_domain_check($domain, $product);
if (!$result['ok']) {
    bk_json_response(['ok' => false, 'error' => $result['error'] ?: 'invalid_domain'], 400);
}

$lang = trim((string) ($payload['lang'] ?? $_GET['lang'] ?? 'en'));
$result['links'] = bh_cms_domain_hostinger_links($lang, $product);
bk_json_response(['ok' => true] + $result);