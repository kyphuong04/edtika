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
                    Modal <strong>Đăng nhập / Đăng ký</strong> có một khung slider bên phải, tự chuyển qua tối đa 5 slide.
                    Mỗi slide gồm 1 ảnh, 1 tiêu đề và 1 dòng mô tả (không bắt buộc). Thay đổi ở đây áp dụng ngay cho mọi trang có modal.
                    Vị trí nào chưa tuỳ chỉnh và theme không có sẵn slide thì sẽ không hiển thị.
                </p>
                <p class="mb-0 text-muted" style="font-size:13px;">
                    Ảnh JPG, PNG hoặc WebP, tối đa 5MB, rộng tối thiểu 400px. Ảnh tải lên sẽ <strong>phủ kín khung slider</strong>,
                    nên dùng tỉ lệ <strong>4:5 (dọc)</strong>, ví dụ 1080&times;1350px. Ảnh khác tỉ lệ sẽ bị cắt bớt phần mép.
                    Nếu banner đã có chữ sẵn trong ảnh, hãy để trống tiêu đề / mô tả.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row">
            @foreach($slides as $slot => $slide)
                <div class="col-12 col-md-6 col-xl-4" id="slide-{{ $slot }}">
                    <div class="card ams-card">
                        <div class="card-header d-flex align-items-center justify-content-between" style="gap:8px;">
                            <h4 class="mb-0">Slide {{ $slot }}</h4>
                            @if($slide['is_custom'])
                                <span class="badge badge-primary">Đã tuỳ chỉnh</span>
                            @elseif($slide['is_shown'])
                                <span class="badge badge-light">Mặc định</span>
                            @else
                                <span class="badge badge-warning">Chưa hiển thị</span>
                            @endif
                        </div>

                        <div class="card-body">
                            {{-- Xem trước giống khung slider trong modal --}}
                            <div class="ams-preview {{ $slide['has_custom_image'] ? 'is-fill' : '' }} {{ $slide['is_shown'] ? '' : 'is-hidden-slot' }}" id="ams-preview-{{ $slot }}" data-hidden="{{ $slide['is_shown'] ? 0 : 1 }}">
                                <div class="ams-preview__image-wrap">
                                    <img src="{{ $slide['image'] }}" alt="Slide {{ $slot }}" id="ams-img-{{ $slot }}"
                                         data-original="{{ $slide['image'] }}">
                                </div>
                                <div class="ams-preview__title" id="ams-title-{{ $slot }}">{{ $slide['title'] }}</div>
                                <div class="ams-preview__subtitle" id="ams-subtitle-{{ $slot }}">{{ $slide['subtitle'] }}</div>
                            </div>

                            @unless($slide['is_shown'])
                                <div class="text-muted mt-2" style="font-size:12px;">
                                    Slide này chưa có nội dung nên đang bị ẩn khỏi modal. Tải ảnh lên để hiển thị.
                                </div>
                            @endunless

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
                                           class="form-control-file ams-file-input" data-preview="#ams-img-{{ $slot }}"
                                           data-frame="#ams-preview-{{ $slot }}" data-was-fill="{{ $slide['has_custom_image'] ? 1 : 0 }}">
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
    /* Giống khung slider trong modal: tỉ lệ 4:5, ảnh phủ kín */
    .ams-preview.is-fill {
        position: relative;
        justify-content: flex-end;
        min-height: 0;
        aspect-ratio: 4 / 5;
        padding: 0 0 16px;
        overflow: hidden;
        border-style: solid;
    }
    .ams-preview.is-fill .ams-preview__image-wrap {
        position: absolute;
        inset: 0;
        width: 100%;
        max-width: none;
        min-height: 0;
    }
    .ams-preview.is-fill .ams-preview__image-wrap img {
        width: 100%;
        height: 100%;
        max-height: none;
        object-fit: cover;
    }
    .ams-preview.is-fill .ams-preview__title,
    .ams-preview.is-fill .ams-preview__subtitle {
        position: relative;
        z-index: 1;
        color: #fff;
        padding: 0 14px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.45);
    }
    .ams-preview.is-hidden-slot {
        opacity: 0.45;
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

            // Ảnh mới tải lên sẽ phủ kín khung -> xem trước đúng như trong modal.
            var frame = document.querySelector(input.dataset.frame);
            if (frame) {
                frame.classList.toggle('is-fill', !!file || input.dataset.wasFill === '1');
                frame.classList.toggle('is-hidden-slot', !file && frame.dataset.hidden === '1');
            }
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
