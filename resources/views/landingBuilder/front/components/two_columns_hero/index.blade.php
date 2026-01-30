@if(!empty($landingComponent) and $landingComponent->enable)
    @php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    @endphp

    @push('styles_top')
        <link rel="stylesheet" href="{{ getLandingComponentStylePath("two_columns_hero") }}">
    @endpush

    <div class="two-columns-hero-section " @if(!empty($contents['background'])) style="background-image: url({{ $contents['background'] }})" @endif>
        <div class="container h-100">
            <div class="row h-100 flex-column-reverse flex-lg-row">
                <div class="col-12 col-lg-5 two-columns-hero-section__content">
                    {{-- Upper Call to Action --}}
                    @if(!empty($contents['upper_cta']) and (!empty($contents['upper_cta']['badge_text']) or !empty($contents['upper_cta']['main_text'])))
                        <a href="{{ !empty($contents['upper_cta']['url']) ? $contents['upper_cta']['url'] : '' }}" target="_blank" class="">
                            <div class="d-inline-flex align-items-center gap-8 p-8 pr-16 rounded-32 border-2 border-dark">
                                @if(!empty($contents['upper_cta']['badge_text']))
                                    <div class="d-flex-center gap-4 p-6 pr-10 rounded-16 bg-primary">
                                        @if(!empty($contents['upper_cta']['icon']))
                                            @svg("iconsax-{$contents['upper_cta']['icon']}", ['width' => '20px', 'height' => '20px', 'class' => "icons text-white two-columns-hero-section__upper-cta-badge-icon"])
                                        @endif

                                        <span class="font-14 text-white">{{ trans('home.hero_badge') }}</span>
                                    </div>
                                @endif

                                @if(!empty($contents['upper_cta']['main_text']))
                                    <span class="font-14 text-dark">{{ trans('home.book_a_meeting') }}</span>
                                @endif

                                <x-iconsax-lin-arrow-right class="icons text-dark" width="16px" height="16px"/>
                            </div>
                        </a>
                    @endif

                    {{-- Title --}}
                    @if(!empty($contents['main_content']))
                        <h1 class="d-inline-flex flex-column font-64 mt-24">
                            <div class="d-inline-flex align-items-center gap-12 font-64">
                                <span class="text-dark">{{ trans('home.hero_title') }}</span>
                            </div>
                        </h1>

                        @if(!empty($contents['main_content']['description']))
                            <div class="mt-16 font-16 text-gray-500">{!! nl2br(trans('home.hero_desc')) !!}</div>
                        @endif

                        @if(!empty($contents['main_content']['primary_button']) or !empty($contents['main_content']['secondary_button']))
                            <div class="d-flex align-items-lg-center flex-column flex-lg-row mt-32 gap-16">
                                {{-- Primary Button --}}
                                @if(!empty($contents['main_content']['primary_button']) and !empty($contents['main_content']['primary_button']['label']))
                                    <a href="{{ !empty($contents['main_content']['primary_button']['url']) ? $contents['main_content']['primary_button']['url'] : '' }}" class="btn-flip-effect btn btn-primary btn-xlg gap-8 text-white" data-text="{{ trans('home.start_learning') }}">
                                        @if(!empty($contents['main_content']['primary_button']['icon']))
                                            @svg("iconsax-{$contents['main_content']['primary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                        @endif

                                        <span class="btn-flip-effect__text text-white">{{ trans('home.start_learning') }}</span>
                                    </a>
                                @endif

                                {{-- Secondary Button --}}
                                @if(!empty($contents['main_content']['secondary_button']) and !empty($contents['main_content']['secondary_button']['label']))
                                    <a href="{{ !empty($contents['main_content']['secondary_button']['url']) ? $contents['main_content']['secondary_button']['url'] : '' }}" class="btn-flip-effect btn-flip-effect__text-dark btn btn-xlg gap-8" data-text="{{ trans('home.explore_courses') }}">
                                        @if(!empty($contents['main_content']['secondary_button']['icon']))
                                            @svg("iconsax-{$contents['main_content']['secondary_button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])
                                        @endif

                                        <span class="btn-flip-effect__text text-dark">{{ trans('home.explore_courses') }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif
                    @endif

                    {{-- Students Widget --}}
                    @if(!empty($contents['students_widget']) and !empty($contents['students_widget']['title']))
                        <a href="{{ !empty($contents['students_widget']['url']) ? $contents['students_widget']['url'] : '' }}" class="">
                            <div class="d-inline-flex align-items-center gap-8 mt-40 mt-lg-80 p-12 rounded-32 bg-gray-400-20 backdrop-filter-blur-2">
                                @if(!empty($contents['students_widget_avatars']) and is_array($contents['students_widget_avatars']))
                                    <div class="d-flex align-items-center overlay-avatars overlay-avatars-20">
                                        @foreach($contents['students_widget_avatars'] as $avatar)
                                            <div class="overlay-avatars__item size-40 rounded-circle border-0">
                                                <img src="{{ $avatar }}" alt="avatar" class="img-cover rounded-circle">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="">
                                    <div class="d-flex align-items-center">
                                        @foreach([1,2,3,4,5] as $st)
                                            <x-iconsax-bol-star-1 class="icons text-warning" width="16px" height="16px"/>
                                        @endforeach
                                    </div>

                                    @if(!empty($contents['students_widget']['title']))
                                        <h5 class="font-14 mt-4 text-dark mr-8">{{ $contents['students_widget']['title'] }}</h5>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                </div>

                <div class="col-lg-1"></div>

                <div class="col-12 col-lg-6">
                    @if(!empty($contents['image_content']))
                        <div class="two-columns-hero-section__images-side position-relative d-flex justify-content-end w-100 h-100 px-lg-24">

                            @if(!empty($contents['image_content']['type']))
                                <div class="d-flex-center two-columns-hero-section__main-img">
                                    @if($contents['image_content']['type'] == "lottie_json" and !empty($contents['image_content']['lottie_json']))
                                        @push('scripts_bottom')
                                            <script src="/assets/default/vendors/lottie/lottie-player.js"></script>
                                        @endpush

                                        <lottie-player src="{{ $contents['image_content']['lottie_json'] }}" background="transparent" speed="1" class="w-100 h-100" loop autoplay></lottie-player>
                                    @elseif($contents['image_content']['type'] == "image" and !empty($contents['image_content']['image']))
                                        <img src="{{ $contents['image_content']['image'] }}" alt="hero" class="img-cover">
                                    @endif
                                </div>
                            @endif

                            @if(!empty($contents['image_content']['spinning_image']))
                                <div class="d-flex-center two-columns-hero-section__spinning-img">
                                    <img src="{{ $contents['image_content']['spinning_image'] }}" alt="spinning_image" class="img-cover">
                                </div>
                            @endif

                            @if(!empty($contents['image_content']['overlay_image']))
                                <div class="d-flex-center two-columns-hero-section__overlay-img">
                                    <img src="{{ $contents['image_content']['overlay_image'] }}" alt="overlay_image" class="img-cover">
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
