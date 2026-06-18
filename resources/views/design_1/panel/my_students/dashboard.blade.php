@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
    <style>
        /* ── Metric card styles ─────────────────────────────────── */
        .metric-card {
            display: block;
            background: #fff;
            border: 2px solid transparent;
            border-radius: 16px;
            padding: 14px 16px;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
            text-decoration: none !important;
            color: inherit;
            margin-bottom: 10px;
        }
        .metric-card:hover {
            border-color: #511D99;
            box-shadow: 0 4px 16px rgba(81,29,153,.18);
        }
        .metric-card.active {
            border-color: #511D99;
            box-shadow: 0 4px 16px rgba(81,29,153,.25);
        }
        .metric-card .mc-label {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
            line-height: 1.3;
        }
        .metric-card .mc-value {
            font-size: 22px;
            font-weight: 700;
            color: #1e2a3b;
            line-height: 1.2;
            margin-top: 4px;
        }
        .metric-card .mc-sub {
            font-size: 11px;
            color: #adb5bd;
            margin-top: 2px;
        }
        .metric-card .mc-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        /* Student-list metric card (link style) */
        .metric-card.mc-link {
            background: #fff;
        }
        .metric-card.mc-link:hover {
            background: #f0f3ff;
        }

        /* ── Chart wrapper ──────────────────────────────────────── */
        .chart-wrapper {
            position: relative;
            flex: 1;
            min-height: 240px;
        }
        .chart-title {
            font-size: 14px;
            font-weight: 600;
            color: #495057;
        }
        .chart-subtitle {
            font-size: 12px;
            color: #adb5bd;
        }

        /* ── Bottom table cards ─────────────────────────────────── */
        .bottom-card {
            background: #fff;
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
        }
        .bottom-card .card-head {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f1f1;
        }
        .bottom-card .card-head h5 {
            font-size: 13px;
            font-weight: 600;
            color: #1e2a3b;
            margin: 0;
        }
        .bottom-card .student-row {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-bottom: 1px solid #f8f9fa;
            gap: 10px;
        }
        .bottom-card .student-row:last-child {
            border-bottom: none;
        }
        .bottom-card .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
            background: #e9ecef; /* Placeholder background while loading */
            transition: opacity 0.15s ease; /* Smooth loading transition */
        }
        .bottom-card .student-avatar.loading {
            opacity: 0.6;
        }
        .bottom-card .student-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e2a3b;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .bottom-card .student-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .badge-danger-soft   { background: rgba(231,76,60,.12);  color: #c0392b; }
        .badge-warning-soft  { background: rgba(255,159,67,.15); color: #d4770b; }
        .badge-primary-soft  { background: rgba(84,130,255,.12); color: #3a6bff; }
        .badge-success-soft  { background: rgba(34,182,127,.12); color: #1a9967; }

        .no-data-msg {
            padding: 28px 16px;
            text-align: center;
            color: #adb5bd;
            font-size: 13px;
        }

        /* band skill pills */
        .skill-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            margin-right: 2px;
        }
        .pill-L { background: rgba(84,130,255,.1);  color: #5482ff; }
        .pill-R { background: rgba(34,182,127,.1);  color: #22b67f; }
        .pill-W { background: rgba(255,159,67,.1);  color: #ff9f43; }
        .pill-S { background: rgba(231,76,60,.1);   color: #e74c3c; }

        .spinner-chart {
            position: absolute;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,.8);
            border-radius: 16px;
            z-index: 10;
        }
        .spinner-chart.show { display: flex; }

        /* Global page loading optimization */
        .page-loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }
        .page-loading.hidden {
            opacity: 0;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')

{{-- ── ROW 1 : Chart + Metric Boxes ────────────────────────────────────────── --}}
<div class="row align-items-stretch">

    {{-- Left: Line chart --}}
    <div class="col-lg-8 mb-20 d-flex">
        <div class="bg-white rounded-24 p-20 w-100 d-flex flex-column">
            <div class="d-flex align-items-start justify-content-between mb-16">
                <div>
                    <div class="chart-title" id="chartMetricTitle">Quiz Accuracy — % theo tuần</div>
                    <div class="chart-subtitle">So sánh My Students / All Students / KPI</div>
                </div>
            </div>
            <div class="chart-wrapper">
                <div class="spinner-chart" id="chartSpinner">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
                <canvas id="weeklyLineChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Right: Metric boxes --}}
    <div class="col-lg-4 mb-20">

        {{-- 1. My Student List (link, not chart trigger) --}}
        <a href="/panel/my-students/list" class="metric-card mc-link d-flex align-items-center gap-12">
            <div class="mc-icon bg-primary-30">
                <x-iconsax-bul-people class="icons text-primary" width="22"/>
            </div>
            <div>
                <div class="mc-label">My Student List</div>
                <div class="mc-value font-18">{{ $totalStudents }} students</div>
                <div class="mc-sub">Click to view full list &rarr;</div>
            </div>
        </a>

        {{-- 2. Quiz Accuracy --}}
        <div class="metric-card active d-flex align-items-center gap-12"
             data-metric="quiz_accuracy"
             data-title="Quiz Accuracy — % theo tuần">
            <div class="mc-icon bg-primary-30">
                <x-iconsax-bul-task-square class="icons text-primary" width="22"/>
            </div>
            <div>
                <div class="mc-label">Quiz Accuracy</div>
                <div class="mc-value">{{ $quizAccuracy }}%</div>
                <div class="mc-sub">Avg % đúng khi làm quiz</div>
            </div>
        </div>

        {{-- 3. Exercise Accuracy --}}
        <div class="metric-card d-flex align-items-center gap-12"
             data-metric="exercise_accuracy"
             data-title="Exercise Accuracy — % theo tuần">
            <div class="mc-icon bg-success-30">
                <x-iconsax-bul-book-1 class="icons text-success" width="22"/>
            </div>
            <div>
                <div class="mc-label">Exercise Accuracy</div>
                <div class="mc-value">{{ $exerciseAccuracy }}%</div>
                <div class="mc-sub">Avg % đúng khi làm exercise</div>
            </div>
        </div>

        {{-- 4. Test Band --}}
        <div class="metric-card d-flex align-items-center gap-12"
             data-metric="test_band"
             data-title="Test Band — % theo tuần (4 skills)">
            <div class="mc-icon bg-warning-30">
                <x-iconsax-bul-chart-square class="icons text-warning" width="22"/>
            </div>
            <div style="flex:1">
                <div class="mc-label">Test Band <small class="text-gray-400">(4 lines/each skill)</small></div>
                <div class="mt-4">
                    <span class="skill-pill pill-L">L {{ $testBands['listening'] }}</span>
                    <span class="skill-pill pill-R">R {{ $testBands['reading'] }}</span>
                    <span class="skill-pill pill-W">W {{ $testBands['writing'] }}</span>
                    <span class="skill-pill pill-S">S {{ $testBands['speaking'] }}</span>
                </div>
                <div class="mc-sub">Avg band/9 khi làm mock test</div>
            </div>
        </div>

        {{-- 5. Improvement Rate --}}
        <div class="metric-card d-flex align-items-center gap-12"
             data-metric="improvement_rate"
             data-title="Improvement Rate — % theo tuần">
            <div class="mc-icon" style="background:rgba(231,76,60,.1)">
                <x-iconsax-bul-trend-up class="icons" style="color:#e74c3c" width="22"/>
            </div>
            <div>
                <div class="mc-label">Improvement Rate</div>
                <div class="mc-value @if($improvementRate >= 0) text-success @else text-danger @endif">
                    @if($improvementRate >= 0)+@endif{{ $improvementRate }}%
                </div>
                <div class="mc-sub">(LTC − LTĐ) / LTĐ</div>
            </div>
        </div>

    </div>
</div>

{{-- ── ROW 2 : Three bottom tables ─────────────────────────────────────────── --}}
<div class="row">

    {{-- Table 1: Students near exam date --}}
    <div class="col-md-4 mb-20">
        <div class="bottom-card">
            <div class="card-head d-flex align-items-center justify-content-between">
                <h5>
                    <x-iconsax-lin-calendar-1 class="icons mr-1 text-warning" width="16"/>
                    Học viên cận ngày thi
                </h5>
                <span class="badge badge-warning-soft">{{ $studentsNearExam->count() }}</span>
            </div>

            @forelse($studentsNearExam as $s)
                <div class="student-row">
                    <img src="{{ !empty($s->avatar) ? (str_starts_with($s->avatar, 'http') ? $s->avatar : '/store/' . $s->avatar) : '/assets/default/img/user/avatar_default.png' }}"
                         class="student-avatar"
                         alt="{{ $s->full_name }}"
                         loading="lazy"
                         onerror="this.src='/assets/default/img/user/avatar_default.png'; this.onerror=null;"
                         onload="this.classList.remove('loading')"
                         onloadstart="this.classList.add('loading')">
                    <span class="student-name">{{ $s->full_name }}</span>
                    <span class="student-badge @if($s->days_remaining <= 7) badge-danger-soft @else badge-warning-soft @endif">
                        {{ $s->days_remaining }}d
                    </span>
                </div>
            @empty
                <div class="no-data-msg">Không có học viên nào gần ngày thi</div>
            @endforelse
        </div>
    </div>

    {{-- Table 2: Students with low accuracy --}}
    <div class="col-md-4 mb-20">
        <div class="bottom-card">
            <div class="card-head d-flex align-items-center justify-content-between">
                <h5>
                    <x-iconsax-lin-danger class="icons mr-1 text-danger" width="16"/>
                    Học viên tỷ lệ chính xác thấp
                </h5>
                <span class="badge badge-danger-soft">{{ $studentsLowAccuracy->count() }}</span>
            </div>

            @forelse($studentsLowAccuracy as $s)
                <div class="student-row">
                    <img src="{{ !empty($s->avatar) ? (str_starts_with($s->avatar, 'http') ? $s->avatar : '/store/' . $s->avatar) : '/assets/default/img/user/avatar_default.png' }}"
                         class="student-avatar"
                         alt="{{ $s->full_name }}"
                         loading="lazy"
                         onerror="this.src='/assets/default/img/user/avatar_default.png'; this.onerror=null;"
                         onload="this.classList.remove('loading')"
                         onloadstart="this.classList.add('loading')">
                    <span class="student-name">{{ $s->full_name }}</span>
                    <span class="student-badge @if($s->accuracy < 40) badge-danger-soft @else badge-warning-soft @endif">
                        {{ $s->accuracy }}%
                    </span>
                </div>
            @empty
                <div class="no-data-msg">Không có dữ liệu quiz</div>
            @endforelse
        </div>
    </div>

    {{-- Table 3: Students below aim band --}}
    <div class="col-md-4 mb-20">
        <div class="bottom-card">
            <div class="card-head d-flex align-items-center justify-content-between">
                <h5>
                    <x-iconsax-lin-trend-down class="icons mr-1 text-danger" width="16"/>
                    Học viên luyện thi thấp hơn aim band
                </h5>
                <span class="badge badge-danger-soft">{{ $studentsBelowAimBand->count() }}</span>
            </div>

            @forelse($studentsBelowAimBand as $s)
                <div class="student-row">
                    <img src="{{ !empty($s->avatar) ? (str_starts_with($s->avatar, 'http') ? $s->avatar : '/store/' . $s->avatar) : '/assets/default/img/user/avatar_default.png' }}"
                         class="student-avatar"
                         alt="{{ $s->full_name }}"
                         loading="lazy"
                         onerror="this.src='/assets/default/img/user/avatar_default.png'; this.onerror=null;"
                         onload="this.classList.remove('loading')"
                         onloadstart="this.classList.add('loading')">
                    <div style="flex:1; min-width:0;">
                        <div class="student-name">{{ $s->full_name }}</div>
                        <div style="font-size:11px;color:#adb5bd;">
                            Aim: {{ $s->aim_band }} &nbsp;|&nbsp; Actual: {{ $s->actual_band }}
                        </div>
                    </div>
                    <span class="student-badge badge-danger-soft">-{{ $s->gap }}</span>
                </div>
            @empty
                <div class="no-data-msg">Tất cả học viên đang đạt aim band 🎉</div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts_bottom')
<script src="/assets/default/vendors/chartjs/chart.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Initial chart data from PHP ──────────────────────────────────────────
    const initialData = {!! $chartJson !!};

    // ── Chart.js colour helpers ──────────────────────────────────────────────
    const ctx = document.getElementById('weeklyLineChart').getContext('2d');
    let chart = null;

    function buildChart(data) {
        const datasets = data.datasets.map(function (ds) {
            return {
                label:           ds.label,
                data:            ds.data,
                borderColor:     ds.borderColor,
                backgroundColor: ds.backgroundColor,
                tension:         typeof ds.tension !== 'undefined' ? ds.tension : 0.4,
                fill:            typeof ds.fill    !== 'undefined' ? ds.fill    : false,
                borderDash:      ds.borderDash || [],
                borderWidth:     2,
                pointRadius:     3,
                pointHoverRadius: 5,
            };
        });

        if (chart) {
            chart.data.labels   = data.labels;
            chart.data.datasets = datasets;
            chart.options.scales.y.title.text = data.yLabel || '%';
            chart.update();
        } else {
            chart = new Chart(ctx, {
                type: 'line',
                data: { labels: data.labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, font: { size: 12 } }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (ctx) {
                                    return ' ' + ctx.dataset.label + ': ' + ctx.formattedValue + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid:  { color: 'rgba(0,0,0,.05)' },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            min: 0,
                            max: 100,
                            grid:  { color: 'rgba(0,0,0,.05)' },
                            ticks: {
                                font: { size: 11 },
                                callback: function (v) { return v + '%'; }
                            },
                            title: {
                                display: true,
                                text: data.yLabel || '%',
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });
        }
    }

    // ── Build initial chart ──────────────────────────────────────────────────
    buildChart(initialData);

    // ── Metric card click handler ────────────────────────────────────────────
    const metricCards = document.querySelectorAll('.metric-card[data-metric]');
    const spinner     = document.getElementById('chartSpinner');
    const chartTitle  = document.getElementById('chartMetricTitle');

    metricCards.forEach(function (card) {
        card.addEventListener('click', function () {
            const metric = this.dataset.metric;
            const title  = this.dataset.title || metric;

            // Update active state
            metricCards.forEach(function (c) { c.classList.remove('active'); });
            this.classList.add('active');

            // Update title
            chartTitle.textContent = title;

            // Show spinner
            spinner.classList.add('show');

            // Fetch data (30-second timeout so spinner never hangs forever)
            const controller = new AbortController();
            const fetchTimeout = setTimeout(() => controller.abort(), 30000);

            fetch('/panel/my-students/chart-data?metric=' + metric + '&weeks=8', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: controller.signal
            })
            .then(function (res) {
                clearTimeout(fetchTimeout);
                return res.json();
            })
            .then(function (data) {
                buildChart(data);
            })
            .catch(function (err) {
                clearTimeout(fetchTimeout);
                if (err.name !== 'AbortError') {
                    console.error('Chart data fetch error:', err);
                }
            })
            .finally(function () {
                spinner.classList.remove('show');
            });
        });
    });

    // ── Page load completion handler ─────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        // Hide any lingering loading indicators
        const pageLoaders = document.querySelectorAll('.spinner-chart, .page-loading, [id*="loader"], [class*="loading"]');
        pageLoaders.forEach(function(loader) {
            if (loader.id !== 'chartSpinner') { // Keep chart spinner for click events
                loader.style.display = 'none';
            }
        });
        
        // Ensure chart spinner is hidden by default
        if (spinner) {
            spinner.classList.remove('show');
        }
        
        // Force refresh of any cached content that might be stuck
        setTimeout(function() {
            // Clear any residual loading states after 2 seconds
            const allSpinners = document.querySelectorAll('[class*="spinner"], [class*="loading"]');
            allSpinners.forEach(function(s) {
                if (s.id !== 'chartSpinner') {
                    s.style.display = 'none';
                }
            });
        }, 2000);
    });

})();
</script>
@endpush
