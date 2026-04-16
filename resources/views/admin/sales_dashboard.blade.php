@extends('admin.layouts.app')

@push('libraries_top')
	<link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
@endpush

@section('content')
	@php
		$dashboard = $businessDashboard ?? [];
		$chartData = $dashboard['chartData'] ?? ['week' => ['labels' => [], 'thisYear' => [], 'lastYear' => [], 'kpi' => []], 'month' => ['labels' => [], 'thisYear' => [], 'lastYear' => [], 'kpi' => []], 'year' => ['labels' => [], 'thisYear' => [], 'lastYear' => [], 'kpi' => []]];
		$salesRepresentatives = $dashboard['salesRepresentatives'] ?? collect();
	@endphp

	<section class="section manager-business-page">
		<div class="row mb-24">
			<div class="col-12">
				<div class="card manager-section-card">
					<div class="card-body p-20 p-md-24">
						<h2 class="manager-business-title mb-24">Tổng quan về doanh thu</h2>

						<div class="manager-business-card">
						<div class="d-flex align-items-center justify-content-between flex-wrap gap-12 mb-24">
							<div class="btn-group manager-chart-switch" role="group" aria-label="Business chart range">
								<button type="button" class="btn btn-light active" data-range="week">Tuần</button>
								<button type="button" class="btn btn-light" data-range="month">Tháng</button>
								<button type="button" class="btn btn-light" data-range="year">Năm</button>
							</div>

							<div class="manager-line-legend">
								<span><i class="line-dot dot-dark"></i>Doanh thu năm nay</span>
								<span><i class="line-dot dot-gray"></i>Doanh thu năm ngoái</span>
								<span><i class="line-dot dot-black"></i>KPI</span>
							</div>
						</div>

						<div class="manager-line-chart-wrap">
							<canvas id="managerBusinessLineChart"></canvas>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="card manager-section-card">
					<div class="card-body p-20 p-md-24">
						<h3 class="manager-business-subtitle mb-24">Doanh thu trên mỗi đại diện bán hàng</h3>

						<div class="manager-sales-list-box">
							@forelse($salesRepresentatives as $rep)
								<div class="manager-sales-row">
									<div class="manager-sales-col"><strong>Tên:</strong> {{ $rep['name'] }}</div>
									<div class="manager-sales-col"><strong>Mail:</strong> {{ $rep['email'] ?? '-' }}</div>
									<div class="manager-sales-col"><strong>Tổng chi trả:</strong> {{ handlePrice($rep['totalRevenue'] ?? 0) }}</div>
									<div class="manager-sales-col"><strong>Ngày đăng ký:</strong> {{ !empty($rep['registeredAt']) ? dateTimeFormat($rep['registeredAt'], 'd/m/Y') : '-' }}</div>
								</div>
							@empty
								<div class="manager-sales-empty">Chưa có dữ liệu đại diện bán hàng.</div>
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
			var activeRange = 'week';
			var ctx = document.getElementById('managerBusinessLineChart');

			if (!ctx) {
				return;
			}

			function buildDataset(range) {
				var source = allChartData[range] || {labels: [], thisYear: [], lastYear: [], kpi: []};

				return {
					labels: source.labels || [],
					datasets: [
						{
							label: 'Doanh thu năm nay',
							data: source.thisYear || [],
							borderColor: '#1f2937',
							backgroundColor: 'transparent',
							borderWidth: 2,
							pointRadius: 0,
							pointHoverRadius: 3,
							tension: 0.35
						},
						{
							label: 'Doanh thu năm ngoái',
							data: source.lastYear || [],
							borderColor: '#9ca3af',
							backgroundColor: 'transparent',
							borderWidth: 2,
							pointRadius: 0,
							pointHoverRadius: 3,
							tension: 0.35
						},
						{
							label: 'KPI',
							data: source.kpi || [],
							borderColor: '#000000',
							backgroundColor: 'transparent',
							borderWidth: 2,
							borderDash: [4, 4],
							pointRadius: 0,
							pointHoverRadius: 3,
							tension: 0.35
						}
					]
				};
			}

			var lineChart = new Chart(ctx, {
				type: 'line',
				data: buildDataset(activeRange),
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {display: false}
					},
					interaction: {
						mode: 'index',
						intersect: false
					},
					scales: {
						x: {
							grid: {
								display: false
							},
							ticks: {
								color: '#6b7280'
							}
						},
						y: {
							beginAtZero: true,
							grid: {
								color: '#e5e7eb',
								borderDash: [3, 3]
							},
							ticks: {
								color: '#6b7280',
								callback: function (value) {
									return value.toLocaleString();
								}
							}
						}
					}
				}
			});

			var switchButtons = document.querySelectorAll('.manager-chart-switch [data-range]');
			switchButtons.forEach(function (btn) {
				btn.addEventListener('click', function () {
					var range = btn.getAttribute('data-range');
					if (!range || range === activeRange) {
						return;
					}

					activeRange = range;

					switchButtons.forEach(function (b) {
						b.classList.remove('active', 'btn-primary');
						b.classList.add('btn-light');
					});

					btn.classList.add('active', 'btn-primary');
					btn.classList.remove('btn-light');

					lineChart.data = buildDataset(activeRange);
					lineChart.update();
				});
			});
		})();
	</script>

	<style>
		.manager-business-page {
			--business-bg: #f3f4f6;
			--business-border: #b9bec7;
			--business-text: #111827;
			--business-muted: #6b7280;
		}

		.manager-section-card {
			background: #fff;
			border: 1px solid #edf0f5;
			border-radius: 16px;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
		}

		.manager-business-title {
			font-size: 42px;
			line-height: 1.1;
			font-weight: 700;
			color: var(--business-text);
		}

		.manager-business-subtitle {
			font-size: 28px;
			line-height: 1.2;
			font-weight: 700;
			color: var(--business-text);
		}

		.manager-business-card {
			background: var(--business-bg);
			border-radius: 16px;
			padding: 20px;
		}

		.manager-chart-switch .btn {
			min-width: 62px;
			font-size: 13px;
			font-weight: 600;
			border-radius: 10px;
		}

		.manager-chart-switch .btn + .btn {
			margin-left: 6px;
		}

		.manager-chart-switch .btn.active,
		.manager-chart-switch .btn.btn-primary {
			background: #111827;
			border-color: #111827;
			color: #fff;
		}

		.manager-line-legend {
			display: flex;
			gap: 14px;
			flex-wrap: wrap;
			font-size: 12px;
			color: var(--business-muted);
		}

		.manager-line-legend .line-dot {
			display: inline-block;
			width: 9px;
			height: 9px;
			border-radius: 50%;
			margin-right: 6px;
			vertical-align: middle;
		}

		.manager-line-legend .dot-dark {
			background: #1f2937;
		}

		.manager-line-legend .dot-gray {
			background: #9ca3af;
		}

		.manager-line-legend .dot-black {
			background: #000;
		}

		.manager-line-chart-wrap {
			position: relative;
			height: 360px;
			background: #fff;
			border: 2px solid var(--business-border);
			border-radius: 20px;
			padding: 16px;
			margin-top: 4px;
		}

		.manager-sales-list-box {
			background: var(--business-bg);
			border-radius: 16px;
			padding: 16px;
		}

		.manager-sales-row {
			display: grid;
			grid-template-columns: 1.2fr 1.2fr 1.4fr 1.1fr;
			gap: 16px;
			background: #fff;
			border: 2px solid var(--business-border);
			border-radius: 12px;
			padding: 16px 22px;
			margin-bottom: 14px;
			align-items: center;
		}

		.manager-sales-row:last-child {
			margin-bottom: 0;
		}

		.manager-sales-col {
			color: var(--business-text);
			font-size: 16px;
			line-height: 1.4;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.manager-sales-col strong {
			font-weight: 700;
		}

		.manager-sales-empty {
			padding: 20px;
			font-size: 14px;
			color: var(--business-muted);
			text-align: center;
			background: #fff;
			border: 1px dashed var(--business-border);
			border-radius: 12px;
		}

		@media (max-width: 1199px) {
			.manager-business-title {
				font-size: 36px;
			}

			.manager-sales-row {
				grid-template-columns: 1fr 1fr;
			}
		}

		@media (max-width: 767px) {
			.manager-business-title {
				font-size: 30px;
			}

			.manager-business-subtitle {
				font-size: 24px;
			}

			.manager-line-chart-wrap {
				height: 300px;
				padding: 12px;
			}

			.manager-sales-list-box {
				padding: 14px;
			}

			.manager-sales-row {
				grid-template-columns: 1fr;
				gap: 8px;
				padding: 14px;
			}

			.manager-sales-col {
				font-size: 14px;
			}
		}
	</style>
@endpush
