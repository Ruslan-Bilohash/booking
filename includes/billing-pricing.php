<?php

require_once __DIR__ . '/subscription-links.php';

$__bkEcoPricing = dirname(__DIR__, 2) . '/includes/ecosystem-pricing.php';
if (is_file($__bkEcoPricing)) {
    require_once $__bkEcoPricing;
}

function bk_billing_ecosystem_lang(string $lang): string
{
    return match ($lang) {
        'no' => 'no',
        'uk', 'ua' => 'ua',
        default => 'en',
    };
}

function bk_billing_ecosystem_inc(): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $path = dirname(__DIR__, 2) . '/includes/ecosystem-pricing.php';
    if (is_file($path)) {
        require_once $path;
    }
    $done = true;
}

function bk_billing_subscription_tagline(string $lang): string
{
    bk_billing_ecosystem_inc();
    $eco = bk_billing_ecosystem_lang($lang);
    if (function_exists('ecosystem_pricing_tagline')) {
        return ecosystem_pricing_tagline($eco);
    }

    return '49 kr/mo or 249 kr/mo · 1 domain · one-click install';
}

function bk_billing_banner_text(array $labels, string $lang): string
{
    $tagline = bk_billing_subscription_tagline($lang);
    $tpl = (string) ($labels['text'] ?? '{tagline}');

    return strtr($tpl, ['{tagline}' => $tagline]);
}

function bk_billing_banner_resolve_link(string $link): string
{
    if ($link === '' || $link === 'subscription' || $link === 'wordpress') {
        return bk_subscription_url();
    }
    if (str_starts_with($link, 'http://') || str_starts_with($link, 'https://')) {
        return $link;
    }
    if ($link !== '' && function_exists('bk_url')) {
        return bk_url($link);
    }

    return bk_subscription_url();
}

function bk_billing_render_demo_banner(array $t, string $lang): void
{
    $labels = is_array($t['billing'] ?? null) ? $t['billing'] : [];
    if (($labels['enabled'] ?? true) === false) {
        return;
    }
    $text = bk_billing_banner_text($labels, $lang);
    $href = bk_billing_banner_resolve_link((string) ($labels['link'] ?? 'subscription'));
    $cta = trim((string) ($labels['cta'] ?? ''));
    ?>
<a href="<?= htmlspecialchars($href) ?>" class="bk-billing-strip" role="status" <?= bk_subscription_external_attrs() ?>>
    <i class="fas fa-sparkles" aria-hidden="true"></i>
    <span><?= htmlspecialchars($text) ?></span>
    <?php if ($cta !== ''): ?>
    <span class="bk-billing-strip-cta"><?= htmlspecialchars($cta) ?> <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
    <?php endif; ?>
</a>
    <?php
}

function bk_billing_render_site_banner(array $t, string $lang): void
{
    $labels = is_array($t['billing'] ?? null) ? $t['billing'] : [];
    if (($labels['enabled'] ?? true) === false) {
        return;
    }
    $text = bk_billing_banner_text($labels, $lang);
    $href = bk_billing_banner_resolve_link((string) ($labels['link'] ?? 'subscription'));
    $cta = trim((string) ($labels['cta'] ?? ''));
    ?>
<a href="<?= htmlspecialchars($href) ?>" class="bks-billing-strip" role="status" <?= bk_subscription_external_attrs() ?>>
    <div class="bks-container bks-billing-strip-inner">
        <i class="fas fa-sparkles" aria-hidden="true"></i>
        <span><?= htmlspecialchars($text) ?></span>
        <?php if ($cta !== ''): ?>
        <span class="bks-billing-strip-cta"><?= htmlspecialchars($cta) ?></span>
        <?php endif; ?>
    </div>
</a>
    <?php
}