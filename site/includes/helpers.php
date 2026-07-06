<?php

function bks_lang_url(string $code, bool $for_hreflang = false): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: bks_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    if ($code === 'no' && $for_hreflang) {
        unset($q['lang']);
    } else {
        $q['lang'] = $code;
    }
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}

function bks_vertical_url(string $slug, ?string $langCode = null): string
{
    global $lang;
    $lng = $langCode ?? $lang ?? 'no';
    $url = bks_demo_url($slug . '/');
    return $lng !== 'no' ? $url . '?lang=' . urlencode($lng) : $url;
}

function bks_screen_info(string $key): array
{
    global $t;
    $info = $t['screens']['items'][$key] ?? null;
    if (is_array($info) && ($info['title'] ?? '') !== '') {
        return $info;
    }
    static $en = null;
    if ($en === null) {
        $en_file = __DIR__ . '/../lang/en.php';
        $en = is_file($en_file) ? (require $en_file) : [];
    }
    $fallback = $en['screens']['items'][$key] ?? null;
    if (is_array($fallback) && ($fallback['title'] ?? '') !== '') {
        return $fallback;
    }
    return ['title' => ucwords(str_replace('_', ' ', $key)), 'desc' => ''];
}

function bks_screens(): array
{
    return [
        ['file' => 'home.svg', 'key' => 'home'],
        ['file' => 'search.svg', 'key' => 'search'],
        ['file' => 'property.svg', 'key' => 'property'],
        ['file' => 'property-amenities.svg', 'key' => 'property_amenities'],
        ['file' => 'property-reviews.svg', 'key' => 'property_reviews'],
        ['file' => 'book.svg', 'key' => 'book'],
        ['file' => 'solutions.svg', 'key' => 'solutions'],
        ['file' => 'contact.svg', 'key' => 'contact'],
        ['file' => 'admin-dashboard.svg', 'key' => 'admin_dash'],
        ['file' => 'admin-properties.svg', 'key' => 'admin_props'],
        ['file' => 'admin-bookings.svg', 'key' => 'admin_book'],
        ['file' => 'admin-reviews.svg', 'key' => 'admin_reviews'],
        ['file' => 'admin-settings.svg', 'key' => 'admin_settings'],
    ];
}