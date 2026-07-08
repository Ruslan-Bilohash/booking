<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
require_once __DIR__ . '/site-integrations.php';
bk_boot_public_integrations();
$cms_nav_discuss = cms_contact_texts('booking', $lang)['nav_discuss'];
$page_title = $page_title ?? $t['meta']['title'];
$page_desc  = $page_desc ?? $t['meta']['description'];
$canonical  = $canonical ?? $site_url . '/';
$body_class = $body_class ?? '';
$seo_schemas = $seo_schemas ?? [];
$seo_og_image = $seo_og_image ?? null;
$seo_og_type  = $seo_og_type ?? 'website';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php bk_render_public_stylesheets(); ?>
    <?php bk_render_theme_styles(bk_site_settings()); ?>
    <?php bk_render_seo_head($page_title, $page_desc, $canonical, [], $seo_og_image, $seo_og_type, !empty($seo_noindex)); ?>
    <?php if ((!empty($cms_prefix) && $cms_prefix !== 'fl') || ($current_page ?? '') === 'contact'): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(cms_contact_stylesheet_href()) ?>">
    <?php endif; ?>
    <?php bk_render_favicon_tag(bk_site_settings()); ?>
    <?php
    require_once __DIR__ . '/analytics-settings.php';
    bk_render_tracking_snippets(bk_site_settings());
    $customHead = trim((string) (bk_site_settings()['custom_head_code'] ?? ''));
    if ($customHead !== '') {
        echo $customHead;
    }
    ?>
    <?php bk_render_seo_social($page_title, $page_desc, bk_absolute_url($canonical), $seo_og_image, $seo_og_type ?? 'website'); ?>
</head>
<body class="<?= htmlspecialchars($body_class) ?>">

<div class="bk-top-bar">
<?php
require_once __DIR__ . '/billing-pricing.php';
bk_billing_render_demo_banner($t, $lang);
?>
<div class="bk-demo-strip" role="status">
    <div class="bk-demo-strip-main">
        <i class="fas fa-hard-hat" aria-hidden="true"></i>
        <a href="https://bilohash.com/" class="bk-demo-strip-back" rel="home">← <?= htmlspecialchars($t['demo_strip']['back'] ?? 'bilohash.com') ?></a>
        <span class="bk-demo-strip-sep" aria-hidden="true">·</span>
        <span><?= htmlspecialchars($t['demo_strip']['text']) ?></span>
        <a href="<?= bk_url('site/') ?>"><?= htmlspecialchars($t['demo_strip']['cms']) ?> →</a>
    </div>
</div>

<header class="bk-header" id="bkHeader" itemscope itemtype="https://schema.org/WPHeader">
    <div class="bk-header-inner">
        <a href="<?= bk_url('index.php') ?>" class="bk-logo" itemprop="url">
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
            <nav class="bk-nav" aria-label="<?= htmlspecialchars($t['a11y']['main_nav'] ?? 'Main') ?>">
                <a href="<?= bk_url('solutions.php') ?>" class="<?= ($current_page ?? '') === 'solutions' ? 'active' : '' ?>"><?= htmlspecialchars(bk_vertical_hub_label($lang)) ?></a>
                <a href="<?= bk_url('index.php') ?>" class="<?= ($current_page ?? '') === 'home' ? 'active' : '' ?>"><?= htmlspecialchars($t['nav']['stays']) ?></a>
                <a href="<?= bk_url('search.php') ?>"><?= htmlspecialchars($t['nav']['flights']) ?></a>
                <a href="<?= bk_url('search.php?type=apartment') ?>"><?= htmlspecialchars($t['nav']['cars']) ?></a>
                <a href="<?= bk_url('search.php') ?>"><?= htmlspecialchars($t['nav']['attractions']) ?></a>
            </nav>
            <div class="bk-header-actions">
                <a href="<?= bk_url('contact.php') ?>" class="bk-btn-yellow bk-btn-discuss <?= ($current_page ?? '') === 'contact' ? 'active' : '' ?>"><i class="fas fa-comments" aria-hidden="true"></i> <?= htmlspecialchars($cms_nav_discuss) ?></a>
                <a href="<?= bk_url('site/') ?>" class="bk-btn-outline"><?= htmlspecialchars($t['demo_strip']['cms']) ?></a>
                <a href="<?= bk_url('admin/login.php') ?>" class="bk-btn-outline"><i class="fas fa-user-shield" aria-hidden="true"></i> <?= htmlspecialchars($t['nav']['admin']) ?></a>
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