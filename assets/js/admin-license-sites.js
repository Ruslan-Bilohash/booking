(function () {
    'use strict';

    var root = document.getElementById('shLicenseSitesManager');
    if (!root) return;

    var api = root.getAttribute('data-api') || '';
    var canManage = root.getAttribute('data-can-manage') === '1';
    var syncBtn = document.getElementById('shLicenseSitesSyncBtn');
    var tbody = document.getElementById('shLicenseSitesTableBody');
    var msgEl = document.getElementById('shLicenseSitesMsg');
    var emptyEl = document.getElementById('shLicenseSitesEmpty');

    var labels = {
        sync: root.getAttribute('data-label-sync') || 'Sync domains',
        syncing: root.getAttribute('data-label-syncing') || 'Syncing…',
        syncOk: root.getAttribute('data-label-sync-ok') || 'Domains updated',
        detach: root.getAttribute('data-label-detach') || 'Detach',
        detachConfirm: root.getAttribute('data-label-detach-confirm') || 'Remove {domain} from registry?',
        detachOk: root.getAttribute('data-label-detach-ok') || 'Domain detached',
        detachFail: root.getAttribute('data-label-detach-fail') || 'Could not detach domain',
        current: root.getAttribute('data-label-current') || 'This site',
        active: root.getAttribute('data-label-active') || 'Active',
        open: root.getAttribute('data-label-open') || 'Open admin'
    };

    function setMsg(text, isError, isOk) {
        if (!msgEl) return;
        msgEl.hidden = !text;
        msgEl.textContent = text || '';
        msgEl.className = 'adm-muted adm-license-msg'
            + (isError ? ' adm-license-msg--err' : '')
            + (isOk ? ' adm-license-msg--ok' : '');
    }

    function esc(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function renderTable(sites) {
        if (!tbody) return;
        if (!Array.isArray(sites) || !sites.length) {
            tbody.innerHTML = '';
            if (emptyEl) emptyEl.hidden = false;
            return;
        }
        if (emptyEl) emptyEl.hidden = true;
        tbody.innerHTML = sites.map(function (site) {
            if (!site || !site.domain) return '';
            var isCurrent = !!site.current;
            var adminUrl = 'https://' + site.domain + '/booking/admin/';
            var actions = '';
            if (canManage) {
                actions = '<td class="adm-license-domain-actions">';
                if (!isCurrent) {
                    actions += '<a href="' + esc(adminUrl) + '" class="adm-btn adm-btn-outline adm-btn-xs" target="_blank" rel="noopener">'
                        + '<i class="fas fa-external-link-alt"></i> ' + esc(labels.open) + '</a> ';
                    actions += '<button type="button" class="adm-btn adm-btn-outline adm-btn-xs shLicenseDetachBtn" data-domain="' + esc(site.domain) + '">'
                        + '<i class="fas fa-unlink"></i> ' + esc(labels.detach) + '</button>';
                } else {
                    actions += '<span class="adm-muted adm-license-current-hint">' + esc(labels.current) + '</span>';
                }
                actions += '</td>';
            }
            var statusBadge = isCurrent
                ? '<span class="adm-badge adm-badge-info adm-badge-sm">' + esc(labels.current) + '</span>'
                : '<span class="adm-badge adm-badge-green adm-badge-sm">' + esc(labels.active) + '</span>';
            return '<tr class="adm-license-domain-row' + (isCurrent ? ' is-current' : '') + '" data-domain="' + esc(site.domain) + '">'
                + '<td><i class="fas fa-globe adm-muted" aria-hidden="true"></i> <code>' + esc(site.domain) + '</code></td>'
                + '<td>' + esc(site.version || '—') + '</td>'
                + '<td>' + esc(site.last_seen || '—') + '</td>'
                + '<td>' + statusBadge + '</td>'
                + actions
                + '</tr>';
        }).join('');
        bindDetachButtons();
    }

    function post(action, extra) {
        var body = Object.assign({ action: action }, extra || {});
        return fetch(api, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'same-origin',
            body: JSON.stringify(body)
        }).then(function (r) {
            return r.json().then(function (j) { return { status: r.status, body: j }; });
        });
    }

    function runSync() {
        if (!syncBtn) return;
        syncBtn.disabled = true;
        setMsg(labels.syncing, false, false);
        post('sync').then(function (res) {
            var data = res.body || {};
            if (!data.ok) {
                setMsg(data.error || labels.detachFail, true, false);
                return;
            }
            renderTable(data.sites || []);
            setMsg(labels.syncOk, false, true);
        }).catch(function () {
            setMsg(labels.detachFail, true, false);
        }).finally(function () {
            syncBtn.disabled = false;
        });
    }

    function runDetach(domain) {
        var confirmTpl = labels.detachConfirm.replace('{domain}', domain);
        if (!window.confirm(confirmTpl)) return;
        setMsg('', false, false);
        post('detach', { domain: domain }).then(function (res) {
            var data = res.body || {};
            if (!data.ok) {
                setMsg(data.error || labels.detachFail, true, false);
                return;
            }
            renderTable(data.sites || []);
            setMsg(labels.detachOk, false, true);
        }).catch(function () {
            setMsg(labels.detachFail, true, false);
        });
    }

    function bindDetachButtons() {
        if (!canManage) return;
        root.querySelectorAll('.shLicenseDetachBtn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var domain = btn.getAttribute('data-domain') || '';
                if (domain) runDetach(domain);
            });
        });
    }

    if (syncBtn) {
        syncBtn.addEventListener('click', runSync);
    }
    bindDetachButtons();
})();