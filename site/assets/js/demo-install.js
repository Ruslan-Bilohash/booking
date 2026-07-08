(function () {
    var tabs = document.querySelectorAll('.bks-di-tab');
    var panels = {
        download: document.getElementById('bksDiPanelDownload'),
        ftp: document.getElementById('bksDiPanelFtp'),
    };
    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var key = tab.getAttribute('data-tab');
            if (!key || !panels[key]) return;
            tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
            Object.keys(panels).forEach(function (k) {
                if (panels[k]) panels[k].hidden = k !== key;
            });
        });
    });

    var form = document.getElementById('bksDiDownloadForm');
    if (!form) return;
    var cfg = {};
    try {
        cfg = JSON.parse(form.getAttribute('data-config') || '{}');
    } catch (e) {
        cfg = {};
    }
    var msg = document.getElementById('bksDiDownloadMessage');
    var btn = document.getElementById('bksDiDownloadSubmit');

    function showMsg(text, ok) {
        if (!msg) return;
        msg.hidden = false;
        msg.textContent = text;
        msg.className = 'bks-di-message ' + (ok ? 'is-ok' : 'is-error');
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!cfg.loggedIn) {
            showMsg(cfg.errAuth || 'Login required', false);
            if (cfg.cabinetUrl) {
                window.location.href = cfg.cabinetUrl;
            }
            return;
        }
        var terms = form.querySelector('input[name="terms"]');
        if (!terms || !terms.checked) {
            showMsg(cfg.errTerms || 'Accept terms', false);
            return;
        }
        var fd = new FormData(form);
        fd.set('terms_accept', '1');
        fd.set('mode', 'download');
        if (msg) msg.hidden = true;
        if (btn) btn.disabled = true;
        fetch(cfg.api || '/api/booking-demo-download.php', { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) {
                var ctype = r.headers.get('content-type') || '';
                if (r.ok && (ctype.indexOf('zip') !== -1 || ctype.indexOf('octet-stream') !== -1)) {
                    return r.blob().then(function (blob) {
                        if (!blob || blob.size < 1024) throw new Error('package_missing');
                        var a = document.createElement('a');
                        a.href = URL.createObjectURL(blob);
                        a.download = cfg.filename || 'booking.zip';
                        a.click();
                        URL.revokeObjectURL(a.href);
                        showMsg(cfg.okDownload || 'Download started', true);
                    });
                }
                return r.json().then(function (j) {
                    throw new Error((j && j.error) || 'fail');
                });
            })
            .catch(function (ex) {
                var code = (ex && ex.message) ? ex.message : 'fail';
                var map = cfg.errors || {};
                showMsg(map[code] || cfg.errGeneric || 'Error', false);
                if (code === 'cabinet_required' && cfg.cabinetUrl) {
                    setTimeout(function () { window.location.href = cfg.cabinetUrl; }, 1200);
                }
            })
            .finally(function () { if (btn) btn.disabled = false; });
    });
})();