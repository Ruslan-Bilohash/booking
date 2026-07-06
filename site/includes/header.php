<?php
require_once __DIR__ . '/seo.php';
require_once dirname(__DIR__, 3) . '/includes/cms-contact.php';
$cms_nav_discuss = cms_contact_texts('booking', $lang)['nav_discuss'];
$page_title = $page_title ?? $t['meta']['title'];
$page_desc  = $page_desc ?? $t['meta']['description'];
$canonical  = $canonical ?? $site_url . '/';
$seo_schemas = $seo_schemas ?? bks_seo_schemas(bks_absolute_url($canonical), $page_title, $page_desc);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php bks_render_seo_head($page_title, $page_desc, $canonical, $seo_schemas); ?>
    <?php bks_render_stylesheets(); ?>
    <?php if (!empty($cms_prefix) && $cms_prefix !== 'fl'): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(cms_contact_stylesheet_href()) ?>">
    <?php endif; ?>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%23003580' width='100' height='100' rx='12'/><text x='50' y='58' font-size='36' text-anchor='middle' fill='%23febb02' font-family='sans-serif' font-weight='bold'>B</text></svg>">
</head>
<body>

<header class="bks-header" id="bksHeader">
    <div class="bks-header-inner">
        <a href="<?= bks_url('index.php') ?>" class="bks-logo">
            <span class="bks-logo-icon">B</span>
            <span class="bks-logo-text">Booking <em>CMS</em></span>
        </a>
        <div class="bks-header-tools">
            <?php require __DIR__ . '/lang-dropdown.php'; ?>
            <button class="bks-menu-toggle" id="bksMenuBtn" aria-label="<?= htmlspecialchars($t['a11y']['menu'] ?? 'Menu') ?>" type="button" aria-expanded="false" aria-controls="bksMobilePanel">
                <i class="fas fa-bars bks-menu-icon-open" aria-hidden="true"></i>
                <i class="fas fa-times bks-menu-icon-close" aria-hidden="true"></i>
            </button>
        </div>
        <div class="bks-header-actions">
            <a href="<?= bks_demo_url() ?>" class="bks-btn-ghost"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($t['demo']['frontend']) ?></a>
            <a href="<?= bks_demo_url('admin/login.php') ?>" class="bks-btn-ghost"><i class="fas fa-lock"></i> <?= htmlspecialchars($t['nav']['admin']) ?></a>
            <a href="<?= bks_url('order.php') ?>" class="bks-btn-ghost"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($t['nav']['order'] ?? '') ?></a>
            <a href="<?= bks_url('contact.php') ?>" class="bks-btn-primary"><i class="fas fa-comments"></i> <?= htmlspecialchars($cms_nav_discuss) ?></a>
        </div>
    </div>
    <div class="bks-mobile-panel" id="bksMobilePanel" hidden>
        <nav class="bks-nav-mobile" aria-label="<?= htmlspecialchars($t['a11y']['menu'] ?? 'Menu') ?>">
            <a href="<?= bks_demo_url() ?>"><i class="fas fa-play-circle" aria-hidden="true"></i> <?= htmlspecialchars($t['demo']['frontend']) ?></a>
            <a href="<?= bks_demo_url('admin/login.php') ?>"><i class="fas fa-lock" aria-hidden="true"></i> <?= htmlspecialchars($t['nav']['admin']) ?></a>
            <a href="<?= bks_url('order.php') ?>"><i class="fas fa-laptop-code" aria-hidden="true"></i> <?= htmlspecialchars($t['nav']['order'] ?? '') ?></a>
        </nav>
        <?php require __DIR__ . '/ecosystem-mobile-block.php'; ?>
    </div>
</header>
<div class="bks-overlay" id="bksOverlay" hidden></div>