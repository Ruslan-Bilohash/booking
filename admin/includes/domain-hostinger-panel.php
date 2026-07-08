<?php
$dh = $ta['domain_hostinger'] ?? [];
if ($dh === []) {
    return;
}

$rootLib = dirname(__DIR__, 3) . '/includes/bh-cms-domain-hostinger.php';
if (is_file($rootLib)) {
    require_once $rootLib;
}

$cmsSlug = 'booking';
$defaultDomain = function_exists('bk_license_host') ? bk_license_host() : '';
$state = function_exists('bk_license_state') ? bk_license_state() : [];
$licDomain = trim((string) ($state['license_domain'] ?? ''));
if ($licDomain !== '' && $licDomain !== '*') {
    $defaultDomain = $licDomain;
}
$links = bh_cms_domain_hostinger_links($lang, $cmsSlug);
$apiUrl = bk_admin_url('api/domain-check.php');

?>
<div class="adm-card adm-domain-hostinger" id="bhDomainHostingerPanel"
     data-api="<?= htmlspecialchars($apiUrl) ?>"
     data-product="<?= htmlspecialchars($cmsSlug) ?>"
     data-default-domain="<?= htmlspecialchars($defaultDomain) ?>"
     data-label-checking="<?= htmlspecialchars($dh['checking'] ?? 'Checking domain…') ?>"
     data-label-error="<?= htmlspecialchars($dh['error_generic'] ?? 'Could not check domain') ?>"
     data-label-score-ready="<?= htmlspecialchars($dh['score_ready'] ?? 'CMS reachable') ?>"
     data-label-score-partial="<?= htmlspecialchars($dh['score_partial'] ?? 'DNS OK — upload CMS files') ?>"
     data-label-score-none="<?= htmlspecialchars($dh['score_none'] ?? 'Domain not ready') ?>"
     data-chk-format="<?= htmlspecialchars($dh['chk_format'] ?? 'Valid domain format') ?>"
     data-chk-dns="<?= htmlspecialchars($dh['chk_dns'] ?? 'DNS resolves') ?>"
     data-chk-hostinger-ns="<?= htmlspecialchars($dh['chk_hostinger_ns'] ?? 'Hostinger nameservers') ?>"
     data-chk-bilohash-ip="<?= htmlspecialchars($dh['chk_bilohash_ip'] ?? 'BILOHASH hosting IP') ?>"
     data-chk-ssl="<?= htmlspecialchars($dh['chk_ssl'] ?? 'HTTPS available') ?>"
     data-chk-cms-http="<?= htmlspecialchars($dh['chk_cms_http'] ?? 'CMS URL responds') ?>">
    <div class="adm-card-head">
        <h2><i class="fas fa-server"></i> <?= htmlspecialchars($dh['title'] ?? 'Domain & Hostinger') ?></h2>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help adm-help-compact"><?= htmlspecialchars($dh['intro'] ?? '') ?></p>
        <div class="adm-domain-hostinger-check">
            <label class="adm-field-label" for="bhDomainCheckInput"><?= htmlspecialchars($dh['input_label'] ?? 'Your domain') ?></label>
            <div class="adm-domain-hostinger-row">
                <input type="text" id="bhDomainCheckInput" class="adm-input" placeholder="example.com"
                       value="<?= htmlspecialchars($defaultDomain) ?>" autocomplete="off" spellcheck="false">
                <button type="button" class="adm-btn adm-btn-primary adm-btn-sm" id="bhDomainCheckBtn">
                    <i class="fas fa-magnifying-glass-chart"></i> <?= htmlspecialchars($dh['check_btn'] ?? 'Check domain') ?>
                </button>
            </div>
            <p class="adm-muted adm-domain-hostinger-hint"><?= htmlspecialchars($dh['input_hint'] ?? '') ?></p>
        </div>
        <div class="adm-domain-hostinger-results" id="bhDomainCheckResults" hidden>
            <p class="adm-domain-hostinger-score" id="bhDomainCheckScore"></p>
            <ul class="adm-domain-hostinger-checklist" id="bhDomainCheckList"></ul>
        </div>
        <p class="adm-muted adm-domain-msg" id="bhDomainCheckMsg" hidden role="status"></p>
        <div class="adm-domain-hostinger-options">
            <p class="adm-nav-label" style="margin:16px 0 10px"><?= htmlspecialchars($dh['options_title'] ?? 'Get your domain & hosting') ?></p>
            <div class="adm-domain-hostinger-grid">
                <article class="adm-domain-hostinger-option">
                    <h3><i class="fas fa-globe"></i> <?= htmlspecialchars($dh['opt_register_title'] ?? 'Register a domain') ?></h3>
                    <p><?= htmlspecialchars($dh['opt_register_text'] ?? '') ?></p>
                    <a href="<?= htmlspecialchars($links['hostinger_domain']) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_register_btn'] ?? 'Hostinger domains') ?></a>
                </article>
                <article class="adm-domain-hostinger-option">
                    <h3><i class="fas fa-cloud"></i> <?= htmlspecialchars($dh['opt_hostinger_title'] ?? 'Hostinger hosting') ?></h3>
                    <p><?= htmlspecialchars($dh['opt_hostinger_text'] ?? '') ?></p>
                    <div class="adm-domain-hostinger-option-actions">
                        <a href="<?= htmlspecialchars($links['hostinger_hosting']) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_hostinger_btn'] ?? 'Web hosting') ?></a>
                        <a href="<?= htmlspecialchars($links['hostinger_hpanel']) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_hpanel_btn'] ?? 'hPanel') ?></a>
                    </div>
                </article>
                <article class="adm-domain-hostinger-option adm-domain-hostinger-option--highlight">
                    <h3><i class="fas fa-rocket"></i> <?= htmlspecialchars($dh['opt_self_title'] ?? 'Self-install') ?></h3>
                    <p><?= htmlspecialchars($dh['opt_self_text'] ?? '') ?></p>
                    <a href="<?= htmlspecialchars($links['bilohash_install']) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_self_btn'] ?? 'Install guide') ?></a>
                </article>
                <article class="adm-domain-hostinger-option adm-domain-hostinger-option--bilohash">
                    <h3><i class="fas fa-hands-helping"></i> <?= htmlspecialchars($dh['opt_managed_title'] ?? 'BILOHASH servers') ?></h3>
                    <p><?= htmlspecialchars($dh['opt_managed_text'] ?? '') ?></p>
                    <div class="adm-domain-hostinger-option-actions">
                        <a href="<?= htmlspecialchars($links['bilohash_contact']) ?>" class="adm-btn adm-btn-primary adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_contact_btn'] ?? 'Contact us') ?></a>
                        <a href="<?= htmlspecialchars($links['bilohash_join']) ?>" class="adm-btn adm-btn-outline adm-btn-sm" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars($dh['opt_managed_btn'] ?? 'Subscription') ?></a>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>