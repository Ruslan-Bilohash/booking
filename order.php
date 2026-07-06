<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';

$current_page = 'order';
$o = $t['order'] ?? [];
$cms_discuss = cms_contact_texts('booking', $lang)['nav_discuss'];

$page_title = $o['page_title'] ?? 'Order booking website development | Booking CMS';
$page_desc  = $o['meta_description'] ?? '';
$canonical  = $site_url . '/order.php' . ($lang !== 'no' ? '?lang=' . $lang : '');
$canon_abs  = bk_absolute_url($canonical);
$seo_schemas = [
    bk_seo_organization(),
    bk_seo_webpage($canon_abs, $page_title, $page_desc),
    bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => $o['h1'] ?? $page_title, 'url' => $canon_abs],
    ]),
    bk_seo_professional_service(),
];

require __DIR__ . '/includes/header.php';
?>

<main class="bk-container bk-order-page">
    <nav class="bk-vertical-crumb" aria-label="<?= htmlspecialchars($t['a11y']['breadcrumb'] ?? 'Breadcrumb') ?>">
        <a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <?= htmlspecialchars($o['h1'] ?? '') ?>
    </nav>

    <section class="bk-vertical-hero">
        <div class="bk-vertical-hero-icon"><i class="fas fa-laptop-code" aria-hidden="true"></i></div>
        <h1><?= htmlspecialchars($o['h1'] ?? '') ?></h1>
        <p class="bk-vertical-subtitle"><?= htmlspecialchars($o['subtitle'] ?? '') ?></p>
        <p class="bk-vertical-intro"><?= htmlspecialchars($o['intro'] ?? '') ?></p>
        <div class="bk-vertical-cta-row">
            <a href="<?= bk_url('contact.php') ?>" class="bk-btn-blue"><i class="fas fa-comments"></i> <?= htmlspecialchars($o['cta_contact'] ?? $cms_discuss) ?></a>
            <a href="<?= bk_url('index.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($o['cta_demo'] ?? '') ?></a>
            <a href="<?= bk_url('site/') ?>" class="bk-btn-outline-dark"><i class="fas fa-book"></i> <?= htmlspecialchars($o['cta_product'] ?? '') ?></a>
        </div>
    </section>

    <section class="bk-vertical-section">
        <h2><?= htmlspecialchars($o['benefits_title'] ?? '') ?></h2>
        <div class="bk-vertical-benefits">
            <?php foreach ($o['benefits'] ?? [] as $b): ?>
            <article class="bk-vertical-benefit">
                <h3><?= htmlspecialchars($b['title'] ?? '') ?></h3>
                <p><?= htmlspecialchars($b['text'] ?? '') ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="bk-vertical-section">
        <h2><?= htmlspecialchars($o['steps_title'] ?? '') ?></h2>
        <ol class="bk-order-steps">
            <?php foreach ($o['steps'] ?? [] as $step): ?>
            <li><?= htmlspecialchars($step) ?></li>
            <?php endforeach; ?>
        </ol>
    </section>

    <section class="bk-vertical-section">
        <h2><?= htmlspecialchars($o['crosslinks_title'] ?? '') ?></h2>
        <div class="bk-vertical-links bk-order-crosslinks">
            <a href="<?= bk_url('index.php') ?>" class="bk-vertical-link-card">
                <i class="fas fa-play-circle"></i>
                <strong><?= htmlspecialchars($o['cta_demo'] ?? '') ?></strong>
            </a>
            <a href="<?= bk_url('site/') ?>" class="bk-vertical-link-card">
                <i class="fas fa-book"></i>
                <strong><?= htmlspecialchars($o['cta_product'] ?? '') ?></strong>
            </a>
            <a href="<?= bk_url('solutions.php') ?>" class="bk-vertical-link-card">
                <i class="fas fa-th-large"></i>
                <strong><?= htmlspecialchars($o['cta_solutions'] ?? '') ?></strong>
            </a>
            <a href="<?= bk_url('contact.php') ?>" class="bk-vertical-link-card">
                <i class="fas fa-envelope"></i>
                <strong><?= htmlspecialchars($cms_discuss) ?></strong>
            </a>
            <a href="https://bilohash.com/" rel="author" class="bk-vertical-link-card">
                <i class="fas fa-globe"></i>
                <strong><?= htmlspecialchars($o['cta_portfolio'] ?? 'bilohash.com') ?></strong>
            </a>
            <a href="https://bilohash.com/news/booking-cms.html" rel="related" class="bk-vertical-link-card">
                <i class="fas fa-newspaper"></i>
                <strong><?= htmlspecialchars($t['footer']['news'] ?? 'News') ?></strong>
            </a>
        </div>
    </section>

    <section class="bk-vertical-cta-band">
        <h2><?= htmlspecialchars($o['h1'] ?? '') ?></h2>
        <a href="<?= bk_url('contact.php') ?>" class="bk-btn-blue" style="display:block;text-align:center"><?= htmlspecialchars($o['cta_contact'] ?? $cms_discuss) ?></a>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>