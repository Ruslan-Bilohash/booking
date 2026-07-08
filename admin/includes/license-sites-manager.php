<?php
$lp = $ta['license_page'] ?? [];
$sm = $lp['sites_manage'] ?? [];
$licenseInfo = isset($licenseInfo) && is_array($licenseInfo) ? $licenseInfo : bk_license_verify_current(true);
$status = $status ?? bk_license_status();
$state = $state ?? bk_license_state();

$sites = is_array($licenseInfo['sites'] ?? null) ? $licenseInfo['sites'] : [];
$sitesCount = max(count($sites), (int) ($licenseInfo['sites_count'] ?? 0));
$daysLeft = (int) ($licenseInfo['days_left'] ?? ($status['trial_days_left'] ?? 0));
$statusKey = (string) ($status['status'] ?? 'trial');
$licenseKey = trim((string) ($state['license_key'] ?? ''));
$canManage = $statusKey === 'licensed' && $licenseKey !== '';
$isOwner = function_exists('bk_admin_is_owner') && bk_admin_is_owner();
$maskedKey = $licenseKey !== '' ? bk_license_mask_key($licenseKey) : '';
$expLabel = (string) ($licenseInfo['exp_label'] ?? '');
if ($statusKey === 'trial') {
    $expLabel = $sm['trial_expires'] ?? ($lp['panel']['trial_expires'] ?? 'Trial period');
}

?>
<div class="adm-card adm-license-manage" id="shLicenseSitesManager"
     data-api="<?= htmlspecialchars(bk_admin_url('api/license-sites.php')) ?>"
     data-can-manage="<?= $canManage && $isOwner ? '1' : '0' ?>"
     data-label-sync="<?= htmlspecialchars($sm['sync_btn'] ?? 'Sync domains') ?>"
     data-label-syncing="<?= htmlspecialchars($sm['syncing'] ?? 'Syncing…') ?>"
     data-label-sync-ok="<?= htmlspecialchars($sm['sync_ok'] ?? 'Domains updated') ?>"
     data-label-detach="<?= htmlspecialchars($sm['action_detach'] ?? 'Detach') ?>"
     data-label-detach-confirm="<?= htmlspecialchars($sm['detach_confirm'] ?? 'Remove {domain} from your license registry?') ?>"
     data-label-detach-ok="<?= htmlspecialchars($sm['detach_ok'] ?? 'Domain detached') ?>"
     data-label-detach-fail="<?= htmlspecialchars($sm['detach_fail'] ?? 'Could not detach domain') ?>"
     data-label-current="<?= htmlspecialchars($sm['status_current'] ?? ($lp['panel']['current_site'] ?? 'This site')) ?>"
     data-label-active="<?= htmlspecialchars($sm['status_active'] ?? 'Active') ?>"
     data-label-open="<?= htmlspecialchars($sm['action_open'] ?? 'Open admin') ?>">
    <div class="adm-card-head">
        <h2><i class="fas fa-globe"></i> <?= htmlspecialchars($sm['title'] ?? ($lp['sites_title'] ?? 'Connected booking installations')) ?></h2>
        <?php if ($canManage && $isOwner): ?>
        <button type="button" class="adm-btn adm-btn-outline adm-btn-sm" id="shLicenseSitesSyncBtn">
            <i class="fas fa-arrows-rotate"></i> <?= htmlspecialchars($sm['sync_btn'] ?? 'Sync domains') ?>
        </button>
        <?php endif; ?>
    </div>
    <div class="adm-card-body padded">
        <p class="adm-help adm-help-compact"><?= htmlspecialchars($sm['help'] ?? ($lp['sites_help'] ?? '')) ?></p>

        <div class="adm-license-manage-meta">
            <?php if ($maskedKey !== ''): ?>
            <span class="adm-license-manage-pill"><i class="fas fa-key"></i> <?= htmlspecialchars($sm['key_label'] ?? 'License key') ?>: <code><?= htmlspecialchars($maskedKey) ?></code></span>
            <?php endif; ?>
            <span class="adm-license-manage-pill"><i class="fas fa-calendar"></i> <?= htmlspecialchars($sm['days_label'] ?? 'Days left') ?>: <strong><?= (int) $daysLeft ?></strong></span>
            <?php if ($expLabel !== ''): ?>
            <span class="adm-license-manage-pill"><i class="fas fa-hourglass-half"></i> <?= htmlspecialchars($sm['exp_label'] ?? 'Valid until') ?>: <strong><?= htmlspecialchars($expLabel) ?></strong></span>
            <?php endif; ?>
            <span class="adm-license-manage-pill"><i class="fas fa-server"></i> <?= htmlspecialchars(sprintf($sm['count_label'] ?? ($lp['sites_count_label'] ?? '%d connected'), $sitesCount)) ?></span>
        </div>

        <?php if ($statusKey === 'trial'): ?>
        <p class="adm-help adm-license-trial-note"><i class="fas fa-info-circle"></i> <?= htmlspecialchars($sm['trial_note'] ?? '') ?></p>
        <?php endif; ?>

        <?php if ($sites !== []): ?>
        <div class="adm-table-wrap adm-license-table-wrap">
            <table class="adm-table adm-license-domains-table">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars($sm['col_domain'] ?? 'Domain') ?></th>
                        <th><?= htmlspecialchars($sm['col_version'] ?? 'Version') ?></th>
                        <th><?= htmlspecialchars($sm['col_last_seen'] ?? 'Last seen') ?></th>
                        <th><?= htmlspecialchars($sm['col_status'] ?? 'Status') ?></th>
                        <?php if ($canManage && $isOwner): ?>
                        <th><?= htmlspecialchars($sm['col_actions'] ?? 'Actions') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody id="shLicenseSitesTableBody">
                    <?php foreach ($sites as $site):
                        if (!is_array($site)) {
                            continue;
                        }
                        $siteDomain = (string) ($site['domain'] ?? '');
                        if ($siteDomain === '') {
                            continue;
                        }
                        $isCurrent = !empty($site['current']);
                        $adminUrl = bk_license_booking_admin_url($siteDomain);
                    ?>
                    <tr class="adm-license-domain-row<?= $isCurrent ? ' is-current' : '' ?>" data-domain="<?= htmlspecialchars($siteDomain) ?>">
                        <td>
                            <i class="fas fa-globe adm-muted" aria-hidden="true"></i>
                            <code><?= htmlspecialchars($siteDomain) ?></code>
                        </td>
                        <td><?= htmlspecialchars((string) ($site['version'] ?? '') ?: '—') ?></td>
                        <td><?= htmlspecialchars(bk_license_format_last_seen((string) ($site['last_seen'] ?? ''))) ?></td>
                        <td>
                            <?php if ($isCurrent): ?>
                            <span class="adm-badge adm-badge-info adm-badge-sm"><?= htmlspecialchars($sm['status_current'] ?? 'This site') ?></span>
                            <?php else: ?>
                            <span class="adm-badge adm-badge-green adm-badge-sm"><?= htmlspecialchars($sm['status_active'] ?? 'Active') ?></span>
                            <?php endif; ?>
                        </td>
                        <?php if ($canManage && $isOwner): ?>
                        <td class="adm-license-domain-actions">
                            <?php if ($adminUrl !== '' && !$isCurrent): ?>
                            <a href="<?= htmlspecialchars($adminUrl) ?>" class="adm-btn adm-btn-outline adm-btn-xs" target="_blank" rel="noopener">
                                <i class="fas fa-external-link-alt"></i> <?= htmlspecialchars($sm['action_open'] ?? 'Open admin') ?>
                            </a>
                            <?php endif; ?>
                            <?php if (!$isCurrent): ?>
                            <button type="button" class="adm-btn adm-btn-outline adm-btn-xs shLicenseDetachBtn" data-domain="<?= htmlspecialchars($siteDomain) ?>">
                                <i class="fas fa-unlink"></i> <?= htmlspecialchars($sm['action_detach'] ?? 'Detach') ?>
                            </button>
                            <?php else: ?>
                            <span class="adm-muted adm-license-current-hint"><?= htmlspecialchars($sm['current_hint'] ?? 'Current installation') ?></span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="adm-muted" id="shLicenseSitesEmpty"><?= htmlspecialchars($lp['sites_empty'] ?? 'No sites in registry yet.') ?></p>
        <?php endif; ?>

        <p class="adm-muted adm-license-msg" id="shLicenseSitesMsg" hidden role="status"></p>
    </div>
</div>