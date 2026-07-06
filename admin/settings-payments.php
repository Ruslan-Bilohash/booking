<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
require_once dirname(__DIR__) . '/includes/payment-settings.php';

$admin_page = 'settings';
$settings_tab = 'payments';
$page_title = bk_settings_admin_label('settings_tab_payments', $ta);
$tp = $ta['payments_page'] ?? [];
$guides = $tp['guides'] ?? [];
$fields = $tp['fields'] ?? [];
$modes = $tp['modes'] ?? [];

$payment_tab = $_GET['tab'] ?? 'paypal';
if (!bk_payment_tab_valid($payment_tab)) {
    $payment_tab = 'paypal';
}

$settings = bk_load_settings();
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = bk_payment_apply_post($payment_tab, $_POST, $settings);
    $flash = bk_save_settings($settings) ? 'success' : 'error';
    $settings = bk_load_settings();
}

$cfg = $settings[$payment_tab] ?? [];
$guide = $guides[$payment_tab] ?? [];
$configured = bk_payment_is_configured($payment_tab, $settings);

require __DIR__ . '/includes/layout.php';
$adminUrlFn = 'bk_admin_url';
bk_render_settings_tabs($adminUrlFn, $ta);
require __DIR__ . '/includes/payment-tabs.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($tp['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($tp['save_error']) ?></div>
<?php endif; ?>

<div class="adm-alert adm-alert-info">
    <i class="fas fa-flask"></i> <?= htmlspecialchars($tp['demo_note']) ?>
</div>

<div class="adm-payment-status">
    <span class="adm-badge <?= $configured ? 'adm-badge--green' : 'adm-badge--orange' ?>">
        <i class="fas fa-<?= $configured ? 'check-circle' : 'clock' ?>"></i>
        <?= htmlspecialchars($configured ? $tp['status_configured'] : $tp['status_pending']) ?>
    </span>
    <?php if (!empty($cfg['enabled'])): ?>
    <span class="adm-badge adm-badge--blue"><i class="fas fa-power-off"></i> <?= htmlspecialchars($tp['enabled']) ?></span>
    <?php endif; ?>
</div>

<form method="post" class="adm-settings-form adm-settings-form--wide">
    <div class="adm-payment-grid">
        <div class="adm-payment-form">
            <div class="adm-card">
                <div class="adm-card-head">
                    <h2><?= htmlspecialchars(($tp['tabs'] ?? [])[$payment_tab] ?? ucfirst($payment_tab)) ?></h2>
                </div>
                <div class="adm-card-body padded">
                    <div class="adm-field adm-field-check adm-field-check-block">
                        <label>
                            <input type="checkbox" name="enabled" value="1" <?= !empty($cfg['enabled']) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($tp['enable_provider']) ?>
                        </label>
                    </div>

                    <?php if ($payment_tab === 'paypal'): ?>
                    <div class="adm-form-grid adm-form-grid--settings">
                        <div class="adm-field">
                            <label><?= htmlspecialchars($fields['mode']) ?></label>
                            <select name="mode">
                                <option value="sandbox" <?= ($cfg['mode'] ?? '') === 'sandbox' ? 'selected' : '' ?>><?= htmlspecialchars($modes['sandbox']) ?></option>
                                <option value="live" <?= ($cfg['mode'] ?? '') === 'live' ? 'selected' : '' ?>><?= htmlspecialchars($modes['live']) ?></option>
                            </select>
                        </div>
                        <div class="adm-field">
                            <label><?= htmlspecialchars($fields['currency']) ?></label>
                            <input type="text" name="currency" value="<?= htmlspecialchars($cfg['currency'] ?? 'NOK') ?>" maxlength="3">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['client_id']) ?></label>
                            <input type="text" name="client_id" value="<?= htmlspecialchars($cfg['client_id'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['client_secret']) ?></label>
                            <input type="password" name="client_secret" value="" placeholder="<?= htmlspecialchars(($cfg['client_secret'] ?? '') !== '' ? bk_secret_preview($cfg['client_secret']) : ($fields['secret_placeholder'])) ?>" autocomplete="new-password">
                        </div>
                    </div>

                    <?php elseif ($payment_tab === 'stripe'): ?>
                    <div class="adm-form-grid adm-form-grid--settings">
                        <div class="adm-field">
                            <label><?= htmlspecialchars($fields['mode']) ?></label>
                            <select name="mode">
                                <option value="test" <?= ($cfg['mode'] ?? '') === 'test' ? 'selected' : '' ?>><?= htmlspecialchars($modes['test']) ?></option>
                                <option value="live" <?= ($cfg['mode'] ?? '') === 'live' ? 'selected' : '' ?>><?= htmlspecialchars($modes['live']) ?></option>
                            </select>
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['publishable_key']) ?></label>
                            <input type="text" name="publishable_key" value="<?= htmlspecialchars($cfg['publishable_key'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['secret_key']) ?></label>
                            <input type="password" name="secret_key" value="" placeholder="<?= htmlspecialchars(($cfg['secret_key'] ?? '') !== '' ? bk_secret_preview($cfg['secret_key']) : ($fields['secret_placeholder'])) ?>" autocomplete="new-password">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['webhook_secret']) ?></label>
                            <input type="password" name="webhook_secret" value="" placeholder="<?= htmlspecialchars(($cfg['webhook_secret'] ?? '') !== '' ? bk_secret_preview($cfg['webhook_secret']) : ($fields['secret_placeholder'])) ?>" autocomplete="new-password">
                        </div>
                    </div>

                    <?php elseif ($payment_tab === 'vipps'): ?>
                    <div class="adm-form-grid adm-form-grid--settings">
                        <div class="adm-field">
                            <label><?= htmlspecialchars($fields['environment']) ?></label>
                            <select name="environment">
                                <option value="test" <?= ($cfg['environment'] ?? '') === 'test' ? 'selected' : '' ?>><?= htmlspecialchars($modes['vipps_test']) ?></option>
                                <option value="production" <?= ($cfg['environment'] ?? '') === 'production' ? 'selected' : '' ?>><?= htmlspecialchars($modes['production']) ?></option>
                            </select>
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['client_id']) ?></label>
                            <input type="text" name="client_id" value="<?= htmlspecialchars($cfg['client_id'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['client_secret']) ?></label>
                            <input type="password" name="client_secret" value="" placeholder="<?= htmlspecialchars(($cfg['client_secret'] ?? '') !== '' ? bk_secret_preview($cfg['client_secret']) : ($fields['secret_placeholder'])) ?>" autocomplete="new-password">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['subscription_key']) ?></label>
                            <input type="text" name="subscription_key" value="<?= htmlspecialchars($cfg['subscription_key'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="adm-field">
                            <label><?= htmlspecialchars($fields['merchant_serial']) ?></label>
                            <input type="text" name="merchant_serial" value="<?= htmlspecialchars($cfg['merchant_serial'] ?? '') ?>" autocomplete="off">
                        </div>
                        <div class="adm-field adm-field-full">
                            <label><?= htmlspecialchars($fields['callback_token']) ?></label>
                            <input type="password" name="callback_token" value="" placeholder="<?= htmlspecialchars(($cfg['callback_token'] ?? '') !== '' ? bk_secret_preview($cfg['callback_token']) : ($fields['secret_placeholder'])) ?>" autocomplete="new-password">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="adm-form-actions adm-form-actions-sticky">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($tp['save']) ?></button>
            </div>
        </div>

        <aside class="adm-payment-guide">
            <div class="adm-card">
                <div class="adm-card-head">
                    <h2><i class="fas fa-book-open"></i> <?= htmlspecialchars($guide['title'] ?? $tp['guide_title']) ?></h2>
                </div>
                <div class="adm-card-body padded adm-guide-body">
                    <?php if (!empty($guide['intro'])): ?>
                    <p class="adm-guide-intro"><?= htmlspecialchars($guide['intro']) ?></p>
                    <?php endif; ?>

                    <?php if (!empty($guide['steps']) && is_array($guide['steps'])): ?>
                    <ol class="adm-guide-steps">
                        <?php foreach ($guide['steps'] as $step): ?>
                        <li><?= htmlspecialchars($step) ?></li>
                        <?php endforeach; ?>
                    </ol>
                    <?php endif; ?>

                    <?php if (!empty($guide['links']) && is_array($guide['links'])): ?>
                    <div class="adm-guide-links">
                        <strong><?= htmlspecialchars($tp['useful_links']) ?></strong>
                        <ul>
                            <?php foreach ($guide['links'] as $link): ?>
                            <li>
                                <a href="<?= htmlspecialchars($link['url'] ?? '#') ?>" target="_blank" rel="noopener">
                                    <?= htmlspecialchars($link['label'] ?? '') ?> <i class="fas fa-external-link-alt"></i>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($guide['note'])): ?>
                    <p class="adm-guide-note"><i class="fas fa-lightbulb"></i> <?= htmlspecialchars($guide['note']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</form>

<?php require __DIR__ . '/includes/layout-end.php'; ?>