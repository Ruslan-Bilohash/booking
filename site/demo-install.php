<?php
require_once __DIR__ . '/init.php';

$di = $t['demo_install'] ?? [];
$page_title = $di['page_title'] ?? 'Download Booking CMS — 30-day demo';
$page_desc  = $di['meta_description'] ?? '';
$canonical  = $site_url . '/demo-install.php' . ($lang !== 'no' ? '?lang=' . $lang : '');
$canon_abs  = bks_absolute_url($canonical);
$seo_schemas = bks_seo_schemas($canon_abs, $page_title, $page_desc);

$ecoPricing = dirname(__DIR__, 2) . '/includes/ecosystem-pricing.php';
if (is_file($ecoPricing)) {
    require_once $ecoPricing;
}
$fxNote = function_exists('ecosystem_pricing_fx_note')
    ? ecosystem_pricing_fx_note($lang === 'uk' ? 'ua' : ($lang === 'no' ? 'no' : 'en'))
    : '';
$scriptBadge = function_exists('ecosystem_script_pricing_badge')
    ? ecosystem_script_pricing_badge($lang === 'uk' ? 'ua' : ($lang === 'no' ? 'no' : 'en'))
    : '';
$fullBadge = function_exists('ecosystem_full_pricing_badge')
    ? ecosystem_full_pricing_badge($lang === 'uk' ? 'ua' : ($lang === 'no' ? 'no' : 'en'))
    : '';

$cabinetLang = $lang === 'uk' ? 'ua' : ($lang === 'no' ? 'no' : 'en');
$cabinetFile = dirname(__DIR__, 2) . '/includes/license-cabinet-i18n.php';
$cabinetUrl = '/ecosystem/cabinet.php?product=booking' . ($cabinetLang !== 'en' ? '&lang=' . urlencode($cabinetLang === 'ua' ? 'uk' : $cabinetLang) : '');
if (is_file($cabinetFile)) {
    require_once $cabinetFile;
    $cabinetUrl = license_cabinet_url($cabinetLang) . (str_contains(license_cabinet_url($cabinetLang), '?') ? '&' : '?') . 'product=booking';
}

$formResult = null;
$formValues = [
    'contact_name' => '',
    'email'        => '',
    'domain'       => '',
    'ftp_host'     => '',
    'ftp_user'     => '',
    'ftp_pass'     => '',
    'ftp_path'     => '/public_html/booking',
    'license_key'  => '',
];
$csrf = '';
$hasDemoZip = false;
$cabinetLoggedIn = false;
$downloadToken = '';
$downloadApi = '/api/booking-demo-download.php';
$demoPkgFile = dirname(__DIR__) . '/includes/demo-package.php';
if (is_file($demoPkgFile)) {
    require_once $demoPkgFile;
    $hasDemoZip = bk_demo_package_latest_zip() !== null;
}
$cabinetRoot = dirname(__DIR__, 2) . '/includes/license-cabinet.php';
if (is_file($cabinetRoot)) {
    require_once $cabinetRoot;
    license_cabinet_ensure_session();
    $cabinetLoggedIn = license_cabinet_is_logged_in();
    if ($cabinetLoggedIn) {
        $formValues['email'] = license_cabinet_user_email();
        $downloadToken = license_cabinet_download_token_issue($formValues['email']);
    }
}

$diReqFile = dirname(__DIR__, 2) . '/includes/demo-install-request.php';
if (is_file($diReqFile)) {
    require_once $diReqFile;
    $csrf = demo_install_request_ensure_csrf();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auto_install'])) {
        $_POST['product'] = 'booking';
        $formResult = demo_install_request_handle_post($lang);
        $formValues = $formResult['values'];
    }
}

require __DIR__ . '/includes/header.php';
?>

<section class="bks-order-hero">
    <div class="bks-container">
        <div class="bks-section-head">
            <h1><?= htmlspecialchars($di['h1'] ?? 'Self-install Booking CMS') ?></h1>
            <p class="bks-section-sub"><?= htmlspecialchars($di['subtitle'] ?? '') ?></p>
        </div>
        <?php if ($fxNote !== ''): ?>
        <p class="bks-order-intro"><i class="fas fa-exchange-alt"></i> <?= htmlspecialchars($fxNote) ?></p>
        <?php endif; ?>
        <div class="bks-di-plans">
            <span class="bks-di-plan-pill"><?= htmlspecialchars($scriptBadge) ?></span>
            <span class="bks-di-plan-pill bks-di-plan-pill--full"><?= htmlspecialchars($fullBadge) ?></span>
        </div>
    </div>
</section>

<section class="bks-section bks-order-body">
    <div class="bks-container bks-di-grid">
        <div class="bks-order-block">
            <h2 class="bks-order-heading"><i class="fas fa-list-ol"></i> <?= htmlspecialchars($di['steps_title'] ?? 'Installation steps') ?></h2>
            <ol class="bks-order-steps">
                <?php foreach ($di['steps'] ?? [] as $step): ?>
                <li><?= htmlspecialchars($step) ?></li>
                <?php endforeach; ?>
            </ol>
            <p class="bks-help"><?= htmlspecialchars(strtr($di['trial_note'] ?? 'Trial: {days} days on one domain.', ['{days}' => (string) (defined('BK_DEMO_TRIAL_DAYS') ? BK_DEMO_TRIAL_DAYS : 30)])) ?></p>
            <p class="bks-help"><?= htmlspecialchars($di['license_note'] ?? '') ?></p>
            <div class="bks-di-cabinet-alt">
                <h3 class="bks-order-heading bks-order-heading--sm"><i class="fas fa-door-open"></i> <?= htmlspecialchars($di['cabinet_only_title'] ?? 'Download in customer cabinet') ?></h3>
                <p class="bks-help"><?= htmlspecialchars($di['cabinet_only_text'] ?? '') ?></p>
                <a href="<?= htmlspecialchars($cabinetUrl) ?>#download" class="bks-btn-outline">
                    <i class="fas fa-download"></i> <?= htmlspecialchars($di['cabinet_cta'] ?? 'Open customer cabinet') ?>
                </a>
            </div>
        </div>

        <div class="bks-order-block bks-di-form-block">
            <h2 class="bks-order-heading"><i class="fas fa-box-open"></i> <?= htmlspecialchars($di['form_title'] ?? 'Get package') ?></h2>

            <div class="bks-di-tabs" role="tablist">
                <button type="button" class="bks-di-tab is-active" data-tab="download" role="tab" aria-selected="true">
                    <i class="fas fa-download"></i> <?= htmlspecialchars($di['tab_download'] ?? 'Download ZIP') ?>
                </button>
                <button type="button" class="bks-di-tab" data-tab="ftp" role="tab" aria-selected="false">
                    <i class="fas fa-cloud-upload-alt"></i> <?= htmlspecialchars($di['tab_ftp'] ?? 'FTP install') ?>
                </button>
            </div>

            <div id="bksDiPanelDownload" class="bks-di-panel" role="tabpanel">
                <p class="bks-help"><?= htmlspecialchars($di['download_help'] ?? '') ?></p>
                <?php if (!$hasDemoZip): ?>
                <p class="bks-di-message bks-di-warn"><?= htmlspecialchars($di['package_missing'] ?? 'Package unavailable') ?></p>
                <?php else: ?>
                <?php if (!$cabinetLoggedIn): ?>
                <p class="bks-di-cabinet-hint">
                    <?= htmlspecialchars($di['cabinet_only_text'] ?? '') ?>
                    <a href="<?= htmlspecialchars($cabinetUrl) ?>#download"><?= htmlspecialchars($di['cabinet_cta'] ?? 'Open customer cabinet') ?></a>
                </p>
                <?php endif; ?>
                <form id="bksDiDownloadForm" class="bks-di-form" autocomplete="off"
                      data-config="<?= htmlspecialchars(json_encode([
                          'api'       => $downloadApi,
                          'loggedIn'  => $cabinetLoggedIn,
                          'cabinetUrl'=> $cabinetUrl . '#download',
                          'filename'  => bk_demo_package_basename() ?: 'booking.zip',
                          'errAuth'   => $di['cabinet_login_required'] ?? 'Sign in to the customer cabinet first.',
                          'errTerms'  => $di['err_terms'] ?? '',
                          'errGeneric'=> $di['err_generic'] ?? '',
                          'okDownload'=> $di['ok_download'] ?? 'Download started.',
                          'errors'    => [
                              'cabinet_required' => $di['cabinet_login_required'] ?? '',
                              'terms'            => $di['err_terms'] ?? '',
                              'license'          => $di['err_license'] ?? '',
                              'package_missing'  => $di['package_missing'] ?? '',
                          ],
                      ], JSON_UNESCAPED_UNICODE)) ?>">
                    <input type="hidden" name="lang" value="<?= htmlspecialchars($lang) ?>">
                    <input type="hidden" name="download_token" value="<?= htmlspecialchars($downloadToken) ?>">
                    <div class="bks-field">
                        <label for="di_dl_email"><?= htmlspecialchars($di['email'] ?? 'Email') ?></label>
                        <input class="bks-input" type="email" id="di_dl_email" name="email" required
                               value="<?= htmlspecialchars($formValues['email']) ?>" <?= $cabinetLoggedIn ? 'readonly' : '' ?>>
                    </div>
                    <div class="bks-field">
                        <label for="di_dl_domain"><?= htmlspecialchars($di['domain'] ?? 'Domain') ?></label>
                        <input class="bks-input" type="text" id="di_dl_domain" name="domain" required placeholder="example.com"
                               value="<?= htmlspecialchars($formValues['domain']) ?>">
                    </div>
                    <div class="bks-field">
                        <label for="di_dl_license"><?= htmlspecialchars($di['license_optional'] ?? 'License key (optional)') ?></label>
                        <input class="bks-input" type="text" id="di_dl_license" name="license_key"
                               value="<?= htmlspecialchars($formValues['license_key']) ?>">
                    </div>
                    <label class="bks-di-terms">
                        <input type="checkbox" name="terms" value="1" required>
                        <span>
                            <?= htmlspecialchars($di['terms_label'] ?? '') ?>
                            <a href="<?= htmlspecialchars($di['terms_url'] ?? 'https://bilohash.com/ecosystem/install.php') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($di['terms_link'] ?? '') ?></a>
                        </span>
                    </label>
                    <button type="submit" class="bks-btn-primary bks-btn-lg" id="bksDiDownloadSubmit">
                        <i class="fas fa-download"></i> <?= htmlspecialchars($di['submit_download'] ?? 'Download demo package') ?>
                    </button>
                    <p id="bksDiDownloadMessage" class="bks-di-message" hidden role="status"></p>
                </form>
                <?php endif; ?>
            </div>

            <div id="bksDiPanelFtp" class="bks-di-panel" role="tabpanel" hidden>
            <h3 class="bks-order-heading bks-order-heading--sm"><i class="fas fa-cloud-upload-alt"></i> <?= htmlspecialchars($di['auto_install_title'] ?? 'Auto-install on your domain') ?></h3>
            <p class="bks-help"><?= htmlspecialchars($di['auto_install_help'] ?? $di['ftp_help'] ?? '') ?></p>

            <?php if ($formResult !== null): ?>
            <p class="bks-di-message <?= $formResult['type'] === 'success' ? 'is-ok' : ($formResult['type'] === 'warn' ? 'bks-di-warn' : 'is-error') ?>">
                <?= htmlspecialchars($formResult['message']) ?>
            </p>
            <?php endif; ?>

            <form method="post" class="bks-di-form" autocomplete="off">
                <input type="hidden" name="auto_install" value="1">
                <input type="hidden" name="product" value="booking">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf) ?>">
                <div class="bks-field" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                    <label for="di_website">Website</label>
                    <input type="text" id="di_website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="bks-field">
                    <label for="di_contact_name"><?= htmlspecialchars($di['contact_name'] ?? 'Your name') ?></label>
                    <input class="bks-input" type="text" id="di_contact_name" name="contact_name" required
                           value="<?= htmlspecialchars($formValues['contact_name']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_email"><?= htmlspecialchars($di['email'] ?? 'Email') ?></label>
                    <input class="bks-input" type="email" id="di_email" name="email" required
                           value="<?= htmlspecialchars($formValues['email']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_domain"><?= htmlspecialchars($di['domain'] ?? 'Your domain') ?></label>
                    <input class="bks-input" type="text" id="di_domain" name="domain" required placeholder="example.com"
                           value="<?= htmlspecialchars($formValues['domain']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_ftp_host"><?= htmlspecialchars($di['ftp_host'] ?? 'FTP host') ?></label>
                    <input class="bks-input" type="text" id="di_ftp_host" name="ftp_host" required placeholder="ftp.example.com"
                           value="<?= htmlspecialchars($formValues['ftp_host']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_ftp_user"><?= htmlspecialchars($di['ftp_user'] ?? 'FTP username') ?></label>
                    <input class="bks-input" type="text" id="di_ftp_user" name="ftp_user" required
                           value="<?= htmlspecialchars($formValues['ftp_user']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_ftp_pass"><?= htmlspecialchars($di['ftp_pass'] ?? 'FTP password') ?></label>
                    <input class="bks-input" type="password" id="di_ftp_pass" name="ftp_pass" required
                           value="<?= htmlspecialchars($formValues['ftp_pass']) ?>">
                </div>
                <div class="bks-field">
                    <label for="di_ftp_path"><?= htmlspecialchars($di['ftp_path'] ?? 'Remote path') ?></label>
                    <input class="bks-input" type="text" id="di_ftp_path" name="ftp_path"
                           value="<?= htmlspecialchars($formValues['ftp_path'] !== '' ? $formValues['ftp_path'] : '/public_html/booking') ?>">
                </div>
                <div class="bks-field">
                    <label for="di_license_key"><?= htmlspecialchars($di['license_optional'] ?? 'License key (optional)') ?></label>
                    <input class="bks-input" type="text" id="di_license_key" name="license_key"
                           value="<?= htmlspecialchars($formValues['license_key']) ?>">
                </div>

                <label class="bks-di-terms">
                    <input type="checkbox" name="terms" value="1" required>
                    <span>
                        <?= htmlspecialchars($di['terms_label'] ?? 'I agree to the installation terms') ?>
                        <a href="<?= htmlspecialchars($di['terms_url'] ?? 'https://bilohash.com/ecosystem/install.php') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($di['terms_link'] ?? 'Read terms') ?></a>
                    </span>
                </label>

                <button type="submit" class="bks-btn-primary bks-btn-lg">
                    <i class="fas fa-paper-plane"></i> <?= htmlspecialchars($di['submit_ftp'] ?? 'Submit auto-install request') ?>
                </button>
            </form>
            </div>
        </div>
    </div>
</section>

<script src="<?= htmlspecialchars(bks_asset('js/demo-install.js')) ?>?v=1" defer></script>
<?php require __DIR__ . '/includes/footer.php'; ?>