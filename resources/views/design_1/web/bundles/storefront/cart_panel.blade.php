{{--
    Giỏ hàng + thanh toán DEMO dạng modal (theo "Mini Side Panel" trong bản demo của khách).
    - Mở được khi chưa đăng nhập: bấm nút giỏ hàng nổi, hoặc "Mua ngay" khi là khách.
    - 3 bước: Đơn hàng → Xác thực OTP & Thanh toán → Hoàn tất.
    - Chỉ để khách hàng xem trước: OTP, mã giảm giá, QR, cổng thanh toán đều là dữ liệu mô phỏng,
      KHÔNG tạo đơn, không trừ tiền, không gửi Zalo. Đơn thật vẫn đi qua /cart (cần đăng nhập).
    Đặt partial này bên trong thẻ <div class="edk"> để nhận CSS.
--}}
@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/edtika-cart-panel.css?v=1">
@endpush

<div class="pu-veil" id="pu-veil" aria-hidden="true"></div>
<aside class="pu-panel" id="pu-panel" role="dialog" aria-modal="true" aria-labelledby="pu-panel-title" aria-hidden="true">
    <div class="pu-panel-top">
        <h2 id="pu-panel-title">Giỏ hàng</h2>
        <button class="pu-icon-button" type="button" data-act="close" aria-label="Đóng">×</button>
    </div>
    <div class="pu-panel-content" id="pu-panel-body"></div>
    <div class="pu-panel-foot" id="pu-panel-footer"></div>
</aside>
<div id="pu-float" class="pu-floating-message" role="status" hidden></div>
<div class="pu-dialog" id="pu-dialog" role="dialog" aria-modal="true">
    <div class="pu-dialog__box">
        <h3 id="pu-dialog-title"></h3>
        <div id="pu-dialog-body"></div>
        <button type="button" class="pu-btn primary full" data-act="dialog-close">Đã hiểu</button>
    </div>
</div>

@push('scripts_bottom')
<script>
    (function () {
        'use strict';

        var IS_GUEST = @json(!auth()->check());
        var DEMO_OTP = '246810';
        var OTP_MINUTES = 15;
        var QR_MINUTES = 15;
        var KEY = 'edtika-cart-demo-v1';

        var methods = [
            {id: 'qr', name: 'VietQR', detail: 'Quét QR · ưu tiên', icon: '▦'},
            {id: 'wallet', name: 'Ví điện tử', detail: 'MoMo / ZaloPay', icon: '◈'},
            {id: 'atm', name: 'Thẻ ATM', detail: 'VNPay / OnePay', icon: '▤'},
            {id: 'card', name: 'Thẻ quốc tế', detail: 'Visa / Mastercard', icon: '▧'}
        ];
        // Mã giảm giá mô phỏng (giống bản demo)
        var DEMO_COUPONS = {PROMO: 10, EDTIKA10: 10, BACK2SCHOOL: 15, WELCOME5: 5};

        var $ = function (id) { return document.getElementById(id); };
        var panel = $('pu-panel'), veil = $('pu-veil');
        if (!panel) {
            return;
        }

        // Thông tin giữ lại giữa các lần mở (chỉ trong trình duyệt)
        var keep = {phone: '', verifiedPhone: '', coupon: '', method: 'qr', provider: 'MoMo'};
        try { Object.assign(keep, JSON.parse(localStorage.getItem(KEY) || '{}')); } catch (e) {}
        var save = function () { try { localStorage.setItem(KEY, JSON.stringify(keep)); } catch (e) {} };

        var s = {}; // trạng thái của lần mở hiện tại
        var items = [];
        var priceFormat = '__N__';
        var opened = false, loading = false, lastFocus = null;

        function esc(value) {
            return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
                return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c];
            });
        }
        function price(n) {
            n = Math.max(0, Math.round(n || 0));
            return priceFormat.replace('__N__', n.toLocaleString('vi-VN'));
        }
        function clock(left) {
            return Math.floor(left / 60) + ':' + String(left % 60).padStart(2, '0');
        }
        function percent(code) {
            return DEMO_COUPONS[String(code || '').trim().toUpperCase()] || 0;
        }
        function sellable() {
            return items.filter(function (i) { return !i.owned; });
        }
        function breakdown() {
            var sub = sellable().reduce(function (acc, i) { return acc + i.price; }, 0);
            var discount = Math.round(sub * percent(keep.coupon) / 100);
            return {sub: sub, discount: discount, total: Math.max(0, sub - discount)};
        }
        function otpVerified() {
            var n = (keep.phone || '').replace(/\D/g, '');
            return !!keep.verifiedPhone && n === keep.verifiedPhone;
        }
        function toast(text) {
            var el = $('pu-float');
            el.textContent = text;
            el.hidden = false;
            clearTimeout(window.puCartToastTimer);
            window.puCartToastTimer = setTimeout(function () { el.hidden = true; }, 3200);
        }
        function dialog(title, html) {
            $('pu-dialog-title').textContent = title;
            $('pu-dialog-body').innerHTML = html;
            $('pu-dialog').classList.add('on');
        }
        function updateCounters(count) {
            document.querySelectorAll('.js-edk-cart-count').forEach(function (el) {
                el.textContent = count;
            });
        }

        /* ---------------- Dữ liệu ---------------- */

        function load() {
            loading = true;
            draw();
            var url = '/bundles/cart-preview' + (s.mode === 'quick' ? '?bundle_id=' + encodeURIComponent(s.quickId) : '');

            return fetch(url, {credentials: 'same-origin', headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}})
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    items = data.items || [];
                    priceFormat = data.price_format || '__N__';
                    if (s.mode === 'cart') {
                        updateCounters(items.length);
                    }
                })
                .catch(function () {
                    items = [];
                    toast('Không tải được giỏ hàng. Vui lòng thử lại.');
                })
                .then(function () {
                    loading = false;
                    drawAtTop();
                });
        }

        function removeItem(key) {
            fetch('/cart/' + encodeURIComponent(key) + '/delete', {
                credentials: 'same-origin',
                headers: {'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}
            }).then(function () {
                return load();
            });
        }

        /* ---------------- Mở / đóng ---------------- */

        function open(mode, quickId) {
            s = {mode: mode, quickId: quickId || null, step: 'order', otpSent: false, otpExpiresAt: 0, legal: false, order: '', deadline: 0, result: '', lastItems: [], lastTotal: 0};
            lastFocus = document.activeElement;
            opened = true;
            panel.classList.add('on');
            veil.classList.add('on');
            panel.setAttribute('aria-hidden', 'false');
            document.body.classList.add('edk-cart-open');
            load();
            var closeButton = panel.querySelector('.pu-icon-button');
            if (closeButton) {
                closeButton.focus({preventScroll: true});
            }
        }

        function close() {
            opened = false;
            panel.classList.remove('on');
            veil.classList.remove('on');
            panel.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('edk-cart-open');
            if (lastFocus && lastFocus.isConnected && lastFocus.focus) {
                lastFocus.focus({preventScroll: true});
            }
        }

        /* ---------------- Vẽ giao diện ---------------- */

        function draw() {
            if (!opened) {
                return;
            }
            var heading = $('pu-panel-title'), body = $('pu-panel-body'), foot = $('pu-panel-footer');
            var isOrder = s.step === 'order', isResult = s.step === 'result', paying = s.step === 'payment';
            heading.textContent = isOrder ? (s.mode === 'cart' ? 'Giỏ hàng' : 'Thông tin đơn hàng') : isResult ? 'Hoàn tất thanh toán' : 'Xác thực & Thanh toán';

            if (loading) {
                body.innerHTML = '<div class="pu21-loading">Đang tải giỏ hàng…</div>';
                foot.innerHTML = '';
                return;
            }

            var active = isResult ? 3 : isOrder ? 1 : 2;
            var progress = '<div class="pu21-progress" aria-label="Tiến trình thanh toán">' + [['Đơn hàng', 1], ['Xác thực & Thanh toán', 2], ['Hoàn tất', 3]].map(function (x) {
                var cls = active === x[1] ? 'current' : active > x[1] ? 'done' : '';
                return '<div class="pu21-prog ' + cls + '"><span>' + (active > x[1] ? '✓' : x[1]) + '</span><small>' + x[0] + '</small></div>';
            }).join('') + '</div>';

            if (isOrder) {
                body.innerHTML = progress + '<p class="pu21-stage-lead">Xem khóa học đã chọn, mã giảm giá và tổng tiền trước khi tiếp tục.</p>' + orderCard() + disclaimer('Không xử lý thanh toán hoặc gửi tin nhắn thật.');
                foot.innerHTML = orderFooter();
                syncCoupon();
            } else if (!isResult) {
                body.innerHTML = progress
                    + '<div class="pu21-stage-lead"><button class="pu21-back-stage" type="button" data-act="back-order">← Quay lại thông tin đơn hàng</button><div>Nhận mã Zalo OTP, chọn phương thức thanh toán và xác nhận điều khoản.</div></div>'
                    + '<div id="pu22-setup-area" class="' + (paying ? 'pu22-setup-locked' : '') + '">' + contactCard() + methodCard() + legalCard() + '</div>'
                    + (paying ? '<section id="pu22-inline-payment" class="pu22-inline-payment"><h3>Thanh toán đơn hàng</h3><div class="pu22-lock-note">Đơn hàng và phương thức đã được khóa cho giao dịch này. Bạn có thể quay lại để chỉnh sửa.</div>' + paymentBody() + '</section>' : '')
                    + disclaimer('OTP và thanh toán đều là dữ liệu minh họa.');
                foot.innerHTML = paying ? paymentFooter() : setupFooter();
                if (paying) {
                    body.querySelectorAll('#pu22-setup-area input, #pu22-setup-area button').forEach(function (el) { el.disabled = true; });
                }
            } else {
                body.innerHTML = progress + resultBody();
                foot.innerHTML = resultFooter();
            }
        }

        function drawAtTop() {
            draw();
            var scroller = $('pu-panel-body');
            if (!scroller) {
                return;
            }
            var target = s.step === 'payment' ? $('pu22-inline-payment') : null;
            scroller.scrollTop = target ? Math.max(0, target.offsetTop - scroller.offsetTop - 10) : 0;
        }

        function disclaimer(text) {
            return '<p class="pu21-disclaimer">DEMO · ' + text + '</p>';
        }

        function productCard(item, removable) {
            return '<div class="pu21-product"><div class="pu21-product-icon">' + (/writing/i.test(item.title) ? '✎' : '▤') + '</div>'
                + '<div class="pu21-product-info"><strong>' + esc(item.title) + '</strong><small>' + esc(item.tag) + '</small>'
                + (item.owned ? '<div class="pu21-feedback success" style="margin-top:6px">✓ Bạn đã sở hữu khóa này · không tính tiền</div>' : '')
                + '<div class="pu21-prices"><b>' + price(item.price) + '</b>' + (item.old > item.price ? '<s>' + price(item.old) + '</s><span>' + esc(item.off) + '</span>' : '') + '</div></div>'
                + (removable && item.remove_key !== null ? '<button type="button" class="pu21-remove" data-act="remove" data-arg="' + esc(item.remove_key) + '" aria-label="Xóa ' + esc(item.title) + ' khỏi giỏ">×</button>' : '')
                + '</div>';
        }

        function orderCard() {
            var b = breakdown();
            var html = '<section class="pu21-block"><div class="pu21-section-head"><span class="pu21-section-icon">1</span><div><h3>Thông tin đơn hàng</h3><p>' + items.length + ' khóa học · giá được cập nhật theo voucher</p></div></div>';

            if (!items.length) {
                return html + '<div class="pu21-empty"><b>Giỏ hàng đang trống</b><p>Vui lòng chọn khóa học trước khi thanh toán.</p><a class="pu-btn" href="/classes" data-act="close">Khám phá khóa học</a></div></section>';
            }

            html += items.map(function (i) { return productCard(i, s.mode === 'cart'); }).join('');
            html += '<div class="pu21-voucher"><label class="pu21-label" for="pu21-voucher">Mã ưu đãi / mã giới thiệu</label><div class="pu21-inputline"><input id="pu21-voucher" autocomplete="off" spellcheck="false" placeholder="Nhập mã ưu đãi" value="' + esc(keep.coupon) + '"/><span class="pu21-voucher-mark" id="pu21-voucher-mark">' + (percent(keep.coupon) ? '✓' : '%') + '</span></div><p class="pu21-hint" id="pu21-voucher-note" aria-live="polite"></p></div>';
            html += '<div class="pu21-breakdown"><div><span>Giá khóa học</span><strong id="pu21-sub">' + price(b.sub) + '</strong></div>'
                + '<div><span>Ưu đãi <small id="pu21-disc-pct">' + (percent(keep.coupon) ? '(' + percent(keep.coupon) + '%)' : '') + '</small></span><strong class="pu21-discount" id="pu21-disc">' + (b.discount ? '-' + price(b.discount) : '—') + '</strong></div>'
                + '<div class="pu21-breakdown-total"><span>Tổng thanh toán</span><strong id="pu21-total">' + price(b.total) + '</strong></div></div>';
            return html + '</section>';
        }

        function orderFooter() {
            return '<div class="pu21-sticky-summary"><span>Tổng đơn hàng</span><strong id="pu21-foot-total">' + price(breakdown().total) + '</strong></div>'
                + '<button class="pu-btn primary full pu21-primary-footer" type="button" data-act="next">Tiếp tục xác thực & thanh toán <span>→</span></button>';
        }

        function contactCard() {
            var left = Math.max(0, Math.ceil(((s.otpExpiresAt || 0) - Date.now()) / 1000));
            var expired = s.otpSent && left === 0;
            var verified = otpVerified();
            var html = '<section class="pu21-block"><div class="pu21-section-head"><span class="pu21-section-icon">2</span><div><h3>Xác thực SĐT bằng mã Zalo</h3><p>Không cần mật khẩu · xác thực trước khi thanh toán</p></div></div>'
                + '<label class="pu21-label" for="pu21-phone">Số điện thoại nhận mã Zalo (OTP)</label>'
                + '<div class="pu21-inputline"><input id="pu21-phone" inputmode="tel" autocomplete="tel" placeholder="VD: 0912 345 678" value="' + esc(keep.phone) + '" maxlength="13" ' + (verified ? 'readonly' : '') + '/>'
                + '<button class="pu21-small" type="button" data-act="send-otp" ' + (s.otpSent && !expired ? 'disabled' : '') + '>' + (verified ? 'Gửi mã mới' : s.otpSent && !expired ? 'Đã gửi mã' : 'Gửi mã Zalo') + '</button></div>';

            if (verified) {
                html += '<div class="pu21-feedback success"><span>✓</span> Đã xác thực SĐT ' + esc(keep.verifiedPhone) + ' cho đơn hàng.</div>';
            } else if (s.otpSent) {
                html += '<div class="pu21-otp"><label class="pu21-label" for="pu21-otp">Nhập mã Zalo (OTP) gồm 6 số</label>'
                    + '<div class="pu21-inputline"><input id="pu21-otp" type="text" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="Nhập mã OTP" ' + (expired ? 'disabled' : '') + '/><button type="button" class="pu21-small primary" data-act="verify-otp" ' + (expired ? 'disabled' : '') + '>Xác nhận</button></div>'
                    + '<div class="pu22-otp-time ' + (expired ? 'expired' : '') + '"><span>' + (expired ? 'Mã OTP đã hết hiệu lực' : 'Mã OTP hết hạn sau') + ' <b id="pu22-otp-timer">' + clock(left) + '</b></span><button class="pu22-otp-resend" type="button" data-act="resend-otp">Nhận lại mã OTP</button></div>'
                    + '<div class="pu21-feedback">Mã demo: <b>' + DEMO_OTP + '</b> · Không gửi tin Zalo thật. Nhấn <b>Nhận lại mã OTP</b> để bắt đầu phiên ' + OTP_MINUTES + ' phút mới.</div></div>';
            } else {
                html += '<p class="pu21-hint">Nhấn Gửi mã Zalo để nhận OTP mô phỏng, có hiệu lực ' + OTP_MINUTES + ' phút.</p>';
            }
            return html + '</section>';
        }

        function methodCard() {
            var html = '<section class="pu21-block"><div class="pu21-section-head"><span class="pu21-section-icon">3</span><div><h3>Phương thức thanh toán</h3><p>Chọn một phương thức để tiếp tục</p></div></div><div class="pu21-methods">';
            html += methods.map(function (m) {
                return '<label class="pu21-method ' + (keep.method === m.id ? 'selected' : '') + '"><input type="radio" name="pu21-method" value="' + m.id + '" ' + (keep.method === m.id ? 'checked' : '') + '/><span class="pu21-method-ico">' + m.icon + '</span><span class="pu21-method-copy"><b>' + m.name + '</b><small>' + m.detail + '</small></span></label>';
            }).join('') + '</div>';

            var providers = keep.method === 'wallet' ? ['MoMo', 'ZaloPay'] : keep.method === 'atm' ? ['VNPay', 'OnePay'] : [];
            if (providers.length) {
                html += '<div class="pu21-submethod">' + providers.map(function (x) {
                    return '<button type="button" class="pu21-pick ' + (keep.provider === x ? 'selected' : '') + '" data-act="provider" data-arg="' + x + '">' + x + '</button>';
                }).join('') + '</div>';
            }
            return html + '</section>';
        }

        function legalCard() {
            return '<div class="pu21-legal"><label class="pu21-legal-row"><input type="checkbox" id="pu21-legal" ' + (s.legal ? 'checked' : '') + ' aria-label="Tôi đồng ý với chính sách hoàn tiền và điều khoản dịch vụ"/><span>Tôi đồng ý với <button type="button" data-act="legal-doc" data-arg="refund">Chính sách hoàn tiền</button> và <button type="button" data-act="legal-doc" data-arg="terms">Điều khoản dịch vụ</button>.</span></label><p>Phải xác nhận trước khi thanh toán.</p></div>';
        }

        function setupFooter() {
            return '<div class="pu21-sticky-summary"><span>Tổng thanh toán</span><strong id="pu21-foot-total">' + price(breakdown().total) + '</strong></div>'
                + '<button class="pu-btn primary full pu21-primary-footer" type="button" data-act="go-payment">' + (keep.method === 'qr' ? 'Tạo mã VietQR' : 'Tiếp tục thanh toán') + ' <span>→</span></button>'
                + '<small class="pu21-footnote">🔒 Chỉ tiếp tục khi đã xác thực OTP và đồng ý điều khoản.</small>';
        }

        function gatewayName() {
            return keep.method === 'wallet' ? (keep.provider || 'MoMo') : keep.method === 'atm' ? (keep.provider === 'OnePay' ? 'OnePay' : 'VNPay') : 'Visa / Mastercard';
        }

        function qrSvg() {
            var r = '<svg role="img" aria-label="QR minh họa — không dùng để chuyển khoản" viewBox="0 0 180 180" xmlns="http://www.w3.org/2000/svg"><rect width="180" height="180" rx="8" fill="#fff"/>';
            var finders = [[0, 0], [22, 0], [0, 22]];
            var finder = function (ox, oy, x, y) {
                var a = x - ox, b = y - oy;
                return a >= 0 && b >= 0 && a < 7 && b < 7 && ((a === 0 || b === 0 || a === 6 || b === 6) || (a >= 2 && a <= 4 && b >= 2 && b <= 4));
            };
            for (var y = 0; y < 29; y++) {
                for (var x = 0; x < 29; x++) {
                    var inFinder = finders.some(function (f) { return x >= f[0] && x < f[0] + 7 && y >= f[1] && y < f[1] + 7; });
                    var on = inFinder ? finders.some(function (f) { return finder(f[0], f[1], x, y); }) : ((x * 37 + y * 19 + x * y * 3 + 11) % 13 < 6);
                    if (on) {
                        r += '<rect x="' + (3 + x * 6) + '" y="' + (3 + y * 6) + '" width="6" height="6" fill="#2D1B39"/>';
                    }
                }
            }
            return r + '<rect x="58" y="75" width="64" height="31" rx="7" fill="#fff" stroke="#5A2B81"/><text x="90" y="96" font-size="14" font-weight="900" fill="#5A2B81" text-anchor="middle">DEMO</text></svg>';
        }

        function paymentBody() {
            var left = Math.max(0, Math.ceil((s.deadline - Date.now()) / 1000));
            var total = price(s.lastTotal);

            if (keep.method === 'qr') {
                if (left <= 0) {
                    return '<div class="pu21-result-center"><div class="pu21-status expiry">◷</div><h2>Mã VietQR đã hết hạn</h2><p>Phiên ' + QR_MINUTES + ' phút đã kết thúc. Chưa xác nhận được khoản tiền nào.</p><button class="pu-btn primary" type="button" data-act="renew">Tạo mã QR mới</button></div>';
                }
                return '<div class="pu21-payment-info"><span class="pu21-pay-tag">VietQR · ' + QR_MINUTES + ' phút hiệu lực</span><h3>Quét mã để thanh toán</h3><p>Hướng điện thoại vào mã VietQR. Phiên bản demo không kết nối ngân hàng.</p><strong class="pu21-pay-amount">' + total + '</strong>'
                    + '<div class="pu21-qr">' + qrSvg() + '<div class="pu21-qr-watermark">QR MINH HỌA · KHÔNG CHUYỂN TIỀN</div></div>'
                    + '<div class="pu21-clock">⏱ Mã QR hết hạn sau <b id="pu21-timer">' + clock(left) + '</b></div>'
                    + '<div class="pu21-transfer"><div><small>STK demo (không tồn tại)</small><span>0000 0000 0000</span><button type="button" data-act="copy" data-arg="0000 0000 0000" class="pu21-copy">Sao chép</button></div><div><small>Nội dung chuyển khoản</small><span>' + esc(s.order) + '</span><button type="button" data-act="copy" data-arg="' + esc(s.order) + '" class="pu21-copy">Sao chép</button></div></div>'
                    + '<div class="pu21-feedback">Khi chạy thật: VietQR tạo mã động và webhook ngân hàng xác nhận. Ở đây chọn nút mô phỏng bên dưới để thử trạng thái.</div></div>';
            }

            var gateway = gatewayName();
            return '<div class="pu21-payment-info"><span class="pu21-pay-tag">Chuyển tới cổng thanh toán</span><h3>Đang kết nối ' + esc(gateway) + '</h3><div class="pu21-gateway"><div class="pu21-spinner" role="status" aria-label="Đang kết nối"></div><b>Khởi tạo giao dịch ' + esc(gateway) + '</b><p>Mô phỏng quá trình mở SDK/Popup/Redirect của đối tác. Không nhập số thẻ, mật khẩu hoặc OTP ngân hàng thật.</p><div class="pu21-provider-icons">🔒 <strong>' + esc(gateway) + '</strong> · DEMO</div></div><div class="pu21-gateway-summary">Mã đơn: <b>' + esc(s.order) + '</b><br/>Số tiền: <strong>' + total + '</strong></div></div>';
        }

        function paymentFooter() {
            var edit = '<button type="button" class="pu21-secondary-link full" data-act="edit-order">← Quay lại xác thực & phương thức</button>';
            var qr = keep.method === 'qr';
            return '<div class="pu21-demo-hint">' + (qr ? 'DEMO kết quả từ webhook ngân hàng' : 'Mô phỏng phản hồi của cổng thanh toán') + '</div>'
                + '<div class="pu21-foot-row"><button type="button" class="pu-btn" data-act="complete" data-arg="failed">' + (qr ? 'Thất bại' : 'Cổng báo lỗi') + '</button><button type="button" class="pu-btn primary" data-act="complete" data-arg="success">' + (qr ? '✓ Xác nhận thành công' : 'Thanh toán thành công') + '</button></div>'
                + '<div class="pu21-foot-row"><button type="button" class="pu21-secondary-link" data-act="complete" data-arg="pending">' + (qr ? 'Chờ đối soát' : 'Giao dịch chờ đối soát') + '</button><button type="button" class="pu21-secondary-link" data-act="complete" data-arg="cancelled">' + (qr ? 'Hủy giao dịch' : 'Hủy') + '</button></div>' + edit;
        }

        function resultBody() {
            var success = s.result === 'success', pending = s.result === 'pending', cancelled = s.result === 'cancelled';
            var label = success ? 'Thanh toán thành công' : pending ? 'Giao dịch chờ xác nhận' : cancelled ? 'Giao dịch đã hủy' : 'Thanh toán chưa thành công';
            var desc = success ? 'Đơn hàng được ghi nhận trong bản demo (chưa tạo đơn và chưa kích hoạt khóa học thật).'
                : pending ? 'Ngân hàng chưa trả kết quả cuối cùng. Không tạo giao dịch trùng; chờ đối soát hoặc liên hệ hỗ trợ.'
                : cancelled ? 'Không ghi nhận khoản thanh toán nào. Bạn có thể thử lại khi sẵn sàng.'
                : 'Thanh toán bị từ chối hoặc cổng đang gặp lỗi. Bạn chưa bị trừ tiền trong bản demo.';

            var html = '<div class="pu21-result-center"><div class="pu21-status ' + (success ? 'ok' : pending ? 'wait' : 'bad') + '">' + (success ? '✓' : pending ? '◷' : '!') + '</div><h2>' + label + '</h2><p>' + desc + '</p><span class="pu21-ref">' + esc(s.order) + '</span>'
                + '<div class="pu21-completed-items">' + s.lastItems.map(function (i) { return '<div><span>✓</span>' + esc(i.title) + '</div>'; }).join('') + '</div>'
                + '<div class="pu21-result-total">Tổng đơn hàng <strong>' + price(s.lastTotal) + '</strong></div>'
                + (success ? '<div class="pu21-feedback success">✓ Zalo OA gửi biên nhận / xác nhận qua webhook: <b>mô phỏng</b>, không gửi tin thật.</div>' : '') + '</div>';

            if (success) {
                if (IS_GUEST) {
                    html += '<section class="pu22-completion" id="pu22-completion"><h3>Hoàn tất thanh toán — Bước tiếp theo</h3><p>Để truy cập khóa học, vui lòng <b>tạo tài khoản</b> nếu là học viên mới hoặc <b>đăng nhập</b> nếu đã có tài khoản EDTIKA.</p>'
                        + '<div class="pu22-account-options"><a class="pu-btn primary" href="/register">Tạo tài khoản</a><a class="pu-btn" href="/login" data-open-auth-modal="true">Đăng nhập</a></div></section>';
                } else {
                    html += '<section class="pu22-completion" id="pu22-completion"><h3>Đây là bản xem trước</h3><p>Bạn đang đăng nhập. Để mua thật, hãy thanh toán tại trang giỏ hàng.</p><a class="pu-btn primary full" href="/cart">Đến trang giỏ hàng →</a></section>';
                }
            }
            return html;
        }

        function resultFooter() {
            if (s.result === 'success') {
                return '<button type="button" class="pu21-secondary-link full" data-act="close">Tiếp tục xem khóa học</button>';
            }
            if (s.result === 'pending') {
                return '<button type="button" class="pu-btn primary full" data-act="complete" data-arg="success">Demo nhận xác nhận thành công</button><button type="button" class="pu21-secondary-link full" data-act="retry">Quay lại chọn phương thức</button>';
            }
            return '<button type="button" class="pu-btn primary full pu21-primary-footer" data-act="retry">Thử lại thanh toán →</button><button type="button" class="pu21-secondary-link full" data-act="close">Đóng</button>';
        }

        function syncCoupon() {
            var note = $('pu21-voucher-note');
            if (!note) {
                return;
            }
            note.textContent = keep.coupon
                ? (percent(keep.coupon) ? 'Áp dụng ' + percent(keep.coupon) + '% giảm giá (' + keep.coupon + ').' : 'Mã không hợp lệ — đơn hàng chưa được giảm giá.')
                : 'Mã demo: PROMO · EDTIKA10 · BACK2SCHOOL · WELCOME5';
        }

        function updateTotals() {
            var b = breakdown();
            var values = {
                'pu21-sub': price(b.sub),
                'pu21-disc': b.discount ? '-' + price(b.discount) : '—',
                'pu21-disc-pct': percent(keep.coupon) ? '(' + percent(keep.coupon) + '%)' : '',
                'pu21-total': price(b.total),
                'pu21-foot-total': price(b.total),
                'pu21-voucher-mark': percent(keep.coupon) ? '✓' : '%'
            };
            Object.keys(values).forEach(function (id) {
                if ($(id)) {
                    $(id).textContent = values[id];
                }
            });
            syncCoupon();
        }

        /* ---------------- Hành động ---------------- */

        var actions = {
            'close': close,
            'dialog-close': function () { $('pu-dialog').classList.remove('on'); },
            'remove': removeItem,
            'next': function () {
                if (!sellable().length) {
                    return toast(items.length ? 'Bạn đã sở hữu các khóa trong giỏ.' : 'Vui lòng thêm ít nhất một khóa vào giỏ hàng.');
                }
                s.step = 'setup';
                drawAtTop();
            },
            'back-order': function () { s.step = 'order'; s.deadline = 0; drawAtTop(); },
            'send-otp': sendOtp,
            'resend-otp': sendOtp,
            'verify-otp': function () {
                var code = (($('pu21-otp') || {}).value || '').trim();
                if (!s.otpSent || Date.now() >= (s.otpExpiresAt || 0)) {
                    draw();
                    return toast('Mã OTP đã hết hạn. Hãy nhấn Nhận lại mã OTP.');
                }
                if (code !== DEMO_OTP) {
                    return toast('OTP chưa đúng. Trong demo, hãy nhập ' + DEMO_OTP + '.');
                }
                keep.verifiedPhone = keep.phone.replace(/\D/g, '');
                s.otpSent = false;
                s.otpExpiresAt = 0;
                save();
                draw();
                toast('Số điện thoại đã xác thực bằng Zalo OTP (demo).');
            },
            'provider': function (x) { keep.provider = x; save(); draw(); },
            'legal-doc': function (doc) {
                var title = doc === 'refund' ? 'Chính sách hoàn tiền' : 'Điều khoản dịch vụ';
                dialog(title, '<p>Đây là vị trí trình bày <b>' + title + '</b> để người mua xem trước khi xác nhận.</p><p>Nội dung chính thức sẽ được bổ sung khi triển khai thật.</p>');
            },
            'go-payment': function () {
                if (!sellable().length) {
                    return toast('Giỏ hàng chưa có khóa học.');
                }
                if (!otpVerified()) {
                    return toast('Vui lòng xác thực mã Zalo OTP trước khi thanh toán.');
                }
                if (!s.legal) {
                    return toast('Vui lòng đồng ý Chính sách hoàn tiền & Điều khoản dịch vụ.');
                }
                var now = Date.now();
                s.step = 'payment';
                s.deadline = now + QR_MINUTES * 60 * 1000;
                s.order = 'ED-DEMO-' + String(now).slice(-8);
                s.result = '';
                s.lastTotal = breakdown().total;
                s.lastItems = sellable();
                drawAtTop();
            },
            'edit-order': function () { s.step = 'setup'; s.deadline = 0; drawAtTop(); },
            'renew': function () { s.deadline = Date.now() + QR_MINUTES * 60 * 1000; s.order = 'ED-DEMO-' + String(Date.now()).slice(-8); drawAtTop(); },
            'complete': function (result) {
                if (s.step === 'result' && s.result === 'success') {
                    return;
                }
                s.result = result;
                s.step = 'result';
                drawAtTop();
            },
            'retry': function () { s.step = 'setup'; s.result = ''; drawAtTop(); },
            'copy': function (text) {
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(function () { toast('Đã sao chép (demo)'); }, function () { toast('Không thể sao chép tự động.'); });
                } else {
                    toast('Không thể sao chép tự động.');
                }
            }
        };

        function sendOtp() {
            var n = (keep.phone || '').replace(/\D/g, '');
            if (!/^0\d{9}$/.test(n)) {
                return toast('Nhập số điện thoại Việt Nam hợp lệ gồm 10 chữ số.');
            }
            keep.phone = n;
            keep.verifiedPhone = '';
            s.otpSent = true;
            s.otpExpiresAt = Date.now() + OTP_MINUTES * 60 * 1000;
            save();
            draw();
            setTimeout(function () {
                if (opened && $('pu21-otp')) {
                    $('pu21-otp').focus({preventScroll: true});
                }
            }, 0);
            toast('Đã tạo mã Zalo OTP mô phỏng, có hiệu lực ' + OTP_MINUTES + ' phút. Mã thử: ' + DEMO_OTP);
        }

        /* ---------------- Sự kiện ---------------- */

        document.addEventListener('click', function (event) {
            // Nút giỏ hàng nổi: mở panel thay vì chuyển sang /cart
            if (event.target.closest('.js-edk-open-cart')) {
                event.preventDefault();
                open('cart');
                return;
            }

            // Khách chưa đăng nhập bấm "Mua ngay": mở panel demo ở chế độ mua 1 khóa
            var buy = IS_GUEST ? event.target.closest('.js-sf-buy') : null;
            if (buy) {
                event.preventDefault();
                open('quick', buy.getAttribute('data-bundle-id'));
                return;
            }

            if (event.target === veil) {
                close();
                return;
            }

            var actionEl = event.target.closest('#pu-panel [data-act], #pu-dialog [data-act]');
            if (actionEl && actions[actionEl.getAttribute('data-act')]) {
                if (actionEl.tagName === 'BUTTON') {
                    event.preventDefault();
                }
                actions[actionEl.getAttribute('data-act')](actionEl.getAttribute('data-arg'));
            }
        });

        panel.addEventListener('input', function (event) {
            if (event.target.id === 'pu21-voucher') {
                keep.coupon = event.target.value.toUpperCase();
                save();
                updateTotals();
            } else if (event.target.id === 'pu21-phone' && s.step === 'setup') {
                keep.phone = event.target.value;
                s.otpSent = false;
                s.otpExpiresAt = 0;
                save();
            }
        });

        panel.addEventListener('change', function (event) {
            if (event.target.name === 'pu21-method') {
                keep.method = event.target.value;
                keep.provider = keep.method === 'wallet' ? 'MoMo' : keep.method === 'atm' ? 'VNPay' : keep.provider;
                save();
                draw();
            } else if (event.target.id === 'pu21-legal') {
                s.legal = event.target.checked;
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key !== 'Escape') {
                return;
            }
            if ($('pu-dialog').classList.contains('on')) {
                $('pu-dialog').classList.remove('on');
            } else if (opened) {
                close();
            }
        });

        // Sau khi thêm vào giỏ thành công (add_to_cart_script), mở giỏ hàng như bản demo
        document.addEventListener('edk:cart-added', function () {
            open('cart');
        });

        // Đồng hồ OTP và QR
        setInterval(function () {
            if (!opened) {
                return;
            }
            if (s.otpSent) {
                var left = Math.max(0, Math.ceil(((s.otpExpiresAt || 0) - Date.now()) / 1000));
                var timer = $('pu22-otp-timer');
                if (timer) {
                    timer.textContent = clock(left);
                    if (left === 0 && s.step === 'setup') {
                        draw();
                        toast('Mã Zalo OTP hết hạn. Vui lòng nhận lại mã OTP.');
                    }
                }
            }
            if (s.step === 'payment' && keep.method === 'qr') {
                var qrLeft = Math.max(0, Math.ceil((s.deadline - Date.now()) / 1000));
                var t = $('pu21-timer');
                if (t) {
                    t.textContent = clock(qrLeft);
                    if (qrLeft === 0) {
                        draw();
                    }
                }
            }
        }, 1000);
    })();
</script>
@endpush
