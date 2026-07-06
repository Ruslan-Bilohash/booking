<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
$current_page = 'home';
$search_params = bk_search_params();
$destinations = bk_destinations($lang);
$popular = bk_popular_properties(4);
$deals = array_filter(bk_properties(), fn($p) => !empty($p['deal']));
$deals = array_slice(array_values($deals), 0, 4);
$canonical = $site_url . '/';
$page_title = $t['meta']['title'];
$page_desc  = $t['meta']['description'];
$canon_abs  = bk_absolute_url($canonical);
$seo_schemas = [
    bk_seo_organization(),
    bk_seo_website($canon_abs),
    bk_seo_software_app($canon_abs, $page_desc),
    bk_seo_webpage($canon_abs, $page_title, $page_desc),
    bk_seo_professional_service(),
];
require __DIR__ . '/includes/header.php';
?>

<section class="bk-hero">
    <div class="bk-hero-inner">
        <span class="bk-demo-badge"><?= htmlspecialchars($t['hero']['badge']) ?></span>
        <h1><?= htmlspecialchars($t['hero']['title']) ?></h1>
        <p><?= htmlspecialchars($t['hero']['subtitle']) ?></p>
        <?php $search_params = $search_params; require __DIR__ . '/includes/search-form.php'; ?>
    </div>
</section>

<section class="bk-about-script">
    <div class="bk-container bk-about-script-inner">
        <div class="bk-about-script-text">
            <h2><?= htmlspecialchars($t['about_script']['title']) ?></h2>
            <p><?= htmlspecialchars($t['about_script']['text']) ?></p>
            <?php if (bk_use_case_slugs()): ?>
            <p class="bk-about-use-label"><?= htmlspecialchars($t['about_script']['use_label'] ?? '') ?></p>
            <div class="bk-about-usecases">
                <?php foreach (bk_use_case_slugs() as $slug):
                    $vdef = bk_vertical_defs()[$slug] ?? null;
                    if (!$vdef) continue;
                    $label = $vdef[$lang] ?? $vdef['en'] ?? $slug;
                ?>
                <a href="<?= htmlspecialchars(bk_vertical_url($slug)) ?>" class="bk-about-usecase bk-about-usecase-link">
                    <i class="fas fa-<?= htmlspecialchars($vdef['icon'] ?? 'calendar-check') ?>" aria-hidden="true"></i>
                    <?= htmlspecialchars($label) ?>
                </a>
                <?php endforeach; ?>
            </div>
            <p class="bk-about-use-more">
                <a href="<?= bk_url('solutions.php') ?>"><?= htmlspecialchars(bk_vertical_hub_label($lang)) ?> →</a>
            </p>
            <?php endif; ?>
            <ul class="bk-about-features">
                <li><i class="fas fa-check-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['f1']) ?></li>
                <li><i class="fas fa-check-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['f2']) ?></li>
                <li><i class="fas fa-check-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['f3']) ?></li>
            </ul>
            <div class="bk-about-actions">
                <a href="<?= bk_url('index.php') ?>" class="bk-btn-blue"><i class="fas fa-play-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['demo_btn']) ?></a>
                <a href="<?= bk_url('admin/login.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-user-shield" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['admin_btn']) ?></a>
                <a href="<?= bk_url('site/') ?>" class="bk-btn-outline-dark"><i class="fas fa-book" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['product_btn']) ?></a>
                <a href="https://bilohash.com/news/booking-cms.html" class="bk-btn-outline-dark" target="_blank" rel="noopener noreferrer"><i class="fas fa-newspaper" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['news_btn']) ?></a>
                <a href="<?= bk_url('order.php') ?>" class="bk-btn-yellow"><i class="fas fa-laptop-code" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['order_btn']) ?></a>
                <a href="<?= bk_url('contact.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
            </div>
            <p class="bk-about-creds"><i class="fas fa-key" aria-hidden="true"></i> <?= htmlspecialchars($t['about_script']['creds']) ?></p>
        </div>
        <div class="bk-about-script-visual">
            <img src="<?= htmlspecialchars(bk_url('screen/home.svg')) ?>" alt="Booking CMS" width="1200" height="720" loading="lazy" fetchpriority="low" decoding="async" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
        </div>
    </div>
</section>

<div class="bk-container">
    <h2 class="bk-section-title"><?= htmlspecialchars($t['home']['trending']) ?></h2>
    <div class="bk-dest-grid">
        <?php foreach ($destinations as $d):
            $url = bk_url('search.php?' . http_build_query(['destination' => $d['city'], 'checkin' => $search_params['checkin'], 'checkout' => $search_params['checkout'], 'adults' => $search_params['adults'], 'rooms' => $search_params['rooms']]));
        ?>
        <a href="<?= htmlspecialchars($url) ?>" class="bk-dest-card">
            <img src="<?= htmlspecialchars($d['image']) ?>" alt="<?= htmlspecialchars($d['city']) ?>" width="400" height="300" loading="lazy" decoding="async" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
            <div class="bk-dest-overlay">
                <strong><?= htmlspecialchars($d['city']) ?></strong>
                <span><?= (int)$d['count'] ?> <?= htmlspecialchars($t['home']['properties']) ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="bk-container" style="padding-top:0">
    <h2 class="bk-section-title"><?= htmlspecialchars($t['home']['browse_type']) ?></h2>
    <div class="bk-type-grid">
        <?php foreach (['hotel', 'apartment', 'cabin'] as $type): ?>
        <a href="<?= bk_url('search.php?type=' . $type) ?>" class="bk-type-card">
            <i class="fas fa-<?= $type === 'hotel' ? 'hotel' : ($type === 'apartment' ? 'building' : 'tree') ?>"></i>
            <strong><?= htmlspecialchars($t['types'][$type]) ?></strong>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="bk-container" style="padding-top:0">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px">
        <h2 class="bk-section-title" style="margin:0"><?= htmlspecialchars($t['home']['deals']) ?></h2>
        <span style="color:var(--bk-gray-500);font-size:13px"><?= htmlspecialchars($t['home']['deals_sub']) ?></span>
    </div>
    <div class="bk-prop-grid">
        <?php foreach ($deals as $property):
            $search_params = $search_params;
            require __DIR__ . '/includes/property-card.php';
        endforeach; ?>
    </div>
</div>

<div class="bk-container" style="padding-top:0">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
        <h2 class="bk-section-title" style="margin:0"><?= htmlspecialchars($t['home']['popular']) ?></h2>
        <a href="<?= bk_url('search.php') ?>"><?= htmlspecialchars($t['home']['view_all']) ?> →</a>
    </div>
    <div class="bk-prop-grid">
        <?php foreach ($popular as $property):
            require __DIR__ . '/includes/property-card.php';
        endforeach; ?>
    </div>
</div>

<div class="bk-container" style="padding-top:0;padding-bottom:40px">
    <h2 class="bk-section-title"><?= htmlspecialchars($t['home']['why']) ?></h2>
    <div class="bk-why-grid">
        <div class="bk-why-item">
            <i class="fas fa-tag"></i>
            <h4><?= htmlspecialchars($t['home']['why_1_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_1_d']) ?></p>
        </div>
        <div class="bk-why-item">
            <i class="fas fa-calendar-xmark"></i>
            <h4><?= htmlspecialchars($t['home']['why_2_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_2_d']) ?></p>
        </div>
        <div class="bk-why-item">
            <i class="fas fa-headset"></i>
            <h4><?= htmlspecialchars($t['home']['why_3_t']) ?></h4>
            <p><?= htmlspecialchars($t['home']['why_3_d']) ?></p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>