<?php
require_once __DIR__ . '/init.php';
$current_page = 'search';
$search_params = bk_search_params();
$results = bk_filter_properties($search_params, $lang);
$page_title = $t['search_page']['title'] . ($search_params['destination'] ? ' — ' . $search_params['destination'] : '');
$page_desc  = sprintf($t['search_page']['found'], count($results)) . '. ' . $t['meta']['description'];
$canonical = $site_url . '/search.php?' . http_build_query($search_params);
$canon_abs = bk_absolute_url($canonical);
$seo_noindex = true;
$seo_schemas = [
    bk_seo_webpage($canon_abs, $page_title, $page_desc),
    bk_seo_item_list($results, $lang, $canon_abs),
    bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => $t['search_page']['title'], 'url' => $canon_abs],
    ]),
];
require __DIR__ . '/includes/header.php';
?>

<section class="bk-hero" style="padding:24px 16px 32px">
    <div class="bk-hero-inner">
        <?php require __DIR__ . '/includes/search-form.php'; ?>
    </div>
</section>

<div class="bk-container">
    <div class="bk-search-layout">
        <aside class="bk-filters">
            <h3><?= htmlspecialchars($t['search_page']['filter']) ?></h3>
            <form method="get" action="<?= bk_url('search.php') ?>">
                <input type="hidden" name="destination" value="<?= htmlspecialchars($search_params['destination']) ?>">
                <input type="hidden" name="checkin" value="<?= htmlspecialchars($search_params['checkin']) ?>">
                <input type="hidden" name="checkout" value="<?= htmlspecialchars($search_params['checkout']) ?>">
                <input type="hidden" name="adults" value="<?= (int)$search_params['adults'] ?>">
                <input type="hidden" name="children" value="<?= (int)$search_params['children'] ?>">
                <input type="hidden" name="rooms" value="<?= (int)$search_params['rooms'] ?>">
                <div class="bk-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['type']) ?></label>
                    <select name="type" onchange="this.form.submit()">
                        <option value=""><?= htmlspecialchars($t['search_page']['all']) ?></option>
                        <?php foreach (['hotel', 'apartment', 'cabin'] as $tp): ?>
                        <option value="<?= $tp ?>" <?= $search_params['type'] === $tp ? 'selected' : '' ?>><?= htmlspecialchars($t['types'][$tp]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="bk-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['sort']) ?></label>
                    <select name="sort" onchange="this.form.submit()">
                        <option value="recommended" <?= $search_params['sort'] === 'recommended' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_rec']) ?></option>
                        <option value="price_low" <?= $search_params['sort'] === 'price_low' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_price_l']) ?></option>
                        <option value="price_high" <?= $search_params['sort'] === 'price_high' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_price_h']) ?></option>
                        <option value="rating" <?= $search_params['sort'] === 'rating' ? 'selected' : '' ?>><?= htmlspecialchars($t['search_page']['sort_rating']) ?></option>
                    </select>
                </div>
                <div class="bk-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['price']) ?> (min)</label>
                    <input type="number" name="min_price" value="<?= (int)$search_params['min_price'] ?>" min="0" step="100" placeholder="0">
                </div>
                <div class="bk-filter-group">
                    <label><?= htmlspecialchars($t['search_page']['price']) ?> (max)</label>
                    <input type="number" name="max_price" value="<?= (int)$search_params['max_price'] ?>" min="0" step="100" placeholder="5000">
                </div>
                <button type="submit" class="bk-btn-blue" style="width:100%"><?= htmlspecialchars($t['search']['search_btn']) ?></button>
            </form>
        </aside>

        <div>
            <div class="bk-results-header">
                <h1><?= sprintf(htmlspecialchars($t['search_page']['found']), count($results)) ?></h1>
                <?php if ($search_params['destination']): ?>
                <span style="color:var(--bk-gray-500)"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($search_params['destination']) ?></span>
                <?php endif; ?>
            </div>

            <?php if (empty($results)): ?>
            <div class="bk-form-card" style="text-align:center;padding:48px">
                <i class="fas fa-search" style="font-size:3rem;color:var(--bk-gray-200);margin-bottom:16px"></i>
                <p><?= htmlspecialchars($t['search_page']['no_results']) ?></p>
                <a href="<?= bk_url('index.php') ?>" class="bk-btn-blue" style="display:inline-block;margin-top:12px;text-decoration:none"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
            </div>
            <?php else: ?>
            <div class="bk-prop-grid">
                <?php foreach ($results as $property):
                    require __DIR__ . '/includes/property-card.php';
                endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>