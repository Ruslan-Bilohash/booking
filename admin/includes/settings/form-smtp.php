<?php /** @var array $settings @var array $ta */ ?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('smtp_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <label class="adm-toggle"><input type="checkbox" name="smtp_enabled" value="1" <?= !empty($settings['smtp_enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('smtp_enabled', $ta)) ?></span></label>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_host', $ta)) ?></label><input type="text" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? '') ?>"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_port', $ta)) ?></label><input type="number" name="smtp_port" value="<?= (int) ($settings['smtp_port'] ?? 465) ?>"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_encryption', $ta)) ?></label>
                    <select name="smtp_encryption"><option value="ssl" <?= ($settings['smtp_encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL</option><option value="tls" <?= ($settings['smtp_encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS</option><option value="none" <?= ($settings['smtp_encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None</option></select>
                </div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_username', $ta)) ?></label><input type="text" name="smtp_username" value="<?= htmlspecialchars($settings['smtp_username'] ?? '') ?>"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_password', $ta)) ?></label><input type="password" name="smtp_password" value="" placeholder="••••••••" autocomplete="new-password"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_from_email', $ta)) ?></label><input type="email" name="smtp_from_email" value="<?= htmlspecialchars($settings['smtp_from_email'] ?? '') ?>"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('smtp_from_name', $ta)) ?></label><input type="text" name="smtp_from_name" value="<?= htmlspecialchars($settings['smtp_from_name'] ?? 'Booking CMS') ?>"></div>
                <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('booking_notify_email', $ta)) ?></label><input type="email" name="booking_notify_email" value="<?= htmlspecialchars($settings['booking_notify_email'] ?? '') ?>"></div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>