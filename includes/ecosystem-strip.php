<?php
if (empty($t['ecosystem']['items'])) {
    return;
}
$eco = $t['ecosystem'];
?>
<section class="bk-ecosystem-section" id="ecosystem">
    <div class="bk-container">
        <div class="bk-section-head bk-section-head-center">
            <div>
                <h2 class="bk-section-title"><?= htmlspecialchars($eco['title']) ?></h2>
                <p class="bk-section-sub"><?= htmlspecialchars($eco['subtitle']) ?></p>
            </div>
        </div>
        <div class="bk-ecosystem-grid">
            <?php foreach ($eco['items'] as $item): ?>
            <article class="bk-ecosystem-card">
                <div class="bk-ecosystem-icon">
                    <?php if (($item['icon'] ?? '') === 'wordpress'): ?>
                    <i class="fab fa-wordpress" aria-hidden="true"></i>
                    <?php else: ?>
                    <i class="fas fa-<?= htmlspecialchars($item['icon']) ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                </div>
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p><?= htmlspecialchars($item['desc']) ?></p>
                <div class="bk-ecosystem-links">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="bk-btn-outline bk-btn-sm" rel="related"><?= htmlspecialchars($eco['product_btn']) ?></a>
                    <a href="<?= htmlspecialchars($item['demo']) ?>" class="bk-btn-primary bk-btn-sm" rel="related"><?= htmlspecialchars($eco['demo_btn']) ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>