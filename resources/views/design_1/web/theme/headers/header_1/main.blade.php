@php
    $themeSpecificButton = (new \App\Mixins\Themes\ThemeHeaderMixins())->getHeader1NavbarSpecificButton($themeHeaderData['contents']);
@endphp

<div id="themeHeaderSticky" class="theme-header-1__main">
    <div class="container h-100 position-relative">
        <div class="theme-header-1__main-mask"></div>

        <div class="position-relative z-index-2 bg-white rounded-24 w-100 h-100 p-16">
            <div class="row align-items-center h-100">

                {{-- Logo --}}
                <div class="col-6 col-lg-2">
                    <a href="/" class="theme-header-1__logo text-left d-block">
                        @if(!empty($generalSettings['logo']))
                            <img src="{{ $generalSettings['logo'] }}" class="img-fluid light-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                        @endif

                        @if(!empty($generalSettings['dark_mode_logo']))
                            <img src="{{ $generalSettings['dark_mode_logo'] }}" class="img-fluid dark-only" alt="{{ $generalSettings['site_name'] ?? 'site' }}">
                        @endif
                    </a>
                </div>

                {{-- Custom Nav Links --}}
                <div class="col-12 col-lg-7 mt-12 mt-lg-0 order-3 order-lg-2">
                    <div class="d-flex align-items-center justify-content-center gap-16 gap-lg-28 flex-wrap flex-lg-nowrap">

                        {{-- Home --}}
                        <a href="/" class="custom-nav-link font-weight-500">{{ trans('navbar.home') }}</a>

                        {{-- Courses --}}
                        <a href="/classes" class="custom-nav-link font-weight-500">{{ trans('navbar.courses') }}</a>

                        {{-- Tests (dropdown) --}}
                        <div class="theme-header-1__dropdown theme-header-1__nav-dropdown position-relative">
                            <div class="d-inline-flex align-items-center gap-6 cursor-pointer custom-nav-link font-weight-500">
                                <span>{{ trans('navbar.tests') }}</span>
                                <x-iconsax-lin-arrow-down class="icons" width="14px" height="14px"/>
                            </div>

                            <div class="header-1-dropdown-menu py-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="header-1-dropdown-menu__item">
                                        <a href="/panel/ielts-tests/practice" class="d-flex align-items-center w-100 px-16 py-8">
                                            {{ trans('navbar.diagnostic_tests') }}
                                        </a>
                                    </li>
                                    <li class="header-1-dropdown-menu__item">
                                        <a href="/panel/ielts-tests/mock" class="d-flex align-items-center w-100 px-16 py-8">
                                            {{ trans('navbar.mock_tests') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Dictionary & Flashcard --}}
                        <a href="/panel/dictionary" class="custom-nav-link font-weight-500">{{ trans('navbar.dictionary_flashcard') }}</a>

                        {{-- Articles --}}
                        <a href="/blog" class="custom-nav-link font-weight-500">{{ trans('navbar.articles') }}</a>

                    </div>
                </div>

                {{-- Right Button --}}
                <div class="col-6 col-lg-3 mt-12 mt-lg-0 order-2 order-lg-3 d-flex align-items-center justify-content-end">
                    @if(!empty($themeSpecificButton) and !empty($themeSpecificButton['title']))
                        <a href="{{ $themeSpecificButton['url'] }}" class="btn-flip-effect btn btn-primary btn-lg gap-8 text-white" data-text="{{ trans('home.start_learning') }}">
                            @if(!empty($themeSpecificButton['icon']))
                                @svg("iconsax-{$themeSpecificButton['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons"])
                            @endif

                            <span class="btn-flip-effect__text text-white">{{ trans('home.start_learning') }}</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
