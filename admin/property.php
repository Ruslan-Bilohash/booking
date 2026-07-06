<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
$admin_page = 'properties';

$id = $_GET['id'] ?? '';
$list = bk_load_properties_raw();
$idx = null;
$property = null;
foreach ($list as $i => $p) {
    if ($p['id'] === $id) {
        $idx = $i;
        $property = $p;
        break;
    }
}
if ($property === null) {
    header('Location: ' . bk_admin_url('properties.php'), true, 302);
    exit;
}

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list[$idx]['price']   = max(0, (int)($_POST['price'] ?? $property['price']));
    $list[$idx]['deal']    = max(0, min(90, (int)($_POST['deal'] ?? 0)));
    $list[$idx]['rating']  = max(1, min(10, (float)($_POST['rating'] ?? $property['rating'])));
    $list[$idx]['reviews'] = max(0, (int)($_POST['reviews'] ?? $property['reviews']));
    $list[$idx]['active']  = !empty($_POST['active']);
    $list[$idx]['type']    = in_array($_POST['type'] ?? '', ['hotel', 'apartment', 'cabin'], true) ? $_POST['type'] : $property['type'];
    foreach (['en', 'no', 'uk'] as $lc) {
        if (isset($_POST['name_' . $lc])) {
            $list[$idx]['name'][$lc] = trim($_POST['name_' . $lc]);
        }
        if (isset($_POST['city_' . $lc])) {
            $list[$idx]['city'][$lc] = trim($_POST['city_' . $lc]);
        }
        if (isset($_POST['country_' . $lc])) {
            $list[$idx]['country'][$lc] = trim($_POST['country_' . $lc]);
        }
    }
    $amenities = [];
    foreach (bk_amenity_keys() as $key) {
        if (!empty($_POST['amenity_' . $key])) {
            $amenities[] = $key;
        }
    }
    $list[$idx]['amenities'] = $amenities;
    if (bk_save_properties($list)) {
        $flash = 'success';
        $property = $list[$idx];
    } else {
        $flash = 'error';
    }
}

$page_title = $ta['edit'] . ': ' . ($property['name']['en'] ?? $id);
require __DIR__ . '/includes/layout.php';
?>

<?php if ($flash === 'success'): ?>
<div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($ta['saved']) ?></div>
<?php elseif ($flash === 'error'): ?>
<div class="adm-alert adm-alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($ta['error']) ?></div>
<?php endif; ?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($property['name']['en'] ?? $id) ?></h2>
        <a href="<?= bk_admin_url('properties.php') ?>" class="adm-btn adm-btn-outline adm-btn-sm"><?= htmlspecialchars($ta['cancel']) ?></a>
    </div>
    <div class="adm-card-body padded">
        <form method="post">
            <div class="adm-property-preview">
                <img src="<?= htmlspecialchars(bk_property_image($property)) ?>" alt="" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';">
                <div class="adm-property-meta">
                    <strong><?= htmlspecialchars($ta['property_id']) ?>:</strong> <?= htmlspecialchars($property['id']) ?><br>
                    <?= htmlspecialchars($property['city']['en'] ?? '') ?>, <?= htmlspecialchars($property['country']['en'] ?? '') ?>
                </div>
            </div>

            <div class="adm-form-grid adm-form-grid--settings">
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['name_en']) ?></label>
                    <input type="text" name="name_en" value="<?= htmlspecialchars($property['name']['en'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['name_no']) ?></label>
                    <input type="text" name="name_no" value="<?= htmlspecialchars($property['name']['no'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['name_uk']) ?></label>
                    <input type="text" name="name_uk" value="<?= htmlspecialchars($property['name']['uk'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_city_en']) ?></label>
                    <input type="text" name="city_en" value="<?= htmlspecialchars($property['city']['en'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_city_no']) ?></label>
                    <input type="text" name="city_no" value="<?= htmlspecialchars($property['city']['no'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_city_uk']) ?></label>
                    <input type="text" name="city_uk" value="<?= htmlspecialchars($property['city']['uk'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_country_en']) ?></label>
                    <input type="text" name="country_en" value="<?= htmlspecialchars($property['country']['en'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_country_no']) ?></label>
                    <input type="text" name="country_no" value="<?= htmlspecialchars($property['country']['no'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['seo_country_uk']) ?></label>
                    <input type="text" name="country_uk" value="<?= htmlspecialchars($property['country']['uk'] ?? '') ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['type']) ?></label>
                    <select name="type">
                        <?php foreach (['hotel', 'apartment', 'cabin'] as $tp): ?>
                        <option value="<?= $tp ?>" <?= $property['type'] === $tp ? 'selected' : '' ?>><?= htmlspecialchars($t['types'][$tp]) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['price']) ?></label>
                    <input type="number" name="price" min="0" step="50" value="<?= (int)$property['price'] ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['deal']) ?></label>
                    <input type="number" name="deal" min="0" max="90" value="<?= (int)($property['deal'] ?? 0) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['rating']) ?></label>
                    <input type="number" name="rating" min="1" max="10" step="0.1" value="<?= htmlspecialchars((string)$property['rating']) ?>">
                </div>
                <div class="adm-field">
                    <label><?= htmlspecialchars($ta['reviews']) ?></label>
                    <input type="number" name="reviews" min="0" value="<?= (int)$property['reviews'] ?>">
                    <small class="adm-field-hint-inline"><?= htmlspecialchars($ta['reviews_sync_hint']) ?></small>
                </div>
            </div>

            <div class="adm-field adm-field-full">
                <label><?= htmlspecialchars($ta['amenities']) ?></label>
                <div class="adm-amenity-grid">
                    <?php $propAm = $property['amenities'] ?? []; ?>
                    <?php foreach (bk_amenity_keys() as $amKey): ?>
                    <label class="adm-check">
                        <input type="checkbox" name="amenity_<?= htmlspecialchars($amKey) ?>" value="1" <?= in_array($amKey, $propAm, true) ? 'checked' : '' ?>>
                        <i class="fas <?= htmlspecialchars(bk_amenity_icon($amKey)) ?>" aria-hidden="true"></i>
                        <?= htmlspecialchars($t['amenities'][$amKey] ?? $amKey) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="adm-field adm-check">
                <input type="checkbox" name="active" id="active" value="1" <?= ($property['active'] ?? true) ? 'checked' : '' ?>>
                <label for="active" style="margin:0"><?= htmlspecialchars($ta['active']) ?></label>
            </div>

            <div class="adm-form-actions adm-form-actions-sticky">
                <button type="submit" class="adm-btn adm-btn-primary"><i class="fas fa-save"></i> <?= htmlspecialchars($ta['save']) ?></button>
                <a href="<?= bk_url('property.php?id=' . urlencode($property['id'])) ?>" class="adm-btn adm-btn-outline" target="_blank"><i class="fas fa-eye"></i> <?= htmlspecialchars($ta['view_site']) ?></a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>