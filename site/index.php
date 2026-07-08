<?php
require_once __DIR__ . '/init.php';
require_once dirname(__DIR__) . '/includes/vertical-lib.php';
require_once dirname(__DIR__, 2) . '/includes/cms-contact.php';
$canonical = $site_url . '/';
require __DIR__ . '/includes/header.php';
?>

<section class="bks-hero">
    <div class="bks-hero-bg"></div>
    <div class="bks-container bks-hero-inner">
        <div class="bks-hero-content">
            <div class="bks-hero-badges">
                <span class="bks-badge"><?= htmlspecialchars($t['hero']['badge']) ?></span>
                <span class="bks-version-pill" title="<?= htmlspecialchars($t['hero']['version_badge'] ?? 'Version') ?>">
                    <i class="fas fa-code-branch" aria-hidden="true"></i> <?= htmlspecialchars(bk_version_label()) ?>
                </span>
            </div>
            <h1><?= htmlspecialchars($t['hero']['title']) ?></h1>
            <p class="bks-hero-sub"><?= htmlspecialchars($t['hero']['subtitle']) ?></p>
            <div class="bks-hero-cta">
                <a href="<?= bks_demo_url() ?>" class="bks-btn-primary bks-btn-lg"><i class="fas fa-play-circle"></i> <?= htmlspecialchars($t['hero']['cta_demo']) ?></a>
                <a href="<?= bks_demo_url('admin/login.php') ?>" class="bks-btn-outline bks-btn-lg"><i class="fas fa-user-shield"></i> <?= htmlspecialchars($t['hero']['cta_admin']) ?></a>
                <a href="<?= bks_url('order.php') ?>" class="bks-btn-ghost bks-btn-lg"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($t['nav']['order'] ?? '') ?></a>
                <a href="<?= bks_url('contact.php') ?>" class="bks-btn-ghost bks-btn-lg"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
            </div>
        </div>
        <div class="bks-hero-preview">
            <img src="<?= bks_screen('home.svg') ?>" alt="Booking CMS — <?= htmlspecialchars($t['screens']['items']['home']['title']) ?>" width="1200" height="720" loading="eager">
        </div>
    </div>
</section>

<section class="bks-section bks-intro">
    <div class="bks-container">
        <h2><?= htmlspecialchars($t['intro']['title']) ?></h2>
        <p class="bks-lead"><?= htmlspecialchars($t['intro']['text']) ?></p>
        <?php if (bk_use_case_slugs()): ?>
        <p class="bks-use-label"><?= htmlspecialchars($t['intro']['use_label'] ?? '') ?></p>
        <div class="bks-usecases">
            <?php foreach (bk_use_case_slugs() as $slug):
                $vdef = bk_vertical_defs()[$slug] ?? null;
                if (!$vdef) continue;
                $label = $vdef[$lang] ?? $vdef['en'] ?? $slug;
            ?>
            <a href="<?= htmlspecialchars(bks_vertical_url($slug)) ?>" class="bks-usecase bks-usecase-link" rel="related">
                <i class="fas fa-<?= htmlspecialchars($vdef['icon'] ?? 'calendar-check') ?>" aria-hidden="true"></i>
                <?= htmlspecialchars($label) ?>
            </a>
            <?php endforeach; ?>
        </div>
        <p class="bks-use-more">
            <a href="<?= htmlspecialchars(bks_demo_url('solutions.php' . ($lang !== 'no' ? '?lang=' . $lang : ''))) ?>"><?= htmlspecialchars(bk_vertical_hub_label($lang)) ?> →</a>
        </p>
        <?php endif; ?>
    </div>
</section>

<section class="bks-section" id="features">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars($t['features']['title']) ?></h2>
        <div class="bks-features-grid">
            <?php foreach ($t['features']['items'] as $f): ?>
            <article class="bks-feature-card">
                <div class="bks-feature-icon"><i class="fas fa-<?= htmlspecialchars($f['icon']) ?>"></i></div>
                <h3><?= htmlspecialchars($f['title']) ?></h3>
                <p><?= htmlspecialchars($f['desc']) ?></p>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bks-section bks-screens-section" id="screens">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars($t['screens']['title']) ?></h2>
        <p class="bks-section-sub"><?= htmlspecialchars($t['screens']['subtitle']) ?></p>
        <div class="bks-screens-grid">
            <?php foreach (bks_screens() as $scr):
                $info = bks_screen_info($scr['key']);
            ?>
            <figure class="bks-screen-card">
                <a href="<?= bks_screen($scr['file']) ?>" target="_blank" rel="noopener">
                    <img src="<?= bks_screen($scr['file']) ?>" alt="Booking CMS — <?= htmlspecialchars($info['title']) ?>" width="1200" height="720" loading="lazy">
                </a>
                <figcaption>
                    <strong><?= htmlspecialchars($info['title']) ?></strong>
                    <span><?= htmlspecialchars($info['desc']) ?></span>
                </figcaption>
            </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bks-section bks-tech-section" id="tech">
    <div class="bks-container">
        <div class="bks-tech-layout">
            <div>
                <h2 class="bks-section-title"><?= htmlspecialchars($t['tech']['title']) ?></h2>
                <ul class="bks-tech-list">
                    <?php foreach ($t['tech']['items'] as $item): ?>
                    <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bks-tech-visual">
                <img src="<?= bks_screen('admin-dashboard.svg') ?>" alt="Booking CMS Admin" width="1200" height="720" loading="lazy">
            </div>
        </div>
    </div>
</section>

<?php if (!empty($t['seo'])): ?>
<section class="bks-section bks-seo-section" id="seo">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars($t['seo']['title']) ?></h2>
        <p class="bks-section-sub"><?= htmlspecialchars($t['seo']['subtitle']) ?></p>
        <div class="bks-lighthouse-panel">
            <h3 class="bks-seo-block-title"><?= htmlspecialchars($t['seo']['lighthouse_title']) ?></h3>
            <div class="bks-lighthouse-grid">
                <?php foreach ($t['seo']['scores'] as $sc):
                    $val = (int) ($sc['value'] ?? 0);
                    $ring = min(100, max(0, $val));
                ?>
                <div class="bks-lighthouse-card <?= !empty($sc['highlight']) ? 'is-highlight' : '' ?>">
                    <div class="bks-lighthouse-ring" style="--bks-score:<?= $ring ?>">
                        <span><?= htmlspecialchars($sc['value']) ?></span>
                    </div>
                    <strong><?= htmlspecialchars($sc['label']) ?></strong>
                    <?php if (!empty($sc['note'])): ?>
                    <small><?= htmlspecialchars($sc['note']) ?></small>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($t['seo']['vitals'])): ?>
            <div class="bks-vitals-row">
                <?php foreach ($t['seo']['vitals'] as $v): ?>
                <span class="bks-vital"><em><?= htmlspecialchars($v['label']) ?></em> <?= htmlspecialchars($v['boost']) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="bks-seo-columns">
            <div class="bks-seo-col">
                <h3 class="bks-seo-block-title"><i class="fas fa-code"></i> <?= htmlspecialchars($t['seo']['markup_title']) ?></h3>
                <ul class="bks-seo-list">
                    <?php foreach ($t['seo']['markup_items'] as $item): ?>
                    <li><i class="fas fa-check"></i> <?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="bks-seo-col">
                <h3 class="bks-seo-block-title"><i class="fas fa-sliders-h"></i> <?= htmlspecialchars($t['seo']['admin_title']) ?></h3>
                <ul class="bks-seo-list">
                    <?php foreach ($t['seo']['admin_items'] as $item): ?>
                    <li><i class="fas fa-check"></i> <?= htmlspecialchars($item) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="bks-section bks-demo-section" id="demo">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars($t['demo']['title']) ?></h2>
        <div class="bks-demo-grid">
            <article class="bks-demo-card">
                <div class="bks-demo-icon"><i class="fas fa-globe"></i></div>
                <h3><?= htmlspecialchars($t['demo']['frontend']) ?></h3>
                <p><?= htmlspecialchars($t['demo']['frontend_desc']) ?></p>
                <a href="<?= bks_demo_url() ?>" class="bks-btn-primary"><?= htmlspecialchars($t['demo']['open']) ?> →</a>
            </article>
            <article class="bks-demo-card">
                <div class="bks-demo-icon admin"><i class="fas fa-user-shield"></i></div>
                <h3><?= htmlspecialchars($t['demo']['admin']) ?></h3>
                <p><?= htmlspecialchars($t['demo']['admin_desc']) ?></p>
                <code class="bks-creds">demo / bilobook2026</code>
                <a href="<?= bks_demo_url('admin/login.php') ?>" class="bks-btn-outline"><?= htmlspecialchars($t['demo']['open']) ?> →</a>
            </article>
        </div>
    </div>
</section>

<?php
require_once dirname(__DIR__) . '/includes/subscription-links.php';
$pr = $t['pricing'] ?? [];
if (!empty($pr['title'])):
?>
<section class="bks-section bks-pricing-section" id="pricing">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars((string) $pr['title']) ?></h2>
        <?php if (!empty($pr['lead'])): ?>
        <p class="bks-section-sub"><?= htmlspecialchars((string) $pr['lead']) ?></p>
        <?php endif; ?>
        <div class="bks-pricing-grid">
            <article class="bks-pricing-card">
                <h3><?= htmlspecialchars((string) ($pr['demo_title'] ?? '30-day demo')) ?></h3>
                <p class="bks-pricing-price"><?= htmlspecialchars((string) ($pr['demo_price'] ?? 'Free')) ?></p>
                <p><?= htmlspecialchars((string) ($pr['demo_desc'] ?? '')) ?></p>
            </article>
            <article class="bks-pricing-card bks-pricing-card--featured">
                <h3><?= htmlspecialchars((string) ($pr['license_title'] ?? 'BILOHASH subscription')) ?></h3>
                <p><?= htmlspecialchars((string) ($pr['license_desc'] ?? '')) ?></p>
                <a href="<?= htmlspecialchars(bk_subscription_url()) ?>" class="bks-btn-primary bks-btn-sm" <?= bk_subscription_external_attrs() ?>>
                    <?= htmlspecialchars((string) ($pr['cta'] ?? 'Subscribe')) ?>
                </a>
            </article>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="bks-section bks-version-section" id="version">
    <div class="bks-container">
        <h2 class="bks-section-title"><?= htmlspecialchars($t['version']['title'] ?? 'Product version') ?></h2>
        <div class="bks-version-card">
            <div class="bks-version-current">
                <span class="bks-version-label"><?= htmlspecialchars($t['version']['current'] ?? 'Current version') ?></span>
                <strong class="bks-version-num"><?= htmlspecialchars(bk_version_label()) ?></strong>
                <time class="bks-version-date" datetime="<?= htmlspecialchars(bk_version_date()) ?>">
                    <?= sprintf(htmlspecialchars($t['version']['released'] ?? 'Released %s'), htmlspecialchars(bk_version_date())) ?>
                </time>
                <p class="bks-version-note"><?= htmlspecialchars($t['version']['script_note'] ?? '') ?></p>
                <a href="<?= bks_demo_url('admin/login.php') ?>" class="bks-btn-outline bks-btn-sm">
                    <i class="fas fa-user-shield"></i> <?= htmlspecialchars($t['demo']['admin']) ?> — <?= htmlspecialchars(bk_version_label()) ?>
                </a>
            </div>
            <div class="bks-version-changelog">
                <h3><?= htmlspecialchars($t['version']['changelog_title'] ?? 'Changelog') ?></h3>
                <ol class="bks-changelog-list">
                    <?php foreach (bk_version_releases() as $rel):
                        $note = $t['changelog_notes'][$rel['version']] ?? '';
                    ?>
                    <li class="<?= $rel['version'] === bk_version() ? 'is-current' : '' ?>">
                        <div class="bks-changelog-head">
                            <strong><?= htmlspecialchars($rel['version']) ?></strong>
                            <time datetime="<?= htmlspecialchars($rel['date']) ?>"><?= htmlspecialchars($rel['date']) ?></time>
                        </div>
                        <?php if ($note !== ''): ?>
                        <p><?= htmlspecialchars($note) ?></p>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="bks-cta-band">
    <div class="bks-container">
        <h2><?= htmlspecialchars($t['cta']['title']) ?></h2>
        <p><?= htmlspecialchars($t['cta']['text']) ?></p>
        <div class="bks-hero-cta">
            <a href="<?= bks_url('order.php') ?>" class="bks-btn-primary bks-btn-lg"><i class="fas fa-laptop-code"></i> <?= htmlspecialchars($t['nav']['order'] ?? $t['cta']['btn']) ?></a>
            <a href="<?= bks_url('contact.php') ?>" class="bks-btn-outline bks-btn-lg"><i class="fas fa-comments"></i> <?= htmlspecialchars(cms_contact_texts('booking', $lang)['nav_discuss']) ?></a>
            <a href="https://bilohash.com/" rel="author" class="bks-btn-ghost bks-btn-lg"><i class="fas fa-globe"></i> bilohash.com</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>