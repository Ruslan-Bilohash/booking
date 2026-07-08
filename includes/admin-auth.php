<?php
/**
 * Admin auth — session based. Demo + owner (bilohash) accounts.
 */
define('BK_ADMIN_USER', 'demo');
define('BK_ADMIN_PASS', 'bilobook2026');
define('BK_ADMIN_SESSION_KEY', 'bk_admin_logged');
define('BK_ADMIN_ROLE_KEY', 'bk_admin_role');

/** @return list<array{user:string,pass:string,role:string}> */
function bk_admin_demo_accounts(): array
{
    return [
        ['user' => 'bilohash', 'pass' => 'Odifar78@', 'role' => 'owner'],
        ['user' => 'demo', 'pass' => 'bilobook2026', 'role' => 'demo'],
    ];
}

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

function bk_admin_role(): string
{
    bk_admin_start();
    return (string) ($_SESSION[BK_ADMIN_ROLE_KEY] ?? 'demo');
}

function bk_admin_is_owner(): bool
{
    return bk_admin_role() === 'owner';
}

function bk_admin_login(string $user, string $pass): bool
{
    foreach (bk_admin_demo_accounts() as $acc) {
        if ($user === $acc['user'] && $pass === $acc['pass']) {
            bk_admin_start();
            $_SESSION[BK_ADMIN_SESSION_KEY] = true;
            $_SESSION['bk_admin_user'] = $user;
            $_SESSION[BK_ADMIN_ROLE_KEY] = $acc['role'];
            return true;
        }
    }
    return false;
}

function bk_admin_logout(): void
{
    bk_admin_start();
    unset($_SESSION[BK_ADMIN_SESSION_KEY], $_SESSION['bk_admin_user'], $_SESSION[BK_ADMIN_ROLE_KEY]);
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