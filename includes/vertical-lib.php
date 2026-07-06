<?php

if (!function_exists('bh_str_lower')) {
    function bh_str_lower(string $s): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s);
    }
}

function bk_vertical_defs(): array
{
    static $defs = null;
    if ($defs === null) {
        $defs = require __DIR__ . '/../data/vertical-defs.php';
    }
    return $defs;
}

function bk_use_case_slugs(): array
{
    return array_keys(bk_vertical_defs());
}

function bk_vertical_hub_label(string $lang): string
{
    $labels = [
        'no' => 'Booking-løsninger',
        'en' => 'Booking solutions',
        'sv' => 'Bokningslösningar',
        'uk' => 'Рішення для бронювання',
        'ru' => 'Решения для бронирования',
    ];
    return $labels[$lang] ?? $labels['en'];
}

function bk_verticals_build(): array
{
    $defs = bk_vertical_defs();
    $tpl = [
        'no' => [
            'title' => 'Bestill %s bookingsystem Norge | Booking CMS',
            'description' => 'Booking CMS — PHP bookingsystem for %s i Norge og Europa. Online reservasjon, adminpanel, flerspråklig SEO og live demo. Bestill skreddersydd løsning.',
            'keywords' => 'bestill %s bookingsystem Norge, PHP booking %s, online reservasjon Norge, Booking CMS, bookingsystem Europa, adminpanel booking, flerspråklig booking',
            'subtitle' => 'Profesjonelt PHP bookingsystem for %s — bygget for norske og europeiske bedrifter',
            'intro' => 'Booking CMS er en modulær PHP-plattform for online reservasjon av %s. Live demo med søk, kalender, adminpanel og JSON-lagring — klar til tilpasning med Vipps, Stripe, SMS-varsler og flere språk.',
            'cta' => 'Klar for et bookingsystem for %s? Få et tilbud i dag.',
            'h1' => 'Booking CMS for %s',
        ],
        'en' => [
            'title' => 'Order %s Booking System Europe | Booking CMS',
            'description' => 'Booking CMS — PHP booking platform for %s in Europe. Online reservations, admin panel, multilingual SEO and live demo. Order a custom solution.',
            'keywords' => 'order %s booking system Europe, PHP booking %s, online reservation Europe, Booking CMS, booking software Norway, multilingual booking script',
            'subtitle' => 'Professional PHP booking system for %s — built for European businesses',
            'intro' => 'Booking CMS is a modular PHP platform for online %s reservations. Live demo with search, calendar, admin panel and JSON storage — ready for Vipps, Stripe, SMS alerts and extra languages.',
            'cta' => 'Ready for a %s booking system? Get a quote today.',
            'h1' => 'Booking CMS for %s',
        ],
        'sv' => [
            'title' => 'Beställ %s bokningssystem Sverige | Booking CMS',
            'description' => 'Booking CMS — PHP-bokningsplattform för %s i Sverige och Norden. Onlinebokning, adminpanel, flerspråkig SEO och live demo. Beställ skräddarsydd lösning.',
            'keywords' => 'beställ %s bokningssystem Sverige, PHP bokning %s, onlinebokning Norden, Booking CMS, bokningsplattform, flerspråkigt bokningsskript',
            'subtitle' => 'Professionellt PHP-bokningssystem för %s — byggt för svenska och nordiska företag',
            'intro' => 'Booking CMS är en modulär PHP-plattform för onlinebokning av %s. Live demo med sökning, kalender, adminpanel och JSON-lagring — redo för Vipps, Stripe, SMS och fler språk.',
            'cta' => 'Redo för ett bokningssystem för %s? Få en offert idag.',
            'h1' => 'Booking CMS för %s',
        ],
        'uk' => [
            'title' => 'Замовити систему бронювання %s Україна | Booking CMS',
            'description' => 'Booking CMS — PHP-платформа бронювання для %s в Україні та Європі. Онлайн-запис, адмін-панель, багатомовне SEO та live demo. Замовити індивідуальне рішення.',
            'keywords' => 'замовити бронювання %s Україна, PHP booking %s, система запису Європа, Booking CMS, скрипт бронювання, багатомовне бронювання',
            'subtitle' => 'Професійна PHP-система бронювання для %s — для бізнесу в Україні та Європі',
            'intro' => 'Booking CMS — модульна PHP-платформа для онлайн-бронювання %s. Live demo з пошуком, календарем, адмін-панеллю та JSON-сховищем — готова до Vipps, Stripe, SMS та додаткових мов.',
            'cta' => 'Потрібна система бронювання для %s? Отримайте пропозицію сьогодні.',
            'h1' => 'Booking CMS — %s',
        ],
        'ru' => [
            'title' => 'Заказать систему бронирования %s Россия | Booking CMS',
            'description' => 'Booking CMS — PHP-платформа бронирования для %s в России и Европе. Онлайн-запись, админ-панель, многоязычное SEO и live demo. Закажите индивидуальное решение.',
            'keywords' => 'заказать систему бронирования %s Россия, PHP booking %s, онлайн-запись Европа, Booking CMS, скрипт бронирования, многоязычное бронирование',
            'subtitle' => 'Профессиональная PHP-система бронирования для %s — для бизнеса в России и Европе',
            'intro' => 'Booking CMS — модульная PHP-платформа для онлайн-бронирования %s. Live demo с поиском, календарём, админ-панелью и JSON-хранилищем — готова к Vipps, Stripe, SMS и дополнительным языкам.',
            'cta' => 'Нужна система бронирования для %s? Получите предложение сегодня.',
            'h1' => 'Booking CMS — %s',
        ],
    ];
    $benefits = [
        'no' => [
            ['title' => 'Økt konvertering', 'text' => 'Rask, mobilvennlig bookingflyt som reduserer friksjon og øker fullførte reservasjoner.'],
            ['title' => 'Full kontroll i admin', 'text' => 'Administrer tilgjengelighet, priser, statuser og kunder i et moderne adminpanel.'],
            ['title' => 'Flerspråklig & SEO', 'text' => 'Norsk, engelsk og ukrainsk med hreflang, Schema.org og dynamisk sitemap.'],
            ['title' => 'Skalerbar PHP-arkitektur', 'text' => 'Uten tungt rammeverk — enkel deploy på norsk hosting og migrering til MySQL.'],
        ],
        'en' => [
            ['title' => 'Higher conversion', 'text' => 'Fast, mobile-first booking flow that reduces friction and increases completed reservations.'],
            ['title' => 'Full admin control', 'text' => 'Manage availability, prices, statuses and customers in a modern admin panel.'],
            ['title' => 'Multilingual & SEO', 'text' => 'Norwegian, English and Ukrainian with hreflang, Schema.org and dynamic sitemap.'],
            ['title' => 'Scalable PHP architecture', 'text' => 'No heavy framework — easy deploy on Norwegian hosting and migration to MySQL.'],
        ],
        'uk' => [
            ['title' => 'Вища конверсія', 'text' => 'Швидкий mobile-first процес бронювання зменшує відмови та підвищує завершені записи.'],
            ['title' => 'Повний контроль в адмінці', 'text' => 'Керуйте доступністю, цінами, статусами та клієнтами в сучасній адмін-панелі.'],
            ['title' => 'Багатомовність та SEO', 'text' => 'Норвезька, англійська та українська з hreflang, Schema.org і динамічним sitemap.'],
            ['title' => 'Масштабована PHP-архітектура', 'text' => 'Без важких фреймворків — легкий деплой на норвезькому хостингу та міграція на MySQL.'],
        ],
        'ru' => [
            ['title' => 'Выше конверсия', 'text' => 'Быстрый mobile-first процесс бронирования снижает отказы и повышает завершённые записи.'],
            ['title' => 'Полный контроль в админке', 'text' => 'Управляйте доступностью, ценами, статусами и клиентами в современной админ-панели.'],
            ['title' => 'Многоязычность и SEO', 'text' => 'Норвежский, английский, украинский, русский и шведский с hreflang, Schema.org и динамическим sitemap.'],
            ['title' => 'Масштабируемая PHP-архитектура', 'text' => 'Без тяжёлых фреймворков — лёгкий деплой на хостинге в России, Норвегии или ЕС и миграция на MySQL.'],
        ],
        'sv' => [
            ['title' => 'Högre konvertering', 'text' => 'Snabb, mobilanpassad bokningsflöde som minskar friktion och ökar slutförda bokningar.'],
            ['title' => 'Full kontroll i admin', 'text' => 'Hantera tillgänglighet, priser, statusar och kunder i en modern adminpanel.'],
            ['title' => 'Flerspråkigt & SEO', 'text' => 'Svenska, norska, engelska, ukrainska och ryska med hreflang, Schema.org och dynamisk sitemap.'],
            ['title' => 'Skalbar PHP-arkitektur', 'text' => 'Utan tungt ramverk — enkel driftsättning på svensk eller nordisk hosting och migrering till MySQL.'],
        ],
    ];
    $features = [
        'no' => ['Online kalender og tilgjengelighet', 'Søk, filtre og responsive design', 'E-post/SMS-varsler (klar for integrasjon)', 'Adminpanel med dashboard', 'JSON-lagring — enkel migrering', 'GDPR-vennlig cookie-samtykke'],
        'en' => ['Online calendar and availability', 'Search, filters and responsive design', 'Email/SMS notifications (integration-ready)', 'Admin panel with dashboard', 'JSON storage — easy migration', 'GDPR-friendly cookie consent'],
        'uk' => ['Онлайн-календар і доступність', 'Пошук, фільтри та адаптивний дизайн', 'Email/SMS сповіщення (готово до інтеграції)', 'Адмін-панель з дашбордом', 'JSON-сховище — легка міграція', 'GDPR cookie consent'],
        'ru' => ['Онлайн-календарь и доступность', 'Поиск, фильтры и адаптивный дизайн', 'Email/SMS уведомления (готово к интеграции)', 'Админ-панель с дашбордом', 'JSON-хранилище — лёгкая миграция', 'GDPR cookie consent'],
        'sv' => ['Onlinekalender och tillgänglighet', 'Sökning, filter och responsiv design', 'E-post/SMS-aviseringar (integrationsklart)', 'Adminpanel med instrumentpanel', 'JSON-lagring — enkel migrering', 'GDPR-vänligt cookie-samtycke'],
    ];
    $faq_q = [
        'no' => ['Kan Booking CMS tilpasses %s?', 'Hvilke språk støttes?', 'Trenger jeg egen server?', 'Hvordan bestiller jeg utvikling?'],
        'en' => ['Can Booking CMS be customized for %s?', 'Which languages are supported?', 'Do I need a dedicated server?', 'How do I order development?'],
        'uk' => ['Чи можна адаптувати Booking CMS під %s?', 'Які мови підтримуються?', 'Чи потрібен окремий сервер?', 'Як замовити розробку?'],
        'ru' => ['Можно ли адаптировать Booking CMS под %s?', 'Какие языки поддерживаются?', 'Нужен ли отдельный сервер?', 'Как заказать разработку?'],
        'sv' => ['Kan Booking CMS anpassas för %s?', 'Vilka språk stöds?', 'Behöver jag en egen server?', 'Hur beställer jag utveckling?'],
    ];
    $faq_a = [
        'no' => ['Ja. Vi tilpasser felt, varighet, priser, betaling og varsler for din %s-virksomhet i Norge eller Europa.', 'Norsk, engelsk, ukrainsk, russisk og svensk — hreflang, cookie og språkmeny på nettsted og i admin. Flere språk kan legges til raskt.', 'Nei. PHP 8+ på delt hosting eller VPS i Norge/EU er nok for start.', 'Bruk «Bestill utvikling» eller kontakt på bilohash.com — beskriv %s-prosjektet, vi sender tilbud og tidsplan.'],
        'en' => ['Yes. We adapt fields, duration, pricing, payments and notifications for your %s business in Europe.', 'Norwegian, English, Ukrainian, Russian and Swedish — hreflang, cookie and language switcher on the site and in admin. More languages can be added quickly.', 'No. PHP 8+ on shared hosting or a VPS in Norway/EU is enough to start.', 'Use the order page or contact form on bilohash.com — describe your %s project and we send a quote and timeline.'],
        'uk' => ['Так. Ми налаштовуємо поля, тривалість, ціни, оплату та сповіщення для вашого %s-бізнесу в Україні та Європі.', 'Норвезька, англійська, українська, російська та шведська — hreflang, cookie та перемикач мов на сайті й в адмінці. Інші мови додаються швидко.', 'Ні. Достатньо PHP 8+ на звичайному хостингу або VPS в Україні, Норвегії чи ЄС.', 'Сторінка «Замовити розробку» або контакт на bilohash.com — опишіть %s-проєкт, надішлемо пропозицію та терміни.'],
        'ru' => ['Да. Мы настраиваем поля, длительность, цены, оплату и уведомления для вашего %s-бизнеса в России или Европе.', 'Норвежский, английский, украинский, русский и шведский — hreflang, cookie и переключатель языков на сайте и в админке.', 'Нет. Достаточно PHP 8+ на обычном хостинге или VPS в России, Норвегии или ЕС.', 'Страница «Заказать разработку» или контакт на bilohash.com — опишите %s-проект, пришлём предложение и сроки.'],
        'sv' => ['Ja. Vi anpassar fält, varaktighet, priser, betalning och aviseringar för din %s-verksamhet i Sverige eller Norden.', 'Svenska, norska, engelska, ukrainska och ryska — hreflang, cookie och språkmeny på webbplats och i admin. Fler språk kan läggas till snabbt.', 'Nej. PHP 8+ på delad hosting eller VPS i Sverige/Norden räcker för start.', 'Använd «Beställ utveckling» eller kontakt på bilohash.com — beskriv ditt %s-projekt, vi skickar offert och tidsplan.'],
    ];

    $out = [];
    foreach ($defs as $slug => $def) {
        $entry = ['icon' => $def['icon'], 'demo_param' => $def['demo_param']];
        foreach (['no', 'en', 'sv', 'uk', 'ru'] as $lng) {
            $name = $def[$lng] ?? $def['en'] ?? $def['no'] ?? $slug;
            $lower = bh_str_lower($name);
            $t = $tpl[$lng];
            $faqs = [];
            foreach ($faq_q[$lng] as $i => $q) {
                $faqs[] = ['q' => sprintf($q, $lower), 'a' => sprintf($faq_a[$lng][$i], $lower)];
            }
            $entry[$lng] = [
                'title' => sprintf($t['title'], $name),
                'description' => sprintf($t['description'], $lower),
                'keywords' => sprintf($t['keywords'], $lower, $lower),
                'h1' => sprintf($t['h1'], $name),
                'subtitle' => sprintf($t['subtitle'], $lower),
                'intro' => sprintf($t['intro'], $lower),
                'benefits' => $benefits[$lng],
                'features' => $features[$lng],
                'faq' => $faqs,
                'cta' => sprintf($t['cta'], $lower),
            ];
        }
        $out[$slug] = $entry;
    }
    return $out;
}

function bk_verticals_all(): array
{
    static $cache = null;
    if ($cache === null) {
        $file = __DIR__ . '/../data/verticals.php';
        $cache = is_file($file) ? require $file : bk_verticals_build();
    }
    return $cache;
}

function bk_vertical_slugs(): array
{
    return array_keys(bk_verticals_all());
}

function bk_vertical_by_slug(string $slug): ?array
{
    $all = bk_verticals_all();
    return $all[$slug] ?? null;
}

function bk_vertical_lang(array $vertical, string $lang): array
{
    $lang = in_array($lang, ['no', 'en', 'sv', 'uk', 'ru'], true) ? $lang : 'no';
    return $vertical[$lang] ?? $vertical['no'] ?? [];
}

function bk_vertical_url(string $slug, ?string $lang = null): string
{
    $path = bk_url($slug . '/');
    if ($lang && $lang !== 'no') {
        return $path . '?lang=' . urlencode($lang);
    }
    return $path;
}

function bk_vertical_canonical(string $slug): string
{
    global $site_url, $lang;
    $base = rtrim($site_url, '/') . '/' . $slug . '/';
    return $lang !== 'no' ? $base . '?lang=' . $lang : $base;
}

function bk_vertical_lang_url_for(string $slug, string $code): string
{
    return bk_vertical_url($slug, $code === 'no' ? null : $code);
}

function bk_seo_faq_page(array $items): array
{
    $entities = [];
    foreach ($items as $item) {
        $entities[] = [
            '@type'          => 'Question',
            'name'           => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $item['a'],
            ],
        ];
    }
    return [
        '@type'            => 'FAQPage',
        'mainEntity'       => $entities,
    ];
}

function bk_seo_vertical_service(string $name, string $description, string $canonical): array
{
    return [
        '@type'       => 'Service',
        '@id'         => $canonical . '#service',
        'name'        => $name,
        'description' => $description,
        'url'         => $canonical,
        'provider'    => ['@id' => 'https://bilohash.com/booking/#organization'],
        'areaServed'  => bk_market_area_served($GLOBALS['lang'] ?? 'en')['area_served'],
        'serviceType' => 'Online booking system development',
    ];
}

function bk_render_vertical_seo_head(
    string $page_title,
    string $page_desc,
    string $canonical,
    array $schema_graphs = [],
    ?string $page_keywords = null,
    ?string $og_image = null
): void {
    global $lang_meta, $lang;
    $og_image = $og_image ?: bk_seo_og_image();
    $canonical_abs = bk_absolute_url($canonical);
    $robots = 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $vertical_langs = ['no', 'en', 'sv', 'uk', 'ru'];
    $marketGeo = bk_seo_market_geo($lang);
    ?>
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if ($page_keywords): ?>
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <?php endif; ?>
    <meta name="robots" content="<?= $robots ?>">
    <meta name="author" content="Booking CMS">
    <meta name="geo.region" content="<?= htmlspecialchars($marketGeo['region']) ?>">
    <meta name="geo.placename" content="<?= htmlspecialchars($marketGeo['place']) ?>">
    <link rel="canonical" href="<?= htmlspecialchars($canonical_abs) ?>">
    <?php
    $slug = $GLOBALS['vertical_slug'] ?? '';
    foreach ($vertical_langs as $code):
        $alt = bk_absolute_url(bk_vertical_lang_url_for($slug, $code));
        $hreflang = $code === 'uk' ? 'uk' : $code;
    ?>
    <link rel="alternate" hreflang="<?= $hreflang ?>" href="<?= htmlspecialchars($alt) ?>">
    <?php endforeach; ?>
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(bk_absolute_url(bk_vertical_lang_url_for($slug, 'no'))) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_desc) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_abs) ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(BK_SITE_NAME) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($og_image) ?>">
    <meta property="og:locale" content="<?= htmlspecialchars(str_replace('-', '_', $lang_meta['locale'])) ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_desc) ?>">
    <?php if (!empty($schema_graphs)): ?>
    <script type="application/ld+json"><?= bk_seo_json(array_values(array_filter($schema_graphs))) ?></script>
    <?php endif;
}