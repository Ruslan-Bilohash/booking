<?php

function bk_protocol(): string
{
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    ) ? 'https' : 'http';
}

function bk_host(): string
{
    return $_SERVER['HTTP_HOST'] ?? BK_DOMAIN;
}

function bk_absolute_url(string $path): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return bk_protocol() . '://' . bk_host() . (str_starts_with($path, '/') ? $path : '/' . $path);
}

function bk_full_lang_url(string $code): string
{
    return bk_absolute_url(bk_lang_url($code, true));
}

function bk_seo_settings(): array
{
    $s = function_exists('bk_site_settings') ? bk_site_settings() : [];
    return is_array($s) ? $s : [];
}

/** @return array{region: string, place: string} */
function bk_seo_market_geo(string $lang): array
{
    return match ($lang) {
        'no' => ['region' => 'NO', 'place' => 'Norway'],
        'sv' => ['region' => 'SE', 'place' => 'Sweden'],
        'uk' => ['region' => 'UA', 'place' => 'Ukraine'],
        'ru' => ['region' => 'RU', 'place' => 'Russia'],
        'en' => ['region' => 'EU', 'place' => 'Europe'],
        default => ['region' => 'EU', 'place' => 'Europe'],
    };
}

/** @return array{country_en: string, area_served: list<array<string, string>>} */
function bk_market_area_served(string $lang): array
{
    return match ($lang) {
        'no' => [
            'country_en' => 'Norway',
            'area_served' => [
                ['@type' => 'Country', 'name' => 'Norway'],
                ['@type' => 'Place', 'name' => 'Scandinavia'],
            ],
        ],
        'sv' => [
            'country_en' => 'Sweden',
            'area_served' => [
                ['@type' => 'Country', 'name' => 'Sweden'],
                ['@type' => 'Place', 'name' => 'Nordic countries'],
            ],
        ],
        'uk' => [
            'country_en' => 'Ukraine',
            'area_served' => [
                ['@type' => 'Country', 'name' => 'Ukraine'],
                ['@type' => 'Place', 'name' => 'Europe'],
            ],
        ],
        'ru' => [
            'country_en' => 'Russia',
            'area_served' => [
                ['@type' => 'Country', 'name' => 'Russia'],
                ['@type' => 'Place', 'name' => 'Europe'],
            ],
        ],
        default => [
            'country_en' => 'Norway',
            'area_served' => [
                ['@type' => 'Country', 'name' => 'Norway'],
                ['@type' => 'Place', 'name' => 'Europe'],
            ],
        ],
    };
}

function bk_popular_properties(int $limit = 4): array
{
    $items = array_values(bk_properties());
    usort($items, static fn($a, $b) => $b['rating'] <=> $a['rating'] ?: $b['reviews'] <=> $a['reviews']);
    return array_slice($items, 0, $limit);
}

function bk_seo_og_image(): string
{
    $custom = trim(bk_seo_settings()['seo_default_og_image'] ?? '');
    return $custom !== '' ? $custom : 'https://bilohash.com/booking/screen/home.svg';
}

function bk_seo_site_name(): string
{
    $custom = trim(bk_seo_settings()['seo_site_name'] ?? '');
    return $custom !== '' ? $custom : BK_SITE_NAME;
}

function bk_seo_org_name(): string
{
    $custom = trim(bk_seo_settings()['seo_org_name'] ?? '');
    return $custom !== '' ? $custom : BK_SITE_NAME;
}

function bk_seo_author(): array
{
    return [
        '@type' => 'Organization',
        'name'  => bk_seo_org_name(),
        'url'   => 'https://bilohash.com/booking/',
    ];
}

function bk_seo_organization(): array
{
    return [
        '@type' => 'Organization',
        '@id'   => 'https://bilohash.com/booking/#organization',
        'name'  => bk_seo_org_name(),
        'url'   => 'https://bilohash.com/booking/',
        'logo'  => 'https://bilohash.com/favicon.ico',
        'areaServed' => ['NO', 'SE', 'UA', 'RU', 'LT', 'EU'],
        'knowsAbout' => [
            'PHP booking scripts',
            'Online reservation systems',
            'Booking website development Europe',
            'Multilingual SEO',
            'Schema.org structured data',
        ],
    ];
}

function bk_seo_software_app(string $canonical, string $description): array
{
    return [
        '@type'               => 'SoftwareApplication',
        '@id'                 => $canonical . '#software',
        'name'                => BK_SITE_NAME,
        'applicationCategory' => 'BusinessApplication',
        'applicationSubCategory' => 'Booking and reservation software',
        'operatingSystem'     => 'Web',
        'description'         => $description,
        'url'                 => $canonical,
        'image'               => bk_seo_og_image(),
        'inLanguage'          => ['nb-NO', 'en-GB', 'sv-SE', 'uk-UA', 'ru-RU'],
        'offers'              => [
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'NOK',
            'availability'  => 'https://schema.org/InStock',
            'url'           => 'https://bilohash.com/booking/site/',
        ],
        'author'    => bk_seo_author(),
        'publisher' => bk_seo_organization(),
        'featureList' => 'Multilingual frontend, property search, admin panel, JSON storage, Schema.org SEO, responsive UI',
    ];
}

function bk_seo_website(string $canonical): array
{
    global $site_url;
    return [
        '@type' => 'WebSite',
        '@id'   => rtrim($site_url, '/') . '/#website',
        'name'  => BK_SITE_NAME,
        'url'   => rtrim($site_url, '/') . '/',
        'inLanguage' => ['nb-NO', 'en-GB', 'uk-UA'],
        'publisher' => ['@id' => 'https://bilohash.com/booking/#organization'],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => rtrim($site_url, '/') . '/search.php?destination={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

function bk_seo_webpage(string $canonical, string $title, string $description): array
{
    global $lang;
    return [
        '@type'       => 'WebPage',
        '@id'         => $canonical . '#webpage',
        'url'         => $canonical,
        'name'        => $title,
        'description' => $description,
        'isPartOf'    => ['@id' => 'https://bilohash.com/booking/#website'],
        'about'       => ['@id' => 'https://bilohash.com/booking/#software'],
        'inLanguage'  => bk_langs()[$lang]['locale'] ?? 'en-GB',
    ];
}

function bk_seo_breadcrumbs(array $items): array
{
    $list = [];
    $pos = 1;
    foreach ($items as $item) {
        $entry = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $item['name'],
        ];
        if (!empty($item['url'])) {
            $entry['item'] = $item['url'];
        }
        $list[] = $entry;
    }
    return [
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $list,
    ];
}

function bk_seo_country_code(array $property): string
{
    $settings = bk_seo_settings();
    $fallback = strtoupper(trim($settings['seo_default_country_code'] ?? 'NO'));
    $country = bk_localized($property, 'country', 'en');
    if (preg_match('/\b(Norway|Norge|Норвегія|Норвегия)\b/ui', $country)) {
        return 'NO';
    }
    return $fallback !== '' ? $fallback : 'NO';
}

function bk_seo_lodging(array $property, string $lang, string $canonical): array
{
    $name = bk_localized($property, 'name', $lang);
    $city = bk_localized($property, 'city', $lang);
    $country = bk_localized($property, 'country', $lang);
    $desc = bk_localized($property, 'desc', $lang);
    $type = match ($property['type'] ?? 'hotel') {
        'apartment' => 'Apartment',
        'cabin'     => 'House',
        default     => 'Hotel',
    };
    $schema = [
        '@type'       => $type,
        '@id'         => $canonical . '#lodging',
        'name'        => $name,
        'description' => $desc,
        'url'         => $canonical,
        'image'       => bk_property_image($property),
        'address'     => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $city,
            'addressCountry'  => bk_seo_country_code($property),
            'name'            => $city . ', ' . $country,
        ],
        'priceRange' => bk_price((int) ($property['price'] ?? 0)),
    ];
    if (!empty($property['stars'])) {
        $schema['starRating'] = [
            '@type'       => 'Rating',
            'ratingValue' => (int) $property['stars'],
            'bestRating'  => 5,
        ];
    }
    if (!empty($property['rating'])) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round((float) $property['rating'], 1),
            'bestRating'  => 10,
            'reviewCount' => max(0, (int) ($property['reviews'] ?? 0)),
        ];
    }
    if (!empty($property['lat']) && !empty($property['lng'])) {
        $schema['geo'] = [
            '@type'     => 'GeoCoordinates',
            'latitude'  => (float) $property['lat'],
            'longitude' => (float) $property['lng'],
        ];
    }
    $price = (int) ($property['price'] ?? 0);
    if ($price > 0) {
        $schema['makesOffer'] = [
            '@type'         => 'Offer',
            'price'         => $price,
            'priceCurrency' => BK_CURRENCY,
            'availability'  => 'https://schema.org/InStock',
            'url'           => $canonical,
        ];
    }
    return $schema;
}

function bk_seo_product(array $property, string $lang, string $canonical): array
{
    $name = bk_localized($property, 'name', $lang);
    $desc = bk_localized($property, 'desc', $lang);
    $city = bk_localized($property, 'city', $lang);
    $schema = [
        '@type'       => 'Product',
        '@id'         => $canonical . '#product',
        'name'        => $name,
        'description' => mb_substr(strip_tags($desc), 0, 300),
        'image'       => bk_property_image($property),
        'url'         => $canonical,
        'category'    => $property['type'] ?? 'hotel',
        'brand'       => ['@type' => 'Brand', 'name' => bk_seo_site_name()],
        'areaServed'  => [
            '@type' => 'City',
            'name'  => $city,
        ],
    ];
    $price = (int) ($property['price'] ?? 0);
    if ($price > 0) {
        $schema['offers'] = [
            '@type'         => 'Offer',
            'price'         => $price,
            'priceCurrency' => BK_CURRENCY,
            'availability'  => 'https://schema.org/InStock',
            'url'           => $canonical,
        ];
    }
    if (!empty($property['rating'])) {
        $schema['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => round((float) $property['rating'], 1),
            'bestRating'  => 10,
            'reviewCount' => max(0, (int) ($property['reviews'] ?? 0)),
        ];
    }
    return $schema;
}

function bk_seo_schema_enabled(string $key): bool
{
    $settings = bk_seo_settings();
    $map = [
        'lodging'     => 'seo_schema_lodging',
        'product'     => 'seo_schema_product',
        'breadcrumbs' => 'seo_schema_breadcrumbs',
    ];
    $field = $map[$key] ?? null;
    if ($field === null) {
        return true;
    }
    return !array_key_exists($field, $settings) || !empty($settings[$field]);
}

function bk_seo_item_list(array $properties, string $lang, string $listUrl): array
{
    $elements = [];
    $pos = 1;
    foreach (array_slice($properties, 0, 20) as $p) {
        $url = bk_absolute_url(bk_url('property.php?id=' . urlencode($p['id'])));
        $elements[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'url'      => $url,
            'name'     => bk_localized($p, 'name', $lang),
        ];
    }
    return [
        '@type'           => 'ItemList',
        'url'             => $listUrl,
        'numberOfItems'   => count($properties),
        'itemListElement' => $elements,
    ];
}

function bk_seo_professional_service(?string $lang = null): array
{
    $lang ??= $GLOBALS['lang'] ?? 'en';
    $place = bk_seo_market_geo($lang)['place'];
    $t = $GLOBALS['t'] ?? [];
    $order = is_array($t['order'] ?? null) ? $t['order'] : [];
    $name = trim((string) ($order['h1'] ?? 'Custom booking website development')) . ' — ' . $place;
    $description = trim((string) ($order['meta_description'] ?? $order['subtitle'] ?? ''));
    if ($description === '') {
        $description = 'Order development of online booking websites and PHP reservation scripts.';
    }
    $served = array_map(
        static fn(array $item): string => (string) ($item['name'] ?? ''),
        bk_market_area_served($lang)['area_served']
    );

    return [
        '@type' => 'ProfessionalService',
        '@id'   => 'https://bilohash.com/booking/site/#service',
        'name'  => $name,
        'url'   => 'https://bilohash.com/booking/site/',
        'description' => $description,
        'areaServed' => array_values(array_filter($served)),
        'provider' => bk_seo_organization(),
    ];
}

function bk_seo_json(array $graphs): string
{
    $graphs = array_values(array_filter($graphs));
    if (count($graphs) === 1) {
        $data = array_merge(['@context' => 'https://schema.org'], $graphs[0]);
    } else {
        $data = [
            '@context' => 'https://schema.org',
            '@graph'   => $graphs,
        ];
    }
    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

function bk_render_seo_head(
    string $page_title,
    string $page_desc,
    string $canonical,
    array $schema_graphs = [],
    ?string $og_image = null,
    ?string $og_type = 'website',
    bool $noindex = false
): void {
    global $lang;
    $settings = bk_seo_settings();
    $canonical_abs = bk_absolute_url($canonical);
    $keywords = $GLOBALS['t']['meta']['keywords'] ?? '';
    $robots = $noindex ? 'noindex, nofollow' : 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $marketGeo = bk_seo_market_geo($lang);
    $geoRegion = trim($settings['seo_geo_region'] ?? '') ?: $marketGeo['region'];
    $geoPlace = trim($settings['seo_geo_placename'] ?? '') ?: $marketGeo['place'];
    $siteName = bk_seo_site_name();
    ?>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if ($keywords !== ''): ?>
    <meta name="keywords" content="<?= htmlspecialchars($keywords) ?>">
    <?php endif; ?>
    <meta name="robots" content="<?= $robots ?>">
    <meta name="author" content="<?= htmlspecialchars($siteName) ?>">
    <meta name="geo.region" content="<?= htmlspecialchars($geoRegion) ?>">
    <meta name="geo.placename" content="<?= htmlspecialchars($geoPlace) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_abs) ?>">
    <?php
}

function bk_render_seo_social(
    string $page_title,
    string $page_desc,
    string $canonical_abs,
    ?string $og_image = null,
    string $og_type = 'website'
): void {
    global $lang_meta, $lang;
    $settings = bk_seo_settings();
    $og_image ??= bk_seo_og_image();
    $siteName = bk_seo_site_name();
    $twitterSite = trim($settings['seo_twitter_site'] ?? '');
    ?>
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(bk_full_lang_url('no')) ?>">
    <?php foreach (bk_langs() as $code => $info): ?>
    <link rel="alternate" hreflang="<?= $code === 'uk' ? 'uk' : $code ?>" href="<?= htmlspecialchars(bk_full_lang_url($code)) ?>">
    <?php endforeach; ?>
    <link rel="alternate" type="text/plain" href="<?= htmlspecialchars(bk_absolute_url(bk_url('llms.txt'))) ?>" title="LLM context">
    <meta property="og:type" content="<?= htmlspecialchars($og_type) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($siteName) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
    <meta property="og:image:alt" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?= htmlspecialchars(str_replace('-', '_', $lang_meta['locale'])) ?>">
    <?php foreach (bk_langs() as $code => $info):
        if ($code === $lang) continue; ?>
    <meta property="og:locale:alternate" content="<?= htmlspecialchars(str_replace('-', '_', $info['locale'])) ?>">
    <?php endforeach; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($og_image) ?>">
    <?php if ($twitterSite !== ''): ?>
    <meta name="twitter:site" content="<?= htmlspecialchars($twitterSite) ?>">
    <?php endif; ?>
    <?php
}

function bk_render_seo_schemas(array $schema_graphs): void
{
    if ($schema_graphs === []) {
        return;
    }
    echo '<script type="application/ld+json">' . bk_seo_json(array_values(array_filter($schema_graphs))) . '</script>';
}