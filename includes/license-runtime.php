<?php
/**
 * Booking CMS license runtime — 30-day trial, activation via bilohash.com/license.php keys.
 */
declare(strict_types=1);

const BK_LICENSE_TRIAL_DAYS = 30;
const BK_LICENSE_PRODUCT = 'booking';

function bk_license_verify_url(): string
{
    if (function_exists('cms_license_verify_url')) {
        return cms_license_verify_url();
    }
    return 'https://bilohash.com/api/cms-license-verify.php';
}

function bk_license_register_url(): string
{
    if (function_exists('cms_license_register_url')) {
        return cms_license_register_url();
    }
    return 'https://bilohash.com/api/cms-license-register.php';
}

function bk_license_unregister_url(): string
{
    if (function_exists('cms_license_unregister_url')) {
        return cms_license_unregister_url();
    }
    return 'https://bilohash.com/api/cms-license-unregister.php';
}

function bk_license_state_path(): string
{
    return dirname(__DIR__) . '/data/license.state.json';
}

/** @return array<string, mixed> */
function bk_license_state(): array
{
    static $cache = null;
    if (is_array($cache)) {
        return $cache;
    }
    $path = bk_license_state_path();
    $defaults = [
        'installed_at' => gmdate('c'),
        'trial_days'   => BK_LICENSE_TRIAL_DAYS,
        'license_key'  => '',
        'activated_at' => '',
        'license_exp'  => 0,
        'license_domain' => '',
        'status'       => 'trial',
    ];
    if (!is_file($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @file_put_contents($path, json_encode($defaults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n", LOCK_EX);
        $cache = $defaults;
        return $cache;
    }
    $raw = json_decode((string) file_get_contents($path), true);
    $cache = is_array($raw) ? array_merge($defaults, $raw) : $defaults;
    return $cache;
}

function bk_license_save_state(array $state): bool
{
    $path = bk_license_state_path();
    $dir = dirname($path);
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $json = json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    return file_put_contents($path, $json . "\n", LOCK_EX) !== false;
}

function bk_license_host(): string
{
    $host = strtolower(trim((string) ($_SERVER['HTTP_HOST'] ?? '')));
    return preg_replace('/:\d+$/', '', $host) ?: '';
}

/** @param list<array<string,mixed>> $sites */
function bk_license_normalize_sites_list(array $sites, ?string $host = null): array
{
    $host = strtolower(trim($host ?? bk_license_host()));
    $out = [];
    $seen = [];
    foreach ($sites as $site) {
        if (!is_array($site)) {
            continue;
        }
        $domain = strtolower(trim((string) ($site['domain'] ?? '')));
        if ($domain === '' || isset($seen[$domain])) {
            continue;
        }
        $seen[$domain] = true;
        $out[] = [
            'domain'    => $domain,
            'last_seen' => (string) ($site['last_seen'] ?? ''),
            'version'   => (string) ($site['version'] ?? ''),
            'current'   => $domain === $host,
        ];
    }
    if ($host !== '' && !isset($seen[$host])) {
        $out[] = [
            'domain'    => $host,
            'last_seen' => gmdate('c'),
            'version'   => '',
            'current'   => true,
        ];
    }
    usort($out, static fn(array $a, array $b): int => strcmp((string) $a['domain'], (string) $b['domain']));
    return $out;
}

/** @param array<string, mixed> $state */
function bk_license_registry_stale(array $state): bool
{
    $syncedAt = strtotime((string) ($state['sites_synced_at'] ?? '')) ?: 0;
    if ($syncedAt <= 0) {
        return true;
    }
    if ((time() - $syncedAt) > 1800) {
        return true;
    }
    $host = bk_license_host();
    if ($host === '') {
        return false;
    }
    $sites = is_array($state['connected_sites'] ?? null) ? $state['connected_sites'] : [];
    foreach ($sites as $site) {
        if (is_array($site) && strtolower((string) ($site['domain'] ?? '')) === $host) {
            return false;
        }
    }
    return true;
}

function bk_license_sync_registry_if_needed(bool $force = false): void
{
    $status = bk_license_status();
    if ($status['status'] !== 'licensed') {
        return;
    }
    $state = bk_license_state();
    $key = trim((string) ($state['license_key'] ?? ''));
    if ($key === '') {
        return;
    }
    if (!$force && !bk_license_registry_stale($state)) {
        return;
    }
    bk_license_register_site($key);
}

/** @return array{status:string,trial_days_left:int,licensed:bool,expired:bool,message:string} */
function bk_license_status(): array
{
    $state = bk_license_state();
    if (($state['status'] ?? '') === 'licensed' && trim((string) ($state['license_key'] ?? '')) !== '') {
        $exp = (int) ($state['license_exp'] ?? 0);
        if ($exp > 0 && $exp < time()) {
            return [
                'status'           => 'expired',
                'trial_days_left'  => 0,
                'licensed'         => false,
                'expired'          => true,
                'message'          => 'License key expired',
            ];
        }
        return [
            'status'           => 'licensed',
            'trial_days_left'  => 0,
            'licensed'         => true,
            'expired'          => false,
            'message'          => '',
        ];
    }

    $installed = strtotime((string) ($state['installed_at'] ?? '')) ?: time();
    $trialDays = max(1, (int) ($state['trial_days'] ?? BK_LICENSE_TRIAL_DAYS));
    $ends = $installed + ($trialDays * 86400);
    $left = (int) max(0, ceil(($ends - time()) / 86400));

    if ($left > 0) {
        return [
            'status'           => 'trial',
            'trial_days_left'  => $left,
            'licensed'         => false,
            'expired'          => false,
            'message'          => '',
        ];
    }

    return [
        'status'           => 'expired',
        'trial_days_left'  => 0,
        'licensed'         => false,
        'expired'          => true,
        'message'          => 'Trial period ended',
    ];
}

function bk_license_is_active(): bool
{
    $s = bk_license_status();
    return $s['status'] === 'trial' || $s['status'] === 'licensed';
}

/** @return array{ok:bool,error:string}> */
function bk_license_activate(string $key): array
{
    $key = trim($key);
    if ($key === '') {
        return ['ok' => false, 'error' => 'Enter license key'];
    }

    $local = bk_license_verify_local($key);
    if (!$local['ok']) {
        $remote = bk_license_verify_remote($key);
        if (!$remote['ok']) {
            return ['ok' => false, 'error' => $remote['error'] ?: $local['error']];
        }
        $local = $remote;
    }

    $state = bk_license_state();
    $state['status'] = 'licensed';
    $state['license_key'] = $key;
    $state['activated_at'] = gmdate('c');
    $state['license_exp'] = (int) ($local['exp'] ?? 0);
    $state['license_domain'] = (string) ($local['domain'] ?? '');
    if (!bk_license_save_state($state)) {
        return ['ok' => false, 'error' => 'Could not save license state'];
    }
    bk_license_register_site($key);
    return ['ok' => true, 'error' => ''];
}

function bk_license_key_fingerprint(string $key): string
{
    $key = strtoupper(trim($key));
    $parts = explode('.', $key);
    $body = $parts[1] ?? $key;
    return substr(hash('sha256', $body), 0, 16);
}

/**
 * @return array{ok:bool,sites_count:int,sites:list,exp:int,error:string}
 */
function bk_license_register_site(?string $key = null): array
{
    require_once __DIR__ . '/version.php';
    $state = bk_license_state();
    $key = trim($key ?? (string) ($state['license_key'] ?? ''));
    if ($key === '') {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'exp' => 0, 'error' => 'No license key'];
    }
    $payload = json_encode([
        'key'     => $key,
        'domain'  => bk_license_host(),
        'product' => BK_LICENSE_PRODUCT,
        'version' => bk_version_label(),
    ], JSON_UNESCAPED_UNICODE);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);
    $raw = @file_get_contents(bk_license_register_url(), false, $ctx);
    if ($raw === false || $raw === '') {
        return ['ok' => false, 'sites_count' => 1, 'sites' => [['domain' => bk_license_host(), 'current' => true]], 'exp' => 0, 'error' => 'Registry unreachable'];
    }
    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['ok'])) {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'exp' => 0, 'error' => (string) ($data['error'] ?? 'Register failed')];
    }
    $sites = bk_license_normalize_sites_list(is_array($data['sites'] ?? null) ? $data['sites'] : []);
    $state['connected_sites'] = $sites;
    $state['sites_count'] = count($sites);
    $state['sites_synced_at'] = gmdate('c');
    if ((int) ($data['exp'] ?? 0) > 0) {
        $state['license_exp'] = (int) $data['exp'];
    }
    bk_license_save_state($state);
    return [
        'ok'          => true,
        'sites_count' => count($sites),
        'sites'       => $sites,
        'exp'         => (int) ($data['exp'] ?? 0),
        'error'       => '',
    ];
}

/**
 * @return array{ok:bool,sites_count:int,sites:list,error:string}
 */
function bk_license_unregister_site(string $domain, ?string $key = null): array
{
    $domain = strtolower(trim($domain));
    if ($domain === '') {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'error' => 'Invalid domain'];
    }
    $state = bk_license_state();
    $key = trim($key ?? (string) ($state['license_key'] ?? ''));
    if ($key === '') {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'error' => 'No license key'];
    }
    $payload = json_encode([
        'key'     => $key,
        'domain'  => $domain,
        'product' => BK_LICENSE_PRODUCT,
    ], JSON_UNESCAPED_UNICODE);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);
    $raw = @file_get_contents(bk_license_unregister_url(), false, $ctx);
    if ($raw === false || $raw === '') {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'error' => 'Registry unreachable'];
    }
    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['ok'])) {
        return ['ok' => false, 'sites_count' => 0, 'sites' => [], 'error' => (string) ($data['error'] ?? 'Unregister failed')];
    }
    $sites = bk_license_normalize_sites_list(is_array($data['sites'] ?? null) ? $data['sites'] : []);
    $state['connected_sites'] = $sites;
    $state['sites_count'] = count($sites);
    $state['sites_synced_at'] = gmdate('c');
    bk_license_save_state($state);
    return [
        'ok'          => true,
        'sites_count' => count($sites),
        'sites'       => $sites,
        'error'       => '',
    ];
}

function bk_license_format_last_seen(string $iso): string
{
    $iso = trim($iso);
    if ($iso === '') {
        return '—';
    }
    $ts = strtotime($iso);
    return $ts !== false ? gmdate('Y-m-d H:i', $ts) . ' UTC' : $iso;
}

function bk_license_mask_key(string $key): string
{
    $key = trim($key);
    if ($key === '') {
        return '';
    }
    if (strlen($key) <= 12) {
        return $key;
    }
    return substr($key, 0, 8) . '…' . substr($key, -6);
}

function bk_license_booking_admin_url(string $domain): string
{
    if (function_exists('cms_license_admin_url')) {
        return cms_license_admin_url(BK_LICENSE_PRODUCT, $domain);
    }
    $domain = trim($domain);
    return $domain === '' ? '' : 'https://' . $domain . '/booking/admin/';
}

/**
 * @return array{ok:bool,valid:bool,exp:int,exp_label:string,days_left:int,renew_soon:bool,domain:string,sites_count:int,sites:list,error:string,source:string}
 */
function bk_license_verify_current(bool $refreshRegistry = false): array
{
    $state = bk_license_state();
    $status = bk_license_status();
    $key = trim((string) ($state['license_key'] ?? ''));
    $host = bk_license_host();
    $base = [
        'ok'          => false,
        'valid'       => false,
        'exp'         => 0,
        'exp_label'   => '',
        'days_left'   => 0,
        'renew_soon'  => false,
        'domain'      => $host,
        'sites_count' => max(1, (int) ($state['sites_count'] ?? 0)),
        'sites'       => is_array($state['connected_sites'] ?? null) ? $state['connected_sites'] : [],
        'error'       => '',
        'source'      => '',
    ];

    if ($status['status'] === 'trial') {
        $base['ok'] = true;
        $base['valid'] = true;
        $base['days_left'] = (int) ($status['trial_days_left'] ?? 0);
        $base['sites_count'] = 1;
        $base['sites'] = bk_license_normalize_sites_list([['domain' => $host, 'current' => true, 'last_seen' => gmdate('c')]]);
        $base['source'] = 'trial';
        return $base;
    }

    if ($key === '') {
        $base['error'] = $status['message'] ?: 'No license key';
        return $base;
    }

    $local = bk_license_verify_local($key);
    $source = 'local';
    if (!$local['ok']) {
        $remote = bk_license_verify_remote($key);
        if (!$remote['ok']) {
            $base['error'] = $remote['error'] ?: $local['error'];
            return $base;
        }
        $local = $remote;
        $source = 'remote';
    }

    $exp = (int) ($local['exp'] ?? (int) ($state['license_exp'] ?? 0));
    $daysLeft = $exp > 0 ? (int) max(0, ceil(($exp - time()) / 86400)) : 0;
    $base['ok'] = true;
    $base['valid'] = true;
    $base['exp'] = $exp;
    $base['exp_label'] = $exp > 0 ? gmdate('Y-m-d', $exp) : '';
    $base['days_left'] = $daysLeft;
    $base['renew_soon'] = $daysLeft > 0 && $daysLeft <= 30;
    $base['domain'] = (string) ($local['domain'] ?? $host);
    $base['source'] = $source;

    if ($refreshRegistry || bk_license_registry_stale($state)) {
        $reg = bk_license_register_site($key);
        if ($reg['ok']) {
            $base['sites_count'] = $reg['sites_count'];
            $base['sites'] = $reg['sites'];
            if ($reg['exp'] > 0) {
                $base['exp'] = $reg['exp'];
                $base['days_left'] = (int) max(0, ceil(($reg['exp'] - time()) / 86400));
                $base['renew_soon'] = $base['days_left'] > 0 && $base['days_left'] <= 30;
                $base['exp_label'] = gmdate('Y-m-d', $reg['exp']);
            }
        } else {
            $base['sites'] = bk_license_normalize_sites_list($base['sites']);
            $base['sites_count'] = count($base['sites']);
        }
    } else {
        $base['sites'] = bk_license_normalize_sites_list($base['sites']);
        $base['sites_count'] = count($base['sites']);
    }

    return $base;
}

/** @return array{ok:bool,exp:int,domain:string,error:string} */
function bk_license_verify_local(string $key): array
{
    $lib = dirname(__DIR__, 2) . '/includes/cms-license.php';
    if (!is_file($lib)) {
        $lib = dirname(__DIR__, 2) . '/includes/shop-license.php';
    }
    if (!is_file($lib)) {
        $lib = __DIR__ . '/shop-license.php';
    }
    if (!is_file($lib)) {
        return ['ok' => false, 'exp' => 0, 'domain' => '', 'error' => 'License library missing'];
    }
    require_once $lib;
    if (function_exists('cms_license_parse_key')) {
        $parsed = cms_license_parse_key($key, bk_license_host(), BK_LICENSE_PRODUCT);
    } elseif (function_exists('shop_license_parse_key')) {
        $parsed = shop_license_parse_key($key, bk_license_host());
    } else {
        return ['ok' => false, 'exp' => 0, 'domain' => '', 'error' => 'License parser missing'];
    }
    if (!$parsed['ok'] || !$parsed['valid']) {
        return ['ok' => false, 'exp' => 0, 'domain' => '', 'error' => (string) ($parsed['error'] ?? 'Invalid key')];
    }
    return [
        'ok'     => true,
        'exp'    => (int) ($parsed['payload']['e'] ?? 0),
        'domain' => (string) ($parsed['payload']['d'] ?? '*'),
        'error'  => '',
    ];
}

/** @return array{ok:bool,exp:int,domain:string,error:string} */
function bk_license_verify_remote(string $key): array
{
    $payload = json_encode(['key' => $key, 'domain' => bk_license_host(), 'product' => BK_LICENSE_PRODUCT], JSON_UNESCAPED_UNICODE);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\nAccept: application/json\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);
    $raw = @file_get_contents(bk_license_verify_url(), false, $ctx);
    if ($raw === false || $raw === '') {
        return ['ok' => false, 'exp' => 0, 'domain' => '', 'error' => 'License server unreachable'];
    }
    $data = json_decode($raw, true);
    if (!is_array($data) || empty($data['ok'])) {
        return ['ok' => false, 'exp' => 0, 'domain' => '', 'error' => (string) ($data['error'] ?? 'Verification failed')];
    }
    return [
        'ok'     => true,
        'exp'    => (int) ($data['exp'] ?? 0),
        'domain' => (string) ($data['domain'] ?? '*'),
        'error'  => '',
    ];
}

/**
 * Gate CMS update checks — trial or valid licensed key required.
 *
 * @return array{allowed:bool,reason:string,message:string,status:array}
 */
function bk_license_can_check_updates(): array
{
    $status = bk_license_status();
    if ($status['status'] === 'trial') {
        return ['allowed' => true, 'reason' => '', 'message' => '', 'status' => $status];
    }
    if ($status['status'] === 'licensed') {
        $state = bk_license_state();
        $key = trim((string) ($state['license_key'] ?? ''));
        if ($key !== '') {
            $verified = bk_license_verify_local($key);
            if (!$verified['ok']) {
                $verified = bk_license_verify_remote($key);
            }
            if (!$verified['ok']) {
                return [
                    'allowed' => false,
                    'reason'  => 'license_invalid',
                    'message' => $verified['error'] ?: 'License key is no longer valid',
                    'status'  => $status,
                ];
            }
        }
        return ['allowed' => true, 'reason' => '', 'message' => '', 'status' => $status];
    }
    $message = $status['status'] === 'expired'
        ? 'Trial expired — activate a license key to check for updates'
        : ($status['message'] ?: 'License required');
    return [
        'allowed' => false,
        'reason'  => 'license_expired',
        'message' => $message,
        'status'  => $status,
    ];
}

function bk_license_require_admin(): void
{
    if (bk_license_is_active()) {
        return;
    }
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    if (str_contains($script, 'license.php') || str_contains($script, 'login.php') || str_contains($script, 'logout.php')) {
        return;
    }
    header('Location: ' . bk_admin_url('license.php'), true, 302);
    exit;
}