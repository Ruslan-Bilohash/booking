<?php /** @var array $settings @var array $ta */ ?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('analytics_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('analytics_help', $ta)) ?></p>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars(bk_settings_admin_label('tracking_gtag_id', $ta)) ?></label>
                    <input type="text" name="tracking_gtag_id" value="<?= htmlspecialchars($settings['tracking_gtag_id'] ?? '') ?>" placeholder="G-XXXXXXXX">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars(bk_settings_admin_label('tracking_meta_pixel', $ta)) ?></label>
                    <input type="text" name="tracking_meta_pixel" value="<?= htmlspecialchars($settings['tracking_meta_pixel'] ?? '') ?>" placeholder="Meta Pixel ID">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars(bk_settings_admin_label('tracking_tiktok_pixel', $ta)) ?></label>
                    <input type="text" name="tracking_tiktok_pixel" value="<?= htmlspecialchars($settings['tracking_tiktok_pixel'] ?? '') ?>">
                </div>
            </div>
            <h3 class="adm-subhead"><?= htmlspecialchars(bk_settings_admin_label('google_ads_section', $ta)) ?></h3>
            <label class="adm-toggle"><input type="checkbox" name="google_ads_enabled" value="1" <?= !empty($settings['google_ads_enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('google_ads_enabled', $ta)) ?></span></label>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('google_ads_id', $ta)) ?></label><input type="text" name="google_ads_id" value="<?= htmlspecialchars($settings['google_ads_id'] ?? '') ?>" placeholder="AW-XXXXXXXXX"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('google_ads_conversion_label', $ta)) ?></label><input type="text" name="google_ads_conversion_label" value="<?= htmlspecialchars($settings['google_ads_conversion_label'] ?? '') ?>"></div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>