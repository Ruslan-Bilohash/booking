<?php
require_once __DIR__ . '/_bootstrap.php';
require_once dirname(__DIR__, 2) . '/includes/license-runtime.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    bk_json_response(['ok' => false, 'error' => 'POST required'], 405);
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '', true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$action = trim((string) ($payload['action'] ?? 'sync'));
$status = bk_license_status();

if ($status['status'] !== 'licensed') {
    bk_json_response([
        'ok'      => false,
        'error'   => 'license_required',
        'message' => 'Activate a BHBOOK or BHECO license key to manage connected domains',
    ], 403);
}

if ($action === 'detach') {
    if (!bk_admin_is_owner()) {
        bk_json_response(['ok' => false, 'error' => 'forbidden'], 403);
    }
    $domain = strtolower(trim((string) ($payload['domain'] ?? '')));
    if ($domain === '') {
        bk_json_response(['ok' => false, 'error' => 'invalid_domain'], 400);
    }
    $host = bk_license_host();
    if ($domain === $host) {
        bk_json_response(['ok' => false, 'error' => 'cannot_detach_current'], 400);
    }
    $result = bk_license_unregister_site($domain);
    if (!$result['ok']) {
        bk_json_response(['ok' => false, 'error' => $result['error'] ?: 'detach_failed'], 400);
    }
    bk_json_response([
        'ok'          => true,
        'action'      => 'detach',
        'sites_count' => $result['sites_count'],
        'sites'       => $result['sites'],
        'removed'     => $domain,
    ]);
}

$reg = bk_license_register_site();
$info = bk_license_verify_current(false);
bk_json_response([
    'ok'             => $reg['ok'],
    'action'         => 'sync',
    'sites_count'    => $info['sites_count'],
    'sites'          => $info['sites'],
    'days_left'      => $info['days_left'],
    'exp_label'      => $info['exp_label'],
    'license_status' => $status['status'],
    'error'          => $reg['error'],
    'synced_at'      => gmdate('c'),
], $reg['ok'] ? 200 : 400);