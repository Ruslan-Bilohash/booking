<?php

function bks_protocol(): string
{
    return (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    ) ? 'https' : 'http';
}

function bks_absolute_url(string $path): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    $host = $_SERVER['HTTP_HOST'] ?? BK_DOMAIN;
    return bks_protocol() . '://' . $host . (str_starts_with($path, '/') ? $path : '/' . $path);
}

function bks_full_lang_url(string $code): string
{
    return bks_absolute_url(bks_lang_url($code, true));
}

function bks_demo_absolute(string $path = ''): string
{
    return 'https://bilohash.com/booking/' . ltrim($path, '/');
}

function bks_seo_og_image(): string
{
    return 'https://bilohash.com/booking/screen/home.svg';
}

/** @return array{region: string, place: string, area: string, service: string} */
function bks_seo_market(string $lang): array
{
    require_once __DIR__ . '/market.php';
    $m = bks_market($lang);
    return [
        'region'  => $m['region'],
        'place'   => $m['place_en'],
        'area'    => $m['place_en'],
        'service' => $m['service_en'],
    ];
}

function bks_seo_json(array $graphs): string
{
    $graphs = array_values(array_filter($graphs));
    $data = count($graphs) === 1
        ? array_merge(['@context' => 'https://schema.org'], $graphs[0])
        : ['@context' => 'https://schema.org', '@graph' => $graphs];
    return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

function bks_site_style_version(): string
{
    return '23';
}

function bks_critical_css(): string
{
    static $css = null;
    if ($css === null) {
        $path = __DIR__ . '/../assets/css/site-critical.css';
        $css = is_file($path) ? (string) file_get_contents($path) : '';
    }
    return $css;
}

function bks_font_awesome_href(): string
{
    return 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css';
}

function bks_render_stylesheets(): void
{
    $siteHref = bks_asset('css/site.css') . '?v=' . bks_site_style_version();
    $faHref = bks_font_awesome_href();
    $critical = bks_critical_css();
    ?>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <?php if ($critical !== ''): ?>
    <style id="bks-critical"><?= $critical ?></style>
    <?php endif; ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($siteHref) ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars($faHref) ?>" crossorigin>
    <script src="<?= htmlspecialchars(bks_asset('js/site.js')) ?>?v=<?= bks_site_script_version() ?>" defer></script>
    <?php
}

function bks_site_script_version(): string
{
    return '8';
}

function bks_render_seo_head(string $page_title, string $page_desc, string $canonical, array $schema_graphs = []): void
{
    global $lang_meta, $lang;
    $canonical_abs = bks_absolute_url($canonical);
    $keywords = $GLOBALS['t']['meta']['keywords'] ?? '';
    ?>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if ($keywords !== ''): ?>
    <meta name="keywords" content="<?= htmlspecialchars($keywords) ?>">
    <?php endif; ?>
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="author" content="Booking CMS">
    <?php $bks_market = bks_seo_market($lang); ?>
    <meta name="geo.region" content="<?= htmlspecialchars($bks_market['region']) ?>">
    <meta name="geo.placename" content="<?= htmlspecialchars($bks_market['place']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_abs) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(bks_full_lang_url('no')) ?>">
    <?php foreach (bks_langs() as $code => $info): ?>
    <link rel="alternate" hreflang="<?= $code === 'uk' ? 'uk' : $code ?>" href="<?= htmlspecialchars(bks_full_lang_url($code)) ?>">
    <?php endforeach; ?>
    <link rel="alternate" type="text/plain" href="https://bilohash.com/booking/llms.txt" title="LLM context">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars($GLOBALS['t']['meta']['site_name'] ?? 'Booking CMS') ?>">
    <meta property="og:image" content="<?= bks_seo_og_image() ?>">
    <meta property="og:locale" content="<?= htmlspecialchars(str_replace('-', '_', $lang_meta['locale'])) ?>">
    <?php foreach (bks_langs() as $code => $info):
        if ($code === $lang) continue; ?>
    <meta property="og:locale:alternate" content="<?= htmlspecialchars(str_replace('-', '_', $info['locale'])) ?>">
    <?php endforeach; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta name="twitter:image" content="<?= bks_seo_og_image() ?>">
    <?php if (!empty($schema_graphs)): ?>
    <script type="application/ld+json"><?= bks_seo_json($schema_graphs) ?></script>
    <?php endif;
}

function bks_seo_schemas(string $canonical, string $title, string $desc): array
{
    global $lang;
    require_once __DIR__ . '/market.php';
    return [
        [
            '@type' => 'Organization',
            '@id'   => 'https://bilohash.com/booking/#organization',
            'name'  => 'Booking CMS',
            'url'   => 'https://bilohash.com/booking/site/',
        ],
        [
            '@type'               => 'SoftwareApplication',
            '@id'                 => $canonical . '#software',
            'name'                => 'Booking CMS',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem'     => 'Web',
            'description'         => $desc,
            'url'                 => $canonical,
            'image'               => bks_seo_og_image(),
            'inLanguage'          => ['nb-NO', 'en-GB', 'sv-SE', 'uk-UA', 'ru-RU', 'lt-LT'],
            'offers'              => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => bks_market($lang)['currency']],
            'author'              => ['@type' => 'Organization', 'name' => 'Booking CMS', 'url' => 'https://bilohash.com/booking/site/'],
            'downloadUrl'         => bks_demo_absolute(),
            'softwareVersion'     => bk_version(),
            'datePublished'       => '2026-06-20',
            'dateModified'        => bk_version_date(),
        ],
        [
            '@type' => 'ProfessionalService',
            '@id'   => $canonical . '#service',
            'name'  => 'Order booking website development — ' . (bks_seo_market($lang)['place'] ?? 'Europe'),
            'url'   => $canonical,
            'description' => bks_seo_market($lang)['service'],
            'areaServed' => bks_seo_market($lang)['area'],
            'provider' => ['@id' => 'https://bilohash.com/booking/#organization'],
        ],
        [
            '@type' => 'WebPage',
            '@id'   => $canonical . '#webpage',
            'url'   => $canonical,
            'name'  => $title,
            'description' => $desc,
            'inLanguage' => bks_langs()[$lang]['locale'] ?? 'nb-NO',
            'isPartOf' => ['@type' => 'WebSite', 'name' => 'Booking CMS', 'url' => 'https://bilohash.com/booking/site/'],
        ],
    ];
}