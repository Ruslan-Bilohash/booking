<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
$admin_page = 'dashboard';
$page_title = $ta['dashboard'];

$properties = bk_properties_all();
$bookings   = bk_load_bookings();
$active     = count(array_filter($properties, fn($p) => ($p['active'] ?? true) !== false));
$pending    = count(array_filter($bookings, fn($b) => ($b['status'] ?? '') === 'pending'));
$revenue    = array_sum(array_map(fn($b) => ($b['status'] ?? '') === 'confirmed' ? (int)($b['total'] ?? 0) : 0, $bookings));
$activeBookings = array_values(array_filter($bookings, 'bk_booking_is_active'));
usort($activeBookings, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
$recent = array_slice($activeBookings !== [] ? $activeBookings : $bookings, 0, 5);

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-alert adm-alert-info">
    <i class="fas fa-flask"></i> <?= htmlspecialchars($ta['add_note']) ?>
</div>

<div class="adm-stats">
    <div class="adm-stat">
        <div class="adm-stat-icon blue"><i class="fas fa-hotel"></i></div>
        <div>
            <div class="adm-stat-val"><?= $active ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_props']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon green"><i class="fas fa-calendar-check"></i></div>
        <div>
            <div class="adm-stat-val"><?= count($activeBookings) ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_book_active']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon yellow"><i class="fas fa-coins"></i></div>
        <div>
            <div class="adm-stat-val"><?= bk_price($revenue) ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_rev']) ?></div>
        </div>
    </div>
    <div class="adm-stat">
        <div class="adm-stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="adm-stat-val"><?= $pending ?></div>
            <div class="adm-stat-label"><?= htmlspecialchars($ta['stats_pending']) ?></div>
        </div>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['recent_book']) ?></h2>
        <a href="<?= bk_admin_url('bookings.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['all_book']) ?> →</a>
    </div>
    <div class="adm-card-body">
        <?php if (empty($recent)): ?>
        <p style="padding:24px;text-align:center;color:var(--adm-muted)"><?= htmlspecialchars($ta['no_bookings']) ?></p>
        <?php else: ?>
        <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th><?= htmlspecialchars($ta['ref']) ?></th>
                    <th><?= htmlspecialchars($ta['guest']) ?></th>
                    <th><?= htmlspecialchars($ta['property']) ?></th>
                    <th><?= htmlspecialchars($ta['dates']) ?></th>
                    <th><?= htmlspecialchars($ta['total']) ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recent as $b): ?>
                <tr>
                    <td><code style="font-size:11px"><?= htmlspecialchars($b['ref'] ?? '') ?></code></td>
                    <td><?= htmlspecialchars($b['guest'] ?? '') ?></td>
                    <td><?= htmlspecialchars($b['property_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars(($b['checkin'] ?? '') . ' ' . $ta['date_sep'] . ' ' . ($b['checkout'] ?? '')) ?></td>
                    <td><strong><?= bk_price((int)($b['total'] ?? 0)) ?></strong></td>
                    <td><span class="adm-badge adm-badge-<?= htmlspecialchars($b['status'] ?? 'pending') ?>"><?= htmlspecialchars($ta['status_' . ($b['status'] ?? 'pending')] ?? $b['status']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head"><h2><?= htmlspecialchars($ta['quick_actions']) ?></h2></div>
    <div class="adm-card-body padded" style="display:flex;flex-wrap:wrap;gap:10px">
        <a href="<?= bk_admin_url('properties.php') ?>" class="adm-btn adm-btn-primary"><i class="fas fa-hotel"></i> <?= htmlspecialchars($ta['properties']) ?></a>
        <a href="<?= bk_admin_url('bookings.php') ?>" class="adm-btn adm-btn-outline"><i class="fas fa-list"></i> <?= htmlspecialchars($ta['bookings']) ?></a>
        <a href="<?= bk_url('index.php') ?>" class="adm-btn adm-btn-outline" target="_blank"><i class="fas fa-globe"></i> <?= htmlspecialchars($ta['view_site']) ?></a>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>