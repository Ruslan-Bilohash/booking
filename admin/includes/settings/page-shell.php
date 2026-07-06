<?php
require_once dirname(__DIR__, 3) . '/includes/site-settings.php';

$settings = is_callable($bk_load_settings) ? $bk_load_settings() : call_user_func($bk_load_settings);
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = bk_settings_apply_post($settings_tab, $_POST, $settings);
    $saved = is_callable($bk_save_settings) ? $bk_save_settings($settings) : call_user_func($bk_save_settings, $settings);
    $flash = $saved ? 'success' : 'error';
    $settings = is_callable($bk_load_settings) ? $bk_load_settings() : call_user_func($bk_load_settings);
}

$adminUrlFn = $bk_admin_url;