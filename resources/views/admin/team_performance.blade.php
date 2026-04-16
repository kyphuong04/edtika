@extends('admin.layouts.app')

@section('content')
    @php
        $dashboard = $teamPerformanceDashboard ?? [];
        $summary = $dashboard['summary'] ?? [];
        $teamMembers = collect($dashboard['teamMembers'] ?? []);
    @endphp

    <section class="section manager-team-performance-page">
        <div class="row manager-tp-top-row mb-28">
            <div class="col-12 col-md-4 mb-14 mb-md-0">
                <div class="manager-tp-kpi-card h-100">
                    <div class="manager-tp-kpi-head">
                        <div class="manager-tp-kpi-title">Tỷ lệ Chốt của Nhóm</div>
                        <div class="manager-tp-kpi-icon bg-success-30 text-success">
                            <x-iconsax-bul-tick-circle class="icons" width="17px" height="17px"/>
                        </div>
                    </div>
                    <div class="manager-tp-kpi-value">{{ number_format($summary['teamCloseRate'] ?? 0, 1) }}%</div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-14 mb-md-0">
                <div class="manager-tp-kpi-card h-100">
                    <div class="manager-tp-kpi-head">
                        <div class="manager-tp-kpi-title">Thời gian Liên hệ Trung bình</div>
                        <div class="manager-tp-kpi-icon bg-primary-40 text-primary">
                            <x-iconsax-bul-clock class="icons" width="17px" height="17px"/>
                        </div>
                    </div>
                    <div class="manager-tp-kpi-value">{{ $summary['avgContactLabel'] ?? '0m' }}</div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="manager-tp-kpi-card h-100">
                    <div class="manager-tp-kpi-head">
                        <div class="manager-tp-kpi-title">Leads Quá hạn Cần Chăm sóc</div>
                        <div class="manager-tp-kpi-icon bg-warning-30 text-warning">
                            <x-iconsax-bul-warning-2 class="icons" width="17px" height="17px"/>
                        </div>
                    </div>
                    <div class="manager-tp-kpi-value">{{ number_format($summary['overdueLeads'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="manager-tp-title-wrap mb-20">
            <h2 class="manager-tp-title mb-0">Hiệu suất Nhóm</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="manager-tp-panel">
                    <div class="manager-tp-scroll">
                        <table class="manager-tp-table">
                            <thead>
                                <tr>
                                    <th>Nhân viên Kinh doanh</th>
                                    <th>Leads được giao trong tháng</th>
                                    <th>Tổng số cuộc gọi đã ghi nhận</th>
                                    <th>Thời gian liên hệ trung bình</th>
                                    <th>Tỷ lệ chốt thành công</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teamMembers as $member)
                                    <tr>
                                        <td class="manager-tp-col-name">{{ $member['name'] ?? '-' }}</td>
                                        <td>{{ number_format($member['assignedLeadsInMonth'] ?? 0) }}</td>
                                        <td>{{ number_format($member['totalCalls'] ?? 0) }}</td>
                                        <td>{{ $member['avgContactLabel'] ?? '0m' }}</td>
                                        <td>{{ number_format($member['closeRate'] ?? 0, 1) }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="manager-tp-empty">Chưa có dữ liệu hiệu suất nhóm.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles_top')
    <style>
        .manager-team-performance-page {
            padding-bottom: 14px;
        }

        .manager-tp-top-row {
            margin-top: 4px;
        }

        .manager-tp-kpi-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 18px 18px;
            min-height: 118px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
        }

        .manager-tp-kpi-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .manager-tp-kpi-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .manager-tp-kpi-title {
            color: #1a1a1a;
            font-size: 17px;
            font-weight: 600;
            line-height: 1.35;
        }

        .manager-tp-kpi-value {
            color: #111827;
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
            margin-left: 2px;
        }

        .manager-tp-title {
            color: #111111;
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
        }

        .manager-tp-panel {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 18px;
            padding: 14px 14px 18px;
            width: 100%;
        }

        .manager-tp-scroll {
            max-height: 520px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 2px;
        }

        .manager-tp-table {
            width: 96%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0 12px;
            table-layout: fixed;
        }

        .manager-tp-table thead th,
        .manager-tp-table tbody td {
            background: #ffffff;
            border: 2px solid #7f7f7f;
            border-left-width: 0;
            border-right-width: 0;
            padding: 14px 16px;
            white-space: normal;
        }

        .manager-tp-table thead th {
            font-size: 13px;
            font-weight: 700;
            color: #1f2937;
        }

        .manager-tp-table tbody td {
            font-size: 15px;
            font-weight: 600;
            color: #151515;
        }

        .manager-tp-table thead th:first-child,
        .manager-tp-table tbody td:first-child {
            border-left-width: 2px;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            text-align: left;
        }

        .manager-tp-table thead th:last-child,
        .manager-tp-table tbody td:last-child {
            border-right-width: 2px;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .manager-tp-table thead th:not(:first-child),
        .manager-tp-table tbody td:not(:first-child) {
            text-align: center;
        }

        .manager-tp-table .manager-tp-col-name {
            min-width: 220px;
        }

        .manager-tp-table thead th:nth-child(2),
        .manager-tp-table tbody td:nth-child(2) {
            min-width: 220px;
        }

        .manager-tp-table thead th:nth-child(3),
        .manager-tp-table tbody td:nth-child(3) {
            min-width: 220px;
        }

        .manager-tp-table thead th:nth-child(4),
        .manager-tp-table tbody td:nth-child(4) {
            min-width: 210px;
        }

        .manager-tp-table thead th:nth-child(5),
        .manager-tp-table tbody td:nth-child(5) {
            min-width: 180px;
        }

        .manager-tp-empty {
            padding: 24px 10px;
            color: #6b7280;
            font-size: 15px;
            font-weight: 500;
            text-align: center;
        }

        @media (max-width: 991px) {
            .manager-tp-kpi-title {
                font-size: 16px;
            }

            .manager-tp-kpi-value {
                font-size: 24px;
            }

            .manager-tp-title {
                font-size: 28px;
            }

            .manager-tp-table {
                width: 100%;
                min-width: 900px;
            }

            .manager-tp-scroll {
                overflow-x: auto;
            }
        }

        @media (max-width: 767px) {
            .manager-tp-kpi-card {
                min-height: 102px;
                padding: 16px 14px;
            }

            .manager-tp-kpi-title {
                font-size: 15px;
            }

            .manager-tp-kpi-value {
                font-size: 22px;
            }

            .manager-tp-title {
                font-size: 24px;
            }

            .manager-tp-table {
                min-width: 900px;
            }
        }
    </style>
@endpush
