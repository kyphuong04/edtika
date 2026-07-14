@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Placement Test</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">Placement Test</div>
        </div>
    </div>

    <div class="section-body">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Danh sách đề</h4>
        <a href="{{ route('admin.placement_tests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Tạo đề mới
        </a>
    </div>

    {{-- Tiến độ theo pool: 3 B1, 2 A2, 2 B2, 1 A1, 1 B2+ --}}
    <div class="row mb-4">
        @foreach($poolProgress as $p)
            <div class="col-md-2 col-4 mb-12">
                <div style="border:2px solid {{ $p['current'] >= $p['required'] ? '#22c55e' : '#e5e7eb' }};border-radius:12px;padding:14px;text-align:center;">
                    <div style="font-weight:700;font-size:18px;color:#511D99;">{{ $p['level'] }}</div>
                    <div style="font-size:13px;color:#6b7280;">{{ $p['current'] }} / {{ $p['required'] }} đề</div>
                    <div style="font-size:11px;color:#9ca3af;">{{ $p['published'] }} đã xuất bản</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Tiêu đề</th>
                        <th>Số câu</th>
                        <th>Trạng thái</th>
                        <th>Cập nhật</th>
                        <th class="text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                        <tr>
                            <td><span class="badge badge-primary">{{ $test->level }}</span></td>
                            <td>{{ $test->title }}</td>
                            <td>
                                {{ $test->questions_count }}/{{ \App\Models\PlacementTest::MAX_QUESTIONS }}
                                @if($test->questions_count < \App\Models\PlacementTest::MAX_QUESTIONS)
                                    <span class="text-warning ml-4"><i class="fas fa-exclamation-triangle"></i></span>
                                @endif
                            </td>
                            <td>
                                @if($test->status === 'published')
                                    <span class="badge badge-success">Đã xuất bản</span>
                                @else
                                    <span class="badge badge-secondary">Nháp</span>
                                @endif
                            </td>
                            <td>{{ $test->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-right">
                                <a href="{{ route('admin.placement_tests.edit', $test) }}" class="btn btn-sm btn-outline-primary rounded-12"><i class="fas fa-edit"></i></a>

                                <form action="{{ route('admin.placement_tests.toggle_status', $test) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-12" title="{{ $test->status === 'published' ? 'Chuyển về nháp' : 'Xuất bản' }}">
                                        <i class="fas {{ $test->status === 'published' ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                    </button>
                                </form>

                                <form action="{{ route('admin.placement_tests.destroy', $test) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá đề này?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-12"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-30">Chưa có đề nào. Hãy tạo đề đầu tiên.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    </div>{{-- /section-body --}}
</section>
@endsection