<?php
declare(strict_types=1);

function bk_analytics_defaults(): array
{
    return [
        'tracking_gtag_id'       => '',
        'tracking_meta_pixel'    => '',
        'tracking_tiktok_pixel'  => '',
        'google_ads_enabled'     => false,
        'google_ads_id'          => '',
        'google_ads_conversion_label' => '',
        'google_ads_remarketing' => true,
        'google_ads_track_purchase' => true,
        'google_ads_track_begin_checkout' => true,
    ];
}

function bk_analytics_merge(array $settings): array
{
    return array_merge(bk_analytics_defaults(), $settings);
}

function bk_analytics_apply_post(array $post, array $settings): array
{
    $settings = bk_analytics_merge($settings);
    $settings['tracking_gtag_id'] = trim((string) ($post['tracking_gtag_id'] ?? ''));
    $settings['tracking_meta_pixel'] = trim((string) ($post['tracking_meta_pixel'] ?? ''));
    $settings['tracking_tiktok_pixel'] = trim((string) ($post['tracking_tiktok_pixel'] ?? ''));
    $settings['google_ads_enabled'] = !empty($post['google_ads_enabled']);
    $settings['google_ads_id'] = strtoupper(trim((string) ($post['google_ads_id'] ?? '')));
    $settings['google_ads_conversion_label'] = trim((string) ($post['google_ads_conversion_label'] ?? ''));
    $settings['google_ads_remarketing'] = !empty($post['google_ads_remarketing']);
    $settings['google_ads_track_purchase'] = !empty($post['google_ads_track_purchase']);
    $settings['google_ads_track_begin_checkout'] = !empty($post['google_ads_track_begin_checkout']);
    return $settings;
}

function bk_render_tracking_snippets(?array $settings = null): void
{
    $settings = bk_analytics_merge(is_array($settings) ? $settings : []);
    $gtag = trim((string) ($settings['tracking_gtag_id'] ?? ''));
    if ($gtag !== '' && preg_match('/^G-[A-Z0-9]+$/i', $gtag)) {
        echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id=" . htmlspecialchars($gtag) . "\"></script>";
        echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . htmlspecialchars($gtag) . "');</script>";
    }
    $pixel = trim((string) ($settings['tracking_meta_pixel'] ?? ''));
    if ($pixel !== '' && ctype_digit($pixel)) {
        echo "<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" . htmlspecialchars($pixel) . "');fbq('track','PageView');</script>";
    }
}