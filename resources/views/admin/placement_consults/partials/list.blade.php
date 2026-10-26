{{--
    Danh sách "Yêu cầu tư vấn" (form gửi từ trang kết quả Placement Test).
    Dùng chung cho:
      - /admin/placement-consults  (Manager, CEO)  -> admin.placement_consults.index
      - /panel/placement-consults  (Admin)          -> design_1.panel.placement_consults.index
    Biến cần truyền: $pcRoute (tiền tố tên route), $pcModalStack (stack chứa modal),
    cùng dữ liệu từ PlacementConsultRequestController::index().

    Mọi thao tác (lọc, tìm, phân trang, cập nhật, xoá) chạy bằng AJAX — không tải lại trang.
    CSS bọc trong .pc-wrap / .pc-modal và dùng !important ở các chỗ theme admin/panel hay ghi đè.
--}}
@push('styles_top')
<style>
.pc-wrap {
    --pc-primary: #511D99; --pc-primary-dark: #3b1475; --pc-accent: #7c3aed;
    --pc-soft: #f5f1ff; --pc-border: #ebe5f6; --pc-text: #1f1235; --pc-muted: #8a83a0;
    display: flex; flex-direction: column; gap: 20px; color: var(--pc-text);
}
.pc-wrap *, .pc-modal * { box-sizing: border-box; }
.pc-wrap input, .pc-wrap select, .pc-wrap button, .pc-modal textarea, .pc-modal button { font-family: inherit !important; }
.pc-card { background: #fff !important; border: 1px solid var(--pc-border); border-radius: 16px; box-shadow: 0 4px 18px rgba(81, 29, 153, .05); }

/* ── Thẻ thống kê ── */
.pc-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
.pc-stat {
    display: flex; align-items: center; gap: 14px; width: 100%; text-align: left;
    padding: 18px 20px; cursor: pointer; transition: border-color .15s, box-shadow .15s, transform .15s;
    font: inherit; color: inherit;
}
.pc-stat:hover { border-color: #cdbdf3; transform: translateY(-1px); }
.pc-stat.is-active { border: 2px solid var(--pc-primary); padding: 17px 19px; box-shadow: 0 8px 22px rgba(81, 29, 153, .12); }
.pc-stat__icon { width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.pc-stat__icon--total { background: #efe9ff; color: var(--pc-primary); }
.pc-stat__icon--new { background: #fee2e2; color: #dc2626; }
.pc-stat__icon--contacted { background: #fef3c7; color: #b45309; }
.pc-stat__icon--closed { background: #dcfce7; color: #15803d; }
.pc-stat__num { font-size: 24px; font-weight: 800; line-height: 1.1; color: var(--pc-text); }
.pc-stat__label { font-size: 13px; color: var(--pc-muted); margin-top: 2px; }

/* ── Bộ lọc ── */
.pc-filters { padding: 20px 24px; }
.pc-filters__grid { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
.pc-field { display: flex; flex-direction: column; gap: 6px; flex: 1 1 150px; min-width: 0; }
.pc-field--search { flex: 2 1 260px; }
.pc-field--level { flex: 0 1 120px; min-width: 110px; }
.pc-field label { font-size: 13px; font-weight: 600; color: #4b4560; margin: 0 !important; }
.pc-control { position: relative; }
.pc-control > i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--pc-muted); font-size: 13px; pointer-events: none; }
.pc-wrap .pc-input {
    width: 100%; height: 42px !important; padding: 0 12px !important; font-size: 14px !important;
    border: 1px solid #e3dcf1 !important; border-radius: 10px !important; background: #fff !important;
    color: var(--pc-text) !important; box-shadow: none !important; outline: none;
}
.pc-wrap .pc-control--icon .pc-input { padding-left: 38px !important; }
.pc-wrap .pc-input:focus { border-color: var(--pc-accent) !important; box-shadow: 0 0 0 3px rgba(124, 58, 237, .12) !important; }
.pc-filters__actions { display: flex; align-items: center; gap: 10px; flex: 0 0 auto; height: 42px; }

/* ── Nút ── */
.pc-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    height: 42px; padding: 0 18px; border-radius: 10px; font-size: 14px; font-weight: 600;
    border: 1px solid transparent; cursor: pointer; white-space: nowrap; transition: background .15s, border-color .15s;
}
.pc-btn--ghost { background: #fff; border-color: #ddd3f2; color: var(--pc-primary); }
.pc-btn--ghost:hover { background: var(--pc-soft); }
.pc-btn--primary { background: var(--pc-primary); color: #fff; }
.pc-btn--primary:hover { background: var(--pc-primary-dark); }
.pc-btn[disabled] { opacity: .6; cursor: not-allowed; }
.pc-spinner { width: 18px; height: 18px; border: 2px solid #ddd3f2; border-top-color: var(--pc-primary); border-radius: 50%; animation: pcSpin .7s linear infinite; display: none; }
.is-loading .pc-spinner, .pc-spinner.is-visible { display: inline-block; }
@keyframes pcSpin { to { transform: rotate(360deg); } }

/* ── Bảng ── */
.pc-results { overflow: hidden; transition: opacity .15s; }
.pc-results.is-loading { opacity: .55; pointer-events: none; }
.pc-results__head { display: flex; align-items: center; justify-content: space-between; padding: 16px 24px; border-bottom: 1px solid var(--pc-border); }
.pc-results__count { font-size: 14px; color: var(--pc-muted); }
.pc-results__count strong { color: var(--pc-text); }
.pc-table-scroll { overflow-x: auto; }
.pc-wrap .pc-table { width: 100%; min-width: 1150px; border-collapse: separate; border-spacing: 0; margin: 0; background: transparent !important; }
.pc-wrap .pc-table th {
    background: #faf8ff !important; color: #6b6386 !important; font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .4px; padding: 12px 16px !important; border: 0 !important;
    border-bottom: 1px solid var(--pc-border) !important; white-space: nowrap; text-align: left;
}
.pc-wrap .pc-table th.text-center { text-align: center; }
.pc-wrap .pc-table th.text-right { text-align: right; }
.pc-wrap .pc-table td {
    padding: 16px !important; border: 0 !important; border-bottom: 1px solid #f2eef9 !important;
    vertical-align: middle !important; font-size: 14px; background: transparent !important; text-align: left;
}
.pc-wrap .pc-table td.text-center { text-align: center; }
.pc-wrap .pc-table tbody tr:last-child td { border-bottom: 0 !important; }
.pc-wrap .pc-table tbody tr { transition: background .2s, opacity .3s; }
.pc-wrap .pc-table tbody tr:hover td { background: #fcfbff !important; }
.pc-wrap .pc-table tbody tr.is-new td:first-child { box-shadow: inset 3px 0 0 #ef4444; }
.pc-wrap .pc-table tbody tr.is-flash td { background: #f3edff !important; }
.pc-wrap .pc-table tbody tr.is-removing { opacity: 0; }

.pc-person { display: flex; align-items: center; gap: 12px; }
.pc-avatar {
    width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, #ede4ff, #d8c6ff); color: var(--pc-primary);
    font-size: 13px; font-weight: 800; display: flex; align-items: center; justify-content: center;
}
.pc-person__text { min-width: 0; }
.pc-name { font-weight: 700; color: var(--pc-text); line-height: 1.3; }
.pc-muted { font-size: 12px; color: var(--pc-muted); line-height: 1.4; }
.pc-phone { display: inline-flex; align-items: center; gap: 6px; font-weight: 600; color: var(--pc-primary) !important; white-space: nowrap; }
.pc-phone i { font-size: 12px; }
.pc-email { display: block; font-size: 12px; color: var(--pc-muted) !important; margin-top: 3px; word-break: break-all; }
.pc-course { display: inline-block; font-weight: 600; color: var(--pc-primary-dark); background: var(--pc-soft); padding: 4px 10px; border-radius: 8px; font-size: 13px; white-space: nowrap; }
.pc-result { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; }
.pc-scores { display: flex; flex-wrap: wrap; gap: 4px; }
.pc-wrap .pc-chip { display: inline-block; font-size: 11.5px; font-weight: 600; padding: 3px 8px; border-radius: 6px; line-height: 1.3; white-space: nowrap; }
.pc-wrap .pc-chip--level { background: #ede4ff !important; color: var(--pc-primary) !important; font-size: 12.5px; font-weight: 800; padding: 3px 10px; }
.pc-wrap .pc-chip--score { background: #f3f2f7 !important; color: #4b4560 !important; }
.pc-wrap .pc-chip--score.is-ref { background: #fff4d6 !important; color: #8a5a00 !important; }
.pc-date { font-weight: 600; white-space: nowrap; }
.pc-wrap .pc-status { display: inline-block; font-size: 12px; font-weight: 700; padding: 4px 12px; border-radius: 999px; white-space: nowrap; }
.pc-wrap .pc-status--new { background: #fee2e2 !important; color: #b91c1c !important; }
.pc-wrap .pc-status--contacted { background: #fef3c7 !important; color: #92400e !important; }
.pc-wrap .pc-status--closed { background: #dcfce7 !important; color: #166534 !important; }
.pc-handled { margin-top: 6px; }
.pc-note { font-size: 13px; color: #4b4560; max-width: 240px; white-space: pre-line; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; }

.pc-actions { display: flex; justify-content: flex-end; gap: 8px; }
.pc-icon-btn {
    width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;
    font-size: 13px; cursor: pointer; border: 1px solid transparent; transition: background .15s, color .15s, border-color .15s;
    text-decoration: none !important; padding: 0;
}
.pc-icon-btn--primary { background: var(--pc-primary); color: #fff !important; }
.pc-icon-btn--primary:hover { background: var(--pc-primary-dark); }
.pc-icon-btn--soft { background: var(--pc-soft); color: var(--pc-primary) !important; border-color: #e4dbf7; }
.pc-icon-btn--soft:hover { background: #e9e0ff; }
.pc-icon-btn--soft.is-playing { background: var(--pc-primary); color: #fff !important; }
.pc-icon-btn--danger { background: #fff; color: #dc2626 !important; border-color: #fbd0d0; }
.pc-icon-btn--danger:hover { background: #fef2f2; }

.pc-pager { display: flex; justify-content: center; align-items: center; gap: 6px; padding: 16px 24px; border-top: 1px solid var(--pc-border); }
.pc-page { min-width: 36px; height: 36px; padding: 0 10px; border-radius: 10px; border: 1px solid #e4dbf7; background: #fff; color: var(--pc-primary); font-weight: 600; font-size: 14px; cursor: pointer; }
.pc-page:hover:not([disabled]) { background: var(--pc-soft); }
.pc-page.is-active { background: var(--pc-primary); border-color: var(--pc-primary); color: #fff; }
.pc-page[disabled] { opacity: .4; cursor: not-allowed; }
.pc-page-gap { color: var(--pc-muted); padding: 0 4px; }

.pc-empty { text-align: center; padding: 56px 24px; }
.pc-empty__icon { width: 64px; height: 64px; border-radius: 50%; background: var(--pc-soft); color: var(--pc-primary); font-size: 26px; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; }
.pc-empty__title { font-weight: 700; margin-bottom: 4px; }

/* ── Modal ── */
.pc-modal .modal-content { border: 0; border-radius: 18px; overflow: hidden; }
.pc-modal .modal-header { padding: 20px 24px; border-bottom: 1px solid #f0ebf8; align-items: flex-start; }
.pc-modal__title { font-size: 18px; font-weight: 800; color: #1f1235; margin: 0; }
.pc-modal__meta { font-size: 13px; color: #8a83a0; margin-top: 4px; }
.pc-modal .modal-body { padding: 22px 24px; display: flex; flex-direction: column; gap: 18px; }
.pc-modal .modal-footer { padding: 16px 24px; border-top: 1px solid #f0ebf8; gap: 10px; }
.pc-modal .modal-footer > * { margin: 0; }
.pc-modal label { font-size: 13px; font-weight: 600; color: #4b4560; margin-bottom: 8px; display: block; }
.pc-status-pick { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.pc-status-pick input { position: absolute; opacity: 0; pointer-events: none; }
.pc-status-pick label {
    margin: 0; padding: 12px 10px; border: 1px solid #e3dcf1; border-radius: 12px; text-align: center;
    cursor: pointer; font-weight: 700; color: #4b4560; transition: all .15s;
}
.pc-status-pick input:checked + label { border-color: #511D99; background: #f5f1ff; color: #511D99; box-shadow: 0 0 0 3px rgba(81, 29, 153, .1); }
.pc-status-pick input:focus-visible + label { outline: 2px solid #7c3aed; }
.pc-modal textarea {
    width: 100%; border: 1px solid #e3dcf1 !important; border-radius: 12px !important; padding: 12px 14px !important;
    font-size: 14px; resize: vertical; min-height: 120px; outline: none; box-shadow: none !important;
}
.pc-modal textarea:focus { border-color: #7c3aed !important; box-shadow: 0 0 0 3px rgba(124, 58, 237, .12) !important; }
.pc-modal__hint { font-size: 12px; color: #8a83a0; margin-top: 6px; }
.pc-modal__error { font-size: 13px; color: #dc2626; }
.pc-modal__close { background: none; border: 0; font-size: 26px; line-height: 1; color: #8a83a0; padding: 0 4px; cursor: pointer; margin-left: auto; }
.pc-modal__close:hover { color: #1f1235; }

/* Toast dự phòng khi layout không có showToast() */
.pc-toast { position: fixed; right: 24px; bottom: 24px; z-index: 2000; background: #1f1235; color: #fff; padding: 12px 18px; border-radius: 12px; font-size: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.2); opacity: 0; transform: translateY(10px); transition: all .2s; max-width: 360px; }
.pc-toast.is-visible { opacity: 1; transform: none; }
.pc-toast--error { background: #b91c1c; }

@media (max-width: 991px) {
    .pc-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 575px) {
    .pc-stat { padding: 14px; }
    .pc-stat__icon { width: 38px; height: 38px; font-size: 15px; }
    .pc-stat__num { font-size: 20px; }
    .pc-filters { padding: 16px; }
    .pc-field, .pc-field--search, .pc-field--level { flex: 1 1 100%; }
    .pc-results__head { padding: 14px 16px; }
}
</style>
@endpush

<div class="pc-wrap" data-pc-root data-index-url="{{ route($pcRoute . '.index') }}">

    {{-- Thống kê nhanh — bấm để lọc theo trạng thái --}}
    @php
        $currentStatus = (string) request('status');
        $statCards = [
            ['key' => 'total',     'status' => '',          'label' => 'Tổng số yêu cầu',  'icon' => 'fa-inbox'],
            ['key' => 'new',       'status' => 'new',       'label' => 'Mới — chưa xử lý', 'icon' => 'fa-bell'],
            ['key' => 'contacted', 'status' => 'contacted', 'label' => 'Đã liên hệ',       'icon' => 'fa-phone-alt'],
            ['key' => 'closed',    'status' => 'closed',    'label' => 'Đã đóng',          'icon' => 'fa-check-circle'],
        ];
    @endphp
    <div class="pc-stats">
        @foreach($statCards as $card)
            <button type="button" class="pc-card pc-stat {{ $currentStatus === $card['status'] ? 'is-active' : '' }}" data-pc-stat-filter="{{ $card['status'] }}">
                <span class="pc-stat__icon pc-stat__icon--{{ $card['key'] }}"><i class="fas {{ $card['icon'] }}"></i></span>
                <span>
                    <span class="pc-stat__num d-block" data-pc-stat="{{ $card['key'] }}">{{ $stats[$card['key']] }}</span>
                    <span class="pc-stat__label d-block">{{ $card['label'] }}</span>
                </span>
            </button>
        @endforeach
    </div>

    {{-- Bộ lọc — tự áp dụng khi đổi giá trị, không cần bấm nút --}}
    <form class="pc-card pc-filters" data-pc-filters autocomplete="off">
        <div class="pc-filters__grid">
            <div class="pc-field pc-field--search">
                <label for="pcSearch">Tìm kiếm</label>
                <div class="pc-control pc-control--icon">
                    <i class="fas fa-search"></i>
                    <input id="pcSearch" type="search" name="search" class="pc-input" value="{{ request('search') }}" placeholder="Họ tên, SĐT hoặc email">
                </div>
            </div>
            <div class="pc-field">
                <label for="pcStatus">Trạng thái</label>
                <select id="pcStatus" name="status" class="pc-input">
                    <option value="">Tất cả</option>
                    @foreach($statuses as $key => [$label])
                        <option value="{{ $key }}" @selected($currentStatus === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pc-field">
                <label for="pcCourse">Khóa quan tâm</label>
                <select id="pcCourse" name="course" class="pc-input">
                    <option value="">Tất cả</option>
                    @foreach($courseOptions as $key => $title)
                        <option value="{{ $key }}" @selected(request('course') === $key)>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pc-field pc-field--level">
                <label for="pcLevel">Level</label>
                <select id="pcLevel" name="level" class="pc-input">
                    <option value="">Tất cả</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl }}" @selected(request('level') === $lvl)>{{ $lvl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="pc-field">
                <label for="pcFrom">Từ ngày</label>
                <input id="pcFrom" type="date" name="from" class="pc-input" value="{{ request('from') }}">
            </div>
            <div class="pc-field">
                <label for="pcTo">Đến ngày</label>
                <input id="pcTo" type="date" name="to" class="pc-input" value="{{ request('to') }}">
            </div>
            <div class="pc-filters__actions">
                <span class="pc-spinner" data-pc-filter-spinner aria-hidden="true"></span>
                <button type="button" class="pc-btn pc-btn--ghost" data-pc-clear><i class="fas fa-undo-alt"></i>Xoá lọc</button>
            </div>
        </div>
    </form>

    {{-- Bảng kết quả — được thay bằng AJAX --}}
    <div class="pc-card pc-results" data-pc-results>
        @include('admin.placement_consults.partials.results')
    </div>
</div>

@push($pcModalStack)
{{-- Modal cập nhật trạng thái / ghi chú (dùng chung cho mọi dòng) --}}
<div class="modal fade pc-modal" id="pcEditModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form method="POST" class="modal-content" id="pcEditForm" novalidate>
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <div class="modal-header">
                <div>
                    <h5 class="pc-modal__title">Xử lý yêu cầu — <span id="pcEditName"></span></h5>
                    <div class="pc-modal__meta" id="pcEditMeta"></div>
                </div>
                <button type="button" class="pc-modal__close" data-dismiss="modal" aria-label="Đóng">&times;</button>
            </div>
            <div class="modal-body">
                <div>
                    <label>Trạng thái</label>
                    <div class="pc-status-pick">
                        @foreach($statuses as $key => [$label])
                            <div>
                                <input type="radio" name="status" id="pcStatusPick-{{ $key }}" value="{{ $key }}">
                                <label for="pcStatusPick-{{ $key }}">{{ $label }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label for="pcEditNote">Ghi chú nội bộ</label>
                    <textarea name="admin_note" id="pcEditNote" rows="5" maxlength="2000"
                              placeholder="VD: Đã gọi lúc 15h, học viên hẹn tư vấn lại cuối tuần..."></textarea>
                    <div class="pc-modal__hint">Chỉ hiển thị trong trang quản trị, học viên không thấy.</div>
                </div>
                <div class="pc-modal__error" id="pcEditError" hidden></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="pc-btn pc-btn--ghost" data-dismiss="modal">Huỷ</button>
                <button type="submit" class="pc-btn pc-btn--primary" id="pcEditSubmit">
                    <span class="pc-spinner" style="border-color:rgba(255,255,255,.4);border-top-color:#fff;"></span>
                    <i class="fas fa-save" data-icon></i><span data-label>Lưu thay đổi</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts_bottom')
<script>
(function () {
    'use strict';

    var root = document.querySelector('[data-pc-root]');
    if (!root) return;

    var indexUrl   = root.dataset.indexUrl;
    var filters    = root.querySelector('[data-pc-filters]');
    var results    = root.querySelector('[data-pc-results]');
    var spinner    = root.querySelector('[data-pc-filter-spinner]');
    var editForm   = document.getElementById('pcEditForm');
    var csrfToken  = editForm ? editForm.querySelector('input[name="_token"]').value : '';
    var currentReq = null;

    /* ───────── Tiện ích ───────── */
    function toast(status, title, msg) {
        if (typeof window.showToast === 'function') {
            window.showToast(status, title, msg);
            return;
        }
        var el = document.createElement('div');
        el.className = 'pc-toast' + (status === 'error' ? ' pc-toast--error' : '');
        el.textContent = (title ? title + ': ' : '') + msg;
        document.body.appendChild(el);
        requestAnimationFrame(function () { el.classList.add('is-visible'); });
        setTimeout(function () { el.classList.remove('is-visible'); setTimeout(function () { el.remove(); }, 250); }, 3200);
    }

    function currentParams() {
        var params = new URLSearchParams();
        new FormData(filters).forEach(function (value, key) {
            value = String(value).trim();
            if (value !== '') params.set(key, value);
        });
        return params;
    }

    function updateStats(stats) {
        if (!stats) return;
        Object.keys(stats).forEach(function (key) {
            var el = root.querySelector('[data-pc-stat="' + key + '"]');
            if (el) el.textContent = stats[key];
        });
        // Badge "Yêu cầu tư vấn" ở sidebar (cả /admin và /panel)
        document.querySelectorAll('[data-pc-sidebar-badge]').forEach(function (badge) {
            badge.textContent = stats.new;
            badge.style.display = stats.new > 0 ? '' : 'none';
        });
    }

    function syncActiveStat() {
        var status = filters.elements.status.value;
        root.querySelectorAll('[data-pc-stat-filter]').forEach(function (card) {
            card.classList.toggle('is-active', card.dataset.pcStatFilter === status);
        });
    }

    function sendJson(url, method, body) {
        if (!body) body = new FormData();
        body.set('_token', csrfToken);
        body.set('_method', method);
        return fetch(url, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: body,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (data) {
                return { status: res.status, data: data };
            });
        });
    }

    function errorMessage(r) {
        if (r.status === 419) return 'Phiên làm việc đã hết hạn, vui lòng tải lại trang.';
        if (r.status === 403) return 'Bạn không có quyền thực hiện thao tác này.';
        if (r.status === 404) return 'Yêu cầu này không còn tồn tại.';
        if (r.status === 422 && r.data.errors) {
            var first = Object.keys(r.data.errors)[0];
            return r.data.errors[first][0];
        }
        return (r.data && r.data.message) || 'Có lỗi xảy ra, vui lòng thử lại.';
    }

    /* ───────── Tải danh sách (lọc / phân trang) ───────── */
    function load(page, pushHistory) {
        var params = currentParams();
        if (page && page > 1) params.set('page', page);
        var qs = params.toString();

        if (currentReq) currentReq.abort();
        currentReq = new AbortController();

        results.classList.add('is-loading');
        spinner.classList.add('is-visible');

        return fetch(indexUrl + (qs ? '?' + qs : ''), {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
            signal: currentReq.signal
        }).then(function (res) {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        }).then(function (data) {
            results.innerHTML = data.html;
            updateStats(data.stats);
            syncActiveStat();
            if (pushHistory !== false) {
                history.pushState({ pc: true }, '', indexUrl + (qs ? '?' + qs : ''));
            }
        }).catch(function (err) {
            if (err.name === 'AbortError') return;
            toast('error', 'Lỗi', 'Không tải được danh sách, vui lòng thử lại.');
        }).finally(function () {
            results.classList.remove('is-loading');
            spinner.classList.remove('is-visible');
        });
    }

    function currentPage() {
        var active = results.querySelector('.pc-page.is-active');
        return active ? parseInt(active.dataset.pcPage, 10) : 1;
    }

    var searchTimer = null;
    filters.addEventListener('input', function (e) {
        if (e.target.name !== 'search') return;
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () { load(1); }, 400);
    });
    filters.addEventListener('change', function (e) {
        if (e.target.name === 'search') return;
        load(1);
    });
    filters.addEventListener('submit', function (e) {
        e.preventDefault();
        clearTimeout(searchTimer);
        load(1);
    });

    root.querySelector('[data-pc-clear]').addEventListener('click', function () {
        filters.reset();
        Array.prototype.forEach.call(filters.elements, function (el) {
            if (el.name) el.value = '';
        });
        load(1);
    });

    root.querySelectorAll('[data-pc-stat-filter]').forEach(function (card) {
        card.addEventListener('click', function () {
            filters.elements.status.value = card.dataset.pcStatFilter;
            load(1);
        });
    });

    // Nút Back / Forward của trình duyệt
    window.addEventListener('popstate', function () {
        var params = new URLSearchParams(location.search);
        Array.prototype.forEach.call(filters.elements, function (el) {
            if (el.name) el.value = params.get(el.name) || '';
        });
        load(parseInt(params.get('page') || '1', 10), false);
    });

    /* ───────── Nghe Speaking: 1 player dùng chung ───────── */
    var player = new Audio();
    var playingBtn = null;
    function setPlaying(btn, on) {
        if (!btn) return;
        btn.classList.toggle('is-playing', on);
        btn.innerHTML = on ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
    }
    player.addEventListener('pause', function () { setPlaying(playingBtn, false); });
    player.addEventListener('ended', function () { setPlaying(playingBtn, false); });
    player.addEventListener('play', function () { setPlaying(playingBtn, true); });

    /* ───────── Click trong bảng (ủy quyền sự kiện vì bảng được thay bằng AJAX) ───────── */
    results.addEventListener('click', function (e) {
        var pageBtn = e.target.closest('[data-pc-page]');
        if (pageBtn && !pageBtn.disabled) {
            load(parseInt(pageBtn.dataset.pcPage, 10));
            results.scrollIntoView({ behavior: 'smooth', block: 'start' });
            return;
        }

        var playBtn = e.target.closest('[data-pc-play]');
        if (playBtn) {
            if (playingBtn === playBtn && !player.paused) { player.pause(); return; }
            setPlaying(playingBtn, false);
            playingBtn = playBtn;
            player.src = playBtn.dataset.pcPlay;
            player.play().catch(function () {
                setPlaying(playBtn, false);
                toast('error', 'Lỗi', 'Không phát được file ghi âm này.');
            });
            return;
        }

        var editBtn = e.target.closest('[data-pc-edit]');
        if (editBtn) { openEdit(editBtn); return; }

        var delBtn = e.target.closest('[data-pc-delete]');
        if (delBtn) { removeRow(delBtn); }
    });

    /* ───────── Cập nhật trạng thái / ghi chú ───────── */
    var editError = document.getElementById('pcEditError');
    var editSubmit = document.getElementById('pcEditSubmit');

    function setSaving(on) {
        editSubmit.disabled = on;
        editSubmit.querySelector('.pc-spinner').classList.toggle('is-visible', on);
        editSubmit.querySelector('[data-icon]').style.display = on ? 'none' : '';
        editSubmit.querySelector('[data-label]').textContent = on ? 'Đang lưu...' : 'Lưu thay đổi';
    }

    function openEdit(btn) {
        editForm.action = btn.dataset.action;
        document.getElementById('pcEditName').textContent = btn.dataset.name;
        document.getElementById('pcEditMeta').textContent = btn.dataset.meta || '';
        document.getElementById('pcEditNote').value = btn.dataset.note || '';
        // Yêu cầu "Mới" thường được mở ra để đánh dấu đã liên hệ -> chọn sẵn.
        var status = btn.dataset.status === 'new' ? 'contacted' : btn.dataset.status;
        var radio = document.getElementById('pcStatusPick-' + status);
        if (radio) radio.checked = true;
        editError.hidden = true;
        setSaving(false);
        $('#pcEditModal').modal('show');
    }

    editForm.addEventListener('submit', function (e) {
        e.preventDefault();
        editError.hidden = true;
        setSaving(true);

        var body = new FormData(editForm);
        var newStatus = body.get('status');

        sendJson(editForm.action, 'PATCH', body).then(function (r) {
            setSaving(false);
            if (r.status !== 200 || !r.data.ok) {
                editError.textContent = errorMessage(r);
                editError.hidden = false;
                return;
            }

            $('#pcEditModal').modal('hide');
            updateStats(r.data.stats);
            toast('success', 'Đã cập nhật', r.data.message);

            var id = editForm.action.split('/').pop();
            var oldRow = results.querySelector('[data-pc-row="' + id + '"]');
            var statusFilter = filters.elements.status.value;

            // Đang lọc theo trạng thái khác -> dòng không còn thuộc danh sách này
            if (oldRow && statusFilter && statusFilter !== newStatus) {
                fadeOutRow(oldRow);
                return;
            }

            if (oldRow && r.data.row_html) {
                var tpl = document.createElement('tbody');
                tpl.innerHTML = r.data.row_html.trim();
                var newRow = tpl.firstElementChild;
                oldRow.replaceWith(newRow);
                newRow.classList.add('is-flash');
                setTimeout(function () { newRow.classList.remove('is-flash'); }, 1200);
            }
        }).catch(function () {
            setSaving(false);
            editError.textContent = 'Không kết nối được máy chủ, vui lòng thử lại.';
            editError.hidden = false;
        });
    });

    /* ───────── Xoá ───────── */
    function fadeOutRow(row) {
        row.classList.add('is-removing');
        var page = currentPage();
        setTimeout(function () {
            row.remove();
            // Tải lại ngầm trang hiện tại để số đếm + phân trang luôn đúng
            // (lùi 1 trang nếu vừa xoá dòng cuối cùng của trang).
            var remaining = results.querySelectorAll('[data-pc-row]').length;
            load(remaining ? page : Math.max(1, page - 1), false);
        }, 300);
    }

    function removeRow(btn) {
        if (!confirm('Xoá yêu cầu tư vấn của ' + btn.dataset.name + '?\n\nThao tác này không hoàn tác được.')) return;
        btn.disabled = true;

        sendJson(btn.dataset.pcDelete, 'DELETE').then(function (r) {
            if (r.status !== 200 || !r.data.ok) {
                btn.disabled = false;
                toast('error', 'Không xoá được', errorMessage(r));
                return;
            }
            updateStats(r.data.stats);
            toast('success', 'Đã xoá', r.data.message);
            var row = btn.closest('[data-pc-row]');
            if (row) fadeOutRow(row);
        }).catch(function () {
            btn.disabled = false;
            toast('error', 'Lỗi', 'Không kết nối được máy chủ, vui lòng thử lại.');
        });
    }
})();
</script>
@endpush
