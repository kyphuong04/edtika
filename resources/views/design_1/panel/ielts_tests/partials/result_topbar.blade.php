{{--
    Navbar dùng chung cho cụm "sau khi thi": trang kết quả (Overall) và
    trang chữa bài (Breakdown).

    $topbar = [
        'active'       => 'overall' | 'breakdown',
        'logoUrl'      => URL logo (về danh sách đề),
        'overallUrl'   => URL trang kết quả, null = không xem được (giáo viên xem bài học viên),
        'breakdownUrl' => URL trang chữa bài,
        'stats'        => null | ['correct', 'incorrect', 'empty', 'total'],
        'canRetake'    => bool  (kèm @include('...partials.retake_modal') trong trang),
        'studentName'  => null | tên học viên (khi giáo viên xem bài),
        'sections'     => [] | [['label', 'url', 'active']] — đề nhiều kỹ năng,
        'extraClass'   => class thêm cho thẻ header,
    ]
--}}
@php
    $tb = $topbar ?? [];
    $tbActive = $tb['active'] ?? 'overall';
    $tbStats = $tb['stats'] ?? null;
    $tbSections = $tb['sections'] ?? [];
@endphp

<header class="rs-topbar {{ $tb['extraClass'] ?? '' }}">
    <div class="rs-topbar-left">
        <a href="{{ $tb['logoUrl'] ?? '#' }}" class="rs-logo" title="Về danh sách đề">EDTIKA</a>

        <nav class="rs-tabs" aria-label="Kết quả">
            @if(!empty($tb['overallUrl']))
                <a href="{{ $tb['overallUrl'] }}" class="rs-tab {{ $tbActive === 'overall' ? 'is-active' : '' }}"
                   @if($tbActive === 'overall') aria-current="page" @endif>Overall</a>
            @else
                <span class="rs-tab is-disabled" title="Chỉ học viên làm bài mới xem được trang này">Overall</span>
            @endif
            <a href="{{ $tb['breakdownUrl'] ?? '#' }}" class="rs-tab {{ $tbActive === 'breakdown' ? 'is-active' : '' }}"
               @if($tbActive === 'breakdown') aria-current="page" @endif>Breakdown</a>
        </nav>

        @if(count($tbSections) > 1)
            <nav class="rs-skill-switch" aria-label="Kỹ năng">
                @foreach($tbSections as $s)
                    <a href="{{ $s['url'] }}" class="{{ !empty($s['active']) ? 'is-active' : '' }}">{{ $s['label'] }}</a>
                @endforeach
            </nav>
        @endif

        @if(!empty($tb['studentName']))
            <span class="rs-student" title="Bài làm của học viên">
                <i class="far fa-user" aria-hidden="true"></i> {{ $tb['studentName'] }}
            </span>
        @endif
    </div>

    @if($tbStats)
        <div class="rs-statbar" aria-label="Thống kê bài làm">
            <div class="rs-statbar-cell is-correct">
                <strong>{{ $tbStats['correct'] }}</strong><span>Đúng</span>
            </div>
            <div class="rs-statbar-cell is-incorrect">
                <strong>{{ $tbStats['incorrect'] }}</strong><span>Sai</span>
            </div>
            <div class="rs-statbar-cell is-empty">
                <strong>{{ $tbStats['empty'] }}</strong><span>Chưa làm</span>
            </div>
            <div class="rs-statbar-cell is-score">
                <strong>{{ $tbStats['correct'] }} / {{ $tbStats['total'] }}</strong><span>Số câu đúng</span>
            </div>
        </div>
    @endif

    <div class="rs-topbar-actions">
        @if(!empty($tb['canRetake']))
            <button type="button" class="rs-btn rs-btn--outline" data-retake-open>
                <i class="fas fa-redo-alt" aria-hidden="true"></i> Làm lại
            </button>
        @endif
        <button type="button" class="rs-btn rs-btn--primary" disabled title="Tính năng đang được phát triển">
            <i class="fas fa-share-alt" aria-hidden="true"></i> Chia sẻ bài làm
        </button>
    </div>
</header>
