@extends('admin.layouts.app')

@push('libraries_top')
	<link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
@endpush

@section('content')
	@php
		$dashboard = $managerDashboard ?? [];
		$summary = $dashboard['summary'] ?? [];
		$revenueChart = $dashboard['revenueChart'] ?? ['labels' => [], 'actual' => [], 'kpi' => []];
		$salesChart = $dashboard['salesChart'] ?? ['labels' => [], 'rejected' => [], 'closed' => [], 'leads' => []];
		$activeStudent = $dashboard['activeStudent'] ?? [];
		$activeMentor = $dashboard['activeMentor'] ?? [];
		$activeAdmin = $dashboard['activeAdmin'] ?? [];
		$rejectedLeadsList = $dashboard['rejectedLeadsList'] ?? collect();
	@endphp

	<section class="section manager-dashboard">
		<div class="row mb-16">
			<div class="col-12">
				<div class="manager-title-card rounded-16 p-20">
					<h2 class="font-22 font-weight-bold text-dark mb-4">Manager Dashboard</h2>
					<p class="text-gray-500 font-13 mb-0">Tổng quan Lead, doanh thu và hiệu suất team trong năm hiện tại.</p>
				</div>
			</div>
		</div>

		<div class="row mb-16">
			<div class="col-12 col-md-6 col-lg-3 mb-12 mb-lg-0">
				<div class="card manager-metric-card h-100" title="Đếm theo năm, reset annually">
					<div class="card-body p-16">
						<div class="d-flex align-items-center justify-content-between mb-10">
							<span class="font-12 text-gray-500">Số Lead (User)</span>
							<div class="manager-metric-icon bg-warning-30 text-warning">
								<x-iconsax-bul-user-add class="icons" width="18px" height="18px"/>
							</div>
						</div>
						<h3 class="font-28 font-weight-bold text-dark mb-0">{{ number_format($summary['leads'] ?? 0) }}</h3>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3 mb-12 mb-lg-0">
				<div class="card manager-metric-card h-100" title="Đếm theo năm, reset annually">
					<div class="card-body p-16">
						<div class="d-flex align-items-center justify-content-between mb-10">
							<span class="font-12 text-gray-500">Lead Đang Được Admin Tư Vấn</span>
							<div class="manager-metric-icon bg-primary-40 text-primary">
								<x-iconsax-bul-message-text class="icons" width="18px" height="18px"/>
							</div>
						</div>
						<h3 class="font-28 font-weight-bold text-dark mb-0">{{ number_format($summary['consulted'] ?? 0) }}</h3>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3 mb-12 mb-md-0">
				<div class="card manager-metric-card h-100" title="Đếm theo năm, tính tới hiện tại">
					<div class="card-body p-16">
						<div class="d-flex align-items-center justify-content-between mb-10">
							<span class="font-12 text-gray-500">Lead Đã Chốt / Trả Phí</span>
							<div class="manager-metric-icon bg-success-30 text-success">
								<x-iconsax-bul-wallet-money class="icons" width="18px" height="18px"/>
							</div>
						</div>
						<h3 class="font-28 font-weight-bold text-dark mb-0">{{ number_format($summary['paid'] ?? 0) }}</h3>
					</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3">
				<a href="#" data-toggle="modal" data-target="#lostLeadsModal" class="card manager-metric-card h-100 text-decoration-none" title="Click để xem lost list">
					<div class="card-body p-16">
						<div class="d-flex align-items-center justify-content-between mb-10">
							<span class="font-12 text-gray-500">Lead Bị Từ Chối (Lost)</span>
							<div class="manager-metric-icon bg-danger-30 text-danger">
								<x-iconsax-bul-close-circle class="icons" width="18px" height="18px"/>
							</div>
						</div>
						<h3 class="font-28 font-weight-bold text-dark mb-0">{{ number_format($summary['rejected'] ?? 0) }}</h3>
					</div>
				</a>
			</div>
		</div>

		<div class="row mb-16">
			<div class="col-12 col-lg-7 mb-12 mb-lg-0">
				<div class="card manager-chart-card h-100">
					<div class="card-header border-0 pb-0">
						<h4 class="mb-0">Doanh Thu Admin Trong 4 Tuần Gần Nhất</h4>
					</div>
					<div class="card-body pt-14">
						<div class="manager-legend mb-8">
							<span><i class="legend-box legend-white"></i>Doanh thu thực</span>
							<span><i class="legend-box legend-black"></i>KPI</span>
						</div>
						<div class="chart-wrap">
							<canvas id="managerRevenueChart"></canvas>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-5">
				<div class="card manager-chart-card h-100">
					<div class="card-header border-0 pb-0">
						<h4 class="mb-0">Doanh Số Sale Trong 4 Tuần Gần Nhất</h4>
					</div>
					<div class="card-body pt-14">
						<div class="manager-legend mb-8">
							<span><i class="legend-box legend-gray"></i>Deal từ chối</span>
							<span><i class="legend-box legend-white"></i>Deal đã chốt</span>
							<span><i class="legend-box legend-black"></i>Lead (User)</span>
						</div>
						<div class="chart-wrap">
							<canvas id="managerSalesChart"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-lg-4 mb-12 mb-lg-0">
				<div class="card manager-detail-card h-100">
					<div class="card-header border-0 pb-0">
						<h4 class="mb-0">Active Student</h4>
					</div>
					<div class="card-body">
						<ul class="manager-stat-list mb-0">
							<li>
								<span>Drop-off student</span>
								<strong>{{ number_format($activeStudent['dropOffCount'] ?? 0) }}</strong>
							</li>
							<li>
								<span>Điểm bài tập TB (30 ngày)</span>
								<strong>{{ number_format($activeStudent['avgAssignmentScore'] ?? 0, 1) }}</strong>
							</li>
							<li>
								<span>Tỷ lệ đạt aim band</span>
								<strong>{{ number_format($activeStudent['aimBandRate'] ?? 0, 1) }}%</strong>
							</li>
							<li>
								<span>Tỷ lệ mua thêm courses</span>
								<strong>{{ number_format($activeStudent['extraCourseRate'] ?? 0, 1) }}%</strong>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-4 mb-12 mb-lg-0">
				<div class="card manager-detail-card h-100">
					<div class="card-header border-0 pb-0">
						<h4 class="mb-0">Active Mentor (Teacher/Instructor)</h4>
					</div>
					<div class="card-body">
						<ul class="manager-stat-list mb-0">
							<li>
								<span>Student TB / mentor</span>
								<strong>{{ number_format($activeMentor['avgStudentsPerMentor'] ?? 0, 1) }}</strong>
							</li>
							<li>
								<span>Thời gian chờ chấm bài TB</span>
								<strong>{{ number_format($activeMentor['avgGradingWaitHours'] ?? 0, 1) }}h</strong>
							</li>
							<li>
								<span>Thời gian chờ hỗ trợ TB</span>
								<strong>{{ number_format($activeMentor['avgSupportWaitHours'] ?? 0, 1) }}h</strong>
							</li>
							<li>
								<span>Blog TB / tháng</span>
								<strong>{{ number_format($activeMentor['avgBlogsPerMonth'] ?? 0, 1) }}</strong>
							</li>
						</ul>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-4">
				<div class="card manager-detail-card h-100">
					<div class="card-header border-0 pb-0">
						<h4 class="mb-0">Active Admin (Organization)</h4>
					</div>
					<div class="card-body">
						<ul class="manager-stat-list mb-0">
							<li>
								<span>Doanh thu TB / tháng / admin</span>
								<strong>{{ handlePrice($activeAdmin['avgRevenuePerMonth'] ?? 0) }}</strong>
							</li>
							<li>
								<span>Tỷ lệ chốt đơn TB / tháng</span>
								<strong>{{ number_format($activeAdmin['avgCloseRatePerMonth'] ?? 0, 1) }}%</strong>
							</li>
							<li>
								<span>Blog TB / tháng / admin</span>
								<strong>{{ number_format($activeAdmin['avgBlogsPerMonth'] ?? 0, 1) }}</strong>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="lostLeadsModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content rounded-16">
				<div class="modal-header border-0 pb-0">
					<h5 class="modal-title font-14 font-weight-bold">Lost List - Lead bị từ chối</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pt-12">
					@if($rejectedLeadsList->count() > 0)
						<div class="manager-lost-list">
							@foreach($rejectedLeadsList as $sale)
								<div class="manager-lost-item d-flex align-items-start justify-content-between">
									<div class="pr-10 overflow-hidden">
										<div class="font-12 font-weight-bold text-dark text-ellipsis">{{ $sale->buyer->full_name ?? 'Lead ẩn danh' }}</div>
										<div class="font-11 text-gray-500 text-ellipsis">{{ $sale->buyer->email ?? 'Không có email' }}</div>
										<div class="font-11 text-gray-500 text-ellipsis mt-4">{{ $sale->seller->full_name ?? 'Không rõ admin' }}</div>
									</div>
									<div class="text-right flex-shrink-0">
										<div class="font-11 text-danger font-weight-bold">Rejected</div>
										<div class="font-10 text-gray-400">{{ !empty($sale->refund_at) ? dateTimeFormat($sale->refund_at, 'j M Y') : '-' }}</div>
									</div>
								</div>
							@endforeach
						</div>
					@else
						<div class="font-12 text-gray-500">Chưa có deal bị từ chối trong năm hiện tại.</div>
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

			var revenueCtx = document.getElementById('managerRevenueChart');
			if (revenueCtx) {
				new Chart(revenueCtx, {
					type: 'bar',
					data: {
						labels: @json($revenueChart['labels'] ?? []),
						datasets: [
							{
								label: 'Doanh thu thực',
								data: @json($revenueChart['actual'] ?? []),
								backgroundColor: '#ffffff',
								borderColor: '#111111',
								borderWidth: 1,
								borderRadius: 6,
								maxBarThickness: 24
							},
							{
								label: 'KPI',
								data: @json($revenueChart['kpi'] ?? []),
								backgroundColor: '#111111',
								borderColor: '#111111',
								borderWidth: 1,
								borderRadius: 6,
								maxBarThickness: 24
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
								grid: { display: false }
							},
							y: {
								beginAtZero: true,
								ticks: {
									callback: function (value) {
										return value.toLocaleString();
									}
								}
							}
						}
					}
				});
			}

			var salesCtx = document.getElementById('managerSalesChart');
			if (salesCtx) {
				new Chart(salesCtx, {
					type: 'bar',
					data: {
						labels: @json($salesChart['labels'] ?? []),
						datasets: [
							{
								label: 'Deal từ chối',
								data: @json($salesChart['rejected'] ?? []),
								backgroundColor: '#9ca3af',
								borderColor: '#6b7280',
								borderWidth: 1,
								borderRadius: 6,
								maxBarThickness: 22,
								categoryPercentage: 0.78,
								barPercentage: 0.95,
								grouped: true,
								order: 1
							},
							{
								label: 'Deal đã chốt',
								data: @json($salesChart['closed'] ?? []),
								backgroundColor: '#ffffff',
								borderColor: '#111111',
								borderWidth: 1,
								borderRadius: 6,
								maxBarThickness: 22,
								categoryPercentage: 0.78,
								barPercentage: 0.95,
								grouped: true,
								order: 2
							},
							{
								label: 'Lead',
								data: @json($salesChart['leads'] ?? []),
								backgroundColor: '#111111',
								borderColor: '#111111',
								borderWidth: 1,
								borderRadius: 6,
								maxBarThickness: 22,
								categoryPercentage: 0.78,
								barPercentage: 0.95,
								grouped: true,
								order: 3
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
								stacked: false,
								offset: true,
								grid: { display: false }
							},
							y: {
								stacked: false,
								beginAtZero: true
							}
						}
					}
				});
			}
		})();
	</script>

	<style>
		.manager-dashboard .manager-title-card,
		.manager-dashboard .manager-metric-card,
		.manager-dashboard .manager-chart-card,
		.manager-dashboard .manager-detail-card {
			background: #fff;
			border: 1px solid #edf0f5;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
		}

		.manager-metric-icon {
			width: 34px;
			height: 34px;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
		}

		.manager-legend {
			display: flex;
			gap: 16px;
			flex-wrap: wrap;
			font-size: 11px;
			color: #6b7280;
		}

		.manager-legend .legend-box {
			display: inline-block;
			width: 10px;
			height: 10px;
			border-radius: 2px;
			margin-right: 6px;
			vertical-align: -1px;
		}

		.manager-legend .legend-white {
			background: #fff;
			border: 1px solid #111;
		}

		.manager-legend .legend-black {
			background: #111;
		}

		.manager-legend .legend-gray {
			background: #9ca3af;
		}

		.manager-dashboard .chart-wrap {
			position: relative;
			height: 260px;
		}

		.manager-stat-list {
			list-style: none;
			padding-left: 0;
		}

		.manager-stat-list li {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 10px;
			border-bottom: 1px dashed #e5e7eb;
			padding: 10px 0;
		}

		.manager-stat-list li:last-child {
			border-bottom: 0;
			padding-bottom: 0;
		}

		.manager-stat-list span {
			font-size: 12px;
			color: #6b7280;
		}

		.manager-stat-list strong {
			font-size: 13px;
			color: #111827;
			white-space: nowrap;
		}

		.manager-lost-list {
			max-height: 360px;
			overflow-y: auto;
		}

		.manager-lost-item {
			background: #f9fafb;
			border-radius: 10px;
			padding: 10px;
			margin-bottom: 8px;
		}
	</style>
@endpush
