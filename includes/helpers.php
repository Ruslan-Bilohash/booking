<?php

function bk_properties(bool $include_inactive = false): array
{
    require_once __DIR__ . '/storage.php';
    $list = bk_load_properties_raw();
    if (!$include_inactive) {
        $list = array_values(array_filter($list, fn($p) => ($p['active'] ?? true) !== false));
    }
    return $list;
}

function bk_properties_all(): array
{
    return bk_properties(true);
}

function bk_property_by_id(string $id, bool $include_inactive = false): ?array
{
    foreach (bk_properties($include_inactive) as $p) {
        if ($p['id'] === $id) {
            return $p;
        }
    }
    return null;
}

function bk_localized(array $item, string $field, string $lang): string
{
    $val = $item[$field] ?? '';
    if (is_array($val)) {
        return $val[$lang] ?? $val['en'] ?? $val['uk'] ?? '';
    }
    return (string) $val;
}

/** @return list<string> */
function bk_localized_list(array $item, string $field, string $lang): array
{
    $val = $item[$field] ?? [];
    if (!is_array($val)) {
        return $val !== '' ? [(string) $val] : [];
    }
    if (isset($val[0]) || $val === []) {
        return array_values(array_filter(array_map('strval', $val)));
    }
    $list = $val[$lang] ?? $val['en'] ?? $val['uk'] ?? [];
    return is_array($list) ? array_values(array_filter(array_map('strval', $list))) : [];
}

function bk_rating_label(float $rating, array $t): string
{
    if ($rating >= 9.0) return $t['rating_superb'];
    if ($rating >= 8.0) return $t['rating_very_good'];
    if ($rating >= 7.0) return $t['rating_good'];
    return $t['rating_ok'];
}

function bk_search_params(): array
{
    $default_in  = (new DateTime('today'))->modify('+7 days')->format('Y-m-d');
    $in          = $_GET['checkin'] ?? $default_in;
    $out         = $_GET['checkout'] ?? (new DateTime($in))->modify('+3 days')->format('Y-m-d');
    return [
        'destination' => trim($_GET['destination'] ?? ''),
        'checkin'     => preg_match('/^\d{4}-\d{2}-\d{2}$/', $in) ? $in : date('Y-m-d', strtotime('+7 days')),
        'checkout'    => preg_match('/^\d{4}-\d{2}-\d{2}$/', $out) ? $out : date('Y-m-d', strtotime('+10 days')),
        'adults'      => max(1, min(20, (int)($_GET['adults'] ?? 2))),
        'children'    => max(0, min(10, (int)($_GET['children'] ?? 0))),
        'rooms'       => max(1, min(10, (int)($_GET['rooms'] ?? 1))),
        'type'        => $_GET['type'] ?? '',
        'sort'        => $_GET['sort'] ?? 'recommended',
        'min_price'   => (int)($_GET['min_price'] ?? 0),
        'max_price'   => (int)($_GET['max_price'] ?? 0),
    ];
}

function bk_filter_properties(array $params, string $lang): array
{
    $items = bk_properties();
    $dest  = mb_strtolower($params['destination']);

    if ($dest !== '') {
        $items = array_filter($items, function ($p) use ($dest, $lang) {
            $hay = mb_strtolower(
                bk_localized($p, 'city', $lang) . ' '
                . bk_localized($p, 'country', $lang) . ' '
                . bk_localized($p, 'name', $lang)
            );
            return str_contains($hay, $dest);
        });
    }

    if ($params['type'] !== '') {
        $items = array_filter($items, fn($p) => $p['type'] === $params['type']);
    }
    if ($params['min_price'] > 0) {
        $items = array_filter($items, fn($p) => $p['price'] >= $params['min_price']);
    }
    if ($params['max_price'] > 0) {
        $items = array_filter($items, fn($p) => $p['price'] <= $params['max_price']);
    }

    $items = array_values($items);

    usort($items, function ($a, $b) use ($params) {
        return match ($params['sort']) {
            'price_low'  => $a['price'] <=> $b['price'],
            'price_high' => $b['price'] <=> $a['price'],
            'rating'     => $b['rating'] <=> $a['rating'],
            default      => ($b['deal'] <=> $a['deal']) ?: ($b['rating'] <=> $a['rating']),
        };
    });

    return $items;
}

function bk_nights(string $checkin, string $checkout): int
{
    $a = new DateTime($checkin);
    $b = new DateTime($checkout);
    $n = (int) $a->diff($b)->days;
    return max(1, $n);
}

function bk_total_price(array $property, int $nights, int $rooms): int
{
    $base = $property['price'] * $nights * $rooms;
    if (!empty($property['deal'])) {
        $base = (int) round($base * (1 - $property['deal'] / 100));
    }
    return $base;
}

function bk_lang_url(string $code, bool $for_hreflang = false): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: bk_url('index.php');
    parse_str(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY) ?? '', $q);
    if ($code === 'no' && $for_hreflang) {
        unset($q['lang']);
    } else {
        $q['lang'] = $code;
    }
    $qs = http_build_query($q);
    return $path . ($qs !== '' ? '?' . $qs : '');
}

function bk_placeholder_image(): string
{
    return bk_asset('images/placeholder.svg');
}

function bk_property_image(array $property): string
{
    return trim($property['image'] ?? '') ?: bk_placeholder_image();
}

function bk_amenity_keys(): array
{
    return ['wifi', 'breakfast', 'parking', 'spa', 'gym', 'pool', 'restaurant', 'kitchen', 'washer', 'ac', 'sauna', 'pets', 'bar', 'beach'];
}

function bk_amenity_icon(string $key): string
{
    return match ($key) {
        'wifi' => 'fa-wifi',
        'breakfast' => 'fa-mug-hot',
        'parking' => 'fa-square-parking',
        'spa' => 'fa-spa',
        'gym' => 'fa-dumbbell',
        'pool' => 'fa-water-ladder',
        'restaurant' => 'fa-utensils',
        'kitchen' => 'fa-kitchen-set',
        'washer' => 'fa-shirt',
        'ac' => 'fa-snowflake',
        'sauna' => 'fa-hot-tub-person',
        'pets' => 'fa-paw',
        'bar' => 'fa-martini-glass',
        'beach' => 'fa-umbrella-beach',
        default => 'fa-check-circle',
    };
}

function bk_review_trip_label(string $type, array $t): string
{
    return $t['reviews']['trip_' . $type] ?? $type;
}

function bk_destinations(string $lang): array
{
    $seen = [];
    $out  = [];
    foreach (bk_properties() as $p) {
        $city = bk_localized($p, 'city', $lang);
        $key  = mb_strtolower($city);
        if (!isset($seen[$key])) {
            $seen[$key] = true;
            $out[] = [
                'city'    => $city,
                'country' => bk_localized($p, 'country', $lang),
                'count'   => 0,
                'image'   => $p['image'],
            ];
        }
    }
    foreach ($out as &$d) {
        foreach (bk_properties() as $p) {
            if (mb_strtolower(bk_localized($p, 'city', $lang)) === mb_strtolower($d['city'])) {
                $d['count']++;
            }
        }
    }
    return array_slice($out, 0, 6);
}

function bk_property_coords(array $property): ?array
{
    if (!isset($property['lat'], $property['lng'])) {
        return null;
    }
    $lat = (float) $property['lat'];
    $lng = (float) $property['lng'];
    if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
        return null;
    }
    return ['lat' => $lat, 'lng' => $lng];
}

function bk_map_embed_url(float $lat, float $lng, float $pad = 0.018): string
{
    $west  = $lng - $pad;
    $south = $lat - $pad;
    $east  = $lng + $pad;
    $north = $lat + $pad;
    $bbox  = rawurlencode(sprintf('%.5f,%.5f,%.5f,%.5f', $west, $south, $east, $north));
    $marker = rawurlencode(sprintf('%.5f,%.5f', $lat, $lng));
    return 'https://www.openstreetmap.org/export/embed.html?bbox=' . $bbox . '&layer=mapnik&marker=' . $marker;
}

function bk_map_directions_url(float $lat, float $lng, string $label = ''): string
{
    $dest = $label !== ''
        ? rawurlencode($label)
        : rawurlencode(sprintf('%.5f,%.5f', $lat, $lng));
    return 'https://www.google.com/maps/dir/?api=1&destination=' . $dest . '&travelmode=driving';
}

function bk_map_open_url(float $lat, float $lng): string
{
    return sprintf(
        'https://www.openstreetmap.org/?mlat=%.5f&mlon=%.5f#map=15/%.5f/%.5f',
        $lat,
        $lng,
        $lat,
        $lng
    );
}