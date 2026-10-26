{{-- Bảng + phân trang. Được thay toàn bộ bằng AJAX khi lọc / chuyển trang.
     Cần: $requests (paginator), $pcRoute, $canViewAttempt. --}}
<div class="pc-results__head">
    <div class="pc-results__count">
        @if($requests->total())
            Hiển thị <strong>{{ $requests->firstItem() }}–{{ $requests->lastItem() }}</strong> trên <strong>{{ $requests->total() }}</strong> yêu cầu
        @else
            Không có yêu cầu nào
        @endif
    </div>
</div>

@if($requests->count())
    <div class="pc-table-scroll">
        <table class="pc-table">
            <thead>
                <tr>
                    <th style="min-width:200px;">Người gửi</th>
                    <th style="min-width:170px;">Liên hệ</th>
                    <th style="min-width:150px;">Khóa quan tâm</th>
                    <th style="min-width:190px;">Kết quả Placement</th>
                    <th class="text-center" style="width:80px;">Speaking</th>
                    <th style="width:100px;">Gửi lúc</th>
                    <th style="min-width:140px;">Trạng thái</th>
                    <th style="min-width:180px;">Ghi chú</th>
                    <th style="width:130px;" class="text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $item)
                    @include('admin.placement_consults.partials.row', ['item' => $item])
                @endforeach
            </tbody>
        </table>
    </div>

    @if($requests->lastPage() > 1)
        @php
            $current = $requests->currentPage();
            $last = $requests->lastPage();
            $pages = collect([1, $last, $current - 1, $current, $current + 1])
                ->filter(fn ($p) => $p >= 1 && $p <= $last)->unique()->sort()->values();
        @endphp
        <nav class="pc-pager" aria-label="Phân trang">
            <button type="button" class="pc-page" data-pc-page="{{ $current - 1 }}" @disabled($current <= 1) aria-label="Trang trước">
                <i class="fas fa-chevron-left"></i>
            </button>
            @foreach($pages as $idx => $p)
                @if($idx > 0 && $p - $pages[$idx - 1] > 1)
                    <span class="pc-page-gap">…</span>
                @endif
                <button type="button" class="pc-page {{ $p === $current ? 'is-active' : '' }}" data-pc-page="{{ $p }}">{{ $p }}</button>
            @endforeach
            <button type="button" class="pc-page" data-pc-page="{{ $current + 1 }}" @disabled($current >= $last) aria-label="Trang sau">
                <i class="fas fa-chevron-right"></i>
            </button>
        </nav>
    @endif
@else
    <div class="pc-empty">
        <div class="pc-empty__icon"><i class="far fa-folder-open"></i></div>
        <div class="pc-empty__title">Chưa có yêu cầu tư vấn nào khớp bộ lọc</div>
        <div class="pc-muted">Thử đổi điều kiện lọc hoặc bấm "Xoá lọc".</div>
    </div>
@endif
