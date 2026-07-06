<?php

function bk_default_payment_providers(): array
{
    return [
        'paypal' => [
            'enabled'       => false,
            'mode'          => 'sandbox',
            'client_id'     => '',
            'client_secret' => '',
            'currency'      => 'NOK',
        ],
        'stripe' => [
            'enabled'         => false,
            'mode'            => 'test',
            'publishable_key' => '',
            'secret_key'      => '',
            'webhook_secret'  => '',
        ],
        'vipps' => [
            'enabled'          => false,
            'environment'      => 'test',
            'client_id'        => '',
            'client_secret'    => '',
            'subscription_key' => '',
            'merchant_serial'  => '',
            'callback_token'   => '',
        ],
    ];
}

function bk_default_tax_settings(): array
{
    return [
        'enabled'        => true,
        'mode'           => 'excluded',
        'rate'           => 12.0,
        'show_breakdown' => true,
        'labels'         => [
            'en' => 'VAT',
            'no' => 'MVA',
            'uk' => 'ПДВ',
            'ru' => 'НДС',
            'sv' => 'moms',
        ],
    ];
}

function bk_payment_tabs(): array
{
    return [
        'paypal' => ['icon' => 'fab fa-paypal', 'brand' => true, 'color' => '#003087'],
        'stripe' => ['icon' => 'fab fa-stripe-s', 'brand' => true, 'color' => '#635BFF'],
        'vipps'  => ['icon' => 'bk-vipps-icon', 'brand' => true, 'color' => '#FF5B24'],
    ];
}

function bk_vipps_icon_svg(): string
{
    return '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" focusable="false">'
        . '<rect width="32" height="32" rx="8" fill="currentColor"/>'
        . '<path d="M9 17.5c3.5 4 10.5 4 14 0" stroke="#fff" stroke-width="2.5" stroke-linecap="round" fill="none"/>'
        . '</svg>';
}

function bk_payment_method_icon_html(string $provider): string
{
    if ($provider === 'vipps') {
        return '<span class="bk-vipps-icon" aria-hidden="true">' . bk_vipps_icon_svg() . '</span>';
    }
    $meta = bk_payment_tabs()[$provider] ?? null;
    $icon = is_array($meta) ? ($meta['icon'] ?? 'fas fa-credit-card') : 'fas fa-credit-card';
    return '<i class="' . htmlspecialchars($icon) . '" aria-hidden="true"></i>';
}

function bk_payment_tab_valid(string $tab): bool
{
    return isset(bk_payment_tabs()[$tab]);
}

function bk_merge_nested_settings(array $defaults, array $saved): array
{
    foreach ($defaults as $key => $defaultVal) {
        if (!is_array($defaultVal)) {
            continue;
        }
        $savedVal = $saved[$key] ?? null;
        $defaults[$key] = array_merge($defaultVal, is_array($savedVal) ? $savedVal : []);
    }
    return $defaults;
}

function bk_secret_preview(string $value): string
{
    if ($value === '') {
        return '';
    }
    $len = strlen($value);
    if ($len <= 4) {
        return str_repeat('•', $len);
    }
    return str_repeat('•', min(12, $len - 4)) . substr($value, -4);
}

function bk_payment_apply_post(string $tab, array $post, array $settings): array
{
    if (!bk_payment_tab_valid($tab)) {
        return $settings;
    }

    $bool = static fn(string $key): bool => !empty($post[$key]);
    $str  = static fn(string $key): string => trim($post[$key] ?? '');

    foreach (array_keys(bk_default_payment_providers()) as $provider) {
        if (!isset($settings[$provider]) || !is_array($settings[$provider])) {
            $settings[$provider] = bk_default_payment_providers()[$provider];
        }
    }

    switch ($tab) {
        case 'paypal':
            $settings['paypal']['enabled'] = $bool('enabled');
            $settings['paypal']['mode'] = in_array($post['mode'] ?? '', ['sandbox', 'live'], true)
                ? $post['mode'] : 'sandbox';
            $settings['paypal']['client_id'] = $str('client_id');
            if ($str('client_secret') !== '') {
                $settings['paypal']['client_secret'] = $str('client_secret');
            }
            $settings['paypal']['currency'] = strtoupper(substr($str('currency') ?: 'NOK', 0, 3));
            break;

        case 'stripe':
            $settings['stripe']['enabled'] = $bool('enabled');
            $settings['stripe']['mode'] = in_array($post['mode'] ?? '', ['test', 'live'], true)
                ? $post['mode'] : 'test';
            $settings['stripe']['publishable_key'] = $str('publishable_key');
            if ($str('secret_key') !== '') {
                $settings['stripe']['secret_key'] = $str('secret_key');
            }
            if ($str('webhook_secret') !== '') {
                $settings['stripe']['webhook_secret'] = $str('webhook_secret');
            }
            break;

        case 'vipps':
            $settings['vipps']['enabled'] = $bool('enabled');
            $settings['vipps']['environment'] = in_array($post['environment'] ?? '', ['test', 'production'], true)
                ? $post['environment'] : 'test';
            $settings['vipps']['client_id'] = $str('client_id');
            if ($str('client_secret') !== '') {
                $settings['vipps']['client_secret'] = $str('client_secret');
            }
            $settings['vipps']['subscription_key'] = $str('subscription_key');
            $settings['vipps']['merchant_serial'] = $str('merchant_serial');
            if ($str('callback_token') !== '') {
                $settings['vipps']['callback_token'] = $str('callback_token');
            }
            break;
    }

    return $settings;
}

function bk_taxes_apply_post(array $post, array $settings): array
{
    $tax = $settings['taxes'] ?? bk_default_tax_settings();
    $tax['enabled'] = !empty($post['tax_enabled']);
    $tax['mode'] = ($post['tax_mode'] ?? '') === 'included' ? 'included' : 'excluded';
    $tax['show_breakdown'] = !empty($post['tax_show_breakdown']);
    $rate = (float) str_replace(',', '.', (string) ($post['tax_rate'] ?? '0'));
    $tax['rate'] = max(0, min(100, round($rate, 2)));

    $labels = is_array($tax['labels'] ?? null) ? $tax['labels'] : [];
    foreach (['en', 'no', 'uk', 'ru', 'sv'] as $code) {
        $key = 'tax_label_' . $code;
        if (array_key_exists($key, $post)) {
            $labels[$code] = trim((string) $post[$key]);
        }
    }
    $tax['labels'] = array_merge(bk_default_tax_settings()['labels'], $labels);
    $settings['taxes'] = $tax;

    return $settings;
}

function bk_payment_is_configured(string $provider, ?array $settings = null): bool
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        require_once __DIR__ . '/storage.php';
        $settings = bk_load_settings();
    }
    $cfg = $settings[$provider] ?? [];

    return match ($provider) {
        'paypal' => !empty($cfg['client_id']) && !empty($cfg['client_secret']),
        'stripe' => !empty($cfg['publishable_key']) && !empty($cfg['secret_key']),
        'vipps'  => !empty($cfg['client_id']) && !empty($cfg['client_secret'])
            && !empty($cfg['subscription_key']) && !empty($cfg['merchant_serial']),
        default => false,
    };
}

function bk_payment_provider_enabled(string $provider, ?array $settings = null): bool
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return false;
    }
    $cfg = $settings[$provider] ?? [];
    return !empty($cfg['enabled']);
}

function bk_enabled_payment_methods(?array $settings = null): array
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return [];
    }
    $out = [];
    foreach (array_keys(bk_payment_tabs()) as $provider) {
        if (bk_payment_provider_enabled($provider, $settings)) {
            $out[] = $provider;
        }
    }
    return $out;
}

/** Public booking demo: always show PayPal, Stripe, Vipps — no real charge. */
function bk_demo_payment_methods(): array
{
    return array_keys(bk_payment_tabs());
}

function bk_tax_settings(?array $settings = null): array
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        require_once __DIR__ . '/storage.php';
        $settings = bk_load_settings();
    }
    return array_merge(bk_default_tax_settings(), is_array($settings['taxes'] ?? null) ? $settings['taxes'] : []);
}

function bk_tax_label(?string $lang = null, ?array $settings = null): string
{
    $lang ??= $GLOBALS['lang'] ?? 'en';
    $tax = bk_tax_settings($settings);
    $labels = $tax['labels'] ?? [];
    return (string) ($labels[$lang] ?? $labels['en'] ?? 'VAT');
}

function bk_booking_price_breakdown(int $subtotal, ?array $settings = null): array
{
    $tax = bk_tax_settings($settings);
    $enabled = !empty($tax['enabled']);
    $rate = max(0, min(100, (float) ($tax['rate'] ?? 0)));
    $mode = ($tax['mode'] ?? 'excluded') === 'included' ? 'included' : 'excluded';
    $showBreakdown = !empty($tax['show_breakdown']);

    if (!$enabled || $rate <= 0) {
        return [
            'subtotal'       => $subtotal,
            'tax'            => 0,
            'total'          => $subtotal,
            'tax_enabled'    => false,
            'tax_rate'       => 0.0,
            'tax_mode'       => $mode,
            'show_breakdown' => false,
        ];
    }

    if ($mode === 'included') {
        $taxAmount = (int) round($subtotal * $rate / (100 + $rate));
        return [
            'subtotal'       => $subtotal - $taxAmount,
            'tax'            => $taxAmount,
            'total'          => $subtotal,
            'tax_enabled'    => true,
            'tax_rate'       => $rate,
            'tax_mode'       => $mode,
            'show_breakdown' => $showBreakdown,
        ];
    }

    $taxAmount = (int) round($subtotal * $rate / 100);
    return [
        'subtotal'       => $subtotal,
        'tax'            => $taxAmount,
        'total'          => $subtotal + $taxAmount,
        'tax_enabled'    => true,
        'tax_rate'       => $rate,
        'tax_mode'       => $mode,
        'show_breakdown' => $showBreakdown,
    ];
}

function bk_payment_method_label(string $provider, ?array $t = null): string
{
    $t ??= $GLOBALS['t'] ?? [];
    $labels = $t['payment']['methods'] ?? [];
    return (string) ($labels[$provider] ?? ucfirst($provider));
}

function bk_tax_display_note(?string $lang = null, ?array $settings = null): string
{
    $lang ??= $GLOBALS['lang'] ?? 'en';
    $t = $GLOBALS['t'] ?? [];
    $tax = bk_tax_settings($settings);
    if (empty($tax['enabled'])) {
        return (string) ($t['property']['taxes_exempt'] ?? $t['property']['taxes'] ?? '');
    }
    $label = bk_tax_label($lang, $settings);
    $rate = (float) ($tax['rate'] ?? 0);
    $mode = ($tax['mode'] ?? 'excluded') === 'included' ? 'included' : 'excluded';
    if ($rate <= 0) {
        return sprintf((string) ($t['property']['taxes_incl'] ?? 'Includes %s'), $label);
    }
    if ($mode === 'included') {
        return sprintf((string) ($t['property']['taxes_included_rate'] ?? 'Includes %s (%.1f%%)'), $label, $rate);
    }
    return sprintf((string) ($t['property']['taxes_excluded_rate'] ?? '+%s %.1f%% at checkout'), $label, $rate);
}