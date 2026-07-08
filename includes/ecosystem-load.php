<?php
declare(strict_types=1);

function bk_require_ecosystem(string $filename): void
{
    static $loaded = [];
    if (isset($loaded[$filename])) {
        return;
    }
    $candidates = [
        dirname(__DIR__, 2) . '/includes/' . $filename,
        dirname(__DIR__) . '/includes/' . $filename,
    ];
    foreach ($candidates as $path) {
        if (is_file($path)) {
            require_once $path;
            $loaded[$filename] = true;
            return;
        }
    }
}