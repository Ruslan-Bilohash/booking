<?php
require_once __DIR__ . '/init.php';
bk_admin_require();

require_once dirname(__DIR__) . '/includes/license-runtime.php';
require_once dirname(__DIR__) . '/includes/subscription-links.php';
require_once dirname(__DIR__) . '/includes/billing-pricing.php';

$admin_page = 'license';
$ta = $t['admin'] ?? [];
$lp = $ta['license_page'] ?? [];
$bp = $ta['billing_demo_page'] ?? [];
$page_title = $lp['title'] ?? 'License';

$status = bk_license_status();
$state = bk_license_state();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key = trim((string) ($_POST['license_key'] ?? ''));
    $result = bk_license_activate($key);
    if ($result['ok']) {
        $success = $lp['activated'] ?? 'License activated successfully';
        $status = bk_license_status();
        $state = bk_license_state();
    } else {
        $error = $result['error'] ?: ($lp['invalid'] ?? 'Invalid license key');
    }
}

$licenseInfo = bk_license_verify_current(true);

$admin_extra_js = [
    bk_asset('js/admin-license-sites.js') . '?v=1',
    bk_asset('js/admin-domain-hostinger.js') . '?v=1',
];
if (bk_admin_is_owner()) {
    $admin_extra_js[] = bk_asset('js/admin-license-panel.js') . '?v=1';
}

bk_billing_ecosystem_inc();
$ecoLang = bk_billing_ecosystem_lang($lang);
$pricing = function_exists('ecosystem_pricing_for_lang') ? ecosystem_pricing_for_lang($ecoLang) : [];
$apiMonthly = (int) ($pricing['api_requests_monthly'] ?? 100);
$apiYearly = (int) ($pricing['api_requests_yearly'] ?? 500);
$subUrl = bk_subscription_url();
$cabinetUrl = bk_license_cabinet_url();
$tagline = bk_billing_subscription_tagline($lang);

$subHelp = (string) ($lp['subscription_help'] ?? 'One BILOHASH subscription for all CMS scripts and AI — {tagline}.');
if (str_contains($subHelp, '{tagline}')) {
    $subHelp = str_replace('{tagline}', $tagline, $subHelp);
}

require __DIR__ . '/includes/layout.php';
?>
<?php require __DIR__ . '/includes/license-admin-panel.php'; ?>
<?php require __DIR__ . '/includes/license-sites-manager.php'; ?>
<?php require __DIR__ . '/includes/domain-hostinger-panel.php'; ?>

<div class="adm-my-console">
    <div class="adm-my-hero">
        <div class="adm-my-hero-main">
            <span class="adm-my-avatar" aria-hidden="true"><i class="fas fa-key"></i></span>
            <div>
                <h2 class="adm-my-hero-title"><i class="fas fa-crown"></i> <?= htmlspecialchars($lp['subscription_title'] ?? 'BILOHASH subscription') ?></h2>
                <p class="adm-my-hero-text"><?= htmlspecialchars($subHelp) ?></p>
                <p class="adm-ai-hero-subscribe">
                    <a href="<?= htmlspecialchars($subUrl) ?>" class="adm-btn adm-btn-primary adm-btn-sm" <?= bk_subscription_external_attrs() ?>>
                        <i class="fas fa-layer-group"></i> <?= htmlspecialchars($lp['subscribe_btn'] ?? 'Get subscription') ?>
                    </a>
                    <a href="<?= htmlspecialchars($cabinetUrl) ?>" class="adm-btn adm-btn-outline adm-btn-sm" <?= bk_subscription_external_attrs() ?>>
                        <i class="fas fa-door-open"></i> <?= htmlspecialchars($lp['cabinet_btn'] ?? 'Customer cabinet') ?>
                    </a>
                    <span class="adm-muted adm-ai-hero-tagline"><?= htmlspecialchars($tagline) ?></span>
                </p>
            </div>
        </div>
    </div>

    <div class="adm-card adm-my-sub-card">
        <div class="adm-card-head">
            <h2><i class="fas fa-receipt"></i> <?= htmlspecialchars($lp['plans_title'] ?? 'Subscription plans') ?></h2>
        </div>
        <div class="adm-card-body padded">
            <div class="adm-my-plans">
                <article class="adm-bd-plan adm-my-plan">
                    <h3><?= htmlspecialchars($bp['monthly_title'] ?? '1 CMS script') ?></h3>
                    <p class="adm-bd-price"><?= htmlspecialchars((string) ($pricing['monthly_fmt'] ?? '49 kr')) ?><small>/<?= htmlspecialchars($bp['per_month'] ?? 'mo') ?></small></p>
                    <ul class="adm-bd-features">
                        <li><i class="fas fa-check"></i> <?= htmlspecialchars($bp['feat_script_monthly'] ?? '1 CMS script · 1 domain') ?></li>
                        <li><i class="fas fa-check"></i> <?= htmlspecialchars(strtr($bp['feat_api'] ?? '{n} BILOHASH AI API requests', ['{n}' => (string) $apiMonthly])) ?></li>
                    </ul>
                </article>
                <article class="adm-bd-plan adm-bd-plan--yearly adm-my-plan">
                    <span class="adm-bd-save"><?= htmlspecialchars($bp['yearly_badge'] ?? 'Full library') ?></span>
                    <h3><?= htmlspecialchars($bp['yearly_title'] ?? 'All CMS scripts') ?></h3>
                    <p class="adm-bd-price"><?= htmlspecialchars((string) ($pricing['full_monthly_fmt'] ?? $pricing['yearly_fmt'] ?? '249 kr')) ?><small>/<?= htmlspecialchars($bp['per_month'] ?? 'mo') ?></small></p>
                    <ul class="adm-bd-features">
                        <li><i class="fas fa-check"></i> <?= htmlspecialchars($bp['feat_all_yearly'] ?? 'All CMS scripts · releases & updates') ?></li>
                        <li><i class="fas fa-check"></i> <?= htmlspecialchars(strtr($bp['feat_api'] ?? '{n} BILOHASH AI API requests', ['{n}' => (string) $apiYearly])) ?></li>
                    </ul>
                </article>
            </div>
            <p class="adm-help adm-help-compact" style="margin-top:12px"><?= htmlspecialchars($lp['cabinet_help'] ?? '') ?></p>
        </div>
    </div>

    <div class="adm-card" style="max-width:720px">
        <div class="adm-card-head">
            <h2><i class="fas fa-key"></i> <?= htmlspecialchars($page_title) ?></h2>
        </div>
        <div class="adm-card-body padded">
            <?php if ($status['status'] === 'licensed'): ?>
            <p class="adm-badge adm-badge-green"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($lp['status_licensed'] ?? 'Licensed') ?></p>
            <?php elseif ($status['status'] === 'trial'): ?>
            <p class="adm-badge adm-badge-info"><i class="fas fa-hourglass-half"></i> <?= htmlspecialchars(sprintf($lp['status_trial'] ?? 'Trial: %d days left', $status['trial_days_left'])) ?></p>
            <?php else: ?>
            <p class="adm-badge adm-badge-warn"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($lp['status_expired'] ?? 'Trial expired — activate license') ?></p>
            <?php endif; ?>

            <p class="adm-muted"><?= htmlspecialchars($lp['intro'] ?? '') ?></p>

            <?php if ($error): ?>
            <div class="adm-login-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
            <div class="adm-flash adm-flash-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="post" style="margin-top:16px">
                <div class="adm-field">
                    <label for="license_key"><?= htmlspecialchars($lp['key_label'] ?? 'License key (BHBOOK.…)') ?></label>
                    <input type="text" id="license_key" name="license_key" required autocomplete="off" placeholder="BHBOOK.… BHECO.…" value="">
                </div>
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-unlock"></i> <?= htmlspecialchars($lp['activate'] ?? 'Activate license') ?></button>
            </form>

            <?php if (!empty($state['activated_at'])): ?>
            <p class="adm-muted" style="margin-top:16px;font-size:12px">
                <?= htmlspecialchars($lp['activated_at'] ?? 'Activated') ?>: <?= htmlspecialchars((string) $state['activated_at']) ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/includes/layout-end.php'; ?>