<?php
$current = bks_langs()[$lang] ?? bks_langs()['no'];
?>
<details class="bks-lang-details" id="bksLangDetails">
    <summary class="bks-lang-dropdown-btn" id="bksLangBtn" aria-label="<?= htmlspecialchars($t['a11y']['language'] ?? 'Language') ?>">
        <span class="bks-lang-current">
            <span class="bks-lang-flag" aria-hidden="true"><?= $current['flag'] ?></span>
            <span class="bks-lang-code"><?= htmlspecialchars($current['label']) ?></span>
        </span>
        <span class="bks-lang-chevron" aria-hidden="true"><i class="fas fa-chevron-down"></i></span>
    </summary>
    <ul class="bks-lang-dropdown-menu" id="bksLangMenu" role="listbox">
        <?php foreach (bks_langs() as $code => $info): ?>
        <li role="option">
            <a href="<?= htmlspecialchars(bks_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>" <?= $lang === $code ? 'aria-current="true"' : '' ?>>
                <span><?= $info['flag'] ?></span>
                <span><?= htmlspecialchars($info['name']) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</details>