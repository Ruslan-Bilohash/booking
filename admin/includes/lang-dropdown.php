<?php
/** Admin language dropdown — expects $lang, bk_langs(), bk_admin_lang_url() */
$current = bk_langs()[$lang] ?? bk_langs()['no'];
?>
<div class="adm-lang-dropdown" id="admLangDropdown">
    <button type="button" class="adm-lang-dropdown-btn" id="admLangBtn" aria-expanded="false" aria-haspopup="listbox" aria-controls="admLangMenu">
        <span class="adm-lang-dropdown-flag"><?= $current['flag'] ?></span>
        <span class="adm-lang-dropdown-label"><?= htmlspecialchars($current['label']) ?></span>
        <i class="fas fa-chevron-down" aria-hidden="true"></i>
    </button>
    <ul class="adm-lang-dropdown-menu" id="admLangMenu" role="listbox" hidden>
        <?php foreach (bk_langs() as $code => $info): ?>
        <li role="option">
            <a href="<?= htmlspecialchars(bk_admin_lang_url($code)) ?>" class="<?= $lang === $code ? 'active' : '' ?>" <?= $lang === $code ? 'aria-current="true"' : '' ?>>
                <span><?= $info['flag'] ?></span>
                <span><?= htmlspecialchars($info['name']) ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>