@extends('design_1.web.layouts.app')

@push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("auth/theme_1") }}">
    @push("styles_top")
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="{{ getDesign1StylePath("auth/theme_1") }}">

    <style>
        .auth-page-card .btn-primary,
        .auth-page-card .js-submit-form-btn {
            background-color: #511D99 !important;
            border-color: #511D99 !important;
        }
        .auth-page-card .btn-primary:hover,
        .auth-page-card .js-submit-form-btn:hover {
            background-color: #3f1677 !important;
            border-color: #3f1677 !important;
        }
        .auth-page-card .form-control:focus {
            border-color: #511D99 !important;
            box-shadow: 0 0 0 0.2rem rgba(81, 29, 153, 0.15) !important;
        }
        .auth-page-card a,
        .auth-page-card .text-dark {
            color: #15151d;
        }
        .auth-page-card .font-weight-bold.text-dark:hover,
        .auth-page-card a.font-weight-bold {
            color: #511D99 !important;
        }
        .auth-page-card .custom-control-input:checked ~ .custom-control__label::before,
        .auth-page-card .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #511D99 !important;
            border-color: #511D99 !important;
        }
        .auth-page-card .auth-register-method-item input:checked + label {
            background-color: #511D99 !important;
            border-color: #511D99 !important;
            color: #fff !important;
        }
        .auth-slider-container .swiper-pagination-bullet-active {
            background: #511D99 !important;
        }
        .password-input-visibility svg,
        .password-input-visibility .icons-eye,
        .password-input-visibility .icons-eye-slash {
            color: #511D99 !important;
        }
    </style>
@endpush
@endpush

@section("content")
    <section class="container mt-96 mb-104 position-relative">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="auth-page-card position-relative">
                    <div class="auth-page-card__mask"></div>

                    <div class="position-relative bg-white rounded-32 p-16 z-index-2">
                        <div class="row">
                            <div class="col-12 col-lg-6">

                                @yield("page_content")

                            </div>

                            <div class="col-12 col-lg-6 d-none d-lg-block">
                                @include('design_1.web.auth.theme_1.includes.slider')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>

    <script src="{{ getDesign1ScriptPath("auth_theme_1") }}"></script>
@endpush
