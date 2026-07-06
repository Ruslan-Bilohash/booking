<?php
/**
 * BILOHASH unified chat widget embed
 *
 * Optional vars before include:
 *   $bh_chat_lang       — ua|en|no|ru (default: from $lang or 'en')
 *   $bh_chat_variant    — root|ai|website|auto (default: auto)
 *   $bh_chat_lazy       — true = CSS only; JS loaded by home.js (default: false)
 *   $bh_chat_require_consent — bool|null (null = preset default in JS)
 *   $bh_chat_auto_open  — bool (default: false, website preset overrides)
 *   $bh_chat_auto_open_delay — int ms
 *   $bh_chat_title, $bh_chat_subtitle, $bh_chat_show_footer, $bh_chat_crm_url
 */
$bh_chat_asset_v = '1';
$bh_chat_lang = $bh_chat_lang ?? ($lang ?? 'en');
if ($bh_chat_lang === 'uk') {
    $bh_chat_lang = 'ua';
}
$bh_chat_variant = $bh_chat_variant ?? 'auto';
$bh_chat_lazy = !empty($bh_chat_lazy);
$bh_chat_show_footer = $bh_chat_show_footer ?? true;

$bh_chat_config = [
    'lang' => $bh_chat_lang,
    'variant' => $bh_chat_variant,
    'showFooter' => (bool) $bh_chat_show_footer,
];

if (isset($bh_chat_require_consent)) {
    $bh_chat_config['requireConsent'] = (bool) $bh_chat_require_consent;
}
if (!empty($bh_chat_auto_open)) {
    $bh_chat_config['autoOpen'] = true;
}
if (isset($bh_chat_auto_open_delay)) {
    $bh_chat_config['autoOpenDelay'] = (int) $bh_chat_auto_open_delay;
}
if (!empty($bh_chat_title)) {
    $bh_chat_config['title'] = $bh_chat_title;
}
if (!empty($bh_chat_subtitle)) {
    $bh_chat_config['subtitle'] = $bh_chat_subtitle;
}
if (!empty($bh_chat_crm_url)) {
    $bh_chat_config['crmUrl'] = $bh_chat_crm_url;
}

if ($bh_chat_variant !== 'auto') {
    $preset_map = [
        'root' => ['apiUrl' => '/bot.php', 'historyUrl' => '/get-messages.php', 'sessionKey' => 'bilohash_chat_session'],
        'ai' => ['apiUrl' => '/ai/bot.php', 'historyUrl' => '/ai/get-messages.php', 'sessionKey' => 'grok_ai_consultant_session'],
        'website' => [
            'apiUrl' => '/website/ai/bot.php',
            'historyUrl' => '/website/ai/get-messages.php',
            'sessionKey' => 'bilohash_ai_consultant_session',
            'autoOpen' => true,
            'autoOpenDelay' => 5000,
            'requireConsent' => true,
        ],
    ];
    if (isset($preset_map[$bh_chat_variant])) {
        $bh_chat_config = array_merge($preset_map[$bh_chat_variant], $bh_chat_config);
    }
}
?>
<script>
window.BH_CHAT_CONFIG = Object.assign(window.BH_CHAT_CONFIG || {}, <?= json_encode($bh_chat_config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>);
window.BH_CHAT_LAZY = <?= $bh_chat_lazy ? 'true' : 'false' ?>;
window.BH_CHAT_ASSET_V = <?= (int) $bh_chat_asset_v ?>;
</script>
<?php if (!$bh_chat_lazy): ?>
<link rel="stylesheet" href="/assets/css/bh-chat-widget.css?v=<?= (int) $bh_chat_asset_v ?>">
<script src="/assets/js/bh-chat-widget.js?v=<?= (int) $bh_chat_asset_v ?>" defer></script>
<?php endif; ?>