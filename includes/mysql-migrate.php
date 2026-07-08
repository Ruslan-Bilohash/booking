<?php
/**
 * Booking CMS — JSON → MySQL migration.
 */
declare(strict_types=1);

const BK_MIGRATE_VERSION = '1.3.0';

/** @return array<string, array{file:string,table:string,id_key:string}> */
function bk_migrate_json_catalog(): array
{
    return [
        'properties' => ['file' => 'properties.json', 'table' => 'properties', 'id_key' => 'id'],
        'bookings'   => ['file' => 'bookings.json',   'table' => 'bookings',   'id_key' => 'id'],
        'reviews'    => ['file' => 'reviews.json',    'table' => 'reviews',    'id_key' => 'id'],
    ];
}

function bk_migrate_schema_paths(string $appRoot): array
{
    $out = [];
    foreach ([$appRoot . '/schema.sql'] as $path) {
        if (is_readable($path)) {
            $out[] = $path;
        }
    }
    return $out;
}

function bk_migrate_resolve_schema(string $appRoot): string
{
    $paths = bk_migrate_schema_paths($appRoot);
    if ($paths === []) {
        throw new RuntimeException('schema.sql not found.');
    }
    return $paths[0];
}

function bk_migrate_prefix_safe(string $prefix): string
{
    $prefix = preg_replace('/[^a-z0-9_]/i', '', $prefix) ?? 'bk_';
    return $prefix !== '' ? $prefix : 'bk_';
}

/** @return array{ok:bool,error:string,pdo:?PDO} */
function bk_migrate_connect(string $host, string $database, string $user, string $pass): array
{
    try {
        $pdo = new PDO(
            'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8mb4',
            $user,
            $pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return ['ok' => true, 'error' => '', 'pdo' => $pdo];
    } catch (Throwable $e) {
        return ['ok' => false, 'error' => $e->getMessage(), 'pdo' => null];
    }
}

function bk_migrate_write_db_config(string $dataDir, array $cfg): bool
{
    $php = "<?php\nreturn [\n"
        . "    'host' => " . var_export($cfg['host'], true) . ",\n"
        . "    'database' => " . var_export($cfg['database'], true) . ",\n"
        . "    'user' => " . var_export($cfg['user'], true) . ",\n"
        . "    'pass' => " . var_export($cfg['pass'], true) . ",\n"
        . "    'prefix' => " . var_export($cfg['prefix'], true) . ",\n"
        . "];\n";
    return file_put_contents($dataDir . '/db.config.php', $php) !== false;
}

function bk_migrate_write_lock(string $lockFile, string $note = ''): bool
{
    $line = gmdate('c') . "\nBooking CMS " . BK_MIGRATE_VERSION . " — MySQL\n";
    if ($note !== '') {
        $line .= $note . "\n";
    }
    return file_put_contents($lockFile, $line) !== false;
}

function bk_migrate_run_schema(PDO $pdo, string $prefix, string $schemaFile): void
{
    $sql = file_get_contents($schemaFile) ?: '';
    $sql = str_replace('{prefix}', bk_migrate_prefix_safe($prefix), $sql);
    foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
        if ($stmt !== '') {
            $pdo->exec($stmt);
        }
    }
}

/**
 * @param list<array<string,mixed>> $items
 * @return array{imported:int,skipped:int}
 */
function bk_migrate_import_collection(PDO $pdo, string $prefix, string $table, array $items, string $idKey): array
{
    $tbl = bk_migrate_prefix_safe($prefix) . $table;
    $pdo->exec('DELETE FROM `' . $tbl . '`');
    $imported = 0;
    $skipped = 0;
    if ($items === []) {
        return ['imported' => 0, 'skipped' => 0];
    }
    $stmt = $pdo->prepare('INSERT INTO `' . $tbl . '` (`id`, `data`) VALUES (?, ?)');
    foreach ($items as $item) {
        if (!is_array($item)) {
            $skipped++;
            continue;
        }
        $id = trim((string) ($item[$idKey] ?? ''));
        if ($id === '') {
            $skipped++;
            continue;
        }
        $json = json_encode($item, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $skipped++;
            continue;
        }
        $stmt->execute([$id, $json]);
        $imported++;
    }
    return ['imported' => $imported, 'skipped' => $skipped];
}

/** @param array<string,mixed> $settings */
function bk_migrate_import_settings(PDO $pdo, string $prefix, array $settings): bool
{
    if ($settings === []) {
        return true;
    }
    $tbl = bk_migrate_prefix_safe($prefix) . 'settings';
    $json = json_encode($settings, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    $stmt = $pdo->prepare('INSERT INTO `' . $tbl . '` (id, data) VALUES (1, ?) ON DUPLICATE KEY UPDATE data = VALUES(data)');
    return $stmt->execute([$json]);
}

/** @return list<string> */
function bk_migrate_json_files_found(string $dataDir): array
{
    $found = [];
    if (is_readable($dataDir . '/settings.json')) {
        $found[] = 'settings.json';
    }
    foreach (bk_migrate_json_catalog() as $meta) {
        $path = $dataDir . '/' . $meta['file'];
        if (is_readable($path)) {
            $found[] = $meta['file'];
        }
    }
    return array_values(array_unique($found));
}

function bk_migrate_json_edition_detected(string $dataDir): bool
{
    return bk_migrate_json_files_found($dataDir) !== [];
}

function bk_migrate_mysql_installed(string $dataDir): bool
{
    return is_file($dataDir . '/installed.lock') && is_readable($dataDir . '/db.config.php');
}

/** @return array<string,mixed>|null */
function bk_migrate_read_json_file(string $path)
{
    if (!is_readable($path)) {
        return null;
    }
    $decoded = json_decode(file_get_contents($path) ?: '[]', true);
    return is_array($decoded) ? $decoded : null;
}

/** @return array{ok:bool,error:string,stats:array<string,array{imported:int,skipped:int}>} */
function bk_migrate_import_json_dir(PDO $pdo, string $prefix, string $dataDir): array
{
    $stats = [];
    foreach (bk_migrate_json_catalog() as $name => $meta) {
        $data = bk_migrate_read_json_file($dataDir . '/' . $meta['file']);
        if (!is_array($data) || $data === []) {
            continue;
        }
        $stats[$name] = bk_migrate_import_collection($pdo, $prefix, $meta['table'], $data, $meta['id_key']);
    }
    $settings = bk_migrate_read_json_file($dataDir . '/settings.json');
    if (is_array($settings) && $settings !== []) {
        bk_migrate_import_settings($pdo, $prefix, $settings);
        $stats['settings'] = ['imported' => 1, 'skipped' => 0];
    }
    return ['ok' => true, 'error' => '', 'stats' => $stats];
}

/** Seed properties from PHP if JSON missing. */
function bk_migrate_seed_properties_if_empty(PDO $pdo, string $prefix, string $dataDir): void
{
    $tbl = bk_migrate_prefix_safe($prefix) . 'properties';
    $count = (int) $pdo->query('SELECT COUNT(*) FROM `' . $tbl . '`')->fetchColumn();
    if ($count > 0) {
        return;
    }
    $seedFile = $dataDir . '/properties.php';
    if (!is_readable($seedFile)) {
        return;
    }
    $seed = require $seedFile;
    if (!is_array($seed) || $seed === []) {
        return;
    }
    foreach ($seed as &$p) {
        $p['active'] = true;
    }
    unset($p);
    bk_migrate_import_collection($pdo, $prefix, 'properties', $seed, 'id');
}

function bk_migrate_write_admin_config(string $dataDir, string $adminUser, string $adminPass): bool
{
    if ($adminUser === '' || strlen($adminPass) < 6) {
        return false;
    }
    $php = "<?php\nreturn [\n"
        . "    'user' => " . var_export($adminUser, true) . ",\n"
        . "    'pass_hash' => " . var_export(password_hash($adminPass, PASSWORD_DEFAULT), true) . ",\n"
        . "];\n";
    return file_put_contents($dataDir . '/admin.config.php', $php) !== false;
}

/**
 * @return array{ok:bool,error:string,stats:array<string,array{imported:int,skipped:int}>,backup_dir:string}
 */
function bk_migrate_json_to_mysql(array $opts): array
{
    $appRoot = (string) ($opts['app_root'] ?? '');
    $dataDir = (string) ($opts['data_dir'] ?? ($appRoot . '/data'));
    $lockFile = (string) ($opts['lock_file'] ?? ($dataDir . '/installed.lock'));
    $host = trim((string) ($opts['db_host'] ?? 'localhost'));
    $database = trim((string) ($opts['db_name'] ?? ''));
    $user = trim((string) ($opts['db_user'] ?? ''));
    $pass = (string) ($opts['db_pass'] ?? '');
    $prefix = bk_migrate_prefix_safe((string) ($opts['db_prefix'] ?? 'bk_'));
    $adminUser = trim((string) ($opts['admin_user'] ?? ''));
    $adminPass = (string) ($opts['admin_pass'] ?? '');

    if ($database === '' || $user === '') {
        return ['ok' => false, 'error' => 'Database name and user are required.', 'stats' => [], 'backup_dir' => ''];
    }
    if (bk_migrate_mysql_installed($dataDir)) {
        return ['ok' => false, 'error' => 'MySQL already installed.', 'stats' => [], 'backup_dir' => ''];
    }

    $conn = bk_migrate_connect($host, $database, $user, $pass);
    if (!$conn['ok'] || !$conn['pdo'] instanceof PDO) {
        return ['ok' => false, 'error' => 'MySQL connection failed: ' . $conn['error'], 'stats' => [], 'backup_dir' => ''];
    }

    if (!is_dir($dataDir)) {
        @mkdir($dataDir, 0755, true);
    }

    if (!bk_migrate_write_db_config($dataDir, [
        'host' => $host, 'database' => $database, 'user' => $user, 'pass' => $pass, 'prefix' => $prefix,
    ])) {
        return ['ok' => false, 'error' => 'Could not write db.config.php', 'stats' => [], 'backup_dir' => ''];
    }

    try {
        $schema = bk_migrate_resolve_schema($appRoot);
        bk_migrate_run_schema($conn['pdo'], $prefix, $schema);
        $import = bk_migrate_import_json_dir($conn['pdo'], $prefix, $dataDir);
        bk_migrate_seed_properties_if_empty($conn['pdo'], $prefix, $dataDir);
    } catch (Throwable $e) {
        @unlink($dataDir . '/db.config.php');
        return ['ok' => false, 'error' => $e->getMessage(), 'stats' => [], 'backup_dir' => ''];
    }

    if (!is_readable($dataDir . '/admin.config.php') && $adminUser !== '' && $adminPass !== '') {
        bk_migrate_write_admin_config($dataDir, $adminUser, $adminPass);
    }

    bk_migrate_write_lock($lockFile, 'Migrated from JSON edition');

    return ['ok' => true, 'error' => '', 'stats' => $import['stats'], 'backup_dir' => ''];
}