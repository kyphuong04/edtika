@extends('design_1.panel.layouts.panel')

{{--
    "Khóa học của tôi" theo màn #library trong bản demo của khách: hero, bộ lọc Skill / Duration / Content Type,
    thẻ khóa học đã mua (tiến độ + Bắt đầu / Tiếp tục) và khóa chưa mua (mờ, rê chuột: Xem chi tiết / Mua khóa học).
--}}

@push('styles_top')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-storefront.css?v=gd5-4">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-learning.css?v=1">
    <link rel="stylesheet" href="/assets/design_1/css/edtika-my-courses.css?v=1">
@endpush

@section('content')
    @php
        $lockSvg = '<svg class="v9-svg" viewBox="0 0 24 24" aria-hidden="true"><rect width="14" height="11" x="5" y="10" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg>';
        $examOptions = collect(['IELTS', 'TOEIC', 'APTIS'])->merge($cards->pluck('exam'))->unique()->values();
    @endphp

    <div class="edk edk-learning">
        <section id="library">
            <div class="crumb">
                <a href="/classes">{{ trans('bundle_learning.my_courses_crumb_root') }}</a><span>›</span><b>{{ trans('panel.my_courses') }}</b>
            </div>

            <div class="shell hero">
                <span class="deco one">✦</span><span class="deco two">✧</span>
                <h1>{{ trans('panel.my_courses') }}</h1>
                <p>{{ trans('bundle_learning.my_courses_intro') }}</p>
            </div>

            <div class="filters">
                <div>
                    <label class="filter-label" for="filter-skill">Skill</label>
                    <div class="select-wrap">
                        <select id="filter-skill" class="js-library-filter">
                            <option value="all">{{ trans('bundle_learning.filter_all') }}</option>
                            @foreach(['writing', 'speaking', 'reading', 'listening'] as $skillOption)
                                <option value="{{ $skillOption }}">{{ trans('bundle_learning.skill_' . $skillOption) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="filter-label" for="filter-duration">Duration</label>
                    <div class="select-wrap">
                        <select id="filter-duration" class="js-library-filter">
                            <option value="all">{{ trans('bundle_learning.filter_all') }}</option>
                            <option value="short">{{ trans('bundle_learning.filter_short_video', ['minutes' => (int)round($shortVideoMaxSeconds / 60)]) }}</option>
                            <option value="long">{{ trans('bundle_learning.filter_long_video', ['minutes' => (int)round($shortVideoMaxSeconds / 60)]) }}</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="filter-label" for="filter-exam">Content Type</label>
                    <div class="select-wrap">
                        <select id="filter-exam" class="js-library-filter">
                            <option value="all">{{ trans('bundle_learning.filter_all') }}</option>
                            @foreach($examOptions as $examOption)
                                <option value="{{ $examOption }}">{{ $examOption }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="library-layout">
                <div>
                    <div id="library-grid" class="library-grid">
                        @foreach($cards as $card)
                            @php
                                $duration = $card['avg_video_seconds'] > 0 ? ($card['avg_video_seconds'] <= $shortVideoMaxSeconds ? 'short' : 'long') : '';
                                if ($card['completed']) {
                                    $stateLabel = trans('bundle_learning.status_completed');
                                    $actionLabel = trans('bundle_learning.library_review');
                                } elseif ($card['started']) {
                                    $stateLabel = trans('bundle_learning.status_in_progress');
                                    $actionLabel = trans('bundle_learning.library_continue');
                                } else {
                                    $stateLabel = trans('bundle_learning.status_not_started');
                                    $actionLabel = trans('bundle_learning.library_start');
                                }
                            @endphp
                            <article class="inner library-item {{ $card['owned'] ? '' : 'unowned' }} js-library-item"
                                     tabindex="{{ $card['owned'] ? '-1' : '0' }}"
                                     data-skills="{{ implode(',', $card['skills']) }}"
                                     data-duration="{{ $duration }}"
                                     data-exam="{{ $card['exam'] }}">
                                <div class="library-cover"><img alt="{{ $card['title'] }}" src="{{ $card['cover'] }}" loading="lazy"></div>
                                <div class="library-info">
                                    <div class="library-tags">
                                        <span class="tag">{{ $card['exam'] }}</span>
                                        @if(!empty($card['skill_label']))
                                            <span class="tag">{{ $card['skill_label'] }}</span>
                                        @endif
                                    </div>
                                    <h3>{{ $card['title'] }}</h3>
                                    {{-- Luôn có dòng mô tả để các hàng của thẻ không bị lệch khi khóa chưa có mô tả --}}
                                    <p class="{{ $card['summary'] === '' ? 'is-empty' : '' }}">{{ $card['summary'] !== '' ? \Illuminate\Support\Str::limit($card['summary'], 160) : '—' }}</p>
                                    <div class="metrics">
                                        <div class="metric"><span class="icon">▣</span><span>Module<b>{{ $card['modules'] }} module</b></span></div>
                                        <div class="metric"><span class="icon">◫</span><span>Lesson<b>{{ trans('bundle_learning.library_lessons', ['count' => $card['lessons']]) }}</b></span></div>
                                        <div class="metric"><span class="icon">▷</span><span>Video<b>{{ $card['videos'] }} video</b></span></div>
                                        <div class="metric"><span class="icon">✓</span><span>Quiz<b>{{ $card['mini_quizzes'] }} Mini Quiz</b></span></div>
                                    </div>
                                    <div class="library-bottom">
                                        <div class="library-progress">
                                            <div class="state-line">
                                                <span>{{ $card['owned'] ? $stateLabel : trans('bundle_learning.library_not_owned') }}</span>
                                                <span>{{ $card['owned'] ? $card['progress'] . '%' : '—' }}</span>
                                            </div>
                                            <div class="bar"><i style="width:{{ $card['owned'] ? $card['progress'] : 0 }}%"></i></div>
                                        </div>
                                        @if($card['owned'])
                                            <a href="/panel/courses/purchases/{{ $card['slug'] }}" class="action sm primary">{{ $actionLabel }}</a>
                                        @endif
                                    </div>
                                </div>

                                @if(!$card['owned'])
                                    <span class="library-unowned-mobile">{{ trans('bundle_learning.library_hover_hint') }}</span>
                                    <div class="library-unowned-actions">
                                        <span class="v10-unowned-lock" aria-hidden="true">{!! $lockSvg !!}</span>
                                        <div class="v10-unowned-actions-row">
                                            <a href="/bundles/{{ $card['slug'] }}" class="action sm">{{ trans('bundle_learning.library_view_detail') }}</a>
                                            <a href="/bundles/{{ $card['slug'] }}?buy=1" class="action sm primary">{{ trans('bundle_learning.library_buy') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>

                    <p class="edk-library-empty js-library-empty" {{ $cards->isEmpty() ? '' : 'hidden' }}>{{ trans('bundle_learning.library_empty') }}</p>

                    <div id="library-pages" class="pagination js-library-pages"></div>

                    {{-- Module mua lẻ (không thuộc khóa học đã mua) - vẫn giữ lối vào học --}}
                    @if($otherWebinars->isNotEmpty())
                        <h2 class="edk-library-section-title">{{ trans('bundle_learning.library_other_courses') }}</h2>
                        <div class="library-grid">
                            @foreach($otherWebinars as $otherIndex => $otherWebinar)
                                <article class="inner library-item">
                                    <div class="library-cover"><img alt="{{ $otherWebinar->title }}" src="/assets/design_1/img/bundles/covers/{{ ['diamond', 'bars', 'target', 'book'][$otherIndex % 4] }}.webp" loading="lazy"></div>
                                    <div class="library-info">
                                        <div class="library-tags"><span class="tag">{{ trans('bundle_learning.library_single_course') }}</span></div>
                                        <h3>{{ $otherWebinar->title }}</h3>
                                        <div class="library-bottom">
                                            <div class="library-progress"></div>
                                            <a href="/panel/courses/purchases/learning/{{ $otherWebinar->slug }}" class="action sm primary">{{ trans('bundle_learning.library_continue') }}</a>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            'use strict';

            var PER_PAGE = 6;
            var items = Array.prototype.slice.call(document.querySelectorAll('.js-library-item'));
            var pages = document.querySelector('.js-library-pages');
            var empty = document.querySelector('.js-library-empty');
            var current = 1;

            function value(id) {
                var element = document.getElementById(id);
                return element ? element.value : 'all';
            }

            function matches(item) {
                var skill = value('filter-skill');
                var duration = value('filter-duration');
                var exam = value('filter-exam');
                var skills = (item.getAttribute('data-skills') || '').split(',');

                return (skill === 'all' || skills.indexOf(skill) !== -1)
                    && (duration === 'all' || item.getAttribute('data-duration') === duration)
                    && (exam === 'all' || item.getAttribute('data-exam') === exam);
            }

            function render() {
                var visible = items.filter(matches);
                var total = Math.max(1, Math.ceil(visible.length / PER_PAGE));
                current = Math.min(current, total);

                items.forEach(function (item) { item.hidden = true; });
                visible.slice((current - 1) * PER_PAGE, current * PER_PAGE).forEach(function (item) { item.hidden = false; });

                if (empty) {
                    empty.hidden = visible.length > 0;
                }

                pages.innerHTML = '';
                if (total <= 1) {
                    return;
                }

                function button(label, page, options) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.textContent = label;
                    if (options && options.aria) { b.setAttribute('aria-label', options.aria); }
                    if (page === current && !(options && options.nav)) { b.className = 'on'; }
                    b.disabled = !!(options && options.disabled);
                    b.addEventListener('click', function () {
                        current = page;
                        render();
                        document.getElementById('library').scrollIntoView({behavior: 'smooth', block: 'start'});
                    });
                    pages.appendChild(b);
                }

                button('‹', Math.max(1, current - 1), {nav: true, aria: @json(trans('bundle_learning.page_prev')), disabled: current === 1});
                for (var page = 1; page <= total; page++) {
                    button(String(page), page);
                }
                button('›', Math.min(total, current + 1), {nav: true, aria: @json(trans('bundle_learning.page_next')), disabled: current === total});
            }

            document.querySelectorAll('.js-library-filter').forEach(function (select) {
                select.addEventListener('change', function () {
                    current = 1;
                    render();
                });
            });

            render();
        })();
    </script>
@endpush
