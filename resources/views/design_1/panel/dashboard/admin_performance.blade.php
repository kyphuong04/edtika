@extends('design_1.panel.layouts.panel')

@php
    $ap = $adminPerformance ?? [];
@endphp

@section('content')
    <div class="row gx-16">
        <div class="col-12 col-xl-8">
            <div class="ap-box ap-box-top mb-12">
                <div class="d-flex align-items-center justify-content-between mb-8">
                    <h3 class="font-18 font-weight-bold text-dark mb-0">Admin Performance</h3>
                    <span class="font-11 text-gray-500">Month KPI</span>
                </div>

                <div class="progress ap-progress-full">
                    <div class="progress-bar ap-progress-dark" role="progressbar" style="width: {{ $ap['kpiMonthlyProgress'] ?? 0 }}%"></div>
                </div>
            </div>

            <div class="row g-12 mb-12">
                <div class="col-6 col-md-3">
                    <div class="ap-box ap-metric-box">
                        <div class="ap-metric-title">Avg deal value</div>
                        <div class="ap-metric-value">{{ handlePrice($ap['avgDealValue'] ?? 0) }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="ap-box ap-metric-box">
                        <div class="ap-metric-title">Avg call per day</div>
                        <div class="ap-metric-value">{{ $ap['avgCallsPerDay'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="ap-box ap-metric-box">
                        <div class="ap-metric-title">Avg Drop rates</div>
                        <div class="ap-metric-value">{{ $ap['avgDropRate'] ?? 0 }}%</div>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="ap-box ap-metric-box">
                        <div class="ap-metric-title">Avg closing time</div>
                        <div class="ap-metric-value">{{ $ap['avgClosingTimeDays'] ?? 0 }} d</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="ap-box ap-profile-box mb-12">
                <div class="ap-profile-avatar-wrap">
                    <img src="{{ $authUser->getAvatar(90) }}" alt="" class="ap-profile-avatar">
                </div>

                <h4 class="font-16 font-weight-bold text-dark mt-10 mb-0">{{ strtoupper($authUser->full_name) }}</h4>
                <div class="font-13 text-dark mt-8">THÀNH TÍCH CHỐT SALE: {{ $ap['profileSaleAchievement'] ?? 0 }}%</div>

                <div class="progress ap-progress mt-10 mx-auto">
                    <div class="progress-bar ap-progress-dark" role="progressbar" style="width: {{ $ap['profileSaleAchievement'] ?? 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="ap-box ap-chart-box mb-12">
                <div class="d-flex align-items-center justify-content-between mb-12 flex-wrap gap-8">
                    <h5 class="font-20 font-weight-bold text-dark mb-0">Sale Chart</h5>

                    <div class="ap-period-switch d-flex align-items-center">
                        <button type="button" class="ap-period-btn active" data-period="weekly">Weekly</button>
                        <button type="button" class="ap-period-btn" data-period="monthly">Monthly</button>
                        <button type="button" class="ap-period-btn" data-period="yearly">Yearly</button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-16 mb-10 font-11 text-gray-500">
                    <span class="d-inline-flex align-items-center">
                        <span class="ap-dot ap-dot-personal mr-6"></span> Admin
                    </span>
                    <span class="d-inline-flex align-items-center">
                        <span class="ap-dot ap-dot-team mr-6"></span> Team
                    </span>
                </div>

                <div id="adminPerformanceChart"></div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="ap-box ap-lead-box mb-12">
                <h5 class="font-32 font-weight-bold text-dark text-center mb-16">My Lead</h5>

                @forelse(($ap['myLeads'] ?? []) as $lead)
                    <div class="ap-list-item mb-10">
                        <div class="d-flex align-items-center overflow-hidden">
                            <div class="size-36 rounded-circle overflow-hidden bg-gray-300 mr-10">
                                <img src="{{ $lead['user']->getAvatar(36) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-13 font-weight-bold text-dark text-ellipsis">{{ $lead['user']->full_name }}</div>
                                <div class="font-11 text-gray-500 text-ellipsis">{{ $lead['user']->email }}</div>
                            </div>
                        </div>
                        <div class="font-10 text-gray-500 ml-8">{{ $lead['calls'] }} calls</div>
                    </div>
                @empty
                    <div class="ap-empty">No lead data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="ap-box ap-bottom-box mb-12">
                <h5 class="font-34 font-weight-bold text-dark text-center mb-16">My Sale log</h5>

                @forelse(($ap['mySaleLogs'] ?? []) as $log)
                    <div class="ap-list-item mb-10">
                        <div class="d-flex align-items-start overflow-hidden">
                            <div class="size-34 rounded-circle overflow-hidden bg-gray-300 mr-10 mt-2">
                                <img src="{{ optional($log->user)->getAvatar(34) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-13 font-weight-bold text-dark text-ellipsis">{{ optional($log->user)->full_name ?? 'Lead' }}</div>
                                <div class="font-11 text-gray-500 mt-2">{{ truncate($log->description, 90) }}</div>
                            </div>
                        </div>
                        <div class="font-10 text-gray-500 ml-8 text-nowrap">{{ dateTimeFormat($log->created_at, 'j M') }}</div>
                    </div>
                @empty
                    <div class="ap-empty">No sale logs yet.</div>
                @endforelse
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="ap-box ap-bottom-box mb-12">
                <h5 class="font-34 font-weight-bold text-dark text-center mb-16">New Leads</h5>

                @forelse(($ap['newLeads'] ?? []) as $lead)
                    <div class="ap-list-item mb-10">
                        <div class="d-flex align-items-center overflow-hidden">
                            <div class="size-34 rounded-circle overflow-hidden bg-gray-300 mr-10">
                                <img src="{{ optional($lead->user)->getAvatar(34) }}" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="overflow-hidden">
                                <div class="font-13 font-weight-bold text-dark text-ellipsis">{{ optional($lead->user)->full_name ?? 'Unknown lead' }}</div>
                                <div class="font-11 text-gray-500 text-ellipsis">{{ optional($lead->user)->email ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="font-10 text-gray-500 ml-8 text-nowrap">{{ dateTimeFormat($lead->created_at, 'j M') }}</div>
                    </div>
                @empty
                    <div class="ap-empty">No new leads.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('styles_top')
    <style>
        .ap-box {
            background: #ffffff;
            border: 1px solid #edf0f5;
            box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
            border-radius: 18px;
            padding: 16px;
        }

        .ap-box-top {
            min-height: 104px;
        }

        .ap-progress {
            height: 8px;
            width: 180px;
            border-radius: 20px;
            background: #e5e7eb;
        }

        .ap-progress-full {
            height: 8px;
            border-radius: 999px;
            background: #cfcfd1;
        }

        .ap-progress-dark {
            background: #545454;
        }

        .ap-metric-box {
            min-height: 108px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 14px;
            text-align: left;
        }

        .ap-metric-title {
            font-size: 15px;
            color: #1f2937;
            line-height: 1.2;
        }

        .ap-metric-value {
            margin-top: 8px;
            font-size: 22px;
            font-weight: 700;
            color: #111111;
        }

        .ap-metric-card {
            border: 1px solid #e5e7eb;
        }

        .ap-profile-box {
            min-height: 224px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .ap-profile-avatar-wrap {
            width: 90px;
            height: 90px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #777;
            background: #f5f5f5;
        }

        .ap-profile-avatar {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .ap-chart-box {
            min-height: 468px;
        }

        .ap-lead-box {
            min-height: 468px;
        }

        .ap-bottom-box {
            min-height: 300px;
        }

        .ap-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .ap-dot-personal {
            background: #ffffff;
            border: 1px solid #9ca3af;
        }

        .ap-dot-team {
            background: #111111;
        }

        .ap-period-switch {
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
        }

        .ap-period-btn {
            border: 0;
            background: #ffffff;
            color: #6b7280;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1;
        }

        .ap-period-btn.active {
            background: #111111;
            color: #ffffff;
        }

        .ap-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #dcdcdc;
            border-radius: 12px;
            padding: 10px;
        }

        .ap-empty {
            text-align: center;
            padding: 18px;
            font-size: 12px;
            color: #6b7280;
            background: #dcdcdc;
            border-radius: 12px;
        }

        @media (max-width: 1199px) {
            .ap-chart-box,
            .ap-lead-box,
            .ap-bottom-box {
                min-height: auto;
            }
        }
    </style>
@endpush

@push('scripts_bottom')
    <script>
        (function () {
            "use strict";

            var periodSeries = @json($ap['periodSeries'] ?? []);
            var chartEl = document.getElementById('adminPerformanceChart');
            var periodButtons = document.querySelectorAll('.ap-period-btn');
            var chart;

            function buildOptions(period) {
                var payload = periodSeries[period] || { labels: [], personal: [], team: [] };

                return {
                    series: [
                        { name: 'My Performance', data: payload.personal || [] },
                        { name: 'Team', data: payload.team || [] }
                    ],
                    chart: {
                        type: 'bar',
                        height: 260,
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '58%',
                            borderRadius: 4,
                            grouped: true
                        }
                    },
                    colors: ['#ffffff', '#111111'],
                    stroke: { show: true, width: 1, colors: ['#9ca3af'] },
                    dataLabels: { enabled: false },
                    xaxis: {
                        categories: payload.labels || [],
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { fontSize: '11px', colors: '#9ca3af' } }
                    },
                    yaxis: {
                        min: 0,
                        tickAmount: 4,
                        labels: {
                            formatter: function (value) {
                                return Math.round(value);
                            },
                            style: { fontSize: '11px', colors: '#9ca3af' }
                        }
                    },
                    grid: {
                        borderColor: '#f3f4f6',
                        strokeDashArray: 4
                    },
                    legend: { show: false },
                    tooltip: { theme: 'light' }
                };
            }

            function setActiveButton(period) {
                periodButtons.forEach(function (button) {
                    button.classList.toggle('active', button.getAttribute('data-period') === period);
                });
            }

            if (chartEl && typeof ApexCharts !== 'undefined') {
                chart = new ApexCharts(chartEl, buildOptions('weekly'));
                chart.render();

                periodButtons.forEach(function (button) {
                    button.addEventListener('click', function () {
                        var period = button.getAttribute('data-period');
                        setActiveButton(period);
                        chart.updateOptions(buildOptions(period));
                    });
                });
            }
        }());
    </script>
@endpush
