@php
    // Trang dùng header/footer EDTIKA riêng, tắt header/footer của theme Rocket LMS
    $appHeader = true;
    $appFooter = true;
@endphp

@extends('design_1.web.layouts.app')

{{-- GĐ5: trang danh sách khóa học (/classes và /bundles), dựng theo markup + CSS của bản demo --}}

@push('styles_top')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-storefront.css?v=gd5-4">
    @include('design_1.web.bundles.storefront.styles')
@endpush

@section('content')
    @php
        $typeLabels = [];
        foreach (\App\Models\Bundle::TYPES as $type) {
            $typeLabels[$type] = trans('bundle_learning.bundle_type_' . $type);
        }
    @endphp

    {{-- Header dùng chung với trang home --}}
    @include('design_1.web.includes.edtika_header')

    <div class="edk">
        <main class="wrap">
            <section id="discovery" class="stage on">
                {{-- ===== Hero ===== --}}
                <div class="shell hero">
                    <span class="deco one">✦</span><span class="deco two">✦</span><span class="deco three">✧</span>
                    <h1>Khóa học<br><span>1000+ Lessons</span></h1>
                    <p>Khám phá thư viện khóa học toàn diện theo band điểm mục tiêu. Trải nghiệm hệ thống bài giảng đa dạng topic, bộ công cụ AI Examiner và dịch vụ giáo viên đồng hành 1:1.</p>
                </div>

                {{-- ===== Chọn band ===== --}}
                <div class="shell select-card">
                    <h2 class="section-title" style="font-size:23px">Chọn khóa phù hợp để học tập và luyện đề cùng <span style="color:var(--p)">Edtika</span></h2>
                    <p class="section-sub">Thiết lập band hiện tại và mục tiêu tương lai để tìm lộ trình học phù hợp.</p>
                    <div class="bands">
                        <div class="bands-col">
                            <h3>Trình độ hiện tại</h3>
                            <div id="from-bands" class="band-options js-edk-from"></div>
                        </div>
                        <div class="bands-col">
                            <h3>Mục tiêu tương lai</h3>
                            <div id="to-bands" class="band-options js-edk-to"></div>
                        </div>
                    </div>
                    <p style="text-align:center;color:var(--sub);font-size:12px;margin-bottom:12px">Bạn chưa biết trình độ hiện tại? Chúng tôi có bài kiểm tra đầu vào miễn phí.</p>
                    <div style="text-align:center"><a href="/placement-test" class="action sm">Kiểm tra đầu vào</a></div>
                </div>

                {{-- ===== 4 ô gợi ý ===== --}}
                <div class="feature-row js-edk-stats" id="feature-stats"></div>

                {{-- ===== Gợi ý khóa học ===== --}}
                <div class="shell reco">
                    <div class="reco-head">
                        <h2 class="section-title">Gợi ý khóa học phù hợp với bạn</h2>
                        <p class="section-sub">Lộ trình cá nhân hóa theo band điểm mục tiêu, kết hợp các kỹ năng cốt lõi để nâng cao năng lực tiếng Anh một cách toàn diện.</p>
                    </div>

                    @if($bundles->isEmpty())
                        <p class="section-sub" style="text-align:center">{{ trans('bundle_payment.catalog_empty') }}</p>
                    @else
                        <div id="course-catalog" class="catalog js-edk-catalog">
                            @foreach($bundles as $index => $bundle)
                                @php
                                    $currency = $bundle->getPriceCurrency();
                                    $offPercent = $bundle->getOriginalPriceDiscountPercent();
                                    $owned = in_array($bundle->id, $ownedIds);
                                    $hasGifts = $bundle->gifts->isNotEmpty();
                                    $hasRibbon = ($hasGifts or $bundle->is_combo);
                                    $summary = \Illuminate\Support\Str::limit(trim(strip_tags((string)$bundle->summary)), 160);
                                    $classification = !empty($bundle->bundle_type) ? ($typeLabels[$bundle->bundle_type] ?? '') : optional($bundle->category)->title;
                                @endphp

                                <article class="inner course-card {{ $hasRibbon ? 'has-ribbon' : '' }} js-edk-course"
                                         data-order="{{ $index }}"
                                         data-band-from="{{ $bundle->band_from }}"
                                         data-band-to="{{ $bundle->band_to }}"
                                         data-roadmap="{{ $bundle->roadmap_months }}"
                                         data-modules="{{ $bundle->bundle_webinars_count }}"
                                         data-featured="{{ $hasRibbon ? 1 : 0 }}">
                                    @if($bundle->is_combo)
                                        <div class="ed19-corner combo" aria-label="Khóa học Combo"><span class="spark a">✦</span><span class="spark b">✧</span><span class="spark c">✦</span><span class="corner-core">X2</span></div>
                                    @elseif($hasGifts)
                                        <div class="ed19-corner " aria-label="Có quà tặng khóa học"><span class="spark a">✦</span><span class="spark b">✧</span><span class="spark c">✦</span><span class="corner-core">{!! \App\Support\Storefront\StorefrontIcons::GIFT !!}</span></div>
                                    @endif

                                    <div class="classification">{{ $classification }}</div>
                                    <h3>{{ $bundle->title }}</h3>
                                    <p>{{ $summary }}</p>

                                    <div class="course-perks">
                                        @if($bundle->is_combo)
                                            <span class="tag ed19-pill">COMBO</span>
                                        @endif
                                        @foreach($bundle->tags->take(4) as $tag)
                                            <span class="tag outline">{{ $tag->title }}</span>
                                        @endforeach
                                        @if($hasGifts)
                                            <span class="tag ed19-pill">Quà tặng khóa học</span>
                                        @endif
                                    </div>

                                    <div class="course-foot">
                                        <div class="course-prices">
                                            @if($bundle->price > 0)
                                                <b class="price">{{ handleBundlePriceByCurrency($bundle->price, $currency) }}</b>
                                                @if($offPercent > 0)
                                                    <s class="old">{{ handleBundlePriceByCurrency($bundle->original_price, $currency) }}</s>
                                                    <span class="tag success">-{{ $offPercent }}%</span>
                                                @endif
                                            @else
                                                <b class="price">{{ trans('public.free') }}</b>
                                            @endif
                                        </div>

                                        <div class="flex pu-card-actions" style="gap:8px">
                                            @if($owned)
                                                <a href="/panel/courses/purchases/{{ $bundle->slug }}" class="action sm primary">Vào học →</a>
                                            @else
                                                <button type="button" class="action sm js-edk-add-cart" data-bundle-id="{{ $bundle->id }}">+ Giỏ hàng</button>
                                                <button type="button" class="action sm primary js-sf-buy" data-bundle-id="{{ $bundle->id }}" data-title="{{ $bundle->title }}">Mua ngay</button>
                                            @endif
                                            <a href="{{ $bundle->getUrl() }}" class="action sm">Chi tiết →</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
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
            // Nhóm band theo bản demo: trình độ hiện tại (điểm cuối) và mục tiêu (điểm đầu)
            var fromGroups = [
                {label: 'BAND 0 - 3.0', end: 3}, {label: 'BAND 3.5 - 4.0', end: 4},
                {label: 'BAND 4.5 - 5.0', end: 5}, {label: 'BAND 5.5 - 6.0', end: 6}
            ];
            var toGroups = [
                {label: 'BAND 4.0 - 4.5', start: 4}, {label: 'BAND 5.0 - 5.5', start: 5},
                {label: 'BAND 6.0 - 6.5', start: 6}, {label: 'BAND 7.0+', start: 7}
            ];

            var state = {from: 1, to: 2};
            try {
                var saved = JSON.parse(localStorage.getItem('edtika-band-picker') || 'null');
                if (saved && typeof saved.from === 'number' && typeof saved.to === 'number') {
                    state = saved;
                }
            } catch (e) {}

            var fromBox = document.querySelector('.js-edk-from');
            var toBox = document.querySelector('.js-edk-to');
            var statsBox = document.querySelector('.js-edk-stats');
            var catalog = document.querySelector('.js-edk-catalog');

            function fixTarget() {
                if (toGroups[state.to].start <= fromGroups[state.from].end) {
                    for (var i = 0; i < toGroups.length; i++) {
                        if (toGroups[i].start > fromGroups[state.from].end) {
                            state.to = i;
                            break;
                        }
                    }
                }
            }

            // Khóa bắt đầu gần trình độ hiện tại và đưa tới band mục tiêu thì xếp trước
            function relevance(card, currentEnd, targetStart) {
                var from = parseFloat(card.dataset.bandFrom);
                var to = parseFloat(card.dataset.bandTo);
                if (isNaN(from) && isNaN(to)) {
                    return 1;
                }
                if (isNaN(from)) { from = 0; }
                if (isNaN(to)) { to = 9; }
                if (from <= currentEnd + 0.5 && to >= targetStart) {
                    return 3;
                }
                if (from <= targetStart && to >= currentEnd) {
                    return 2;
                }
                return 0;
            }

            function bandButton(label, selected, disabled, onClick) {
                var button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.className = selected ? 'selected' : '';
                button.disabled = !!disabled;
                button.addEventListener('click', onClick);
                return button;
            }

            function render() {
                fixTarget();

                fromBox.innerHTML = '';
                fromGroups.forEach(function (group, index) {
                    fromBox.appendChild(bandButton(group.label, index === state.from, false, function () {
                        state.from = index;
                        render();
                    }));
                });

                toBox.innerHTML = '';
                toGroups.forEach(function (group, index) {
                    toBox.appendChild(bandButton(group.label, index === state.to, group.start <= fromGroups[state.from].end, function () {
                        state.to = index;
                        render();
                    }));
                });

                var currentEnd = fromGroups[state.from].end;
                var targetStart = toGroups[state.to].start;
                var gap = targetStart - currentEnd;
                var cards = catalog ? Array.prototype.slice.call(catalog.querySelectorAll('.js-edk-course')) : [];

                cards.forEach(function (card) {
                    card._score = relevance(card, currentEnd, targetStart);
                });

                // Như bản demo: khóa có quà/combo lên trước, sau đó theo mức phù hợp band, rồi khóa mới nhất
                cards.sort(function (a, b) {
                    return (parseInt(b.dataset.featured, 10) - parseInt(a.dataset.featured, 10))
                        || (b._score - a._score)
                        || (parseInt(a.dataset.order, 10) - parseInt(b.dataset.order, 10));
                }).forEach(function (card) {
                    catalog.appendChild(card);
                });

                var best = cards.filter(function (card) { return card._score >= 3; })[0];
                var modules = best && parseInt(best.dataset.modules, 10) ? parseInt(best.dataset.modules, 10) : (gap > 2 ? 4 : gap > 1 ? 3 : 2);
                var months = best && parseInt(best.dataset.roadmap, 10) ? parseInt(best.dataset.roadmap, 10) : (gap > 2 ? 12 : gap > 1 ? 8 : 4);
                var intensive = months > 8 ? 4 : months > 4 ? 3 : 2;

                var stats = [
                    {n: modules + ' module', s: 'Gợi ý theo trình độ', i: '▤'},
                    {n: months + ' tháng', s: 'Lộ trình học tập', i: '◷'},
                    {n: intensive + ' tháng', s: 'Một khóa học chuyên sâu', i: '▣'},
                    {n: toGroups[state.to].label.replace('BAND ', 'Band '), s: 'Mục tiêu tương lai', i: '✦'}
                ];

                statsBox.innerHTML = '';
                stats.forEach(function (item) {
                    var tile = document.createElement('div');
                    tile.className = 'shell feature';
                    tile.innerHTML = '<div><div class="n"></div><div class="s"></div></div><div class="ico"></div>';
                    tile.querySelector('.n').textContent = item.n;
                    tile.querySelector('.s').textContent = item.s;
                    tile.querySelector('.ico').textContent = item.i;
                    statsBox.appendChild(tile);
                });

                try {
                    localStorage.setItem('edtika-band-picker', JSON.stringify(state));
                } catch (e) {}
            }

            render();
        })();
    </script>
@endpush
