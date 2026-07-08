<?php
/**
 * BILOHASH CMS ecosystem license — signed keys for shop, booking, and full ecosystem.
 * Prefixes: BHSHOP, BHBOOK, BHECO
 */
declare(strict_types=1);

const CMS_LICENSE_PRODUCT_SHOP = 'shop';
const CMS_LICENSE_PRODUCT_BOOKING = 'booking';
const CMS_LICENSE_PRODUCT_ECOSYSTEM = 'ecosystem';
const CMS_LICENSE_TRIAL_DAYS = 30;

/** @return array<string, array{prefix:string,label:string}> */
function cms_license_catalog(): array
{
    return [
        CMS_LICENSE_PRODUCT_SHOP      => ['prefix' => 'BHSHOP', 'label' => 'Shop CMS'],
        CMS_LICENSE_PRODUCT_BOOKING => ['prefix' => 'BHBOOK', 'label' => 'Booking CMS'],
        CMS_LICENSE_PRODUCT_ECOSYSTEM => ['prefix' => 'BHECO', 'label' => 'BILOHASH Ecosystem'],
    ];
}

function cms_license_secret(): string
{
    static $secret = null;
    if ($secret !== null) {
        return $secret;
    }
    $file = __DIR__ . '/../data/shop-license-secret.php';
    if (is_readable($file)) {
        $v = require $file;
        if (is_string($v) && strlen($v) >= 32) {
            $secret = $v;
            return $secret;
        }
    }
    $env = getenv('BH_SHOP_LICENSE_SECRET');
    $secret = is_string($env) && strlen($env) >= 32 ? $env : 'bilohash-shop-cms-license-v1-change-in-production';
    return $secret;
}

function cms_license_normalize_product(string $product): string
{
    $product = strtolower(trim($product));
    return isset(cms_license_catalog()[$product]) ? $product : '';
}

function cms_license_prefix_for_product(string $product): string
{
    $product = cms_license_normalize_product($product);
    if ($product === '') {
        return '';
    }
    return cms_license_catalog()[$product]['prefix'];
}

function cms_license_product_from_prefix(string $prefix): string
{
    $prefix = strtoupper(trim($prefix));
    foreach (cms_license_catalog() as $slug => $meta) {
        if ($meta['prefix'] === $prefix) {
            return $slug;
        }
    }
    return '';
}

function cms_license_product_matches(string $keyProduct, string $requestedProduct): bool
{
    $keyProduct = cms_license_normalize_product($keyProduct);
    $requestedProduct = cms_license_normalize_product($requestedProduct);
    if ($keyProduct === '' || $requestedProduct === '') {
        return false;
    }
    if ($keyProduct === CMS_LICENSE_PRODUCT_ECOSYSTEM) {
        return true;
    }
    return $keyProduct === $requestedProduct;
}

function cms_license_domain_ok(array $payload, string $domain): bool
{
    $licDomain = strtolower(trim((string) ($payload['d'] ?? '*')));
    $host = strtolower(trim($domain));
    return $licDomain === '*' || $licDomain === '' || $host === '' || $host === $licDomain
        || ($licDomain !== '' && $host !== '' && str_ends_with($host, '.' . $licDomain));
}

/**
 * @return array{ok:bool,valid:bool,expired:bool,domain_ok:bool,product:string,prefix:string,payload:array,error:string}
 */
function cms_license_parse_key(string $key, string $domain = '', ?string $forProduct = null): array
{
    $key = strtoupper(trim($key));
    $fail = static fn(string $error): array => [
        'ok' => false, 'valid' => false, 'expired' => false, 'domain_ok' => false,
        'product' => '', 'prefix' => '', 'payload' => [], 'error' => $error,
    ];

    if ($key === '') {
        return $fail('Invalid key format');
    }

    $parts = explode('.', $key);
    if (count($parts) !== 3) {
        return $fail('Invalid key structure');
    }

    $prefix = $parts[0];
    $product = cms_license_product_from_prefix($prefix);
    if ($product === '') {
        return $fail('Invalid key format');
    }

    $body = $parts[1];
    $sig = $parts[2];
    $expected = strtoupper(substr(hash_hmac('sha256', $body, cms_license_secret()), 0, 16));
    if (!hash_equals($expected, $sig)) {
        return $fail('Invalid signature');
    }

    $decoded = base64_decode(strtr($body, '-_', '+/') . str_repeat('=', (4 - strlen($body) % 4) % 4), true);
    if ($decoded === false) {
        return $fail('Invalid payload');
    }

    $payload = json_decode($decoded, true);
    if (!is_array($payload)) {
        return $fail('Invalid payload');
    }

    $payloadProduct = cms_license_normalize_product((string) ($payload['p'] ?? ''));
    if ($payloadProduct === '' || $payloadProduct !== $product) {
        return $fail('Wrong product');
    }

    if ($forProduct !== null && $forProduct !== '' && !cms_license_product_matches($product, $forProduct)) {
        return $fail('Wrong product for this CMS');
    }

    $exp = (int) ($payload['e'] ?? 0);
    $expired = $exp > 0 && $exp < time();
    $domainOk = cms_license_domain_ok($payload, $domain);

    return [
        'ok'        => true,
        'valid'     => !$expired && $domainOk,
        'expired'   => $expired,
        'domain_ok' => $domainOk,
        'product'   => $product,
        'prefix'    => $prefix,
        'payload'   => $payload,
        'error'     => $expired ? 'License expired' : ($domainOk ? '' : 'Domain mismatch'),
    ];
}

/** Generate a license key. $domain = '*' for any host. */
function cms_license_generate_key(string $product, string $domain = '*', int $years = 1, string $email = '', ?string $version = null): string
{
    $product = cms_license_normalize_product($product);
    if ($product === '') {
        $product = CMS_LICENSE_PRODUCT_SHOP;
    }
    $prefix = cms_license_prefix_for_product($product);
    $payload = [
        'p' => $product,
        'd' => $domain === '' ? '*' : strtolower($domain),
        'e' => strtotime('+' . max(1, $years) . ' year'),
        'v' => $version ?? (defined('SH_VERSION') ? SH_VERSION : (defined('BK_VERSION') ? BK_VERSION : '1.0.0')),
        'm' => substr(trim($email), 0, 120),
        'i' => gmdate('Y-m-d'),
    ];
    $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
    $body = rtrim(strtr(base64_encode((string) $json), '+/', '-_'), '=');
    $sig = strtoupper(substr(hash_hmac('sha256', $body, cms_license_secret()), 0, 16));
    return $prefix . '.' . $body . '.' . $sig;
}

function cms_license_fingerprint(string $key): string
{
    $parts = explode('.', strtoupper(trim($key)));
    $body = $parts[1] ?? $key;
    return substr(hash('sha256', $body), 0, 16);
}

function cms_license_api_base(): string
{
    return 'https://bilohash.com/api';
}

function cms_license_verify_url(): string
{
    return cms_license_api_base() . '/cms-license-verify.php';
}

function cms_license_register_url(): string
{
    return cms_license_api_base() . '/cms-license-register.php';
}

function cms_license_unregister_url(): string
{
    return cms_license_api_base() . '/cms-license-unregister.php';
}

function cms_license_admin_url(string $product, string $domain): string
{
    $domain = trim($domain);
    if ($domain === '') {
        return '';
    }
    $path = match (cms_license_normalize_product($product)) {
        CMS_LICENSE_PRODUCT_BOOKING => '/booking/admin/',
        CMS_LICENSE_PRODUCT_SHOP    => '/shop/admin/',
        default                     => '/',
    };
    return 'https://' . $domain . $path;
}