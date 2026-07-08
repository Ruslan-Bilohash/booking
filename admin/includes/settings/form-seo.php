<?php /** @var array $settings @var array $ta */ ?>
<form method="post" class="adm-settings-form">
    <div class="adm-card">
        <div class="adm-card-head"><h2><?= htmlspecialchars(bk_settings_admin_label('seo_section', $ta)) ?></h2></div>
        <div class="adm-card-body padded">
            <p class="adm-help"><?= htmlspecialchars(bk_settings_admin_label('seo_help', $ta)) ?></p>
            <?php bk_render_admin_steps($ta, 'seo_steps'); ?>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_site_name', $ta)) ?></label>
                    <input type="text" name="seo_site_name" value="<?= htmlspecialchars($settings['seo_site_name'] ?? '') ?>" placeholder="Booking CMS">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_org_name', $ta)) ?></label>
                    <input type="text" name="seo_org_name" value="<?= htmlspecialchars($settings['seo_org_name'] ?? '') ?>" placeholder="Booking CMS">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_default_og_image', $ta)) ?></label>
                    <input type="url" name="seo_default_og_image" value="<?= htmlspecialchars($settings['seo_default_og_image'] ?? '') ?>" placeholder="https://yourdomain.com/og-image.jpg" inputmode="url" autocomplete="url">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_geo_region', $ta)) ?></label>
                    <input type="text" name="seo_geo_region" value="<?= htmlspecialchars($settings['seo_geo_region'] ?? 'NO') ?>" maxlength="8" autocapitalize="characters">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_geo_placename', $ta)) ?></label>
                    <input type="text" name="seo_geo_placename" value="<?= htmlspecialchars($settings['seo_geo_placename'] ?? 'Norway') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_twitter_site', $ta)) ?></label>
                    <input type="text" name="seo_twitter_site" value="<?= htmlspecialchars($settings['seo_twitter_site'] ?? '') ?>" placeholder="@yourbrand">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars(bk_settings_admin_label('seo_default_country_code', $ta)) ?></label>
                    <input type="text" name="seo_default_country_code" value="<?= htmlspecialchars($settings['seo_default_country_code'] ?? 'NO') ?>" maxlength="2" class="adm-input-upper" autocapitalize="characters">
                </div>
            </div>
            <p class="adm-help adm-seo-note"><?= htmlspecialchars(bk_settings_admin_label('seo_property_note', $ta)) ?></p>
            <div class="adm-form-grid adm-form-grid--checks">
                <div class="adm-field adm-field-check adm-field-full">
                    <label>
                        <input type="checkbox" name="seo_schema_lodging" value="1" <?= !empty($settings['seo_schema_lodging']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars(bk_settings_admin_label('seo_schema_lodging', $ta)) ?>
                    </label>
                </div>
                <div class="adm-field adm-field-check adm-field-full">
                    <label>
                        <input type="checkbox" name="seo_schema_product" value="1" <?= !empty($settings['seo_schema_product']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars(bk_settings_admin_label('seo_schema_product', $ta)) ?>
                    </label>
                </div>
                <div class="adm-field adm-field-check adm-field-full">
                    <label>
                        <input type="checkbox" name="seo_schema_breadcrumbs" value="1" <?= !empty($settings['seo_schema_breadcrumbs']) ? 'checked' : '' ?>>
                        <?= htmlspecialchars(bk_settings_admin_label('seo_schema_breadcrumbs', $ta)) ?>
                    </label>
                </div>
                <div class="adm-field adm-field-check adm-field-full">
                    <label><input type="checkbox" name="seo_schema_website" value="1" <?= !empty($settings['seo_schema_website']) ? 'checked' : '' ?>> <?= htmlspecialchars(bk_settings_admin_label('seo_schema_website', $ta)) ?></label>
                </div>
                <div class="adm-field adm-field-check adm-field-full">
                    <label><input type="checkbox" name="seo_schema_organization" value="1" <?= !empty($settings['seo_schema_organization']) ? 'checked' : '' ?>> <?= htmlspecialchars(bk_settings_admin_label('seo_schema_organization', $ta)) ?></label>
                </div>
            </div>
            <h3 class="adm-subhead"><?= htmlspecialchars(bk_settings_admin_label('sitemap_section', $ta)) ?></h3>
            <label class="adm-toggle"><input type="checkbox" name="sitemap_enabled" value="1" <?= !empty($settings['sitemap_enabled']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('sitemap_enabled', $ta)) ?></span></label>
            <label class="adm-toggle"><input type="checkbox" name="sitemap_include_properties" value="1" <?= !empty($settings['sitemap_include_properties']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('sitemap_include_properties', $ta)) ?></span></label>
            <label class="adm-toggle"><input type="checkbox" name="sitemap_include_verticals" value="1" <?= !empty($settings['sitemap_include_verticals']) ? 'checked' : '' ?>><span><?= htmlspecialchars(bk_settings_admin_label('sitemap_include_verticals', $ta)) ?></span></label>
            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('sitemap_priority_home', $ta)) ?></label><input type="text" name="sitemap_priority_home" value="<?= htmlspecialchars($settings['sitemap_priority_home'] ?? '1.0') ?>"></div>
                <div class="adm-field"><label><?= htmlspecialchars(bk_settings_admin_label('sitemap_priority_property', $ta)) ?></label><input type="text" name="sitemap_priority_property" value="<?= htmlspecialchars($settings['sitemap_priority_property'] ?? '0.8') ?>"></div>
            </div>
        </div>
    </div>
    <div class="adm-form-actions adm-form-actions-sticky">
        <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars(bk_settings_admin_label('save', $ta)) ?></button>
    </div>
</form>