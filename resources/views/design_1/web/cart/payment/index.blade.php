@extends("design_1.web.layouts.app")

@push("styles_top")
    <link rel="stylesheet" href="{{ getDesign1StylePath("cart_page") }}">
@endpush

@php
    $isMultiCurrency = !empty(getFinancialCurrencySettings('multi_currency'));
    $userCurrency = currency();
    $invalidChannels = [];
@endphp

@section("content")
    <section class="container my-56 position-relative">
        <div class="d-flex-center flex-column text-center">
            <h1 class="font-32">{{ trans('update.checkout') }}</h1>
            <p class="mt-8 font-16 text-gray-500">{{ handlePrice($calculatePrices["total"], true, true, false, null, true) . ' ' . trans('cart.for_items',['count' => $count]) }}</p>
        </div>

        <form action="/payments/payment-request" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <div class="row">
                {{-- Items --}}
                <div class="col-12 col-md-7 col-lg-9 mt-32 mb-104">

                    {{-- CashBack --}}
                    @if(!empty($totalCashbackAmount))
                        @include('design_1.web.cart.overview.includes.cashback_alert')
                    @endif

                    <div class="card-with-mask position-relative">
                        <div class="mask-8-white"></div>

                        <div class="position-relative z-index-2 bg-white rounded-16 py-16">
                            <div class="card-before-line px-16">
                                <h3 class="font-14">{{ trans('update.select_a_payment_gateway') }}</h3>
                            </div>

                            <div class="d-grid grid-columns-2 grid-lg-columns-3 gap-24 px-16 mt-16">
                                {{-- VNPay Payment Gateway --}}
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_vnpay" data-class="VNPay" value="vnpay">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_vnpay">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/vnpay.svg" alt="VNPay" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">VNPay</h6>
                                        </div>
                                    </label>
                                </div>

                                {{-- Momo Payment Gateway --}}
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_momo" data-class="Momo" value="momo">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_momo">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/momo.svg" alt="Momo" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">Momo</h6>
                                        </div>
                                    </label>
                                </div>

                                {{-- Visa Card Payment Gateway --}}
                                <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_visa" data-class="Visa" value="visa">
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_visa">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <img src="/assets/design_1/img/payment-gateways/visa.svg" alt="Visa" class="img-fluid">
                                            </div>
                                            <h6 class="font-14 mt-12">Visa Card</h6>
                                        </div>
                                    </label>
                                </div>

                                {{-- <div class="payment-channel-card position-relative">
                                    <input type="radio" name="gateway" id="gateway_credit" value="credit" {{ (empty($userCharge) or ($calculatePrices["total"] > $userCharge)) ? 'disabled' : '' }}>
                                    <label class="position-relative w-100 d-block cursor-pointer" for="gateway_credit">
                                        <div class="gateway-mask"></div>
                                        <div class="gateway-card position-relative z-index-2 d-flex-center flex-column rounded-16 bg-white w-100 h-100 text-center">
                                            <div class="d-flex-center size-48 bg-gray-100">
                                                <x-iconsax-bul-empty-wallet class="icons text-dark" width="48px" height="48px"/>
                                            </div>
                                            <h6 class="font-14 mt-12">{{ trans('financial.account_charge') }}</h6>
                                            <p class="mt-4 font-12 text-gray-500">{{ handlePrice($userCharge) }}</p>
                                        </div>
                                    </label>
                                </div> --}}
                            </div>

                        </div>
                    </div>


                </div>

                {{-- Right Side --}}
                <div class="col-12 col-md-5 col-lg-3 mt-32">
                    <div class="cart-right-side-section">
                        {{-- Summary --}}

                        <div class="js-cart-summary-container">
                            @include('design_1.web.cart.overview.includes.summary', ['isCartPaymentPage' => true])
                        </div>

                    </div>
                </div>
            </div>

        </form>

    </section>

    @if(!empty($razorpay) and $razorpay)
        <form action="/payments/verify/Razorpay" method="get">
            <input type="hidden" name="order_id" value="{{ $order->id }}">

            <script src="https://checkout.razorpay.com/v1/checkout.js"
                    data-key="{{ getRazorpayApiKey()['api_key'] }}"
                    data-amount="{{ (int)($order->total_amount * 100) }}"
                    data-buttontext=""
                    data-description="Rozerpay"
                    data-currency="{{ currency() }}"
                    data-image="{{ $generalSettings['logo'] }}"
                    data-prefill.name="{{ $order->user->full_name }}"
                    data-prefill.email="{{ $order->user->email }}"
                    data-theme.color="#43d477">
            </script>
        </form>
    @endif

@endsection

@push('scripts_bottom')
    <script>
        var hasErrors = '{{ (!empty($errors) and count($errors)) ? 'true' : 'false' }}';
        var hasErrorsHintLang = '{{ trans('update.please_check_the_errors_in_the_shipping_form') }}';
        var selectPaymentGatewayLang = '{{ trans('update.select_a_payment_gateway') }}';
        var pleaseWaitLang = '{{ trans('update.please_wait') }}';
        var transferringToLang = '{{ trans('update.transferring_to_the_payment_gateway') }}';
    </script>
    <script src="{{ getDesign1ScriptPath("cart_page") }}"></script>

@endpush
