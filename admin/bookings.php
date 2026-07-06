<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
$admin_page = 'bookings';
$page_title = $ta['bookings'];

$bookings = bk_load_bookings();
$filter = $_GET['filter'] ?? 'active';
if (!in_array($filter, ['active', 'all', 'cancelled'], true)) {
    $filter = 'active';
}
$activeCount = count(array_filter($bookings, 'bk_booking_is_active'));
$filtered = array_values(array_filter($bookings, function ($b) use ($filter) {
    $st = $b['status'] ?? 'pending';
    if ($filter === 'active') {
        return bk_booking_is_active($b);
    }
    if ($filter === 'cancelled') {
        return $st === 'cancelled';
    }
    return true;
}));
usort($filtered, function ($a, $b) {
    $order = ['pending' => 0, 'confirmed' => 1, 'cancelled' => 2];
    $sa = $order[$a['status'] ?? 'pending'] ?? 9;
    $sb = $order[$b['status'] ?? 'pending'] ?? 9;
    if ($sa !== $sb) {
        return $sa <=> $sb;
    }
    return strcmp($b['created_at'] ?? '', $a['created_at'] ?? '');
});
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['booking_id']) && !empty($_POST['status'])) {
    $bid = $_POST['booking_id'];
    $newStatus = $_POST['status'];
    if (in_array($newStatus, ['confirmed', 'pending', 'cancelled'], true)) {
        foreach ($bookings as &$b) {
            if (($b['id'] ?? '') === $bid) {
                $b['status'] = $newStatus;
                break;
            }
        }
        unset($b);
        if (bk_save_bookings($bookings)) {
            $flash = 'success';
        } else {
            $flash = 'error';
        }
    }
}

require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<nav class="adm-booking-filters" aria-label="Booking filters">
    <a href="<?= bk_admin_url('bookings.php?filter=active') ?>" class="adm-booking-filter <?= $filter === 'active' ? 'active' : '' ?>">
        <?= htmlspecialchars($ta['bookings_active']) ?> (<?= $activeCount ?>)
    </a>
    <a href="<?= bk_admin_url('bookings.php?filter=all') ?>" class="adm-booking-filter <?= $filter === 'all' ? 'active' : '' ?>">
        <?= htmlspecialchars($ta['all_book']) ?> (<?= count($bookings) ?>)
    </a>
    <a href="<?= bk_admin_url('bookings.php?filter=cancelled') ?>" class="adm-booking-filter <?= $filter === 'cancelled' ? 'active' : '' ?>">
        <?= htmlspecialchars($ta['status_cancelled']) ?> (<?= count($bookings) - $activeCount ?>)
    </a>
</nav>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['bookings']) ?></h2>
    </div>
    <div class="adm-card-body">
        <?php if (empty($filtered)): ?>
        <p style="padding:32px;text-align:center;color:var(--adm-muted)"><?= htmlspecialchars($ta['no_bookings']) ?></p>
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
                    <th><?= htmlspecialchars($ta['created']) ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filtered as $b):
                    $st = $b['status'] ?? 'pending';
                ?>
                <tr>
                    <td><code style="font-size:11px"><?= htmlspecialchars($b['ref'] ?? $b['id'] ?? '') ?></code></td>
                    <td>
                        <strong><?= htmlspecialchars($b['guest'] ?? '') ?></strong><br>
                        <small style="color:var(--adm-muted)"><?= htmlspecialchars($b['email'] ?? '') ?></small>
                    </td>
                    <td><?= htmlspecialchars($b['property_name'] ?? '') ?></td>
                    <td style="white-space:nowrap"><?= htmlspecialchars($b['checkin'] ?? '') ?><br><?= htmlspecialchars($ta['date_sep']) ?> <?= htmlspecialchars($b['checkout'] ?? '') ?></td>
                    <td><strong><?= bk_price((int)($b['total'] ?? 0)) ?></strong></td>
                    <td><span class="adm-badge adm-badge-<?= htmlspecialchars($st) ?>"><?= htmlspecialchars($ta['status_' . $st] ?? $st) ?></span></td>
                    <td style="font-size:12px;color:var(--adm-muted)"><?= isset($b['created_at']) ? date('d.m.Y H:i', strtotime($b['created_at'])) : '—' ?></td>
                    <td>
                        <form method="post" style="display:flex;gap:4px;align-items:center">
                            <input type="hidden" name="booking_id" value="<?= htmlspecialchars($b['id'] ?? '') ?>">
                            <select name="status" style="padding:4px 8px;border-radius:4px;border:1px solid var(--adm-border);font-size:12px">
                                <option value="confirmed" <?= $st === 'confirmed' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_confirmed']) ?></option>
                                <option value="pending" <?= $st === 'pending' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_pending']) ?></option>
                                <option value="cancelled" <?= $st === 'cancelled' ? 'selected' : '' ?>><?= htmlspecialchars($ta['status_cancelled']) ?></option>
                            </select>
                            <button type="submit" class="adm-btn adm-btn-primary adm-btn-sm" title="<?= htmlspecialchars($ta['save']) ?>"><i class="fas fa-check"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>