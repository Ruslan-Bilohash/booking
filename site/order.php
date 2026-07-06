<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';

$o = $t['order'] ?? [];
$cms_discuss = cms_contact_texts('booking', $lang)['nav_discuss'];

$page_title = $o['page_title'] ?? 'Order booking website development | Booking CMS';
$page_desc  = $o['meta_description'] ?? '';
$canonical  = $site_url . '/order.php' . ($lang !== 'no' ? '?lang=' . $lang : '');
$canon_abs  = bks_absolute_url($canonical);
$seo_schemas = bks_seo_schemas($canon_abs, $page_title, $page_desc);

require __DIR__ . '/includes/header.php';
?>

<section class="bks-order-hero">
    <div class="bks-container">
        <div class="bks-section-head">
            <h1><?= htmlspecialchars($o['h1'] ?? '') ?></h1>
            <p class="bks-section-sub"><?= htmlspecialchars($o['subtitle'] ?? '') ?></p>
        </div>
        <p class="bks-order-intro"><?= htmlspecialchars($o['intro'] ?? '') ?></p>
        <div class="bks-page-cta">
            <a href="<?= bks_url('contact.php') ?>" class="bks-btn-primary bks-btn-lg"><i class="fas fa-comments"></i> <?= htmlspecialchars($o['cta_contact'] ?? $cms_discuss) ?></a>
            <a href="<?= bks_demo_url() ?>" class="bks-btn-outline bks-btn-lg"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($o['cta_demo'] ?? '') ?></a>
            <a href="https://bilohash.com/" rel="author" class="bks-btn-ghost bks-btn-lg"><i class="fas fa-globe"></i> <?= htmlspecialchars($o['cta_portfolio'] ?? 'bilohash.com') ?></a>
        </div>
    </div>
</section>

<section class="bks-section bks-order-body">
    <div class="bks-container">
        <div class="bks-order-block">
            <h2 class="bks-order-heading"><?= htmlspecialchars($o['benefits_title'] ?? '') ?></h2>
            <div class="bks-features-grid bks-features-grid--order">
                <?php foreach ($o['benefits'] ?? [] as $b): ?>
                <article class="bks-feature-card">
                    <h3><?= htmlspecialchars($b['title'] ?? '') ?></h3>
                    <p><?= htmlspecialchars($b['text'] ?? '') ?></p>
                </article>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bks-order-block">
            <h2 class="bks-order-heading"><?= htmlspecialchars($o['steps_title'] ?? '') ?></h2>
            <ol class="bks-order-steps">
                <?php foreach ($o['steps'] ?? [] as $step): ?>
                <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <div class="bks-order-block">
            <h2 class="bks-order-heading"><?= htmlspecialchars($o['crosslinks_title'] ?? '') ?></h2>
            <div class="bks-demo-grid bks-demo-grid--order">
                <a href="<?= bks_demo_url() ?>" class="bks-demo-card bks-demo-card--link"><i class="fas fa-play-circle" aria-hidden="true"></i><span><?= htmlspecialchars($o['cta_demo'] ?? '') ?></span></a>
                <a href="<?= bks_demo_url('solutions.php') ?>" class="bks-demo-card bks-demo-card--link"><i class="fas fa-th-large" aria-hidden="true"></i><span><?= htmlspecialchars($o['cta_solutions'] ?? '') ?></span></a>
                <a href="<?= bks_url('contact.php') ?>" class="bks-demo-card bks-demo-card--link"><i class="fas fa-envelope" aria-hidden="true"></i><span><?= htmlspecialchars($cms_discuss) ?></span></a>
                <a href="https://bilohash.com/news/booking-cms.html" rel="related" class="bks-demo-card bks-demo-card--link"><i class="fas fa-newspaper" aria-hidden="true"></i><span><?= htmlspecialchars($t['footer']['news'] ?? 'News') ?></span></a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>