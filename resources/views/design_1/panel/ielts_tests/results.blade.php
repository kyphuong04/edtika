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

    {{-- ── Thanh trên cùng ───────────────────────────────────────── --}}
    <header class="rs-topbar">
        <div class="rs-topbar-left">
            <a href="{{ $backUrl }}" class="rs-logo" title="Về danh sách đề">EDTIKA</a>
            <nav class="rs-tabs" role="tablist" aria-label="Kết quả">
                <button type="button" class="rs-tab is-active" role="tab" data-tab="overall" aria-selected="true">Overall</button>
                <i class="fas fa-chevron-right rs-tab-sep" aria-hidden="true"></i>
                <button type="button" class="rs-tab" role="tab" data-tab="breakdown" aria-selected="false">Breakdown</button>
            </nav>
        </div>

        <div class="rs-topbar-actions">
            @if($canRetake)
                <button type="button" class="rs-btn rs-btn--outline" data-retake-open>
                    <i class="fas fa-redo-alt"></i> Làm lại
                </button>
            @endif
            <button type="button" class="rs-btn rs-btn--primary" disabled title="Tính năng đang được phát triển">
                <i class="fas fa-share-alt"></i> Chia sẻ kết quả
            </button>
        </div>
    </header>

    <main class="rs-main">

        {{-- ══ OVERALL ═══════════════════════════════════════════════ --}}
        <section class="rs-panel is-active" data-panel="overall" role="tabpanel">
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

        {{-- ══ BREAKDOWN ═════════════════════════════════════════════ --}}
        <section class="rs-panel" data-panel="breakdown" role="tabpanel" hidden>
            <div class="rs-skill-grid {{ count($skills) === 1 ? 'is-single' : '' }}">
                @foreach($skills as $s)
                    <article class="rs-card rs-skill">
                        <header class="rs-skill-head">
                            <h3>{{ $s['label'] }}</h3>
                            @if($s['band'] !== null)
                                <span class="rs-skill-band">Band {{ number_format($s['band'], 1) }}</span>
                            @elseif($s['type'] === 'manual')
                                <span class="rs-skill-band is-pending">Chờ chấm</span>
                            @endif
                        </header>

                        @if($s['type'] === 'auto')
                            <p class="rs-skill-sum">
                                Đúng <strong>{{ $s['correct'] }}</strong> · Sai <strong>{{ $s['incorrect'] }}</strong>
                                · Bỏ qua <strong>{{ $s['empty'] }}</strong> trên {{ $s['total'] }} câu
                            </p>

                            @foreach($s['sections'] as $block)
                                @if($block['label'])
                                    <div class="rs-section-label">{{ $block['label'] }}</div>
                                @endif
                                @foreach($block['parts'] as $part)
                                    <div class="rs-part">
                                        <div class="rs-part-title">{{ $part['title'] }}</div>
                                        <div class="rs-dots">
                                            @foreach($part['items'] as $item)
                                                <a href="{{ $block['reviewUrl'] }}&q={{ $item['n'] }}"
                                                   class="rs-dot is-{{ $item['status'] }}"
                                                   title="Câu {{ $item['n'] }}">{{ $item['n'] }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            @if(!$s['graded'])
                                <p class="rs-skill-sum">Bài của bạn đang chờ giáo viên chấm.</p>
                            @endif
                            <div class="rs-criteria">
                                @foreach($s['criteria'] as $c)
                                    <div class="rs-criterion">
                                        <span>{{ $c['label'] }}</span>
                                        <strong class="{{ $c['value'] === null ? 'is-empty' : '' }}">
                                            {{ $c['value'] !== null ? number_format($c['value'], 1) : '—' }}
                                        </strong>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <footer class="rs-skill-foot">
                            <a href="{{ $s['reviewUrl'] }}" class="rs-link">
                                {{ $s['type'] === 'auto' ? 'Xem đáp án' : 'Xem nhận xét' }}
                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                            </a>
                        </footer>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
</div>

{{-- ── Xác nhận làm lại ─────────────────────────────────────────── --}}
@if($canRetake)
    <div class="rs-modal" id="rsRetakeModal" role="dialog" aria-modal="true" aria-labelledby="rsRetakeTitle" hidden>
        <div class="rs-modal-backdrop" data-retake-close></div>
        <div class="rs-modal-card">
            <span class="rs-modal-icon"><i class="fas fa-exclamation-triangle" aria-hidden="true"></i></span>
            <h3 id="rsRetakeTitle">Xác nhận làm lại bài thi</h3>
            <p>Kết quả hiện tại của bạn sẽ bị hủy và không thể khôi phục.<br>Bạn có chắc chắn muốn làm lại bài thi này không?</p>
            <form action="{{ $retakeUrl }}" method="POST" class="rs-modal-actions">
                @csrf
                <button type="button" class="rs-btn rs-btn--outline" data-retake-close>Hủy</button>
                <button type="submit" class="rs-btn rs-btn--primary" data-retake-submit>Xác nhận làm lại</button>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts_bottom')
<script>
(function () {
    // ── Tab Overall / Breakdown ──────────────────────────────────────
    var tabs = document.querySelectorAll('.rs-tab');
    var panels = document.querySelectorAll('.rs-panel');

    function showTab(name) {
        tabs.forEach(function (t) {
            var on = t.dataset.tab === name;
            t.classList.toggle('is-active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panels.forEach(function (p) {
            var on = p.dataset.panel === name;
            p.classList.toggle('is-active', on);
            p.hidden = !on;
        });
        var url = window.location.pathname + window.location.search + (name === 'breakdown' ? '#breakdown' : '');
        window.history.replaceState(null, '', url);
    }

    tabs.forEach(function (t) {
        t.addEventListener('click', function () { showTab(t.dataset.tab); });
    });
    if (window.location.hash === '#breakdown') showTab('breakdown');

    // ── Modal làm lại ────────────────────────────────────────────────
    var modal = document.getElementById('rsRetakeModal');
    if (!modal) return;

    var opener = document.querySelector('[data-retake-open]');
    var submit = modal.querySelector('[data-retake-submit]');

    function openModal() {
        modal.hidden = false;
        document.body.classList.add('rs-modal-open');
        submit.focus();
    }
    function closeModal() {
        modal.hidden = true;
        document.body.classList.remove('rs-modal-open');
        if (opener) opener.focus();
    }

    if (opener) opener.addEventListener('click', openModal);
    modal.querySelectorAll('[data-retake-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !modal.hidden) closeModal();
    });

    // Chặn bấm 2 lần -> tạo 2 attempt.
    modal.querySelector('form').addEventListener('submit', function () {
        submit.disabled = true;
        submit.textContent = 'Đang chuẩn bị bài thi...';
    });
})();
</script>
@endpush