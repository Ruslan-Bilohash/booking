<?php
/**
 * One-shot MySQL setup for Booking CMS (CLI or browser).
 * Usage: php scripts/setup-mysql.php
 */
declare(strict_types=1);

$appRoot = dirname(__DIR__);
require_once $appRoot . '/includes/mysql-migrate.php';

$dataDir = $appRoot . '/data';
$lockFile = $dataDir . '/installed.lock';

if (bk_migrate_mysql_installed($dataDir)) {
    echo "OK: MySQL already installed.\n";
    exit(0);
}

$result = bk_migrate_json_to_mysql([
    'app_root'  => $appRoot,
    'data_dir'  => $dataDir,
    'lock_file' => $lockFile,
    'db_host'   => 'localhost',
    'db_name'   => 'u762384583_booking',
    'db_user'   => 'u762384583_booking',
    'db_pass'   => 'Odifarka78@',
    'db_prefix' => 'bk_',
]);

if (empty($result['ok'])) {
    fwrite(STDERR, 'FAIL: ' . ($result['error'] ?? 'unknown') . "\n");
    exit(1);
}

echo "OK: Booking CMS migrated to MySQL.\n";
foreach ($result['stats'] as $name => $stat) {
    echo "  {$name}: imported " . ($stat['imported'] ?? 0) . "\n";
}
exit(0);