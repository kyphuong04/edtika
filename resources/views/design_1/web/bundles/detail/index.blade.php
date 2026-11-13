@php
    // Trang dùng header/footer EDTIKA riêng, tắt header/footer của theme Rocket LMS
    $appHeader = true;
    $appFooter = true;
@endphp

@extends('design_1.web.layouts.app')

{{-- GĐ5: trang chi tiết khóa học, dựng theo markup + CSS của bản demo --}}

@push('styles_top')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-storefront.css?v=gd5-4">
    @include('design_1.web.bundles.storefront.styles')
    <style>
        /* Video giới thiệu thật nằm trong khung preview của demo */
        .edk #detail .preview-screen { position: relative; overflow: hidden; }
        .edk #detail .preview-screen iframe,
        .edk #detail .preview-screen video { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; border-radius: inherit; background: #000; }
        .edk #detail .detail-intro .detail-buy > .edk-detail-actions { grid-area: purchase; display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .edk .slides-viewport { overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; }
        .edk .slides-viewport::-webkit-scrollbar { display: none; }
    </style>
@endpush

@section('content')
    @php
        $currency = $bundle->getPriceCurrency();
        $offPercent = $bundle->getOriginalPriceDiscountPercent();

        // Video giới thiệu: YouTube / Vimeo nhúng iframe, còn lại phát bằng thẻ <video>
        $demoEmbed = null;
        $demoVideoUrl = null;
        if (!empty($bundle->video_demo)) {
            if ($bundle->video_demo_source === 'youtube' and preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{6,})~', $bundle->video_demo, $match)) {
                $demoEmbed = 'https://www.youtube.com/embed/' . $match[1];
            } elseif ($bundle->video_demo_source === 'vimeo' and preg_match('~vimeo\.com/(?:video/)?(\d+)~', $bundle->video_demo, $match)) {
                $demoEmbed = 'https://player.vimeo.com/video/' . $match[1];
            } else {
                $demoVideoUrl = $bundle->video_demo_source === 'upload' ? url($bundle->video_demo) : $bundle->video_demo;
            }
        }
        $demoDuration = !empty($bundle->demo_video_duration)
            ? sprintf('%02d:%02d', intdiv($bundle->demo_video_duration, 60), $bundle->demo_video_duration % 60)
            : null;
    @endphp

    {{-- Header dùng chung với trang home --}}
    @include('design_1.web.includes.edtika_header')

    <div class="edk">
        <main class="wrap">
            <section id="detail" class="stage on">
                <div class="crumb">
                    <a href="/classes">Khóa học</a><span>›</span><b>Thông tin khóa học</b>
                </div>

                {{-- ===== Hero ===== --}}
                <div class="shell detail-hero">
                    <div class="detail-intro">
                        <div class="detail-eyebrow">THÔNG TIN KHÓA HỌC</div>
                        <h1>{{ $bundle->title }}</h1>
                        @if(!empty($bundle->summary))
                            <p>{{ trim(strip_tags($bundle->summary)) }}</p>
                        @endif

                        <div class="meta-pills">
                            <span class="tag">{{ strtoupper($bundle->exam_type ?: 'ielts') }}</span>
                            <span class="tag outline">{{ $modules->count() }} module</span>
                            <span class="tag outline">{{ $lessonsCount }} lessons</span>
                            <span class="tag outline">{{ $videosCount }} video</span>
                            @if($bundle->is_combo)
                                <span class="tag ed19-pill">COMBO</span>
                            @endif
                        </div>

                        <div class="detail-buy">
                            <div class="detail-purchase">
                                @if($bundle->price > 0)
                                    <span class="price">{{ handleBundlePriceByCurrency($bundle->price, $currency) }}</span>
                                    @if($offPercent > 0)
                                        <span class="old">{{ handleBundlePriceByCurrency($bundle->original_price, $currency) }}</span>
                                        <span class="tag success">-{{ $offPercent }}%</span>
                                    @endif
                                @else
                                    <span class="price">{{ trans('public.free') }}</span>
                                @endif
                            </div>

                            <div class="edk-detail-actions">
                                @if($owned)
                                    <a href="/panel/courses/purchases/{{ $bundle->slug }}" class="action primary">Vào học →</a>
                                @else
                                    <button type="button" class="action primary js-sf-buy" data-bundle-id="{{ $bundle->id }}" data-title="{{ $bundle->title }}">Mua ngay →</button>
                                    <button type="button" class="action pu-detail-cart js-edk-add-cart" data-bundle-id="{{ $bundle->id }}">+ Thêm vào giỏ</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="detail-aside">
                        <div class="video-preview">
                            <div class="preview-screen">
                                @if(!empty($demoEmbed))
                                    <iframe src="{{ $demoEmbed }}" title="{{ $bundle->title }}" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                                @elseif(!empty($demoVideoUrl))
                                    <video src="{{ $demoVideoUrl }}" controls preload="metadata"></video>
                                @else
                                    <span class="preview-play" aria-hidden="true">▶</span>
                                @endif
                                @if($demoDuration and empty($demoEmbed) and empty($demoVideoUrl))
                                    <div style="position:absolute;bottom:12px;font-size:11px;opacity:.86">{{ $demoDuration }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ===== Quà tặng khóa học ===== --}}
                @if($bundle->gifts->isNotEmpty())
                    <section class="ed19-bonus" aria-label="Quà tặng khóa học">
                        <span class="ed19-ambient">✦</span><span class="ed19-ambient">✧</span><span class="ed19-ambient">✦</span><span class="ed19-ambient">✧</span>
                        <div class="ed19-bonus-header">
                            <span class="ed19-gift-icon">{!! \App\Support\Storefront\StorefrontIcons::GIFT !!}</span>
                            <div>
                                <h2>QUÀ TẶNG KHÓA HỌC</h2>
                                <p>Đặc quyền học tập đi kèm khi sở hữu khóa học này.</p>
                            </div>
                        </div>
                        <div class="ed19-bonus-grid">
                            @foreach($bundle->gifts as $gift)
                                <article class="ed19-gift-tile" @if(!empty($gift->description)) title="{{ $gift->description }}" @endif>
                                    <div class="ed19-gift-thumb">{!! \App\Support\Storefront\StorefrontIcons::giftItem($gift->icon) !!}</div>
                                    <strong>{{ $gift->title }}</strong>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- ===== Chi tiết và nội dung ===== --}}
                <div class="detail-title">
                    <h2>Chi tiết và nội dung</h2>
                    <p>Khám phá hệ thống module, bài tập tương tác và các hoạt động đánh giá xuyên suốt khóa học.</p>
                </div>

                <div class="slides-viewport js-edk-slides">
                    <div id="info-track" class="info-track">
                        @foreach($highlights as $index => $highlight)
                            <button type="button" class="info-tile {{ $index === 0 ? 'current' : '' }} js-edk-slide" aria-label="Xem {{ $highlight['title'] }}">
                                <span class="tile-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <b>{{ $highlight['title'] }}</b>
                                <span>{{ $highlight['description'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="slider-controls">
                    <button type="button" aria-label="Thẻ trước" class="js-edk-slide-prev">‹</button>
                    <button type="button" aria-label="Thẻ tiếp theo" class="js-edk-slide-next">›</button>
                </div>

                {{-- ===== Mô tả đầy đủ (nếu teacher nhập) ===== --}}
                @if(!empty(trim(strip_tags((string)$bundle->description))))
                    <div class="shell" style="padding:24px 28px;margin-top:24px">
                        <h2 class="section-title">Giới thiệu khóa học</h2>
                        <div class="section-sub" style="font-size:14px">{!! clean($bundle->description) !!}</div>
                    </div>
                @endif
            </section>
        </main>

        @include('design_1.web.bundles.storefront.cart_fab')
    </div>

    {{-- Footer dùng chung với trang home --}}
    @include('design_1.web.includes.edtika_footer')

    {{-- Mua nhanh thật cần đăng nhập; khách bấm "Mua ngay" sẽ mở panel demo (cart_panel) --}}
    @auth
        @include('design_1.web.bundles.storefront.quick_buy_panel')
    @endauth
@endsection

@push('scripts_bottom')
    @include('design_1.web.bundles.storefront.add_to_cart_script')

    <script>
        (function () {
            var viewport = document.querySelector('.js-edk-slides');
            if (!viewport) {
                return;
            }

            var tiles = Array.prototype.slice.call(viewport.querySelectorAll('.js-edk-slide'));
            var prev = document.querySelector('.js-edk-slide-prev');
            var next = document.querySelector('.js-edk-slide-next');
            var current = 0;

            function select(index) {
                current = Math.max(0, Math.min(tiles.length - 1, index));
                tiles.forEach(function (tile, i) {
                    tile.classList.toggle('current', i === current);
                });
                var tile = tiles[current];
                if (tile) {
                    viewport.scrollTo({left: tile.offsetLeft - viewport.offsetLeft, behavior: 'smooth'});
                }
                prev.disabled = current === 0;
                next.disabled = current === tiles.length - 1;
            }

            tiles.forEach(function (tile, index) {
                tile.addEventListener('click', function () {
                    select(index);
                });
            });
            prev.addEventListener('click', function () { select(current - 1); });
            next.addEventListener('click', function () { select(current + 1); });

            prev.disabled = true;
            next.disabled = tiles.length <= 1;
        })();
    </script>
@endpush
