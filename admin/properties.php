<?php
require_once __DIR__ . '/init.php';
bk_admin_require();
$admin_page = 'properties';
$page_title = $ta['properties'];
$properties = bk_properties_all();

require __DIR__ . '/includes/layout.php';
?>

<div class="adm-card">
    <div class="adm-card-head">
        <h2><?= htmlspecialchars($ta['properties']) ?> (<?= count($properties) ?>)</h2>
    </div>
    <div class="adm-card-body">
        <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th></th>
                    <th><?= htmlspecialchars($ta['name_en']) ?></th>
                    <th><?= htmlspecialchars($ta['type']) ?></th>
                    <th><?= htmlspecialchars($ta['price']) ?></th>
                    <th><?= htmlspecialchars($ta['rating']) ?></th>
                    <th><?= htmlspecialchars($ta['status']) ?></th>
                    <th><?= htmlspecialchars($ta['actions']) ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $p): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars(bk_property_image($p)) ?>" alt="" class="adm-thumb" loading="lazy" onerror="this.onerror=null;this.src='<?= htmlspecialchars(bk_placeholder_image()) ?>';"></td>
                    <td>
                        <strong><?= htmlspecialchars($p['name']['en'] ?? $p['id']) ?></strong><br>
                        <small style="color:var(--adm-muted)"><?= htmlspecialchars($p['city']['en'] ?? '') ?>, <?= htmlspecialchars($p['country']['en'] ?? '') ?></small>
                    </td>
                    <td><?= htmlspecialchars($t['types'][$p['type']] ?? $p['type']) ?></td>
                    <td><strong><?= bk_price((int)$p['price']) ?></strong>
                        <?php if (!empty($p['deal'])): ?><br><small style="color:var(--adm-green)">−<?= (int)$p['deal'] ?>%</small><?php endif; ?>
                    </td>
                    <td><?= number_format($p['rating'], 1) ?> <small style="color:var(--adm-muted)">(<?= number_format($p['reviews']) ?>)</small></td>
                    <td>
                        <?php if (($p['active'] ?? true) !== false): ?>
                        <span class="adm-badge adm-badge-active"><?= htmlspecialchars($ta['active']) ?></span>
                        <?php else: ?>
                        <span class="adm-badge adm-badge-hidden"><?= htmlspecialchars($ta['inactive']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= bk_admin_url('property.php?id=' . urlencode($p['id'])) ?>" class="adm-btn adm-btn-outline adm-btn-sm">
                            <i class="fas fa-pen"></i> <?= htmlspecialchars($ta['edit']) ?>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>