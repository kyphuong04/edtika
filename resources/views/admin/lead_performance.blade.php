@extends('admin.layouts.app')

@section('content')
    @php
        $dashboard = $leadPerformanceDashboard ?? [];
        $summary = $dashboard['summary'] ?? [];
        $filters = $dashboard['filters'] ?? [];
        $leadRows = collect($dashboard['leadRows'] ?? []);
    @endphp

    <section class="section manager-lead-performance-page">
        <div class="mlp-shell">
            <div class="row mb-20">
                <div class="col-12 col-md-4 mb-12 mb-md-0">
                    <div class="mlp-kpi-card h-100">
                        <div class="mlp-kpi-head">
                            <div class="mlp-kpi-title">Lead đã phản hồi hôm nay</div>
                            <div class="mlp-kpi-icon bg-primary-40 text-primary">
                                <x-iconsax-bul-call-calling class="icons" width="17px" height="17px"/>
                            </div>
                        </div>
                        <div class="mlp-kpi-value">{{ number_format($summary['respondedToday'] ?? 0) }}</div>
                    </div>
                </div>

                <div class="col-12 col-md-4 mb-12 mb-md-0">
                    <div class="mlp-kpi-card h-100">
                        <div class="mlp-kpi-head">
                            <div class="mlp-kpi-title">Lead chuyển sang Qualified</div>
                            <div class="mlp-kpi-icon bg-success-30 text-success">
                                <x-iconsax-bul-tick-circle class="icons" width="17px" height="17px"/>
                            </div>
                        </div>
                        <div class="mlp-kpi-value">{{ number_format($summary['qualifiedLeads'] ?? 0) }}</div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="mlp-kpi-card h-100">
                        <div class="mlp-kpi-head">
                            <div class="mlp-kpi-title">Lead không phản hồi</div>
                            <div class="mlp-kpi-icon bg-danger-30 text-danger">
                                <x-iconsax-bul-close-circle class="icons" width="17px" height="17px"/>
                            </div>
                        </div>
                        <div class="mlp-kpi-value">{{ number_format($summary['unresponsiveLeads'] ?? 0) }}</div>
                    </div>
                </div>
            </div>

            <form method="get" action="{{ getAdminPanelUrl('/lead-performance') }}" class="row mb-20">
                <div class="col-12 col-lg-8 mb-10 mb-lg-0">
                    <input
                        type="text"
                        name="search"
                        value="{{ $filters['search'] ?? '' }}"
                        class="form-control mlp-control"
                        placeholder="Tìm kiếm...."
                    >
                </div>

                <div class="col-12 col-lg-4">
                    <div class="d-flex gap-8">
                        <select name="lead_type" class="form-control mlp-control">
                            <option value="">Bộ lọc</option>
                            <option value="hot" {{ (($filters['leadType'] ?? '') === 'hot') ? 'selected' : '' }}>Hot</option>
                            <option value="warm" {{ (($filters['leadType'] ?? '') === 'warm') ? 'selected' : '' }}>Warm</option>
                            <option value="cold" {{ (($filters['leadType'] ?? '') === 'cold') ? 'selected' : '' }}>Cold</option>
                        </select>

                        <button type="submit" class="btn btn-primary mlp-filter-btn">Lọc</button>
                    </div>
                </div>
            </form>

            <div class="mlp-table-panel">
                <div class="mlp-table-scroll">
                    <table class="mlp-table">
                        <thead>
                            <tr>
                                <th>Họ và tên</th>
                                <th>Ngày tháng năm sinh</th>
                                <th>Sale reps</th>
                                <th>Lead Score</th>
                                <th>Lead Status</th>
                                <th>Lead Type</th>
                                <th>Stage tiếp theo</th>
                                <th>Last Activity</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($leadRows as $lead)
                                <tr>
                                    <td class="mlp-col-name">{{ $lead['name'] ?? '-' }}</td>
                                    <td>{{ $lead['birthday'] ?? '-' }}</td>
                                    <td>{{ $lead['saleRep'] ?? '-' }}</td>
                                    <td>{{ number_format($lead['leadScore'] ?? 0) }}%</td>
                                    <td>{{ $lead['leadStatus'] ?? '-' }}</td>
                                    <td>{{ $lead['leadType'] ?? '-' }}</td>
                                    <td>{{ $lead['nextStage'] ?? '-' }}</td>
                                    <td>{{ $lead['lastActivity'] ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="mlp-empty">Chưa có dữ liệu lead.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles_top')
    <style>
        .manager-lead-performance-page {
            padding-bottom: 16px;
            --mlp-lavender-bg: rgba(212, 211, 254, 0.62);
            --mlp-lavender-border: rgba(255, 255, 255, 0.56);
            --mlp-lavender-shadow: 0 14px 28px rgba(58, 65, 111, 0.14);
        }

        .mlp-shell {
            background: transparent;
            border-radius: 0;
            padding: 0;
        }

        .mlp-kpi-card {
            background: var(--mlp-lavender-bg);
            border: 1px solid var(--mlp-lavender-border);
            border-radius: 20px;
            box-shadow: var(--mlp-lavender-shadow);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            min-height: 104px;
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
        }

        .mlp-kpi-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .mlp-kpi-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .mlp-kpi-title {
            color: #1f2937;
            font-size: 20px;
            font-weight: 600;
            line-height: 1.2;
        }

        .mlp-kpi-value {
            color: #111827;
            font-size: 22px;
            font-weight: 700;
            line-height: 1;
        }

        .mlp-control {
            height: 52px;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            background: #ffffff;
            color: #1f2937;
            font-size: 15px;
            font-weight: 500;
            padding: 0 18px;
        }

        .mlp-control:focus {
            border-color: #4b5563;
            box-shadow: none;
            background: #ffffff;
        }

        .mlp-filter-btn {
            height: 52px;
            border-radius: 16px;
            min-width: 88px;
            font-weight: 600;
            font-size: 14px;
        }

        .mlp-table-panel {
            background: var(--mlp-lavender-bg);
            border: 1px solid var(--mlp-lavender-border);
            border-radius: 16px;
            box-shadow: var(--mlp-lavender-shadow);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 12px;
        }

        .mlp-table-scroll {
            max-height: 540px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 2px;
        }

        .mlp-table {
            width: 96%;
            margin: 0 auto;
            border-collapse: separate;
            border-spacing: 0 10px;
            table-layout: fixed;
        }

        .mlp-table thead th,
        .mlp-table tbody td {
            background: rgba(255, 255, 255, 0.86);
            border-top: 2px solid #c5cde7;
            border-bottom: 2px solid #c5cde7;
            border-left: 0;
            border-right: 0;
            padding: 12px 14px;
            color: #1f2937;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .mlp-table tbody td {
            font-size: 14px;
            font-weight: 600;
        }

        .mlp-table thead th:first-child,
        .mlp-table tbody td:first-child {
            border-left: 2px solid #c5cde7;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        .mlp-table thead th:last-child,
        .mlp-table tbody td:last-child {
            border-right: 2px solid #c5cde7;
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        .mlp-col-name {
            font-weight: 700;
        }

        .mlp-table thead th:nth-child(1),
        .mlp-table tbody td:nth-child(1) {
            width: 14%;
        }

        .mlp-table thead th:nth-child(2),
        .mlp-table tbody td:nth-child(2) {
            width: 14%;
        }

        .mlp-table thead th:nth-child(3),
        .mlp-table tbody td:nth-child(3) {
            width: 13%;
        }

        .mlp-table thead th:nth-child(4),
        .mlp-table tbody td:nth-child(4) {
            width: 10%;
            text-align: center;
        }

        .mlp-table thead th:nth-child(5),
        .mlp-table tbody td:nth-child(5) {
            width: 12%;
        }

        .mlp-table thead th:nth-child(6),
        .mlp-table tbody td:nth-child(6) {
            width: 9%;
            text-align: center;
        }

        .mlp-table thead th:nth-child(7),
        .mlp-table tbody td:nth-child(7) {
            width: 15%;
        }

        .mlp-table thead th:nth-child(8),
        .mlp-table tbody td:nth-child(8) {
            width: 13%;
            text-align: right;
            padding-right: 18px;
        }

        .mlp-empty {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            font-weight: 600;
            padding: 20px 12px;
        }

        @media (max-width: 1199px) {
            .mlp-kpi-title {
                font-size: 16px;
            }

            .mlp-control {
                font-size: 15px;
            }
        }

        @media (max-width: 991px) {
            .mlp-table-scroll {
                overflow-x: auto;
            }

            .mlp-table {
                width: 100%;
                min-width: 1160px;
            }
        }

        @media (max-width: 767px) {
            .mlp-kpi-card {
                min-height: 92px;
                padding: 14px;
            }

            .mlp-kpi-title {
                font-size: 15px;
            }

            .mlp-kpi-value {
                font-size: 20px;
            }

            .mlp-control {
                height: 48px;
                font-size: 14px;
            }

            .mlp-filter-btn {
                height: 48px;
            }
        }
    </style>
@endpush
