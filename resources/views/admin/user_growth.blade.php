@extends('admin.layouts.app')

@push('libraries_top')
	<link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
@endpush

@section('content')
	@php
		$dashboard = $userGrowthDashboard ?? [];
		$summary = $dashboard['summary'] ?? [];
		$chartData = $dashboard['chartData'] ?? [
			'week' => ['labels' => [], 'leads' => []],
			'month' => ['labels' => [], 'leads' => []],
			'year' => ['labels' => [], 'leads' => []],
		];
		$renewalReminders = collect($dashboard['renewalReminders'] ?? []);
	@endphp

	<section class="section manager-user-growth-page">
		<div class="row mb-20">
			<div class="col-12 col-md-6 col-lg-3 mb-14 mb-lg-0">
				<div class="manager-ug-stat-card h-100">
					<div class="manager-ug-stat-head">
						<div class="manager-ug-stat-title">New Leads</div>
						<div class="manager-ug-stat-icon manager-ug-icon-warning">
							<x-iconsax-bul-user-add class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-ug-stat-value">{{ number_format($summary['newLeads'] ?? 0) }}</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3 mb-14 mb-lg-0">
				<div class="manager-ug-stat-card h-100">
					<div class="manager-ug-stat-head">
						<div class="manager-ug-stat-title">Người dùng trả phí mới</div>
						<div class="manager-ug-stat-icon manager-ug-icon-success">
							<x-iconsax-bul-wallet-money class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-ug-stat-value">{{ number_format($summary['newPaidUsers'] ?? 0) }}</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3 mb-14 mb-md-0">
				<div class="manager-ug-stat-card h-100">
					<div class="manager-ug-stat-head">
						<div class="manager-ug-stat-title">Tỷ lệ chuyển đổi<br>(Lead -> Paid)</div>
						<div class="manager-ug-stat-icon manager-ug-icon-primary">
							<x-iconsax-bul-chart-square class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-ug-stat-value">{{ number_format($summary['conversionRate'] ?? 0, 1) }}%</div>
				</div>
			</div>

			<div class="col-12 col-md-6 col-lg-3">
				<div class="manager-ug-stat-card h-100">
					<div class="manager-ug-stat-head">
						<div class="manager-ug-stat-title">Thời gian trung bình<br>(Lead -> Paid)</div>
						<div class="manager-ug-stat-icon manager-ug-icon-accent">
							<x-iconsax-bul-clock class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-ug-stat-value">{{ $summary['avgLeadToPaidLabel'] ?? '0 ngày' }}</div>
				</div>
			</div>
		</div>

		<div class="row mb-22">
			<div class="col-12">
				<div class="card manager-ug-section-card">
					<div class="card-body p-18">
						<div class="manager-ug-chart-panel">
							<div class="btn-group manager-ug-switch mb-16" role="group" aria-label="User growth chart range">
								<button type="button" class="btn btn-light" data-range="week">Tuần</button>
								<button type="button" class="btn btn-light" data-range="month">Tháng</button>
								<button type="button" class="btn btn-light active" data-range="year">Năm</button>
							</div>

							<div class="manager-ug-chart-card">
								<canvas id="managerUserGrowthChart"></canvas>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card manager-ug-section-card">
					<div class="card-body p-18">
						<div class="manager-ug-reminder-panel">
							@forelse($renewalReminders as $item)
								<div class="manager-ug-reminder-row">
									<div class="manager-ug-reminder-text">
										<strong>{{ $item['studentName'] ?? '-' }}</strong>
										<span>- {{ $item['itemTitle'] ?? '-' }}</span>
										<span>- Còn {{ number_format($item['remainingDays'] ?? 0) }} ngày (Hết hạn: {{ !empty($item['expireAt']) ? dateTimeFormat($item['expireAt'], 'd/m/Y') : '-' }})</span>
									</div>

									@if(!empty($item['reminderUrl']))
										<a href="{{ $item['reminderUrl'] }}" class="btn btn-outline-dark manager-ug-remind-btn">Nhắc nhở</a>
									@else
										<button type="button" class="btn btn-outline-dark manager-ug-remind-btn" disabled>Nhắc nhở</button>
									@endif
								</div>
							@empty
								<div class="manager-ug-empty">Chưa có student nào sắp hết hạn khóa học trong 30 ngày tới.</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection

@push('scripts_bottom')
	<script src="/assets/default/vendors/chartjs/chart.min.js"></script>
	<script>
		(function () {
			"use strict";

			var allChartData = @json($chartData);
			var activeRange = 'year';
			var chartElement = document.getElementById('managerUserGrowthChart');

			if (!chartElement) {
				return;
			}

			function buildDataset(range) {
				var source = allChartData[range] || {labels: [], leads: []};
				var maxThickness = (source.labels || []).length > 7 ? 46 : 58;

				return {
					labels: source.labels || [],
					datasets: [
						{
							label: 'User Growth',
							data: source.leads || [],
							backgroundColor: '#ffffff',
							borderColor: '#6b7280',
							borderWidth: 2,
							borderRadius: 8,
							maxBarThickness: maxThickness,
							categoryPercentage: 0.92,
							barPercentage: 0.95
						}
					]
				};
			}

			var userGrowthChart = new Chart(chartElement, {
				type: 'bar',
				data: buildDataset(activeRange),
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false
						}
					},
					scales: {
						x: {
							grid: {
								display: false
							},
							ticks: {
								color: '#374151'
							}
						},
						y: {
							beginAtZero: true,
							grid: {
								display: false
							},
							ticks: {
								display: false,
								precision: 0
							}
						}
					}
				}
			});

			var switchButtons = document.querySelectorAll('.manager-ug-switch [data-range]');
			switchButtons.forEach(function (btn) {
				btn.addEventListener('click', function () {
					var range = btn.getAttribute('data-range');
					if (!range || range === activeRange) {
						return;
					}

					activeRange = range;
					switchButtons.forEach(function (button) {
						button.classList.remove('active', 'btn-primary');
						button.classList.add('btn-light');
					});

					btn.classList.add('active', 'btn-primary');
					btn.classList.remove('btn-light');

					userGrowthChart.data = buildDataset(activeRange);
					userGrowthChart.update();
				});
			});
		})();
	</script>

	<style>
		.manager-user-growth-page {
			--ug-panel-bg: #f3f4f6;
			--ug-stroke: #b9bec7;
			--ug-text: #111827;
			--ug-muted: #6b7280;
		}

		.manager-ug-stat-card {
			background: #ffffff;
			border: 1px solid #edf0f5;
			border-radius: 16px;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
			padding: 16px 18px;
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			min-height: 136px;
		}

		.manager-ug-stat-head {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			gap: 10px;
			margin-bottom: 14px;
		}

		.manager-ug-stat-icon {
			width: 36px;
			height: 36px;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.manager-ug-icon-warning {
			background: #fdecc8;
			color: #d97706;
		}

		.manager-ug-icon-success {
			background: #cff7de;
			color: #059669;
		}

		.manager-ug-icon-primary {
			background: #d8e9ff;
			color: #3b82f6;
		}

		.manager-ug-icon-accent {
			background: #f1ddff;
			color: #7c3aed;
		}

		.manager-ug-section-card {
			background: #ffffff;
			border: 1px solid #edf0f5;
			border-radius: 16px;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
		}

		.manager-ug-stat-title {
			font-size: 13px;
			line-height: 1.35;
			font-weight: 600;
			color: #94a3b8;
			margin-bottom: 0;
		}

		.manager-ug-stat-value {
			font-size: 38px;
			line-height: 1.05;
			font-weight: 700;
			color: var(--ug-text);
		}

		.manager-ug-chart-panel {
			background: transparent;
			padding: 0;
		}

		.manager-ug-switch .btn {
			min-width: 60px;
			font-size: 13px;
			font-weight: 600;
			border-radius: 10px;
		}

		.manager-ug-switch .btn + .btn {
			margin-left: 6px;
		}

		.manager-ug-switch .btn.active,
		.manager-ug-switch .btn.btn-primary {
			background: #111827;
			border-color: #111827;
			color: #fff;
		}

		.manager-ug-chart-card {
			background: #ffffff;
			border-radius: 16px;
			border: 1px solid #edf0f5;
			height: 360px;
			padding: 8px 10px 6px;
		}

		.manager-ug-reminder-panel {
			background: var(--ug-panel-bg);
			border-radius: 16px;
			padding: 18px;
		}

		.manager-ug-reminder-row {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 14px;
			margin-bottom: 14px;
		}

		.manager-ug-reminder-row:last-child {
			margin-bottom: 0;
		}

		.manager-ug-reminder-text {
			flex: 1;
			background: #fff;
			border: 2px solid var(--ug-stroke);
			border-radius: 16px;
			padding: 14px 18px;
			font-size: 15px;
			line-height: 1.35;
			color: var(--ug-text);
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.manager-ug-reminder-text strong {
			font-weight: 700;
		}

		.manager-ug-remind-btn {
			min-width: 118px;
			border-radius: 999px;
			font-size: 20px;
			line-height: 1;
			padding: 10px 18px;
			font-weight: 600;
			white-space: nowrap;
		}

		.manager-ug-empty {
			padding: 18px;
			text-align: center;
			font-size: 14px;
			color: var(--ug-muted);
			background: #fff;
			border: 1px dashed var(--ug-stroke);
			border-radius: 14px;
		}

		@media (max-width: 1199px) {
			.manager-ug-stat-title {
				font-size: 12px;
				margin-bottom: 0;
			}

			.manager-ug-stat-value {
				font-size: 34px;
			}
		}

		@media (max-width: 767px) {
			.manager-ug-stat-card {
				min-height: 112px;
				padding: 16px;
			}

			.manager-ug-stat-icon {
				width: 32px;
				height: 32px;
				border-radius: 9px;
			}

			.manager-ug-stat-title {
				font-size: 12px;
				margin-bottom: 0;
			}

			.manager-ug-stat-value {
				font-size: 30px;
			}

			.manager-ug-chart-card {
				height: 300px;
			}

			.manager-ug-reminder-row {
				flex-direction: column;
				align-items: stretch;
			}

			.manager-ug-reminder-text {
				font-size: 14px;
			}

			.manager-ug-remind-btn {
				width: 100%;
				font-size: 16px;
			}
		}
	</style>
@endpush
