(function () {
    'use strict';

    var header = document.getElementById('bksHeader');
    var menuBtn = document.getElementById('bksMenuBtn');
    var panel = document.getElementById('bksMobilePanel');
    var overlay = document.getElementById('bksOverlay');
    var langDetails = document.getElementById('bksLangDetails');

    function closeLangDetails() {
        if (langDetails) langDetails.removeAttribute('open');
    }

    function setNavOpen(open) {
        if (!header) return;
        header.classList.toggle('nav-open', open);
        document.body.classList.toggle('bks-nav-open', open);
        if (menuBtn) menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (panel) {
            panel.hidden = !open;
            panel.classList.toggle('is-open', open);
        }
        if (overlay) {
            if (open) {
                overlay.hidden = false;
                requestAnimationFrame(function () {
                    overlay.classList.add('is-open');
                });
            } else {
                overlay.classList.remove('is-open');
                overlay.hidden = true;
            }
        }
        if (open) closeLangDetails();
    }

    function closeNav() {
        if (panel) {
            panel.querySelectorAll('details[open]').forEach(function (d) {
                d.removeAttribute('open');
            });
        }
        setNavOpen(false);
    }

    function openNav() {
        setNavOpen(true);
    }

    function onMenuToggle(e) {
        e.preventDefault();
        e.stopPropagation();
        closeLangDetails();
        if (header && header.classList.contains('nav-open')) closeNav();
        else openNav();
    }

    if (menuBtn && header) {
        menuBtn.addEventListener('click', onMenuToggle);
    }

    if (overlay) overlay.addEventListener('click', closeNav);

    if (panel) {
        panel.addEventListener('click', function (e) {
            e.stopPropagation();
        });
        panel.querySelectorAll('a').forEach(function (a) {
            a.addEventListener('click', closeNav);
        });
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1200) closeNav();
    });

    if (langDetails) {
        langDetails.addEventListener('toggle', function () {
            if (langDetails.open) closeNav();
        });
        document.addEventListener('click', function (e) {
            if (!langDetails.open) return;
            if (!langDetails.contains(e.target)) closeLangDetails();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && langDetails.open) closeLangDetails();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeNav();
    });

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
})();