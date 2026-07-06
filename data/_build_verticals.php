<?php
/** One-time builder — run: php data/_build_verticals.php */

$defs = [
    'hotell-overnatting' => ['icon' => 'hotel', 'demo_param' => 'type=hotel', 'no' => 'Hotell & overnatting', 'en' => 'Hotels & accommodation', 'uk' => 'Готелі та проживання'],
    'leiebolig'          => ['icon' => 'home', 'demo_param' => 'type=apartment', 'no' => 'Leiebolig', 'en' => 'Rental property', 'uk' => 'Оренда житла'],
    'legetime'           => ['icon' => 'user-md', 'demo_param' => '', 'no' => 'Legetime', 'en' => 'Doctor appointments', 'uk' => 'Запис до лікаря'],
    'tannlege'           => ['icon' => 'tooth', 'demo_param' => '', 'no' => 'Tannlege', 'en' => 'Dental clinic', 'uk' => 'Стоматологія'],
    'skjonnhetssalong'   => ['icon' => 'spa', 'demo_param' => '', 'no' => 'Skjønnhetssalong', 'en' => 'Beauty salon', 'uk' => 'Салон краси'],
    'spa-massasje'       => ['icon' => 'hot-tub', 'demo_param' => '', 'no' => 'Spa & massasje', 'en' => 'Spa & massage', 'uk' => 'Спа та масаж'],
    'trening-kurs'       => ['icon' => 'dumbbell', 'demo_param' => '', 'no' => 'Trening & kurs', 'en' => 'Training & courses', 'uk' => 'Тренування та курси'],
    'utstyrutleie'       => ['icon' => 'toolbox', 'demo_param' => '', 'no' => 'Utstyrutleie', 'en' => 'Equipment rental', 'uk' => 'Оренда обладнання'],
];

$tpl = [
    'no' => [
        'title' => 'Bestill %s bookingsystem Norge | Booking CMS',
        'description' => 'Booking CMS — PHP bookingsystem for %s i Norge og Europa. Online reservasjon, adminpanel, flerspråklig SEO og live demo. Bestill skreddersydd løsning.',
        'keywords' => 'bestill %s bookingsystem Norge, PHP booking %s, online reservasjon Norge, Booking CMS, bookingsystem Europa, adminpanel booking, flerspråklig booking',
        'subtitle' => 'Profesjonelt PHP bookingsystem for %s — bygget for norske og europeiske bedrifter',
        'intro' => 'Booking CMS er en modulær PHP-plattform for online reservasjon av %s. Live demo med søk, kalender, adminpanel og JSON-lagring — klar til tilpasning med Vipps, Stripe, SMS-varsler og flere språk.',
        'cta' => 'Klar for et bookingsystem for %s? Få et tilbud i dag.',
    ],
    'en' => [
        'title' => 'Order %s Booking System Norway | Booking CMS',
        'description' => 'Booking CMS — PHP booking platform for %s in Norway & Europe. Online reservations, admin panel, multilingual SEO and live demo. Order a custom solution.',
        'keywords' => 'order %s booking system Norway, PHP booking %s, online reservation Europe, Booking CMS, booking software Norway, multilingual booking script',
        'subtitle' => 'Professional PHP booking system for %s — built for Norwegian and European businesses',
        'intro' => 'Booking CMS is a modular PHP platform for online %s reservations. Live demo with search, calendar, admin panel and JSON storage — ready for Vipps, Stripe, SMS alerts and extra languages.',
        'cta' => 'Ready for a %s booking system? Get a quote today.',
    ],
    'uk' => [
        'title' => 'Замовити систему бронювання %s | Booking CMS',
        'description' => 'Booking CMS — PHP-платформа бронювання для %s у Норвегії та Європі. Онлайн-запис, адмін-панель, багатомовне SEO та live demo. Замовити індивідуальне рішення.',
        'keywords' => 'замовити бронювання %s Норвегія, PHP booking %s, система запису Європа, Booking CMS, скрипт бронювання, багатомовне бронювання',
        'subtitle' => 'Професійна PHP-система бронювання для %s — для бізнесу в Норвегії та Європі',
        'intro' => 'Booking CMS — модульна PHP-платформа для онлайн-бронювання %s. Live demo з пошуком, календарем, адмін-панеллю та JSON-сховищем — готова до Vipps, Stripe, SMS та додаткових мов.',
        'cta' => 'Потрібна система бронювання для %s? Отримайте пропозицію сьогодні.',
    ],
];

$benefits_tpl = [
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
];

$features_tpl = [
    'no' => ['Online kalender og tilgjengelighet', 'Søk, filtre og responsive design', 'E-post/SMS-varsler (klar for integrasjon)', 'Adminpanel med dashboard', 'JSON-lagring — enkel migrering', 'GDPR-vennlig cookie-samtykke'],
    'en' => ['Online calendar and availability', 'Search, filters and responsive design', 'Email/SMS notifications (integration-ready)', 'Admin panel with dashboard', 'JSON storage — easy migration', 'GDPR-friendly cookie consent'],
    'uk' => ['Онлайн-календар і доступність', 'Пошук, фільтри та адаптивний дизайн', 'Email/SMS сповіщення (готово до інтеграції)', 'Адмін-панель з дашбордом', 'JSON-сховище — легка міграція', 'GDPR cookie consent'],
];

$faq_tpl = [
    'no' => [
        ['q' => 'Kan Booking CMS tilpasses %s?', 'a' => 'Ja. Vi tilpasser felt, varighet, priser, betaling og varsler for din %s-virksomhet i Norge eller Europa.'],
        ['q' => 'Hvilke språk støttes?', 'a' => 'Demoen har norsk (standard), engelsk og ukrainsk. Flere språk kan legges til raskt.'],
        ['q' => 'Trenger jeg egen server?', 'a' => 'Nei. PHP 8+ på delt hosting eller VPS i Norge/EU er nok for start.'],
        ['q' => 'Hvordan bestiller jeg utvikling?', 'a' => 'Kontakt via bilohash.com med kort beskrivelse av %s-behovet — vi leverer tilbud og tidsplan.'],
    ],
    'en' => [
        ['q' => 'Can Booking CMS be customized for %s?', 'a' => 'Yes. We adapt fields, duration, pricing, payments and notifications for your %s business in Norway or Europe.'],
        ['q' => 'Which languages are supported?', 'a' => 'The demo includes Norwegian (default), English and Ukrainian. More languages can be added quickly.'],
        ['q' => 'Do I need a dedicated server?', 'a' => 'No. PHP 8+ on shared hosting or a VPS in Norway/EU is enough to start.'],
        ['q' => 'How do I order development?', 'a' => 'Contact via bilohash.com with a short brief about your %s needs — we provide a quote and timeline.'],
    ],
    'uk' => [
        ['q' => 'Чи можна адаптувати Booking CMS під %s?', 'a' => 'Так. Ми налаштовуємо поля, тривалість, ціни, оплату та сповіщення для вашого %s-бізнесу в Норвегії чи Європі.'],
        ['q' => 'Які мови підтримуються?', 'a' => 'У демо — норвезька (за замовчуванням), англійська та українська. Інші мови додаються швидко.'],
        ['q' => 'Чи потрібен окремий сервер?', 'a' => 'Ні. Достатньо PHP 8+ на shared hosting або VPS у Норвегії/ЄС.'],
        ['q' => 'Як замовити розробку?', 'a' => 'Зв\'яжіться через bilohash.com з коротким описом потреб для %s — надішлемо пропозицію та терміни.'],
    ],
];

$out = [];
foreach ($defs as $slug => $def) {
    $entry = ['icon' => $def['icon'], 'demo_param' => $def['demo_param']];
    foreach (['no', 'en', 'uk', 'ru'] as $lng) {
        $name = $def[$lng];
        $t = $tpl[$lng];
        $entry[$lng] = [
            'title' => sprintf($t['title'], $name),
            'description' => sprintf($t['description'], mb_strtolower($name)),
            'keywords' => sprintf($t['keywords'], mb_strtolower($name), mb_strtolower($name)),
            'h1' => 'Booking CMS for ' . $name,
            'subtitle' => sprintf($t['subtitle'], mb_strtolower($name)),
            'intro' => sprintf($t['intro'], mb_strtolower($name)),
            'benefits' => $benefits_tpl[$lng],
            'features' => $features_tpl[$lng],
            'faq' => array_map(fn($f) => ['q' => sprintf($f['q'], mb_strtolower($name)), 'a' => sprintf($f['a'], mb_strtolower($name))], $faq_tpl[$lng]),
            'cta' => sprintf($t['cta'], mb_strtolower($name)),
        ];
        if ($lng === 'no') {
            $entry[$lng]['h1'] = 'Booking CMS for ' . $def['no'];
        }
        if ($lng === 'uk') {
            $entry[$lng]['h1'] = 'Booking CMS — ' . $def['uk'];
        }
    }
    $out[$slug] = $entry;
}

$export = "<?php\nreturn " . var_export($out, true) . ";\n";
file_put_contents(__DIR__ . '/verticals.php', $export);
echo "Written verticals.php with " . count($out) . " verticals\n";