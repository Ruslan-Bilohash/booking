<?php
require_once __DIR__ . '/init.php';
$slug = trim($_GET['slug'] ?? '');
$vertical = bk_vertical_by_slug($slug);
if (!$vertical) {
    header('Location: ' . bk_url('solutions.php'), true, 302);
    exit;
}

$GLOBALS['vertical_slug'] = $slug;
$v = bk_vertical_lang($vertical, $lang);
$page_title = $v['title'] ?? BK_SITE_NAME;
$page_desc  = $v['description'] ?? '';
$canonical  = bk_vertical_canonical($slug);
$canon_abs  = bk_absolute_url($canonical);

$seo_schemas = [
    bk_seo_organization(),
    bk_seo_webpage($canon_abs, $page_title, $page_desc),
    bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => bk_vertical_hub_label($lang), 'url' => bk_absolute_url(bk_url('solutions.php'))],
        ['name' => $v['h1'] ?? $slug, 'url' => $canon_abs],
    ]),
    bk_seo_vertical_service($v['h1'] ?? $slug, $page_desc, $canon_abs),
    bk_seo_software_app($canon_abs, $page_desc),
];
if (!empty($v['faq'])) {
    $seo_schemas[] = bk_seo_faq_page($v['faq']);
}

require __DIR__ . '/includes/vertical-template.php';