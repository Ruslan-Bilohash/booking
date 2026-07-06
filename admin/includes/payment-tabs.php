<?php
/** @var callable $adminUrlFn @var array $ta @var string $payment_tab */
require_once dirname(__DIR__, 2) . '/includes/payment-settings.php';
$tp = $ta['payments_page'] ?? [];
$tabLabels = $tp['tabs'] ?? [];
?>
<div class="adm-settings-jump adm-settings-jump--payment adm-settings-jump--desktop">
    <label class="adm-settings-jump-label" for="admPaymentJump">
        <i class="fas fa-credit-card" aria-hidden="true"></i>
        <?= htmlspecialchars($tp['nav_label']) ?>
    </label>
    <div class="adm-settings-jump-wrap">
        <select id="admPaymentJump" class="adm-settings-jump-select" aria-label="<?= htmlspecialchars($tp['nav_label']) ?>">
            <?php foreach (bk_payment_tabs() as $key => $meta):
                $label = $tabLabels[$key] ?? ucfirst($key);
                $url = $adminUrlFn('settings-payments.php?tab=' . urlencode($key));
            ?>
            <option value="<?= htmlspecialchars($url) ?>" <?= ($payment_tab ?? '') === $key ? 'selected' : '' ?>><?= htmlspecialchars($label) ?></option>
            <?php endforeach; ?>
        </select>
        <i class="fas fa-chevron-down adm-settings-jump-icon" aria-hidden="true"></i>
    </div>
</div>
<nav class="adm-payment-mobile-nav" aria-label="<?= htmlspecialchars($tp['nav_label']) ?>">
    <?php foreach (bk_payment_tabs() as $key => $meta):
        $label = $tabLabels[$key] ?? ucfirst($key);
        $url = $adminUrlFn('settings-payments.php?tab=' . urlencode($key));
        $active = ($payment_tab ?? '') === $key;
    ?>
    <a href="<?= htmlspecialchars($url) ?>" class="adm-payment-mobile-link adm-payment-mobile-link--<?= htmlspecialchars($key) ?> <?= $active ? 'active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
        <?= bk_payment_method_icon_html($key) ?>
        <span><?= htmlspecialchars($label) ?></span>
    </a>
    <?php endforeach; ?>
</nav>
<script>
(function () {
    var sel = document.getElementById('admPaymentJump');
    if (!sel) return;
    sel.addEventListener('change', function () {
        if (sel.value) window.location.href = sel.value;
    });
})();
</script>