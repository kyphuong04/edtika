@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Ảnh modal đăng nhập</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">Giao diện</div>
            <div class="breadcrumb-item">Ảnh modal đăng nhập</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <p class="mb-2">
                    Modal <strong>Đăng nhập / Đăng ký</strong> có một khung slider bên phải, tự chuyển qua 3 slide.
                    Mỗi slide gồm 1 ảnh, 1 tiêu đề và 1 dòng mô tả. Thay đổi ở đây áp dụng ngay cho mọi trang có modal.
                </p>
                <p class="mb-0 text-muted" style="font-size:13px;">
                    Ảnh JPG, PNG hoặc WebP, tối đa 5MB, rộng tối thiểu 400px. Nên dùng ảnh minh hoạ gần vuông
                    (khoảng 720&times;720px), PNG nền trong suốt sẽ đẹp nhất. Ảnh được thu nhỏ vừa khung, không bị cắt.
                    Để trống tiêu đề / mô tả nếu chỉ muốn hiện ảnh.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row">
            @foreach($slides as $slot => $slide)
                <div class="col-12 col-lg-4" id="slide-{{ $slot }}">
                    <div class="card ams-card">
                        <div class="card-header d-flex align-items-center justify-content-between" style="gap:8px;">
                            <h4 class="mb-0">Slide {{ $slot }}</h4>
                            @if($slide['is_custom'])
                                <span class="badge badge-primary">Đã tuỳ chỉnh</span>
                            @else
                                <span class="badge badge-light">Mặc định</span>
                            @endif
                        </div>

                        <div class="card-body">
                            {{-- Xem trước giống khung slider trong modal --}}
                            <div class="ams-preview">
                                <div class="ams-preview__image-wrap">
                                    <img src="{{ $slide['image'] }}" alt="Slide {{ $slot }}" id="ams-img-{{ $slot }}"
                                         data-original="{{ $slide['image'] }}">
                                </div>
                                <div class="ams-preview__title" id="ams-title-{{ $slot }}">{{ $slide['title'] }}</div>
                                <div class="ams-preview__subtitle" id="ams-subtitle-{{ $slot }}">{{ $slide['subtitle'] }}</div>
                            </div>

                            @if($slide['is_custom'] && $slide['updated_at'])
                                <div class="text-muted mt-2" style="font-size:12px;">
                                    Cập nhật {{ date('d/m/Y H:i', $slide['updated_at']) }}
                                    @if($slide['updated_by_name']) bởi {{ $slide['updated_by_name'] }} @endif
                                </div>
                            @endif

                            <form action="{{ getAdminPanelUrl('/auth-modal-slides/' . $slot) }}" method="POST"
                                  enctype="multipart/form-data" class="mt-3">
                                @csrf
                                <input type="hidden" name="slot" value="{{ $slot }}">

                                <div class="form-group">
                                    <label class="input-label">Ảnh</label>
                                    <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                                           class="form-control-file ams-file-input" data-preview="#ams-img-{{ $slot }}">
                                    <small class="form-text text-muted">Không chọn ảnh = giữ ảnh đang hiển thị.</small>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">Tiêu đề</label>
                                    <input type="text" name="title" maxlength="160" class="form-control ams-text-input"
                                           data-preview="#ams-title-{{ $slot }}"
                                           value="{{ (int) old('slot') === (int) $slot ? old('title') : $slide['title'] }}">
                                </div>

                                <div class="form-group">
                                    <label class="input-label">Mô tả</label>
                                    <input type="text" name="subtitle" maxlength="255" class="form-control ams-text-input"
                                           data-preview="#ams-subtitle-{{ $slot }}"
                                           value="{{ (int) old('slot') === (int) $slot ? old('subtitle') : $slide['subtitle'] }}">
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-1"></i> Lưu slide {{ $slot }}
                                </button>
                            </form>

                            @if($slide['is_custom'])
                                <form action="{{ getAdminPanelUrl('/auth-modal-slides/' . $slot . '/reset') }}" method="POST" class="mt-2"
                                      onsubmit="return confirm('Xoá nội dung đã tuỳ chỉnh và dùng lại slide mặc định?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-block">
                                        <i class="fas fa-undo mr-1"></i> Dùng lại mặc định
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .ams-card {
        height: calc(100% - 30px);
    }
    .ams-preview {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 300px;
        padding: 20px 18px;
        border: 1px dashed #d8cdf0;
        border-radius: 18px;
        background: #faf8ff;
        text-align: center;
    }
    .ams-preview__image-wrap {
        width: 78%;
        max-width: 240px;
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .ams-preview__image-wrap img {
        display: block;
        width: 100%;
        height: auto;
        max-height: 220px;
        object-fit: contain;
    }
    .ams-preview__title {
        margin-top: 10px;
        color: #1b2450;
        font-size: 18px;
        line-height: 1.25;
        font-weight: 800;
    }
    .ams-preview__subtitle {
        margin-top: 6px;
        color: #8da0c2;
        font-size: 13px;
        line-height: 1.4;
    }
    .ams-preview__title:empty,
    .ams-preview__subtitle:empty {
        display: none;
    }
</style>

<script>
    // Xem trước ảnh và chữ trước khi bấm lưu.
    document.querySelectorAll('.ams-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var img = document.querySelector(input.dataset.preview);
            if (!img) return;

            var file = input.files && input.files[0];
            img.src = file ? URL.createObjectURL(file) : img.dataset.original;
        });
    });

    document.querySelectorAll('.ams-text-input').forEach(function (input) {
        input.addEventListener('input', function () {
            var target = document.querySelector(input.dataset.preview);
            if (target) target.textContent = input.value.trim();
        });
    });
</script>
@endsection
