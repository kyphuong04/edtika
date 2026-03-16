@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
/* ── Layout ─────────────────────────────────────────── */
.sd-page { display: flex; gap: 20px; align-items: flex-start; }
.sd-left  { flex: 2; min-width: 0; display: flex; flex-direction: column; gap: 16px; }
.sd-right { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 16px; }
@media (max-width: 991px) {
    .sd-page { flex-direction: column; }
    .sd-left, .sd-right { flex: unset; width: 100%; }
}

/* ── Shared card shell ──────────────────────────────── */
.sd-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    padding: 18px 20px;
}
.sd-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e2a3b;
    margin: 0 0 14px;
    text-transform: uppercase;
    letter-spacing: .04em;
    text-align: center;
}

/* ── Stat boxes row ─────────────────────────────────── */
.sd-stats { display: flex; gap: 12px; }
.sd-stat-box {
    flex: 1;
    background: #fff;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    border: 1px solid #f0f0f0;
}
.sd-stat-box .stat-label {
    font-size: 11px;
    font-weight: 600;
    color: #8896b0;
    text-transform: uppercase;
    letter-spacing: .05em;
    text-align: center;
}
.sd-stat-box .stat-value {
    font-size: 26px;
    font-weight: 800;
    color: #5482ff;
    line-height: 1.1;
    text-align: center;
}
.sd-stat-box .stat-value.negative { color: #e74c3c; }
.sd-stat-box .stat-value.positive { color: #27ae60; }
.sd-stat-box .stat-unit {
    font-size: 13px;
    font-weight: 600;
    color: #adb5bd;
}
.sd-stat-box .stat-hint { text-align: center; }

/* ── Middle row ─────────────────────────────────────── */
.sd-mid { display: flex; gap: 14px; }
.sd-mid > div { flex: 1; min-width: 0; }

/* ── Mock test table ────────────────────────────────── */
.sd-mock-table { width: 100%; border-collapse: collapse; font-size: 12px; }
.sd-mock-table th {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #8896b0;
    padding: 4px 6px 8px;
    border-bottom: 1px solid #f0f0f0;
    text-align: center;
}
.sd-mock-table th:first-child { text-align: left; }
.sd-mock-table td {
    padding: 7px 6px;
    border-bottom: 1px solid #f8f9fa;
    color: #495057;
    text-align: center;
    vertical-align: middle;
}
.sd-mock-table td:first-child { text-align: left; font-weight: 600; color: #1e2a3b; max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.sd-mock-table tr:last-child td { border-bottom: none; }
.sd-band-pill {
    display: inline-block;
    padding: 1px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    background: #f0f3ff;
    color: #5482ff;
}
.sd-band-pill.overall { background: #5482ff; color: #fff; }

/* ── Weak points ────────────────────────────────────── */
.sd-weak-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f8f9fa;
}
.sd-weak-item:last-child { border-bottom: none; }
.sd-weak-label {
    width: 80px;
    flex-shrink: 0;
    font-size: 12px;
    font-weight: 600;
    color: #495057;
}
.sd-weak-bar { flex: 1; height: 8px; background: #f0f0f0; border-radius: 20px; overflow: hidden; }
.sd-weak-bar-fill { height: 100%; border-radius: 20px; transition: width .6s; }
.sd-weak-band { width: 32px; flex-shrink: 0; font-size: 12px; font-weight: 700; text-align: right; }

/* ── Profile card ───────────────────────────────────── */
.sd-profile { text-align: center; padding: 24px 20px 18px; }
.sd-profile-avatar {
    width: 72px; height: 72px;
    border-radius: 50%; object-fit: cover;
    border: 3px solid #f0f0f0;
    margin: 0 auto 10px;
    display: block;
}
.sd-profile-name { font-size: 15px; font-weight: 800; color: #1e2a3b; margin: 0 0 4px; }
.sd-profile-meta { font-size: 12px; color: #8896b0; margin: 0 0 12px; }
.sd-profile-stats { display: flex; flex-direction: column; gap: 5px; text-align: center; margin-top: 12px; }
.sd-pstat { font-size: 13px; color: #8896b0; }
.sd-pstat .ps-label { font-weight: 400; }
.sd-pstat .ps-val { font-weight: 600; color: #1e2a3b; }
.sd-rank-badge {
    display: inline-block;
    background: linear-gradient(135deg,#5482ff,#7fa3ff);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
}

/* ── Course card ────────────────────────────────────── */
.sd-course-name { font-size: 13px; font-weight: 700; color: #1e2a3b; margin: 0 0 10px; line-height: 1.4; text-align: center; }
.sd-course-stats { display: flex; flex-direction: column; gap: 5px; font-size: 13px; color: #8896b0; margin-bottom: 12px; text-align: center; }
.sd-course-stats span strong { color: #5482ff; font-size: 14px; }
.sd-course-progress { height: 6px; background: #f0f0f0; border-radius: 20px; overflow: hidden; }
.sd-course-progress-bar { height: 100%; background: linear-gradient(90deg,#5482ff,#7fa3ff); border-radius: 20px; transition: width .6s; }
.sd-course-pct { font-size: 11px; color: #adb5bd; margin-top: 4px; text-align: right; }

/* ── S&W History ────────────────────────────────────── */
.sd-sw-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 12px; font-size: 12px;
    border-radius: 10px;
    border: 1px solid #f0f0f0;
    margin-bottom: 8px;
    text-decoration: none;
    color: inherit;
    background: #fff;
    transition: box-shadow .15s, border-color .15s;
    cursor: pointer;
}
.sd-sw-item:hover { border-color: #c7d4ff; box-shadow: 0 2px 10px rgba(84,130,255,.1); color: inherit; text-decoration: none; }
.sd-sw-item-left { flex: 1; min-width: 0; }
.sd-sw-title { font-weight: 600; color: #1e2a3b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; }
.sd-sw-meta { color: #8896b0; font-size: 11px; margin-top: 2px; }
.sd-sw-bands { display: flex; gap: 6px; align-items: center; flex-shrink: 0; margin-left: 10px; }
.sd-sw-band-pill { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; background: #f0f3ff; color: #5482ff; white-space: nowrap; }
.sd-sw-band-pill.s-pill { background: #fff0f6; color: #e84393; }
.sd-sw-band-pill.w-pill { background: #f0f8ff; color: #0084d1; }
.sd-sw-action { font-size: 10px; font-weight: 700; color: #5482ff; margin-left: 8px; white-space: nowrap; background: #f0f3ff; padding: 3px 8px; border-radius: 20px; flex-shrink: 0; }
/* toggle more button */
.sd-sw-more-btn { display: block; width: 100%; text-align: center; font-size: 12px; font-weight: 600; color: #5482ff; background: #f6f8ff; border: 1px solid #e0e7ff; border-radius: 10px; padding: 7px; cursor: pointer; margin-top: 4px; transition: background .15s; }
.sd-sw-more-btn:hover { background: #eef1ff; }
.sd-empty { text-align: center; color: #adb5bd; font-size: 12px; padding: 20px 0; }
</style>
@endpush

@section('content')
    <section>

        {{-- ██ MAIN 2-COLUMN LAYOUT ██ --}}
        <div class="sd-page">

            {{-- ════════ LEFT 2/3 ════════ --}}
            <div class="sd-left">

                {{-- ── Stat boxes ────────────────────────────────────────── --}}
                <div class="sd-stats">
                    <div class="sd-stat-box">
                        <span class="stat-label">Quizz Accuracy</span>
                        <div class="stat-value">{{ $quizAccuracy }}<span class="stat-unit">%</span></div>
                        <span class="stat-hint" style="font-size:11px;color:#adb5bd;">Độ chính xác khi làm Quiz</span>
                    </div>
                    <div class="sd-stat-box">
                        <span class="stat-label">Exercise Accuracy</span>
                        <div class="stat-value">{{ $exerciseAccuracy }}<span class="stat-unit">%</span></div>
                        <span class="stat-hint" style="font-size:11px;color:#adb5bd;">Độ chính xác luyện tập</span>
                    </div>
                    <div class="sd-stat-box">
                        <span class="stat-label">Improvement Rate</span>
                        <div class="stat-value {{ $improvementRate >= 0 ? 'positive' : 'negative' }}">{{ $improvementRate >= 0 ? '+' : '' }}{{ $improvementRate }}<span class="stat-unit">%</span></div>
                        <span class="stat-hint" style="font-size:11px;color:#adb5bd;">(Cuối - Đầu) / Đầu × 100</span>
                    </div>
                    <div class="sd-stat-box">
                        <span class="stat-label">Satisfaction Rate</span>
                        <div class="stat-value {{ $satisfactionRate >= 80 ? 'positive' : ($satisfactionRate >= 50 ? '' : 'negative') }}">{{ $satisfactionRate }}<span class="stat-unit">%</span></div>
                        <span class="stat-hint" style="font-size:11px;color:#adb5bd;">Đánh giá trung bình của học viên</span>
                    </div>
                </div>

                {{-- ── Radar Chart + Mock Test Results ────────────────────── --}}
                <div class="sd-mid">

                    {{-- Radar --}}
                    <div class="sd-card">
                        <p class="sd-card-title">Radar 4 Skills</p>
                        <div id="sdRadarChart" style="min-height:220px;"></div>
                    </div>

                    {{-- Mock Tests --}}
                    <div class="sd-card" style="overflow-x:auto;">
                        <p class="sd-card-title">Kết quả Mock Test</p>
                        @if($mockTestResults->isEmpty())
                            <div class="sd-empty">Chưa có bài thi Mock Test nào.</div>
                        @else
                            <table class="sd-mock-table">
                                <thead>
                                    <tr>
                                        <th>Đề thi</th>
                                        <th>L</th><th>R</th><th>W</th><th>S</th>
                                        <th>Overall</th>
                                        <th>Ngày</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($mockTestResults as $mt)
                                    <tr>
                                        <td title="{{ $mt->test_title }}">{{ $mt->test_title }}</td>
                                        <td><span class="sd-band-pill">{{ $mt->listening_band ?? '–' }}</span></td>
                                        <td><span class="sd-band-pill">{{ $mt->reading_band   ?? '–' }}</span></td>
                                        <td><span class="sd-band-pill">{{ $mt->writing_band   ?? '–' }}</span></td>
                                        <td><span class="sd-band-pill">{{ $mt->speaking_band  ?? '–' }}</span></td>
                                        <td><span class="sd-band-pill overall">{{ $mt->overall_band ?? '–' }}</span></td>
                                        <td style="color:#adb5bd;white-space:nowrap;">
                                            @if($mt->completed_at)
                                                {{ date('d/m/Y', (int)$mt->completed_at) }}
                                            @else —
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                {{-- ── Weak Points ─────────────────────────────────────────── --}}
                <div class="sd-card">
                    <p class="sd-card-title">Weak Point (Kỹ năng yếu nhất)</p>
                    @php $hasAnyBand = collect($skillBands)->except('overall')->sum() > 0; @endphp
                    @if(!$hasAnyBand)
                        <div class="sd-empty">Chưa có dữ liệu kỹ năng từ Mock Test.</div>
                    @else
                        @foreach($weakPoints as $wp)
                            @php
                                $pct   = $wp['band'] > 0 ? round($wp['band'] / 9 * 100) : 0;
                                $color = $wp['band'] >= 6 ? '#27ae60' : ($wp['band'] >= 4 ? '#f39c12' : '#e74c3c');
                            @endphp
                            <div class="sd-weak-item">
                                <span class="sd-weak-label">{{ $wp['label'] }}</span>
                                <div class="sd-weak-bar">
                                    <div class="sd-weak-bar-fill" style="width:{{ $pct }}%;background:{{ $color }};"></div>
                                </div>
                                <span class="sd-weak-band" style="color:{{ $color }}">{{ $wp['band'] > 0 ? $wp['band'] : '—' }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ── Back button ──────────────────────────────────────── --}}
                <div class="text-center mt-2">
                    <a href="/panel/my-students/list" class="d-inline-flex align-items-center justify-content-center"
                       style="font-size:13px;color:#1e2a3b;text-decoration:none;gap:6px;
                              border:1.5px solid #d0d7e2;border-radius:999px;
                              padding:7px 20px;background:#fff;
                              transition:border-color .2s,box-shadow .2s;"
                       onmouseover="this.style.borderColor='#5482ff';this.style.boxShadow='0 2px 8px rgba(84,130,255,.15)'"
                       onmouseout="this.style.borderColor='#d0d7e2';this.style.boxShadow='none'">
                        ← Quay lại danh sách
                    </a>
                </div>

            </div>{{-- /sd-left --}}

            {{-- ════════ RIGHT 1/3 ════════ --}}
            <div class="sd-right">

                {{-- ── Student Profile ──────────────────────────────────── --}}
                <div class="sd-card sd-profile">
                    <img src="{{ $student->getAvatar(80) }}" class="sd-profile-avatar"
                         alt="{{ $student->full_name }}"
                         onerror="this.src='/assets/default/img/user/avatar_default.png'">
                    <p class="sd-profile-name">{{ $student->full_name }}</p>
                    <p class="sd-profile-meta">{{ $student->email }}</p>
                    <div class="sd-profile-stats">
                        <div class="sd-pstat">
                            <span class="ps-label">Estimate Band: </span><span class="ps-val" style="color:#5482ff;">{{ $student->estimated_band > 0 ? $student->estimated_band : '—' }}</span>
                        </div>
                        <div class="sd-pstat">
                            <span class="ps-label">Aim Band: </span><span class="ps-val">{{ $student->aim_band ?? '—' }}</span>
                        </div>
                        <div class="sd-pstat">
                            <span class="ps-label">Ngày thi: </span><span class="ps-val">@if(!empty($student->exam_date) && $student->exam_date !== '0000-00-00'){{ \Carbon\Carbon::parse($student->exam_date)->format('d/m/Y') }}@else—@endif</span>
                        </div>
                        <div class="sd-pstat">
                            <span class="ps-label">Ranking: </span><span class="ps-val">@if($rankDisplay !== '—')<span class="sd-rank-badge">{{ $rankDisplay }}</span>@else—@endif</span>
                        </div>
                    </div>
                </div>

                {{-- ── Khóa học đang học ────────────────────────────────── --}}
                <div class="sd-card">
                    <p class="sd-card-title">Khóa học đang học</p>
                    @if(empty($coursesData))
                        <div class="sd-empty">Chưa đăng ký khóa nào.</div>
                    @else
                        @foreach($coursesData as $cd)
                            <div class="{{ !$loop->first ? 'mt-14 pt-14' : '' }}" style="{{ !$loop->first ? 'border-top:1px solid #f8f9fa;' : '' }}">
                                <p class="sd-course-name" style="text-decoration:none;">{{ $cd['webinar']->title }}</p>
                                <div class="sd-course-stats">
                                    <span>Số bài học: <strong>{{ $cd['completed_lessons'] }}</strong></span>
                                    <span>Số đề luyện tập: <strong>{{ $cd['exercises_done'] }}</strong></span>
                                </div>
                                <div class="sd-course-progress">
                                    <div class="sd-course-progress-bar" style="width:{{ min(100, max(0, $cd['progress'])) }}%"></div>
                                </div>
                                <div class="sd-course-pct">{{ number_format($cd['progress'], 1) }}% hoàn thành</div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- ── Lịch sử Speaking & Writing ──────────────────────── --}}
                <div class="sd-card">
                    <p class="sd-card-title">Lịch sử Speaking &amp; Writing</p>
                    @if($swHistory->isEmpty())
                        <div class="sd-empty">Chưa có lịch sử Speaking / Writing.</div>
                    @else
                        @php $swAll = $swHistory; $swCount = $swAll->count(); @endphp
                        <div id="swList">
                        @foreach($swAll as $i => $sw)
                            @php
                                $gradeUrl = '/panel/ielts-grading/' . $sw->id . '/grade';
                                $resultUrl = '/panel/ielts-tests/attempt/' . $sw->id . '/results';
                                $skillLabel = ($sw->speaking_completed && $sw->writing_completed)
                                    ? 'Speaking + Writing'
                                    : ($sw->speaking_completed ? 'Speaking' : 'Writing');
                                $linkUrl = $gradeUrl;
                            @endphp
                            <a href="{{ $linkUrl }}" class="sd-sw-item{{ $i >= 3 ? ' sd-sw-extra d-none' : '' }}" target="_blank">
                                <div class="sd-sw-item-left">
                                    <span class="sd-sw-title" title="{{ $sw->test_title }}">{{ $sw->test_title }}</span>
                                    <div class="sd-sw-meta">
                                        {{ $skillLabel }}
                                        @if($sw->completed_at)
                                            &nbsp;·&nbsp;{{ date('d/m/Y', (int)$sw->completed_at) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="sd-sw-bands">
                                    @if($sw->speaking_completed)
                                        <span class="sd-sw-band-pill s-pill">S: {{ $sw->speaking_band ?? '—' }}</span>
                                    @endif
                                    @if($sw->writing_completed)
                                        <span class="sd-sw-band-pill w-pill">W: {{ $sw->writing_band ?? '—' }}</span>
                                    @endif
                                </div>
                                <span class="sd-sw-action">Xem / Chấm →</span>
                            </a>
                        @endforeach
                        </div>
                        @if($swCount > 3)
                            <button class="sd-sw-more-btn" id="swMoreBtn" onclick="sdSwToggle()">Xem thêm {{ $swCount - 3 }} bài ▾</button>
                        @endif
                    @endif
                </div>

            </div>{{-- /sd-right --}}

        </div>{{-- /sd-page --}}

    </section>
@endsection

@push('scripts_bottom')
<script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>
<script>
(function () {
    'use strict';
    var el = document.getElementById('sdRadarChart');
    if (!el || typeof ApexCharts === 'undefined') return;
    var bands  = @json(array_values($skillBands));
    var labels = ['Listening', 'Reading', 'Writing', 'Speaking', 'Overall'];
    new ApexCharts(el, {
        chart: {
            type: 'radar',
            height: 230,
            toolbar: { show: false },
            fontFamily: 'Roboto, sans-serif',
            background: 'transparent',
        },
        series: [{ name: 'Band', data: bands }],
        xaxis: { categories: labels },
        yaxis: { show: false, min: 0, max: 9 },
        fill:    { opacity: 0.18, colors: ['#5482ff'] },
        stroke:  { width: 2,  colors: ['#5482ff'] },
        markers: { size: 4,   colors: ['#5482ff'], hover: { size: 6 } },
        plotOptions: {
            radar: { polygons: { strokeColors: '#e9ecef', connectorColors: '#e9ecef' } }
        },
        dataLabels: {
            enabled: true,
            style: { fontSize: '10px', colors: ['#374151'] },
            formatter: function (v) { return v > 0 ? v : ''; },
        },
        tooltip: { y: { formatter: function (v) { return v + ' / 9'; } } },
    }).render();
})();

// S&W toggle expand/collapse
function sdSwToggle() {
    var extras = document.querySelectorAll('.sd-sw-extra');
    var btn    = document.getElementById('swMoreBtn');
    var hidden = extras.length > 0 && extras[0].classList.contains('d-none');
    extras.forEach(function(el) {
        el.classList.toggle('d-none', !hidden);
    });
    if (hidden) {
        btn.textContent = 'Thu gọn ▴';
    } else {
        var n = extras.length;
        btn.textContent = 'Xem thêm ' + n + ' bài ▾';
    }
}
</script>
@endpush
