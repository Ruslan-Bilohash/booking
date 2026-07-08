<?php /** @var array $settings @var array $ta */ ?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('advanced_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <label class="adm-toggle"><input type="checkbox" name="maintenance_mode" value="1" <?= !empty($settings['maintenance_mode']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('maintenance_mode', $ta)) ?></span></label>
            <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('maintenance_message', $ta)) ?></label><textarea name="maintenance_message" rows="2"><?= htmlspecialchars($settings['maintenance_message'] ?? '') ?></textarea></div>
            <label class="adm-toggle"><input type="checkbox" name="cookie_consent" value="1" <?= !empty($settings['cookie_consent']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('cookie_consent', $ta)) ?></span></label>
            <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('custom_head_code', $ta)) ?></label><textarea name="custom_head_code" rows="4"><?= htmlspecialchars($settings['custom_head_code'] ?? '') ?></textarea></div>
            <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('custom_footer_code', $ta)) ?></label><textarea name="custom_footer_code" rows="4"><?= htmlspecialchars($settings['custom_footer_code'] ?? '') ?></textarea></div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>