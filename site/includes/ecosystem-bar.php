<?php
if (empty($t['ecosystem']['items'])) {
    return;
}
?>
<div class="bks-ecosystem-bar" role="navigation" aria-label="<?= htmlspecialchars($t['ecosystem']['strip_label'] ?? 'Ecosystem') ?>">
    <div class="bks-ecosystem-bar-inner">
        <span class="bks-ecosystem-label"><?= htmlspecialchars($t['ecosystem']['strip_label'] ?? 'Bilohash ecosystem') ?>:</span>
        <?php foreach ($t['ecosystem']['items'] as $eco): ?>
        <a href="<?= htmlspecialchars($eco['demo']) ?>" rel="related" title="<?= htmlspecialchars($eco['name']) ?>"><?= htmlspecialchars($eco['short'] ?? $eco['name']) ?></a>
        <?php endforeach; ?>
    </div>
</div>