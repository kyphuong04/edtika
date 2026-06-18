@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
/* ─── Layout ─────────────────────────────────────────────────── */
.tp-top-left,  .tp-bottom-left  { flex: 2; min-width: 0; }
.tp-top-right, .tp-bottom-right { flex: 1; min-width: 0; display: flex; flex-direction: column; }

/* ─── Welcome card ──────────────────────────────────────────── */
.tp-welcome {
    background: #fff;
    border-radius: 20px;
    padding: 24px 28px;
}
.tp-welcome-title {
    font-size: 1rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #111;
    margin-bottom: 6px;
}
.tp-kpi-label {
    font-size: .82rem;
    color: #6b7280;
    margin-bottom: 10px;
}
.tp-kpi-track {
    background: #e5e7eb;
    border-radius: 20px;
    height: 10px;
    overflow: hidden;
}
.tp-kpi-fill {
    height: 100%;
    border-radius: 20px;
    background: #111827;
    transition: width .6s ease;
}

/* ─── Monthly stat cards ─────────────────────────────────────── */
.tp-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-top: 18px;
}
.tp-stat-card {
    background: #fff;
    border-radius: 16px;
    padding: 50px 12px 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 5px;
}
.tp-stat-label {
    font-size: .78rem;
    font-weight: 600;
    color: #6b7280;
    line-height: 1.35;
}
.tp-stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #111;
    line-height: 1;
}

/* ─── KPI Chart card ─────────────────────────────────────────── */
.tp-chart-card {
    background: #fff;
    border-radius: 20px;
    padding: 40px 40px;
    height: 100%;
    box-sizing: border-box;
}
.tp-chart-title {
    font-size: .88rem;
    font-weight: 700;
    color: #111;
}
.tp-chart-sub {
    font-size: .76rem;
    color: #9ca3af;
    margin-top: 2px;
}
.tp-period-btn {
    background: transparent;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: .78rem;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    transition: all .18s;
}
.tp-period-btn.active {
    background: #511D99;
    border-color: #511D99;
    color: #fff;
}

/* ─── Profile card (right) ───────────────────────────────────── */
.tp-profile-card {
    background: #fff;
    border-radius: 20px;
    padding: 28px 20px 22px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}
.tp-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}
.tp-teacher-name {
    font-size: 1rem;
    font-weight: 800;
    color: #111;
    text-align: center;
    margin: 0;
}
.tp-level-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f3f4f6;
    border-radius: 20px;
    padding: 4px 14px;
    font-size: .8rem;
    font-weight: 600;
    color: #374151;
}
.tp-level-edit-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    color: #9ca3af;
    line-height: 1;
}
.tp-level-edit-btn:hover { color: #4b5563; }

/* ─── Metric cards (right) ───────────────────────────────────── */
.tp-metric-grid {
    display: flex;
    flex-direction: column;
    gap: 10px;
    height: 100%;
}
.tp-metric-card {
    background: #fff;
    border-radius: 16px;
    padding: 14px 18px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color .18s, box-shadow .18s;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex: 1;
}
.tp-metric-card.selected {
    border-color: #511D99;
    box-shadow: 0 2px 8px rgba(81,29,153,.18);
}
.tp-metric-label {
    font-size: .78rem;
    font-weight: 600;
    color: #6b7280;
    line-height: 1.4;
    flex: 1;
}
.tp-metric-value-wrap {
    display: flex;
    align-items: baseline;
    gap: 4px;
    white-space: nowrap;
    flex-shrink: 0;
}
.tp-metric-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #111;
    line-height: 1;
}
.tp-metric-unit {
    font-size: .72rem;
    color: #9ca3af;
}

/* Level edit modal */
.tp-level-modal .modal-content { border-radius: 20px; border: none; }
.tp-level-modal .modal-header { border-bottom: none; padding: 24px 28px 8px; }
.tp-level-modal .modal-body   { padding: 0 28px 28px; }

/* Stars */
.tp-stars { display: flex; gap: 3px; }
.tp-star-icon { color: #d1d5db; }
.tp-star-icon.filled { color: #f59e0b; }
</style>
@endpush

@section('content')
@php
    $authUser = auth()->user();
    $isManager = $authUser->isManager() || $authUser->isAdmin();

    /* ── KPI ── */
    $kpiTarget  = (int)($teacherData['kpiTarget'] ?? 0);
    $kpiCurrent = (int)($teacherData['kpiCurrent'] ?? 0);
    $kpiPct     = $kpiTarget > 0 ? min(100, round($kpiCurrent / $kpiTarget * 100)) : 0;

    /* ── Monthly stats ── */
    $monthlyStudents  = (int)($teacherData['monthlyStudents'] ?? 0);
    $monthlyReferrals = (int)($teacherData['monthlyReferrals'] ?? 0);
    $monthlySpeaking  = (int)($teacherData['monthlySpeaking'] ?? 0);
    $monthlyWriting   = (int)($teacherData['monthlyWriting'] ?? 0);

    /* ── Profile ── */
    $teacherLevel  = $teacherData['teacherLevel'] ?? 'Junior';
    $avgRating     = $teacherData['avgRating'] ?? 0;
    $reviewCount   = $teacherData['reviewCount'] ?? 0;

    /* ── Chart & metric data ── (indexed by period: week/month/year, then metric) ── */
    $chartData   = $teacherData['chartData']   ?? [];
    $metricData  = $teacherData['metricData']  ?? [];
@endphp

<div class="tp-page">

    {{-- ── Row 1: Welcome + Stats  |  Profile card ──────────────── --}}
    <div class="d-flex gap-16 align-items-stretch flex-wrap flex-xl-nowrap">

        {{-- LEFT top: welcome bar + 4 stat cards --}}
        <div class="tp-top-left">

            <div class="tp-welcome">
                <p class="tp-welcome-title">
                    WELCOME, HAVE A GOOD DAY {{ strtoupper($user->full_name) }}!
                </p>
                <p class="tp-kpi-label">
                    Tổng số bài Speaking &amp; Writing đã chấm:
                    <strong class="text-dark">{{ $kpiCurrent }}/{{ $kpiTarget > 0 ? $kpiTarget : '—' }} bài</strong>
                    @if($isManager)
                        <button type="button"
                                class="btn btn-link btn-sm p-0 ml-8 font-12"
                                data-toggle="modal"
                                data-target="#kpiEditModal">
                            (Đặt KPI)
                        </button>
                    @endif
                </p>
                <div class="tp-kpi-track">
                    <div class="tp-kpi-fill" style="width:{{ $kpiPct }}%;"></div>
                </div>
            </div>

            <div class="tp-stat-grid">
                <div class="tp-stat-card">
                    <span class="tp-stat-label">Tổng số Học viên phụ trách</span>
                    <span class="tp-stat-value">{{ $monthlyStudents }}</span>
                    <span class="font-11 text-gray-400">tháng này</span>
                </div>
                <div class="tp-stat-card">
                    <span class="tp-stat-label">Lời mời giới thiệu đến Học viên</span>
                    <span class="tp-stat-value">{{ $monthlyReferrals }}</span>
                    <span class="font-11 text-gray-400">tháng này</span>
                </div>
                <div class="tp-stat-card">
                    <span class="tp-stat-label">Bài Speaking đã chấm</span>
                    <span class="tp-stat-value">{{ $monthlySpeaking }}</span>
                    <span class="font-11 text-gray-400">tháng này</span>
                </div>
                <div class="tp-stat-card">
                    <span class="tp-stat-label">Bài Writing đã chấm</span>
                    <span class="tp-stat-value">{{ $monthlyWriting }}</span>
                    <span class="font-11 text-gray-400">tháng này</span>
                </div>
            </div>

        </div>{{-- /tp-top-left --}}

        {{-- RIGHT top: Profile card (stretches to match left height) --}}
        <div class="tp-top-right">
            <div class="tp-profile-card" style="height:100%;">
                <img src="{{ $user->getAvatar() }}"
                     alt="{{ $user->full_name }}"
                     class="tp-avatar">

                <p class="tp-teacher-name">{{ strtoupper($user->full_name) }}</p>

                <span class="tp-level-badge">
                    Level: {{ $teacherLevel }}
                    @if($isManager)
                        <button type="button"
                                class="tp-level-edit-btn"
                                data-toggle="modal"
                                data-target="#levelEditModal"
                                title="Chỉnh sửa Level">
                            <x-iconsax-lin-edit-2 class="icons" width="13px" height="13px"/>
                        </button>
                    @endif
                </span>

                <div class="tp-stars">
                    @for($s = 1; $s <= 5; $s++)
                        @php $filled = $s <= round($avgRating); @endphp
                        <span class="tp-star-icon{{ $filled ? ' filled' : '' }}">
                            <x-iconsax-bol-star-1 class="icons" width="22px" height="22px"/>
                        </span>
                    @endfor
                </div>
                @if($reviewCount > 0)
                    <span class="font-12 text-gray-400">({{ $avgRating }}/5 từ {{ $reviewCount }} đánh giá)</span>
                @endif
            </div>
        </div>{{-- /tp-top-right --}}

    </div>{{-- /row-1 --}}

    {{-- ── Row 2: KPI Chart  |  4 Metric cards ───────────────────── --}}
    <div class="d-flex gap-16 align-items-stretch flex-wrap flex-xl-nowrap" style="margin-top:18px;">

        {{-- LEFT bottom: KPI chart --}}
        <div class="tp-bottom-left">
            <div class="tp-chart-card">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-8">
                    <div>
                        <p class="tp-chart-title">Biểu đồ so sánh KPI so với Team</p>
                        <p class="tp-chart-sub" id="tpChartSub">Theo dõi KPI của Giáo viên so với Team</p>
                    </div>
                    <div class="d-flex gap-6">
                        <button class="tp-period-btn active" data-period="week">Weekly</button>
                        <button class="tp-period-btn"        data-period="month">Monthly</button>
                        <button class="tp-period-btn"        data-period="year">Yearly</button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-16 mt-10 mb-4">
                    <div class="d-flex align-items-center gap-6">
                        <span class="d-inline-block rounded-circle" style="width:10px;height:10px;background:#511D99;"></span>
                        <span class="font-12 text-gray-500">Tôi</span>
                    </div>
                    <div class="d-flex align-items-center gap-6">
                        <span class="d-inline-block rounded-circle" style="width:10px;height:10px;background:#d1d5db;"></span>
                        <span class="font-12 text-gray-500">Team TB</span>
                    </div>
                </div>

                <div id="tpKpiChart" style="min-height:240px;"></div>
            </div>
        </div>{{-- /tp-bottom-left --}}

        {{-- RIGHT bottom: 4 Metric cards (same height as chart) --}}
        <div class="tp-bottom-right">
            <div class="tp-metric-grid">

                <div class="tp-metric-card selected" data-metric="response_time">
                    <span class="tp-metric-label">Thời gian trung bình phản hồi tin nhắn chờ</span>
                    <div class="tp-metric-value-wrap">
                        <span class="tp-metric-value" id="val-response_time">{{ $metricData['week']['response_time'] ?? '—' }}</span>
                        <span class="tp-metric-unit">phút</span>
                    </div>
                </div>

                <div class="tp-metric-card" data-metric="grading_wait">
                    <span class="tp-metric-label">Thời gian chờ trung bình chấm bài Writing &amp; Speaking</span>
                    <div class="tp-metric-value-wrap">
                        <span class="tp-metric-value" id="val-grading_wait">{{ $metricData['week']['grading_wait'] ?? '—' }}</span>
                        <span class="tp-metric-unit">giờ</span>
                    </div>
                </div>

                <div class="tp-metric-card" data-metric="feedback_count">
                    <span class="tp-metric-label">Số feedback trung bình</span>
                    <div class="tp-metric-value-wrap">
                        <span class="tp-metric-value" id="val-feedback_count">{{ $metricData['week']['feedback_count'] ?? '—' }}</span>
                        <span class="tp-metric-unit">feedback/bài</span>
                    </div>
                </div>

                <div class="tp-metric-card" data-metric="improvement">
                    <span class="tp-metric-label">Chỉ số cải thiện trung bình của Học viên</span>
                    <div class="tp-metric-value-wrap">
                        <span class="tp-metric-value" id="val-improvement">{{ $metricData['week']['improvement'] ?? '—' }}</span>
                        <span class="tp-metric-unit">điểm band</span>
                    </div>
                </div>

            </div>
        </div>{{-- /tp-bottom-right --}}

    </div>{{-- /row-2 --}}

</div>

{{-- ============================================================
     MODALS
============================================================= --}}

{{-- KPI Edit Modal (manager only) --}}
@if($isManager)
<div class="modal fade" id="kpiEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border-radius:20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-16 font-weight-bold">Đặt KPI tháng cho Giáo viên</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-12 pb-24 px-24">
                <form action="{{ route('panel.teacher-profile.kpi', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-13 font-weight-600 text-dark">Mục tiêu bài chấm/tháng</label>
                        <input type="number" name="kpi_target" min="0" max="9999"
                               value="{{ $kpiTarget }}"
                               class="form-control mt-6 rounded-10">
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-10 mt-8">Lưu KPI</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Level Edit Modal (manager only) --}}
<div class="modal fade" id="levelEditModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content" style="border-radius:20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-16 font-weight-bold">Chỉnh sửa Level Giáo viên</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-12 pb-24 px-24">
                <form action="{{ route('panel.teacher-profile.level', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="font-13 font-weight-600 text-dark">Level</label>
                        <select name="teacher_level" class="form-control mt-6 rounded-10">
                            @foreach(['Junior','Mid','Senior','Expert','Master'] as $lvl)
                                <option value="{{ $lvl }}" {{ $teacherLevel === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 rounded-10 mt-8">Lưu Level</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts_bottom')
<script src="/assets/design_1/vendor/apexcharts/apexcharts.js"></script>
<script>
(function () {
    "use strict";

    /* ── Pre-loaded data from PHP ───────────────────────────── */
    var ALL_CHART_DATA   = @json($chartData);
    var ALL_METRIC_DATA  = @json($metricData);

    /* ── State ──────────────────────────────────────────────── */
    var activePeriod = 'week';
    var activeMetric = 'response_time';

    /* ── ApexChart instance ─────────────────────────────────── */
    var chartOptions = buildChartOptions(activePeriod, activeMetric);
    var apexChart = new ApexCharts(document.querySelector('#tpKpiChart'), chartOptions);
    apexChart.render();

    function buildChartOptions(period, metric) {
        var dataset = (ALL_CHART_DATA[period] && ALL_CHART_DATA[period][metric])
                        ? ALL_CHART_DATA[period][metric]
                        : { labels: [], teacher: [], team: [] };

        return {
            series: [
                { name: 'Tôi',     data: dataset.teacher },
                { name: 'Team TB', data: dataset.team    },
            ],
            chart: {
                type: 'bar',
                height: 240,
                toolbar: { show: false },
                fontFamily: 'inherit',
            },
            colors: ['#511D99', '#d1d5db'],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    borderRadiusApplication: 'end',
                    grouped: true,
                },
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: dataset.labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: '11px', colors: '#111', fontWeight: '600' } },
            },
            yaxis: {
                labels: {
                    style: { fontSize: '11px', colors: '#111', fontWeight: '600' },
                    formatter: function (v) { return parseFloat(v % 1 === 0 ? v : v.toFixed(1)); },
                },
                tickAmount: 4,
                min: 0,
            },
            grid: { borderColor: '#f3f4f6', strokeDashArray: 4 },
            legend: { show: false },
            tooltip: {
                shared: true,
                intersect: false,
            },
        };
    }

    function updateChart(period, metric) {
        var dataset = (ALL_CHART_DATA[period] && ALL_CHART_DATA[period][metric])
                        ? ALL_CHART_DATA[period][metric]
                        : { labels: [], teacher: [], team: [] };

        apexChart.updateOptions({
            series: [
                { name: 'Tôi',     data: dataset.teacher },
                { name: 'Team TB', data: dataset.team    },
            ],
            xaxis: { categories: dataset.labels },
        });
    }

    function updateMetricValues(period) {
        var data = ALL_METRIC_DATA[period] || {};
        var metrics = ['response_time', 'grading_wait', 'feedback_count', 'improvement'];
        metrics.forEach(function (m) {
            var el = document.getElementById('val-' + m);
            if (el) el.textContent = (data[m] !== undefined && data[m] !== null) ? data[m] : '—';
        });
    }

    /* ── Period buttons (chart) ──────────────────────────────── */
    document.querySelectorAll('.tp-period-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tp-period-btn').forEach(function (b) { b.classList.remove('active'); });
            this.classList.add('active');
            activePeriod = this.dataset.period;
            updateChart(activePeriod, activeMetric);
            updateMetricValues(activePeriod);
        });
    });

    /* ── Metric card selection ───────────────────────────────── */
    document.querySelectorAll('.tp-metric-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('.tp-metric-card').forEach(function (c) { c.classList.remove('selected'); });
            this.classList.add('selected');
            activeMetric = this.dataset.metric;
            updateChart(activePeriod, activeMetric);
        });
    });

}());
</script>
@endpush
