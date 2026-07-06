<?php
/**
 * Demo admin auth — session based
 * Login: demo / bilobook2026
 */
define('BK_ADMIN_USER', 'demo');
define('BK_ADMIN_PASS', 'bilobook2026');
define('BK_ADMIN_SESSION_KEY', 'bk_admin_logged');

function bk_admin_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function bk_admin_logged(): bool
{
    bk_admin_start();
    return !empty($_SESSION[BK_ADMIN_SESSION_KEY]);
}

function bk_admin_login(string $user, string $pass): bool
{
    if ($user === BK_ADMIN_USER && $pass === BK_ADMIN_PASS) {
        bk_admin_start();
        $_SESSION[BK_ADMIN_SESSION_KEY] = true;
        $_SESSION['bk_admin_user'] = $user;
        return true;
    }
    return false;
}

function bk_admin_logout(): void
{
    bk_admin_start();
    unset($_SESSION[BK_ADMIN_SESSION_KEY], $_SESSION['bk_admin_user']);
}

function bk_admin_require(): void
{
    if (!bk_admin_logged()) {
        header('Location: ' . bk_admin_url('login.php'), true, 302);
        exit;
    }
}

function bk_admin_url(string $path = ''): string
{
    return bk_url('admin/' . ltrim($path, '/'));
}