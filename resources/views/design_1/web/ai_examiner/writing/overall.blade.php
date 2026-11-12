@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $focus = collect($result['criteria'])->firstWhere('key', $result['focus_criterion']);
    $breakdownUrl = route('ai_examiner.writing.breakdown', $submission['id']);
@endphp

@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/ai-examiner.css?v=1.0">
@endpush

@section('content')
<div class="aie-page">
    @include('design_1.web.ai_examiner.partials.header')

    <main class="aie-main">
        <div class="aie-container">
            <nav class="aie-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('ai_examiner.index') }}">AI Examiner</a>
                <span class="sep">›</span>
                <a href="{{ route('ai_examiner.writing', ['task' => $task]) }}">Edtika Writing AI</a>
                <span class="sep">›</span>
                <span class="is-current">Overall</span>
            </nav>

            @include('design_1.web.ai_examiner.partials.task_tabs')

            {{-- Overall band --}}
            <section class="aie-overall-banner">
                <div class="aie-overall-banner__score">Overall band score: <b>{{ number_format($result['overall_band'], 1) }}</b></div>
                <a href="{{ $breakdownUrl }}?criterion={{ $result['focus_criterion'] }}" class="aie-overall-banner__btn">Giải thích chi tiết <i>→</i></a>
            </section>

            {{-- Breakdown 4 tiêu chí --}}
            <h2 class="aie-breakdown-title">Breakdown:</h2>
            <div class="aie-crit-cards">
                @foreach($result['criteria'] as $criterion)
                    <a href="{{ $breakdownUrl }}?criterion={{ $criterion['key'] }}" class="aie-crit-card {{ $criterion['key'] === $result['focus_criterion'] ? 'is-focus' : '' }}">
                        <div class="aie-crit-card__head">
                            <span class="aie-crit-card__name">{{ $criterion['name'] }} <small>({{ $criterion['code'] }})</small></span>
                            <span class="aie-crit-card__icon">@include('design_1.web.ai_examiner.partials.icon', ['icon' => $criterion['icon']])</span>
                        </div>
                        <div class="aie-crit-card__band">{{ number_format($criterion['band'], 1) }}</div>
                        <div class="aie-meter"><span style="width: {{ round($criterion['band'] / 9 * 100) }}%"></span></div>
                        <p>{{ $criterion['summary'] }}</p>
                    </a>
                @endforeach
            </div>

            {{-- Gợi ý cải thiện — chỉ cho tiêu chí yếu nhất --}}
            @if($focus)
                <section class="aie-focus">
                    <div class="aie-focus__head">{{ $focus['name'] }} ({{ number_format($focus['band'], 1) }})</div>
                    <div class="aie-focus__body">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="#5b1fa6" stroke-width="2"><path d="M14 26c-3-2.4-5-5.6-5-9.5a11 11 0 0 1 22 0c0 3.9-2 7.1-5 9.5v4H14z"/><path d="M15 34h10M20 2v3M5 7l2 2M35 7l-2 2M1 17h3M36 17h3"/></svg>
                        <div>
                            <h4>Gợi ý cải thiện</h4>
                            <ul>
                                @foreach($focus['tips'] as $tip)
                                    <li>{{ $tip['text'] }} @if(!empty($tip['link_url']))<a href="{{ $tip['link_url'] }}">{{ $tip['link_label'] }}</a>.@endif</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>
            @endif

            {{-- Tài liệu gợi ý + Khóa học liên quan --}}
            <div class="aie-related">
                <section class="aie-related__box" data-aie-pager="4">
                    <div class="aie-related__head">
                        <h4><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5c3-1.3 6-1.3 9 .8 3-2.1 6-2.1 9-.8v14c-3-1.3-6-1.3-9 .8-3-2.1-6-2.1-9-.8z"/><path d="M12 5.8V20"/></svg>Tài liệu gợi ý</h4>
                        <a href="/blog">Xem tất cả →</a>
                    </div>
                    <div class="aie-related__track aie-related__track--docs">
                        @foreach($resources as $resource)
                            <a href="{{ $resource['url'] }}" class="aie-doc" data-aie-page-item>
                                <div class="aie-doc__cover">{{ $resource['title'] }}</div>
                                <div class="aie-doc__meta">{{ $resource['tag'] }}</div>
                            </a>
                        @endforeach
                    </div>
                    <div class="aie-pager"><button type="button" data-aie-prev aria-label="Trước">‹</button><span data-aie-page-label>1/1</span><button type="button" data-aie-next aria-label="Sau">›</button></div>
                </section>

                <section class="aie-related__box" data-aie-pager="2">
                    <div class="aie-related__head">
                        <h4><svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3L1 9l11 6 9-4.9V17h2V9z"/><path d="M5 13.2v4L12 21l7-3.8v-4L12 17z"/></svg>Khóa học liên quan</h4>
                        <a href="/classes">Xem tất cả →</a>
                    </div>
                    <div class="aie-related__track aie-related__track--courses">
                        @foreach($courses as $course)
                            <a href="{{ $course['url'] }}" class="aie-course" data-aie-page-item>
                                <b>{{ $course['title'] }}</b>
                                <span>{{ $course['subtitle'] }}</span>
                                <small><span>▤ {{ $course['lessons'] }} bài học</span><span>◷ {{ $course['duration'] }}</span></small>
                            </a>
                        @endforeach
                    </div>
                    <div class="aie-pager"><button type="button" data-aie-prev aria-label="Trước">‹</button><span data-aie-page-label>1/1</span><button type="button" data-aie-next aria-label="Sau">›</button></div>
                </section>
            </div>

            <div style="margin-top:18px;text-align:right">
                <a href="{{ route('ai_examiner.writing', ['task' => $task]) }}" class="aie-btn aie-btn--outline">Làm bài mới</a>
            </div>
        </div>
    </main>

    @include('design_1.web.ai_examiner.partials.footer')
</div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            'use strict';
            // Phân trang đơn giản cho "Tài liệu gợi ý" / "Khóa học liên quan"
            document.querySelectorAll('[data-aie-pager]').forEach(function (box) {
                var perPage = Number(box.getAttribute('data-aie-pager'));
                var items = Array.prototype.slice.call(box.querySelectorAll('[data-aie-page-item]'));
                var pages = Math.max(1, Math.ceil(items.length / perPage));
                var page = 0;
                var prev = box.querySelector('[data-aie-prev]');
                var next = box.querySelector('[data-aie-next]');
                var label = box.querySelector('[data-aie-page-label]');

                function render() {
                    items.forEach(function (item, i) {
                        item.style.display = Math.floor(i / perPage) === page ? '' : 'none';
                    });
                    label.textContent = (page + 1) + '/' + pages;
                    prev.disabled = page === 0;
                    next.disabled = page >= pages - 1;
                }

                prev.addEventListener('click', function () { if (page > 0) { page--; render(); } });
                next.addEventListener('click', function () { if (page < pages - 1) { page++; render(); } });
                render();
            });
        })();
    </script>
@endpush
