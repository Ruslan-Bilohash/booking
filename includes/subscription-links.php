<?php
/**
 * Canonical BILOHASH subscription URL — one plan for all CMS scripts.
 */
function bk_subscription_url(): string
{
    return 'https://bilohash.com/ecosystem/join.php';
}

function bk_license_cabinet_url(): string
{
    return 'https://bilohash.com/ecosystem/cabinet.php';
}

function bk_subscription_external_attrs(): string
{
    return 'target="_blank" rel="noopener noreferrer"';
}