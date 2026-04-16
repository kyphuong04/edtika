@extends('admin.layouts.app')

@push('styles_top')
    <style>
        .org-admin-dashboard {
            --box-bg: #ececec;
            --box-border: #dedede;
            --text-main: #1f1f1f;
            --text-sub: #666;
            --radius-lg: 16px;
            --radius-md: 12px;
        }

        .org-admin-dashboard .dashboard-box {
            background: var(--box-bg);
            border: 1px solid var(--box-border);
            border-radius: var(--radius-lg);
        }

        .org-admin-dashboard .metric-box {
            min-height: 84px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .org-admin-dashboard .metric-title {
            color: var(--text-main);
            font-size: 13px;
            line-height: 1.25;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .org-admin-dashboard .metric-value {
            color: var(--text-main);
            font-size: 26px;
            line-height: 1;
            font-weight: 700;
        }

        .org-admin-dashboard .chart-box {
            padding: 14px 14px 8px;
            min-height: 330px;
        }

        .org-admin-dashboard .chart-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .org-admin-dashboard .chart-legend {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 6px;
            font-size: 12px;
            color: var(--text-sub);
        }

        .org-admin-dashboard .legend-dot {
            width: 10px;
            height: 10px;
            display: inline-block;
            margin-right: 5px;
            border-radius: 2px;
            vertical-align: -1px;
            border: 1px solid #111;
        }

        .org-admin-dashboard .legend-white { background: #fff; }
        .org-admin-dashboard .legend-black { background: #000; }

        .org-admin-dashboard .kpi-row-box {
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px 12px;
        }

        .org-admin-dashboard .kpi-row-title {
            font-size: 17px;
            color: var(--text-main);
            font-weight: 700;
            line-height: 1;
        }

        .org-admin-dashboard .kpi-row-sub {
            margin-top: 4px;
            font-size: 12px;
            color: var(--text-sub);
            line-height: 1.2;
        }

        .org-admin-dashboard .list-box {
            padding: 14px;
            min-height: 330px;
        }

        .org-admin-dashboard .list-title {
            text-align: center;
            font-size: 20px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
            margin-bottom: 14px;
        }

        .org-admin-dashboard .wire-list-item {
            background: #f7f7f7;
            border: 1px solid #8c8c8c;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .org-admin-dashboard .wire-list-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #bcbcbc;
            flex-shrink: 0;
        }

        .org-admin-dashboard .wire-list-body {
            min-width: 0;
            flex: 1;
        }

        .org-admin-dashboard .wire-list-name {
            color: var(--text-main);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .org-admin-dashboard .wire-list-meta {
            color: var(--text-sub);
            font-size: 12px;
            line-height: 1.2;
        }

        .org-admin-dashboard .wire-list-check {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .org-admin-dashboard .profile-box {
            padding: 16px 14px;
            text-align: center;
            min-height: 228px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .org-admin-dashboard .profile-avatar {
            width: 74px;
            height: 74px;
            border-radius: 50%;
            border: 1px solid #7d7d7d;
            object-fit: cover;
            margin: 0 auto 10px;
            background: #fff;
        }

        .org-admin-dashboard .profile-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
            text-transform: uppercase;
            line-height: 1.15;
        }

        .org-admin-dashboard .profile-rate {
            color: var(--text-main);
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .org-admin-dashboard .profile-progress {
            width: 84%;
            height: 6px;
            border-radius: 99px;
            background: #b9b9b9;
            margin: 0 auto;
            overflow: hidden;
        }

        .org-admin-dashboard .profile-progress > span {
            display: block;
            height: 100%;
            background: #6d6d6d;
            border-radius: 99px;
        }

        .org-admin-dashboard .side-kpi-box {
            min-height: 78px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px;
        }

        .org-admin-dashboard .side-kpi-title {
            color: var(--text-main);
            font-size: 20px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }

        .org-admin-dashboard .side-kpi-label {
            color: var(--text-main);
            font-size: 13px;
            line-height: 1.2;
            max-width: 95%;
        }

        .org-admin-dashboard .top-seller-box {
            padding: 14px;
            min-height: 330px;
        }

        .org-admin-dashboard .top-seller-title {
            text-align: center;
            font-size: 31px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 12px;
            line-height: 1;
        }

        .org-admin-dashboard .top-seller-item {
            background: #f3f3f3;
            border: 1px solid #7f7f7f;
            border-radius: 10px;
            min-height: 40px;
            padding: 9px 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .org-admin-dashboard .top-seller-name {
            color: var(--text-main);
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .org-admin-dashboard .top-seller-rate {
            color: var(--text-main);
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .org-admin-dashboard .empty-state {
            border: 1px dashed #b8b8b8;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
            background: #f6f6f6;
        }

        @media (max-width: 1199px) {
            .org-admin-dashboard .profile-name,
            .org-admin-dashboard .top-seller-title {
                font-size: 24px;
            }
        }

        @media (max-width: 991px) {
            .org-admin-dashboard .list-box,
            .org-admin-dashboard .chart-box,
            .org-admin-dashboard .top-seller-box {
                min-height: auto;
            }

            .org-admin-dashboard .metric-value {
                font-size: 22px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $dashboard = $organizationMarketingDashboard ?? [];

        $marketingLeads = $dashboard['marketingLeads'] ?? 0;
        $leadsConsulted = $dashboard['leadsConsulted'] ?? 0;
        $leadsPaid = $dashboard['leadsPaid'] ?? 0;
        $leadsRejected = $dashboard['leadsRejected'] ?? 0;

        $rejectedLeadsList = $dashboard['rejectedLeadsList'] ?? collect();

        $weeklyLabels = $dashboard['weeklyLabels'] ?? [];
        $weeklyPersonal = $dashboard['weeklyPersonal'] ?? [];
        $weeklyTeam = $dashboard['weeklyTeam'] ?? [];

        $activeStudents = $dashboard['activeStudents'] ?? 0;
        $nonActiveStudents = $dashboard['nonActiveStudents'] ?? 0;
        $mentorsUnderKpi = $dashboard['mentorsUnderKpi'] ?? 0;

        $formRegistrations = $dashboard['formRegistrations'] ?? collect();
        $completingStudents = $dashboard['completingStudents'] ?? [];

        $saleAchievementPct = $dashboard['saleAchievementPct'] ?? 0;
        $leadsPerDay = $dashboard['leadsPerDay'] ?? 0;
        $personalConversionRate = $dashboard['personalConversionRate'] ?? 0;
        $avgDealDays = $dashboard['avgDealDays'] ?? 0;
        $contractValue = $dashboard['contractValue'] ?? 0;
        $contractRevenue = $dashboard['contractRevenue'] ?? 0;

        $topSellers = $dashboard['topSellers'] ?? [];
        $adminUser = auth()->user();
    @endphp

    <section class="section org-admin-dashboard">
        <div class="row">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <div class="row mb-3">
                    <div class="col-6 col-xl-3 mb-3 mb-xl-0">
                        <div class="dashboard-box metric-box">
                            <div class="metric-title">Marketing lead<br>Form tư vấn</div>
                            <div class="metric-value">{{ number_format($marketingLeads) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3 mb-3 mb-xl-0">
                        <div class="dashboard-box metric-box">
                            <div class="metric-title">Đã tư vấn</div>
                            <div class="metric-value">{{ number_format($leadsConsulted) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3 mb-3 mb-xl-0">
                        <div class="dashboard-box metric-box">
                            <div class="metric-title">Đã trả phí</div>
                            <div class="metric-value">{{ number_format($leadsPaid) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <a href="#" data-toggle="modal" data-target="#adminLostDealsModal" class="dashboard-box metric-box d-block text-decoration-none">
                            <div class="metric-title">Đã từ chối</div>
                            <div class="metric-value">{{ number_format($leadsRejected) }}</div>
                        </a>
                    </div>
                </div>

                <div class="dashboard-box chart-box mb-3">
                    <div class="chart-title">Performance chốt đơn theo tuần</div>
                    <div class="chart-legend">
                        <span><i class="legend-dot legend-white"></i>Cá nhân admin</span>
                        <span><i class="legend-dot legend-black"></i>Theo team admin</span>
                    </div>
                    <div style="height: 245px; position: relative;">
                        <canvas id="adminWeeklyPerformanceChart"></canvas>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="dashboard-box kpi-row-box">
                            <div>
                                <div class="kpi-row-title">{{ number_format($activeStudents) }}</div>
                                <div class="kpi-row-sub">Active student</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="dashboard-box kpi-row-box">
                            <div>
                                <div class="kpi-row-title">{{ number_format($nonActiveStudents) }}</div>
                                <div class="kpi-row-sub">Non - Active student</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-box kpi-row-box">
                            <div>
                                <div class="kpi-row-title">{{ number_format($mentorsUnderKpi) }}</div>
                                <div class="kpi-row-sub">Mentor under KPI Daily</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="dashboard-box list-box">
                            <div class="list-title">List học viên để lại<br>form đăng ký</div>

                            @if($formRegistrations->count() > 0)
                                @foreach($formRegistrations as $idx => $submission)
                                    @php
                                        $user = $submission->user;
                                    @endphp

                                    <div class="wire-list-item js-queue-item js-form-item" @if($idx > 2) style="display:none;" @endif>
                                        <img src="{{ !empty($user) ? $user->getAvatar(48) : getDefaultAvatarPath() }}" class="wire-list-avatar" alt="avatar">

                                        <div class="wire-list-body">
                                            <div class="wire-list-name">{{ !empty($user) ? $user->full_name : 'Lead ẩn danh' }}</div>
                                            <div class="wire-list-meta">{{ !empty($user) ? $user->email : '-' }}</div>
                                            <div class="wire-list-meta">{{ dateTimeFormat($submission->created_at, 'j M Y') }}</div>
                                        </div>

                                        <input type="checkbox" class="wire-list-check js-queue-check" data-group="form-list">
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">Chưa có dữ liệu form đăng ký.</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="dashboard-box list-box">
                            <div class="list-title">List học viên sắp hoàn<br>thành khóa học</div>

                            @if(!empty($completingStudents))
                                @foreach($completingStudents as $idx => $item)
                                    @php
                                        $user = $item['user'] ?? null;
                                        $webinar = $item['webinar'] ?? null;
                                    @endphp

                                    <div class="wire-list-item js-queue-item js-complete-item" @if($idx > 2) style="display:none;" @endif>
                                        <img src="{{ !empty($user) ? $user->getAvatar(48) : getDefaultAvatarPath() }}" class="wire-list-avatar" alt="avatar">

                                        <div class="wire-list-body">
                                            <div class="wire-list-name">{{ !empty($user) ? $user->full_name : 'Student' }}</div>
                                            <div class="wire-list-meta">{{ !empty($webinar) ? $webinar->title : '-' }}</div>
                                            <div class="wire-list-meta">Còn lại {{ $item['remaining'] ?? 0 }}%</div>
                                        </div>

                                        <input type="checkbox" class="wire-list-check js-queue-check" data-group="complete-list">
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">Chưa có học viên gần hoàn thành khóa học.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="dashboard-box profile-box mb-3">
                    <img src="{{ $adminUser->getAvatar(80) }}" class="profile-avatar" alt="avatar">
                    <div class="profile-name">{{ $adminUser->full_name }}</div>
                    <div class="profile-rate">THÀNH TÍCH CHỐT SALE: {{ number_format($saleAchievementPct, 1) }}%</div>
                    <div class="profile-progress"><span style="width: {{ min(100, max(0, $saleAchievementPct)) }}%;"></span></div>
                </div>

                <div class="dashboard-box side-kpi-box mb-3">
                    <div class="side-kpi-title">{{ number_format($leadsPerDay, 1) }}</div>
                    <div class="side-kpi-label">Số lượt lead chăm sóc/ngày (TB tháng trước)</div>
                </div>

                <div class="dashboard-box side-kpi-box mb-3">
                    <div class="side-kpi-title">{{ number_format($personalConversionRate, 1) }}%</div>
                    <div class="side-kpi-label">Tỷ lệ chuyển đổi cá nhân (tháng trước)</div>
                </div>

                <div class="dashboard-box side-kpi-box mb-3">
                    <div class="side-kpi-title">{{ number_format($avgDealDays, 1) }} ngày</div>
                    <div class="side-kpi-label">Thời gian trung bình lead được chốt (tháng trước)</div>
                </div>

                <div class="dashboard-box side-kpi-box mb-3">
                    <div class="side-kpi-title">{{ handlePrice($contractValue) }}</div>
                    <div class="side-kpi-label">Giá trị hợp đồng (Revenue/Sale) - Tổng {{ handlePrice($contractRevenue) }}</div>
                </div>

                <div class="dashboard-box top-seller-box">
                    <div class="top-seller-title">TOP SELLER</div>

                    @if(!empty($topSellers))
                        @foreach($topSellers as $seller)
                            <div class="top-seller-item">
                                <div class="top-seller-name">{{ $seller['user']->full_name ?? '-' }}</div>
                                <div class="top-seller-rate">{{ number_format($seller['rate'] ?? 0, 1) }}%</div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">Chưa có dữ liệu top seller tháng trước.</div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="adminLostDealsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-16">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-14 font-weight-bold">Lost list (Lead bị từ chối)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body pt-12">
                    @if($rejectedLeadsList->count() > 0)
                        @foreach($rejectedLeadsList as $sale)
                            <div class="wire-list-item">
                                <img src="{{ !empty($sale->buyer) ? $sale->buyer->getAvatar(48) : getDefaultAvatarPath() }}" class="wire-list-avatar" alt="avatar">

                                <div class="wire-list-body">
                                    <div class="wire-list-name">{{ $sale->buyer->full_name ?? 'Lead ẩn danh' }}</div>
                                    <div class="wire-list-meta">{{ $sale->buyer->email ?? '-' }}</div>
                                    <div class="wire-list-meta">{{ !empty($sale->webinar) ? $sale->webinar->title : 'Deal đã bị từ chối' }}</div>
                                </div>

                                <div class="wire-list-meta">{{ !empty($sale->refund_at) ? dateTimeFormat($sale->refund_at, 'j M Y') : '-' }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">Không có lead bị từ chối trong năm hiện tại.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>

    <script>
        (function () {
            "use strict";

            var chartEl = document.getElementById('adminWeeklyPerformanceChart');
            if (chartEl) {
                new Chart(chartEl, {
                    type: 'bar',
                    data: {
                        labels: @json($weeklyLabels),
                        datasets: [
                            {
                                label: 'Cá nhân admin',
                                data: @json($weeklyPersonal),
                                backgroundColor: '#ffffff',
                                borderColor: '#111111',
                                borderWidth: 1,
                                borderRadius: 6,
                                maxBarThickness: 22,
                                categoryPercentage: 0.65,
                                barPercentage: 0.92
                            },
                            {
                                label: 'Team admin',
                                data: @json($weeklyTeam),
                                backgroundColor: '#000000',
                                borderColor: '#000000',
                                borderWidth: 1,
                                borderRadius: 6,
                                maxBarThickness: 22,
                                categoryPercentage: 0.65,
                                barPercentage: 0.92
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#555',
                                    maxRotation: 0,
                                    autoSkip: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: '#d8d8d8' },
                                ticks: {
                                    color: '#555',
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            function hookQueueBehavior(groupClass) {
                var list = document.querySelectorAll(groupClass);

                list.forEach(function (checkbox) {
                    checkbox.addEventListener('change', function () {
                        if (!checkbox.checked) {
                            return;
                        }

                        var item = checkbox.closest('.js-queue-item');
                        if (!item) {
                            return;
                        }

                        item.style.display = 'none';

                        var container = item.parentElement;
                        var hiddenItems = container.querySelectorAll('.js-queue-item');

                        for (var i = 0; i < hiddenItems.length; i++) {
                            if (hiddenItems[i].style.display === 'none') {
                                var isChecked = hiddenItems[i].querySelector('.js-queue-check').checked;
                                if (!isChecked) {
                                    hiddenItems[i].style.display = '';
                                    break;
                                }
                            }
                        }
                    });
                });
            }

            hookQueueBehavior('.js-form-item .js-queue-check');
            hookQueueBehavior('.js-complete-item .js-queue-check');
        })();
    </script>
@endpush
