<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
$admin_page = 'settings';
$settings_tab = 'smtp';
$page_title = bk_settings_admin_label('settings_tab_smtp', $ta);
$bk_load_settings = 'bk_load_settings';
$bk_save_settings = 'bk_save_settings';
$bk_admin_url = 'bk_admin_url';
require __DIR__ . '/includes/settings/page-shell.php';
require __DIR__ . '/includes/layout.php';
bk_render_settings_tabs($bk_admin_url, $ta);
if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars(bk_settings_admin_label('settings_saved', $ta)) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars(bk_settings_admin_label('error', $ta)) ?></div>
<?php endif;
bk_render_settings_form('smtp', $settings, $ta);
require __DIR__ . '/includes/layout-end.php';