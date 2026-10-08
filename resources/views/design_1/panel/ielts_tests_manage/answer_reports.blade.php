@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .ar-stat { display:block; background:#fff; padding:16px; border-radius:24px; border:2px solid transparent; color:inherit; transition:border-color .15s; }
    .ar-stat:hover { text-decoration:none; color:inherit; border-color:#e4dafb; }
    .ar-stat.is-active { border-color:#5A2B81; }
    .ar-qnum { display:inline-block; background:#f1ecfd; color:#4c1d95; font-weight:700; font-size:12px; padding:2px 10px; border-radius:8px; white-space:nowrap; }
    .ar-answer { display:inline-block; font-size:12px; padding:2px 8px; border-radius:6px; background:#dcfce7; color:#166534; font-weight:600; }
    .ar-answer.is-student { background:#f3f4f6; color:#374151; font-weight:500; }
    .ar-message { min-width:220px; max-width:360px; white-space:pre-line; word-break:break-word; font-size:14px; color:#1e1b4b; }
    .ar-status { display:inline-block; font-size:12px; font-weight:700; padding:3px 10px; border-radius:999px; white-space:nowrap; }
    .ar-status.is-new { background:#fef3c7; color:#92400e; }
    .ar-status.is-resolved { background:#dcfce7; color:#166534; }
    .ar-row-resolved td { opacity:.65; }
    .ar-actions { display:flex; justify-content:flex-end; align-items:center; gap:6px; }
    .ar-actions form { margin:0; }
    .ar-icon-btn { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#6b7280; }
    .ar-icon-btn:hover { border-color:#5A2B81; color:#5A2B81; text-decoration:none; }
    .ar-icon-btn.is-primary { background:#5A2B81; border-color:#5A2B81; color:#fff; }
    .ar-icon-btn.is-primary:hover { background:#4c1d95; color:#fff; }
    .ar-meta { font-size:12px; color:#6b7280; }
    .ar-row-new td { animation: arFlash 3s ease-out; }
    @keyframes arFlash { 0%, 40% { background:#f1ecfd; } 100% { background:transparent; } }
</style>
@endpush

@section('content')
    @php
        $currentStatus = request('status');
        $statCards = [
            ['label' => 'Tổng báo cáo', 'num' => $stats['total'], 'status' => null, 'icon' => 'document-text', 'bg' => 'bg-primary-30', 'color' => 'text-primary'],
            ['label' => 'Chưa xử lý', 'num' => $stats['new'], 'status' => 'new', 'icon' => 'warning-2', 'bg' => 'bg-warning-30', 'color' => 'text-warning'],
            ['label' => 'Đã xử lý', 'num' => $stats['resolved'], 'status' => 'resolved', 'icon' => 'tick-circle', 'bg' => 'bg-success-30', 'color' => 'text-success'],
        ];
    @endphp

    {{-- #arStats và #arTable được answer_report_poller tải lại khi có báo cáo mới --}}
    <div id="arLive" data-latest-id="{{ $latestId }}"></div>

    <div class="row" id="arStats">
        @foreach($statCards as $i => $card)
            <div class="col-12 col-lg-4 {{ $i ? 'mt-16 mt-lg-0' : '' }}">
                <a href="{{ route('panel.ielts_answer_reports.index', array_filter(['status' => $card['status']])) }}"
                   class="ar-stat {{ $currentStatus === $card['status'] ? 'is-active' : '' }}">
                    <div class="d-flex align-items-start justify-content-between">
                        <span class="text-gray-500 mt-8">{{ $card['label'] }}</span>
                        <div class="size-48 d-flex-center {{ $card['bg'] }} rounded-12">
                            @if($card['icon'] === 'warning-2')
                                <x-iconsax-bul-warning-2 class="icons {{ $card['color'] }}" width="24px" height="24px"/>
                            @elseif($card['icon'] === 'tick-circle')
                                <x-iconsax-bul-tick-circle class="icons {{ $card['color'] }}" width="24px" height="24px"/>
                            @else
                                <x-iconsax-bul-document-text class="icons {{ $card['color'] }}" width="24px" height="24px"/>
                            @endif
                        </div>
                    </div>
                    <h5 class="font-24 mt-12 line-height-1">{{ $card['num'] }}</h5>
                </a>
            </div>
        @endforeach
    </div>

    <div class="bg-white pt-16 rounded-24 mt-20">
        <div class="pb-16 px-16 border-bottom-gray-100">
            <h3 class="font-16">Thông báo đề lỗi</h3>
            <p class="font-12 text-gray-500 mt-4 mb-0">
                Học viên báo đáp án hoặc phần giải thích chưa chính xác trong các đề bạn đã tạo.
                Sửa đề đang xuất bản sẽ cần duyệt lại như bình thường.
            </p>
        </div>

        {{-- Bộ lọc --}}
        <form action="{{ route('panel.ielts_answer_reports.index') }}" method="get" class="px-16">
            <div class="row mt-24">
                <div class="col-12 col-lg-3">
                    <div class="form-group">
                        <label class="form-group-label">Đề thi</label>
                        <select name="test_id" class="form-control">
                            <option value="">Tất cả đề</option>
                            @foreach($tests as $testOption)
                                <option value="{{ $testOption->id }}" {{ (string) request('test_id') === (string) $testOption->id ? 'selected' : '' }}>{{ $testOption->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <div class="form-group">
                        <label class="form-group-label">Kỹ năng</label>
                        <select name="skill" class="form-control">
                            <option value="">Tất cả</option>
                            @foreach($skillLabels as $key => $label)
                                <option value="{{ $key }}" {{ request('skill') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <div class="form-group">
                        <label class="form-group-label">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">Tất cả</option>
                            <option value="new" {{ $currentStatus === 'new' ? 'selected' : '' }}>Chưa xử lý</option>
                            <option value="resolved" {{ $currentStatus === 'resolved' ? 'selected' : '' }}>Đã xử lý</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="form-group">
                        <label class="form-group-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tên, email học viên hoặc nội dung">
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
                </div>
            </div>
        </form>

        <div id="arTable">
        @if($reports->isEmpty())
            <div class="text-center text-gray-500 py-40 px-16 border-top-gray-100">
                {{ request()->hasAny(['status', 'test_id', 'skill', 'search']) ? 'Không có báo cáo nào khớp bộ lọc.' : 'Chưa có học viên nào báo lỗi đề của bạn.' }}
            </div>
        @else
            <div class="table-responsive-lg">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">Đề thi / Câu</th>
                        <th class="text-left">Học viên</th>
                        <th class="text-left">Đáp án</th>
                        <th class="text-left">Mô tả chi tiết</th>
                        <th class="text-center">{{ trans('public.date') }}</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-right">{{ trans('public.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                        @php
                            $created = optional($report->created_at)->timestamp;
                            $reviewUrl = $report->attempt_id
                                ? route('panel.ielts_tests.review', $report->attempt_id) . '?' . http_build_query(array_filter([
                                    'section' => $report->section_id,
                                    'q' => $report->question_number ? (int) $report->question_number : null,
                                ]))
                                : null;
                        @endphp
                        <tr class="{{ $report->isResolved() ? 'ar-row-resolved' : '' }}" data-report-id="{{ $report->id }}">
                            <td class="text-left align-middle" style="min-width:220px;">
                                <span class="d-block font-weight-500 text-dark">{{ $report->test->title ?? ('Đề #' . $report->test_id) }}</span>
                                <div class="mt-4">
                                    <span class="ar-qnum">Câu {{ $report->question_number ?: '?' }}</span>
                                    <span class="ar-meta ml-4">{{ $report->skill_label }}</span>
                                </div>
                                @if($report->question_type)
                                    <span class="ar-meta d-block mt-4">{{ ucwords(str_replace('_', ' ', $report->question_type)) }}</span>
                                @endif
                            </td>
                            <td class="text-left align-middle">
                                <span class="d-block text-dark">{{ $report->user->full_name ?? 'Tài khoản đã xoá' }}</span>
                                @if($report->user)
                                    <span class="ar-meta">{{ $report->user->email }}</span>
                                @endif
                            </td>
                            <td class="text-left align-middle" style="min-width:150px;">
                                <span class="ar-meta d-block">Đáp án hiện tại</span>
                                <span class="ar-answer">{{ $report->current_answer ?: '—' }}</span>
                                <span class="ar-meta d-block mt-4">Học viên chọn</span>
                                <span class="ar-answer is-student">{{ $report->student_answer ?: 'Chưa trả lời' }}</span>
                            </td>
                            <td class="text-left align-middle"><div class="ar-message">{{ $report->message }}</div></td>
                            <td class="text-center align-middle" style="white-space:nowrap;">
                                {{ $created ? dateTimeFormat($created, 'j M Y | H:i') : '—' }}
                            </td>
                            <td class="text-center align-middle">
                                @if($report->isResolved())
                                    <span class="ar-status is-resolved">Đã xử lý</span>
                                @else
                                    <span class="ar-status is-new">Chưa xử lý</span>
                                @endif
                            </td>
                            <td class="text-right align-middle">
                                <div class="ar-actions">
                                    @if($reviewUrl)
                                        <a href="{{ $reviewUrl }}" target="_blank" rel="noopener" class="ar-icon-btn" title="Xem câu này trong bài làm của học viên">
                                            <x-iconsax-lin-eye class="icons" width="18px" height="18px"/>
                                        </a>
                                    @endif
                                    @if($report->test)
                                        <a href="{{ route('panel.my_ielts_tests.edit_inline', $report->test_id) }}" target="_blank" rel="noopener" class="ar-icon-btn" title="Mở trang sửa đề">
                                            <x-iconsax-lin-edit-2 class="icons" width="18px" height="18px"/>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('panel.ielts_answer_reports.toggle', $report->id) }}">
                                        @csrf
                                        @if($report->isResolved())
                                            <button type="submit" class="ar-icon-btn" title="Mở lại">
                                                <x-iconsax-lin-refresh class="icons" width="18px" height="18px"/>
                                            </button>
                                        @else
                                            <button type="submit" class="ar-icon-btn is-primary" title="Đánh dấu đã xử lý">
                                                <x-iconsax-lin-tick-circle class="icons" width="18px" height="18px"/>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-16 pb-16">
                {{ $reports->links() }}
            </div>
        @endif
        </div>
    </div>
@endsection
