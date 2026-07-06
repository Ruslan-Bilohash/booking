<?php
/** @var array $ta @var string $admin_page */
require_once dirname(__DIR__, 2) . '/includes/site-settings.php';
$isSettings = ($admin_page ?? '') === 'settings';
$adminUrlFn = 'bk_admin_url';
$settingsDesc = $ta['nav_desc_settings'] ?? '';
?>
<div class="adm-nav-group <?= $isSettings ? 'is-open' : '' ?>" id="admSettingsNav">
    <button type="button"
            class="adm-nav-group-btn <?= $isSettings ? 'active' : '' ?>"
            id="admSettingsNavBtn"
            aria-expanded="<?= $isSettings ? 'true' : 'false' ?>"
            aria-controls="admSettingsNavSub">
        <i class="fas fa-cog" aria-hidden="true"></i>
        <span class="adm-nav-link-text">
            <span class="adm-nav-link-label"><?= htmlspecialchars($ta['settings']) ?></span>
            <?php if ($settingsDesc !== ''): ?>
            <span class="adm-nav-link-desc"><?= htmlspecialchars($settingsDesc) ?></span>
            <?php endif; ?>
        </span>
        <i class="fas fa-chevron-down adm-nav-chevron" aria-hidden="true"></i>
    </button>
    <div class="adm-nav-sub" id="admSettingsNavSub"<?= $isSettings ? '' : ' hidden' ?>>
        <?php foreach (bk_settings_nav_groups() as $group):
            $groupHasActive = false;
            foreach ($group['items'] as $item) {
                $type = $item['type'] ?? 'tab';
                $key = $item['key'] ?? '';
                if ($key !== '' && bk_settings_nav_item_active($type, $key)) {
                    $groupHasActive = true;
                    break;
                }
            }
        ?>
        <div class="adm-nav-sub-group <?= $groupHasActive ? 'is-open' : '' ?>">
            <button type="button"
                    class="adm-nav-sub-toggle"
                    aria-expanded="<?= $groupHasActive ? 'true' : 'false' ?>">
                <span class="adm-nav-sub-label"><?= htmlspecialchars(bk_settings_admin_label($group['label'], $ta)) ?></span>
                <i class="fas fa-chevron-down adm-nav-sub-chevron" aria-hidden="true"></i>
            </button>
            <div class="adm-nav-sub-items">
                <?php foreach ($group['items'] as $item):
                    $type = $item['type'] ?? 'tab';
                    $key = $item['key'] ?? '';
                    if ($key === '') {
                        continue;
                    }
                    $active = bk_settings_nav_item_active($type, $key);
                    $icon = bk_settings_nav_item_icon($type, $key);
                    $itemDesc = bk_settings_nav_item_desc($type, $key, $ta);
                ?>
                <a href="<?= htmlspecialchars(bk_settings_nav_item_url($type, $key, $adminUrlFn)) ?>"
                   class="adm-nav-sub-link <?= $active ? 'active' : '' ?>">
                    <i class="<?= htmlspecialchars(str_contains($icon, ' ') ? $icon : 'fas fa-' . $icon) ?>" aria-hidden="true"></i>
                    <span class="adm-nav-link-text">
                        <span class="adm-nav-link-label"><?= htmlspecialchars(bk_settings_nav_item_label($type, $key, $ta)) ?></span>
                        <?php if ($itemDesc !== ''): ?>
                        <span class="adm-nav-link-desc adm-nav-link-desc--sub"><?= htmlspecialchars($itemDesc) ?></span>
                        <?php endif; ?>
                    </span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>