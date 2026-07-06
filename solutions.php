<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
if (!function_exists('bh_str_sub')) {
    function bh_str_sub(string $str, int $start, ?int $length = null): string
    {
        if (function_exists('mb_substr')) {
            return $length === null ? mb_substr($str, $start) : mb_substr($str, $start, $length);
        }
        return $length === null ? substr($str, $start) : substr($str, $start, $length);
    }
}

$current_page = 'solutions';
$all = bk_verticals_all();
$s = $t['solutions'] ?? [];

$page_title = $s['page_title'] ?? 'Booking Solutions | Booking CMS';
$page_desc  = $s['page_desc'] ?? '';
$canonical = $site_url . '/solutions.php' . ($lang !== 'no' ? '?lang=' . $lang : '');
$canon_abs = bk_absolute_url($canonical);
$seo_schemas = [
    bk_seo_organization(),
    bk_seo_webpage($canon_abs, $page_title, $page_desc),
    bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => $s['breadcrumb'] ?? 'Solutions', 'url' => $canon_abs],
    ]),
];
require __DIR__ . '/includes/header.php';
?>

<div class="bk-container bk-solutions-hub">
    <h1 class="bk-page-title"><?= htmlspecialchars($s['hub_h1'] ?? '') ?></h1>
    <p class="bk-results-meta"><?= htmlspecialchars($s['hub_sub'] ?? '') ?></p>
    <div class="bk-vertical-links bk-solutions-grid">
        <?php
        $vdefs = bk_vertical_defs();
        foreach ($all as $slug => $item):
            $lv = bk_vertical_lang($item, $lang);
            $short = $vdefs[$slug][$lang] ?? $vdefs[$slug]['en'] ?? ($lv['h1'] ?? $slug);
        ?>
        <a href="<?= htmlspecialchars(bk_vertical_url($slug)) ?>" class="bk-vertical-link-card">
            <i class="fas fa-<?= htmlspecialchars($item['icon'] ?? 'calendar-check') ?>"></i>
            <strong><?= htmlspecialchars($short) ?></strong>
            <span><?= htmlspecialchars(bh_str_sub($lv['subtitle'] ?? '', 0, 90)) ?>…</span>
        </a>
        <?php endforeach; ?>
    </div>
    <section class="bk-vertical-cta-band" style="margin-top:36px">
        <h2><?= htmlspecialchars($s['order_cta'] ?? ($t['footer']['order_dev'] ?? '')) ?></h2>
        <div class="bk-vertical-cta-row">
            <a href="<?= bk_url('order.php') ?>" class="bk-btn-blue"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($s['order_cta'] ?? '') ?></a>
            <a href="<?= bk_url('contact.php') ?>" class="bk-btn-outline-dark"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
        </div>
    </section>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>