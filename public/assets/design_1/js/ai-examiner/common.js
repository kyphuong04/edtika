/* EDTIKA AI Examiner — tiện ích dùng chung */
(function () {
    'use strict';

    var AIE = window.AIE = window.AIE || {};

    AIE.$ = function (sel, root) { return (root || document).querySelector(sel); };
    AIE.$$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };

    AIE.escape = function (text) {
        return String(text == null ? '' : text)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    };

    AIE.wordCount = function (text) {
        var m = String(text || '').trim().match(/\S+/g);
        return m ? m.length : 0;
    };

    AIE.openModal = function (id) {
        var modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    };

    AIE.closeModal = function (modal) {
        if (typeof modal === 'string') modal = document.getElementById(modal);
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    document.addEventListener('click', function (e) {
        var closeBtn = e.target.closest('[data-aie-close]');
        if (closeBtn) {
            AIE.closeModal(closeBtn.closest('.aie-modal'));
            return;
        }
        if (e.target.classList && e.target.classList.contains('aie-modal')) {
            AIE.closeModal(e.target);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            AIE.$$('.aie-modal.is-open').forEach(AIE.closeModal);
        }
    });

    var toastTimer = null;
    AIE.toast = function (message) {
        var el = document.getElementById('aieToast');
        if (!el) return;
        el.textContent = message;
        el.classList.add('is-show');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function () { el.classList.remove('is-show'); }, 3200);
    };

    AIE.storage = {
        get: function (key, fallback) {
            try {
                var raw = window.localStorage.getItem(key);
                return raw ? JSON.parse(raw) : fallback;
            } catch (e) { return fallback; }
        },
        set: function (key, value) {
            try { window.localStorage.setItem(key, JSON.stringify(value)); return true; } catch (e) { return false; }
        },
        remove: function (key) {
            try { window.localStorage.removeItem(key); } catch (e) { /* ignore */ }
        }
    };
})();
