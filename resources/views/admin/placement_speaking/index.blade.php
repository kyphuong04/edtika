@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Câu hỏi Speaking</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">Câu hỏi Speaking</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card mb-20">
            <div class="card-header"><h4>Thêm câu hỏi mới</h4></div>
            <div class="card-body">
                <form action="{{ route('admin.placement_speaking.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <textarea name="question_text" class="form-control" rows="2" required placeholder="VD: Describe a memorable trip you have taken. Why was it memorable?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-12"><i class="fas fa-plus mr-5"></i>Thêm câu hỏi</button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th style="width:55%;">Câu hỏi</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $q)
                            <tr>
                                <td>
                                    <form action="{{ route('admin.placement_speaking.update', $q) }}" method="POST" class="d-flex align-items-center gap-8">
                                        @csrf @method('PUT')
                                        <input type="text" name="question_text" value="{{ $q->question_text }}" class="form-control form-control-sm">
                                        <button type="submit" class="btn btn-sm btn-outline-primary rounded-12">Lưu</button>
                                    </form>
                                </td>
                                <td>
                                    @if($q->is_active)
                                        <span class="badge badge-success">Đang dùng</span>
                                    @else
                                        <span class="badge badge-secondary">Đã tắt</span>
                                    @endif
                                </td>
                                <td>{{ $q->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-right">
                                    <form action="{{ route('admin.placement_speaking.toggle_status', $q) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $q->is_active ? 'secondary' : 'success' }} rounded-12">
                                            {{ $q->is_active ? 'Tắt' : 'Bật' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.placement_speaking.destroy', $q) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá câu hỏi này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-12"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-30">Chưa có câu hỏi Speaking nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection