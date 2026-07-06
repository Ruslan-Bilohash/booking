<?php
/** @var callable $adminUrlFn @var array $ta */
require_once dirname(__DIR__, 3) . '/includes/site-settings.php';

$currentValue = '';
$currentLabel = '';
$groups = bk_settings_nav_groups();

foreach ($groups as $group) {
    foreach ($group['items'] as $item) {
        $type = $item['type'] ?? 'tab';
        $key = $item['key'] ?? '';
        if ($key === '' || !bk_settings_nav_item_active($type, $key)) {
            continue;
        }
        $currentValue = bk_settings_nav_item_url($type, $key, $adminUrlFn);
        $currentLabel = bk_settings_nav_item_label($type, $key, $ta);
        break 2;
    }
}

if ($currentValue === '' && isset(bk_settings_tabs()['appearance'])) {
    $currentValue = bk_settings_nav_item_url('tab', 'appearance', $adminUrlFn);
    $currentLabel = bk_settings_nav_item_label('tab', 'appearance', $ta);
}
?>
<div class="adm-settings-jump adm-settings-jump--desktop">
    <label class="adm-settings-jump-label" for="admSettingsJump">
        <i class="fas fa-sliders-h" aria-hidden="true"></i>
        <?= htmlspecialchars(bk_settings_admin_label('settings_jump_label', $ta)) ?>
    </label>
    <div class="adm-settings-jump-wrap">
        <select id="admSettingsJump" class="adm-settings-jump-select" aria-label="<?= htmlspecialchars(bk_settings_admin_label('settings_nav_aria', $ta)) ?>">
            <?php foreach ($groups as $group): ?>
            <optgroup label="<?= htmlspecialchars(bk_settings_admin_label($group['label'], $ta)) ?>">
                <?php foreach ($group['items'] as $item):
                    $type = $item['type'] ?? 'tab';
                    $key = $item['key'] ?? '';
                    if ($key === '') {
                        continue;
                    }
                    $url = bk_settings_nav_item_url($type, $key, $adminUrlFn);
                    $label = bk_settings_nav_item_label($type, $key, $ta);
                    $selected = bk_settings_nav_item_active($type, $key);
                ?>
                <option value="<?= htmlspecialchars($url) ?>" <?= $selected ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
                <?php endforeach; ?>
            </optgroup>
            <?php endforeach; ?>
        </select>
        <i class="fas fa-chevron-down adm-settings-jump-icon" aria-hidden="true"></i>
    </div>
    <?php if ($currentLabel !== ''): ?>
    <span class="adm-settings-jump-current"><?= htmlspecialchars($currentLabel) ?></span>
    <?php endif; ?>
</div>

<nav class="adm-settings-mobile-nav" aria-label="<?= htmlspecialchars(bk_settings_admin_label('settings_nav_aria', $ta)) ?>">
    <?php foreach ($groups as $group):
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
    <details class="adm-settings-mobile-group" <?= $groupHasActive ? 'open' : '' ?>>
        <summary class="adm-settings-mobile-summary">
            <span><?= htmlspecialchars(bk_settings_admin_label($group['label'], $ta)) ?></span>
            <i class="fas fa-chevron-down" aria-hidden="true"></i>
        </summary>
        <ul class="adm-settings-mobile-list">
            <?php foreach ($group['items'] as $item):
                $type = $item['type'] ?? 'tab';
                $key = $item['key'] ?? '';
                if ($key === '') {
                    continue;
                }
                $url = bk_settings_nav_item_url($type, $key, $adminUrlFn);
                $label = bk_settings_nav_item_label($type, $key, $ta);
                $icon = bk_settings_nav_item_icon($type, $key);
                $active = bk_settings_nav_item_active($type, $key);
            ?>
            <li>
                <a href="<?= htmlspecialchars($url) ?>" class="adm-settings-mobile-link <?= $active ? 'active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
                    <i class="<?= htmlspecialchars(str_contains($icon, ' ') ? $icon : 'fas fa-' . $icon) ?>" aria-hidden="true"></i>
                    <span><?= htmlspecialchars($label) ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </details>
    <?php endforeach; ?>
</nav>
<script>
(function () {
    var sel = document.getElementById('admSettingsJump');
    if (!sel) return;
    sel.addEventListener('change', function () {
        if (sel.value) window.location.href = sel.value;
    });
})();
</script>