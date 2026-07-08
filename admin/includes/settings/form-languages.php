<?php
/** @var array $settings @var array $ta */
$enabled = $settings['enabled_langs'] ?? array_keys(bk_langs());
?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('languages_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('languages_help', $ta)) ?></p>
            <div class="adm-form-grid adm-form-grid--checks">
                <?php foreach (bk_langs() as $code => $meta): ?>
                <div class="adm-field adm-field-check">
                    <label><input type="checkbox" name="enabled_langs[]" value="<?= htmlspecialchars($code) ?>" <?= in_array($code, $enabled, true) ? 'checked' : '' ?>> <?= htmlspecialchars(($meta['flag'] ?? '') . ' ' . ($meta['name'] ?? strtoupper($code))) ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>