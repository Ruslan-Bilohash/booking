<?php

require_once __DIR__ . '/database.php';

function bk_data_path(string $file): string
{
    return __DIR__ . '/../data/' . $file;
}

function bk_properties_file(): string
{
    return bk_data_path('properties.json');
}

function bk_bookings_file(): string
{
    return bk_data_path('bookings.json');
}

function bk_settings_file(): string
{
    return bk_data_path('settings.json');
}

function bk_default_settings(): array
{
    require_once __DIR__ . '/site-settings.php';
    return bk_settings_defaults();
}

function bk_load_settings(): array
{
    require_once __DIR__ . '/site-settings.php';
    if (bk_uses_mysql()) {
        $data = bk_db_load_settings();
        if ($data === []) {
            $defaults = bk_default_settings();
            bk_save_settings($defaults);
            return $defaults;
        }
        return bk_merge_settings($data);
    }
    $file = bk_settings_file();
    if (!is_file($file)) {
        $defaults = bk_default_settings();
        bk_save_settings($defaults);
        return $defaults;
    }
    $data = json_decode(file_get_contents($file) ?: '{}', true);
    return is_array($data) ? bk_merge_settings($data) : bk_default_settings();
}

function bk_save_settings(array $settings): bool
{
    require_once __DIR__ . '/site-settings.php';
    $merged = bk_merge_settings($settings);
    if (bk_uses_mysql()) {
        return bk_db_save_settings($merged);
    }
    $json = json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(bk_settings_file(), $json, LOCK_EX) !== false;
}

function bk_reviews_file(): string
{
    return bk_data_path('reviews.json');
}

function bk_ensure_reviews_json(): void
{
    $file = bk_reviews_file();
    $seed = bk_reviews_seed();
    if (!is_file($file)) {
        bk_save_reviews($seed);
        bk_sync_property_review_stats();
        return;
    }
    $existing = json_decode(file_get_contents($file) ?: '[]', true);
    if (!is_array($existing) || $existing === []) {
        bk_save_reviews($seed);
        bk_sync_property_review_stats();
        return;
    }
    $ids = array_column($existing, 'id');
    $added = false;
    foreach ($seed as $row) {
        $rid = $row['id'] ?? '';
        if ($rid !== '' && !in_array($rid, $ids, true)) {
            $existing[] = $row;
            $added = true;
        }
    }
    if ($added) {
        bk_save_reviews($existing);
        bk_sync_property_review_stats();
    }
}

function bk_reviews_seed(): array
{
    static $cache = null;
    if ($cache === null) {
        $seed = require __DIR__ . '/../data/reviews.php';
        $cache = is_array($seed) ? $seed : [];
    }
    return $cache;
}

function bk_load_reviews_raw(): array
{
    if (bk_uses_mysql()) {
        $data = bk_db_load_reviews();
        if ($data !== []) {
            return $data;
        }
        $seed = bk_reviews_seed();
        if ($seed !== []) {
            bk_save_reviews($seed);
        }
        return $seed;
    }
    bk_ensure_reviews_json();
    $file = bk_reviews_file();
    if (is_file($file)) {
        $data = json_decode(file_get_contents($file) ?: '[]', true);
        if (is_array($data) && $data !== []) {
            return $data;
        }
    }
    return bk_reviews_seed();
}

function bk_save_reviews(array $list): bool
{
    if (bk_uses_mysql()) {
        return bk_db_save_reviews(array_values($list));
    }
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(bk_reviews_file(), $json, LOCK_EX) !== false;
}

function bk_reviews_for_property(string $propertyId, bool $approvedOnly = true): array
{
    $list = array_filter(bk_load_reviews_raw(), static function (array $r) use ($propertyId, $approvedOnly): bool {
        if (($r['property_id'] ?? '') !== $propertyId) {
            return false;
        }
        return !$approvedOnly || !empty($r['approved']);
    });
    usort($list, static function (array $a, array $b): int {
        return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
    });
    return array_values($list);
}

function bk_add_review(array $entry): bool
{
    $list = bk_load_reviews_raw();
    $entry['id'] = $entry['id'] ?? ('rev-' . bin2hex(random_bytes(4)));
    $entry['created_at'] = $entry['created_at'] ?? date('c');
    $entry['approved'] = !empty($entry['approved']);
    $entry['source'] = $entry['source'] ?? 'guest';
    $list[] = $entry;
    if (!bk_save_reviews($list)) {
        return false;
    }
    if (!empty($entry['approved'])) {
        bk_sync_property_review_stats($entry['property_id'] ?? '');
    }
    return true;
}

function bk_update_review(string $id, array $patch): bool
{
    $list = bk_load_reviews_raw();
    $found = false;
    $propertyId = '';
    foreach ($list as $i => $row) {
        if (($row['id'] ?? '') !== $id) {
            continue;
        }
        $propertyId = $row['property_id'] ?? '';
        $list[$i] = array_merge($row, $patch);
        $found = true;
        break;
    }
    if (!$found || !bk_save_reviews($list)) {
        return false;
    }
    bk_sync_property_review_stats($propertyId);
    return true;
}

function bk_delete_review(string $id): bool
{
    $list = bk_load_reviews_raw();
    $propertyId = '';
    $out = [];
    foreach ($list as $row) {
        if (($row['id'] ?? '') === $id) {
            $propertyId = $row['property_id'] ?? '';
            continue;
        }
        $out[] = $row;
    }
    if (count($out) === count($list) || !bk_save_reviews($out)) {
        return false;
    }
    bk_sync_property_review_stats($propertyId);
    return true;
}

function bk_sync_property_review_stats(?string $propertyId = null): void
{
    $props = bk_load_properties_raw();
    $reviews = bk_load_reviews_raw();
    $changed = false;
    foreach ($props as $i => $p) {
        $pid = $p['id'] ?? '';
        if ($pid === '' || ($propertyId !== null && $propertyId !== '' && $pid !== $propertyId)) {
            continue;
        }
        $approved = array_values(array_filter($reviews, static fn(array $r): bool => ($r['property_id'] ?? '') === $pid && !empty($r['approved'])));
        $count = count($approved);
        if ($count > 0) {
            $avg = array_sum(array_map(static fn(array $r): float => (float) ($r['rating'] ?? 0), $approved)) / $count;
            $props[$i]['rating'] = round($avg, 1);
            $demoTotal = (int) ($p['reviews'] ?? 0);
            if ($demoTotal < $count) {
                $props[$i]['reviews'] = $count;
            }
            $changed = true;
        }
    }
    if ($changed) {
        bk_save_properties($props);
    }
}

function bk_ensure_properties_json(): void
{
    $json = bk_properties_file();
    if (!is_file($json)) {
        $defaults = require __DIR__ . '/../data/properties.php';
        foreach ($defaults as &$p) {
            $p['active'] = true;
        }
        unset($p);
        bk_save_properties($defaults);
    }
}

function bk_sync_seed_property_extras(): void
{
    $seed = require __DIR__ . '/../data/properties.php';
    $byId = [];
    foreach ($seed as $p) {
        $byId[$p['id'] ?? ''] = $p;
    }
    $file = bk_properties_file();
    if (!is_file($file)) {
        return;
    }
    $list = json_decode(file_get_contents($file) ?: '[]', true);
    if (!is_array($list)) {
        return;
    }
    $changed = false;
    foreach ($list as $i => $p) {
        $id = $p['id'] ?? '';
        $s = $byId[$id] ?? null;
        if (!$s) {
            continue;
        }
        foreach (['desc_long', 'highlights'] as $key) {
            if (!empty($s[$key]) && empty($p[$key])) {
                $list[$i][$key] = $s[$key];
                $changed = true;
            }
        }
        if (!empty($s['amenities']) && count($s['amenities']) > count($p['amenities'] ?? [])) {
            $list[$i]['amenities'] = $s['amenities'];
            $changed = true;
        }
    }
    if ($changed) {
        bk_save_properties($list);
    }
}

function bk_sync_seed_images(): void
{
    $seed = require __DIR__ . '/../data/properties.php';
    $byId = [];
    foreach ($seed as $p) {
        $byId[$p['id']] = $p['image'] ?? '';
    }
    $list = json_decode(file_get_contents(bk_properties_file()) ?: '[]', true);
    if (!is_array($list)) {
        return;
    }
    $changed = false;
    foreach ($list as &$p) {
        $id = $p['id'] ?? '';
        if ($id === '' || !isset($byId[$id])) {
            continue;
        }
        if (($p['image'] ?? '') !== $byId[$id]) {
            $p['image'] = $byId[$id];
            $changed = true;
        }
    }
    unset($p);
    if ($changed) {
        bk_save_properties($list);
    }
}

function bk_load_properties_raw(): array
{
    if (bk_uses_mysql()) {
        $data = bk_db_load_properties();
        if ($data === []) {
            bk_ensure_properties_json();
            $data = json_decode(file_get_contents(bk_properties_file()) ?: '[]', true);
            if (is_array($data) && $data !== []) {
                bk_db_save_properties($data);
            }
        }
        return is_array($data) ? $data : [];
    }
    bk_ensure_properties_json();
    bk_sync_seed_images();
    bk_sync_seed_property_extras();
    $raw = file_get_contents(bk_properties_file());
    $data = json_decode($raw ?: '[]', true);
    return is_array($data) ? $data : [];
}

function bk_save_properties(array $list): bool
{
    if (bk_uses_mysql()) {
        return bk_db_save_properties(array_values($list));
    }
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(bk_properties_file(), $json, LOCK_EX) !== false;
}

function bk_load_bookings(): array
{
    if (bk_uses_mysql()) {
        $data = bk_db_load_bookings();
        if ($data === []) {
            bk_seed_demo_bookings();
            return bk_db_load_bookings();
        }
        bk_merge_demo_bookings($data);
        return $data;
    }
    $file = bk_bookings_file();
    if (!is_file($file)) {
        bk_seed_demo_bookings();
    }
    $raw = file_get_contents($file);
    $data = json_decode($raw ?: '[]', true);
    if (!is_array($data)) {
        $data = [];
    }
    if ($data === []) {
        bk_seed_demo_bookings();
        $raw = file_get_contents($file);
        $data = json_decode($raw ?: '[]', true);
        return is_array($data) ? $data : [];
    }
    bk_merge_demo_bookings($data);
    return $data;
}

function bk_booking_is_active(array $booking): bool
{
    $status = $booking['status'] ?? 'pending';
    return in_array($status, ['pending', 'confirmed'], true);
}

/** Ensure demo seed bookings exist when JSON is empty or missing demo refs. */
function bk_merge_demo_bookings(array &$list): void
{
    $seedFile = bk_data_path('bookings.seed.json');
    if (!is_file($seedFile)) {
        $tmp = [];
        bk_seed_demo_bookings_to($tmp);
        if ($tmp !== []) {
            @file_put_contents($seedFile, json_encode($tmp, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
    }
    $seed = [];
    if (is_file($seedFile)) {
        $seed = json_decode(file_get_contents($seedFile) ?: '[]', true);
    }
    if (!is_array($seed) || $seed === []) {
        bk_seed_demo_bookings_to($seed);
    }
    $refs = array_flip(array_map(fn($b) => $b['ref'] ?? $b['id'] ?? '', $list));
    $changed = false;
    foreach ($seed as $row) {
        $key = $row['ref'] ?? $row['id'] ?? '';
        if ($key === '' || isset($refs[$key])) {
            continue;
        }
        $list[] = $row;
        $refs[$key] = true;
        $changed = true;
    }
    if ($changed) {
        bk_save_bookings($list);
    }
}

function bk_seed_demo_bookings_to(array &$samples): void
{
    $props = bk_load_properties_raw();
    if (count($props) < 2) {
        return;
    }
    $samples = [
        [
            'id' => 'demo-001', 'ref' => 'BK-DEMO-7F3A9B2C', 'status' => 'confirmed',
            'property_id' => $props[0]['id'], 'property_name' => $props[0]['name']['en'] ?? 'Hotel',
            'guest' => 'Anna Hansen', 'email' => 'anna@demo.no', 'phone' => '+4798765432',
            'checkin' => date('Y-m-d', strtotime('+5 days')), 'checkout' => date('Y-m-d', strtotime('+8 days')),
            'adults' => 2, 'children' => 0, 'rooms' => 1, 'total' => 5670, 'created_at' => date('c', strtotime('-2 days')),
        ],
        [
            'id' => 'demo-002', 'ref' => 'BK-DEMO-4E8D1A0F', 'status' => 'pending',
            'property_id' => $props[1]['id'], 'property_name' => $props[1]['name']['en'] ?? 'Apartment',
            'guest' => 'Ole Nordmann', 'email' => 'ole@demo.no', 'phone' => '+4744455566',
            'checkin' => date('Y-m-d', strtotime('+12 days')), 'checkout' => date('Y-m-d', strtotime('+15 days')),
            'adults' => 2, 'children' => 1, 'rooms' => 1, 'total' => 4350, 'created_at' => date('c', strtotime('-5 hours')),
        ],
        [
            'id' => 'demo-003', 'ref' => 'BK-DEMO-9C2B5E7D', 'status' => 'cancelled',
            'property_id' => $props[2]['id'] ?? $props[0]['id'], 'property_name' => $props[2]['name']['en'] ?? 'Hotel',
            'guest' => 'Maria Kowalska', 'email' => 'maria@demo.pl', 'phone' => '+48123456789',
            'checkin' => date('Y-m-d', strtotime('-3 days')), 'checkout' => date('Y-m-d', strtotime('-1 day')),
            'adults' => 1, 'children' => 0, 'rooms' => 1, 'total' => 1980, 'created_at' => date('c', strtotime('-10 days')),
        ],
    ];
}

function bk_save_bookings(array $list): bool
{
    if (bk_uses_mysql()) {
        return bk_db_save_bookings(array_values($list));
    }
    $json = json_encode(array_values($list), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    return file_put_contents(bk_bookings_file(), $json, LOCK_EX) !== false;
}

function bk_add_booking(array $booking): string
{
    $list = bk_load_bookings();
    $booking['id']        = $booking['id'] ?? ('bk-' . bin2hex(random_bytes(4)));
    $booking['created_at'] = $booking['created_at'] ?? date('c');
    $booking['status']    = $booking['status'] ?? 'confirmed';
    array_unshift($list, $booking);
    bk_save_bookings($list);
    return $booking['ref'] ?? $booking['id'];
}

function bk_seed_demo_bookings(): void
{
    $samples = [];
    bk_seed_demo_bookings_to($samples);
    bk_save_bookings($samples);
}