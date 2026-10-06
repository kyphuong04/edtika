@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Banner trang kết quả</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">IELTS Tests</div>
            <div class="breadcrumb-item">Banner trang kết quả</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <p class="mb-2">Trang kết quả tự chọn 1 trong 3 banner theo tỷ lệ câu đúng của học viên. Đổi ảnh ở đây sẽ áp dụng ngay cho mọi học viên.</p>
                <p class="mb-0 text-muted" style="font-size:13px;">
                    Ảnh JPG, PNG hoặc WebP, tối đa 5MB, rộng tối thiểu 800px. Nên dùng ảnh ngang khoảng 1600&times;320px
                    và cắt sát mép banner, không chừa viền trắng — ảnh hiển thị đúng như khi tải lên.
                </p>
            </div>
        </div>

        @foreach($banners as $tier => $banner)
            <div class="card rb-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                    <div>
                        <h4 class="mb-0">{{ $banner['label'] }}</h4>
                        <div class="text-muted" style="font-size:13px;">{{ $banner['rule'] }}</div>
                    </div>
                    @if($banner['is_custom'])
                        <span class="badge badge-primary">Đã tuỳ chỉnh</span>
                    @else
                        <span class="badge badge-light">Ảnh mặc định</span>
                    @endif
                </div>

                <div class="card-body">
                    <div class="rb-preview">
                        <img src="{{ $banner['url'] }}" alt="{{ $banner['alt'] }}" id="rb-preview-{{ $tier }}"
                             data-original="{{ $banner['url'] }}">
                    </div>

                    @if($banner['is_custom'] && $banner['updated_at'])
                        <div class="text-muted mt-2" style="font-size:12px;">
                            Cập nhật {{ date('d/m/Y H:i', $banner['updated_at']) }}
                            @if($banner['updated_by_name']) bởi {{ $banner['updated_by_name'] }} @endif
                        </div>
                    @endif

                    <div class="d-flex flex-wrap align-items-start mt-3" style="gap:12px;">
                        <form action="{{ route('admin.ielts_result_banners.upload', $tier) }}" method="POST"
                              enctype="multipart/form-data" class="d-flex flex-wrap align-items-center rb-upload-form" style="gap:12px;">
                            @csrf
                            <input type="file" name="image" accept="image/png,image/jpeg,image/webp" required
                                   class="form-control-file rb-file-input" data-preview="#rb-preview-{{ $tier }}"
                                   style="max-width:320px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-1"></i> Tải ảnh lên
                            </button>
                        </form>

                        @if($banner['is_custom'])
                            <form action="{{ route('admin.ielts_result_banners.reset', $tier) }}" method="POST"
                                  onsubmit="return confirm('Xoá ảnh đã tải lên và dùng lại ảnh mặc định?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-undo mr-1"></i> Dùng lại ảnh mặc định
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        @if($errors->has('image'))
            <div class="alert alert-danger">{{ $errors->first('image') }}</div>
        @endif
    </div>
</section>

<style>
    .rb-preview {
        padding: 12px;
        border: 1px dashed #d8cdf0;
        border-radius: 12px;
        background: #faf8ff;
    }
    .rb-preview img {
        display: block;
        width: 100%;
        max-width: 960px;
        height: auto;
        border-radius: 10px;
    }
</style>

<script>
    // Xem trước ảnh vừa chọn trước khi bấm tải lên.
    document.querySelectorAll('.rb-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var img = document.querySelector(input.dataset.preview);
            if (!img) return;

            var file = input.files && input.files[0];
            img.src = file ? URL.createObjectURL(file) : img.dataset.original;
        });
    });
</script>
@endsection