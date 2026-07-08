<?php
/** @var array $settings @var array $ta */
require_once dirname(__DIR__, 3) . '/includes/ai-settings.php';
$providers = bk_ai_providers();
$provider = $settings['ai_provider'] ?? 'grok';
?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('ai_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <label class="adm-toggle"><input type="checkbox" name="ai_enabled" value="1" <?= !empty($settings['ai_enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('ai_enabled', $ta)) ?></span></label>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('ai_provider', $ta)) ?></label>
                    <select name="ai_provider"><?php foreach ($providers as $key => $p): ?><option value="<?= htmlspecialchars($key) ?>" <?= $provider === $key ? 'selected' : '' ?>><?= htmlspecialchars($p['label']) ?></option><?php endforeach; ?></select>
                </div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('ai_model', $ta)) ?></label>
                    <select name="ai_model_select"><?php foreach ($providers[$provider]['models'] ?? [] as $m): ?><option value="<?= htmlspecialchars($m) ?>" <?= ($settings['ai_model'] ?? '') === $m ? 'selected' : '' ?>><?= htmlspecialchars($m) ?></option><?php endforeach; ?></select>
                </div>
                <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('ai_api_key', $ta)) ?></label><input type="password" name="ai_api_key" value="" placeholder="••••••••" autocomplete="new-password"></div>
                <div class="adm-field adm-field-full"><label><?= htmlspecialchars(bk_settings_admin_label('ai_prompt_seo', $ta)) ?></label><textarea name="ai_prompt_seo" rows="3"><?= htmlspecialchars($settings['ai_prompt_seo'] ?? '') ?></textarea></div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky"><button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button></div>
</form>