<?php
/** @var string $admin_page @var array $ta @var string $page_title */
$layout_title = $page_title ?? $ta['dashboard'];
$layout_desc = bk_admin_nav_desc($admin_page ?? 'dashboard', $ta);
$admin_user = $_SESSION['bk_admin_user'] ?? 'demo';

$adm_main_nav = [
    ['slug' => 'dashboard', 'url' => 'index.php', 'label' => $ta['dashboard'], 'desc' => $ta['nav_desc_dashboard'] ?? '', 'icon' => 'fa-chart-pie'],
    ['slug' => 'properties', 'url' => 'properties.php', 'label' => $ta['properties'], 'desc' => $ta['nav_desc_properties'] ?? '', 'icon' => 'fa-hotel'],
    ['slug' => 'bookings', 'url' => 'bookings.php', 'label' => $ta['bookings'], 'desc' => $ta['nav_desc_bookings'] ?? '', 'icon' => 'fa-calendar-check'],
    ['slug' => 'reviews', 'url' => 'reviews.php', 'label' => $ta['guest_reviews'], 'desc' => $ta['nav_desc_reviews'] ?? '', 'icon' => 'fa-star'],
];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($layout_title) ?> — <?= htmlspecialchars($ta['admin_suffix']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(bk_asset('css/admin.css')) ?>?v=17">
    <link rel="stylesheet" href="<?= htmlspecialchars(bk_admin_settings_css_href()) ?>">
</head>
<body class="adm-body adm-body-pc">
<div class="adm-sidebar-overlay" id="admOverlay" hidden></div>
<div class="adm-layout">
    <aside class="adm-sidebar" id="admSidebar" aria-label="<?= htmlspecialchars($ta['a11y_menu'] ?? 'Admin menu') ?>">
        <div class="adm-sidebar-head">
        <a href="<?= bk_admin_url('index.php') ?>" class="adm-sidebar-brand">
            <div class="icon">B</div>
            <div>
                <span><?= htmlspecialchars($ta['brand_name']) ?></span>
                <small><?= htmlspecialchars($ta['title']) ?></small>
            </div>
        </a>
        <button type="button" class="adm-sidebar-close" id="admSidebarClose" aria-label="<?= htmlspecialchars($ta['a11y_close'] ?? 'Close menu') ?>">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        </div>
        <nav class="adm-nav">
            <p class="adm-nav-label"><?= htmlspecialchars($ta['nav_group_main'] ?? 'Main') ?></p>
            <?php foreach ($adm_main_nav as $item): ?>
            <a href="<?= bk_admin_url($item['url']) ?>" class="adm-nav-link <?= ($admin_page ?? '') === $item['slug'] ? 'active' : '' ?>">
                <i class="fas <?= htmlspecialchars($item['icon']) ?>" aria-hidden="true"></i>
                <span class="adm-nav-link-text">
                    <span class="adm-nav-link-label"><?= htmlspecialchars($item['label']) ?></span>
                    <?php if (!empty($item['desc'])): ?>
                    <span class="adm-nav-link-desc"><?= htmlspecialchars($item['desc']) ?></span>
                    <?php endif; ?>
                </span>
            </a>
            <?php endforeach; ?>
            <p class="adm-nav-label"><?= htmlspecialchars($ta['nav_group_setup'] ?? 'Setup') ?></p>
            <?php require __DIR__ . '/settings-nav.php'; ?>
            <a href="<?= bk_url('index.php') ?>" class="adm-nav-link" target="_blank" rel="noopener">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                <span class="adm-nav-link-text">
                    <span class="adm-nav-link-label"><?= htmlspecialchars($ta['view_site']) ?></span>
                    <?php if (!empty($ta['nav_desc_view_site'])): ?>
                    <span class="adm-nav-link-desc"><?= htmlspecialchars($ta['nav_desc_view_site']) ?></span>
                    <?php endif; ?>
                </span>
            </a>
        </nav>
        <div class="adm-sidebar-foot">
            <div class="adm-sidebar-foot-version">
                <i class="fas fa-code-branch" aria-hidden="true"></i>
                <span><?= htmlspecialchars($ta['brand_name']) ?></span>
                <strong><?= htmlspecialchars(bk_version_label()) ?></strong>
            </div>
            <div class="adm-sidebar-foot-actions">
                <a href="<?= bk_url('site/') ?>" class="adm-sidebar-foot-btn" target="_blank" rel="noopener">
                    <i class="fas fa-box-open" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($ta['product_page']) ?></span>
                </a>
                <a href="<?= bk_admin_url('logout.php') ?>" class="adm-sidebar-foot-btn adm-sidebar-foot-btn--logout">
                    <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($ta['logout']) ?></span>
                </a>
            </div>
        </div>
    </aside>

    <div class="adm-main">
        <header class="adm-topbar">
            <div class="adm-topbar-row">
                <button type="button" class="adm-menu-toggle" id="admMenuBtn" aria-expanded="false" aria-controls="admSidebar" aria-label="<?= htmlspecialchars($ta['a11y_menu']) ?>">
                    <i class="fas fa-bars adm-menu-icon-open" aria-hidden="true"></i>
                    <i class="fas fa-times adm-menu-icon-close" aria-hidden="true"></i>
                </button>
                <div class="adm-topbar-head">
                    <h1 class="adm-topbar-title"><?= htmlspecialchars($layout_title) ?></h1>
                    <?php if ($layout_desc !== ''): ?>
                    <p class="adm-topbar-desc"><?= htmlspecialchars($layout_desc) ?></p>
                    <?php endif; ?>
                </div>
                <div class="adm-topbar-tools">
                    <span class="adm-topbar-welcome"><?= htmlspecialchars($ta['welcome']) ?>, <strong><?= htmlspecialchars($admin_user) ?></strong></span>
                    <span class="adm-topbar-version" title="<?= htmlspecialchars($ta['brand_name']) ?> <?= htmlspecialchars(bk_version_label()) ?>"><?= htmlspecialchars(bk_version_label()) ?></span>
                    <?php require __DIR__ . '/lang-dropdown.php'; ?>
                    <a href="<?= bk_url('index.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm adm-topbar-site" target="_blank" rel="noopener">
                        <i class="fas fa-globe" aria-hidden="true"></i>
                        <span><?= htmlspecialchars($ta['view_site']) ?></span>
                    </a>
                    <a href="<?= bk_admin_url('logout.php') ?>" class="adm-topbar-icon-btn adm-topbar-icon-btn--logout" title="<?= htmlspecialchars($ta['logout']) ?>">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        <span class="sr-only"><?= htmlspecialchars($ta['logout']) ?></span>
                    </a>
                </div>
            </div>
        </header>
        <main class="adm-content">