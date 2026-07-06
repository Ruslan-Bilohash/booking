<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/vertical-lib.php';
header('Content-Type: application/xml; charset=UTF-8');

$base = 'https://bilohash.com/booking';
$lastmod = gmdate('Y-m-d');
$bk_langs = array_keys(bk_langs());
$bk_lang_alts = array_values(array_filter($bk_langs, static fn(string $c): bool => $c !== 'no'));
$site_langs = ['no', 'en', 'sv', 'uk', 'ru', 'lt'];
$site_lang_alts = array_values(array_filter($site_langs, static fn(string $c): bool => $c !== 'no'));

$urls = [];

$push = static function (
    string $loc,
    string $priority = '0.5',
    string $changefreq = 'monthly',
    ?array $hreflang = null
) use (&$urls, $lastmod): void {
    $entry = [
        'loc'        => $loc,
        'priority'   => $priority,
        'changefreq' => $changefreq,
        'lastmod'    => $lastmod,
    ];
    if ($hreflang !== null) {
        $entry['hreflang'] = $hreflang;
    }
    $urls[] = $entry;
};

$booking_hreflang = static function (string $path) use ($base, $bk_langs): array {
    $alts = [];
    foreach ($bk_langs as $code) {
        $tag = $code === 'uk' ? 'uk' : $code;
        if ($code === 'no') {
            $alts[$tag] = $base . $path;
        } else {
            $sep = str_contains($path, '?') ? '&' : '?';
            $alts[$tag] = $base . $path . $sep . 'lang=' . rawurlencode($code);
        }
    }
    $alts['x-default'] = $base . $path;
    return $alts;
};

$site_hreflang = static function (string $path) use ($base, $site_langs): array {
    $alts = [];
    foreach ($site_langs as $code) {
        $tag = $code === 'uk' ? 'uk' : $code;
        if ($code === 'no') {
            $alts[$tag] = $base . $path;
        } else {
            $sep = str_contains($path, '?') ? '&' : '?';
            $alts[$tag] = $base . $path . $sep . 'lang=' . rawurlencode($code);
        }
    }
    $alts['x-default'] = $base . $path;
    return $alts;
};

$booking_pages = [
    ['path' => '/',              'priority' => '1.0',  'changefreq' => 'weekly'],
    ['path' => '/order.php',    'priority' => '0.93', 'changefreq' => 'monthly'],
    ['path' => '/solutions.php','priority' => '0.92', 'changefreq' => 'weekly'],
    ['path' => '/contact.php', 'priority' => '0.88', 'changefreq' => 'monthly'],
];

foreach ($booking_pages as $page) {
    $path = $page['path'];
    $push($base . $path, $page['priority'], $page['changefreq'], $booking_hreflang($path));
    foreach ($bk_lang_alts as $lng) {
        $push(
            $base . $path . '?lang=' . rawurlencode($lng),
            (string) ((float) $page['priority'] - 0.02),
            $page['changefreq']
        );
    }
}

$site_pages = [
    ['path' => '/site/',              'priority' => '0.95', 'changefreq' => 'weekly'],
    ['path' => '/site/order.php',    'priority' => '0.92', 'changefreq' => 'monthly'],
    ['path' => '/site/contact.php',  'priority' => '0.87', 'changefreq' => 'monthly'],
];

foreach ($site_pages as $page) {
    $path = $page['path'];
    $push($base . $path, $page['priority'], $page['changefreq'], $site_hreflang($path));
    foreach ($site_lang_alts as $lng) {
        $push(
            $base . $path . '?lang=' . rawurlencode($lng),
            (string) ((float) $page['priority'] - 0.02),
            $page['changefreq']
        );
    }
}

$push($base . '/llms.txt', '0.5', 'monthly');
$push('https://bilohash.com/news/booking-cms.html', '0.75', 'yearly');

foreach (bk_vertical_slugs() as $slug) {
    $path = '/' . $slug . '/';
    $push($base . $path, '0.88', 'monthly', $booking_hreflang($path));
    foreach ($bk_lang_alts as $lng) {
        $push($base . $path . '?lang=' . rawurlencode($lng), '0.86', 'monthly');
    }
}

foreach (bk_properties() as $property) {
    $id = (string) $property['id'];
    $propPath = '/property.php?id=' . rawurlencode($id);
    $bookPath = '/book.php?id=' . rawurlencode($id);

    $push($base . $propPath, '0.8', 'monthly', $booking_hreflang($propPath));
    foreach ($bk_lang_alts as $lng) {
        $push($base . $propPath . '&lang=' . rawurlencode($lng), '0.78', 'monthly');
    }

    $push($base . $bookPath, '0.76', 'monthly', $booking_hreflang($bookPath));
    foreach ($bk_lang_alts as $lng) {
        $push($base . $bookPath . '&lang=' . rawurlencode($lng), '0.74', 'monthly');
    }
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach ($urls as $u): ?>
    <url>
        <loc><?= htmlspecialchars($u['loc']) ?></loc>
        <lastmod><?= htmlspecialchars($u['lastmod']) ?></lastmod>
        <changefreq><?= htmlspecialchars($u['changefreq']) ?></changefreq>
        <priority><?= htmlspecialchars($u['priority']) ?></priority>
        <?php if (!empty($u['hreflang'])):
            foreach ($u['hreflang'] as $tag => $href):
        ?>
        <xhtml:link rel="alternate" hreflang="<?= htmlspecialchars($tag) ?>" href="<?= htmlspecialchars($href) ?>"/>
        <?php endforeach; endif; ?>
    </url>
<?php endforeach; ?>
</urlset>