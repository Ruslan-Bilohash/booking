<?php
declare(strict_types=1);

$rootLib = dirname(__DIR__, 2) . '/includes/shop-license.php';
if (is_file($rootLib)) {
    require_once $rootLib;
    return;
}

require_once __DIR__ . '/cms-license.php';