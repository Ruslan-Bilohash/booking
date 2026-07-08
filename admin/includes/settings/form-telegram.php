<?php /** @var array $settings @var array $ta */ ?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('telegram_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <label class="adm-toggle"><input type="checkbox" name="telegram_enabled" value="1" <?= !empty($settings['telegram_enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('telegram_enabled', $ta)) ?></span></label>
            <label class="adm-toggle"><input type="checkbox" name="telegram_notify_bookings" value="1" <?= !empty($settings['telegram_notify_bookings']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('telegram_notify_bookings', $ta)) ?></span></label>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('telegram_bot_token', $ta)) ?></label><input type="password" name="telegram_bot_token" value="" placeholder="••••••••" autocomplete="new-password"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('telegram_chat_id', $ta)) ?></label><input type="text" name="telegram_chat_id" value="<?= htmlspecialchars($settings['telegram_chat_id'] ?? '') ?>"></div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>