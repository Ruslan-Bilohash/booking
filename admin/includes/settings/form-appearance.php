<?php /** @var array $settings @var array $ta */
$faviconPreset = $settings['favicon_preset'] ?? 'default';
if (!array_key_exists($faviconPreset, bk_favicon_presets())) {
    $faviconPreset = 'default';
}
$faviconPreview = bk_favicon_href($settings);
?>
<form method="post" class="adm-settings-form" id="admAppearanceForm">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('settings_appearance', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('appearance_help', $ta)) ?></p>
            <div class="adm-form-grid adm-color-grid">
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('color_primary', $ta)) ?></label>
                    <input type="color" name="color_primary" id="admColorPrimary" value="<?= htmlspecialchars(bk_hex_color($settings['color_primary'] ?? '#003580')) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('color_button', $ta)) ?></label>
                    <input type="color" name="color_button" value="<?= htmlspecialchars(bk_hex_color($settings['color_button'] ?? ($settings['color_primary'] ?? '#003580'))) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('color_button_hover', $ta)) ?></label>
                    <input type="color" name="color_button_hover" value="<?= htmlspecialchars(bk_hex_color($settings['color_button_hover'] ?? '#00224f')) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('color_footer', $ta)) ?></label>
                    <input type="color" name="color_footer" value="<?= htmlspecialchars(bk_hex_color($settings['color_footer'] ?? '#00224f')) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('bg_color', $ta)) ?></label>
                    <input type="color" name="bg_color" value="<?= htmlspecialchars($settings['bg_color'] !== '' ? bk_hex_color($settings['bg_color'], '#f5f5f5') : '#f5f5f5') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars(bk_settings_admin_label('bg_image', $ta)) ?></label>
                    <input type="url" name="bg_image" value="<?= htmlspecialchars($settings['bg_image'] ?? '') ?>" placeholder="https://...">
                </div>
            </div>

            <div class="adm-favicon-block">
                <h3 class="adm-favicon-title"><?= htmlspecialchars(bk_settings_admin_label('favicon_section', $ta)) ?></h3>
                <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('favicon_help', $ta)) ?></p>
                <p class="adm-field-label"><?= htmlspecialchars(bk_settings_admin_label('favicon_preset', $ta)) ?></p>
                <div class="adm-favicon-picker" role="radiogroup" aria-label="<?= htmlspecialchars(bk_settings_admin_label('favicon_preset', $ta)) ?>">
                    <?php foreach (bk_favicon_presets() as $key => $preset):
                        $letter = $key === 'letter'
                            ? strtoupper(substr(trim((string) ($settings['favicon_letter'] ?? 'B')), 0, 1) ?: 'B')
                            : (string) ($preset['letter'] ?? 'B');
                        $previewSettings = array_merge($settings, ['favicon_preset' => $key, 'favicon_letter' => $letter, 'favicon_url' => '']);
                        $previewUri = bk_favicon_data_uri($previewSettings);
                    ?>
                    <label class="adm-favicon-option">
                        <input type="radio" name="favicon_preset" value="<?= htmlspecialchars($key) ?>" <?= $faviconPreset === $key ? 'checked' : '' ?>>
                        <span class="adm-favicon-option-card">
                            <img src="<?= htmlspecialchars($previewUri) ?>" alt="" width="40" height="40" class="adm-favicon-thumb">
                            <span><?= htmlspecialchars(bk_settings_admin_label($preset['label'], $ta)) ?></span>
                        </span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <div class="adm-field adm-favicon-letter-field<?= $faviconPreset === 'letter' ? '' : ' is-hidden' ?>" id="admFaviconLetterField">
                    <label for="admFaviconLetter"><?= htmlspecialchars(bk_settings_admin_label('favicon_letter', $ta)) ?></label>
                    <input type="text" name="favicon_letter" id="admFaviconLetter" maxlength="1" value="<?= htmlspecialchars($settings['favicon_letter'] ?? 'B') ?>" class="adm-favicon-letter-input" autocapitalize="characters" autocomplete="off">
                </div>
                <div class="adm-field adm-field-full">
                    <label for="admFaviconUrl"><?= htmlspecialchars(bk_settings_admin_label('favicon_url', $ta)) ?></label>
                    <input type="url" name="favicon_url" id="admFaviconUrl" value="<?= htmlspecialchars($settings['favicon_url'] ?? '') ?>" placeholder="https://yourdomain.com/favicon.png">
                    <span class="adm-field-hint"><?= htmlspecialchars(bk_settings_admin_label('favicon_url_help', $ta)) ?></span>
                </div>
                <div class="adm-favicon-preview-box">
                    <span class="adm-favicon-preview-label"><?= htmlspecialchars(bk_settings_admin_label('favicon_preview', $ta)) ?></span>
                    <img src="<?= htmlspecialchars($faviconPreview) ?>" alt="" width="48" height="48" id="admFaviconPreview" class="adm-favicon-preview-img">
                </div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button>
    </div>
</form>
<script>
(function () {
    var form = document.getElementById('admAppearanceForm');
    if (!form) return;

    var colorInput = document.getElementById('admColorPrimary');
    var letterField = document.getElementById('admFaviconLetterField');
    var letterInput = document.getElementById('admFaviconLetter');
    var urlInput = document.getElementById('admFaviconUrl');
    var preview = document.getElementById('admFaviconPreview');
    var presetLetters = { default: 'B', hotel: 'H', calendar: 'C', key: 'K', plane: 'P' };

    function currentPreset() {
        var checked = form.querySelector('input[name="favicon_preset"]:checked');
        return checked ? checked.value : 'default';
    }

    function svgDataUri(bg, letter) {
        var svg = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>"
            + "<rect fill='" + bg + "' width='100' height='100' rx='12'/>"
            + "<text x='50' y='62' font-size='44' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='bold'>" + letter + "</text>"
            + "</svg>";
        return 'data:image/svg+xml,' + encodeURIComponent(svg);
    }

    function currentLetter() {
        var preset = currentPreset();
        if (preset === 'letter') {
            var l = (letterInput && letterInput.value ? letterInput.value : 'B').trim().toUpperCase().slice(0, 1);
            return l || 'B';
        }
        return presetLetters[preset] || 'B';
    }

    function toggleLetterField() {
        if (!letterField) return;
        letterField.classList.toggle('is-hidden', currentPreset() !== 'letter');
    }

    function updatePreview() {
        if (!preview) return;
        var url = urlInput && urlInput.value.trim();
        if (url) {
            preview.src = url;
            return;
        }
        var bg = colorInput ? colorInput.value : '#003580';
        preview.src = svgDataUri(bg, currentLetter());
    }

    form.querySelectorAll('input[name="favicon_preset"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            toggleLetterField();
            updatePreview();
        });
    });
    if (letterInput) letterInput.addEventListener('input', updatePreview);
    if (colorInput) colorInput.addEventListener('input', updatePreview);
    if (urlInput) urlInput.addEventListener('input', updatePreview);

    toggleLetterField();
})();
</script>