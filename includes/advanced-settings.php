<?php
declare(strict_types=1);

function bk_advanced_defaults(): array
{
    return [
        'maintenance_mode'     => false,
        'maintenance_message'=> '',
        'cookie_consent'       => true,
        'custom_head_code'     => '',
        'custom_footer_code'   => '',
        'enabled_langs'        => ['no', 'en', 'sv', 'uk', 'ru'],
    ];
}

function bk_advanced_merge(array $settings): array
{
    $merged = array_merge(bk_advanced_defaults(), $settings);
    if (!is_array($merged['enabled_langs'] ?? null)) {
        $merged['enabled_langs'] = bk_advanced_defaults()['enabled_langs'];
    }
    return $merged;
}

function bk_advanced_apply_post(array $post, array $settings): array
{
    $settings = bk_advanced_merge($settings);
    $settings['maintenance_mode'] = !empty($post['maintenance_mode']);
    $settings['maintenance_message'] = trim((string) ($post['maintenance_message'] ?? ''));
    $settings['cookie_consent'] = !empty($post['cookie_consent']);
    $settings['custom_head_code'] = trim((string) ($post['custom_head_code'] ?? ''));
    $settings['custom_footer_code'] = trim((string) ($post['custom_footer_code'] ?? ''));
    $langs = $post['enabled_langs'] ?? [];
    if (!is_array($langs)) {
        $langs = [];
    }
    $valid = array_keys(bk_langs());
    $settings['enabled_langs'] = array_values(array_intersect($valid, $langs));
    if ($settings['enabled_langs'] === []) {
        $settings['enabled_langs'] = ['no', 'en'];
    }
    return $settings;
}

function bk_maybe_maintenance(): void
{
    if (bk_is_install_script()) {
        return;
    }
    $path = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (str_contains($path, '/admin/')) {
        return;
    }
    require_once __DIR__ . '/storage.php';
    $s = bk_load_settings();
    if (empty($s['maintenance_mode'])) {
        return;
    }
    http_response_code(503);
    $msg = trim((string) ($s['maintenance_message'] ?? ''));
    if ($msg === '') {
        $msg = 'Site is under maintenance. Please try again later.';
    }
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Maintenance</title></head><body style="font-family:sans-serif;text-align:center;padding:48px"><h1>Maintenance</h1><p>' . htmlspecialchars($msg) . '</p></body></html>';
    exit;
}