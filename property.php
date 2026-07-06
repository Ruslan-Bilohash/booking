<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/site-integrations.php';
require_once __DIR__ . '/includes/payment-settings.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
bk_boot_public_integrations();

$id = $_GET['id'] ?? '';
$property = bk_property_by_id($id);
if (!$property) {
    header('Location: ' . bk_url('search.php'), true, 302);
    exit;
}

$review_feedback = '';
$review_feedback_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'review') {
    $guestName = trim(strip_tags($_POST['guest_name'] ?? ''));
    $rating = (float) ($_POST['rating'] ?? 0);
    $text = trim(strip_tags($_POST['review_text'] ?? ''));
    $title = trim(strip_tags($_POST['review_title'] ?? ''));
    $tripType = in_array($_POST['trip_type'] ?? '', ['couple', 'family', 'solo', 'business'], true) ? $_POST['trip_type'] : 'couple';

    if ($guestName === '' || $text === '' || $rating < 1 || $rating > 10) {
        $review_feedback = $t['reviews']['error_fields'] ?? 'Please fill required fields.';
        $review_feedback_type = 'error';
    } elseif (mb_strlen($text) < 20) {
        $review_feedback = $t['reviews']['error_short'] ?? 'Review is too short.';
        $review_feedback_type = 'error';
    } elseif (!bk_verify_recaptcha($_POST['g-recaptcha-response'] ?? '', bk_site_settings())) {
        $review_feedback = $t['reviews']['error_captcha'] ?? 'Please complete reCAPTCHA.';
        $review_feedback_type = 'error';
    } else {
        $entry = [
            'property_id' => $property['id'],
            'guest_name'  => $guestName,
            'rating'      => round($rating, 1),
            'trip_type'   => $tripType,
            'stay_month'  => date('Y-m'),
            'approved'    => false,
            'source'      => 'guest',
            'title'       => ['en' => $title, 'no' => $title, 'uk' => $title],
            'text'        => ['en' => $text, 'no' => $text, 'uk' => $text],
            'country'     => ['en' => '', 'no' => '', 'uk' => ''],
        ];
        if (bk_add_review($entry)) {
            $review_feedback = $t['reviews']['success_pending'] ?? 'Thank you! Your review will appear after moderation.';
            $review_feedback_type = 'success';
        } else {
            $review_feedback = $t['reviews']['error_save'] ?? 'Could not save review.';
            $review_feedback_type = 'error';
        }
    }
}

$search_params = bk_search_params();
$nights = bk_nights($search_params['checkin'], $search_params['checkout']);
$bk_settings = bk_site_settings();
$subtotal = bk_total_price($property, $nights, $search_params['rooms']);
$price = bk_booking_price_breakdown($subtotal, $bk_settings);
$total = $price['total'];
$name = bk_localized($property, 'name', $lang);
$city = bk_localized($property, 'city', $lang);
$country = bk_localized($property, 'country', $lang);
$desc = bk_localized($property, 'desc', $lang);
$property_reviews = bk_reviews_for_property($property['id']);
$review_preview = array_slice($property_reviews, 0, 3);
$desc_long = bk_localized($property, 'desc_long', $lang);
$highlights = bk_localized_list($property, 'highlights', $lang);
$type_key = $property['type'] ?? 'hotel';
$type_label = $t['types'][$type_key] ?? ucfirst($type_key);
$bk_recap_key = bk_recaptcha_site_key(bk_site_settings());
$bk_active_tab = $review_feedback !== '' ? 'reviews' : 'overview';
$page_title = $name . ' — ' . $t['meta']['site_name'];
$page_desc  = mb_substr(strip_tags($desc), 0, 160);
$book_url = bk_url('book.php?' . http_build_query(array_merge($search_params, ['id' => $property['id']])));
$canonical = $site_url . '/property.php?id=' . urlencode($property['id']);
$canon_abs = bk_absolute_url($canonical);
$seo_og_image = bk_property_image($property);
$seo_og_type = 'website';
$seo_schemas = [bk_seo_webpage($canon_abs, $page_title, $page_desc)];
if (bk_seo_schema_enabled('breadcrumbs')) {
    $seo_schemas[] = bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => $city, 'url' => bk_absolute_url(bk_url('search.php?destination=' . urlencode($city)))],
        ['name' => $name, 'url' => $canon_abs],
    ]);
}
if (bk_seo_schema_enabled('lodging')) {
    $seo_schemas[] = bk_seo_lodging($property, $lang, $canon_abs);
}
if (bk_seo_schema_enabled('product')) {
    $seo_schemas[] = bk_seo_product($property, $lang, $canon_abs);
}
require __DIR__ . '/includes/header.php';
?>

<div class="bk-container">
    <nav class="bk-breadcrumb" aria-label="<?= htmlspecialchars($t['a11y']['breadcrumb'] ?? 'Breadcrumb') ?>">
        <a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= bk_url('search.php?destination=' . urlencode($city)) ?>"><?= htmlspecialchars($city) ?></a>
        → <?= htmlspecialchars($name) ?>
    </nav>

    <div class="bk-detail-layout">
        <div class="bk-detail-head">
            <div class="bk-gallery">
                <img src="<?= htmlspecialchars(bk_property_image($property)) ?>" alt="<?= htmlspecialchars($name) ?>" width="800" height="533" fetchpriority="high" decoding="async" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
            </div>
            <h1 class="bk-property-title"><?= htmlspecialchars($name) ?></h1>
            <div class="bk-location bk-property-location">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i> <?= htmlspecialchars($city) ?>, <?= htmlspecialchars($country) ?>
                <?php if ($property['stars']): ?>
                <span class="bk-stars"><?= str_repeat('★', (int)$property['stars']) ?></span>
                <?php endif; ?>
            </div>
            <div class="bk-score-box bk-property-score">
                <div class="bk-score-num"><?= number_format($property['rating'], 1) ?></div>
                <div>
                    <div class="bk-score-label"><?= htmlspecialchars(bk_rating_label($property['rating'], $t)) ?></div>
                    <div class="bk-score-reviews"><?= number_format($property['reviews']) ?> <?= htmlspecialchars($t['card']['reviews']) ?></div>
                </div>
            </div>
        </div>

        <aside class="bk-booking-box">
            <h3 class="bk-booking-box-title"><?= htmlspecialchars($t['property']['your_stay']) ?></h3>
            <div class="bk-booking-box-dates">
                <?= htmlspecialchars($search_params['checkin']) ?> → <?= htmlspecialchars($search_params['checkout']) ?><br>
                <?= sprintf($t['property']['nights'], $nights) ?> · <?= (int)$search_params['adults'] ?> <?= htmlspecialchars($t['search']['adults']) ?> · <?= (int)$search_params['rooms'] ?> <?= htmlspecialchars($t['search']['rooms']) ?>
            </div>
            <div class="bk-price-from"><?= htmlspecialchars($t['card']['from']) ?></div>
            <div class="bk-price"><?= bk_price($total) ?></div>
            <div class="bk-price-note"><?= htmlspecialchars(bk_tax_display_note($lang, bk_site_settings())) ?></div>
            <?php if (!empty($property['deal'])): ?>
            <div class="bk-deal-line">
                <i class="fas fa-badge-percent" aria-hidden="true"></i> −<?= (int)$property['deal'] ?>% <?= htmlspecialchars($t['card']['deal']) ?>
            </div>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($book_url) ?>" class="bk-btn-yellow bk-booking-cta">
                <?= htmlspecialchars($t['property']['book_now']) ?>
            </a>
            <div class="bk-demo-alert"><?= htmlspecialchars($t['property']['demo_note']) ?></div>
        </aside>

        <div class="bk-detail-body">
            <div class="bk-tabs" role="tablist" id="bkPropertyTabs" aria-label="<?= htmlspecialchars($t['property']['tabs_label']) ?>">
                <button type="button" class="bk-tab <?= $bk_active_tab === 'overview' ? 'active' : '' ?>" role="tab" id="bk-tab-btn-overview" aria-controls="bk-tab-overview" aria-selected="<?= $bk_active_tab === 'overview' ? 'true' : 'false' ?>" tabindex="<?= $bk_active_tab === 'overview' ? '0' : '-1' ?>" data-tab="overview"><?= htmlspecialchars($t['property']['overview']) ?></button>
                <button type="button" class="bk-tab <?= $bk_active_tab === 'amenities' ? 'active' : '' ?>" role="tab" id="bk-tab-btn-amenities" aria-controls="bk-tab-amenities" aria-selected="<?= $bk_active_tab === 'amenities' ? 'true' : 'false' ?>" tabindex="<?= $bk_active_tab === 'amenities' ? '0' : '-1' ?>" data-tab="amenities"><?= htmlspecialchars($t['property']['amenities']) ?></button>
                <button type="button" class="bk-tab <?= $bk_active_tab === 'reviews' ? 'active' : '' ?>" role="tab" id="bk-tab-btn-reviews" aria-controls="bk-tab-reviews" aria-selected="<?= $bk_active_tab === 'reviews' ? 'true' : 'false' ?>" tabindex="<?= $bk_active_tab === 'reviews' ? '0' : '-1' ?>" data-tab="reviews"><?= htmlspecialchars($t['property']['reviews_tab']) ?></button>
            </div>

            <div class="bk-tab-panel <?= $bk_active_tab === 'overview' ? 'active' : '' ?>" id="bk-tab-overview" role="tabpanel"<?= $bk_active_tab !== 'overview' ? ' hidden' : '' ?>>
                <p class="bk-property-lead"><?= htmlspecialchars($desc) ?></p>
                <?php if ($desc_long !== '' && $desc_long !== $desc): ?>
                <p class="bk-property-desc-long"><?= htmlspecialchars($desc_long) ?></p>
                <?php endif; ?>
                <p class="bk-property-meta-line">
                    <span><i class="fas fa-building" aria-hidden="true"></i> <?= htmlspecialchars($type_label) ?></span>
                    <?php if (!empty($property['stars'])): ?>
                    <span><i class="fas fa-star" aria-hidden="true"></i> <?= (int) $property['stars'] ?> <?= htmlspecialchars($t['property']['stars_label'] ?? 'stars') ?></span>
                    <?php endif; ?>
                </p>
                <?php if ($highlights !== []): ?>
                <h3 class="bk-section-title"><?= htmlspecialchars($t['property']['highlights'] ?? 'Highlights') ?></h3>
                <ul class="bk-highlights-list">
                    <?php foreach ($highlights as $hl): ?>
                    <li><i class="fas fa-check" aria-hidden="true"></i> <?= htmlspecialchars($hl) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <?php if (!empty($property['amenities'])): ?>
                <h3 class="bk-section-title"><?= htmlspecialchars($t['property']['popular_facilities'] ?? 'Popular facilities') ?></h3>
                <div class="bk-amenities-inline">
                    <?php foreach (array_slice($property['amenities'], 0, 6) as $am): ?>
                    <span class="bk-amenity-chip"><i class="fas <?= htmlspecialchars(bk_amenity_icon($am)) ?>" aria-hidden="true"></i> <?= htmlspecialchars($t['amenities'][$am] ?? $am) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php if ($property_reviews !== []): ?>
                <h3 class="bk-section-title"><?= htmlspecialchars($t['property']['guest_reviews_preview'] ?? 'What guests say') ?></h3>
                <?php if (count($property_reviews) < (int) ($property['reviews'] ?? 0)): ?>
                <p class="bk-tab-lead"><?= sprintf($t['property']['reviews_sample'] ?? 'Showing %d sample guest reviews (demo total: %d).', count($property_reviews), (int) $property['reviews']) ?></p>
                <?php endif; ?>
                <div class="bk-reviews-list bk-reviews-preview">
                    <?php foreach ($review_preview as $rev): ?>
                    <article class="bk-review-card">
                        <header class="bk-review-head">
                            <div class="bk-review-score"><?= number_format((float)($rev['rating'] ?? 0), 1) ?></div>
                            <div class="bk-review-head-text">
                                <strong class="bk-review-name"><?= htmlspecialchars($rev['guest_name'] ?? ($t['ui']['guest_fallback'] ?? 'Guest')) ?></strong>
                                <?php if (!empty($rev['country'][$lang]) || !empty($rev['country']['en'])): ?>
                                <span class="bk-review-country"><?= htmlspecialchars($rev['country'][$lang] ?? $rev['country']['en'] ?? '') ?></span>
                                <?php endif; ?>
                            </div>
                        </header>
                        <?php $revTitle = bk_localized($rev, 'title', $lang); if ($revTitle !== ''): ?>
                        <h4 class="bk-review-title"><?= htmlspecialchars($revTitle) ?></h4>
                        <?php endif; ?>
                        <p class="bk-review-text"><?= htmlspecialchars(bk_localized($rev, 'text', $lang)) ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php if (count($property_reviews) > count($review_preview)): ?>
                <button type="button" class="bk-btn-outline-dark bk-see-all-reviews" data-goto-tab="reviews"><?= sprintf($t['property']['see_all_reviews'] ?? 'See all %d reviews', count($property_reviews)) ?></button>
                <?php endif; ?>
                <?php endif; ?>
                <h3 class="bk-section-title"><?= htmlspecialchars($t['property']['location']) ?></h3>
                <?php
                $bk_coords = bk_property_coords($property);
                $bk_location_label = trim($city . ', ' . $country);
                ?>
                <div class="bk-location-block">
                    <p class="bk-location-detail">
                        <?= htmlspecialchars($bk_location_label) ?>
                        <?php if ($bk_coords): ?>
                        · <?= sprintf(htmlspecialchars($t['property']['gps_demo'] ?? 'GPS %s, %s (demo)'), $bk_coords['lat'], $bk_coords['lng']) ?>
                        <?php endif; ?>
                    </p>
                    <?php if ($bk_coords): ?>
                    <div class="bk-property-map">
                        <iframe
                            title="<?= sprintf(htmlspecialchars($t['property']['map_alt'] ?? 'Map: %s'), $bk_location_label) ?>"
                            src="<?= htmlspecialchars(bk_map_embed_url($bk_coords['lat'], $bk_coords['lng'])) ?>"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <div class="bk-location-actions">
                        <a href="<?= htmlspecialchars(bk_map_directions_url($bk_coords['lat'], $bk_coords['lng'], $bk_location_label)) ?>" class="bk-btn-blue" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-route" aria-hidden="true"></i> <?= htmlspecialchars($t['property']['start_trip'] ?? 'Start trip') ?>
                        </a>
                        <a href="<?= htmlspecialchars(bk_map_open_url($bk_coords['lat'], $bk_coords['lng'])) ?>" class="bk-btn-outline-dark" target="_blank" rel="noopener noreferrer">
                            <i class="fas fa-map" aria-hidden="true"></i> <?= htmlspecialchars($t['property']['open_map'] ?? 'Open full map') ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bk-tab-panel <?= $bk_active_tab === 'amenities' ? 'active' : '' ?>" id="bk-tab-amenities" role="tabpanel"<?= $bk_active_tab !== 'amenities' ? ' hidden' : '' ?>>
                <p class="bk-tab-lead"><?= htmlspecialchars($t['property']['amenities_lead'] ?? '') ?></p>
                <div class="bk-amenities-grid">
                    <?php foreach ($property['amenities'] as $am): ?>
                    <div class="bk-amenity">
                        <i class="fas <?= htmlspecialchars(bk_amenity_icon($am)) ?>"></i>
                        <?= htmlspecialchars($t['amenities'][$am] ?? $am) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($property['amenities'])): ?>
                <p class="bk-muted"><?= htmlspecialchars($t['property']['no_amenities'] ?? 'No facilities listed.') ?></p>
                <?php endif; ?>
            </div>

            <div class="bk-tab-panel <?= $bk_active_tab === 'reviews' ? 'active' : '' ?>" id="bk-tab-reviews" role="tabpanel"<?= $bk_active_tab !== 'reviews' ? ' hidden' : '' ?>>
                <?php if ($property_reviews !== [] && count($property_reviews) < (int) ($property['reviews'] ?? 0)): ?>
                <p class="bk-tab-lead"><?= sprintf($t['property']['reviews_sample'] ?? 'Showing %d sample guest reviews (demo total: %d).', count($property_reviews), (int) $property['reviews']) ?></p>
                <?php endif; ?>
                <?php if ($property_reviews !== []): ?>
                <div class="bk-reviews-list">
                    <?php foreach ($property_reviews as $rev): ?>
                    <article class="bk-review-card">
                        <header class="bk-review-head">
                            <div class="bk-review-score"><?= number_format((float)($rev['rating'] ?? 0), 1) ?></div>
                            <div class="bk-review-head-text">
                                <strong class="bk-review-name"><?= htmlspecialchars($rev['guest_name'] ?? ($t['ui']['guest_fallback'] ?? 'Guest')) ?></strong>
                                <div class="bk-review-meta">
                                    <?php if (!empty($rev['country'][$lang]) || !empty($rev['country']['en'])): ?>
                                    <span class="bk-review-country"><?= htmlspecialchars($rev['country'][$lang] ?? $rev['country']['en'] ?? '') ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($rev['trip_type'])): ?>
                                    <span class="bk-review-trip"><?= htmlspecialchars(bk_review_trip_label($rev['trip_type'], $t)) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($rev['stay_month'])): ?>
                                    <time class="bk-review-date" datetime="<?= htmlspecialchars($rev['stay_month']) ?>"><?= htmlspecialchars($rev['stay_month']) ?></time>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </header>
                        <?php $revTitle = bk_localized($rev, 'title', $lang); if ($revTitle !== ''): ?>
                        <h4 class="bk-review-title"><?= htmlspecialchars($revTitle) ?></h4>
                        <?php endif; ?>
                        <p class="bk-review-text"><?= htmlspecialchars(bk_localized($rev, 'text', $lang)) ?></p>
                    </article>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p class="bk-muted"><?= htmlspecialchars($t['reviews']['empty'] ?? 'No guest reviews yet.') ?></p>
                <?php endif; ?>

                <div class="bk-review-form-wrap">
                    <h3><?= htmlspecialchars($t['reviews']['write_title'] ?? 'Write a review') ?></h3>
                    <p class="bk-tab-lead"><?= htmlspecialchars($t['reviews']['write_lead'] ?? '') ?></p>
                    <?php if ($review_feedback): ?>
                    <div class="bk-review-alert bk-review-alert--<?= htmlspecialchars($review_feedback_type) ?>"><?= htmlspecialchars($review_feedback) ?></div>
                    <?php endif; ?>
                    <form class="bk-review-form" method="post" action="<?= htmlspecialchars(bk_url('property.php?id=' . urlencode($property['id']))) ?>">
                        <input type="hidden" name="action" value="review">
                        <div class="bk-review-form-grid">
                            <label><?= htmlspecialchars($t['reviews']['your_name'] ?? 'Your name') ?>
                                <input type="text" name="guest_name" required maxlength="80" autocomplete="name">
                            </label>
                            <label><?= htmlspecialchars($t['reviews']['your_rating'] ?? 'Your score (1–10)') ?>
                                <input type="number" name="rating" min="1" max="10" step="0.1" required value="9">
                            </label>
                            <label><?= htmlspecialchars($t['reviews']['trip_type'] ?? 'Trip type') ?>
                                <select name="trip_type">
                                    <option value="couple"><?= htmlspecialchars($t['reviews']['trip_couple'] ?? 'Couple') ?></option>
                                    <option value="family"><?= htmlspecialchars($t['reviews']['trip_family'] ?? 'Family') ?></option>
                                    <option value="solo"><?= htmlspecialchars($t['reviews']['trip_solo'] ?? 'Solo') ?></option>
                                    <option value="business"><?= htmlspecialchars($t['reviews']['trip_business'] ?? 'Business') ?></option>
                                </select>
                            </label>
                            <label class="bk-review-full"><?= htmlspecialchars($t['reviews']['title_optional'] ?? 'Title (optional)') ?>
                                <input type="text" name="review_title" maxlength="120">
                            </label>
                            <label class="bk-review-full"><?= htmlspecialchars($t['reviews']['your_review'] ?? 'Your review') ?>
                                <textarea name="review_text" rows="5" required minlength="20" maxlength="2000"></textarea>
                            </label>
                        </div>
                        <?php if ($bk_recap_key !== ''): ?>
                        <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($bk_recap_key) ?>"></div>
                        <?php endif; ?>
                        <button type="submit" class="bk-btn-yellow" style="margin-top:12px"><?= htmlspecialchars($t['reviews']['submit'] ?? 'Submit review') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($bk_recap_key !== ''): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>