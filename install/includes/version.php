<?php
/**
 * Booking CMS — single source of truth for script version.
 * Used on /booking/site/ and /booking/admin/ (must always match).
 */
define('BK_VERSION', '1.3.1');
define('BK_VERSION_DATE', '2026-07-10');

function bk_version(): string
{
    return BK_VERSION;
}

function bk_version_label(): string
{
    return 'v' . BK_VERSION;
}

function bk_version_date(): string
{
    return BK_VERSION_DATE;
}

/** @return list<array{version:string,date:string}> */
function bk_version_releases(): array
{
    return [
        ['version' => '1.3.0', 'date' => '2026-07-08'],
        ['version' => '1.2.0', 'date' => '2026-07-05'],
        ['version' => '1.1.0', 'date' => '2026-07-02'],
        ['version' => '1.0.0', 'date' => '2026-06-20'],
    ];
}