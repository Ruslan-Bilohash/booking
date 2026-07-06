<?php
require_once __DIR__ . '/init.php';
bk_admin_logout();
header('Location: ' . bk_admin_url('login.php'), true, 302);
exit;