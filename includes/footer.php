<?php
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
require_once __DIR__ . '/seo.php';
$ft = $t['footer'] ?? [];
$bk_discuss = cms_contact_texts('booking', $lang)['nav_discuss'];

?>
<footer class="bk-footer" itemscope itemtype="https://schema.org/WPFooter">
    <div class="bk-footer-inner">
        <div class="bk-footer-grid">
            <div>
                <h4><?= htmlspecialchars($ft['about'] ?? 'Booking CMS') ?></h4>
                <p class="bk-footer-text"><?= htmlspecialchars($ft['demo'] ?? '') ?></p>
            </div>
            <div>
                <h4><?= htmlspecialchars($ft['crosslinks'] ?? 'Links') ?></h4>
                <ul>
                    <li><a href="<?= bk_url('order.php') ?>"><?= htmlspecialchars($ft['order_dev'] ?? 'Order development') ?></a></li>
                    <li><a href="<?= bk_url('contact.php') ?>"><?= htmlspecialchars($bk_discuss) ?></a></li>
                    <li><a href="<?= bk_url('index.php') ?>"><?= htmlspecialchars($t['about_script']['demo_btn'] ?? 'Live demo') ?></a></li>
                    <li><a href="https://bilohash.com/" rel="author"><?= htmlspecialchars($ft['portfolio'] ?? 'bilohash.com') ?></a></li>
                    <li><a href="<?= bk_url('admin/login.php') ?>"><?= htmlspecialchars($ft['admin_demo'] ?? 'Admin demo') ?></a></li>
                </ul>
            </div>
            <div>
                <h4><?= htmlspecialchars($ft['docs'] ?? 'Documentation') ?></h4>
                <ul>
                    <li><a href="<?= bk_url('site/') ?>" rel="related"><?= htmlspecialchars($ft['docs_product'] ?? $ft['product_page'] ?? 'Product page') ?></a></li>
                    <li><a href="<?= bk_url('llms.txt') ?>"><?= htmlspecialchars($ft['docs_guide'] ?? $ft['llms_txt'] ?? 'llms.txt') ?></a></li>
                    <li><a href="<?= bk_url('solutions.php') ?>"><?= htmlspecialchars($ft['docs_solutions'] ?? $ft['solutions'] ?? 'Solutions') ?></a></li>
                    <li><a href="<?= bk_url('sitemap.php') ?>"><?= htmlspecialchars($ft['sitemap'] ?? 'Sitemap') ?></a></li>
                    <li><a href="https://bilohash.com/news/booking-cms.html" rel="related"><?= htmlspecialchars($ft['news'] ?? 'News') ?></a></li>
                </ul>
            </div>
            <div>
                <h4><?= htmlspecialchars($ft['legal'] ?? 'Legal') ?></h4>
                <ul>
                    <li><a href="https://bilohash.com/website/privacy-policy.php"><?= htmlspecialchars($ft['privacy'] ?? 'Privacy') ?></a></li>
                    <li><a href="https://bilohash.com/website/cookies.php"><?= htmlspecialchars($ft['terms'] ?? 'Terms') ?></a></li>
                </ul>
            </div>
        </div>

        <?php require __DIR__ . '/ecosystem-footer-block.php'; ?>

        <div class="bk-footer-bottom">
            <?= sprintf(htmlspecialchars($ft['copyright'] ?? '© %s Booking CMS Demo.'), date('Y')) ?>
        </div>
    </div>
</footer>
<?php if (!empty($seo_schemas)): bk_render_seo_schemas($seo_schemas); endif; ?>
<script src="<?= htmlspecialchars(bk_asset('js/main.js')) ?>?v=14" defer></script>
<?php bk_render_chat_widget(bk_site_settings(), $lang ?? 'en'); ?>
</body>
</html>