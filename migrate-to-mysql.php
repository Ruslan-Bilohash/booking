<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/mysql-migrate.php';
$appRoot = __DIR__;
$dataDir = $appRoot . '/data';
$lockFile = $dataDir . '/installed.lock';
$already = bk_migrate_mysql_installed($dataDir);
$error = '';
$result = null;
if (!$already && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $result = bk_migrate_json_to_mysql([
        'app_root' => $appRoot, 'data_dir' => $dataDir, 'lock_file' => $lockFile,
        'db_host' => $_POST['db_host'] ?? 'localhost',
        'db_name' => $_POST['db_name'] ?? '',
        'db_user' => $_POST['db_user'] ?? '',
        'db_pass' => $_POST['db_pass'] ?? '',
        'db_prefix' => $_POST['db_prefix'] ?? 'bk_',
    ]);
    if (!empty($result['ok'])) {
        $already = true;
    } else {
        $error = $result['error'] ?? 'Migration failed';
    }
}
?><!DOCTYPE html><html lang="uk"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Booking CMS — MySQL migration</title>
<style>body{font-family:system-ui,sans-serif;background:#0b1220;color:#e2e8f0;padding:24px}.card{max-width:640px;margin:0 auto;background:#111b2e;border-radius:16px;padding:24px}input{width:100%;padding:10px;margin:6px 0 14px;border-radius:8px;border:1px solid #334155;background:#0b1220;color:#fff}button{width:100%;padding:12px;border:none;border-radius:10px;background:#003580;color:#fff;font-weight:700;cursor:pointer}.ok{color:#86efac}.err{color:#fca5a5}</style></head><body>
<div class="card"><h1>Booking CMS → MySQL</h1>
<?php if ($already): ?><p class="ok">MySQL edition already active. <a href="admin/">Admin panel</a></p>
<?php elseif ($error): ?><p class="err"><?= htmlspecialchars($error) ?></p><?php endif; ?>
<?php if (!$already): ?>
<form method="post">
<label>Host</label><input name="db_host" value="localhost">
<label>Database</label><input name="db_name" value="u762384583_booking" required>
<label>User</label><input name="db_user" value="u762384583_booking" required>
<label>Password</label><input type="password" name="db_pass" required>
<label>Prefix</label><input name="db_prefix" value="bk_">
<button type="submit">Migrate JSON → MySQL</button>
</form><?php endif; ?></div></body></html>