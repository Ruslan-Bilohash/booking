<?php
declare(strict_types=1);

function bk_smtp_defaults(): array
{
    return [
        'smtp_enabled'       => false,
        'smtp_host'          => '',
        'smtp_port'          => 465,
        'smtp_encryption'    => 'ssl',
        'smtp_username'      => '',
        'smtp_password'      => '',
        'smtp_from_email'    => '',
        'smtp_from_name'     => 'Booking CMS',
        'booking_notify_email' => '',
    ];
}

function bk_smtp_merge(array $settings): array
{
    $merged = array_merge(bk_smtp_defaults(), $settings);
    $merged['smtp_enabled'] = !empty($merged['smtp_enabled']);
    $merged['smtp_port'] = max(1, min(65535, (int) ($merged['smtp_port'] ?? 465)));
    $enc = strtolower((string) ($merged['smtp_encryption'] ?? 'ssl'));
    $merged['smtp_encryption'] = in_array($enc, ['ssl', 'tls', 'none'], true) ? $enc : 'ssl';
    return $merged;
}

function bk_smtp_apply_post(array $post, array $settings): array
{
    $settings = bk_smtp_merge($settings);
    $settings['smtp_enabled'] = !empty($post['smtp_enabled']);
    $settings['smtp_host'] = trim((string) ($post['smtp_host'] ?? ''));
    $settings['smtp_port'] = max(1, min(65535, (int) ($post['smtp_port'] ?? 465)));
    $enc = strtolower(trim((string) ($post['smtp_encryption'] ?? 'ssl')));
    $settings['smtp_encryption'] = in_array($enc, ['ssl', 'tls', 'none'], true) ? $enc : 'ssl';
    $settings['smtp_username'] = trim((string) ($post['smtp_username'] ?? ''));
    $pass = trim((string) ($post['smtp_password'] ?? ''));
    if ($pass !== '') {
        $settings['smtp_password'] = $pass;
    }
    $settings['smtp_from_email'] = trim((string) ($post['smtp_from_email'] ?? ''));
    $settings['smtp_from_name'] = trim((string) ($post['smtp_from_name'] ?? 'Booking CMS'));
    $settings['booking_notify_email'] = trim((string) ($post['booking_notify_email'] ?? ''));
    return $settings;
}