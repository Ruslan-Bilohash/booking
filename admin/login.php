<?php
require_once __DIR__ . '/init.php';
$ta = $t['admin'] ?? [];

if (bk_admin_logged()) {
    header('Location: ' . bk_admin_url('index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (bk_admin_login(trim($_POST['username'] ?? ''), $_POST['password'] ?? '')) {
        header('Location: ' . bk_admin_url('index.php'));
        exit;
    }
    $error = $ta['login_error'];
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_meta['html']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?= htmlspecialchars($ta['login_title']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(bk_asset('css/admin.css')) ?>?v=6">
</head>
<body>
<div class="adm-login-wrap">
    <div class="adm-login-box">
        <div class="logo">
            <div class="icon">B</div>
            <h1><?= htmlspecialchars($ta['login_title']) ?></h1>
            <p class="sub"><?= htmlspecialchars($ta['login_sub']) ?></p>
            <p class="adm-login-version"><?= htmlspecialchars(bk_version_label()) ?></p>
        </div>
        <div class="adm-demo-hint"><i class="fas fa-info-circle"></i> <?= htmlspecialchars($ta['demo_creds']) ?></div>
        <?php if ($error): ?>
        <div class="adm-login-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="adm-field">
                <label for="username"><?= htmlspecialchars($ta['username']) ?></label>
                <input type="text" id="username" name="username" required autocomplete="username" value="demo">
            </div>
            <div class="adm-field">
                <label for="password"><?= htmlspecialchars($ta['password']) ?></label>
                <input type="password" id="password" name="password" required autocomplete="current-password" value="bilobook2026">
            </div>
            <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:8px">
                <i class="fas fa-sign-in-alt"></i> <?= htmlspecialchars($ta['login_btn']) ?>
            </button>
        </form>
        <p style="text-align:center;margin-top:20px;font-size:12px">
            <a href="<?= bk_url('index.php') ?>">← <?= htmlspecialchars($t['breadcrumb_home']) ?></a>
        </p>
        <div style="display:flex;justify-content:center;margin-top:16px">
            <?php require __DIR__ . '/includes/lang-dropdown.php'; ?>
        </div>
    </div>
</div>
<script>
(function () {
    var langDropdown = document.getElementById('admLangDropdown');
    var langBtn = document.getElementById('admLangBtn');
    var langMenu = document.getElementById('admLangMenu');
    function closeLangDropdown() {
        if (!langDropdown) return;
        langDropdown.classList.remove('is-open');
        if (langBtn) langBtn.setAttribute('aria-expanded', 'false');
        if (langMenu) langMenu.hidden = true;
    }
    if (langBtn && langMenu && langDropdown) {
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = langDropdown.classList.toggle('is-open');
            langMenu.hidden = !open;
            langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        langMenu.addEventListener('click', function (e) { e.stopPropagation(); });
    }
    document.addEventListener('click', closeLangDropdown);
})();
</script>
</body>
</html>