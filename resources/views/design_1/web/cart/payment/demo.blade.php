@extends("design_1.web.layouts.app")

{{-- GĐ5: trang thanh toán của cổng Demo. Không xử lý tiền thật. --}}

@push('styles_top')
    <style>
        .dp-wrap { max-width: 880px; margin: 48px auto; padding: 0 16px; color: #21182e; }
        .dp-card { background: #fff; border: 1px solid #eee8f4; border-radius: 24px; box-shadow: 0 10px 32px rgba(51, 24, 82, .06); padding: 24px; }
        .dp-badge { display: inline-block; font-size: 11px; font-weight: 700; letter-spacing: .08em; padding: 4px 10px; border-radius: 99px; background: #FEF1F1; color: #e03043; }
        .dp-grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 24px; margin-top: 16px; }
        @media (max-width: 767px) { .dp-grid { grid-template-columns: 1fr; } }
        .dp-methods { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
        .dp-method { border: 1px solid #eee8f4; border-radius: 14px; padding: 10px 12px; cursor: pointer; text-align: left; background: #fff; }
        .dp-method.is-active { border-color: #5A2B81; background: #EBE5FC; }
        .dp-method b { display: block; font-size: 14px; }
        .dp-method span { font-size: 12px; color: #756a82; }
        .dp-qr { display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 16px; border-radius: 18px; background: #fcfbfe; border: 1px solid #eee8f4; }
        .dp-qr svg { width: 200px; height: 200px; }
        .dp-amount { font-size: 28px; font-weight: 800; color: #5A2B81; }
        .dp-row { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; padding: 8px 0; border-top: 1px solid #eee8f4; }
        .dp-timer { font-weight: 700; color: #e03043; }
        .dp-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
        .dp-btn { flex: 1; min-width: 140px; text-align: center; padding: 11px 14px; border-radius: 12px; font-weight: 600; border: 1px solid #eee8f4; color: #21182e; background: #fff; }
        .dp-btn--primary { background: #5A2B81; border-color: #5A2B81; color: #fff !important; }
        .dp-btn--danger { color: #e03043; }
        .dp-note { font-size: 12px; color: #756a82; margin-top: 12px; }
    </style>
@endpush

@section('content')
    @php
        // Mã QR minh họa (không quét được): ô vuông sinh từ mã đơn hàng
        $qrSize = 25;
        $qrSeed = crc32('EDTIKA-' . $order->id);
        mt_srand($qrSeed);
        $qrCells = [];
        for ($y = 0; $y < $qrSize; $y++) {
            for ($x = 0; $x < $qrSize; $x++) {
                $inFinder = ($x < 7 && $y < 7) || ($x >= $qrSize - 7 && $y < 7) || ($x < 7 && $y >= $qrSize - 7);
                if ($inFinder) {
                    $fx = $x >= $qrSize - 7 ? $x - ($qrSize - 7) : $x;
                    $fy = $y >= $qrSize - 7 ? $y - ($qrSize - 7) : $y;
                    $on = ($fx === 0 || $fx === 6 || $fy === 0 || $fy === 6) || ($fx >= 2 && $fx <= 4 && $fy >= 2 && $fy <= 4);
                } else {
                    $on = mt_rand(0, 1) === 1;
                }
                if ($on) {
                    $qrCells[] = [$x, $y];
                }
            }
        }
        mt_srand();
    @endphp

    <div class="dp-wrap">
        <div class="dp-card">
            <span class="dp-badge">{{ trans('bundle_payment.demo_badge') }}</span>
            <h1 class="font-24 font-weight-bold mt-12 mb-0">{{ trans('bundle_payment.demo_page_title') }}</h1>
            <p class="text-gray-500 font-14 mt-4 mb-0">{{ trans('bundle_payment.demo_page_hint') }}</p>

            <div class="dp-grid">
                <div>
                    <h3 class="font-14 font-weight-bold">{{ trans('bundle_payment.choose_method') }}</h3>
                    <div class="dp-methods mt-8">
                        @foreach($methods as $key => $method)
                            <button type="button" class="dp-method js-dp-method {{ $key === $methodKey ? 'is-active' : '' }}" data-method="{{ $key }}">
                                <b>{{ $method['icon'] }} {{ $method['title'] }}</b>
                                <span>{{ $method['detail'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    <h3 class="font-14 font-weight-bold mt-24">{{ trans('bundle_payment.order_info') }}</h3>
                    @foreach($order->orderItems as $orderItem)
                        <div class="dp-row">
                            <span>{{ optional($orderItem->bundle)->title ?? optional($orderItem->webinar)->title ?? ('#' . $orderItem->id) }}</span>
                            <b>{{ handlePrice($orderItem->total_amount ?? $orderItem->amount) }}</b>
                        </div>
                    @endforeach
                    <div class="dp-row">
                        <span>{{ trans('bundle_payment.order_code') }}</span>
                        <b>#{{ $order->id }}</b>
                    </div>
                    <div class="dp-row">
                        <span>{{ trans('bundle_payment.transfer_content') }}</span>
                        <b>{{ $transferContent }}</b>
                    </div>
                </div>

                <div class="dp-qr">
                    <svg viewBox="0 0 {{ $qrSize }} {{ $qrSize }}" shape-rendering="crispEdges" role="img" aria-label="QR">
                        <rect width="{{ $qrSize }}" height="{{ $qrSize }}" fill="#fff"/>
                        @foreach($qrCells as $cell)
                            <rect x="{{ $cell[0] }}" y="{{ $cell[1] }}" width="1" height="1" fill="#21182e"/>
                        @endforeach
                    </svg>
                    <div class="dp-amount">{{ handlePrice($order->total_amount) }}</div>
                    <div class="font-13">{{ trans('bundle_payment.expires_in') }} <span class="dp-timer js-dp-timer">--:--</span></div>
                </div>
            </div>

            <div class="dp-actions">
                <a href="{{ $successUrl }}" class="dp-btn dp-btn--primary">✓ {{ trans('bundle_payment.simulate_success') }}</a>
                <a href="{{ $failUrl }}" class="dp-btn dp-btn--danger">✕ {{ trans('bundle_payment.simulate_fail') }}</a>
                <a href="{{ $cancelUrl }}" class="dp-btn">{{ trans('bundle_payment.cancel') }}</a>
            </div>

            <p class="dp-note">{{ trans('bundle_payment.demo_note') }}</p>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            // Chọn phương thức chỉ đổi giao diện (bản Demo xử lý mọi phương thức như nhau)
            document.querySelectorAll('.js-dp-method').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.querySelectorAll('.js-dp-method').forEach(function (item) {
                        item.classList.remove('is-active');
                    });
                    button.classList.add('is-active');
                });
            });

            // Đếm ngược tới hạn của URL thanh toán; hết hạn thì ghi nhận giao dịch hết hạn
            var expiresAt = {{ (int)$expiresAt }} * 1000;
            var timer = document.querySelector('.js-dp-timer');
            var expiredUrl = @json($expiredUrl);

            function tick() {
                var remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
                var minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
                var seconds = String(remaining % 60).padStart(2, '0');
                timer.textContent = minutes + ':' + seconds;

                if (remaining <= 0) {
                    clearInterval(interval);
                    window.location.href = expiredUrl;
                }
            }

            var interval = setInterval(tick, 1000);
            tick();
        })();
    </script>
@endpush
