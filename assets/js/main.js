(function () {
    'use strict';

    // Mobile header menu
    var header = document.getElementById('bkHeader');
    var menuBtn = document.getElementById('bkMenuBtn');
    var menuClose = document.getElementById('bkMenuClose');
    var overlay = document.getElementById('bkOverlay');
    var panel = document.getElementById('bkHeaderPanel');

    function closeNav() {
        if (!header) return;
        header.classList.remove('nav-open');
        document.body.classList.remove('bk-nav-open');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
        if (overlay) {
            overlay.classList.remove('is-open');
            overlay.hidden = true;
        }
    }

    function openNav() {
        if (!header) return;
        header.classList.add('nav-open');
        document.body.classList.add('bk-nav-open');
        if (menuBtn) menuBtn.setAttribute('aria-expanded', 'true');
        if (overlay) {
            overlay.hidden = false;
            requestAnimationFrame(function () { overlay.classList.add('is-open'); });
        }
    }

    if (menuBtn && header) {
        menuBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeLangDropdown();
            if (header.classList.contains('nav-open')) {
                closeNav();
            } else {
                openNav();
            }
        });
    }
    if (menuClose) {
        menuClose.addEventListener('click', closeNav);
    }
    if (overlay) {
        overlay.addEventListener('click', closeNav);
    }
    if (panel) {
        panel.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', closeNav);
        });
    }

    // Language dropdown(s) — strip (mobile) + header (desktop nav)
    function closeLangDropdown() {
        document.querySelectorAll('.bk-lang-dropdown.is-open').forEach(function (root) {
            root.classList.remove('is-open');
            var btn = root.querySelector('.bk-lang-dropdown-btn');
            var menu = root.querySelector('.bk-lang-dropdown-menu');
            if (btn) btn.setAttribute('aria-expanded', 'false');
            if (menu) menu.hidden = true;
        });
    }

    document.querySelectorAll('.bk-lang-dropdown').forEach(function (langDropdown) {
        var langBtn = langDropdown.querySelector('.bk-lang-dropdown-btn');
        var langMenu = langDropdown.querySelector('.bk-lang-dropdown-menu');
        if (!langBtn || !langMenu) return;
        langBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var wasOpen = langDropdown.classList.contains('is-open');
            closeLangDropdown();
            if (!wasOpen) {
                langDropdown.classList.add('is-open');
                langMenu.hidden = false;
                langBtn.setAttribute('aria-expanded', 'true');
            }
        });
        langMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.bk-lang-dropdown')) {
            closeLangDropdown();
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeNav();
            closeLangDropdown();
        }
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1200) {
            closeNav();
            closeLangDropdown();
        }
    });

    // Guests popup
    var guestsField = document.querySelector('.guests-field');
    var popup = document.getElementById('guestsPopup');
    if (guestsField && popup) {
        var adultsIn = document.getElementById('adultsInput');
        var childrenIn = document.getElementById('childrenInput');
        var roomsIn = document.getElementById('roomsInput');
        var adultsVal = document.getElementById('adultsVal');
        var childrenVal = document.getElementById('childrenVal');
        var roomsVal = document.getElementById('roomsVal');
        var guestsDisplay = document.getElementById('guestsDisplay');

        function sync() {
            if (adultsVal) adultsVal.textContent = adultsIn.value;
            if (childrenVal) childrenVal.textContent = childrenIn.value;
            if (roomsVal) roomsVal.textContent = roomsIn.value;
            if (guestsDisplay) {
                var a = parseInt(adultsIn.value, 10);
                var c = parseInt(childrenIn.value, 10);
                var r = parseInt(roomsIn.value, 10);
                guestsDisplay.textContent = a + ' + ' + c + ' · ' + r;
            }
        }

        var guestsToggle = document.getElementById('guestsToggle');
        if (guestsToggle) {
            guestsToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                var open = popup.classList.toggle('open');
                guestsToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            });
        }
        document.addEventListener('click', function () {
            popup.classList.remove('open');
            if (guestsToggle) guestsToggle.setAttribute('aria-expanded', 'false');
        });
        popup.addEventListener('click', function (e) { e.stopPropagation(); });

        document.querySelectorAll('[data-counter]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = document.getElementById(btn.dataset.counter);
                var min = parseInt(btn.dataset.min || '0', 10);
                var max = parseInt(btn.dataset.max || '20', 10);
                var delta = parseInt(btn.dataset.delta, 10);
                var v = parseInt(target.value, 10) + delta;
                if (v >= min && v <= max) {
                    target.value = v;
                    sync();
                }
            });
        });
        sync();
    }

    // Property detail tabs (overview / amenities / reviews)
    var propTabs = document.getElementById('bkPropertyTabs');
    if (propTabs) {
        var panels = {
            overview: document.getElementById('bk-tab-overview'),
            amenities: document.getElementById('bk-tab-amenities'),
            reviews: document.getElementById('bk-tab-reviews')
        };
        propTabs.querySelectorAll('.bk-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                var key = tab.getAttribute('data-tab');
                propTabs.querySelectorAll('.bk-tab').forEach(function (t) {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                    t.setAttribute('tabindex', '-1');
                });
                tab.classList.add('active');
                tab.setAttribute('aria-selected', 'true');
                tab.setAttribute('tabindex', '0');
                Object.keys(panels).forEach(function (k) {
                    var panel = panels[k];
                    if (!panel) return;
                    var on = k === key;
                    panel.classList.toggle('active', on);
                    panel.hidden = !on;
                });
            });
        });
        function activateTab(key) {
            var tab = propTabs.querySelector('[data-tab="' + key + '"]');
            if (tab) tab.click();
        }
        document.querySelectorAll('[data-goto-tab]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                activateTab(btn.getAttribute('data-goto-tab'));
                propTabs.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            });
        });
        if (window.location.hash === '#reviews' && panels.reviews) {
            activateTab('reviews');
        }
    }

    // Min checkout date
    var checkin = document.getElementById('checkin');
    var checkout = document.getElementById('checkout');
    if (checkin && checkout) {
        checkin.addEventListener('change', function () {
            checkout.min = checkin.value;
            if (checkout.value <= checkin.value) {
                var d = new Date(checkin.value);
                d.setDate(d.getDate() + 1);
                checkout.value = d.toISOString().slice(0, 10);
            }
        });
    }

    // Lazy-load chat widget after first paint
    function loadBhChat() {
        if (!window.BH_CHAT_LAZY || window.__bkChatLoaded) return;
        window.__bkChatLoaded = true;
        var v = window.BH_CHAT_ASSET_V || 1;
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = '/assets/css/bh-chat-widget.css?v=' + v;
        document.head.appendChild(link);
        var script = document.createElement('script');
        script.src = '/assets/js/bh-chat-widget.js?v=' + v;
        script.defer = true;
        document.body.appendChild(script);
    }
    document.querySelectorAll('[data-eco-more-btn]').forEach(function (btn) {
        var listId = btn.getAttribute('aria-controls');
        var list = listId ? document.getElementById(listId) : null;
        if (!list) return;
        btn.addEventListener('click', function () {
            var open = list.hidden;
            list.hidden = !open;
            list.setAttribute('aria-hidden', open ? 'false' : 'true');
            list.classList.toggle('is-open', open);
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            btn.textContent = open
                ? (btn.getAttribute('data-label-less') || 'Show less')
                : (btn.getAttribute('data-label-more') || 'Show more');
        });
    });

    if (window.BH_CHAT_LAZY) {
        function scheduleBhChat() {
            if (window.__bkChatScheduled) return;
            window.__bkChatScheduled = true;
            ['pointerdown', 'keydown'].forEach(function (ev) {
                window.addEventListener(ev, loadBhChat, { once: true, passive: true });
            });
            setTimeout(loadBhChat, 20000);
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', scheduleBhChat);
        } else {
            scheduleBhChat();
        }
    }
})();