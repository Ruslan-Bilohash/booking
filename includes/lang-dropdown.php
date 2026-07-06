<?php
/** Language dropdown — expects $lang, bk_langs(), bk_lang_url(); optional $lang_dropdown_variant = 'strip' | 'header' | 'mobile' */
$current = bk_langs()[$lang] ?? bk_langs()['no'];
$variant = $lang_dropdown_variant ?? '';
$idSuffix = match ($variant) {
    'header' => 'Header',
    'mobile' => 'Mobile',
    default  => '',
};
$rootClass = 'bk-lang-dropdown' . ($variant !== '' ? ' bk-lang-dropdown--' . $variant : '');
?>
<div class="<?= $rootClass ?>" id="bkLangDropdown<?= $idSuffix ?>">
    <button type="button" class="bk-lang-dropdown-btn" id="bkLangBtn<?= $idSuffix ?>" aria-expanded="false" aria-haspopup="listbox" aria-controls="bkLangMenu<?= $idSuffix ?>" aria-label="<?= htmlspecialchars($current['name']) ?>">
        <span class="bk-lang-dropdown-current">
            <?php if ($variant === 'mobile'): ?>
            <span class="bk-lang-flag-only" aria-hidden="true"><?= $current['flag'] ?></span>
            <?php else: ?>
            <span class="bk-lang-flag" aria-hidden="true"><?= $current['flag'] ?></span>
            <span class="bk-lang-code"><?= htmlspecialchars($current['label']) ?></span>
            <?php endif; ?>
        </span>
        <?php if ($variant !== 'mobile'): ?>
        <span class="bk-lang-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
        <?php endif; ?>
    </button>
    <ul class="bk-lang-dropdown-menu" id="bkLangMenu<?= $idSuffix ?>" role="listbox" hidden>
        <?php foreach (bk_langs() as $code => $info): ?>
        <li role="option">
            <a href="<?= htmlspecialchars(bk_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>" <?= $lang === $code ? 'aria-current="true"' : '' ?>>
                <span class="bk-lang-flag"><?= $info['flag'] ?></span>
                <span class="bk-lang-name"><?= htmlspecialchars($info['name']) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>