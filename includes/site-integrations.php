<?php

require_once __DIR__ . '/site-settings.php';
require_once __DIR__ . '/storage.php';

function bk_site_settings(): array
{
    static $s = null;
    if ($s === null) {
        $s = bk_load_settings();
        $GLOBALS['bk_site_settings'] = $s;
        $GLOBALS['bh_cms_site_settings'] = $s;
        bk_bind_recaptcha_settings($s);
    }
    return $s;
}

function bk_boot_public_integrations(): void
{
    bk_site_settings();
}