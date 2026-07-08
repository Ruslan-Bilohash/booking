<?php
require_once __DIR__ . '/../config.php';

define('BKS_LANG_COOKIE', 'bks_lang');

$BKS_LANGS = [
    'no' => ['label' => 'NO', 'name' => 'Norsk', 'flag' => '🇳🇴', 'locale' => 'nb-NO', 'html' => 'no'],
    'en' => ['label' => 'EN', 'name' => 'English', 'flag' => '🇬🇧', 'locale' => 'en-GB', 'html' => 'en'],
    'uk' => ['label' => 'UA', 'name' => 'Українська', 'flag' => '🇺🇦', 'locale' => 'uk-UA', 'html' => 'uk'],
    'ru' => ['label' => 'RU', 'name' => 'Русский', 'flag' => '🇷🇺', 'locale' => 'ru-RU', 'html' => 'ru'],
    'sv' => ['label' => 'SV', 'name' => 'Svenska', 'flag' => '🇸🇪', 'locale' => 'sv-SE', 'html' => 'sv'],
    'lt' => ['label' => 'LT', 'name' => 'Lietuvių', 'flag' => '🇱🇹', 'locale' => 'lt-LT', 'html' => 'lt'],
];

function bks_langs(): array { global $BKS_LANGS; return $BKS_LANGS; }

function bks_detect_lang(): string
{
    global $base_path, $BKS_LANGS;
    $codes = array_keys($BKS_LANGS);

    if (!empty($_GET['lang']) && in_array($_GET['lang'], $codes, true)) {
        $chosen = $_GET['lang'];
        setcookie(BKS_LANG_COOKIE, $chosen, [
            'expires' => time() + 365 * 86400,
            'path'    => rtrim($base_path, '/') . '/' ?: '/',
            'secure'  => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'samesite'=> 'Lax',
        ]);
        if ($chosen === 'no') {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
            $parts = parse_url($uri);
            parse_str($parts['query'] ?? '', $q);
            unset($q['lang']);
            $clean = ($parts['path'] ?? '/') . ($q ? '?' . http_build_query($q) : '');
            if ($clean !== $uri) { header('Location: ' . $clean, true, 302); exit; }
        }
        return $chosen;
    }
    if (!empty($_COOKIE[BKS_LANG_COOKIE]) && in_array($_COOKIE[BKS_LANG_COOKIE], $codes, true)) {
        return $_COOKIE[BKS_LANG_COOKIE];
    }
    return 'no';
}

$lang      = bks_detect_lang();
$lang_meta = $BKS_LANGS[$lang] ?? $BKS_LANGS['no'];
$lang_file = __DIR__ . '/../lang/' . $lang . '.php';
if (!is_file($lang_file)) $lang_file = __DIR__ . '/../lang/en.php';
$t = require $lang_file;

require_once dirname(__DIR__, 3) . '/includes/ecosystem-i18n.php';
$t = bh_apply_ecosystem_translations($t, $lang, 'booking');

require_once __DIR__ . '/market.php';
require_once __DIR__ . '/helpers.php';

$t = bks_apply_market_translations($t, $lang);
$GLOBALS['bks_market'] = bks_market($lang);

$diFile = __DIR__ . '/../lang/demo-install.php';
if (is_file($diFile)) {
    $diAll = require $diFile;
    $t['demo_install'] = $diAll[$lang] ?? $diAll['en'] ?? [];
}