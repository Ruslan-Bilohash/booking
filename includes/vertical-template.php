<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
/** @var string $slug @var array $vertical @var array $v @var string $canonical @var array $seo_schemas */
$current_page = 'vertical';
$hub_label = bk_vertical_hub_label($lang);
$vx = $t['vertical'] ?? [];
$region_note = $vx['region'] ?? '';
$benefits_title = $vx['benefits'] ?? 'Benefits';
$features_title = $vx['features'] ?? 'Booking CMS features';
$faq_title = $vx['faq'] ?? 'FAQ';
$related_title = $vx['related'] ?? 'More booking solutions';
$all = bk_verticals_all();
$demo_q = trim($vertical['demo_param'] ?? '');
$demo_url = bk_url('search.php' . ($demo_q ? '?' . $demo_q : ''));
$canon_abs = bk_absolute_url($canonical);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php bk_render_vertical_seo_head($page_title, $page_desc, $canonical, $seo_schemas, $v['keywords'] ?? null); ?>
    <?php
    require_once __DIR__ . '/site-settings.php';
    bk_render_public_stylesheets();
    require_once __DIR__ . '/site-integrations.php';
    bk_boot_public_integrations();
    bk_render_favicon_tag(bk_site_settings());
    ?>
</head>
<body class="bk-vertical-page">

<div class="bk-top-bar">
<div class="bk-demo-strip" role="status">
    <div class="bk-demo-strip-main">
        <i class="fas fa-hard-hat" aria-hidden="true"></i>
        <a href="https://bilohash.com/" class="bk-demo-strip-back" rel="home">← <?= htmlspecialchars($t['demo_strip']['back'] ?? 'bilohash.com') ?></a>
        <span class="bk-demo-strip-sep" aria-hidden="true">·</span>
        <span><?= htmlspecialchars($t['demo_strip']['text']) ?></span>
        <a href="<?= bk_url('site/') ?>"><?= htmlspecialchars($t['demo_strip']['cms']) ?> →</a>
    </div>
</div>
<header class="bk-header" id="bkHeader">
    <div class="bk-header-inner">
        <a href="<?= bk_url('index.php') ?>" class="bk-logo">
            <span class="bk-logo-icon">B</span>
            <span class="bk-logo-text" itemprop="name"><?= htmlspecialchars($t['meta']['site_name']) ?></span>
        </a>
        <div class="bk-header-panel" id="bkHeaderPanel">
            <div class="bk-panel-head">
                <span class="bk-panel-title"><?= htmlspecialchars($t['meta']['site_name']) ?></span>
                <button type="button" class="bk-menu-close" id="bkMenuClose" aria-label="<?= htmlspecialchars($t['a11y']['close_menu'] ?? 'Close menu') ?>">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <nav class="bk-nav" aria-label="Main">
                <a href="<?= bk_url('solutions.php') ?>"><?= htmlspecialchars($hub_label) ?></a>
                <a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['nav']['stays']) ?></a>
                <a href="<?= bk_url('search.php') ?>"><?= htmlspecialchars($t['nav']['flights']) ?></a>
                <a href="<?= bk_url('contact.php') ?>"><?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
            </nav>
            <div class="bk-header-actions">
                <a href="<?= bk_url('site/') ?>" class="bk-btn-outline"><?= htmlspecialchars($t['demo_strip']['cms']) ?></a>
                <a href="<?= bk_url('order.php') ?>" class="bk-btn-outline"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($t['nav']['order'] ?? '') ?></a>
                <a href="<?= bk_url('admin/login.php') ?>" class="bk-btn-outline"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($t['nav']['admin'] ?? 'Admin') ?></a>
                <?php $lang_dropdown_variant = 'header'; require __DIR__ . '/lang-dropdown.php'; unset($lang_dropdown_variant); ?>
            </div>
        </div>
        <div class="bk-header-mobile-tools">
            <?php $lang_dropdown_variant = 'mobile'; require __DIR__ . '/lang-dropdown.php'; unset($lang_dropdown_variant); ?>
            <button type="button" class="bk-menu-toggle" id="bkMenuBtn" aria-label="<?= htmlspecialchars($t['a11y']['menu'] ?? 'Menu') ?>" aria-expanded="false" aria-controls="bkHeaderPanel">
                <i class="fas fa-bars bk-menu-icon-open" aria-hidden="true"></i>
                <i class="fas fa-times bk-menu-icon-close" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</header>
<div class="bk-overlay" id="bkOverlay" hidden></div>
</div>

<main class="bk-container bk-vertical-main">
    <nav class="bk-vertical-crumb" aria-label="<?= htmlspecialchars($t['a11y']['breadcrumb'] ?? 'Breadcrumb') ?>">
        <a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= bk_url('solutions.php') ?>"><?= htmlspecialchars($hub_label) ?></a>
        → <?= htmlspecialchars($v['h1']) ?>
    </nav>

    <section class="bk-vertical-hero">
        <div class="bk-vertical-hero-icon"><i class="fas fa-<?= htmlspecialchars($vertical['icon'] ?? 'calendar-check') ?>"></i></div>
        <h1><?= htmlspecialchars($v['h1']) ?></h1>
        <p class="bk-vertical-subtitle"><?= htmlspecialchars($v['subtitle']) ?></p>
        <p class="bk-vertical-intro"><?= htmlspecialchars($v['intro']) ?></p>
        <p class="bk-vertical-region"><i class="fas fa-globe-europe" aria-hidden="true"></i> <?= htmlspecialchars($region_note) ?></p>
        <div class="bk-vertical-cta-row">
            <a href="<?= htmlspecialchars($demo_url) ?>" class="bk-btn-blue"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($vx['live_demo'] ?? 'Live demo') ?></a>
            <a href="<?= bk_url('site/') ?>" class="bk-btn-outline-dark"><i class="fas fa-book"></i> <?= htmlspecialchars($vx['product_page'] ?? 'Product page') ?></a>
            <a href="<?= bk_url('order.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($t['nav']['order'] ?? '') ?></a>
            <a href="<?= bk_url('contact.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
        </div>
    </section>

    <section class="bk-vertical-section">
        <h2><?= htmlspecialchars($benefits_title) ?></h2>
        <div class="bk-vertical-benefits">
            <?php foreach ($v['benefits'] ?? [] as $b): ?>
            <article class="bk-vertical-benefit">
                <h3><?= htmlspecialchars($b['title']) ?></h3>
                <p><?= htmlspecialchars($b['text']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bk-vertical-section">
        <h2><?= htmlspecialchars($features_title) ?></h2>
        <ul class="bk-vertical-features">
            <?php foreach ($v['features'] ?? [] as $f): ?>
            <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($f) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>

    <?php if (!empty($v['faq'])): ?>
    <section class="bk-vertical-section bk-vertical-faq">
        <h2><?= htmlspecialchars($faq_title) ?></h2>
        <div class="bk-faq-list">
            <?php foreach ($v['faq'] as $i => $item): ?>
            <details class="bk-faq-item">
                <summary><?= htmlspecialchars($item['q']) ?></summary>
                <p><?= htmlspecialchars($item['a']) ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="bk-vertical-section bk-vertical-related">
        <h2><?= htmlspecialchars($related_title) ?></h2>
        <div class="bk-vertical-links">
            <?php
            $vdefs = bk_vertical_defs();
            foreach ($all as $s => $item):
                if ($s === $slug) continue;
                $short = $vdefs[$s][$lang] ?? $vdefs[$s]['en'] ?? $s;
            ?>
            <a href="<?= htmlspecialchars(bk_vertical_url($s)) ?>" class="bk-vertical-link-card">
                <i class="fas fa-<?= htmlspecialchars($item['icon'] ?? 'tag') ?>"></i>
                <strong><?= htmlspecialchars($short) ?></strong>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bk-vertical-cta-band">
        <h2><?= htmlspecialchars($v['cta'] ?? '') ?></h2>
        <a href="<?= bk_url('contact.php') ?>" class="bk-btn-blue" style="display:block;text-align:center"><?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
    </section>
</main>

<?php require __DIR__ . '/footer.php'; ?>