@extends('admin.layouts.app')

@push('libraries_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
@endpush

@section('content')
    @php
        $dashboard = $adminPerformanceDashboard ?? [];
        $metrics = $dashboard['metrics'] ?? [];
        $chartData = $dashboard['chartData'] ?? [
            'week' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
            'month' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
            'year' => ['labels' => [], 'revenue' => [], 'revenueKpi' => [], 'sales' => [], 'salesKpi' => []],
        ];
        $adminList = collect($dashboard['adminList'] ?? []);
        $funnel = $dashboard['funnel'] ?? [];
        $saleLogs = collect($dashboard['saleLogs'] ?? []);
        $topSellers = collect($dashboard['topSellers'] ?? []);
    @endphp

    <section class="section manager-admin-performance-page">
        <div class="row mb-14">
            <div class="col-12 col-lg-8 mb-12 mb-lg-0">
                <div class="map-card map-top-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-10">
                        <div class="map-head-wrap">
                            <div class="map-top-title">Total Admin Performance</div>
                            <div class="map-top-subtitle">Month KPI</div>
                        </div>
                        <div class="map-kpi-icon bg-primary-40 text-primary">
                            <x-iconsax-bul-chart-square class="icons" width="17px" height="17px"/>
                        </div>
                    </div>

                    <div class="progress map-kpi-progress mb-8">
                        <div class="progress-bar map-kpi-progress-bar" role="progressbar" style="width: {{ $dashboard['monthlyKpiProgress'] ?? 0 }}%"></div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between font-12 text-gray-500">
                        <span>Actual: {{ handlePrice($dashboard['monthlyRevenue'] ?? 0) }}</span>
                        <span>Target: {{ handlePrice($dashboard['monthlyKpiTarget'] ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <button type="button" class="map-card map-top-card map-admin-list-btn w-100 h-100" data-toggle="modal" data-target="#managerAdminListModal">
                    <span>Admin list</span>
                </button>
            </div>
        </div>

        <div class="row mb-14">
            <div class="col-12 col-md-6 col-lg mb-10 mb-lg-0">
                <div class="map-card map-metric-card h-100">
                    <div class="map-metric-head">
                        <div class="map-metric-title">Total Revenue</div>
                        <div class="map-metric-icon bg-success-30 text-success">
                            <x-iconsax-bul-wallet-money class="icons" width="16px" height="16px"/>
                        </div>
                    </div>
                    <div class="map-metric-value">{{ handlePrice($metrics['totalRevenue'] ?? 0) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg mb-10 mb-lg-0">
                <div class="map-card map-metric-card h-100">
                    <div class="map-metric-head">
                        <div class="map-metric-title">Avg deal value</div>
                        <div class="map-metric-icon bg-primary-40 text-primary">
                            <x-iconsax-bul-chart-2 class="icons" width="16px" height="16px"/>
                        </div>
                    </div>
                    <div class="map-metric-value">{{ handlePrice($metrics['avgDealValue'] ?? 0) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg mb-10 mb-lg-0">
                <div class="map-card map-metric-card h-100">
                    <div class="map-metric-head">
                        <div class="map-metric-title">Avg call per day</div>
                        <div class="map-metric-icon bg-warning-30 text-warning">
                            <x-iconsax-bul-call-calling class="icons" width="16px" height="16px"/>
                        </div>
                    </div>
                    <div class="map-metric-value">{{ number_format($metrics['avgCallsPerDay'] ?? 0, 1) }}</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg mb-10 mb-lg-0">
                <div class="map-card map-metric-card h-100">
                    <div class="map-metric-head">
                        <div class="map-metric-title">Avg Drop rates</div>
                        <div class="map-metric-icon bg-danger-30 text-danger">
                            <x-iconsax-bul-close-circle class="icons" width="16px" height="16px"/>
                        </div>
                    </div>
                    <div class="map-metric-value">{{ number_format($metrics['avgDropRate'] ?? 0, 1) }}%</div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg">
                <div class="map-card map-metric-card h-100">
                    <div class="map-metric-head">
                        <div class="map-metric-title">Avg closing time</div>
                        <div class="map-metric-icon bg-gray-200 text-gray-700">
                            <x-iconsax-bul-clock class="icons" width="16px" height="16px"/>
                        </div>
                    </div>
                    <div class="map-metric-value">{{ number_format($metrics['avgClosingTimeDays'] ?? 0, 1) }}d</div>
                </div>
            </div>
        </div>

        <div class="row mb-14">
            <div class="col-12 col-xl-8 mb-12 mb-xl-0">
                <div class="map-card map-chart-card h-100">
                    <div class="d-flex align-items-center justify-content-between mb-10 flex-wrap gap-8">
                        <div class="map-chart-heading">Revenue + KPI / Sale + KPI</div>

                        <div class="btn-group map-period-switch" role="group" aria-label="Admin performance chart range">
                            <button type="button" class="btn btn-light active" data-period="week">Week</button>
                            <button type="button" class="btn btn-light" data-period="month">Month</button>
                            <button type="button" class="btn btn-light" data-period="year">Year</button>
                        </div>
                    </div>

                    <div class="map-chart-legend mb-8">
                        <span><i class="dot dot-revenue"></i>Revenue</span>
                        <span><i class="dot dot-revenue-kpi"></i>Revenue KPI</span>
                        <span><i class="dot dot-sales"></i>Sale</span>
                        <span><i class="dot dot-sales-kpi"></i>Sale KPI</span>
                    </div>

                    <div class="map-chart-wrap">
                        <canvas id="managerAdminPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="map-card map-funnel-card h-100">
                    <div class="map-funnel-shell">
                        <div class="map-funnel-stage map-funnel-stage-1">
                            <div class="map-funnel-label">Lead mới</div>
                            <div class="map-funnel-count">{{ number_format($funnel['newLeads'] ?? 0) }}</div>
                        </div>

                        <div class="map-funnel-stage map-funnel-stage-2">
                            <div class="map-funnel-label">Qualified</div>
                            <div class="map-funnel-count">{{ number_format($funnel['qualified'] ?? 0) }}</div>
                        </div>

                        <div class="map-funnel-stage map-funnel-stage-3">
                            <div class="map-funnel-label">Demo</div>
                            <div class="map-funnel-count">{{ number_format($funnel['demo'] ?? 0) }}</div>
                        </div>

                        <div class="map-funnel-stage map-funnel-stage-4">
                            <div class="map-funnel-label">Won / Closed</div>
                            <div class="map-funnel-count">{{ number_format($funnel['wonClosed'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-xl-8 mb-12 mb-xl-0">
                <div class="map-card map-list-card h-100">
                    <h4 class="map-list-title">Sale log</h4>

                    <div class="map-list-scroll">
                        @forelse($saleLogs as $log)
                            <div class="map-log-row">
                                <div class="map-log-main">
                                    <strong>{{ optional($log->meeting->creator)->full_name ?? 'Admin' }}</strong>
                                    <span>{{ optional($log->user)->full_name ?? 'Lead' }}</span>
                                    <p>{{ truncate($log->description, 115) }}</p>
                                </div>
                                <div class="map-log-date">{{ dateTimeFormat($log->created_at, 'd/m/Y') }}</div>
                            </div>
                        @empty
                            <div class="map-empty">Chưa có dữ liệu nhật ký sale.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="map-card map-list-card h-100">
                    <h4 class="map-list-title">Top seller</h4>

                    <div class="map-list-scroll">
                        @forelse($topSellers as $seller)
                            <div class="map-top-row">
                                <div class="d-flex align-items-center overflow-hidden">
                                    <div class="map-top-avatar rounded-circle overflow-hidden bg-gray-200 mr-8">
                                        <img src="{{ $seller['avatar'] ?? '' }}" alt="" class="img-cover rounded-circle">
                                    </div>
                                    <div class="overflow-hidden">
                                        <div class="font-13 font-weight-bold text-dark text-ellipsis">{{ $seller['name'] ?? '-' }}</div>
                                        <div class="font-11 text-gray-500 text-ellipsis">Revenue: {{ handlePrice($seller['revenue'] ?? 0) }}</div>
                                    </div>
                                </div>
                                <div class="font-12 font-weight-bold text-dark">{{ number_format($seller['closeRate'] ?? 0, 1) }}%</div>
                            </div>
                        @empty
                            <div class="map-empty">Chưa có dữ liệu top seller.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="managerAdminListModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-16">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-16 font-weight-bold">Danh sách Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pt-12">
                    @forelse($adminList as $admin)
                        <div class="map-admin-row">
                            <div class="d-flex align-items-center overflow-hidden">
                                <div class="map-admin-avatar rounded-circle overflow-hidden bg-gray-200 mr-8">
                                    <img src="{{ $admin['avatar'] ?? '' }}" alt="" class="img-cover rounded-circle">
                                </div>
                                <div class="overflow-hidden">
                                    <div class="font-13 font-weight-bold text-dark text-ellipsis">{{ $admin['name'] ?? '-' }}</div>
                                    <div class="font-11 text-gray-500 text-ellipsis">{{ $admin['email'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="font-12 font-weight-bold text-dark">{{ number_format($admin['closeRate'] ?? 0, 1) }}%</div>
                        </div>
                    @empty
                        <div class="map-empty">Không có admin.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles_top')
    <style>
        .manager-admin-performance-page {
            padding-bottom: 14px;
        }

        .map-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
        }

        .map-top-card {
            min-height: 95px;
        }

        .map-head-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .map-top-title {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            line-height: 1.2;
        }

        .map-top-subtitle {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.2;
            font-weight: 600;
        }

        .map-kpi-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .map-kpi-progress {
            height: 8px;
            border-radius: 99px;
            background: #d1d5db;
            overflow: hidden;
        }

        .map-kpi-progress-bar {
            background: #4b5563;
        }

        .map-admin-list-btn {
            border: 0;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            transition: all 0.2s ease;
        }

        .map-admin-list-btn:hover {
            box-shadow: 0 8px 18px rgba(17, 24, 39, 0.08);
        }

        .map-metric-card {
            min-height: 95px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .map-metric-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .map-metric-icon {
            width: 28px;
            height: 28px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .map-metric-title {
            color: #1f2937;
            font-size: 16px;
            line-height: 1.25;
            font-weight: 600;
        }

        .map-metric-value {
            margin-top: 6px;
            color: #111827;
            font-size: 21px;
            font-weight: 700;
            line-height: 1;
        }

        .map-chart-card {
            min-height: 420px;
        }

        .map-chart-heading {
            color: #111827;
            font-size: 20px;
            font-weight: 700;
        }

        .map-period-switch .btn {
            border-color: #d1d5db;
            color: #6b7280;
            font-weight: 600;
            font-size: 12px;
            min-width: 72px;
        }

        .map-period-switch .btn.active {
            background: #111827;
            border-color: #111827;
            color: #ffffff;
        }

        .map-chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            font-size: 12px;
            color: #6b7280;
        }

        .map-chart-legend .dot {
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-right: 6px;
        }

        .dot-revenue {
            background: #111827;
        }

        .dot-revenue-kpi {
            background: #6b7280;
        }

        .dot-sales {
            background: #9ca3af;
        }

        .dot-sales-kpi {
            background: #d1d5db;
            border: 1px solid #9ca3af;
        }

        .map-chart-wrap {
            position: relative;
            height: 320px;
        }

        .map-funnel-card {
            min-height: 420px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 0;
            padding: 8px 0;
        }

        .map-funnel-shell {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            aspect-ratio: 1 / 1.22;
            clip-path: polygon(3% 0, 97% 0, 50% 100%);
            background: #ffffff;
            position: relative;
            overflow: hidden;
            filter: drop-shadow(0 1px 2px rgba(17, 24, 39, 0.06));
        }

        .map-funnel-shell::before {
            content: '';
            position: absolute;
            inset: 0;
            clip-path: polygon(3% 0, 97% 0, 50% 100%);
            border: 1px solid rgba(17, 24, 39, 0.12);
            pointer-events: none;
        }

        .map-funnel-stage {
            position: absolute;
            left: 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #111827;
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .map-funnel-label {
            font-size: 16px;
            font-weight: 700;
            line-height: 1.2;
        }

        .map-funnel-count {
            margin-top: 5px;
            font-size: 14px;
            font-weight: 700;
            color: #374151;
        }

        .map-funnel-stage-1 {
            top: 0;
            height: 24%;
        }

        .map-funnel-stage-2 {
            top: 24%;
            height: 24%;
        }

        .map-funnel-stage-3 {
            top: 48%;
            height: 24%;
        }

        .map-funnel-stage-4 {
            top: 72%;
            height: 18%;
            justify-content: flex-start;
            padding-top: 8px;
        }

        .map-funnel-stage-4 .map-funnel-label {
            font-size: 13px;
            line-height: 1.1;
        }

        .map-funnel-stage-4 .map-funnel-count {
            margin-top: 3px;
            font-size: 13px;
        }

        .map-funnel-shell::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 14px;
            height: 10%;
            background: transparent;
            pointer-events: none;
        }

        .map-funnel-stage-2::before,
        .map-funnel-stage-3::before,
        .map-funnel-stage-4::before {
            content: '';
            position: absolute;
            top: 0;
            border-top: 2px solid #6b7280;
        }

        .map-funnel-stage-2::before {
            left: 9%;
            right: 9%;
        }

        .map-funnel-stage-3::before {
            left: 18%;
            right: 18%;
        }

        .map-funnel-stage-4::before {
            left: 31%;
            right: 31%;
        }

        .map-list-card {
            min-height: 265px;
        }

        .map-list-title {
            color: #111827;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .map-list-scroll {
            max-height: 290px;
            overflow-y: auto;
            padding-right: 2px;
        }

        .map-log-row,
        .map-top-row,
        .map-admin-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 8px 10px;
            background: #f8fafc;
            margin-bottom: 9px;
        }

        .map-top-avatar {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
        }

        .map-admin-avatar {
            width: 26px;
            height: 26px;
            flex-shrink: 0;
        }

        .map-admin-row {
            padding: 7px 10px;
            margin-bottom: 7px;
        }

        .map-admin-row .font-13 {
            font-size: 12px !important;
        }

        .map-admin-row .font-11 {
            font-size: 10px !important;
        }

        .map-admin-row .font-12 {
            font-size: 11px !important;
        }

        .map-log-main {
            display: flex;
            flex-direction: column;
            gap: 2px;
            overflow: hidden;
        }

        .map-log-main strong {
            font-size: 13px;
            color: #111827;
            line-height: 1.2;
        }

        .map-log-main span {
            font-size: 12px;
            color: #6b7280;
        }

        .map-log-main p {
            margin: 2px 0 0;
            font-size: 12px;
            color: #374151;
            line-height: 1.3;
        }

        .map-log-date {
            font-size: 11px;
            color: #6b7280;
            white-space: nowrap;
        }

        .map-empty {
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border: 1px dashed #d1d5db;
            border-radius: 12px;
            padding: 16px;
            background: #f9fafb;
        }

        @media (max-width: 1199px) {
            .map-top-title,
            .map-admin-list-btn,
            .map-list-title {
                font-size: 21px;
            }

            .map-metric-title {
                font-size: 15px;
            }

            .map-metric-value {
                font-size: 19px;
            }

            .map-funnel-part {
                font-size: 16px;
            }

            .map-funnel-label {
                font-size: 15px;
            }

            .map-funnel-count {
                font-size: 13px;
            }

            .map-funnel-shell {
                max-width: 350px;
            }
        }

        @media (max-width: 767px) {
            .map-top-title,
            .map-admin-list-btn,
            .map-list-title {
                font-size: 19px;
            }

            .map-metric-title {
                font-size: 14px;
            }

            .map-metric-value {
                font-size: 17px;
            }

            .map-chart-wrap {
                height: 280px;
            }

            .map-funnel-label {
                font-size: 14px;
            }

            .map-funnel-count {
                font-size: 12px;
            }

            .map-funnel-shell {
                max-width: 300px;
            }

            .map-funnel-stage-4 {
                top: 71%;
                height: 19%;
            }
        }
    </style>
@endpush

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script>
        (function () {
            "use strict";

            var chartData = @json($chartData);
            var activePeriod = 'week';
            var chartCanvas = document.getElementById('managerAdminPerformanceChart');
            var switchButtons = document.querySelectorAll('.map-period-switch .btn');

            if (!chartCanvas) {
                return;
            }

            function buildPayload(period) {
                var source = chartData[period] || {labels: [], revenue: [], revenueKpi: [], sales: [], salesKpi: []};

                return {
                    labels: source.labels || [],
                    datasets: [
                        {
                            label: 'Revenue',
                            data: source.revenue || [],
                            backgroundColor: '#111827',
                            borderRadius: 4,
                            barThickness: 12
                        },
                        {
                            label: 'Revenue KPI',
                            data: source.revenueKpi || [],
                            backgroundColor: '#6b7280',
                            borderRadius: 4,
                            barThickness: 12
                        },
                        {
                            label: 'Sale',
                            data: source.sales || [],
                            backgroundColor: '#9ca3af',
                            borderRadius: 4,
                            barThickness: 12
                        },
                        {
                            label: 'Sale KPI',
                            data: source.salesKpi || [],
                            backgroundColor: '#d1d5db',
                            borderColor: '#9ca3af',
                            borderWidth: 1,
                            borderRadius: 4,
                            barThickness: 12
                        }
                    ]
                };
            }

            var chart = new Chart(chartCanvas, {
                type: 'bar',
                data: buildPayload(activePeriod),
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6b7280', maxRotation: 0, autoSkip: true }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#eef2f7' },
                            ticks: { color: '#6b7280' }
                        }
                    }
                }
            });

            switchButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var nextPeriod = button.getAttribute('data-period');
                    if (!nextPeriod || nextPeriod === activePeriod) {
                        return;
                    }

                    activePeriod = nextPeriod;
                    switchButtons.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    button.classList.add('active');

                    chart.data = buildPayload(activePeriod);
                    chart.update();
                });
            });
        })();
    </script>
@endpush
