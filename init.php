<?php
require_once __DIR__ . '/includes/database.php';
bk_install_redirect_if_needed();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/i18n.php';
require_once __DIR__ . '/includes/seo.php';
require_once __DIR__ . '/includes/vertical-lib.php';
require_once __DIR__ . '/includes/site-integrations.php';
require_once __DIR__ . '/includes/advanced-settings.php';
bk_maybe_maintenance();