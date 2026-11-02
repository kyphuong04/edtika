@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Banner chia sẻ bài làm</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">{{ trans('admin/main.dashboard') }}</a></div>
            <div class="breadcrumb-item">IELTS Tests</div>
            <div class="breadcrumb-item">Banner chia sẻ bài làm</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body">
                <p class="mb-2">
                    Khi học viên bấm <strong>Chia sẻ bài làm</strong>, hệ thống chọn ngẫu nhiên 1 ảnh <strong>đang bật</strong>
                    theo mức điểm (cùng ngưỡng với trang kết quả). Ảnh này hiện trong khung xem trước của Facebook, Threads, LinkedIn…
                    Mức chưa có ảnh nào bật sẽ dùng ảnh mặc định.
                </p>
                <p class="mb-0 text-muted" style="font-size:13px;">
                    Ảnh vuông 1:1 (khuyến nghị 1080&times;1080px), JPG / PNG / WebP, tối đa 5MB mỗi ảnh, chọn được nhiều ảnh một lần.
                    Facebook có thể cắt ảnh còn khung ngang ~1.91:1 ở một số vị trí — nên đặt chữ và nội dung chính ở <strong>giữa ảnh</strong>.
                    Link đã chia sẻ trước đó vẫn giữ ảnh cũ trên mạng xã hội cho tới khi nền tảng quét lại.
                </p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        @foreach($tiers as $tier => $info)
            <div class="card sb-card">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
                    <div>
                        <h4 class="mb-0">{{ $info['label'] }}</h4>
                        <div class="text-muted" style="font-size:13px;">{{ $info['rule'] }}</div>
                    </div>
                    @if($info['active_count'] > 0)
                        <span class="badge badge-primary">{{ $info['active_count'] }} ảnh đang bật</span>
                    @else
                        <span class="badge badge-light">Đang dùng ảnh mặc định</span>
                    @endif
                </div>

                <div class="card-body">
                    <div class="sb-grid">
                        @if($info['active_count'] === 0)
                            <figure class="sb-item is-default">
                                <div class="sb-thumb"><img src="{{ $info['default_url'] }}" alt="{{ $info['alt'] }}" loading="lazy"></div>
                                <figcaption>
                                    <span class="badge badge-light">Ảnh mặc định</span>
                                    <small class="text-muted d-block mt-1">Tự ẩn khi có ảnh được bật.</small>
                                </figcaption>
                            </figure>
                        @endif

                        @foreach($info['items'] as $item)
                            <figure class="sb-item {{ $item['is_active'] ? '' : 'is-off' }}">
                                <div class="sb-thumb">
                                    <a href="{{ $item['url'] }}" target="_blank" rel="noopener">
                                        <img src="{{ $item['url'] }}" alt="{{ $item['title'] ?? $info['alt'] }}" loading="lazy">
                                    </a>
                                </div>
                                <figcaption>
                                    <div class="d-flex align-items-center justify-content-between" style="gap:6px;">
                                        @if($item['is_active'])
                                            <span class="badge badge-success">Đang bật</span>
                                        @else
                                            <span class="badge badge-secondary">Đã tắt</span>
                                        @endif
                                        @if($item['width'])
                                            <small class="text-muted">{{ $item['width'] }}&times;{{ $item['height'] }}</small>
                                        @endif
                                    </div>
                                    @if($item['title'])
                                        <div class="sb-title" title="{{ $item['title'] }}">{{ $item['title'] }}</div>
                                    @endif
                                    @if($item['created_at'])
                                        <small class="text-muted d-block">
                                            {{ date('d/m/Y', $item['created_at']) }}@if($item['creator_name']) · {{ $item['creator_name'] }}@endif
                                        </small>
                                    @endif
                                    <div class="d-flex mt-2" style="gap:6px;">
                                        <form action="{{ route('admin.ielts_share_banners.toggle', $item['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $item['is_active'] ? 'btn-outline-secondary' : 'btn-outline-primary' }}">
                                                <i class="fas {{ $item['is_active'] ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>{{ $item['is_active'] ? 'Tắt' : 'Bật' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.ielts_share_banners.delete', $item['id']) }}" method="POST"
                                              onsubmit="return confirm('Xoá hẳn ảnh này?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </figcaption>
                            </figure>
                        @endforeach
                    </div>

                    <form action="{{ route('admin.ielts_share_banners.upload', $tier) }}" method="POST"
                          enctype="multipart/form-data" class="sb-upload mt-3">
                        @csrf
                        <div class="sb-upload-row">
                            <input type="file" name="images[]" accept="image/png,image/jpeg,image/webp" multiple required
                                   class="form-control-file sb-file-input" data-previews="#sb-new-{{ $tier }}">
                            <input type="text" name="title" maxlength="120" class="form-control" style="max-width:260px;"
                                   placeholder="Ghi chú (tuỳ chọn) — VD: Mãi keo">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-1"></i> Thêm ảnh
                            </button>
                        </div>
                        <div class="sb-new" id="sb-new-{{ $tier }}"></div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>

<style>
    .sb-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }
    .sb-item {
        margin: 0;
        padding: 10px;
        border: 1px solid #ebe7f5;
        border-radius: 12px;
        background: #fff;
    }
    .sb-item.is-off .sb-thumb img { filter: grayscale(1); opacity: .55; }
    .sb-item.is-default { border-style: dashed; background: #faf8ff; }
    .sb-thumb {
        aspect-ratio: 1 / 1;
        border-radius: 8px;
        overflow: hidden;
        background: #f5f3fb;
    }
    .sb-thumb img { display: block; width: 100%; height: 100%; object-fit: cover; }
    .sb-item figcaption { margin-top: 8px; font-size: 13px; }
    .sb-title { margin-top: 4px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .sb-upload-row { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
    .sb-new { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .sb-new figure { margin: 0; width: 84px; text-align: center; font-size: 11px; }
    .sb-new img { width: 84px; height: 84px; object-fit: cover; border-radius: 8px; border: 2px solid #e4dafb; }
    .sb-new figure.is-bad img { border-color: #e11d48; }
    .sb-new figure.is-bad span { color: #e11d48; }
</style>

<script>
    // Xem trước các ảnh vừa chọn + cảnh báo ngay ảnh không vuông.
    document.querySelectorAll('.sb-file-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var box = document.querySelector(input.dataset.previews);
            if (!box) return;
            box.innerHTML = '';

            Array.prototype.forEach.call(input.files || [], function (file) {
                var fig = document.createElement('figure');
                var img = document.createElement('img');
                var cap = document.createElement('span');
                img.onload = function () {
                    var w = img.naturalWidth, h = img.naturalHeight;
                    var ok = h > 0 && Math.abs(w / h - 1) <= 0.03 && w >= 600;
                    cap.textContent = w + '×' + h + (ok ? '' : ' — cần 1:1');
                    if (!ok) fig.classList.add('is-bad');
                };
                img.src = URL.createObjectURL(file);
                fig.appendChild(img);
                fig.appendChild(cap);
                box.appendChild(fig);
            });
        });
    });
</script>
@endsection
