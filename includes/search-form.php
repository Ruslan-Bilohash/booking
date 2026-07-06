<?php
$p = $search_params ?? bk_search_params();
$guests_label = $p['adults'] . ' + ' . $p['children'] . ' · ' . $p['rooms'];
?>
<form class="bk-search" action="<?= bk_url('search.php') ?>" method="get" role="search">
    <div class="bk-search-field" style="flex:2 1 220px">
        <label for="destination"><?= htmlspecialchars($t['search']['destination']) ?></label>
        <input type="text" id="destination" name="destination" value="<?= htmlspecialchars($p['destination']) ?>"
               placeholder="<?= htmlspecialchars($t['search']['placeholder']) ?>" autocomplete="off">
    </div>
    <div class="bk-search-field">
        <label for="checkin"><?= htmlspecialchars($t['search']['checkin']) ?></label>
        <input type="date" id="checkin" name="checkin" value="<?= htmlspecialchars($p['checkin']) ?>"
               min="<?= date('Y-m-d') ?>">
    </div>
    <div class="bk-search-field">
        <label for="checkout"><?= htmlspecialchars($t['search']['checkout']) ?></label>
        <input type="date" id="checkout" name="checkout" value="<?= htmlspecialchars($p['checkout']) ?>"
               min="<?= htmlspecialchars($p['checkin']) ?>">
    </div>
    <?php
    $a11y = $t['a11y'] ?? [];
    $dec = $a11y['decrease'];
    $inc = $a11y['increase'];
    ?>
    <div class="bk-search-field guests-field">
        <label id="guestsLabel" for="guestsToggle"><?= htmlspecialchars($t['search']['guests']) ?></label>
        <button type="button" class="bk-guests-toggle" id="guestsToggle" aria-expanded="false" aria-controls="guestsPopup" aria-labelledby="guestsLabel guestsDisplay">
            <span id="guestsDisplay"><?= htmlspecialchars($guests_label) ?></span>
        </button>
        <div class="bk-guests-popup" id="guestsPopup" role="dialog" aria-label="<?= htmlspecialchars($t['search']['guests']) ?>">
            <div class="bk-guest-row">
                <span id="guestsAdultsLabel"><?= htmlspecialchars($t['search']['adults']) ?></span>
                <div class="bk-counter" role="group" aria-labelledby="guestsAdultsLabel">
                    <button type="button" data-counter="adultsInput" data-delta="-1" data-min="1" aria-label="<?= htmlspecialchars($dec . ' ' . $t['search']['adults']) ?>">−</button>
                    <span id="adultsVal" aria-live="polite"><?= (int)$p['adults'] ?></span>
                    <button type="button" data-counter="adultsInput" data-delta="1" data-max="20" aria-label="<?= htmlspecialchars($inc . ' ' . $t['search']['adults']) ?>">+</button>
                </div>
            </div>
            <div class="bk-guest-row">
                <span id="guestsChildrenLabel"><?= htmlspecialchars($t['search']['children']) ?></span>
                <div class="bk-counter" role="group" aria-labelledby="guestsChildrenLabel">
                    <button type="button" data-counter="childrenInput" data-delta="-1" data-min="0" aria-label="<?= htmlspecialchars($dec . ' ' . $t['search']['children']) ?>">−</button>
                    <span id="childrenVal" aria-live="polite"><?= (int)$p['children'] ?></span>
                    <button type="button" data-counter="childrenInput" data-delta="1" data-max="10" aria-label="<?= htmlspecialchars($inc . ' ' . $t['search']['children']) ?>">+</button>
                </div>
            </div>
            <div class="bk-guest-row">
                <span id="guestsRoomsLabel"><?= htmlspecialchars($t['search']['rooms']) ?></span>
                <div class="bk-counter" role="group" aria-labelledby="guestsRoomsLabel">
                    <button type="button" data-counter="roomsInput" data-delta="-1" data-min="1" aria-label="<?= htmlspecialchars($dec . ' ' . $t['search']['rooms']) ?>">−</button>
                    <span id="roomsVal" aria-live="polite"><?= (int)$p['rooms'] ?></span>
                    <button type="button" data-counter="roomsInput" data-delta="1" data-max="10" aria-label="<?= htmlspecialchars($inc . ' ' . $t['search']['rooms']) ?>">+</button>
                </div>
            </div>
        </div>
        <input type="hidden" name="adults" id="adultsInput" value="<?= (int)$p['adults'] ?>">
        <input type="hidden" name="children" id="childrenInput" value="<?= (int)$p['children'] ?>">
        <input type="hidden" name="rooms" id="roomsInput" value="<?= (int)$p['rooms'] ?>">
    </div>
    <div class="bk-search-btn-wrap">
        <button type="submit" class="bk-search-btn"><?= htmlspecialchars($t['search']['search_btn']) ?></button>
    </div>
</form>