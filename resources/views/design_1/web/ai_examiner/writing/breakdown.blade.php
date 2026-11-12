@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $criterionKeys = array_column($result['criteria'], 'key');
    $activeCriterion = in_array(request()->get('criterion'), $criterionKeys, true) ? request()->get('criterion') : $criterionKeys[0];
    $defaultSample = in_array('7.0', $sampleBands, true) ? '7.0' : $sampleBands[0];
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
                <a href="{{ route('ai_examiner.writing.result', $submission['id']) }}">Overall</a>
                <span class="sep">›</span>
                <span class="is-current">Breakdown</span>
            </nav>

            <div class="aie-bd" id="aieBreakdown">
                {{-- Cột trái: đề bài, biểu đồ, bài làm --}}
                <div class="aie-bd__col aie-bd__col--left">
                    <div class="aie-task-switch" style="margin-bottom:12px">
                        @foreach([1, 2] as $tabTask)
                            @if($tabTask === $task)
                                <span class="is-active" style="display:flex">{{ $taskConfig[$tabTask]['label'] }}</span>
                            @else
                                <a href="{{ !empty($taskLinks[$tabTask]) ? $taskLinks[$tabTask] : route('ai_examiner.writing', ['task' => $tabTask]) }}" class="{{ empty($taskLinks[$tabTask]) ? 'is-empty' : '' }}" title="{{ empty($taskLinks[$tabTask]) ? 'Bạn chưa nộp bài Task ' . $tabTask : '' }}">{{ $taskConfig[$tabTask]['label'] }}</a>
                            @endif
                        @endforeach
                    </div>

                    <div class="aie-bd__block">
                        <h2 class="aie-bd__h">Đề bài</h2>
                        <div class="aie-bd__box">{{ $submission['prompt'] }}</div>
                    </div>

                    @if($task === 1 && !empty($submission['chart_url']))
                        <div class="aie-bd__block">
                            <h2 class="aie-bd__h">Biểu đồ</h2>
                            <div class="aie-bd__box aie-bd__chart"><img src="{{ $submission['chart_url'] }}" alt="Biểu đồ đề bài"></div>
                        </div>
                    @endif

                    <div class="aie-bd__block" style="position:relative">
                        <h2 class="aie-bd__h">Bài làm của bạn</h2>
                        <div class="aie-bd__box">
                            <div class="aie-essay" id="aieEssay"></div>
                            <div class="aie-bd__words">Words: {{ $submission['word_count'] }}</div>
                        </div>
                        <div class="aie-popover" id="aiePopover" role="dialog" aria-label="Chi tiết lỗi"></div>
                    </div>
                </div>

                <div class="aie-bd__splitter" id="aieSplitter" title="Kéo để thay đổi độ rộng"><span>II</span></div>

                {{-- Cột phải: kết quả chấm chữa --}}
                <div class="aie-bd__col aie-bd__col--right">
                    <h2 class="aie-result-head">Kết quả chấm chữa</h2>
                    <div class="aie-mini-cards" id="aieMiniCards"></div>
                    <div class="aie-priorities" id="aiePriorities"></div>
                    <div id="aieSubCriteria"></div>
                </div>
            </div>

            {{-- Bài mẫu theo band --}}
            <section class="aie-sample">
                <div class="aie-sample__head">
                    <h3>
                        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"/><path d="M14 3v5h5M8.5 12h7M8.5 15.5h7M8.5 19h4"/></svg>
                        Bài mẫu
                    </h3>
                    <select id="aieSampleBand" aria-label="Chọn mức bài mẫu">
                        @foreach($sampleBands as $band)
                            <option value="{{ $band }}" {{ $band === $defaultSample ? 'selected' : '' }}>Band {{ $band }}+</option>
                        @endforeach
                    </select>
                </div>
                <div class="aie-sample__text" id="aieSampleText"></div>
                <div class="aie-bd__words" id="aieSampleWords"></div>
            </section>

            <div style="margin-top:18px;display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap">
                <a href="{{ route('ai_examiner.writing.result', $submission['id']) }}" class="aie-btn aie-btn--outline">← Về Overall</a>
                <a href="{{ route('ai_examiner.writing', ['task' => $task]) }}" class="aie-btn aie-btn--primary">Làm bài mới</a>
            </div>
        </div>
    </main>

    @include('design_1.web.ai_examiner.partials.footer')
</div>
@endsection

@push('scripts_bottom')
    <script>
        window.AIE_BREAKDOWN = {
            essay: @json($submission['essay']),
            result: @json($result),
            activeCriterion: @json($activeCriterion),
            defaultSample: @json($defaultSample)
        };
    </script>
    <script src="/assets/design_1/js/ai-examiner/common.js?v=1.0"></script>
    <script src="/assets/design_1/js/ai-examiner/breakdown.js?v=1.0"></script>
@endpush
