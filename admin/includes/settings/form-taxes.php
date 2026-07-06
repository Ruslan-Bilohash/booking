<?php /** @var array $settings @var array $ta */
$tax = $settings['taxes'] ?? [];
$labels = is_array($tax['labels'] ?? null) ? $tax['labels'] : [];
?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head">
            <h2><i class="fas fa-percent" style="color:var(--adm-accent);margin-right:8px"></i> <?= htmlspecialchars(bk_settings_admin_label('taxes_section', $ta)) ?></h2>
        </div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('taxes_help', $ta)) ?></p>
            <?php bk_render_admin_steps($ta, 'tax_steps'); ?>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field adm-field-check adm-field-full">
                    <label>
                        <input type="checkbox" name="tax_enabled" value="1" <?= !empty($tax['enabled']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars(bk_settings_admin_label('tax_enabled', $ta)) ?>
                    </label>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('tax_mode', $ta)) ?></label>
                    <select name="tax_mode">
                        <option value="excluded" <?= ($tax['mode'] ?? 'excluded') === 'excluded' ? 'selected' : '' ?>><?= htmlspecialchars(bk_settings_admin_label('tax_mode_excluded', $ta)) ?></option>
                        <option value="included" <?= ($tax['mode'] ?? '') === 'included' ? 'selected' : '' ?>><?= htmlspecialchars(bk_settings_admin_label('tax_mode_included', $ta)) ?></option>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('tax_rate', $ta)) ?></label>
                    <input type="number" name="tax_rate" value="<?= htmlspecialchars((string) ($tax['rate'] ?? '12')) ?>" min="0" max="100" step="0.01">
                </div>
                <div class="adm-field adm-field-check adm-field-full">
                    <label>
                        <input type="checkbox" name="tax_show_breakdown" value="1" <?= !empty($tax['show_breakdown']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars(bk_settings_admin_label('tax_show_breakdown', $ta)) ?>
                    </label>
                </div>
                <?php foreach (['en' => 'tax_label_en', 'no' => 'tax_label_no', 'uk' => 'tax_label_uk', 'ru' => 'tax_label_ru', 'sv' => 'tax_label_sv'] as $code => $fieldKey): ?>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label($fieldKey, $ta)) ?></label>
                    <input type="text" name="tax_label_<?= $code ?>" value="<?= htmlspecialchars($labels[$code] ?? '') ?>">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button>
    </div>
</form>