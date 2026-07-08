<?php
/**
 * Booking CMS — local site settings (chat, reCAPTCHA, colours, taxes, payments).
 * All data stored in booking/data/settings.json
 */

function bk_seo_settings_defaults(): array
{
    return [
        'seo_site_name'             => '',
        'seo_default_og_image'      => '',
        'seo_org_name'              => '',
        'seo_geo_region'            => 'NO',
        'seo_geo_placename'         => 'Norway',
        'seo_twitter_site'          => '',
        'seo_default_country_code'  => 'NO',
        'seo_schema_lodging'        => true,
        'seo_schema_product'        => true,
        'seo_schema_breadcrumbs'    => true,
        'seo_schema_website'        => true,
        'seo_schema_organization'   => true,
        'sitemap_enabled'           => true,
        'sitemap_include_properties'=> true,
        'sitemap_include_verticals' => true,
        'sitemap_priority_home'     => '1.0',
        'sitemap_priority_property' => '0.8',
        'sitemap_last_generated'    => '',
    ];
}

function bk_settings_defaults(): array
{
    require_once __DIR__ . '/payment-settings.php';
    require_once __DIR__ . '/analytics-settings.php';
    require_once __DIR__ . '/smtp-settings.php';
    require_once __DIR__ . '/telegram-notify.php';
    require_once __DIR__ . '/ai-settings.php';
    require_once __DIR__ . '/advanced-settings.php';
    require_once __DIR__ . '/ecosystem-load.php';
    bk_require_ecosystem('bh-cms-site-settings.php');
    return array_merge(
        bh_cms_site_settings_defaults(bh_cms_product_accent('booking')),
        bk_seo_settings_defaults(),
        bk_analytics_defaults(),
        bk_smtp_defaults(),
        bk_telegram_defaults(),
        bk_ai_defaults(),
        bk_advanced_defaults(),
        [
            'color_footer'         => '#00224f',
            'favicon_preset'       => 'default',
            'favicon_letter'       => 'B',
            'favicon_url'          => '',
            'taxes' => bk_default_tax_settings(),
            'paypal' => bk_default_payment_providers()['paypal'],
            'stripe' => bk_default_payment_providers()['stripe'],
            'vipps' => bk_default_payment_providers()['vipps'],
        ]
    );
}

function bk_merge_settings(array $settings): array
{
    require_once __DIR__ . '/payment-settings.php';
    require_once __DIR__ . '/analytics-settings.php';
    require_once __DIR__ . '/smtp-settings.php';
    require_once __DIR__ . '/telegram-notify.php';
    require_once __DIR__ . '/ai-settings.php';
    require_once __DIR__ . '/advanced-settings.php';
    $defaults = bk_settings_defaults();
    $merged = array_merge($defaults, $settings);
    foreach (['taxes', 'paypal', 'stripe', 'vipps'] as $key) {
        if (!isset($defaults[$key]) || !is_array($defaults[$key])) {
            continue;
        }
        $merged[$key] = array_merge(
            $defaults[$key],
            is_array($merged[$key] ?? null) ? $merged[$key] : []
        );
    }
    return bk_advanced_merge(bk_ai_merge(bk_telegram_merge(bk_smtp_merge(bk_analytics_merge($merged)))));
}

function bk_settings_apply_post(string $section, array $post, array $settings): array
{
    switch ($section) {
        case 'appearance':
            $settings['color_primary'] = trim($post['color_primary'] ?? ($settings['color_primary'] ?? '#003580'));
            $settings['color_button'] = trim($post['color_button'] ?? $settings['color_primary']);
            $settings['color_button_hover'] = trim($post['color_button_hover'] ?? $settings['color_button']);
            $settings['color_footer'] = trim($post['color_footer'] ?? ($settings['color_footer'] ?? '#00224f'));
            $settings['bg_color'] = trim($post['bg_color'] ?? '');
            $settings['bg_image'] = trim($post['bg_image'] ?? '');
            $preset = trim($post['favicon_preset'] ?? 'default');
            $settings['favicon_preset'] = array_key_exists($preset, bk_favicon_presets()) ? $preset : 'default';
            $letter = strtoupper(substr(trim($post['favicon_letter'] ?? 'B'), 0, 1));
            $settings['favicon_letter'] = $letter !== '' ? $letter : 'B';
            $settings['favicon_url'] = trim($post['favicon_url'] ?? '');
            break;
        case 'recaptcha':
            $settings['recaptcha_enabled'] = !empty($post['recaptcha_enabled']);
            $settings['recaptcha_site_key'] = trim($post['recaptcha_site_key'] ?? '');
            $settings['recaptcha_secret_key'] = trim($post['recaptcha_secret_key'] ?? '');
            break;
        case 'chat':
            $settings['chat_enabled'] = !empty($post['chat_enabled']);
            $provider = $post['chat_provider'] ?? 'none';
            $settings['chat_provider'] = in_array($provider, ['none', 'grok', 'gpt'], true) ? $provider : 'none';
            $settings['chat_api_key'] = trim($post['chat_api_key'] ?? '');
            $settings['chat_instructions'] = trim($post['chat_instructions'] ?? '');
            break;
        case 'taxes':
            require_once __DIR__ . '/payment-settings.php';
            $settings = bk_taxes_apply_post($post, $settings);
            break;
        case 'seo':
            $settings['seo_site_name'] = trim($post['seo_site_name'] ?? '');
            $settings['seo_default_og_image'] = trim($post['seo_default_og_image'] ?? '');
            $settings['seo_org_name'] = trim($post['seo_org_name'] ?? '');
            $settings['seo_geo_region'] = strtoupper(substr(trim($post['seo_geo_region'] ?? 'NO'), 0, 8));
            $settings['seo_geo_placename'] = trim($post['seo_geo_placename'] ?? '');
            $settings['seo_twitter_site'] = trim($post['seo_twitter_site'] ?? '');
            $settings['seo_schema_lodging'] = !empty($post['seo_schema_lodging']);
            $settings['seo_schema_product'] = !empty($post['seo_schema_product']);
            $settings['seo_schema_breadcrumbs'] = !empty($post['seo_schema_breadcrumbs']);
            $settings['seo_schema_website'] = !empty($post['seo_schema_website']);
            $settings['seo_schema_organization'] = !empty($post['seo_schema_organization']);
            $settings['sitemap_enabled'] = !empty($post['sitemap_enabled']);
            $settings['sitemap_include_properties'] = !empty($post['sitemap_include_properties']);
            $settings['sitemap_include_verticals'] = !empty($post['sitemap_include_verticals']);
            $settings['sitemap_priority_home'] = bk_sitemap_priority($post['sitemap_priority_home'] ?? '1.0', '1.0');
            $settings['sitemap_priority_property'] = bk_sitemap_priority($post['sitemap_priority_property'] ?? '0.8', '0.8');
            $cc = strtoupper(trim($post['seo_default_country_code'] ?? 'NO'));
            $settings['seo_default_country_code'] = preg_match('/^[A-Z]{2}$/', $cc) ? $cc : 'NO';
            break;
        case 'analytics':
            require_once __DIR__ . '/analytics-settings.php';
            $settings = bk_analytics_apply_post($post, $settings);
            break;
        case 'smtp':
            require_once __DIR__ . '/smtp-settings.php';
            $settings = bk_smtp_apply_post($post, $settings);
            break;
        case 'telegram':
            require_once __DIR__ . '/telegram-notify.php';
            $settings = bk_telegram_apply_post($post, $settings);
            break;
        case 'ai':
            require_once __DIR__ . '/ai-settings.php';
            $settings = bk_ai_apply_post($post, $settings);
            break;
        case 'advanced':
            require_once __DIR__ . '/advanced-settings.php';
            $settings = bk_advanced_apply_post($post, $settings);
            break;
        case 'languages':
            require_once __DIR__ . '/advanced-settings.php';
            $settings = bk_advanced_apply_post($post, $settings);
            break;
    }
    return $settings;
}

function bk_sitemap_priority(string $value, string $fallback): string
{
    $v = (float) str_replace(',', '.', trim($value));
    if ($v < 0.0 || $v > 1.0) {
        return $fallback;
    }
    return number_format($v, 1, '.', '');
}

function bk_settings_tabs(): array
{
    return [
        'appearance' => ['file' => 'settings-appearance.php', 'icon' => 'palette', 'group' => 'general'],
        'languages'  => ['file' => 'settings-languages.php',  'icon' => 'language', 'group' => 'general'],
        'taxes'      => ['file' => 'settings-taxes.php',      'icon' => 'percent', 'group' => 'commerce'],
        'payments'   => ['file' => 'settings-payments.php',   'icon' => 'credit-card', 'group' => 'commerce'],
        'recaptcha'  => ['file' => 'settings-recaptcha.php',  'icon' => 'shield-alt', 'group' => 'security'],
        'chat'       => ['file' => 'settings-chat.php',       'icon' => 'robot', 'group' => 'integrations'],
        'ai'         => ['file' => 'settings-ai.php',         'icon' => 'brain', 'group' => 'integrations'],
        'smtp'       => ['file' => 'settings-smtp.php',       'icon' => 'envelope', 'group' => 'integrations'],
        'telegram'   => ['file' => 'settings-telegram.php',   'icon' => 'paper-plane', 'group' => 'integrations'],
        'seo'        => ['file' => 'settings-seo.php',        'icon' => 'chart-line', 'group' => 'marketing'],
        'analytics'  => ['file' => 'settings-analytics.php',  'icon' => 'chart-pie', 'group' => 'marketing'],
        'advanced'   => ['file' => 'settings-advanced.php',   'icon' => 'sliders-h', 'group' => 'advanced'],
    ];
}

function bk_settings_tab_groups(): array
{
    return [
        'general'       => ['label' => 'settings_group_general', 'tabs' => ['appearance', 'languages']],
        'commerce'      => ['label' => 'settings_group_commerce', 'tabs' => ['taxes', 'payments']],
        'security'      => ['label' => 'settings_group_security', 'tabs' => ['recaptcha']],
        'integrations'  => ['label' => 'settings_group_integrations', 'tabs' => ['chat', 'ai', 'smtp', 'telegram']],
        'marketing'     => ['label' => 'settings_group_seo', 'tabs' => ['seo', 'analytics']],
        'advanced'      => ['label' => 'settings_group_advanced', 'tabs' => ['advanced']],
    ];
}

/** Sidebar / jump menu groups */
function bk_settings_nav_groups(): array
{
    return [
        [
            'label' => 'settings_group_general',
            'items' => [
                ['type' => 'tab', 'key' => 'appearance'],
            ],
        ],
        [
            'label' => 'settings_group_commerce',
            'items' => [
                ['type' => 'tab', 'key' => 'taxes'],
                ['type' => 'payment', 'key' => 'paypal'],
                ['type' => 'payment', 'key' => 'stripe'],
                ['type' => 'payment', 'key' => 'vipps'],
            ],
        ],
        [
            'label' => 'settings_group_security',
            'items' => [
                ['type' => 'tab', 'key' => 'recaptcha'],
            ],
        ],
        [
            'label' => 'settings_group_integrations',
            'items' => [
                ['type' => 'tab', 'key' => 'chat'],
                ['type' => 'tab', 'key' => 'ai'],
                ['type' => 'tab', 'key' => 'smtp'],
                ['type' => 'tab', 'key' => 'telegram'],
            ],
        ],
        [
            'label' => 'settings_group_seo',
            'items' => [
                ['type' => 'tab', 'key' => 'seo'],
                ['type' => 'tab', 'key' => 'analytics'],
            ],
        ],
        [
            'label' => 'settings_group_advanced',
            'items' => [
                ['type' => 'tab', 'key' => 'languages'],
                ['type' => 'tab', 'key' => 'advanced'],
            ],
        ],
    ];
}

function bk_settings_nav_item_url(string $type, string $key, callable $adminUrlFn): string
{
    if ($type === 'payment') {
        return $adminUrlFn('settings-payments.php?tab=' . urlencode($key));
    }
    $tabs = bk_settings_tabs();
    $file = $tabs[$key]['file'] ?? 'settings-appearance.php';
    return $adminUrlFn($file);
}

function bk_settings_nav_item_active(string $type, string $key): bool
{
    global $settings_tab, $payment_tab;
    if ($type === 'payment') {
        return ($settings_tab ?? '') === 'payments' && ($payment_tab ?? 'paypal') === $key;
    }
    return ($settings_tab ?? '') === $key;
}

function bk_settings_nav_item_label(string $type, string $key, array $ta = []): string
{
    if ($type === 'payment') {
        $tp = $ta['payments_page']['tabs'] ?? [];
        return (string) ($tp[$key] ?? ucfirst($key));
    }
    return bk_settings_admin_label('settings_tab_' . $key, $ta);
}

function bk_settings_nav_item_desc(string $type, string $key, array $ta = []): string
{
    if ($type === 'payment') {
        return (string) ($ta['nav_desc_payment_' . $key] ?? '');
    }
    return (string) ($ta['nav_desc_settings_' . $key] ?? '');
}

function bk_admin_nav_desc(string $admin_page, array $ta): string
{
    if ($admin_page === 'settings') {
        global $settings_tab, $payment_tab;
        $tab = $settings_tab ?? '';
        if ($tab === 'payments') {
            $pay = $payment_tab ?? 'paypal';
            return (string) ($ta['nav_desc_payment_' . $pay] ?? $ta['nav_desc_settings'] ?? '');
        }
        if ($tab !== '') {
            return (string) ($ta['nav_desc_settings_' . $tab] ?? $ta['nav_desc_settings'] ?? '');
        }
        return (string) ($ta['nav_desc_settings'] ?? '');
    }
    $map = [
        'dashboard'  => 'nav_desc_dashboard',
        'properties' => 'nav_desc_properties',
        'bookings'   => 'nav_desc_bookings',
        'reviews'    => 'nav_desc_reviews',
    ];
    $key = $map[$admin_page] ?? '';
    return $key !== '' ? (string) ($ta[$key] ?? '') : '';
}

function bk_settings_nav_item_icon(string $type, string $key): string
{
    if ($type === 'payment') {
        require_once __DIR__ . '/payment-settings.php';
        $tabs = bk_payment_tabs();
        return (string) ($tabs[$key]['icon'] ?? 'credit-card');
    }
    $tabs = bk_settings_tabs();
    return (string) ($tabs[$key]['icon'] ?? 'cog');
}

function bk_settings_tab_active(string $tab): bool
{
    global $settings_tab;
    return ($settings_tab ?? '') === $tab;
}

function bk_render_admin_steps(array $ta, string $key): void
{
    $steps = $ta[$key] ?? null;
    if (!is_array($steps) || $steps === []) {
        return;
    }
    echo '<div class="adm-instructions"><h3 class="adm-instructions-title"><i class="fas fa-list-ol" aria-hidden="true"></i> '
        . htmlspecialchars(bk_settings_admin_label('instructions_title', $ta))
        . '</h3><ol class="adm-instructions-list">';
    foreach ($steps as $step) {
        echo '<li>' . htmlspecialchars((string) $step) . '</li>';
    }
    echo '</ol></div>';
}

function bk_settings_admin_label(string $key, array $ta = []): string
{
    $fallbacks = [
        'settings'               => 'Settings',
        'settings_saved'         => 'Settings saved.',
        'error'                  => 'Could not save settings.',
        'save'                   => 'Save',
        'settings_tab_appearance'=> 'Appearance',
        'settings_tab_taxes'     => 'Taxes',
        'settings_tab_payments'  => 'Payments',
        'settings_tab_recaptcha' => 'reCAPTCHA',
        'settings_tab_chat'      => 'AI Chat',
        'settings_tab_seo'       => 'SEO & Schema',
        'settings_tab_analytics' => 'Analytics',
        'settings_tab_smtp'      => 'SMTP & Email',
        'settings_tab_telegram'  => 'Telegram',
        'settings_tab_ai'        => 'AI Settings',
        'settings_tab_advanced'  => 'Advanced',
        'settings_tab_languages' => 'Languages',
        'settings_group_general'      => 'General',
        'settings_group_advanced'     => 'Advanced',
        'analytics_section'    => 'Tracking & pixels',
        'analytics_help'       => 'Google Analytics, Meta Pixel and Google Ads conversion tracking.',
        'tracking_gtag_id'     => 'Google Analytics (gtag.js) ID',
        'tracking_meta_pixel'  => 'Meta Pixel ID',
        'tracking_tiktok_pixel'=> 'TikTok Pixel ID',
        'google_ads_section'   => 'Google Ads',
        'google_ads_enabled'   => 'Enable Google Ads tracking',
        'google_ads_id'        => 'Google Ads ID (AW-…)',
        'google_ads_conversion_label' => 'Conversion label',
        'smtp_section'         => 'Outbound email (SMTP)',
        'smtp_enabled'         => 'Enable SMTP',
        'smtp_host'            => 'SMTP host',
        'smtp_port'            => 'Port',
        'smtp_encryption'      => 'Encryption',
        'smtp_username'        => 'Username',
        'smtp_password'        => 'Password (leave blank to keep)',
        'smtp_from_email'      => 'From email',
        'smtp_from_name'       => 'From name',
        'booking_notify_email' => 'Booking notification email',
        'telegram_section'     => 'Telegram notifications',
        'telegram_enabled'     => 'Enable Telegram bot',
        'telegram_notify_bookings' => 'Notify on new bookings',
        'telegram_bot_token'   => 'Bot token (leave blank to keep)',
        'telegram_chat_id'     => 'Chat ID',
        'ai_section'           => 'AI automation',
        'ai_enabled'           => 'Enable AI features',
        'ai_provider'          => 'Provider',
        'ai_model'             => 'Default model',
        'ai_api_key'           => 'API key (leave blank to keep)',
        'ai_prompt_seo'        => 'SEO generation prompt',
        'advanced_section'     => 'Maintenance & custom code',
        'maintenance_mode'     => 'Maintenance mode (public site only)',
        'maintenance_message'  => 'Maintenance message',
        'cookie_consent'       => 'Show cookie consent banner',
        'custom_head_code'     => 'Custom &lt;head&gt; code',
        'custom_footer_code'   => 'Custom footer code',
        'languages_section'    => 'Enabled languages',
        'languages_help'       => 'Choose which languages appear on the public booking site.',
        'sitemap_section'      => 'XML Sitemap',
        'sitemap_enabled'      => 'Enable sitemap.xml',
        'sitemap_include_properties' => 'Include property pages',
        'sitemap_include_verticals'  => 'Include vertical landing pages',
        'sitemap_priority_home'      => 'Homepage priority',
        'sitemap_priority_property'=> 'Property priority',
        'seo_schema_website'       => 'WebSite schema on homepage',
        'seo_schema_organization'  => 'Organization schema',
        'settings_group_commerce'     => 'Taxes & payments',
        'settings_group_security'     => 'Security',
        'settings_group_integrations' => 'Integrations',
        'settings_group_seo'          => 'SEO & marketing',
        'settings_jump_label'         => 'Settings section',
        'settings_nav_aria'           => 'Settings categories',
        'taxes_section'          => 'Taxes & fees',
        'taxes_help'             => 'Configure VAT/MVA rate and how it appears on property and checkout pages.',
        'tax_enabled'            => 'Show taxes on booking totals',
        'tax_mode'               => 'Tax calculation',
        'tax_mode_excluded'      => 'Added on top of room price',
        'tax_mode_included'      => 'Included in room price',
        'tax_rate'               => 'Tax rate (%)',
        'tax_show_breakdown'     => 'Show subtotal + tax lines on checkout',
        'tax_label_en'           => 'Tax label (EN)',
        'tax_label_no'           => 'Tax label (NO)',
        'tax_label_uk'           => 'Tax label (UA)',
        'tax_label_ru'           => 'Tax label (RU)',
        'tax_label_sv'           => 'Tax label (SV)',
        'tax_steps'              => [
            'Set the tax rate for your country (e.g. 12% MVA for accommodation in Norway, 25% for other services).',
            'Choose whether room prices already include tax or tax is added at checkout.',
            'Enter tax labels per language (VAT, MVA, ПДВ…).',
            'Enable “show breakdown” to display subtotal and tax on book.php.',
            'Save and open a property page — total and tax note should match your settings.',
        ],
        'payments_demo_note'     => 'Demo mode — API keys are stored in booking/data/settings.json. Checkout remains simulated until production gateway integration.',
        'settings_appearance'    => 'Site colours',
        'appearance_help'        => 'Choose the main accent colour for buttons, links and highlights on the public site.',
        'color_primary'          => 'Main site colour',
        'color_button'           => 'Button colour',
        'color_button_hover'     => 'Button hover',
        'color_footer'           => 'Footer background',
        'bg_color'               => 'Background colour (optional)',
        'bg_image'               => 'Background image URL (optional)',
        'favicon_section'        => 'Site favicon',
        'favicon_help'           => 'Browser tab icon for the public booking site. Uses main site colour, or upload a custom .png / .ico / .svg URL.',
        'favicon_preset'         => 'Icon style',
        'favicon_preset_default' => 'Booking (B)',
        'favicon_preset_hotel'   => 'Hotel (H)',
        'favicon_preset_calendar'=> 'Calendar (C)',
        'favicon_preset_key'     => 'Key (K)',
        'favicon_preset_plane'   => 'Travel (P)',
        'favicon_preset_letter'  => 'Custom letter',
        'favicon_letter'         => 'Letter (1 character)',
        'favicon_url'            => 'Custom favicon URL (optional)',
        'favicon_url_help'       => 'If set, overrides the generated icon. Use HTTPS, square image, 32×32 or larger.',
        'favicon_preview'        => 'Preview',
        'recaptcha_section'      => 'reCAPTCHA',
        'recaptcha_help'         => 'Google reCAPTCHA v2 keys for contact and booking forms.',
        'recaptcha_enabled'      => 'Enable reCAPTCHA on public forms',
        'recaptcha_site_key'     => 'Site key',
        'recaptcha_secret_key'   => 'Secret key',
        'chat_section'           => 'AI chat widget',
        'chat_help'              => 'Floating AI assistant on the public site. API key is stored in booking/data/settings.json.',
        'chat_enabled'           => 'Enable chat widget',
        'chat_provider'          => 'Provider',
        'chat_provider_none'     => 'Disabled',
        'chat_provider_grok'     => 'Grok xAI',
        'chat_provider_gpt'      => 'OpenAI GPT',
        'chat_api_key'           => 'API key',
        'chat_api_key_help'      => 'xAI key for Grok, OpenAI key for GPT.',
        'chat_instructions'      => 'System instructions',
        'chat_instructions_help' => 'Context for the assistant: property names, languages, support policy.',
        'instructions_title'     => 'Setup instructions',
        'seo_section'            => 'SEO & structured data',
        'seo_help'               => 'Open Graph, Twitter cards, geo meta and Schema.org toggles for property pages (city, product, lodging).',
        'seo_site_name'          => 'Site name (og:site_name)',
        'seo_default_og_image'   => 'Default Open Graph image URL',
        'seo_org_name'           => 'Organization name in Schema.org',
        'seo_geo_region'         => 'Geo region code (e.g. NO)',
        'seo_geo_placename'      => 'Geo place name',
        'seo_twitter_site'       => 'Twitter @site (optional)',
        'seo_schema_lodging'     => 'Lodging / Hotel schema on property pages',
        'seo_schema_product'     => 'Product + Offer schema on property pages',
        'seo_schema_breadcrumbs'   => 'BreadcrumbList schema',
        'seo_default_country_code' => 'Default ISO country code for addresses',
        'seo_property_note'      => 'City and country per property are edited under Properties — they feed PostalAddress and City markup.',
    ];
    return $ta[$key] ?? $fallbacks[$key] ?? ucfirst(str_replace('_', ' ', $key));
}

function bk_hex_color(string $hex, string $fallback = '#003580'): string
{
    $hex = trim($hex);
    if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $hex)) {
        return $hex;
    }
    return $fallback;
}

function bk_favicon_presets(): array
{
    return [
        'default'  => ['letter' => 'B', 'label' => 'favicon_preset_default'],
        'hotel'    => ['letter' => 'H', 'label' => 'favicon_preset_hotel'],
        'calendar' => ['letter' => 'C', 'label' => 'favicon_preset_calendar'],
        'key'      => ['letter' => 'K', 'label' => 'favicon_preset_key'],
        'plane'    => ['letter' => 'P', 'label' => 'favicon_preset_plane'],
        'letter'   => ['letter' => null, 'label' => 'favicon_preset_letter'],
    ];
}

function bk_favicon_letter(?array $settings = null): string
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return 'B';
    }
    $preset = $settings['favicon_preset'] ?? 'default';
    $presets = bk_favicon_presets();
    if ($preset === 'letter') {
        $letter = strtoupper(substr(trim((string) ($settings['favicon_letter'] ?? 'B')), 0, 1));
        return $letter !== '' ? $letter : 'B';
    }
    return (string) ($presets[$preset]['letter'] ?? 'B');
}

function bk_favicon_svg_markup(?array $settings = null): string
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        $settings = [];
    }
    $bg = bk_hex_color($settings['color_primary'] ?? '', '#003580');
    $letter = htmlspecialchars(bk_favicon_letter($settings), ENT_QUOTES, 'UTF-8');
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'>"
        . "<rect fill='{$bg}' width='100' height='100' rx='12'/>"
        . "<text x='50' y='62' font-size='44' text-anchor='middle' fill='white' font-family='sans-serif' font-weight='bold'>{$letter}</text>"
        . '</svg>';
}

function bk_favicon_data_uri(?array $settings = null): string
{
    return 'data:image/svg+xml,' . rawurlencode(bk_favicon_svg_markup($settings));
}

function bk_favicon_href(?array $settings = null): string
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return bk_favicon_data_uri([]);
    }
    $url = trim((string) ($settings['favicon_url'] ?? ''));
    if ($url !== '' && filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    return bk_favicon_data_uri($settings);
}

function bk_favicon_is_svg(?array $settings = null): bool
{
    $href = bk_favicon_href($settings);
    return str_starts_with($href, 'data:image/svg');
}

function bk_render_favicon_tag(?array $settings = null): void
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    $href = bk_favicon_href($settings);
    if (bk_favicon_is_svg($settings)) {
        echo '<link rel="icon" type="image/svg+xml" href="' . htmlspecialchars($href) . '">';
    } else {
        echo '<link rel="icon" href="' . htmlspecialchars($href) . '">';
    }
    echo '<link rel="apple-touch-icon" href="' . htmlspecialchars($href) . '">';
}

function bk_bind_recaptcha_settings(?array $settings): void
{
    $GLOBALS['bk_recaptcha_settings'] = is_array($settings) ? $settings : null;
    $GLOBALS['bh_cms_recaptcha_settings'] = $GLOBALS['bk_recaptcha_settings'];
}

function bk_recaptcha_site_key(?array $settings = null): string
{
    $settings ??= $GLOBALS['bk_recaptcha_settings'] ?? null;
    if (is_array($settings) && !empty($settings['recaptcha_site_key'])) {
        return (string) $settings['recaptcha_site_key'];
    }
    if (is_array($settings) && !bk_recaptcha_enabled($settings)) {
        return '';
    }
    return function_exists('cms_recaptcha_site_key') ? cms_recaptcha_site_key() : '';
}

function bk_recaptcha_secret_key(?array $settings = null): string
{
    $settings ??= $GLOBALS['bk_recaptcha_settings'] ?? null;
    if (is_array($settings) && !empty($settings['recaptcha_secret_key'])) {
        return (string) $settings['recaptcha_secret_key'];
    }
    if (is_array($settings) && !bk_recaptcha_enabled($settings)) {
        return '';
    }
    return function_exists('cms_recaptcha_secret_key') ? cms_recaptcha_secret_key() : '';
}

function bk_recaptcha_enabled(?array $settings = null): bool
{
    $settings ??= $GLOBALS['bk_recaptcha_settings'] ?? null;
    if (is_array($settings) && array_key_exists('recaptcha_enabled', $settings)) {
        return (bool) $settings['recaptcha_enabled'];
    }
    return true;
}

function bk_verify_recaptcha(?string $response, ?array $settings = null): bool
{
    if (!bk_recaptcha_enabled($settings)) {
        return true;
    }
    $response = trim((string) $response);
    if ($response === '') {
        return false;
    }
    $secret = bk_recaptcha_secret_key($settings);
    if ($secret === '') {
        return false;
    }
    $payload = http_build_query([
        'secret'   => $secret,
        'response' => $response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 12,
        ],
    ]);
    $raw = @file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $ctx);
    if ($raw === false) {
        return false;
    }
    $data = json_decode($raw, true);
    return !empty($data['success']);
}

function bk_chat_enabled(?array $settings = null): bool
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return false;
    }
    return !empty($settings['chat_enabled']) && ($settings['chat_provider'] ?? 'none') !== 'none';
}

function bk_render_chat_widget(?array $settings = null, ?string $lang = null): void
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!bk_chat_enabled($settings)) {
        return;
    }
    $provider = $settings['chat_provider'] ?? 'grok';
    echo '<script>window.FL_CHAT_CONFIG = {provider:' . json_encode($provider)
        . ',instructions:' . json_encode($settings['chat_instructions'] ?? '')
        . ',product:' . json_encode('Booking CMS') . '};</script>';
    $bh_chat_lang = $lang ?? ($GLOBALS['lang'] ?? 'en');
    $bh_chat_variant = 'root';
    $bh_chat_require_consent = false;
    $bh_chat_crm_url = 'https://bilohash.com/ai/crm/';
    $bh_chat_lazy = true;
    include __DIR__ . '/chat-widget.php';
}

function bk_public_style_version(): string
{
    return '44';
}

function bk_critical_css(): string
{
    static $css = null;
    if ($css === null) {
        $path = __DIR__ . '/../assets/css/critical.css';
        $css = is_file($path) ? (string) file_get_contents($path) : '';
    }
    return $css;
}

function bk_font_awesome_href(): string
{
    return 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css';
}

function bk_render_public_stylesheets(): void
{
    $styleHref = bk_asset('css/style.css') . '?v=' . bk_public_style_version();
    $faHref = bk_font_awesome_href();
    $critical = bk_critical_css();
    ?>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://images.unsplash.com" crossorigin>
    <?php if ($critical !== ''): ?>
    <style id="bk-critical"><?= $critical ?></style>
    <?php endif; ?>
    <link rel="preload" href="<?= htmlspecialchars($styleHref) ?>" as="style" fetchpriority="high">
    <link rel="stylesheet" href="<?= htmlspecialchars($styleHref) ?>" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="<?= htmlspecialchars($styleHref) ?>"></noscript>
    <link rel="stylesheet" href="<?= htmlspecialchars($faHref) ?>" crossorigin>
    <?php
}

function bk_render_theme_styles(?array $settings = null): void
{
    $settings ??= $GLOBALS['bk_site_settings'] ?? null;
    if (!is_array($settings)) {
        return;
    }
    $accent = bk_hex_color($settings['color_primary'] ?? '', '#003580');
    $btn = bk_hex_color($settings['color_button'] ?? '', $accent);
    $btnHover = bk_hex_color($settings['color_button_hover'] ?? '', $btn);
    $bg = trim($settings['bg_color'] ?? '');
    $bgImage = trim($settings['bg_image'] ?? '');
    $footer = bk_hex_color($settings['color_footer'] ?? '', '#00224f');
    $css = ":root{--bk-blue:{$accent};--bk-blue-light:{$btn};--bk-yellow-hover:{$btnHover};--bk-blue-dark:{$footer};}";
    if ($bg !== '') {
        $css .= "body{background-color:{$bg};}";
    }
    if ($bgImage !== '') {
        $css .= 'body{background-image:url(' . json_encode($bgImage) . ');background-size:cover;background-attachment:fixed;background-position:center;}';
    }
    echo '<style id="bk-cms-theme">' . $css . '</style>';
}

function bk_admin_settings_css_href(): string
{
    return bk_asset('css/admin-settings.css') . '?v=11';
}

function bk_render_settings_tabs(callable $adminUrlFn, array $ta = []): void
{
    require __DIR__ . '/../admin/includes/settings/settings-tabs.php';
}

function bk_render_settings_form(string $section, array $settings, array $ta = []): void
{
    $path = __DIR__ . '/../admin/includes/settings/form-' . $section . '.php';
    if (is_file($path)) {
        include $path;
    }
}