<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
require_once dirname(__DIR__) . '/includes/storage.php';

$admin_page = 'reviews';
$flash = '';
$properties = bk_load_properties_raw();
$propsById = [];
foreach ($properties as $p) {
    $propsById[$p['id']] = $p;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = trim($_POST['id'] ?? '');

    if ($action === 'add') {
        $propertyId = trim($_POST['property_id'] ?? '');
        $guestName = trim($_POST['guest_name'] ?? '');
        $rating = (float) ($_POST['rating'] ?? 0);
        $textEn = trim($_POST['text_en'] ?? '');
        if ($propertyId === '' || $guestName === '' || $textEn === '' || $rating < 1) {
            $flash = 'error';
        } else {
            $ok = bk_add_review([
                'property_id' => $propertyId,
                'guest_name'  => $guestName,
                'rating'      => round($rating, 1),
                'trip_type'   => $_POST['trip_type'] ?? 'couple',
                'stay_month'  => trim($_POST['stay_month'] ?? date('Y-m')),
                'approved'    => !empty($_POST['approved']),
                'source'      => 'admin',
                'title'       => ['en' => trim($_POST['title_en'] ?? ''), 'no' => trim($_POST['title_no'] ?? ''), 'uk' => trim($_POST['title_uk'] ?? '')],
                'text'        => ['en' => $textEn, 'no' => trim($_POST['text_no'] ?? $textEn), 'uk' => trim($_POST['text_uk'] ?? $textEn)],
                'country'     => ['en' => trim($_POST['country_en'] ?? ''), 'no' => trim($_POST['country_no'] ?? ''), 'uk' => trim($_POST['country_uk'] ?? '')],
            ]);
            $flash = $ok ? 'success' : 'error';
        }
    } elseif ($id !== '' && $action === 'approve') {
        $flash = bk_update_review($id, ['approved' => true]) ? 'success' : 'error';
    } elseif ($id !== '' && $action === 'reject') {
        $flash = bk_update_review($id, ['approved' => false]) ? 'success' : 'error';
    } elseif ($id !== '' && $action === 'delete') {
        $flash = bk_delete_review($id) ? 'success' : 'error';
    }
}

$filterProperty = trim($_GET['property'] ?? '');
$filterStatus = $_GET['status'] ?? 'all';
$reviews = bk_load_reviews_raw();
usort($reviews, static fn(array $a, array $b): int => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

if ($filterProperty !== '') {
    $reviews = array_values(array_filter($reviews, static fn(array $r): bool => ($r['property_id'] ?? '') === $filterProperty));
}
if ($filterStatus === 'pending') {
    $reviews = array_values(array_filter($reviews, static fn(array $r): bool => empty($r['approved'])));
} elseif ($filterStatus === 'approved') {
    $reviews = array_values(array_filter($reviews, static fn(array $r): bool => !empty($r['approved'])));
}

$page_title = $ta['guest_reviews'];
require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-filter"></i> <?= htmlspecialchars($ta['filter_reviews']) ?></h2></div>
    <div class="adm-card-body padded">
        <form method="get" class="adm-form-inline">
            <div class="adm-field">
                <label><?= htmlspecialchars($ta['property']) ?></label>
                <select name="property">
                    <option value=""><?= htmlspecialchars($ta['all_properties']) ?></option>
                    <?php foreach ($properties as $p): ?>
                    <option value="<?= htmlspecialchars($p['id']) ?>" <?= $filterProperty === $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['name']['en'] ?? $p['id']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="adm-field">
                <label><?= htmlspecialchars($ta['status']) ?></label>
                <select name="status">
                    <option value="all" <?= $filterStatus === 'all' ? 'selected' : '' ?>><?= htmlspecialchars($ta['all_statuses']) ?></option>
                    <option value="pending" <?= $filterStatus === 'pending' ? 'selected' : '' ?>><?= htmlspecialchars($ta['review_pending']) ?></option>
                    <option value="approved" <?= $filterStatus === 'approved' ? 'selected' : '' ?>><?= htmlspecialchars($ta['review_approved']) ?></option>
                </select>
            </div>
            <button type="submit" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['apply_filter']) ?></button>
        </form>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-plus"></i> <?= htmlspecialchars($ta['add_review']) ?></h2></div>
    <div class="adm-card-body padded">
        <form method="post">
            <input type="hidden" name="action" value="add">
            <div class="adm-form-grid">
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['property']) ?></label>
                    <select name="property_id" required>
                        <?php foreach ($properties as $p): ?>
                        <option value="<?= htmlspecialchars($p['id']) ?>"><?= htmlspecialchars($p['name']['en'] ?? $p['id']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['guest']) ?></label>
                    <input type="text" name="guest_name" required maxlength="80">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['rating']) ?></label>
                    <input type="number" name="rating" min="1" max="10" step="0.1" value="9" required>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['review_stay_month']) ?></label>
                    <input type="month" name="stay_month" value="<?= date('Y-m') ?>">
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['review_text_en']) ?></label>
                    <textarea name="text_en" rows="3" required></textarea>
                </div>
                <div class="adm-field adm-field-full">
                    <label><?= htmlspecialchars($ta['review_title_en']) ?></label>
                    <input type="text" name="title_en" maxlength="120">
                </div>
                <div class="adm-field adm-check adm-field-full">
                    <input type="checkbox" name="approved" id="revApproved" value="1" checked>
                    <label for="revApproved"><?= htmlspecialchars($ta['review_publish_now']) ?></label>
                </div>
            </div>
            <div class="adm-form-actions">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
            </div>
        </form>
    </div>
</div>

<div class="adm-card">
    <div class="adm-card-head"><h2><i class="fas fa-star"></i> <?= htmlspecialchars($ta['guest_reviews']) ?> (<?= count($reviews) ?>)</h2></div>
    <div class="adm-card-body adm-card-body--flush">
        <?php if ($reviews === []): ?>
        <p class="adm-empty-state"><?= htmlspecialchars($ta['no_reviews']) ?></p>
        <?php else: ?>
        <div class="adm-table-wrap">
            <table class="adm-table adm-table--cards">
                <thead>
                    <tr>
                        <th><?= htmlspecialchars($ta['property']) ?></th>
                        <th><?= htmlspecialchars($ta['guest']) ?></th>
                        <th><?= htmlspecialchars($ta['rating']) ?></th>
                        <th><?= htmlspecialchars($ta['status']) ?></th>
                        <th><?= htmlspecialchars($ta['created']) ?></th>
                        <th><?= htmlspecialchars($ta['actions']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $rev): ?>
                    <?php $prop = $propsById[$rev['property_id'] ?? ''] ?? null; ?>
                    <tr>
                        <td data-label="<?= htmlspecialchars($ta['property']) ?>"><?= htmlspecialchars($prop['name']['en'] ?? ($rev['property_id'] ?? '—')) ?></td>
                        <td data-label="<?= htmlspecialchars($ta['guest']) ?>">
                            <div class="adm-review-guest">
                                <strong><?= htmlspecialchars($rev['guest_name'] ?? '') ?></strong>
                                <div class="adm-review-snippet"><?= htmlspecialchars(mb_substr($rev['text']['en'] ?? '', 0, 80)) ?>…</div>
                            </div>
                        </td>
                        <td data-label="<?= htmlspecialchars($ta['rating']) ?>"><?= number_format((float)($rev['rating'] ?? 0), 1) ?></td>
                        <td data-label="<?= htmlspecialchars($ta['status']) ?>">
                            <?php if (!empty($rev['approved'])): ?>
                            <span class="adm-badge adm-badge-confirmed"><?= htmlspecialchars($ta['review_approved']) ?></span>
                            <?php else: ?>
                            <span class="adm-badge adm-badge-pending"><?= htmlspecialchars($ta['review_pending']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="adm-review-date" data-label="<?= htmlspecialchars($ta['created']) ?>"><?= htmlspecialchars(substr($rev['created_at'] ?? '', 0, 10)) ?></td>
                        <td class="adm-table-cell--actions" data-label="<?= htmlspecialchars($ta['actions']) ?>">
                            <div class="adm-review-actions">
                                <?php if (empty($rev['approved'])): ?>
                                <form method="post" class="adm-inline-form"><input type="hidden" name="action" value="approve"><input type="hidden" name="id" value="<?= htmlspecialchars($rev['id'] ?? '') ?>"><button type="submit" class="adm-btn adm-btn-sm adm-btn-primary"><?= htmlspecialchars($ta['approve']) ?></button></form>
                                <?php else: ?>
                                <form method="post" class="adm-inline-form"><input type="hidden" name="action" value="reject"><input type="hidden" name="id" value="<?= htmlspecialchars($rev['id'] ?? '') ?>"><button type="submit" class="adm-btn adm-btn-sm adm-btn-outline"><?= htmlspecialchars($ta['hide']) ?></button></form>
                                <?php endif; ?>
                                <form method="post" class="adm-inline-form" onsubmit="return confirm(<?= json_encode($ta['delete_confirm'], JSON_UNESCAPED_UNICODE) ?>)"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= htmlspecialchars($rev['id'] ?? '') ?>"><button type="submit" class="adm-btn adm-btn-sm adm-btn-danger"><?= htmlspecialchars($ta['delete']) ?></button></form>
                            </div>
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