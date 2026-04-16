@extends('admin.layouts.app')

@push('libraries_top')
	<link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css">
@endpush

@section('content')
	@php
		$dashboard = $learningQualityDashboard ?? [];
		$summary = $dashboard['summary'] ?? [];
		$allStudents = collect($dashboard['allStudents'] ?? []);
		$statusChart = $dashboard['studentStatusChart'] ?? ['active' => 0, 'dropout' => 0];
		$dropoutActiveStudents = collect($dashboard['dropoutActiveStudents'] ?? []);
		$underPerformanceStudents = collect($dashboard['underPerformanceStudents'] ?? []);
		$dissatisfiedStudents = collect($dashboard['dissatisfiedStudents'] ?? []);
	@endphp

	<section class="section manager-learning-quality-page">
		<div class="row manager-lq-row mb-24">
			<div class="col-12 col-lg-4 mb-16 mb-lg-0">
				<div class="manager-lq-metric-card h-100">
					<div class="manager-lq-metric-head">
						<div class="manager-lq-metric-title">Thời gian TB hoàn thành mỗi khóa</div>
						<div class="manager-lq-metric-icon manager-lq-icon-primary">
							<x-iconsax-bul-clock class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-lq-metric-value">{{ $summary['avgCompletionLabel'] ?? '0 giờ' }}</div>
				</div>
			</div>
			<div class="col-12 col-lg-4 mb-16 mb-lg-0">
				<div class="manager-lq-metric-card h-100">
					<div class="manager-lq-metric-head">
						<div class="manager-lq-metric-title">Tỷ lệ hoàn thành khóa học</div>
						<div class="manager-lq-metric-icon manager-lq-icon-success">
							<x-iconsax-bul-tick-circle class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-lq-metric-value">{{ number_format($summary['courseCompletionRate'] ?? 0, 1) }}%</div>
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<div class="manager-lq-metric-card h-100">
					<div class="manager-lq-metric-head">
						<div class="manager-lq-metric-title">Tỷ lệ đạt aim (trong luyện thi)</div>
						<div class="manager-lq-metric-icon manager-lq-icon-warning">
							<x-iconsax-bul-medal-star class="icons" width="17px" height="17px"/>
						</div>
					</div>
					<div class="manager-lq-metric-value">{{ number_format($summary['aimRate'] ?? 0, 1) }}%</div>
				</div>
			</div>
		</div>

		<div class="row manager-lq-row mb-24">
			<div class="col-12 col-lg-8 mb-16 mb-lg-0">
				<div class="card manager-lq-card h-100">
					<div class="card-body p-16">
						<h3 class="manager-lq-card-title">Danh sách học viên</h3>
						<div class="manager-lq-list-box manager-lq-list-main">
							@forelse($allStudents as $student)
								<div class="manager-lq-list-item manager-lq-list-item-neutral manager-lq-list-item-main">
									<span class="manager-lq-list-name">{{ $student['name'] ?? '-' }}</span>
									<span class="manager-lq-list-meta">{{ $student['email'] ?? '-' }}</span>
								</div>
							@empty
								<div class="manager-lq-empty">Chưa có học viên.</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-4">
				<div class="card manager-lq-card h-100">
					<div class="card-body p-16">
						<h3 class="manager-lq-card-title">Trạng thái học viên</h3>
						<div class="manager-lq-pie-wrap">
							<canvas id="managerLearningQualityStatusChart"></canvas>
						</div>
						<div class="manager-lq-status-legend">
							<span><i class="manager-lq-dot dot-active"></i>Active ({{ number_format($statusChart['active'] ?? 0) }})</span>
							<span><i class="manager-lq-dot dot-dropout"></i>Dropout ({{ number_format($statusChart['dropout'] ?? 0) }})</span>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row manager-lq-row">
			<div class="col-12 col-lg-4 mb-16 mb-lg-0">
				<div class="card manager-lq-card h-100">
					<div class="card-body p-16">
						<h3 class="manager-lq-card-title">Học viên Dropout/active</h3>
						<div class="manager-lq-list-box manager-lq-list-short">
							@forelse($dropoutActiveStudents as $student)
								<div class="manager-lq-list-item {{ ($student['status'] ?? '') === 'dropout' ? 'manager-lq-list-item-dark' : 'manager-lq-list-item-light' }}">
									<span class="manager-lq-list-name">{{ $student['name'] ?? '-' }}</span>
									<span class="manager-lq-list-meta">{{ $student['statusLabel'] ?? '-' }}</span>
								</div>
							@empty
								<div class="manager-lq-empty">Không có dữ liệu.</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-4 mb-16 mb-lg-0">
				<div class="card manager-lq-card h-100">
					<div class="card-body p-16">
						<h3 class="manager-lq-card-title">Học viên under performance</h3>
						<div class="manager-lq-list-box manager-lq-list-short">
							@forelse($underPerformanceStudents as $student)
								<div class="manager-lq-list-item manager-lq-list-item-dark">
									<span class="manager-lq-list-name">{{ $student['name'] ?? '-' }}</span>
									<span class="manager-lq-list-meta">
										Grade: {{ !is_null($student['avgGrade'] ?? null) ? number_format($student['avgGrade'], 1) : '-' }} | Progress: {{ number_format($student['avgProgress'] ?? 0, 1) }}%
									</span>
								</div>
							@empty
								<div class="manager-lq-empty">Không có dữ liệu.</div>
							@endforelse
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-lg-4">
				<div class="card manager-lq-card h-100">
					<div class="card-body p-16">
						<h3 class="manager-lq-card-title">Học viên không hài lòng</h3>
						<div class="manager-lq-list-box manager-lq-list-short">
							@forelse($dissatisfiedStudents as $student)
								<div class="manager-lq-list-item manager-lq-list-item-dark">
									<span class="manager-lq-list-name">{{ $student['name'] ?? '-' }}</span>
									<span class="manager-lq-list-meta">{{ $student['reason'] ?? 'Phản hồi tiêu cực' }}</span>
								</div>
							@empty
								<div class="manager-lq-empty">Không có dữ liệu.</div>
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

			var chartElement = document.getElementById('managerLearningQualityStatusChart');
			if (!chartElement) {
				return;
			}

			new Chart(chartElement, {
				type: 'pie',
				data: {
					labels: ['Active', 'Dropout'],
					datasets: [{
						data: [{{ (int)($statusChart['active'] ?? 0) }}, {{ (int)($statusChart['dropout'] ?? 0) }}],
						backgroundColor: ['#9ca3af', '#e5e7eb'],
						borderColor: ['#9ca3af', '#e5e7eb'],
						borderWidth: 1
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false
						}
					}
				}
			});
		})();
	</script>

	<style>
		.manager-learning-quality-page {
			--lq-panel-bg: #ffffff;
			--lq-stroke: #8d939c;
			--lq-dark: #a3a3a3;
			--lq-light: #ececec;
			--lq-text: #111827;
			--lq-muted: #6b7280;
		}

		.manager-lq-row {
			margin-left: -10px;
			margin-right: -10px;
		}

		.manager-lq-row > [class*="col-"] {
			padding-left: 10px;
			padding-right: 10px;
		}

		.manager-lq-metric-card {
			background: var(--lq-panel-bg);
			border: 1px solid #edf0f5;
			border-radius: 16px;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
			padding: 20px;
			min-height: 118px;
		}

		.manager-lq-metric-head {
			display: flex;
			align-items: flex-start;
			justify-content: space-between;
			gap: 10px;
			margin-bottom: 10px;
		}

		.manager-lq-metric-icon {
			width: 34px;
			height: 34px;
			border-radius: 10px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			flex-shrink: 0;
		}

		.manager-lq-icon-primary {
			background: #dbeafe;
			color: #2563eb;
		}

		.manager-lq-icon-success {
			background: #dcfce7;
			color: #16a34a;
		}

		.manager-lq-icon-warning {
			background: #fef3c7;
			color: #d97706;
		}

		.manager-lq-metric-title {
			font-size: 14px;
			line-height: 1.35;
			font-weight: 600;
			color: #94a3b8;
			margin-bottom: 0;
		}

		.manager-lq-metric-value {
			font-size: 30px;
			line-height: 1.1;
			font-weight: 700;
			color: var(--lq-text);
		}

		.manager-lq-card {
			background: #ffffff;
			border: 1px solid #edf0f5;
			border-radius: 16px;
			box-shadow: 0 4px 16px rgba(17, 24, 39, 0.05);
		}

		.manager-lq-card .card-body {
			padding: 20px !important;
		}

		.manager-lq-card-title {
			font-size: 30px;
			line-height: 1.2;
			font-weight: 700;
			text-align: center;
			color: var(--lq-text);
			margin-bottom: 18px;
		}

		.manager-lq-list-box {
			background: transparent;
		}

		.manager-lq-list-main {
			max-height: 300px;
			overflow-y: auto;
			padding-right: 6px;
			padding-left: 4px;
			padding-right: 4px;
		}

		.manager-lq-list-item-main {
			width: 88%;
			max-width: 900px;
			margin-left: auto;
			margin-right: auto;
		}

		.manager-lq-list-short {
			max-height: 220px;
			overflow-y: auto;
			padding-right: 6px;
		}

		.manager-lq-list-item {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 12px;
			border-radius: 14px;
			padding: 9px 12px;
			margin-bottom: 10px;
			border: 1px solid var(--lq-stroke);
		}

		.manager-lq-list-item:last-child {
			margin-bottom: 0;
		}

		.manager-lq-list-item-neutral {
			background: #d7d7d7;
		}

		.manager-lq-list-item-dark {
			background: var(--lq-dark);
		}

		.manager-lq-list-item-light {
			background: var(--lq-light);
		}

		.manager-lq-list-name {
			font-size: 13px;
			font-weight: 700;
			color: var(--lq-text);
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.manager-lq-list-meta {
			font-size: 11px;
			color: #4b5563;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.manager-lq-pie-wrap {
			position: relative;
			height: 210px;
			margin-top: 10px;
		}

		.manager-lq-status-legend {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 18px;
			flex-wrap: wrap;
			font-size: 12px;
			color: var(--lq-muted);
			margin-top: 12px;
		}

		.manager-lq-dot {
			display: inline-block;
			width: 10px;
			height: 10px;
			border-radius: 50%;
			margin-right: 6px;
			vertical-align: middle;
		}

		.manager-lq-dot.dot-active {
			background: #9ca3af;
		}

		.manager-lq-dot.dot-dropout {
			background: #e5e7eb;
		}

		.manager-lq-empty {
			padding: 12px;
			font-size: 12px;
			text-align: center;
			color: var(--lq-muted);
			background: #ffffff;
			border-radius: 12px;
			border: 1px dashed #cdd2db;
		}

		@media (max-width: 1199px) {
			.manager-lq-metric-title {
				font-size: 13px;
			}

			.manager-lq-card-title {
				font-size: 24px;
			}

			.manager-lq-metric-value {
				font-size: 27px;
			}
		}

		@media (max-width: 767px) {
			.manager-lq-row {
				margin-left: -6px;
				margin-right: -6px;
			}

			.manager-lq-row > [class*="col-"] {
				padding-left: 6px;
				padding-right: 6px;
			}

			.manager-lq-metric-title {
				font-size: 12px;
			}

			.manager-lq-metric-value {
				font-size: 24px;
			}

			.manager-lq-card-title {
				font-size: 20px;
			}

			.manager-lq-list-item {
				flex-direction: column;
				align-items: flex-start;
			}

			.manager-lq-list-item-main {
				width: 100%;
			}

			.manager-lq-list-name,
			.manager-lq-list-meta {
				white-space: normal;
			}
		}
	</style>
@endpush
