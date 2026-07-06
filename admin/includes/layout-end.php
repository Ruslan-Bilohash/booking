
        </main>
    </div>
</div>
<script>
(function () {
    var sidebar = document.getElementById('admSidebar');
    var btn = document.getElementById('admMenuBtn');
    var overlay = document.getElementById('admOverlay');

    function scrollNavToActive() {
        if (!sidebar || window.innerWidth > 900) return;
        var active = sidebar.querySelector('.adm-nav-sub-link.active, .adm-nav-link.active');
        if (active) {
            requestAnimationFrame(function () {
                active.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            });
        }
    }

    function setMenuOpen(open) {
        sidebar?.classList.toggle('open', open);
        btn?.classList.toggle('is-open', open);
        btn?.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (open) {
            if (overlay) {
                overlay.hidden = false;
                requestAnimationFrame(function () { overlay.classList.add('is-open'); });
            }
            document.body.classList.add('adm-nav-open');
            scrollNavToActive();
        } else {
            overlay?.classList.remove('is-open');
            if (overlay) overlay.hidden = true;
            document.body.classList.remove('adm-nav-open');
        }
    }

    function closeSidebar() { setMenuOpen(false); }
    function openSidebar() { setMenuOpen(true); }

    btn?.addEventListener('click', function () {
        setMenuOpen(!sidebar?.classList.contains('open'));
    });
    document.getElementById('admSidebarClose')?.addEventListener('click', closeSidebar);
    overlay?.addEventListener('click', closeSidebar);
    sidebar?.querySelectorAll('.adm-nav-link, .adm-nav-sub-link').forEach(function (link) {
        link.addEventListener('click', closeSidebar);
    });

    var settingsNav = document.getElementById('admSettingsNav');
    var settingsNavBtn = document.getElementById('admSettingsNavBtn');
    var settingsNavSub = document.getElementById('admSettingsNavSub');
    function toggleSettingsNav(forceOpen) {
        if (!settingsNav || !settingsNavBtn || !settingsNavSub) return;
        var open = typeof forceOpen === 'boolean' ? forceOpen : !settingsNav.classList.contains('is-open');
        settingsNav.classList.toggle('is-open', open);
        settingsNavSub.hidden = !open;
        settingsNavBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    }
    settingsNavBtn?.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        toggleSettingsNav();
        if (settingsNav?.classList.contains('is-open')) {
            scrollNavToActive();
        }
    });

    document.querySelectorAll('.adm-nav-sub-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (window.innerWidth > 900) return;
            e.preventDefault();
            e.stopPropagation();
            var group = btn.closest('.adm-nav-sub-group');
            if (!group) return;
            var open = group.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
            if (open) {
                requestAnimationFrame(function () {
                    group.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                });
            }
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
    window.addEventListener('resize', function () {
        if (window.innerWidth > 900) closeSidebar();
    });

    var langDropdown = document.getElementById('admLangDropdown');
    var langBtn = document.getElementById('admLangBtn');
    var langMenu = document.getElementById('admLangMenu');
    function closeLangDropdown() {
        if (!langDropdown) return;
        langDropdown.classList.remove('is-open');
        if (langBtn) langBtn.setAttribute('aria-expanded', 'false');
        if (langMenu) langMenu.hidden = true;
    }
    if (langBtn && langMenu && langDropdown) {
        langBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var open = !langDropdown.classList.contains('is-open');
            langDropdown.classList.toggle('is-open', open);
            langMenu.hidden = !open;
            langBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
        langMenu.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () { closeLangDropdown(); });
        });
    }
    document.addEventListener('click', function (e) {
        if (!langDropdown || langDropdown.contains(e.target)) return;
        closeLangDropdown();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeLangDropdown();
    });
})();
</script>
</body>
</html>