{{--
    GĐ5: panel mua nhanh (side panel) 3 bước: Đơn hàng → Thanh toán → Hoàn tất.
    Mở bằng nút có class "js-sf-buy" và data-bundle-id, data-title, data-price, data-original.
    "Hoàn tất" là trang /payments/status có sẵn sau khi cổng thanh toán xác nhận.
--}}
@php
    $sfMethods = config('bundle_payment.methods', []);
@endphp

<div class="sf-veil js-sf-close"></div>

<aside class="sf-panel" role="dialog" aria-modal="true" aria-labelledby="sfPanelTitle">
    <div class="sf-panel__head">
        <h3 id="sfPanelTitle" class="font-16 font-weight-bold mb-0">{{ trans('bundle_payment.panel_title') }}</h3>
        <button type="button" class="sf-btn js-sf-close" aria-label="{{ trans('public.close') }}">×</button>
    </div>

    <form method="post" action="/bundles/quick-buy" class="d-flex flex-column flex-grow-1" style="min-height: 0;" id="sfQuickBuyForm">
        {{ csrf_field() }}
        <input type="hidden" name="bundle_id" value="">

        <div class="sf-panel__body">
            <div class="sf-steps">
                <div class="sf-step is-current"><span>1</span>{{ trans('bundle_payment.step_order') }}</div>
                <div class="sf-step"><span>2</span>{{ trans('bundle_payment.step_payment') }}</div>
                <div class="sf-step"><span>3</span>{{ trans('bundle_payment.step_done') }}</div>
            </div>

            <div class="sf-card p-16">
                <div class="font-12 sf-muted">{{ trans('bundle_payment.course') }}</div>
                <b class="font-16 js-sf-title"></b>
            </div>

            @guest
                <div class="sf-card p-16 mt-16">
                    <p class="font-14 mb-12">{{ trans('bundle_payment.login_required') }}</p>
                    <a href="/login" class="sf-btn sf-btn--primary w-100">{{ trans('auth.login') }}</a>
                </div>
            @else
                <div class="js-sf-owned d-none mt-16">
                    <div class="sf-owned">✓ {{ trans('bundle_payment.already_owned') }}</div>
                    <a href="#" class="sf-btn sf-btn--primary w-100 mt-12 js-sf-learn-link">{{ trans('bundle_payment.go_learn') }} →</a>
                </div>

                <div class="js-sf-order mt-16">
                    <div class="sf-line"><span>{{ trans('bundle_payment.sub_total') }}</span><b class="js-sf-subtotal">—</b></div>
                    <div class="sf-line js-sf-discount-row d-none"><span>{{ trans('bundle_payment.discount') }}</span><b class="text-success js-sf-discount">—</b></div>
                    <div class="sf-line js-sf-tax-row d-none"><span>{{ trans('bundle_payment.tax') }}</span><b class="js-sf-tax">—</b></div>
                    <div class="sf-line sf-line--total"><span>{{ trans('bundle_payment.total') }}</span><span class="js-sf-total">—</span></div>

                    <div class="sf-coupon">
                        <input type="text" name="coupon" class="form-control" placeholder="{{ trans('bundle_payment.coupon_placeholder') }}" autocomplete="off">
                        <button type="button" class="sf-btn js-sf-apply-coupon">{{ trans('bundle_payment.apply') }}</button>
                    </div>
                    <div class="sf-coupon-msg js-sf-coupon-msg"></div>

                    <div class="js-sf-methods-box mt-16">
                        <div class="font-14 font-weight-bold">{{ trans('bundle_payment.choose_method') }}</div>
                        <div class="sf-methods">
                            @foreach($sfMethods as $methodKey => $method)
                                <label class="sf-method mb-0">
                                    <input type="radio" name="gateway" value="{{ $methodKey }}" {{ $loop->first ? 'checked' : '' }}>
                                    <b>{{ $method['icon'] }} {{ $method['title'] }}</b>
                                    <div class="sf-muted">{{ $method['detail'] }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endguest
        </div>

        @auth
            <div class="sf-panel__foot js-sf-actions">
                <button type="submit" class="sf-btn sf-btn--primary sf-btn--lg w-100 js-sf-submit">{{ trans('bundle_payment.pay_now') }} →</button>
                <button type="button" class="sf-btn w-100 js-sf-add-cart">{{ trans('bundle_payment.add_to_cart') }}</button>
                <p class="font-12 sf-muted text-center mb-0">{{ trans('bundle_payment.secure_note') }}</p>
            </div>
        @endauth
    </form>
</aside>

@push('scripts_bottom')
    <script>
        (function () {
            var body = document.body;
            var form = document.getElementById('sfQuickBuyForm');
            if (!form) {
                return;
            }

            var isGuest = {{ auth()->check() ? 'false' : 'true' }};
            var csrf = form.querySelector('input[name="_token"]').value;
            var currentBundleId = null;

            function q(selector) {
                return document.querySelector(selector);
            }

            function setText(selector, value) {
                var el = q(selector);
                if (el) {
                    el.textContent = value;
                }
            }

            function toggle(selector, show) {
                var el = q(selector);
                if (el) {
                    el.classList.toggle('d-none', !show);
                }
            }

            function post(url, data) {
                return fetch(url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify(data)
                }).then(function (response) {
                    return response.json().then(function (json) {
                        return {ok: response.ok, data: json};
                    });
                });
            }

            function preview() {
                if (isGuest || !currentBundleId) {
                    return;
                }

                var coupon = form.querySelector('input[name="coupon"]').value;

                post('/bundles/quick-buy/preview', {bundle_id: currentBundleId, coupon: coupon}).then(function (result) {
                    var data = result.data || {};

                    if (data.owned) {
                        toggle('.js-sf-owned', true);
                        toggle('.js-sf-order', false);
                        toggle('.js-sf-actions', false);
                        q('.js-sf-learn-link').setAttribute('href', data.learn_url);
                        return;
                    }

                    toggle('.js-sf-owned', false);
                    toggle('.js-sf-order', true);
                    toggle('.js-sf-actions', true);

                    setText('.js-sf-subtotal', data.sub_total || '—');
                    setText('.js-sf-discount', data.has_discount ? ('-' + data.total_discount) : '');
                    setText('.js-sf-tax', data.tax_price || '');
                    setText('.js-sf-total', data.total || '—');
                    toggle('.js-sf-discount-row', !!data.has_discount);
                    toggle('.js-sf-tax-row', !!data.has_tax);
                    toggle('.js-sf-methods-box', !data.is_free);

                    var message = q('.js-sf-coupon-msg');
                    message.textContent = data.coupon_message || '';
                    message.className = 'sf-coupon-msg js-sf-coupon-msg ' + (data.coupon_applied ? 'text-success' : 'text-danger');

                    setText('.js-sf-submit', data.is_free ? @json(trans('bundle_payment.enroll_free')) : @json(trans('bundle_payment.pay_now') . ' →'));
                }).catch(function () {
                    setText('.js-sf-total', '—');
                });
            }

            function open(button) {
                currentBundleId = button.getAttribute('data-bundle-id');
                form.querySelector('input[name="bundle_id"]').value = currentBundleId;
                form.querySelector('input[name="coupon"]') && (form.querySelector('input[name="coupon"]').value = button.getAttribute('data-coupon') || '');
                setText('.js-sf-title', button.getAttribute('data-title') || '');
                setText('.js-sf-coupon-msg', '');
                body.classList.add('sf-panel-open');
                preview();
            }

            function close() {
                body.classList.remove('sf-panel-open');
            }

            document.addEventListener('click', function (event) {
                var buyButton = event.target.closest('.js-sf-buy');
                if (buyButton) {
                    event.preventDefault();
                    open(buyButton);
                    return;
                }

                if (event.target.closest('.js-sf-close')) {
                    close();
                    return;
                }

                if (event.target.closest('.js-sf-apply-coupon')) {
                    preview();
                    return;
                }

                if (event.target.closest('.js-sf-add-cart')) {
                    post('/cart/store', {item_id: currentBundleId, item_name: 'bundle_id'}).then(function (result) {
                        var data = result.data || {};
                        if (typeof window.showToast === 'function') {
                            window.showToast(result.ok ? 'success' : 'error', data.title || '', data.msg || '');
                        }
                        if (result.ok) {
                            close();
                        }
                    }).catch(function () {
                        // Server trả về trang (ví dụ đã sở hữu / không bán) thay vì JSON: tải lại để hiện thông báo
                        window.location.reload();
                    });
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    close();
                }
            });

            form.addEventListener('submit', function () {
                var submit = q('.js-sf-submit');
                if (submit) {
                    submit.disabled = true;
                }
            });
        })();
    </script>
@endpush
