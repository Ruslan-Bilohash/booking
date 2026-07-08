<?php
/**
 * Booking CMS — fresh MySQL installation wizard (30-day trial).
 */
declare(strict_types=1);

require_once __DIR__ . '/includes/mysql-migrate.php';
require_once __DIR__ . '/includes/version.php';
require_once __DIR__ . '/includes/license-runtime.php';

const BK_INSTALL_VERSION = BK_VERSION;

$appRoot = __DIR__;
$dataDir = $appRoot . '/data';
$lockFile = $dataDir . '/installed.lock';
$already = bk_migrate_mysql_installed($dataDir);
$error = '';
$success = false;

if (!$already && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $result = bk_migrate_json_to_mysql([
        'app_root'   => $appRoot,
        'data_dir'   => $dataDir,
        'lock_file'  => $lockFile,
        'db_host'    => $_POST['db_host'] ?? 'localhost',
        'db_name'    => $_POST['db_name'] ?? '',
        'db_user'    => $_POST['db_user'] ?? '',
        'db_pass'    => $_POST['db_pass'] ?? '',
        'db_prefix'  => $_POST['db_prefix'] ?? 'bk_',
        'admin_user' => $_POST['admin_user'] ?? 'admin',
        'admin_pass' => $_POST['admin_pass'] ?? '',
    ]);
    if (!empty($result['ok'])) {
        $licenseKey = trim((string) ($_POST['license_key'] ?? ''));
        if ($licenseKey !== '' && is_file(__DIR__ . '/includes/license-runtime.php')) {
            require_once __DIR__ . '/includes/license-runtime.php';
            bk_license_activate($licenseKey);
        }
        $success = true;
        $already = true;
    } else {
        $error = (string) ($result['error'] ?? 'Installation failed');
    }
}

function bk_install_h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Booking CMS — install</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #0b1220; color: #e2e8f0; margin: 0; padding: 24px; }
        .card { max-width: 560px; margin: 0 auto; background: #111b2e; border: 1px solid #1e293b; border-radius: 16px; padding: 28px; }
        h1 { margin: 0 0 8px; font-size: 1.35rem; }
        .lead { color: #94a3b8; font-size: .92rem; line-height: 1.55; margin: 0 0 20px; }
        label { display: block; font-size: .82rem; color: #94a3b8; margin-bottom: 4px; }
        input { width: 100%; box-sizing: border-box; padding: 10px 12px; margin-bottom: 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #f8fafc; }
        button { width: 100%; padding: 12px; border: none; border-radius: 10px; background: linear-gradient(135deg, #22d3ee, #14b8a6); color: #0f172a; font-weight: 700; cursor: pointer; }
        .ok { color: #6ee7b7; }
        .err { color: #fca5a5; margin-bottom: 12px; }
        .trial { font-size: .8rem; color: #64748b; margin-top: 16px; }
        a { color: #22d3ee; }
    </style>
</head>
<body>
<div class="card">
    <h1>Booking CMS <?= bk_install_h(BK_INSTALL_VERSION) ?></h1>
    <p class="lead">MySQL install wizard — demo data, admin account and <?= (int) BK_LICENSE_TRIAL_DAYS ?>-day trial. Optional BHBOOK license key from <a href="https://bilohash.com/license.php" target="_blank" rel="noopener">bilohash.com/license.php</a>.</p>
    <?php if ($success): ?>
    <p class="ok">Installation complete. <a href="admin/login.php">Open admin panel</a> · <a href="index.php">View site</a></p>
    <?php elseif ($already): ?>
    <p class="ok">Already installed. <a href="admin/">Admin</a> · <a href="index.php">Site</a></p>
    <?php else: ?>
    <?php if ($error !== ''): ?><p class="err"><?= bk_install_h($error) ?></p><?php endif; ?>
    <form method="post">
        <label>MySQL host</label>
        <input name="db_host" value="localhost" required>
        <label>Database name</label>
        <input name="db_name" required placeholder="your_db">
        <label>Database user</label>
        <input name="db_user" required>
        <label>Database password</label>
        <input type="password" name="db_pass">
        <label>Table prefix</label>
        <input name="db_prefix" value="bk_">
        <label>Admin username</label>
        <input name="admin_user" value="admin" required>
        <label>Admin password (min. 6 chars)</label>
        <input type="password" name="admin_pass" required minlength="6">
        <label>License key BHBOOK… (optional)</label>
        <input name="license_key" placeholder="BHBOOK-…">
        <button type="submit">Install Booking CMS</button>
    </form>
    <p class="trial">Trial: <?= (int) BK_LICENSE_TRIAL_DAYS ?> days on this domain. Subscribe at <a href="https://bilohash.com/ecosystem/join.php">bilohash.com/ecosystem/join.php</a>.</p>
    <?php endif; ?>
</div>
</body>
</html>