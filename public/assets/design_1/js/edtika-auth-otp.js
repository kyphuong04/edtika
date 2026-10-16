/**
 * EDTIKA – Đăng nhập / Đăng ký / Xác minh OTP ngay trong modal (không chuyển trang).
 *
 * - Form có data-auth-ajax="login" | "register" | "verify" được gửi bằng fetch (Accept: application/json).
 * - Đăng ký xong (hoặc đăng nhập bằng tài khoản chưa xác minh) -> chuyển sang pane "verify" trong cùng modal.
 * - Lỗi validate (422) hiển thị ngay dưới từng ô, lỗi chung hiển thị ở đầu form.
 * - Nếu trình duyệt không chạy JS, form vẫn submit bình thường như trước (fallback).
 */
(function () {
    'use strict';

    if (!window.fetch || !window.FormData || !Element.prototype.closest) {
        return; // trình duyệt quá cũ -> dùng submit truyền thống
    }

    var STORE_KEY = 'edtikaPendingVerification';
    var PENDING_TTL = 55 * 60 * 1000; // mã hết hạn sau 60 phút ở backend
    var isEnglish = (document.documentElement.getAttribute('lang') || '').toLowerCase().indexOf('en') === 0;

    var T = isEnglish ? {
        generic: 'Something went wrong. Please try again.',
        network: 'Unable to connect to the server. Please check your connection and try again.',
        sessionExpired: 'Your session has expired. Please reload the page and try again.',
        tooMany: 'Too many attempts. Please wait a moment and try again.',
        codeIncomplete: 'Please enter all 5 digits of the code.',
        verified: 'Verified successfully! Redirecting…',
        resent: 'A new verification code has been sent to your email.',
        resendFailed: 'Could not resend the code. Please register or log in again.',
        notVerified: 'Your account has not been verified yet. We have sent a new code to your email.'
    } : {
        generic: 'Đã có lỗi xảy ra. Vui lòng thử lại.',
        network: 'Không thể kết nối tới máy chủ. Vui lòng kiểm tra mạng và thử lại.',
        sessionExpired: 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.',
        tooMany: 'Bạn thao tác quá nhiều lần. Vui lòng đợi một lát rồi thử lại.',
        codeIncomplete: 'Vui lòng nhập đủ 5 chữ số của mã xác minh.',
        verified: 'Xác minh thành công! Đang chuyển hướng…',
        resent: 'Mã xác minh mới đã được gửi tới email của bạn.',
        resendFailed: 'Không thể gửi lại mã. Vui lòng đăng ký hoặc đăng nhập lại.',
        notVerified: 'Tài khoản chưa được xác minh. Mã xác minh mới đã được gửi tới email của bạn.'
    };

    /* ------------------------------------------------------------------ */
    /* Lưu trạng thái "đang chờ xác minh" để mở lại modal vẫn nhập mã được */
    /* ------------------------------------------------------------------ */
    function storeGet() {
        try {
            var raw = window.sessionStorage.getItem(STORE_KEY);
            if (!raw) {
                return null;
            }
            var data = JSON.parse(raw);
            if (!data || !data.usernameValue || !data.expiresAt || data.expiresAt < Date.now()) {
                window.sessionStorage.removeItem(STORE_KEY);
                return null;
            }
            return data;
        } catch (e) {
            return null;
        }
    }

    function storeSet(data) {
        try {
            window.sessionStorage.setItem(STORE_KEY, JSON.stringify(data));
        } catch (e) {
            // sessionStorage bị chặn -> bỏ qua, chỉ mất khả năng khôi phục khi đóng/mở modal
        }
    }

    function storeClear() {
        try {
            window.sessionStorage.removeItem(STORE_KEY);
        } catch (e) {
        }
    }

    /* ------------------------------------------------------------------ */
    /* Tiện ích DOM                                                        */
    /* ------------------------------------------------------------------ */
    function each(list, fn) {
        Array.prototype.forEach.call(list || [], fn);
    }

    function getModal(el) {
        return (el && el.closest('.edtika-auth-modal')) || document.getElementById('edtikaAuthModal');
    }

    function showPane(modal, name) {
        if (!modal) {
            return;
        }
        each(modal.querySelectorAll('[data-auth-pane]'), function (pane) {
            pane.classList.toggle('is-active', pane.getAttribute('data-auth-pane') === name);
        });
        each(modal.querySelectorAll('[data-auth-tab]'), function (tab) {
            tab.classList.toggle('is-active', tab.getAttribute('data-auth-tab') === name);
        });
        var side = modal.querySelector('.edtika-auth-modal__form-side');
        if (side) {
            side.scrollTop = 0;
        }
    }

    function firstMessage(value) {
        if (Array.isArray(value)) {
            return value.length ? String(value[0]) : '';
        }
        return value ? String(value) : '';
    }

    /* ------------------------------------------------------------------ */
    /* Thông báo chung + lỗi theo ô                                        */
    /* ------------------------------------------------------------------ */
    function showAlert(form, message, type) {
        if (!form || !message) {
            return;
        }
        var alert = form.querySelector('[data-auth-alert]');
        if (!alert) {
            alert = document.createElement('div');
            alert.setAttribute('data-auth-alert', '');
            alert.setAttribute('role', 'alert');
            form.insertBefore(alert, form.firstChild);
        }
        alert.className = 'edtika-auth-alert edtika-auth-alert--' + (type || 'error');
        alert.textContent = message;
        alert.hidden = false;
    }

    function hideAlert(form) {
        var alert = form && form.querySelector('[data-auth-alert]');
        if (alert) {
            alert.hidden = true;
            alert.textContent = '';
        }
    }

    function clearErrors(form) {
        if (!form) {
            return;
        }
        each(form.querySelectorAll('.is-invalid'), function (el) {
            el.classList.remove('is-invalid');
            el.removeAttribute('aria-invalid');
        });
        each(form.querySelectorAll('[data-auth-error]'), function (el) {
            if (el.hasAttribute('data-auth-error-dynamic')) {
                el.parentNode.removeChild(el);
            } else {
                el.hidden = true;
                el.textContent = '';
            }
        });
        hideAlert(form);
    }

    function findInputs(form, baseName) {
        return Array.prototype.filter.call(form.querySelectorAll('input, select, textarea'), function (el) {
            var name = el.getAttribute('name') || '';
            if (el.type === 'hidden') {
                return false;
            }
            if (name === baseName || name.indexOf(baseName + '[') === 0) {
                // bỏ qua ô đang bị ẩn (vd: ô điện thoại khi đang chọn đăng nhập bằng email)
                return el.offsetParent !== null || el.closest('.edtika-auth-check');
            }
            return false;
        });
    }

    function setFieldError(form, field, message) {
        var baseName = String(field).split('.')[0];
        var inputs = findInputs(form, baseName);
        var holder = form.querySelector('[data-auth-error="' + baseName + '"]');

        if (!inputs.length && !holder) {
            return false;
        }

        inputs.forEach(function (input) {
            input.classList.add('is-invalid');
            input.setAttribute('aria-invalid', 'true');
        });

        if (!holder) {
            holder = document.createElement('div');
            holder.className = 'edtika-auth-error';
            holder.setAttribute('data-auth-error', baseName);
            holder.setAttribute('data-auth-error-dynamic', '');

            var anchor = inputs[0].closest('.edtika-auth-field') || inputs[0].closest('.edtika-auth-check') || inputs[0];
            if (anchor.classList && anchor.classList.contains('edtika-auth-field')) {
                anchor.appendChild(holder);
            } else {
                anchor.parentNode.insertBefore(holder, anchor.nextSibling);
            }
        }

        holder.textContent = message;
        holder.hidden = false;
        return true;
    }

    function applyErrors(form, errors, fallbackMessage) {
        var unplaced = [];
        var focused = false;

        Object.keys(errors || {}).forEach(function (field) {
            var message = firstMessage(errors[field]);
            if (!message) {
                return;
            }
            if (setFieldError(form, field, message)) {
                if (!focused) {
                    var input = form.querySelector('.is-invalid');
                    if (input && input.focus && input.type !== 'checkbox') {
                        input.focus();
                    }
                    focused = true;
                }
            } else if (unplaced.indexOf(message) === -1) {
                unplaced.push(message);
            }
        });

        if (unplaced.length) {
            showAlert(form, unplaced.join(' '), 'error');
        } else if (!Object.keys(errors || {}).length && fallbackMessage) {
            showAlert(form, fallbackMessage, 'error');
        }
    }

    /* ------------------------------------------------------------------ */
    /* Gửi request                                                         */
    /* ------------------------------------------------------------------ */
    function request(url, options) {
        options = options || {};
        options.credentials = 'same-origin';
        options.headers = Object.assign({
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }, options.headers || {});

        return window.fetch(url, options).then(function (res) {
            var result = {status: res.status, ok: res.ok, redirected: res.redirected, url: res.url, json: null};
            var type = res.headers.get('content-type') || '';

            if (type.indexOf('application/json') === -1) {
                return result;
            }

            return res.json().then(function (json) {
                result.json = json;
                return result;
            }, function () {
                return result;
            });
        });
    }

    function setLoading(form, loading) {
        var button = form.querySelector('[type="submit"]');
        if (!button) {
            return;
        }
        button.disabled = !!loading;
        button.classList.toggle('is-loading', !!loading);
        button.setAttribute('aria-busy', loading ? 'true' : 'false');
    }

    function goTo(url) {
        window.location.href = url || '/panel';
    }

    /** Xử lý các trường hợp lỗi chung. Trả về true nếu đã xử lý. */
    function handleFailure(form, res) {
        var json = res.json || {};

        if (res.status === 422) {
            applyErrors(form, json.errors || {}, json.message || T.generic);
            return true;
        }
        if (res.status === 419) {
            showAlert(form, T.sessionExpired, 'error');
            return true;
        }
        if (res.status === 429) {
            showAlert(form, json.message || T.tooMany, 'error');
            return true;
        }
        if (!res.ok) {
            showAlert(form, json.message || T.generic, 'error');
            return true;
        }
        return false;
    }

    /** Response không phải JSON (vd: server redirect về trang HTML) -> đi theo server. */
    function followServer(res) {
        if (res.redirected && res.url) {
            goTo(res.url);
        } else {
            window.location.reload();
        }
    }

    /* ------------------------------------------------------------------ */
    /* Pane xác minh OTP                                                   */
    /* ------------------------------------------------------------------ */
    function formatSeconds(total) {
        var m = Math.floor(total / 60);
        var s = total % 60;
        return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
    }

    function startCountdown(pane, resendAt) {
        var countdownWrap = pane.querySelector('[data-otp-countdown-wrap]');
        var resendWrap = pane.querySelector('[data-otp-resend-wrap]');
        var countdown = pane.querySelector('[data-otp-countdown]');

        if (pane._otpTimer) {
            window.clearInterval(pane._otpTimer);
        }

        var tick = function () {
            var left = Math.ceil((resendAt - Date.now()) / 1000);

            if (left <= 0) {
                window.clearInterval(pane._otpTimer);
                pane._otpTimer = null;
                if (countdownWrap) countdownWrap.hidden = true;
                if (resendWrap) resendWrap.hidden = false;
                return;
            }

            if (countdownWrap) countdownWrap.hidden = false;
            if (resendWrap) resendWrap.hidden = true;
            if (countdown) countdown.textContent = formatSeconds(left);
        };

        tick();
        pane._otpTimer = window.setInterval(tick, 1000);
    }

    function getResendSeconds(pane) {
        var seconds = parseInt(pane.getAttribute('data-otp-resend-seconds'), 10);
        return isNaN(seconds) || seconds <= 0 ? 60 : seconds;
    }

    function getBoxes(scope) {
        return scope ? Array.prototype.slice.call(scope.querySelectorAll('.edtika-otp__box')) : [];
    }

    function resetBoxes(form) {
        getBoxes(form).forEach(function (box) {
            box.value = '';
            box.classList.remove('is-filled', 'is-invalid');
        });
    }

    function focusFirstEmpty(form) {
        var boxes = getBoxes(form);
        var target = boxes.filter(function (b) { return !b.value; })[0] || boxes[boxes.length - 1];
        if (target) {
            window.setTimeout(function () { target.focus(); }, 60);
        }
    }

    /**
     * Chuyển modal sang pane nhập OTP.
     * @param {Element} modal
     * @param {Object} data {username, usernameValue, resendAt?}
     * @param {Object} opts {fresh: bool – vừa gửi mã mới, message: string}
     */
    function openVerifyPane(modal, data, opts) {
        opts = opts || {};
        var pane = modal && modal.querySelector('[data-auth-pane="verify"]');

        if (!pane) {
            goTo('/verification'); // trang không có pane OTP -> dùng trang xác minh cũ
            return;
        }

        var form = pane.querySelector('form[data-auth-ajax="verify"]');
        var target = pane.querySelector('[data-otp-target]');
        var usernameInput = pane.querySelector('[data-otp-username]');
        var valueInput = pane.querySelector('[data-otp-username-value]');

        if (usernameInput) usernameInput.value = data.username || 'email';
        if (valueInput) valueInput.value = data.usernameValue || '';
        if (target) target.textContent = data.usernameValue || '';

        var resendAt = opts.fresh || !data.resendAt
            ? Date.now() + getResendSeconds(pane) * 1000
            : data.resendAt;

        storeSet({
            username: data.username || 'email',
            usernameValue: data.usernameValue,
            resendAt: resendAt,
            expiresAt: opts.fresh || !data.expiresAt ? Date.now() + PENDING_TTL : data.expiresAt
        });

        clearErrors(form);
        resetBoxes(form);
        showPane(modal, 'verify');
        startCountdown(pane, resendAt);

        if (opts.message) {
            showAlert(form, opts.message, 'success');
        }

        focusFirstEmpty(form);
    }

    function fillBoxes(startBox, digits) {
        var form = startBox.closest('form');
        var boxes = getBoxes(form);
        var index = boxes.indexOf(startBox);

        digits.split('').forEach(function (digit) {
            if (index < boxes.length) {
                boxes[index].value = digit;
                boxes[index].classList.add('is-filled');
                boxes[index].classList.remove('is-invalid');
                index++;
            }
        });

        if (index < boxes.length) {
            boxes[index].focus();
        } else {
            boxes[boxes.length - 1].focus();
        }

        maybeAutoSubmit(form);
    }

    function maybeAutoSubmit(form) {
        var boxes = getBoxes(form);
        var complete = boxes.length && boxes.every(function (b) { return /^\d$/.test(b.value); });

        if (complete && !form._otpSubmitting) {
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                form.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}));
            }
        }
    }

    /* ------------------------------------------------------------------ */
    /* Submit từng loại form                                               */
    /* ------------------------------------------------------------------ */
    function submitAuthForm(form, kind) {
        var modal = getModal(form);

        clearErrors(form);
        setLoading(form, true);

        return request(form.getAttribute('action'), {
            method: 'POST',
            body: new FormData(form)
        }).then(function (res) {
            var json = res.json || {};

            if (json.status === 'verification_required') {
                setLoading(form, false);
                openVerifyPane(modal, json, {
                    fresh: true,
                    message: kind === 'login' ? T.notVerified : ''
                });
                return;
            }

            if ((json.status === 'ok' || json.status === 'registered') && res.ok) {
                storeClear();
                goTo(json.redirect);
                return;
            }

            setLoading(form, false);

            if (handleFailure(form, res)) {
                return;
            }

            followServer(res);
        }, function () {
            setLoading(form, false);
            showAlert(form, T.network, 'error');
        });
    }

    function submitVerifyForm(form) {
        var boxes = getBoxes(form);
        var incomplete = boxes.some(function (b) { return !/^\d$/.test(b.value); });

        clearErrors(form);

        if (incomplete) {
            boxes.forEach(function (b) {
                if (!/^\d$/.test(b.value)) b.classList.add('is-invalid');
            });
            setFieldError(form, 'code', T.codeIncomplete);
            focusFirstEmpty(form);
            return;
        }

        form._otpSubmitting = true;
        setLoading(form, true);

        request(form.getAttribute('action'), {
            method: 'POST',
            body: new FormData(form)
        }).then(function (res) {
            var json = res.json || {};

            if (res.ok && json.status === 'ok') {
                storeClear();
                showAlert(form, T.verified, 'success');
                goTo(json.redirect);
                return;
            }

            form._otpSubmitting = false;
            setLoading(form, false);

            if (res.status === 422) {
                boxes.forEach(function (b) { b.classList.add('is-invalid'); });
                applyErrors(form, json.errors || {}, json.message || T.generic);
                if (boxes[0]) {
                    boxes[0].focus();
                    boxes[0].select();
                }
                return;
            }

            if (handleFailure(form, res)) {
                return;
            }

            followServer(res);
        }, function () {
            form._otpSubmitting = false;
            setLoading(form, false);
            showAlert(form, T.network, 'error');
        });
    }

    function resendCode(button) {
        var pane = button.closest('[data-auth-pane="verify"]');
        var form = pane && pane.querySelector('form[data-auth-ajax="verify"]');

        if (!pane || button.disabled) {
            return;
        }

        button.disabled = true;
        clearErrors(form);

        request('/verification/resend', {method: 'GET'}).then(function (res) {
            button.disabled = false;
            var json = res.json || {};

            if (res.ok && json.code === 200) {
                var pending = storeGet() || {};
                pending.resendAt = Date.now() + getResendSeconds(pane) * 1000;
                pending.expiresAt = Date.now() + PENDING_TTL;
                if (pending.usernameValue) {
                    storeSet(pending);
                }
                resetBoxes(form);
                startCountdown(pane, pending.resendAt);
                showAlert(form, json.msg || T.resent, 'success');
                focusFirstEmpty(form);
                return;
            }

            if (res.status === 419) {
                showAlert(form, T.sessionExpired, 'error');
                return;
            }

            showAlert(form, json.msg || json.message || T.resendFailed, 'error');
        }, function () {
            button.disabled = false;
            showAlert(form, T.network, 'error');
        });
    }

    /* ------------------------------------------------------------------ */
    /* Gắn sự kiện (event delegation – hoạt động với mọi modal trên trang) */
    /* ------------------------------------------------------------------ */
    document.addEventListener('submit', function (event) {
        var form = event.target;
        var kind = form && form.getAttribute && form.getAttribute('data-auth-ajax');

        if (!kind) {
            return;
        }

        event.preventDefault();

        if (kind === 'verify') {
            submitVerifyForm(form);
        } else {
            submitAuthForm(form, kind);
        }
    });

    document.addEventListener('click', function (event) {
        var resendBtn = event.target.closest('[data-otp-resend]');
        if (resendBtn) {
            event.preventDefault();
            resendCode(resendBtn);
            return;
        }

        // Mở lại modal khi đang chờ xác minh -> đưa thẳng tới pane nhập mã.
        // (Listener gắn trực tiếp trên nút mở modal đã chạy trước, mở modal ở tab "Đăng nhập".)
        var openBtn = event.target.closest('[data-open-auth-modal="true"]');
        if (openBtn) {
            var pending = storeGet();
            if (pending) {
                window.setTimeout(function () {
                    var modal = document.querySelector('.edtika-auth-modal.is-open') || getModal(null);
                    openVerifyPane(modal, pending, {fresh: false});
                }, 0);
            }
        }
    });

    // Ô nhập OTP: chỉ nhận số, tự nhảy ô, hỗ trợ dán cả mã
    document.addEventListener('input', function (event) {
        var box = event.target;
        if (!box.classList || !box.classList.contains('edtika-otp__box')) {
            return;
        }

        var digits = (box.value || '').replace(/\D/g, '');

        if (digits.length > 1) {
            box.value = '';
            fillBoxes(box, digits);
            return;
        }

        box.value = digits;
        box.classList.toggle('is-filled', !!digits);
        box.classList.remove('is-invalid');

        if (digits) {
            var boxes = getBoxes(box.closest('form'));
            var next = boxes[boxes.indexOf(box) + 1];
            if (next) {
                next.focus();
                next.select();
            }
            maybeAutoSubmit(box.closest('form'));
        }
    });

    document.addEventListener('keydown', function (event) {
        var box = event.target;
        if (!box.classList || !box.classList.contains('edtika-otp__box')) {
            return;
        }

        var boxes = getBoxes(box.closest('form'));
        var index = boxes.indexOf(box);

        if (event.key === 'Backspace' && !box.value && index > 0) {
            event.preventDefault();
            boxes[index - 1].value = '';
            boxes[index - 1].classList.remove('is-filled');
            boxes[index - 1].focus();
        } else if (event.key === 'ArrowLeft' && index > 0) {
            event.preventDefault();
            boxes[index - 1].focus();
        } else if (event.key === 'ArrowRight' && index < boxes.length - 1) {
            event.preventDefault();
            boxes[index + 1].focus();
        }
    });

    document.addEventListener('paste', function (event) {
        var box = event.target;
        if (!box.classList || !box.classList.contains('edtika-otp__box')) {
            return;
        }

        var text = (event.clipboardData || window.clipboardData).getData('text') || '';
        var digits = text.replace(/\D/g, '');

        if (digits) {
            event.preventDefault();
            fillBoxes(getBoxes(box.closest('form'))[0], digits.slice(0, 5));
        }
    });

    document.addEventListener('focusin', function (event) {
        var box = event.target;
        if (box.classList && box.classList.contains('edtika-otp__box') && box.value) {
            box.select();
        }
    });

    // Bỏ trạng thái lỗi của ô thường khi người dùng sửa lại
    document.addEventListener('input', function (event) {
        var input = event.target;
        if (input.classList && input.classList.contains('is-invalid') && !input.classList.contains('edtika-otp__box')) {
            input.classList.remove('is-invalid');
            input.removeAttribute('aria-invalid');
        }
    });
})();
