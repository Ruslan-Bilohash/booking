<?php
/** @var array $property */
/** @var array $t */
/** @var string $lang */
/** @var array $search_params */
$sp = $search_params ?? bk_search_params();
$nights = bk_nights($sp['checkin'], $sp['checkout']);
$total  = bk_total_price($property, $nights, $sp['rooms']);
$qs = http_build_query(array_merge($sp, ['id' => $property['id']]));
$detail_url = bk_url('property.php?' . $qs);
$name = bk_localized($property, 'name', $lang);
$city = bk_localized($property, 'city', $lang);
$country = bk_localized($property, 'country', $lang);
$desc = bk_localized($property, 'desc', $lang);
$rating_label = bk_rating_label($property['rating'], $t);
$stars = str_repeat('★', (int)$property['stars']);
?>
<article class="bk-prop-card">
    <div class="bk-prop-img">
        <a href="<?= htmlspecialchars($detail_url) ?>">
            <img src="<?= htmlspecialchars(bk_property_image($property)) ?>" alt="<?= htmlspecialchars($name) ?>" loading="lazy" width="240" height="180" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
        </a>
        <?php if (!empty($property['deal'])): ?>
        <span class="bk-deal-badge">−<?= (int)$property['deal'] ?>%</span>
        <?php endif; ?>
    </div>
    <div class="bk-prop-body">
        <h3><a href="<?= htmlspecialchars($detail_url) ?>"><?= htmlspecialchars($name) ?></a></h3>
        <?php if ($stars): ?><div class="bk-stars" aria-label="<?= (int)$property['stars'] ?> stars"><?= $stars ?></div><?php endif; ?>
        <div class="bk-location"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($country) ?></div>
        <p class="bk-desc-snippet"><?= htmlspecialchars($desc) ?></p>
        <div class="bk-tags">
            <span class="bk-tag"><i class="fas fa-check"></i> <?= htmlspecialchars($t['card']['free_cancel']) ?></span>
            <?php if (in_array('breakfast', $property['amenities'] ?? [], true)): ?>
            <span class="bk-tag"><i class="fas fa-check"></i> <?= htmlspecialchars($t['card']['breakfast']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="bk-prop-side">
        <div class="bk-score-box">
            <div>
                <div class="bk-score-label"><?= htmlspecialchars($rating_label) ?></div>
                <div class="bk-score-reviews"><?= number_format($property['reviews']) ?> <?= htmlspecialchars($t['card']['reviews']) ?></div>
            </div>
            <div class="bk-score-num"><?= number_format($property['rating'], 1) ?></div>
        </div>
        <div class="bk-price-block">
            <div class="bk-price-from"><?= htmlspecialchars($t['card']['from']) ?></div>
            <div class="bk-price"><?= bk_price($property['price']) ?></div>
            <div class="bk-price-note"><?= htmlspecialchars($t['card']['per_night']) ?></div>
            <div class="bk-price-note" style="margin-top:4px;font-weight:600;color:var(--bk-gray-700)"><?= bk_price($total) ?> · <?= sprintf($t['property']['nights'], $nights) ?></div>
            <a href="<?= htmlspecialchars($detail_url) ?>" class="bk-btn-blue" style="margin-top:10px;display:inline-block;text-decoration:none"><?= htmlspecialchars($t['card']['see_avail']) ?></a>
        </div>
    </div>
</article>