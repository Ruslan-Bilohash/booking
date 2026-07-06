<?php require_once dirname(__DIR__, 3) . '/includes/cms-contact.php'; ?>
<footer class="bks-footer">
    <div class="bks-footer-inner">
        <div class="bks-footer-grid">
            <div class="bks-footer-brand">
                <a href="<?= bks_url('index.php') ?>" class="bks-logo">
                    <span class="bks-logo-icon">B</span>
                    <span class="bks-logo-text">Booking <em>CMS</em></span>
                </a>
                <p><?= htmlspecialchars($t['intro']['text']) ?></p>
            </div>
            <div>
                <h4><?= htmlspecialchars($t['footer']['product']) ?></h4>
                <ul>
                    <li><a href="<?= bks_url('index.php#features') ?>" rel="bookmark"><?= htmlspecialchars($t['nav']['features']) ?></a></li>
                    <li><a href="<?= bks_url('index.php#screens') ?>"><?= htmlspecialchars($t['nav']['screens']) ?></a></li>
                    <li><a href="<?= bks_url('index.php#tech') ?>"><?= htmlspecialchars($t['nav']['tech']) ?></a></li>
                    <li><a href="<?= bks_url('index.php#demo') ?>"><?= htmlspecialchars($t['nav']['demo']) ?></a></li>
                    <li><a href="<?= bks_url('index.php#version') ?>"><?= htmlspecialchars($t['nav']['version'] ?? 'Version') ?> (<?= htmlspecialchars(bk_version_label()) ?>)</a></li>
                </ul>
            </div>
            <div>
                <h4><?= htmlspecialchars($t['footer']['links']) ?></h4>
                <ul>
                    <li><a href="<?= bks_url('order.php') ?>"><?= htmlspecialchars($t['footer']['order_page'] ?? $t['footer']['order'] ?? 'Order') ?></a></li>
                    <li><a href="<?= bks_url('contact.php') ?>"><?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a></li>
                    <li><a href="<?= bks_demo_url() ?>" rel="related"><?= htmlspecialchars($t['footer']['demo_link'] ?? 'Live demo') ?></a></li>
                    <li><a href="<?= bks_demo_url('solutions.php') ?>"><?= htmlspecialchars($t['order']['cta_solutions'] ?? 'Solutions') ?></a></li>
                    <li><a href="<?= bks_demo_url('admin/login.php') ?>"><?= htmlspecialchars($t['demo']['admin']) ?></a></li>
                    <li><a href="https://bilohash.com/news/booking-cms.html" rel="related"><?= htmlspecialchars($t['footer']['news'] ?? 'News') ?></a></li>
                    <li><a href="https://bilohash.com/" rel="author"><?= htmlspecialchars($t['order']['cta_portfolio'] ?? 'bilohash.com') ?></a></li>
                    <li><a href="https://bilohash.com/booking/llms.txt"><?= htmlspecialchars($t['footer']['llms'] ?? 'llms.txt') ?></a></li>
                    <li><a href="https://bilohash.com/booking/sitemap.php"><?= htmlspecialchars($t['footer']['sitemap'] ?? 'Sitemap') ?></a></li>
                </ul>
            </div>
            <div>
                <h4><?= htmlspecialchars($t['footer']['legal'] ?? 'Legal') ?></h4>
                <ul>
                    <li><a href="https://bilohash.com/website/privacy-policy.php"><?= htmlspecialchars($t['footer']['privacy'] ?? 'Privacy') ?></a></li>
                    <li><a href="https://bilohash.com/website/cookies.php"><?= htmlspecialchars($t['footer']['terms'] ?? 'Terms') ?></a></li>
                </ul>
            </div>
        </div>

        <?php
        $ft = $t['footer'] ?? [];
        $eco_class_prefix = 'bks-footer-eco';
        require dirname(__DIR__, 2) . '/includes/ecosystem-footer-block.php';
        ?>

        <div class="bks-footer-bottom">
            <?= sprintf(htmlspecialchars($t['footer']['copyright']), date('Y'), bk_version_label()) ?>
        </div>
    </div>
</footer>
</body>
</html>