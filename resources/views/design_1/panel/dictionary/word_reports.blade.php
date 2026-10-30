@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .wr-stat { display:block; background:#fff; padding:16px; border-radius:24px; border:2px solid transparent; color:inherit; transition:border-color .15s; }
    .wr-stat:hover { text-decoration:none; color:inherit; border-color:#e4dafb; }
    .wr-stat.is-active { border-color:#5A2B81; }
    .wr-word { font-weight:700; color:#1e1b4b; font-size:15px; word-break:break-word; }
    .wr-pos { display:inline-block; background:#f1ecfd; color:#4c1d95; font-weight:700; font-size:12px; padding:2px 10px; border-radius:8px; white-space:nowrap; }
    .wr-meaning { display:inline-block; max-width:220px; font-size:13px; padding:3px 8px; border-radius:6px; background:#dcfce7; color:#166534; font-weight:600; word-break:break-word; }
    .wr-message { min-width:220px; max-width:360px; white-space:pre-line; word-break:break-word; font-size:14px; color:#1e1b4b; }
    .wr-status { display:inline-block; font-size:12px; font-weight:700; padding:3px 10px; border-radius:999px; white-space:nowrap; }
    .wr-status.is-new { background:#fef3c7; color:#92400e; }
    .wr-status.is-resolved { background:#dcfce7; color:#166534; }
    .wr-row-resolved td { opacity:.65; }
    .wr-actions { display:flex; justify-content:flex-end; align-items:center; gap:6px; }
    .wr-actions form { margin:0; }
    .wr-icon-btn { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; border:1px solid #e5e7eb; background:#fff; color:#6b7280; }
    .wr-icon-btn:hover { border-color:#5A2B81; color:#5A2B81; text-decoration:none; }
    .wr-icon-btn.is-primary { background:#5A2B81; border-color:#5A2B81; color:#fff; }
    .wr-icon-btn.is-primary:hover { background:#4c1d95; color:#fff; }
    .wr-meta { font-size:12px; color:#6b7280; }
    .wr-row-new td { animation: wrFlash 3s ease-out; }
    @keyframes wrFlash { 0%, 40% { background:#f1ecfd; } 100% { background:transparent; } }
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

    {{-- #wrStats và #wrTable được answer_report_poller (pollPrefix = wr) tải lại khi có báo cáo mới --}}
    <div id="wrLive" data-latest-id="{{ $latestId }}"></div>

    <div class="row" id="wrStats">
        @foreach($statCards as $i => $card)
            <div class="col-12 col-lg-4 {{ $i ? 'mt-16 mt-lg-0' : '' }}">
                <a href="{{ route('panel.vocabulary_word_reports.index', array_filter(['status' => $card['status']])) }}"
                   class="wr-stat {{ $currentStatus === $card['status'] ? 'is-active' : '' }}">
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
            <h3 class="font-16">Thông báo từ vựng lỗi</h3>
            <p class="font-12 text-gray-500 mt-4 mb-0">
                Học viên báo nội dung từ vựng (nghĩa, phát âm, ví dụ, hình ảnh...) chưa chính xác trong các bộ từ bạn đã tạo.
            </p>
        </div>

        {{-- Bộ lọc --}}
        <form action="{{ route('panel.vocabulary_word_reports.index') }}" method="get" class="px-16">
            <div class="row mt-24">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Bộ từ vựng</label>
                        <select name="set" class="form-control">
                            <option value="">Tất cả bộ từ</option>
                            @foreach($sets as $setOption)
                                <option value="{{ $setOption['value'] }}" {{ (string) request('set') === $setOption['value'] ? 'selected' : '' }}>{{ $setOption['label'] }}</option>
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
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Từ vựng, tên/email học viên hoặc nội dung">
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ trans('filter') }}</button>
                </div>
            </div>
        </form>

        <div id="wrTable">
        @if($reports->isEmpty())
            <div class="text-center text-gray-500 py-40 px-16 border-top-gray-100">
                {{ request()->hasAny(['status', 'set', 'search']) ? 'Không có báo cáo nào khớp bộ lọc.' : 'Chưa có học viên nào báo lỗi từ vựng trong bộ từ của bạn.' }}
            </div>
        @else
            <div class="table-responsive-lg">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left">Từ vựng / Bộ từ</th>
                        <th class="text-left">Học viên</th>
                        <th class="text-left">Nghĩa lúc báo lỗi</th>
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
                            // Bộ từ khoá học: mở trang quản lý bộ từ để sửa từ
                            $editUrl = ($report->isBundle() && $report->set_id)
                                ? url('/panel/dictionary/bundle-vocabulary/' . $report->set_id)
                                : null;
                        @endphp
                        <tr class="{{ $report->isResolved() ? 'wr-row-resolved' : '' }}" data-report-id="{{ $report->id }}">
                            <td class="text-left align-middle" style="min-width:200px;">
                                <span class="wr-word d-block">{{ $report->word }}</span>
                                @if($report->part_of_speech)
                                    <span class="wr-pos mt-4">{{ $report->part_of_speech }}</span>
                                @endif
                                <span class="wr-meta d-block mt-4">{{ $report->set_name ?: ('Bộ từ #' . $report->set_id) }}</span>
                                <span class="wr-meta d-block">{{ $report->source_label }}</span>
                            </td>
                            <td class="text-left align-middle">
                                <span class="d-block text-dark">{{ $report->user->full_name ?? 'Tài khoản đã xoá' }}</span>
                                @if($report->user)
                                    <span class="wr-meta">{{ $report->user->email }}</span>
                                @endif
                            </td>
                            <td class="text-left align-middle" style="min-width:150px;">
                                <span class="wr-meaning">{{ $report->current_meaning ?: '—' }}</span>
                            </td>
                            <td class="text-left align-middle"><div class="wr-message">{{ $report->message }}</div></td>
                            <td class="text-center align-middle" style="white-space:nowrap;">
                                {{ $created ? dateTimeFormat($created, 'j M Y | H:i') : '—' }}
                            </td>
                            <td class="text-center align-middle">
                                @if($report->isResolved())
                                    <span class="wr-status is-resolved">Đã xử lý</span>
                                @else
                                    <span class="wr-status is-new">Chưa xử lý</span>
                                @endif
                            </td>
                            <td class="text-right align-middle">
                                <div class="wr-actions">
                                    @if($editUrl)
                                        <a href="{{ $editUrl }}" target="_blank" rel="noopener" class="wr-icon-btn" title="Mở bộ từ để sửa">
                                            <x-iconsax-lin-edit-2 class="icons" width="18px" height="18px"/>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('panel.vocabulary_word_reports.toggle', $report->id) }}">
                                        @csrf
                                        @if($report->isResolved())
                                            <button type="submit" class="wr-icon-btn" title="Mở lại">
                                                <x-iconsax-lin-refresh class="icons" width="18px" height="18px"/>
                                            </button>
                                        @else
                                            <button type="submit" class="wr-icon-btn is-primary" title="Đánh dấu đã xử lý">
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
