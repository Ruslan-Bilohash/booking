<?php
require_once __DIR__ . '/init.php';
require_once __DIR__ . '/includes/site-integrations.php';
require_once __DIR__ . '/includes/payment-settings.php';
require_once dirname(__DIR__) . '/includes/cms-contact.php';
bk_boot_public_integrations();
$bk_settings = bk_site_settings();

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$property = $id ? bk_property_by_id($id) : null;
$search_params = bk_search_params();
$success = false;
$ref = '';
$book_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $property) {
    require_once __DIR__ . '/includes/storage.php';
    $demo_methods = bk_demo_payment_methods();
    $payment_method = trim((string) ($_POST['payment_method'] ?? ''));
    if (!in_array($payment_method, $demo_methods, true)) {
        $book_error = $t['book']['error_payment'] ?? 'Please select a payment method.';
    } elseif (!bk_verify_recaptcha($_POST['g-recaptcha-response'] ?? '', $bk_settings)) {
        $book_error = $t['book']['error_captcha'] ?? 'Please complete reCAPTCHA.';
    } else {
    $success = true;
    $ref = 'BK-DEMO-' . strtoupper(substr(md5(uniqid((string)mt_rand(), true)), 0, 8));
    $nights_post = bk_nights($search_params['checkin'], $search_params['checkout']);
    $subtotal_post = bk_total_price($property, $nights_post, $search_params['rooms']);
    $price_post = bk_booking_price_breakdown($subtotal_post, $bk_settings);
    bk_add_booking([
        'ref'           => $ref,
        'property_id'   => $property['id'],
        'property_name' => bk_localized($property, 'name', $lang),
        'guest'         => trim(($_POST['firstname'] ?? '') . ' ' . ($_POST['lastname'] ?? '')),
        'email'         => trim($_POST['email'] ?? ''),
        'phone'         => trim($_POST['phone'] ?? ''),
        'requests'      => trim($_POST['requests'] ?? ''),
        'checkin'       => $search_params['checkin'],
        'checkout'      => $search_params['checkout'],
        'adults'        => $search_params['adults'],
        'children'      => $search_params['children'],
        'rooms'         => $search_params['rooms'],
        'total'         => $price_post['total'],
        'subtotal'      => $price_post['subtotal'],
        'tax'           => $price_post['tax'],
        'payment_method'=> $payment_method !== '' ? $payment_method : 'demo',
        'status'        => 'confirmed',
    ]);
    }
}

$name = $property ? bk_localized($property, 'name', $lang) : '';
$city = $property ? bk_localized($property, 'city', $lang) : '';
$nights = $property ? bk_nights($search_params['checkin'], $search_params['checkout']) : 1;
$subtotal = $property ? bk_total_price($property, $nights, $search_params['rooms']) : 0;
$price = $property ? bk_booking_price_breakdown($subtotal, $bk_settings) : [
    'subtotal' => 0, 'tax' => 0, 'total' => 0, 'tax_enabled' => false, 'show_breakdown' => false,
];
$total = $price['total'];
$bk_payment_methods = bk_demo_payment_methods();
$bk_tax_label = bk_tax_label($lang, $bk_settings);
$page_title = $success ? $t['book']['success'] : $t['book']['title'];
$page_desc  = ($property ? $name . ' — ' : '') . $t['meta']['description'];
$canonical  = $property
    ? $site_url . '/book.php?' . http_build_query(array_merge($search_params, ['id' => $property['id']]))
    : $site_url . '/book.php';
$canon_abs  = bk_absolute_url($canonical);
$seo_schemas = [bk_seo_webpage($canon_abs, $page_title, $page_desc)];
if ($property) {
    $seo_schemas[] = bk_seo_breadcrumbs([
        ['name' => $t['breadcrumb_home'], 'url' => bk_absolute_url(bk_url('index.php'))],
        ['name' => $name, 'url' => bk_absolute_url(bk_url('property.php?id=' . urlencode($property['id'])))],
        ['name' => $t['book']['title'], 'url' => $canon_abs],
    ]);
}
$bk_needs_recaptcha = cms_recaptcha_site_key() !== '';
$body_class = 'bk-page-book';
require __DIR__ . '/includes/header.php';
if ($bk_needs_recaptcha): ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php endif;

if (!$property && !$success):
?>
<div class="bk-container">
    <div class="bk-form-card bk-form-card--empty">
        <p><?= htmlspecialchars($t['search_page']['no_results']) ?></p>
        <a href="<?= bk_url('index.php') ?>" class="bk-btn-blue" style="display:inline-block;margin-top:12px;text-decoration:none"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
    </div>
</div>
<?php
require __DIR__ . '/includes/footer.php';
exit;
endif;
?>

<?php if ($success): ?>
<div class="bk-container">
<div class="bk-success">
    <i class="fas fa-circle-check"></i>
    <h1><?= htmlspecialchars($t['book']['success']) ?></h1>
    <p><?= htmlspecialchars($t['book']['success_msg']) ?></p>
    <div class="bk-ref"><?= htmlspecialchars($ref) ?></div>
    <p class="bk-success-meta"><?= htmlspecialchars($name) ?> · <?= bk_price($total) ?></p>
    <div class="bk-success-actions">
        <a href="<?= bk_url('index.php') ?>" class="bk-btn-blue"><?= htmlspecialchars($t['book']['back_home']) ?></a>
        <a href="<?= bk_url('property.php?' . http_build_query(array_merge($search_params, ['id' => $property['id']]))) ?>" class="bk-btn-outline"><?= htmlspecialchars($t['book']['view_booking']) ?></a>
    </div>
</div>
</div>
<?php else: ?>

<div class="bk-container">
    <nav class="bk-breadcrumb" aria-label="<?= htmlspecialchars($t['a11y']['breadcrumb'] ?? 'Breadcrumb') ?>">
        <a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        → <a href="<?= bk_url('search.php?destination=' . urlencode($city)) ?>"><?= htmlspecialchars($city) ?></a>
        → <a href="<?= bk_url('property.php?' . http_build_query(array_merge($search_params, ['id' => $property['id']]))) ?>"><?= htmlspecialchars($name) ?></a>
        → <?= htmlspecialchars($t['book']['title']) ?>
    </nav>
    <h1 class="bk-page-title"><?= htmlspecialchars($t['book']['title']) ?></h1>
    <div class="bk-form-layout">
        <div class="bk-form-card">
            <h2><?= htmlspecialchars($t['book']['details']) ?></h2>
            <?php if ($book_error !== ''): ?>
            <div class="bk-demo-alert" style="margin-bottom:16px"><?= htmlspecialchars($book_error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="id" value="<?= htmlspecialchars($property['id']) ?>">
                <?php foreach (['checkin','checkout','adults','children','rooms'] as $k): ?>
                <input type="hidden" name="<?= $k ?>" value="<?= htmlspecialchars($search_params[$k]) ?>">
                <?php endforeach; ?>
                <div class="bk-form-row">
                    <div class="bk-field">
                        <label for="firstname"><?= htmlspecialchars($t['book']['firstname']) ?> *</label>
                        <input type="text" id="firstname" name="firstname" required value="Demo">
                    </div>
                    <div class="bk-field">
                        <label for="lastname"><?= htmlspecialchars($t['book']['lastname']) ?> *</label>
                        <input type="text" id="lastname" name="lastname" required value="Guest">
                    </div>
                </div>
                <div class="bk-field">
                    <label for="email"><?= htmlspecialchars($t['book']['email']) ?> *</label>
                    <input type="email" id="email" name="email" required value="demo@bilohash.com">
                </div>
                <div class="bk-field">
                    <label for="phone"><?= htmlspecialchars($t['book']['phone']) ?></label>
                    <input type="tel" id="phone" name="phone" value="+4746255885">
                </div>
                <div class="bk-field">
                    <label for="requests"><?= htmlspecialchars($t['book']['requests']) ?></label>
                    <textarea id="requests" name="requests" placeholder="..."></textarea>
                </div>
                <?php if ($bk_payment_methods !== []): ?>
                <div class="bk-field">
                    <span class="bk-field-label-block"><?= htmlspecialchars($t['book']['payment_method'] ?? 'Payment method') ?></span>
                    <div class="bk-payment-methods" role="radiogroup" aria-label="<?= htmlspecialchars($t['book']['payment_method'] ?? 'Payment method') ?>">
                        <?php foreach ($bk_payment_methods as $i => $method): ?>
                        <label class="bk-payment-option bk-payment-option--<?= htmlspecialchars($method) ?>">
                            <input type="radio" name="payment_method" value="<?= htmlspecialchars($method) ?>" <?= $i === 0 ? 'checked' : '' ?> required>
                            <span class="bk-payment-option-inner">
                                <?= bk_payment_method_icon_html($method) ?>
                                <span><?= htmlspecialchars(bk_payment_method_label($method, $t)) ?></span>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <p class="bk-payment-demo-note"><?= htmlspecialchars($t['book']['payment_demo'] ?? '') ?></p>
                </div>
                <?php endif; ?>
                <?php $bk_recap_key = cms_recaptcha_site_key(); if ($bk_recap_key !== ''): ?>
                <div class="bk-field bk-recaptcha-wrap">
                    <div class="g-recaptcha" data-sitekey="<?= htmlspecialchars($bk_recap_key) ?>"></div>
                </div>
                <?php endif; ?>
                <button type="submit" class="bk-btn-yellow bk-form-submit">
                    <?= htmlspecialchars($t['book']['pay']) ?>
                </button>
            </form>
        </div>

        <aside class="bk-booking-box">
            <h3 class="bk-booking-box-title"><?= htmlspecialchars($t['book']['summary']) ?></h3>
            <img src="<?= htmlspecialchars(bk_property_image($property)) ?>" alt="" class="bk-book-summary-img" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
            <strong class="bk-book-summary-name"><?= htmlspecialchars($name) ?></strong>
            <div class="bk-book-summary-meta">
                <?= htmlspecialchars($search_params['checkin']) ?> — <?= htmlspecialchars($search_params['checkout']) ?><br>
                <?= sprintf($t['property']['nights'], $nights) ?><br>
                <?= (int)$search_params['adults'] ?> <?= htmlspecialchars($t['search']['adults']) ?>, <?= (int)$search_params['rooms'] ?> <?= htmlspecialchars($t['search']['rooms']) ?>
            </div>
            <hr class="bk-book-summary-divider">
            <div class="bk-price-lines">
                <?php if (!empty($price['show_breakdown']) && !empty($price['tax_enabled']) && (int) $price['tax'] > 0): ?>
                <div class="bk-price-line">
                    <span><?= htmlspecialchars($t['book']['subtotal'] ?? 'Subtotal') ?></span>
                    <span><?= bk_price((int) $price['subtotal']) ?></span>
                </div>
                <div class="bk-price-line">
                    <span><?= htmlspecialchars($bk_tax_label) ?> (<?= number_format((float) ($price['tax_rate'] ?? 0), 1, '.', '') ?>%)</span>
                    <span><?= bk_price((int) $price['tax']) ?></span>
                </div>
                <?php endif; ?>
                <div class="bk-book-summary-total">
                    <span><?= htmlspecialchars($t['property']['total']) ?></span>
                    <span><?= bk_price($total) ?></span>
                </div>
            </div>
            <p class="bk-book-tax-note"><?= htmlspecialchars(bk_tax_display_note($lang, $bk_settings)) ?></p>
            <div class="bk-demo-alert"><?= htmlspecialchars($t['property']['demo_note']) ?></div>
        </aside>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>