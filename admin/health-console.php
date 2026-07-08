<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';
$admin_page = 'health';
$page_title = $ta['health_console'] ?? 'Health check';
require __DIR__ . '/includes/layout.php';

$checks = [
    ['ok' => version_compare(PHP_VERSION, '8.0.0', '>='), 'label' => 'PHP 8.0+', 'hint' => PHP_VERSION],
    ['ok' => extension_loaded('pdo_mysql'), 'label' => 'PDO MySQL', 'hint' => ''],
    ['ok' => bk_uses_mysql(), 'label' => 'MySQL storage active', 'hint' => bk_uses_mysql() ? 'connected' : 'JSON fallback'],
    ['ok' => is_writable(dirname(__DIR__) . '/data'), 'label' => 'Writable data/', 'hint' => ''],
];
$props = bk_load_properties_raw();
$bookings = bk_load_bookings();
$settings = bk_load_settings();
?>
<div class="adm-card">
    <div class="adm-card-head"><h2><?= htmlspecialchars($page_title) ?></h2></div>
    <div class="adm-card-body padded">
        <ul class="adm-health-list">
            <?php foreach ($checks as $c): ?>
            <li class="<?= $c['ok'] ? 'ok' : 'fail' ?>"><i class="fas fa-<?= $c['ok'] ? 'check-circle' : 'times-circle' ?>"></i> <?= htmlspecialchars($c['label']) ?> <small><?= htmlspecialchars($c['hint']) ?></small></li>
            <?php endforeach; ?>
        </ul>
        <p class="adm-help">Properties: <?= count($props) ?> · Bookings: <?= count($bookings) ?> · Storage: <?= bk_uses_mysql() ? 'MySQL' : 'JSON' ?></p>
        <?php if (!bk_uses_mysql()): ?>
        <p class="adm-alert adm-alert-warn"><a href="<?= bk_url('migrate-to-mysql.php') ?>">Migrate to MySQL</a> for production storage.</p>
        <?php endif; ?>
    </div>
</div>
<?php require __DIR__ . '/includes/layout-end.php';