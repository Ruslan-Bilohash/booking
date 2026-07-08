(function () {
    'use strict';

    var root = document.getElementById('bhDomainHostingerPanel');
    if (!root) return;

    var api = root.getAttribute('data-api') || '';
    var input = document.getElementById('bhDomainCheckInput');
    var btn = document.getElementById('bhDomainCheckBtn');
    var results = document.getElementById('bhDomainCheckResults');
    var scoreEl = document.getElementById('bhDomainCheckScore');
    var listEl = document.getElementById('bhDomainCheckList');
    var msgEl = document.getElementById('bhDomainCheckMsg');

    var labels = {
        checking: root.getAttribute('data-label-checking') || 'Checking domain…',
        error: root.getAttribute('data-label-error') || 'Could not check domain',
        ready: root.getAttribute('data-label-score-ready') || 'CMS reachable',
        partial: root.getAttribute('data-label-score-partial') || 'DNS OK — upload CMS files',
        none: root.getAttribute('data-label-score-none') || 'Domain not ready'
    };

    var checkLabels = {
        format: root.getAttribute('data-chk-format') || 'Valid domain format',
        dns: root.getAttribute('data-chk-dns') || 'DNS resolves',
        hostinger_ns: root.getAttribute('data-chk-hostinger-ns') || 'Hostinger nameservers',
        bilohash_ip: root.getAttribute('data-chk-bilohash-ip') || 'BILOHASH hosting IP',
        ssl: root.getAttribute('data-chk-ssl') || 'HTTPS available',
        cms_http: root.getAttribute('data-chk-cms-http') || 'CMS URL responds'
    };

    function setMsg(text, isErr) {
        if (!msgEl) return;
        msgEl.hidden = !text;
        msgEl.textContent = text || '';
        msgEl.className = 'adm-muted adm-domain-msg' + (isErr ? ' adm-domain-msg--err' : ' adm-domain-msg--ok');
    }

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function render(data) {
        if (!results || !listEl || !scoreEl) return;
        results.hidden = false;
        var score = data.score || 'none';
        var scoreText = labels[score] || labels.none;
        var scoreClass = score === 'ready' ? 'is-ready' : (score === 'partial' ? 'is-partial' : 'is-none');
        scoreEl.className = 'adm-domain-hostinger-score ' + scoreClass;
        scoreEl.textContent = scoreText + ' — ' + (data.domain || '');

        var c = data.checks || {};
        var items = [];

        items.push({ ok: !!(c.format && c.format.ok), label: checkLabels.format });

        if (c.dns) {
            items.push({
                ok: !!c.dns.ok,
                label: checkLabels.dns + (c.dns.ips && c.dns.ips.length ? ' (' + c.dns.ips.join(', ') + ')' : '')
            });
            items.push({
                ok: !!c.dns.hostinger_ns,
                label: checkLabels.hostinger_ns + (c.dns.nameservers && c.dns.nameservers.length ? ': ' + c.dns.nameservers.slice(0, 2).join(', ') : '')
            });
        }
        if (c.bilohash_ip) {
            items.push({
                ok: !!c.bilohash_ip.ok,
                label: checkLabels.bilohash_ip + ' (' + (c.bilohash_ip.expected || '') + ')'
            });
        }
        if (c.ssl) {
            items.push({ ok: !!c.ssl.ok, label: checkLabels.ssl });
        }
        if (c.cms_http) {
            items.push({
                ok: !!c.cms_http.ok,
                label: checkLabels.cms_http + ' — ' + (c.cms_http.url || '')
            });
        }

        listEl.innerHTML = items.map(function (item) {
            var icon = item.ok ? 'fa-circle-check' : 'fa-circle-xmark';
            var cls = item.ok ? 'is-ok' : 'is-fail';
            return '<li class="adm-domain-hostinger-check-item ' + cls + '"><i class="fas ' + icon + '"></i><span>' + esc(item.label) + '</span></li>';
        }).join('');
    }

    function runCheck() {
        if (!btn || !api) return;
        var domain = (input && input.value ? input.value : '').trim();
        btn.disabled = true;
        setMsg(labels.checking, false);
        if (results) results.hidden = true;

        fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify({
                domain: domain,
                product: root.getAttribute('data-product') || 'shop'
            })
        }).then(function (r) {
            return r.json().then(function (j) { return { status: r.status, body: j }; });
        }).then(function (res) {
            var data = res.body || {};
            if (!data.ok) {
                setMsg(data.error || labels.error, true);
                return;
            }
            setMsg('', false);
            render(data);
        }).catch(function () {
            setMsg(labels.error, true);
        }).finally(function () {
            btn.disabled = false;
        });
    }

    if (btn) btn.addEventListener('click', runCheck);
    if (input) {
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                runCheck();
            }
        });
    }
})();