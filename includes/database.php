<?php
/**
 * Booking CMS — MySQL storage layer.
 */
declare(strict_types=1);

function bk_db_config_file(): string
{
    return __DIR__ . '/../data/db.config.php';
}

function bk_installed_lock_file(): string
{
    return __DIR__ . '/../data/installed.lock';
}

/** @return array<string, mixed>|null */
function bk_db_config(): ?array
{
    static $cache = false;
    if ($cache !== false) {
        return $cache;
    }
    $file = bk_db_config_file();
    if (!is_readable($file)) {
        $cache = null;
        return null;
    }
    $data = require $file;
    $cache = is_array($data) ? $data : null;
    return $cache;
}

function bk_db_prefix(): string
{
    $cfg = bk_db_config();
    $prefix = preg_replace('/[^a-z0-9_]/i', '', (string) ($cfg['prefix'] ?? 'bk_'));
    return $prefix !== '' ? $prefix : 'bk_';
}

function bk_db_table(string $name): string
{
    return bk_db_prefix() . $name;
}

function bk_db_pdo(): ?PDO
{
    static $pdo = null;
    static $failed = false;
    if ($failed) {
        return null;
    }
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $cfg = bk_db_config();
    if ($cfg === null) {
        return null;
    }
    $host = (string) ($cfg['host'] ?? 'localhost');
    $name = (string) ($cfg['database'] ?? $cfg['dbname'] ?? '');
    $user = (string) ($cfg['user'] ?? $cfg['username'] ?? '');
    $pass = (string) ($cfg['pass'] ?? $cfg['password'] ?? '');
    if ($name === '' || $user === '') {
        return null;
    }
    try {
        $dsn = 'mysql:host=' . $host . ';dbname=' . $name . ';charset=utf8mb4';
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (Throwable $e) {
        $failed = true;
        return null;
    }
    return $pdo;
}

function bk_db_require_pdo(): PDO
{
    $pdo = bk_db_pdo();
    if (!$pdo instanceof PDO) {
        throw new RuntimeException('Database not configured. Run install.php first.');
    }
    return $pdo;
}

function bk_is_installed(): bool
{
    return is_file(bk_installed_lock_file()) && bk_db_pdo() instanceof PDO;
}

function bk_is_install_script(): bool
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    return str_ends_with($script, '/install.php')
        || str_ends_with($script, '/migrate-to-mysql.php')
        || str_ends_with($script, '/scripts/setup-mysql.php');
}

function bk_install_url(): string
{
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = ($dir === '' || $dir === '.') ? '' : $dir;
    return ($base === '') ? '/install.php' : $base . '/install.php';
}

function bk_install_redirect_if_needed(): void
{
    if (bk_is_installed() || bk_is_install_script()) {
        return;
    }
    $base = basename(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''));
    if (in_array($base, ['_health.php', 'install.php', 'migrate-to-mysql.php'], true)) {
        return;
    }
    header('Location: ' . bk_install_url(), true, 302);
    exit;
}

/** @return array<string, mixed> */
function bk_db_row_to_array(array $row): array
{
    if (isset($row['data'])) {
        $decoded = is_string($row['data']) ? json_decode($row['data'], true) : $row['data'];
        if (is_array($decoded)) {
            return $decoded;
        }
    }
    unset($row['data']);
    return $row;
}

/** @return list<array<string, mixed>> */
function bk_db_load_collection(string $table): array
{
    $pdo = bk_db_require_pdo();
    $rows = $pdo->query('SELECT * FROM `' . bk_db_table($table) . '`')->fetchAll();
    $out = [];
    foreach ($rows as $row) {
        $out[] = bk_db_row_to_array($row);
    }
    return $out;
}

/** @param list<array<string, mixed>> $items */
function bk_db_save_collection(string $table, array $items, string $idKey): bool
{
    $pdo = bk_db_require_pdo();
    $tbl = bk_db_table($table);
    $pdo->beginTransaction();
    try {
        $pdo->exec('DELETE FROM `' . $tbl . '`');
        $stmt = $pdo->prepare('INSERT INTO `' . $tbl . '` (`id`, `data`) VALUES (?, ?)');
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $id = trim((string) ($item[$idKey] ?? ''));
            if ($id === '') {
                continue;
            }
            $json = json_encode($item, JSON_UNESCAPED_UNICODE);
            if ($json === false) {
                throw new RuntimeException('JSON encode failed for ' . $table);
            }
            $stmt->execute([$id, $json]);
        }
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        return false;
    }
}

/** @return array<string, mixed> */
function bk_db_load_settings(): array
{
    $pdo = bk_db_require_pdo();
    $stmt = $pdo->prepare('SELECT data FROM `' . bk_db_table('settings') . '` WHERE id = 1 LIMIT 1');
    $stmt->execute();
    $row = $stmt->fetch();
    if (!$row) {
        return [];
    }
    $decoded = is_string($row['data'] ?? null) ? json_decode($row['data'], true) : $row['data'];
    return is_array($decoded) ? $decoded : [];
}

/** @param array<string, mixed> $settings */
function bk_db_save_settings(array $settings): bool
{
    $pdo = bk_db_require_pdo();
    $json = json_encode($settings, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    $stmt = $pdo->prepare(
        'INSERT INTO `' . bk_db_table('settings') . '` (id, data) VALUES (1, ?)
         ON DUPLICATE KEY UPDATE data = VALUES(data)'
    );
    return $stmt->execute([$json]);
}

/** @return list<array<string, mixed>> */
function bk_db_load_properties(): array
{
    return bk_db_load_collection('properties');
}

/** @param list<array<string, mixed>> $list */
function bk_db_save_properties(array $list): bool
{
    return bk_db_save_collection('properties', array_values($list), 'id');
}

/** @return list<array<string, mixed>> */
function bk_db_load_bookings(): array
{
    return bk_db_load_collection('bookings');
}

/** @param list<array<string, mixed>> $list */
function bk_db_save_bookings(array $list): bool
{
    return bk_db_save_collection('bookings', array_values($list), 'id');
}

/** @return list<array<string, mixed>> */
function bk_db_load_reviews(): array
{
    return bk_db_load_collection('reviews');
}

/** @param list<array<string, mixed>> $list */
function bk_db_save_reviews(array $list): bool
{
    return bk_db_save_collection('reviews', array_values($list), 'id');
}

function bk_uses_mysql(): bool
{
    return bk_is_installed();
}