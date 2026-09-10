@extends('admin.layouts.app')

@push('styles_top')
<style>
.pr-stat-card { border:2px solid #e5e7eb; border-radius:12px; padding:16px; text-align:center; height:100%; }
.pr-stat-card .pr-stat-num { font-size:24px; font-weight:800; color:#511D99; line-height:1.2; }
.pr-stat-card .pr-stat-label { font-size:12px; color:#6b7280; margin-top:4px; }
.pr-stat-card.is-active { border-color:#511D99; background:#f5f3ff; }

.pr-level-chip { display:inline-block; background:#f3e8ff; color:#511D99; font-weight:700; font-size:12px; padding:2px 10px; border-radius:8px; }
.pr-score-chip { display:inline-block; background:#f3f4f6; color:#374151; font-size:11px; padding:1px 7px; border-radius:6px; margin-right:3px; }
.pr-score-chip.is-unscored { background:#fef3c7; color:#78350f; }
.pr-guest { color:#9ca3af; font-style:italic; }
.pr-actions { white-space:nowrap; }
.pr-actions .btn { margin-left:2px; }
.pr-filter-bar { background:#f8f9fa; border-radius:12px; padding:16px; margin-bottom:20px; }
.pr-bulk-bar { display:none; background:#eef2ff; border:1px solid #c7d2fe; border-radius:10px; padding:10px 14px; margin-bottom:12px; align-items:center; justify-content:space-between; }
.pr-bulk-bar.is-visible { display:flex; }
</style>
@endpush

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Bài làm Placement Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">Bài làm Placement Test</div>
        </div>
    </div>

    <div class="section-body">

    {{-- Thống kê nhanh, bấm vào để lọc --}}
    <div class="row mb-4">
        @php
            $currentStatus = request('status');
            $cards = [
                ['label' => 'Tổng số bài',    'num' => $stats['total'],       'status' => null,          'guest' => null],
                ['label' => 'Đã hoàn thành',  'num' => $stats['completed'],   'status' => 'completed',   'guest' => null],
                ['label' => 'Đang làm dở',    'num' => $stats['in_progress'], 'status' => 'in_progress', 'guest' => null],
                ['label' => 'Đã lưu trữ',     'num' => $stats['archived'],    'status' => 'archived',    'guest' => null],
                ['label' => 'Khách chưa ĐN',  'num' => $stats['guest'],       'status' => null,          'guest' => '1'],
            ];
        @endphp
        @foreach($cards as $card)
            @php
                $isActive = ($card['guest'] === '1' && request('guest') === '1')
                    || ($card['guest'] === null && request('guest') !== '1' && $currentStatus === $card['status']);
                $url = route('admin.placement_results.index', array_filter([
                    'status' => $card['status'],
                    'guest'  => $card['guest'],
                ]));
            @endphp
            <div class="col-md-2 col-6 mb-2">
                <a href="{{ $url }}" class="d-block text-decoration-none">
                    <div class="pr-stat-card {{ $isActive ? 'is-active' : '' }}">
                        <div class="pr-stat-num">{{ $card['num'] }}</div>
                        <div class="pr-stat-label">{{ $card['label'] }}</div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('admin.placement_results.index') }}" class="pr-filter-bar">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label class="input-label">Tìm học viên</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tên, email hoặc số điện thoại">
            </div>
            <div class="col-md-3">
                <label class="input-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="">Tất cả (trừ đã lưu trữ)</option>
                    <option value="completed"   {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Đang làm bài</option>
                    <option value="speaking"    {{ request('status') === 'speaking' ? 'selected' : '' }}>Đang ở phần Speaking</option>
                    <option value="abandoned"   {{ request('status') === 'abandoned' ? 'selected' : '' }}>Bỏ dở</option>
                    <option value="archived"    {{ request('status') === 'archived' ? 'selected' : '' }}>Đã lưu trữ</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="input-label">Level kết quả</label>
                <select name="level" class="form-control">
                    <option value="">Tất cả</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary rounded-12"><i class="fas fa-search mr-2"></i>Lọc</button>
                <a href="{{ route('admin.placement_results.index') }}" class="btn btn-outline-secondary rounded-12">Xoá lọc</a>
            </div>
        </div>
    </form>

    <div class="alert alert-info">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Reset</strong> chuyển bài thi sang trạng thái lưu trữ — học viên làm lại được ngay, dữ liệu cũ vẫn tra cứu được ở bộ lọc
        <em>Đã lưu trữ</em>. <strong>Xoá</strong> là vĩnh viễn, kể cả file ghi âm Speaking.
    </div>

    <form method="POST" action="{{ route('admin.placement_results.bulk_reset') }}" id="prBulkForm">
        @csrf

        <div class="pr-bulk-bar" id="prBulkBar">
            <span>Đã chọn <strong id="prSelectedCount">0</strong> bài</span>
            <button type="submit" class="btn btn-sm btn-primary rounded-12"
                    onclick="return confirm('Reset các bài đã chọn? Học viên sẽ làm lại được từ đầu.');">
                <i class="fas fa-redo mr-2"></i>Reset các bài đã chọn
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:36px;"><input type="checkbox" id="prCheckAll"></th>
                            <th>Học viên</th>
                            <th>Trạng thái</th>
                            <th>Level</th>
                            <th>Các đề đã làm</th>
                            <th>Bắt đầu</th>
                            <th>Hoàn thành</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($attempts as $attempt)
                        @php
                            $statusMap = [
                                'in_progress' => ['Đang làm bài', 'badge-info'],
                                'speaking'    => ['Phần Speaking', 'badge-info'],
                                'completed'   => ['Hoàn thành', 'badge-success'],
                                'abandoned'   => ['Bỏ dở', 'badge-secondary'],
                                'archived'    => ['Đã lưu trữ', 'badge-dark'],
                            ];
                            [$statusLabel, $statusClass] = $statusMap[$attempt->status] ?? [$attempt->status, 'badge-secondary'];
                        @endphp
                        <tr>
                            <td>
                                @if($attempt->user_id && $attempt->status !== 'archived')
                                    <input type="checkbox" name="attempt_ids[]" value="{{ $attempt->id }}" class="pr-row-check">
                                @endif
                            </td>
                            <td>
                                @if($attempt->user)
                                    <div style="font-weight:600;">{{ $attempt->user->full_name }}</div>
                                    <div style="font-size:12px;color:#6b7280;">{{ $attempt->user->email }}</div>
                                @else
                                    <span class="pr-guest"><i class="fas fa-user-secret mr-1"></i>Khách chưa đăng nhập</span>
                                    <div style="font-size:12px;color:#9ca3af;">Attempt #{{ $attempt->id }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                @if($attempt->archived_at)
                                    <div style="font-size:11px;color:#9ca3af;margin-top:3px;">
                                        {{ $attempt->archived_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($attempt->final_level)
                                    <span class="pr-level-chip">{{ $attempt->final_level }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @forelse(($attempt->levels_taken ?? []) as $i => $lvl)
                                    <span class="pr-score-chip {{ $attempt->isStepScored($i) ? '' : 'is-unscored' }}"
                                          title="{{ $attempt->isStepScored($i) ? 'Có tính điểm' : 'Đề tham khảo, không tính điểm' }}">
                                        {{ $lvl }}: {{ $attempt->scores[$i] ?? '—' }}/10
                                    </span>
                                @empty
                                    <span class="text-muted">Chưa có</span>
                                @endforelse
                            </td>
                            <td style="font-size:13px;">{{ optional($attempt->started_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td style="font-size:13px;">{{ optional($attempt->completed_at)->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="text-right pr-actions">
                                @if($attempt->user_id && $attempt->status === 'completed')
                                    <a href="{{ route('admin.users.placement_result_detail', $attempt->user_id) }}"
                                       target="_blank" class="btn btn-sm btn-outline-primary rounded-12" title="Xem chi tiết bài làm">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                @endif

                                @if($attempt->status === 'archived')
                                    <button type="button" class="btn btn-sm btn-outline-success rounded-12"
                                            title="Khôi phục về danh sách kết quả"
                                            onclick="prSubmit('{{ route('admin.placement_results.restore', $attempt->id) }}', 'PATCH', 'Khôi phục bài thi này về danh sách kết quả?')">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                @elseif($attempt->user_id)
                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-12"
                                            title="Reset — cho học viên thi lại"
                                            onclick="prSubmit('{{ route('admin.placement_results.reset', $attempt->id) }}', 'PATCH', 'Reset bài thi của {{ addslashes($attempt->user->full_name ?? '') }}?\n\nHọc viên sẽ làm lại được từ đầu. Kết quả cũ vẫn xem được ở mục Đã lưu trữ.')">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                @endif

                                <button type="button" class="btn btn-sm btn-outline-danger rounded-12"
                                        title="Xoá vĩnh viễn"
                                        onclick="prSubmit('{{ route('admin.placement_results.destroy', $attempt->id) }}', 'DELETE', 'XOÁ VĨNH VIỄN bài thi này?\n\nToàn bộ câu trả lời và file ghi âm Speaking sẽ mất, không khôi phục được.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-30">Không có bài thi nào khớp bộ lọc.</td></tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-3">
        {{ $attempts->links() }}
    </div>

    </div>{{-- /section-body --}}
</section>

{{-- Form ẩn dùng chung cho các thao tác PATCH/DELETE --}}
<form id="prActionForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="_method" id="prActionMethod" value="PATCH">
</form>
@endsection

@push('scripts_bottom')
<script>
function prSubmit(url, method, message) {
    if (!confirm(message)) return;
    const form = document.getElementById('prActionForm');
    form.action = url;
    document.getElementById('prActionMethod').value = method;
    form.submit();
}

(function () {
    const checkAll = document.getElementById('prCheckAll');
    const bulkBar = document.getElementById('prBulkBar');
    const counter = document.getElementById('prSelectedCount');

    function rows() {
        return Array.from(document.querySelectorAll('.pr-row-check'));
    }

    function refresh() {
        const selected = rows().filter(cb => cb.checked).length;
        counter.textContent = selected;
        bulkBar.classList.toggle('is-visible', selected > 0);
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            rows().forEach(cb => { cb.checked = checkAll.checked; });
            refresh();
        });
    }

    rows().forEach(cb => cb.addEventListener('change', refresh));
})();
</script>
@endpush