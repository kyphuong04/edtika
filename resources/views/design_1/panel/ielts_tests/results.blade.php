@extends('design_1.panel.layouts.panel')

@push('styles_top')
@php
    $resultsCss = 'assets/css/ielts-tests/ielts-results.css';
@endphp
<link rel="stylesheet" href="{{ asset($resultsCss) }}?v={{ is_file(public_path($resultsCss)) ? filemtime(public_path($resultsCss)) : time() }}">
@endpush

@section('content')
@php
    $hasAuto = $summary['total'] > 0;
    $pct = fn (int $n) => $summary['total'] > 0 ? round($n / $summary['total'] * 100, 1) : 0;
    $statRows = [
        ['key' => 'correct',   'label' => 'Đúng',    'icon' => 'fa-check', 'value' => $summary['correct']],
        ['key' => 'incorrect', 'label' => 'Sai',     'icon' => 'fa-times', 'value' => $summary['incorrect']],
        ['key' => 'empty',     'label' => 'Bỏ qua',  'icon' => 'fa-minus', 'value' => $summary['empty']],
    ];
@endphp

<div class="rs-page">
    @if(!empty($isMentorPreview) && !empty($mentorPreviewExitUrl))
        <div class="rs-preview-banner">
            <span>Đang xem trước với vai trò học viên — bài làm và bản ghi âm sẽ bị xóa khi thoát xem trước.</span>
            <a href="{{ $mentorPreviewExitUrl }}" onclick="return confirm('Thoát chế độ xem trước?');">Thoát preview</a>
        </div>
    @endif

    {{-- ── Thanh trên cùng (dùng chung với trang chữa bài) ───────── --}}
    @include('design_1.panel.ielts_tests.partials.result_topbar', ['topbar' => [
        'active' => 'overall',
        'logoUrl' => $backUrl,
        'overallUrl' => request()->url(),
        'breakdownUrl' => $reviewUrl,
        'canRetake' => $canRetake,
    ]])

    <main class="rs-main">

        {{-- ══ OVERALL ═══════════════════════════════════════════════ --}}
        <section class="rs-panel is-active" data-panel="overall">
            <div class="rs-banner">
                <img src="{{ $banner['url'] }}" alt="{{ $banner['alt'] }}">
            </div>

            <div class="rs-overview">
                <div class="rs-card rs-result">
                    <h2 class="rs-card-title">Kết quả làm bài</h2>

                    <div class="rs-result-body">
                        <div class="rs-score">
                            <div class="rs-score-main">
                                @if($hasAuto)
                                    <div class="rs-score-value">{{ $summary['correct'] }}/{{ $summary['total'] }}</div>
                                    <div class="rs-score-label">câu đúng</div>
                                @elseif($overallBand !== null)
                                    <div class="rs-score-value">{{ number_format($overallBand, 1) }}</div>
                                    <div class="rs-score-label">band</div>
                                @else
                                    <div class="rs-score-value is-pending">—</div>
                                    <div class="rs-score-label">đang chờ chấm</div>
                                @endif

                                @if($hasAuto && $overallBand !== null)
                                    <span class="rs-score-band">Band {{ number_format($overallBand, 1) }}</span>
                                @endif
                            </div>

                            <div class="rs-score-time">
                                <i class="far fa-clock" aria-hidden="true"></i>
                                <div>
                                    <span>Thời gian làm bài</span>
                                    <strong>{{ $durationLabel }}</strong>
                                </div>
                            </div>
                        </div>

                        @if($hasAuto)
                            <div class="rs-stats">
                                @foreach($statRows as $row)
                                    <div class="rs-stat rs-stat--{{ $row['key'] }}">
                                        <span class="rs-stat-icon"><i class="fas {{ $row['icon'] }}" aria-hidden="true"></i></span>
                                        <div class="rs-stat-body">
                                            <div class="rs-stat-head">
                                                <span>{{ $row['label'] }}</span>
                                                <span>{{ $row['value'] }} câu</span>
                                            </div>
                                            <div class="rs-stat-bar"><span style="width: {{ $pct($row['value']) }}%"></span></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rs-pending-note">
                                Bài Writing/Speaking của bạn đang chờ giáo viên chấm. Điểm và nhận xét sẽ hiện ở đây khi chấm xong.
                            </div>
                        @endif
                    </div>

                    @if(!empty($pendingManual) && $hasAuto)
                        <div class="rs-pending-note rs-pending-note--inline">
                            {{ implode(' và ', $pendingManual) }} đang chờ giáo viên chấm — band tổng sẽ cập nhật khi chấm xong.
                        </div>
                    @endif
                </div>

                <a href="{{ $reviewUrl }}" class="rs-card rs-explain">
                    <span class="rs-explain-icon"><i class="fas fa-book-open" aria-hidden="true"></i></span>
                    <span class="rs-explain-body">
                        <span class="rs-explain-title">Xem giải thích <span class="rs-free">FREE</span></span>
                        <span class="rs-explain-text">Xem giải thích chi tiết và đáp án cho từng câu hỏi để hiểu rõ hơn.</span>
                    </span>
                    <span class="rs-explain-arrow"><i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </a>
            </div>

            @if(!empty($byType))
                <div class="rs-card rs-table-card">
                    <h2 class="rs-card-title"><i class="fas fa-list-alt" aria-hidden="true"></i> Bảng dữ liệu chi tiết</h2>

                    <div class="rs-table-scroll">
                        <table class="rs-table">
                            <thead>
                                <tr>
                                    <th>Loại câu hỏi</th>
                                    <th><i class="fas fa-hashtag" aria-hidden="true"></i> Số câu hỏi</th>
                                    <th><i class="far fa-check-circle" aria-hidden="true"></i> Đúng</th>
                                    <th><i class="far fa-times-circle" aria-hidden="true"></i> Sai</th>
                                    <th><i class="far fa-minus-square" aria-hidden="true"></i> Bỏ qua</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($byType as $row)
                                    <tr>
                                        <td>
                                            <span class="rs-type">
                                                <i class="fas {{ $row['icon'] }}" aria-hidden="true"></i>
                                                {{ $row['label'] }}
                                            </span>
                                        </td>
                                        <td><span class="rs-pill">{{ $row['total'] }}</span></td>
                                        <td><span class="rs-pill rs-pill--correct">{{ $row['correct'] }}</span></td>
                                        <td><span class="rs-pill rs-pill--incorrect">{{ $row['incorrect'] }}</span></td>
                                        <td><span class="rs-pill rs-pill--empty">{{ $row['empty'] }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

    </main>
</div>

@if($canRetake)
    @include('design_1.panel.ielts_tests.partials.retake_modal', ['retakeUrl' => $retakeUrl])
@endif
@endsection

@push('scripts_bottom')
@php
    $shellJs = 'assets/js/ielts-tests/result-shell.js';
@endphp
<script src="{{ asset($shellJs) }}?v={{ is_file(public_path($shellJs)) ? filemtime(public_path($shellJs)) : time() }}"></script>
@endpush
