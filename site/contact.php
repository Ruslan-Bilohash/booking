<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';

$product = 'booking';
$cms_t = cms_contact_texts($product, $lang);
$cms_alert = '';
$cms_alert_type = '';
$cms_values = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = cms_contact_handle_post($product, $lang, 'https://bilohash.com/booking/site/contact.php');
    $cms_alert = $result['alert'];
    $cms_alert_type = $result['alert_type'];
    $cms_values = $result['values'];
}

$cms_csrf = cms_contact_ensure_csrf();
$page_title = $cms_t['page_title'];
$page_desc  = $cms_t['meta_description'];
$canonical  = $site_url . '/contact.php' . ($lang !== 'no' ? '?lang=' . $lang : '');

$cms_prefix = 'bks';
$cms_action = bks_url('contact.php') . ($lang !== 'no' ? '?lang=' . urlencode($lang) : '');

require __DIR__ . '/includes/header.php';
require dirname(__DIR__, 2) . '/includes/cms-contact-form.php';
require __DIR__ . '/includes/footer.php';