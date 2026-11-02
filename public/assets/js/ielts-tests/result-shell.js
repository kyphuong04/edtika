/**
 * result-shell.js — phần dùng chung của trang kết quả và trang chữa bài:
 *  - cửa sổ xác nhận "Làm lại" (partials/retake_modal.blade.php),
 *  - cửa sổ "Chia sẻ bài làm" (partials/share_modal.blade.php).
 */
(function () {
    function init() {
        var modal = document.getElementById('rsRetakeModal');
        if (!modal) return;

        var opener = document.querySelector('[data-retake-open]');
        var submit = modal.querySelector('[data-retake-submit]');
        var form = modal.querySelector('form');

        function openModal() {
            modal.hidden = false;
            document.body.classList.add('rs-modal-open');
            if (submit) submit.focus();
        }

        function closeModal() {
            modal.hidden = true;
            document.body.classList.remove('rs-modal-open');
            if (opener) opener.focus();
        }

        if (opener) opener.addEventListener('click', openModal);

        modal.querySelectorAll('[data-retake-close]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });

        // Chặn bấm 2 lần -> tạo 2 attempt.
        if (form && submit) {
            form.addEventListener('submit', function () {
                submit.disabled = true;
                submit.textContent = 'Đang chuẩn bị bài thi...';
            });
        }
    }

    /* ── Chia sẻ bài làm ──────────────────────────────────────────── */
    function initShare() {
        var modal = document.getElementById('rsShareModal');
        var opener = document.querySelector('[data-share-open]');
        if (!modal || !opener) return;

        var $ = function (sel) { return modal.querySelector(sel); };
        var states = modal.querySelectorAll('[data-share-state]');
        var data = null;
        var busy = false;
        var msgTimer = null;

        var tokenEl = document.querySelector('meta[name="csrf-token"]');
        var csrf = tokenEl ? tokenEl.getAttribute('content') : '';

        function show(name) {
            states.forEach(function (el) { el.hidden = el.getAttribute('data-share-state') !== name; });
        }

        function say(text, isError) {
            var el = $('[data-share-msg]');
            el.textContent = text || '';
            el.classList.toggle('is-error', !!isError);
            clearTimeout(msgTimer);
            if (text) msgTimer = setTimeout(function () { el.textContent = ''; }, 5000);
        }

        function post(url) {
            return fetch(url, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: '{}',
            }).then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (json) {
                    if (!res.ok || json.status !== 'ok') {
                        var err = new Error(json.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                        throw err;
                    }
                    return json;
                });
            });
        }

        function render(share) {
            data = share;
            var img = share.image || {};
            var domain = '';
            try { domain = new URL(share.url).host.replace(/^www\./, '').toUpperCase(); } catch (e) {}

            var main = $('[data-share-img]');
            main.src = img.url || '';
            main.alt = img.alt || '';
            $('[data-share-card-img]').src = img.url || '';
            $('[data-share-domain]').textContent = domain;
            $('[data-share-card-title]').textContent = share.title || '';
            $('[data-share-card-desc]').textContent = share.description || '';
            $('[data-share-link]').value = share.url;
            $('[data-share-views]').textContent = share.views || 0;
            $('[data-share-shuffle]').hidden = !share.canShuffle;

            var dl = $('[data-share-download]');
            dl.href = img.url || '#';
            var ext = /\.(png|jpe?g|webp)(?:\?|$)/i.exec(img.url || '');
            dl.setAttribute('download', 'edtika-ket-qua-' + (share.tier || 'ielts') + '.' + (ext ? ext[1].toLowerCase().replace('jpeg', 'jpg') : 'png'));

            // Giữ lời dẫn người dùng đã sửa khi mở lại cửa sổ.
            var cap = $('[data-share-caption]');
            if (!cap.dataset.touched) cap.value = share.caption || '';

            $('[data-share-net="native"]').hidden = typeof navigator.share !== 'function';
            show('ready');
        }

        function load(url) {
            if (busy) return;
            busy = true;
            if (!data || url === modal.dataset.storeUrl) show('loading');
            post(url)
                .then(function (json) { render(json.share); })
                .catch(function (err) {
                    $('[data-share-error]').textContent = err.message;
                    show('error');
                })
                .then(function () { busy = false; });
        }

        function copy(text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }
            return new Promise(function (resolve, reject) {
                var ta = document.createElement('textarea');
                ta.value = text;
                ta.setAttribute('readonly', '');
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                var ok = false;
                try { ok = document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(ta);
                ok ? resolve() : reject(new Error('copy'));
            });
        }

        function caption() {
            return ($('[data-share-caption]').value || '').trim();
        }

        function popup(url) {
            var w = 620, h = 640;
            var left = Math.max(0, (window.screenX || 0) + (window.outerWidth - w) / 2);
            var top = Math.max(0, (window.screenY || 0) + (window.outerHeight - h) / 2);
            // Không truyền 'noopener' vào features: khi đó window.open luôn trả null.
            var win = window.open(url, 'edtika_share', 'width=' + w + ',height=' + h + ',left=' + left + ',top=' + top);
            if (win) {
                try { win.opener = null; } catch (e) {}
            } else {
                window.open(url, '_blank', 'noopener');
            }
        }

        function openModal() {
            modal.hidden = false;
            document.body.classList.add('rs-modal-open');
            load(modal.dataset.storeUrl);
            var x = $('.rs-share-x');
            if (x) x.focus();
        }

        function closeModal() {
            modal.hidden = true;
            document.body.classList.remove('rs-modal-open');
            opener.focus();
        }

        opener.addEventListener('click', openModal);

        modal.querySelectorAll('[data-share-close]').forEach(function (el) {
            el.addEventListener('click', closeModal);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });

        modal.querySelectorAll('[data-share-retry]').forEach(function (el) {
            el.addEventListener('click', function () { load(modal.dataset.storeUrl); });
        });

        $('[data-share-shuffle]').addEventListener('click', function () {
            load(modal.dataset.shuffleUrl);
        });

        $('[data-share-caption]').addEventListener('input', function () {
            this.dataset.touched = '1';
        });

        modal.querySelectorAll('[data-share-copy]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!data) return;
                var isLink = btn.getAttribute('data-share-copy') === 'link';
                var text = isLink ? data.url : (caption() + '\n' + data.url);
                copy(text).then(function () {
                    var old = btn.textContent;
                    btn.textContent = 'Đã chép';
                    btn.classList.add('is-done');
                    setTimeout(function () { btn.textContent = old; btn.classList.remove('is-done'); }, 1600);
                }).catch(function () {
                    say('Trình duyệt chặn sao chép — hãy bôi đen và sao chép thủ công.', true);
                });
            });
        });

        modal.querySelectorAll('[data-share-net]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (!data) return;
                var net = btn.getAttribute('data-share-net');
                var url = data.url;
                var text = caption();
                var enc = encodeURIComponent;

                if (net === 'native') {
                    navigator.share({ title: data.title, text: text, url: url }).catch(function () {});
                    return;
                }

                if (net === 'threads') {
                    popup('https://www.threads.net/intent/post?text=' + enc(text ? text + '\n' + url : url));
                    return;
                }

                // Facebook / LinkedIn không nhận sẵn lời dẫn -> chép sẵn để dán vào bài đăng.
                if (text) {
                    copy(text).then(function () {
                        say('Đã sao chép lời dẫn — dán (Ctrl/⌘ + V) vào ô nội dung bài đăng nhé.');
                    }).catch(function () {});
                }

                popup(net === 'facebook'
                    ? 'https://www.facebook.com/sharer/sharer.php?u=' + enc(url)
                    : 'https://www.linkedin.com/sharing/share-offsite/?url=' + enc(url));
            });
        });

        $('[data-share-disable]').addEventListener('click', function () {
            if (busy) return;
            busy = true;
            post(modal.dataset.disableUrl)
                .then(function () { show('off'); })
                .catch(function (err) { say(err.message, true); })
                .then(function () { busy = false; });
        });
    }

    function boot() {
        init();
        initShare();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
