<?php
/**
 * Compact ecosystem list for footers: 3 visible + show more.
 * Expects $t with ecosystem translations; optional $ft = $t['footer'].
 */
$ft = $ft ?? ($t['footer'] ?? []);
$eco_items = $t['ecosystem']['items'] ?? [];
if ($eco_items === []) {
    return;
}
$eco_btn  = $t['ecosystem']['product_btn'] ?? 'Product page';
$eco_demo = $t['ecosystem']['demo_btn'] ?? 'Live demo';
$eco_prefix = $eco_class_prefix ?? 'bk-footer-eco';
$eco_visible = array_slice($eco_items, 0, 3);
$eco_hidden  = array_slice($eco_items, 3);
$eco_more_n  = count($eco_hidden);
$eco_render = static function (array $eco) use ($eco_demo, $eco_btn, $eco_prefix): void { ?>
    <li class="<?= htmlspecialchars($eco_prefix) ?>-row">
        <span class="<?= htmlspecialchars($eco_prefix) ?>-name"><?= htmlspecialchars($eco['name']) ?></span>
        <span class="<?= htmlspecialchars($eco_prefix) ?>-actions">
            <a href="<?= htmlspecialchars($eco['demo']) ?>" rel="related"><?= htmlspecialchars($eco_demo) ?></a>
            <span class="<?= htmlspecialchars($eco_prefix) ?>-sep" aria-hidden="true">·</span>
            <a href="<?= htmlspecialchars($eco['url']) ?>" rel="related"><?= htmlspecialchars($eco_btn) ?></a>
        </span>
    </li>
<?php };
?>
<section class="<?= htmlspecialchars($eco_prefix) ?>-block" id="bk-ecosystem" aria-label="<?= htmlspecialchars($ft['ecosystem'] ?? 'Bilohash ecosystem') ?>">
    <div class="<?= htmlspecialchars($eco_prefix) ?>-head">
        <h4 class="<?= htmlspecialchars($eco_prefix) ?>-title">
            <i class="fas fa-layer-group" aria-hidden="true"></i>
            <?= htmlspecialchars($ft['eco_toggle'] ?? $ft['ecosystem'] ?? 'Bilohash ecosystem') ?>
        </h4>
        <span class="<?= htmlspecialchars($eco_prefix) ?>-badge"><?= (int) count($eco_items) ?> CMS</span>
    </div>
    <ul class="<?= htmlspecialchars($eco_prefix) ?>-grid">
        <?php foreach ($eco_visible as $eco) { $eco_render($eco); } ?>
    </ul>
    <?php if ($eco_more_n > 0): ?>
    <ul class="<?= htmlspecialchars($eco_prefix) ?>-grid <?= htmlspecialchars($eco_prefix) ?>-more" id="bkFooterEcoMore" hidden aria-hidden="true">
        <?php foreach ($eco_hidden as $eco) { $eco_render($eco); } ?>
    </ul>
    <button
        type="button"
        class="<?= htmlspecialchars($eco_prefix) ?>-more-btn"
        id="bkFooterEcoMoreBtn"
        aria-expanded="false"
        aria-controls="bkFooterEcoMore"
        data-label-more="<?= htmlspecialchars(sprintf($ft['eco_show_more'] ?? 'Show more (%d)', $eco_more_n)) ?>"
        data-label-less="<?= htmlspecialchars($ft['eco_show_less'] ?? 'Show less') ?>"
    ><?= htmlspecialchars(sprintf($ft['eco_show_more'] ?? 'Show more (%d)', $eco_more_n)) ?></button>
    <?php endif; ?>
</section>