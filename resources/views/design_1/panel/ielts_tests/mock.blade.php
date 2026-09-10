@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
/* ============================================================
   MOCK + PRACTICE TEST PAGE (GỘP) — 2026-08 redesign
   Namespacing: mọi class mới dùng tiền tố "wf2-" để không đụng tới CSS
   của các trang khác (VD practice.blade.php vẫn dùng "wf-" cho Diagnostic).
   ============================================================ */

.wf2-page {
    --wf2-accent: #511D99;
    --wf2-accent-hover: #451884;
    --wf2-ink: #1e293b;
    --wf2-muted: #94a3b8;
}

/* ── Layout ──────────────────────────────────────────── */
.wf2-search-wrap { margin-bottom: 16px; }
.wf2-search {
    position: relative;
}
.wf2-search .wf2-ico {
    position: absolute; left: 18px; top: 50%; transform: translateY(-50%);
    color: var(--wf2-muted);
}
.wf2-search input {
    width: 100%;
    padding: 14px 18px 14px 44px;
    border-radius: 16px;
    border: 1px solid rgba(148, 163, 184, 0.28);
    background: #f8f9fc;
    font-size: 14px;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.dark-mode .wf2-search input { background: #1e293b; border-color: #334155; color: #e2e8f0; }
.wf2-search input:focus {
    border-color: var(--wf2-accent);
    box-shadow: 0 0 0 3px rgba(81, 29, 153, 0.12);
    background: #fff;
}
/* Chip "Đang tìm: #..." dưới ô search (dùng lại style .wf2-tag). */
.wf2-search-active {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
    font-size: 13px;
}
.wf2-search-active__label { color: var(--wf2-muted); }
.wf2-search-active__x { margin-left: 6px; font-size: 15px; line-height: 1; opacity: 0.7; }

/* ── Gợi ý tìm kiếm ──────────────────────────────────────
   Dropdown dựng từ chính dữ liệu đang có trên card (data-hashtags +
   tiêu đề), cộng lịch sử tìm kiếm lưu ở localStorage. Không gọi server. */
.wf2-suggest {
    display: none;
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 60;
    max-height: 380px;
    overflow-y: auto;
    padding: 6px;
    border-radius: 16px;
    border: 1px solid rgba(148, 163, 184, 0.28);
    background: #fff;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
}
.dark-mode .wf2-suggest { background: #111827; border-color: #334155; }
.wf2-suggest.is-open { display: block; }

.wf2-suggest-group {
    padding: 8px 12px 4px;
    font-size: 11.5px;
    font-weight: 700;
    color: var(--wf2-muted);
}

.wf2-suggest-item {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 9px 12px;
    border: none;
    border-radius: 10px;
    background: transparent;
    color: var(--wf2-ink);
    font-size: 14px;
    text-align: left;
    cursor: pointer;
}
.dark-mode .wf2-suggest-item { color: #e2e8f0; }
.wf2-suggest-item:hover,
.wf2-suggest-item.is-active {
    background: rgba(81, 29, 153, 0.07);
}
.dark-mode .wf2-suggest-item:hover,
.dark-mode .wf2-suggest-item.is-active { background: rgba(129, 89, 201, 0.20); }
.wf2-suggest-item .wf2-ico { color: var(--wf2-muted); position: static; transform: none; }

.wf2-suggest-text {
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
/* Phần KHỚP với từ khoá để nhạt đi, phần còn lại in đậm — ngược với
   thói quen tô đậm phần khớp, vì người dùng đã biết mình vừa gõ gì. */
.wf2-suggest-text strong { font-weight: 700; }

.wf2-suggest-remove {
    flex-shrink: 0;
    border: none;
    background: transparent;
    color: var(--wf2-muted);
    font-size: 17px;
    line-height: 1;
    padding: 0 4px;
    cursor: pointer;
    opacity: 0.6;
}
.wf2-suggest-remove:hover { opacity: 1; color: var(--wf2-ink); }

/* ── Tabs ────────────────────────────────────────────── */
.wf2-tabs {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}
.wf2-tab-btn {
    flex: 1;
    padding: 14px 20px;
    border-radius: 16px;
    border: 2px solid var(--wf2-accent);
    background: #fff;
    color: var(--wf2-accent);
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    transition: all 0.15s ease;
}
.dark-mode .wf2-tab-btn { background: #0f172a; }
.wf2-tab-btn:hover { background: rgba(81, 29, 153, 0.06); }
.wf2-tab-btn.active {
    background: var(--wf2-accent);
    color: #fff;
    box-shadow: 0 8px 18px rgba(81, 29, 153, 0.25);
}

/* ── Daily limit badge (giữ lại từ bản cũ, gọn hơn) ─────*/
.wf2-limit-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 14px; border-radius: 10px; font-size: 12.5px;
    font-weight: 600; margin-bottom: 16px;
}
.wf2-limit-badge.success { background: #d1fae5; color: #059669; }
.wf2-limit-badge.warning { background: #fee2e2; color: #dc2626; }
.wf2-limit-badge .wf2-ico { stroke-width: 2.2; }

/* ── Tab panels ──────────────────────────────────────── */
.wf2-tab-panel { display: none; }
.wf2-tab-panel.active { display: block; }
.wf2-card-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

/* ── Empty / filtered-empty states ──────────────────────*/
.wf2-empty {
    text-align: center; padding: 60px 20px;
    background: #f9fafb; border-radius: 20px; border: 2px dashed #e5e7eb;
    grid-column: 1 / -1;
}
.dark-mode .wf2-empty { background: #1e293b; border-color: #334155; }
.wf2-empty img { max-width: 120px; opacity: 0.7; margin-bottom: 14px; }
.wf2-empty h3 { font-size: 16px; font-weight: 700; color: #374151; margin-bottom: 6px; }
.dark-mode .wf2-empty h3 { color: #f1f5f9; }
.wf2-empty p { color: #6b7280; margin: 0; font-size: 13px; }
.wf2-empty-filtered { display: none; }

/* ── Full Test card (ảnh mockup 1) ──────────────────────*/
.wf2-full-card {
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 20px;
    padding: 26px 24px 22px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    display: flex;
    flex-direction: column;
}
.dark-mode .wf2-full-card { background: #111827; border-color: rgba(148, 163, 184, 0.14); }

.wf2-full-card__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 24px;
}
.wf2-full-card__status {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
    flex-shrink: 0;
}
.wf2-full-card__title {
    font-size: 18px;
    font-weight: 800;
    color: var(--wf2-accent);
}
.dark-mode .wf2-full-card__title { color: #e2e8f0; }

.wf2-badge {
    flex-shrink: 0;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 999px;
    white-space: nowrap;
}
.wf2-badge.done { background: #d1fae5; color: #059669; }
.wf2-badge.todo { background: #f1f5f9; color: #94a3b8; }
.dark-mode .wf2-badge.todo { background: #334155; color: #94a3b8; }

/* Khối "Overall 6.5" nằm ngay dưới badge ở góc phải (theo mockup). */
.wf2-full-card__overall {
    background: rgba(81, 29, 153, 0.07);
    border-radius: 14px;
    padding: 8px 16px;
    text-align: center;
    min-width: 104px;
}
.dark-mode .wf2-full-card__overall { background: rgba(129, 89, 201, 0.18); }
.wf2-full-card__overall-label {
    font-size: 11px; color: var(--wf2-muted); font-weight: 600; text-transform: uppercase;
}
.wf2-full-card__overall-value {
    font-size: 26px; font-weight: 800; color: var(--wf2-accent); line-height: 1.2;
}
.dark-mode .wf2-full-card__overall-value { color: #b79bea; }

/* Icon dạng nét (outline): SVG stroke thừa kế màu chữ của phần tử cha.
   Dùng thay Font Awesome vì bộ Free của FA không có biến thể "regular"
   (viền) cho headphones/book/pen/microphone. */
.wf2-ico {
    display: inline-block;
    vertical-align: -0.18em;
    stroke: currentColor;
    fill: none;
    stroke-width: 1.9;
    stroke-linecap: round;
    stroke-linejoin: round;
    flex-shrink: 0;
}

.wf2-skill-mini-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-bottom: 22px;
}
.wf2-skill-mini {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-radius: 14px;
    border: 1px solid rgba(148, 163, 184, 0.22);
}
.dark-mode .wf2-skill-mini { border-color: #334155; }
.wf2-skill-mini .wf2-ico { color: var(--wf2-accent); }
.dark-mode .wf2-skill-mini .wf2-ico { color: #b79bea; }
.wf2-skill-mini strong { font-size: 16px; color: var(--wf2-accent); }
.dark-mode .wf2-skill-mini strong { color: #e2e8f0; }

/* Hàng chứa nút hành động ở chân card. Căn giữa để nút không kéo dài
   bằng chiều ngang card như trước. */
.wf2-card-actions {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    flex-wrap: wrap;
}
.wf2-card-actions form { margin: 0; }

.wf2-btn-start {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    min-width: 150px;
    padding: 12px 30px;
    border: 1.5px solid transparent;
    border-radius: 999px;
    background: var(--wf2-accent);
    color: #fff;
    font-size: 14.5px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.wf2-btn-start:hover { background: var(--wf2-accent-hover); color: #fff; text-decoration: none; }
.wf2-btn-start.is-disabled { background: #cbd5e1; color: #64748b; cursor: not-allowed; }
.wf2-btn-start.is-enroll { background: #f59e0b; }
.wf2-btn-start.is-enroll:hover { background: #d97706; }

/* Nút "Làm lại" / "Review" hiện sau khi học viên đã hoàn thành bài luyện.
   Bo góc vuông-mềm theo mockup, hẹp hơn nút Start ở trạng thái chưa làm. */
.wf2-btn-retry, .wf2-btn-review {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    padding: 11px 22px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s, color 0.15s, border-color 0.15s;
}
.wf2-btn-retry {
    background: #fff;
    border: 1.5px solid var(--wf2-accent);
    color: var(--wf2-accent);
}
.wf2-btn-retry:hover { background: rgba(81, 29, 153, 0.07); color: var(--wf2-accent); text-decoration: none; }
.dark-mode .wf2-btn-retry { background: transparent; color: #b79bea; border-color: #b79bea; }
.wf2-btn-review {
    background: var(--wf2-accent);
    border: 1.5px solid var(--wf2-accent);
    color: #fff;
}
.wf2-btn-review:hover { background: var(--wf2-accent-hover); border-color: var(--wf2-accent-hover); color: #fff; text-decoration: none; }

/* Nút Start dạng viền — dùng cho card Practice theo mockup. */
.wf2-btn-start.is-outline {
    background: #fff;
    border-color: var(--wf2-accent);
    color: var(--wf2-accent);
}
.wf2-btn-start.is-outline:hover { background: rgba(81, 29, 153, 0.07); color: var(--wf2-accent); }
.dark-mode .wf2-btn-start.is-outline { background: transparent; color: #b79bea; border-color: #b79bea; }

/* Đường kẻ ngăn phần nội dung với nút Start. Nút nằm trong <form> nên
   margin-top:auto phải đặt ở đây mới đẩy được cụm đáy xuống chân card. */
.wf2-card-divider {
    height: 1px;
    background: rgba(148, 163, 184, 0.42);
    margin: auto 0 20px;
}
.dark-mode .wf2-card-divider { background: rgba(148, 163, 184, 0.24); }

/* ── Practice card (ảnh mockup 3) ───────────────────────*/
.wf2-practice-card {
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 18px;
    padding: 24px 22px 20px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    display: flex;
    flex-direction: column;
}
.dark-mode .wf2-practice-card { background: #111827; border-color: rgba(148, 163, 184, 0.14); }

.wf2-practice-card__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}
.wf2-practice-card__title {
    font-size: 16px;
    font-weight: 800;
    color: var(--wf2-accent);
}
.dark-mode .wf2-practice-card__title { color: #e2e8f0; }

.wf2-practice-card__skill {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 700;
    color: var(--wf2-accent);
    margin-bottom: 10px;
}
.dark-mode .wf2-practice-card__skill { color: #b79bea; }
.wf2-practice-card__part {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--wf2-accent);
    margin-bottom: 10px;
}
.dark-mode .wf2-practice-card__part { color: #b79bea; }
.wf2-practice-card__desc {
    font-size: 14.5px;
    font-weight: 700;
    color: var(--wf2-accent);
    margin-bottom: 12px;
    line-height: 1.45;
}
.dark-mode .wf2-practice-card__desc { color: #d3c2f2; }

/* Hashtag loại câu hỏi / nguồn đề do người tạo nhập ở form tạo đề
   (cột ielts_tests.hashtags, chỉ áp dụng cho Practice test). Khối này chỉ
   được render khi có hashtag nên không cần :empty. */
.wf2-practice-card__tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 18px;
}
.wf2-tag {
    display: inline-flex;
    align-items: center;
    padding: 5px 11px;
    border: none;
    border-radius: 9px;
    background: rgba(81, 29, 153, 0.07);
    color: var(--wf2-accent);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.35;
    cursor: pointer;
    transition: background 0.15s;
}
.wf2-tag:hover { background: rgba(81, 29, 153, 0.16); }
.wf2-tag:focus-visible { outline: 2px solid var(--wf2-accent); outline-offset: 2px; }
.dark-mode .wf2-tag { background: rgba(129, 89, 201, 0.18); color: #d3c2f2; }
.dark-mode .wf2-tag:hover { background: rgba(129, 89, 201, 0.30); }

/* ── Filter sidebar (ảnh mockup 2) ──────────────────────*/
.wf2-filter-panel {
    background: #fff;
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 20px;
    padding: 18px;
}
.dark-mode .wf2-filter-panel { background: #111827; border-color: rgba(148, 163, 184, 0.14); }
.wf2-filter-panel__title {
    font-size: 17px;
    font-weight: 800;
    color: var(--wf2-ink);
    margin-bottom: 14px;
}
.dark-mode .wf2-filter-panel__title { color: #e2e8f0; }

.wf2-filter-group {
    border: 1px solid rgba(148, 163, 184, 0.22);
    border-radius: 14px;
    margin-bottom: 12px;
    overflow: hidden;
}
.dark-mode .wf2-filter-group { border-color: #334155; }
.wf2-filter-group__header {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 14px;
    cursor: pointer;
    user-select: none;
    font-size: 13.5px;
    font-weight: 700;
    color: var(--wf2-ink);
}
.dark-mode .wf2-filter-group__header { color: #e2e8f0; }
.wf2-filter-group__header .wf2-fg-icon { color: var(--wf2-accent); display: inline-flex; }
.wf2-filter-group__header .wf2-fg-chevron { margin-left: auto; color: var(--wf2-muted); display: inline-flex; transition: transform 0.2s; }
.wf2-filter-group.collapsed .wf2-fg-chevron { transform: rotate(-90deg); }
.wf2-filter-group__body { padding: 0 14px 14px; }
.wf2-filter-group.collapsed .wf2-filter-group__body { display: none; }

.wf2-filter-sub-label {
    font-size: 12px; color: var(--wf2-muted); font-weight: 600; margin: 4px 0 6px;
}
.wf2-filter-check {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: var(--wf2-ink);
    padding: 5px 0;
    cursor: pointer;
}
.dark-mode .wf2-filter-check { color: #cbd5e1; }
.wf2-filter-check input {
    width: 16px; height: 16px; accent-color: var(--wf2-accent); cursor: pointer;
}
.wf2-filter-divider { height: 1px; background: rgba(148, 163, 184, 0.2); margin: 8px 0; }

.wf2-filter-reset {
    width: 100%;
    padding: 12px;
    border: 1px solid rgba(148, 163, 184, 0.35);
    border-radius: 12px;
    background: #fff;
    color: var(--wf2-ink);
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    margin-top: 8px;
}
.dark-mode .wf2-filter-reset { background: #1e293b; color: #e2e8f0; }
.wf2-filter-reset:hover { background: #f8f9fc; }

/* Badge độ khó (cột ielts_tests.difficulty_level). Dùng chung cho card
   Full Test và card Practice, đặt cạnh badge trạng thái. */
.wf2-difficulty-badge {
    flex-shrink: 0;
    font-size: 11.5px;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 999px;
    white-space: nowrap;
    background: rgba(81, 29, 153, 0.08);
    color: var(--wf2-accent);
}
.dark-mode .wf2-difficulty-badge { background: rgba(129, 89, 201, 0.18); color: #d3c2f2; }

/* Cụm badge ở góc phải card Practice — cho phép xuống dòng khi có cả
   độ khó lẫn trạng thái. */
.wf2-practice-card__badges {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

@media (max-width: 1199px) {
    .wf2-card-grid { grid-template-columns: 1fr; }
}
@media (max-width: 767px) {
    .wf2-tabs { flex-direction: column; }
}
</style>
@endpush

@section('content')
@php
    $formatBand = function ($value) {
        return $value !== null ? number_format((float) $value, 1) : '--';
    };

    // Bộ icon dạng nét (outline), kiểu Feather. Dùng SVG nội tuyến thay vì
    // Font Awesome vì bộ FA Free không có biến thể viền cho headphones /
    // book / pen / microphone — chỉ có bản solid (tô đặc).
    $icons = [
        'headphones' => '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>',
        'book'       => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'pen'        => '<path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z"/>',
        'mic'        => '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><path d="M12 19v4"/><path d="M8 23h8"/>',
        'bolt'       => '<path d="M13 2 3 14h9l-1 8 10-12h-9z"/>',
        'retry'      => '<path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>',
        'eye'        => '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>',
        'lock'       => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'clock'      => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'cart'       => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
        'search'     => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/>',
        'bars'       => '<path d="M18 20V10M12 20V4M6 20v-6"/>',
        'checklist'  => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'chevron'    => '<path d="M18 15l-6-6-6 6"/>',
        'check'      => '<path d="M20 6L9 17l-5-5"/>',
        'alert'      => '<circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/>',
    ];

    $ico = function ($name, $size = 20) use ($icons) {
        if (empty($icons[$name])) {
            return '';
        }
        return '<svg class="wf2-ico" width="' . $size . '" height="' . $size . '"'
             . ' viewBox="0 0 24 24" aria-hidden="true" focusable="false">'
             . $icons[$name] . '</svg>';
    };

    $skillMeta = [
        'listening' => ['label' => 'Listening', 'icon' => 'headphones'],
        'reading'   => ['label' => 'Reading',   'icon' => 'book'],
        'writing'   => ['label' => 'Writing',   'icon' => 'pen'],
        'speaking'  => ['label' => 'Speaking',  'icon' => 'mic'],
    ];

    // Nhãn "Passage X / Part X / Task X". Ưu tiên practice_part_number do
    // người tạo đề khai báo; đề cũ chưa có dữ liệu thì fallback về
    // section_number của section đại diện.
    $partLabelFor = function ($test) {
        if (empty($test->primary_skill)) {
            return null;
        }
        if ($test->is_full_test) {
            return 'Full đề';
        }

        $number = $test->practice_part_number
            ?: (optional($test->display_section)->section_number ?: null);

        $prefix = \App\Models\IeltsTest::SKILL_PART_LABELS[$test->primary_skill] ?? 'Part';

        return $number ? ($prefix . ' ' . (int) $number) : $prefix;
    };
@endphp

<div class="wf2-page">
<div class="wf-page-wrap">
    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-12 col-lg-9 mb-20">

            {{-- Search --}}
            <div class="wf2-search-wrap">
                <div class="wf2-search">
                    {!! $ico('search', 18) !!}
                    <input type="text" id="wf2Search" autocomplete="off"
                           role="combobox" aria-expanded="false" aria-autocomplete="list"
                           aria-controls="wf2Suggest"
                           placeholder="Tìm theo tên bài hoặc #hashtag (VD: #CAM, Gap Filling)...">
                    {{-- Gợi ý dựng bằng JS từ card đang có + lịch sử tìm kiếm. --}}
                    <div class="wf2-suggest" id="wf2Suggest" role="listbox"></div>
                </div>
                {{-- Chip hiện từ khoá hashtag đang tìm (bấm chip trên card sẽ set vào đây). --}}
                <div class="wf2-search-active" id="wf2SearchActive" style="display: none;">
                    <span class="wf2-search-active__label">Đang tìm:</span>
                    <button type="button" class="wf2-tag" id="wf2SearchClear">
                        <span id="wf2SearchActiveText"></span>
                        <span class="wf2-search-active__x">×</span>
                    </button>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="wf2-tabs">
                <button type="button" class="wf2-tab-btn active" data-target="wf2PanelFull">Full Test</button>
                <button type="button" class="wf2-tab-btn" data-target="wf2PanelPractice">Practice by Skill</button>
            </div>

            {{-- Daily limit badge (chỉ ý nghĩa cho Mock/Full Test) --}}
            @if(isset($remainingToday) && $remainingToday > 0)
                <div class="wf2-limit-badge success">
                    {!! $ico('check', 16) !!}
                    <span>{{ $remainingToday }} lượt còn lại hôm nay (giới hạn: {{ $dailyLimit ?? 2 }})</span>
                </div>
            @elseif(isset($remainingToday) && $remainingToday <= 0)
                <div class="wf2-limit-badge warning">
                    {!! $ico('alert', 16) !!}
                    <span>{{ trans('update.no_attempts_left_today') }}</span>
                </div>
            @endif

            {{-- ============== FULL TEST TAB ============== --}}
            <div class="wf2-tab-panel active" id="wf2PanelFull">
                <div class="wf2-card-grid">
                    @if($mockTests->isEmpty())
                        <div class="wf2-empty">
                            <img src="/assets/default/img/no-results/support.png" alt="">
                            <h3>{{ trans('update.no_mock_tests_available') }}</h3>
                            <p>{{ trans('update.no_mock_tests_hint') }}</p>
                        </div>
                    @else
                        @foreach($mockTests as $test)
                            @php
                                $isDone = $test->user_attempts > 0;
                                $best = $test->best_attempt;
                            @endphp
                            <div class="wf2-full-card wf2-card"
                                 data-title="{{ mb_strtolower($test->title) }}"
                                 data-difficulty="{{ $test->difficulty_level }}"
                                 data-status="{{ $isDone ? 'done' : 'todo' }}">
                                <div class="wf2-full-card__head">
                                    <div class="wf2-full-card__title">{{ $test->title }}</div>
                                    <div class="wf2-full-card__status">
                                        @if($test->difficulty_label)
                                            <span class="wf2-difficulty-badge">{{ $test->difficulty_label }}</span>
                                        @endif
                                        <span class="wf2-badge {{ $isDone ? 'done' : 'todo' }}">
                                            {{ $isDone ? '✓ Completed' : '--' }}
                                        </span>
                                        @if($best && $best->overall_band !== null)
                                            <div class="wf2-full-card__overall">
                                                <div class="wf2-full-card__overall-label">Overall</div>
                                                <div class="wf2-full-card__overall-value">{{ $formatBand($best->overall_band) }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="wf2-skill-mini-grid">
                                    @foreach($skillMeta as $skillKey => $meta)
                                        <div class="wf2-skill-mini">
                                            {!! $ico($meta['icon'], 22) !!}
                                            <strong>{{ $best ? $formatBand($best->{$skillKey . '_band'} ?? null) : '--' }}</strong>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="wf2-card-divider"></div>

                                <div class="wf2-card-actions">
                                    @if($test->can_take === true)
                                        <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                                            @csrf
                                            {{-- Không truyền 'skill' — startTest() tự chọn section đầu
                                                 tiên theo sort_order (Listening trước). --}}
                                            <button type="submit" class="wf2-btn-start">
                                                {!! $ico('bolt', 18) !!} Start
                                            </button>
                                        </form>
                                    @elseif($test->can_take === 'daily_limit')
                                        <button type="button" class="wf2-btn-start is-disabled" disabled>
                                            {!! $ico('clock', 18) !!} Daily limit
                                        </button>
                                    @elseif($test->can_take === 'max_attempts')
                                        <button type="button" class="wf2-btn-start is-disabled" disabled>
                                            {!! $ico('lock', 18) !!} Max attempts
                                        </button>
                                    @elseif($test->can_take === 'not_enrolled')
                                        <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="wf2-btn-start is-enroll">
                                            {!! $ico('cart', 18) !!} Enroll
                                        </a>
                                    @else
                                        <button type="button" class="wf2-btn-start is-disabled" disabled>
                                            {!! $ico('lock', 18) !!} Locked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="wf2-empty wf2-empty-filtered">
                            <h3>Không tìm thấy bài thi phù hợp</h3>
                            <p>Thử đổi từ khoá tìm kiếm hoặc bộ lọc.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============== PRACTICE BY SKILL TAB ============== --}}
            <div class="wf2-tab-panel" id="wf2PanelPractice">
                <div class="wf2-card-grid">
                    @if($practiceTests->isEmpty())
                        <div class="wf2-empty">
                            <img src="/assets/default/img/no-results/support.png" alt="">
                            <h3>{{ trans('update.no_practice_tests_available') }}</h3>
                            <p>{{ trans('update.no_practice_tests_hint') }}</p>
                        </div>
                    @else
                        @foreach($practiceTests as $test)
                            @php
                                $isDone = $test->user_attempts > 0;
                                $best = $test->best_attempt;
                                $skill = $test->primary_skill;
                                $meta = $skillMeta[$skill] ?? null;
                                $partLabel = $partLabelFor($test);

                                $correctCount = ($best && $skill) ? ($best->{$skill . '_score'} ?? null) : null;
                                $totalQuestions = optional($test->display_section)->total_questions;

                                // Lượt làm để mở trang Review: ưu tiên lượt gần nhất
                                // (indexMock() đã lọc status='completed'), fallback về
                                // lượt điểm cao nhất. Null => học viên chưa làm xong lần nào.
                                $reviewAttempt = $test->last_attempt ?? $best;

                                // Hashtag do người tạo đề nhập (cột hashtags).
                                // Đã bỏ dấu '#' khi lưu -> thêm lại khi render.
                                $hashtags = $test->getHashtagsList();

                                // Chuỗi phục vụ ô tìm kiếm: lowercase + giữ dấu '#'
                                // để gõ "cam" hay "#cam" đều khớp được.
                                $hashtagSearch = !empty($hashtags)
                                    ? mb_strtolower('#' . implode(' #', $hashtags))
                                    : '';
                            @endphp
                            <div class="wf2-practice-card wf2-card"
                                 data-title="{{ mb_strtolower($test->title) }}"
                                 data-hashtags="{{ $hashtagSearch }}"
                                 data-status="{{ $isDone ? 'done' : 'todo' }}"
                                 data-difficulty="{{ $test->difficulty_level }}"
                                 data-skill="{{ $skill }}"
                                 data-mode="{{ $test->is_full_test ? 'full' : 'single' }}"
                                 data-passage="{{ $test->practice_part_number ?: optional($test->display_section)->section_number }}">
                                <div class="wf2-practice-card__head">
                                    <div class="wf2-practice-card__title">{{ $test->title }}</div>
                                    <div class="wf2-practice-card__badges">
                                        @if($test->difficulty_label)
                                            <span class="wf2-difficulty-badge">{{ $test->difficulty_label }}</span>
                                        @endif
                                        <span class="wf2-badge {{ $isDone ? 'done' : 'todo' }}">
                                            @if($isDone && $correctCount !== null && $totalQuestions)
                                                Completed • {{ (int) $correctCount }}/{{ (int) $totalQuestions }}
                                            @elseif($isDone)
                                                ✓ Completed
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                @if($meta)
                                    <div class="wf2-practice-card__skill">
                                        {!! $ico($meta['icon'], 20) !!} {{ $meta['label'] }}
                                    </div>
                                @endif

                                @if($partLabel)
                                    <div class="wf2-practice-card__part">{{ $partLabel }}</div>
                                @endif

                                @if($test->display_section && $test->display_section->title)
                                    <div class="wf2-practice-card__desc">{{ $test->display_section->title }}</div>
                                @endif

                                {{-- Hashtag loại câu hỏi / nguồn đề (chỉ Practice test).
                                     Bấm vào chip = tìm kiếm nhanh theo hashtag đó. --}}
                                @if(!empty($hashtags))
                                    <div class="wf2-practice-card__tags">
                                        @foreach($hashtags as $tag)
                                            <button type="button" class="wf2-tag"
                                                    data-tag="{{ $tag }}"
                                                    title="Tìm các bài có hashtag này">#{{ $tag }}</button>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="wf2-card-divider"></div>

                                <div class="wf2-card-actions">
                                    @if($reviewAttempt)
                                        {{-- Đã làm xong: "Làm lại" + "Review" thay cho nút Start.
                                             Chỉ cho làm lại khi canUserTake() vẫn cho phép. --}}
                                        @if($test->can_take === true)
                                            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                                                @csrf
                                                @if($skill)
                                                    <input type="hidden" name="skill" value="{{ $skill }}">
                                                @endif
                                                <button type="submit" class="wf2-btn-retry">
                                                    Làm lại {!! $ico('retry', 18) !!}
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('panel.ielts_tests.review', $reviewAttempt->id) }}" class="wf2-btn-review">
                                            {!! $ico('eye', 18) !!} Review
                                        </a>
                                    @elseif($test->can_take === true)
                                        <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                                            @csrf
                                            @if($skill)
                                                <input type="hidden" name="skill" value="{{ $skill }}">
                                            @endif
                                            <button type="submit" class="wf2-btn-start is-outline">
                                                {!! $ico('bolt', 18) !!} Start
                                            </button>
                                        </form>
                                    @elseif($test->can_take === 'max_attempts')
                                        <button type="button" class="wf2-btn-start is-disabled" disabled>
                                            {!! $ico('lock', 18) !!} Max attempts
                                        </button>
                                    @elseif($test->can_take === 'not_enrolled')
                                        <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="wf2-btn-start is-enroll">
                                            {!! $ico('cart', 18) !!} Enroll
                                        </a>
                                    @else
                                        <button type="button" class="wf2-btn-start is-disabled" disabled>
                                            {!! $ico('lock', 18) !!} Locked
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="wf2-empty wf2-empty-filtered">
                            <h3>Không tìm thấy bài luyện phù hợp</h3>
                            <p>Thử đổi từ khoá tìm kiếm hoặc bộ lọc.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- end left col --}}

        {{-- RIGHT COLUMN – FILTER SIDEBAR --}}
        <div class="col-12 col-lg-3">
            <div class="wf2-filter-panel">
                <div class="wf2-filter-panel__title">Bộ lọc</div>

                {{-- Trạng thái — đặt đầu tiên vì áp dụng cho CẢ 2 tab
                     (Full Test lẫn Practice by Skill). --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('checklist', 18) !!}</span> Trạng thái
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-status" value="done"> Đã làm
                        </label>
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-status" value="todo"> Chưa làm
                        </label>
                    </div>
                </div>

                {{-- Độ khó — lọc theo cột ielts_tests.difficulty_level, áp dụng
                     cho cả 2 tab. --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('bars', 18) !!}</span> Độ khó
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        @foreach (\App\Models\IeltsTest::DIFFICULTY_LEVEL_LABELS as $difficultyValue => $difficultyLabel)
                            <label class="wf2-filter-check">
                                <input type="checkbox" class="wf2-filter-difficulty" value="{{ $difficultyValue }}"> {{ $difficultyLabel }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Reading --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('book', 18) !!}</span> Reading
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="reading" data-mode="single"> Bài lẻ (mọi phần)
                        </label>
                        <div class="wf2-filter-sub-label">Theo passage</div>
                        @for ($p = 1; $p <= 3; $p++)
                            <label class="wf2-filter-check">
                                <input type="checkbox" class="wf2-filter-skill" data-skill="reading" data-mode="single" data-passage="{{ $p }}"> Passage {{ $p }}
                            </label>
                        @endfor
                        <div class="wf2-filter-divider"></div>
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="reading" data-mode="full"> Full đề
                        </label>
                    </div>
                </div>

                {{-- Listening --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('headphones', 18) !!}</span> Listening
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="listening" data-mode="single"> Bài lẻ (mọi phần)
                        </label>
                        <div class="wf2-filter-sub-label">Theo part</div>
                        @for ($p = 1; $p <= 4; $p++)
                            <label class="wf2-filter-check">
                                <input type="checkbox" class="wf2-filter-skill" data-skill="listening" data-mode="single" data-passage="{{ $p }}"> Part {{ $p }}
                            </label>
                        @endfor
                        <div class="wf2-filter-divider"></div>
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="listening" data-mode="full"> Full đề
                        </label>
                    </div>
                </div>

                {{-- Writing --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('pen', 18) !!}</span> Writing
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="writing" data-mode="single"> Bài lẻ (mọi phần)
                        </label>
                        <div class="wf2-filter-sub-label">Theo task</div>
                        @for ($p = 1; $p <= 2; $p++)
                            <label class="wf2-filter-check">
                                <input type="checkbox" class="wf2-filter-skill" data-skill="writing" data-mode="single" data-passage="{{ $p }}"> Task {{ $p }}
                            </label>
                        @endfor
                        <div class="wf2-filter-divider"></div>
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="writing" data-mode="full"> Full đề
                        </label>
                    </div>
                </div>

                {{-- Speaking --}}
                <div class="wf2-filter-group">
                    <div class="wf2-filter-group__header">
                        <span class="wf2-fg-icon">{!! $ico('mic', 18) !!}</span> Speaking
                        <span class="wf2-fg-chevron">{!! $ico('chevron', 16) !!}</span>
                    </div>
                    <div class="wf2-filter-group__body">
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="speaking" data-mode="single"> Bài lẻ (mọi phần)
                        </label>
                        <div class="wf2-filter-sub-label">Theo part</div>
                        @for ($p = 1; $p <= 3; $p++)
                            <label class="wf2-filter-check">
                                <input type="checkbox" class="wf2-filter-skill" data-skill="speaking" data-mode="single" data-passage="{{ $p }}"> Part {{ $p }}
                            </label>
                        @endfor
                        <div class="wf2-filter-divider"></div>
                        <label class="wf2-filter-check">
                            <input type="checkbox" class="wf2-filter-skill" data-skill="speaking" data-mode="full"> Full đề
                        </label>
                    </div>
                </div>

                <button type="button" id="wf2FilterReset" class="wf2-filter-reset">Đặt lại</button>
            </div>
        </div>

    </div>{{-- end row --}}
</div>
</div>
@endsection

@push('scripts_bottom')
<script>
(function () {
    // ---------- Tabs ----------
    document.querySelectorAll('.wf2-tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.wf2-tab-btn').forEach(function (b) { b.classList.remove('active'); });
            document.querySelectorAll('.wf2-tab-panel').forEach(function (p) { p.classList.remove('active'); });
            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.add('active');
            wf2CloseSuggest();
            wf2ApplySearch();
        });
    });

    // ---------- Filter sidebar accordion ----------
    document.querySelectorAll('.wf2-filter-group__header').forEach(function (header) {
        header.addEventListener('click', function () {
            header.closest('.wf2-filter-group').classList.toggle('collapsed');
        });
    });

    // ---------- Search (client-side, theo tab đang active) ----------
    var searchInput = document.getElementById('wf2Search');
    var searchActive = document.getElementById('wf2SearchActive');
    var searchActiveText = document.getElementById('wf2SearchActiveText');

    // Bỏ dấu tiếng Việt + hạ chữ thường, để gõ "doc hieu" vẫn ra "Đọc hiểu".
    // NFD tách dấu thành ký tự tổ hợp riêng rồi xoá, nên độ dài chuỗi kết quả
    // bằng chuỗi gốc — nhờ vậy chỉ số khớp dùng được để tô đậm chuỗi gốc.
    function wf2Deaccent(value) {
        return String(value || '')
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd')
            .replace(/Đ/g, 'D')
            .toLowerCase();
    }

    // Chip "Đang tìm: ..." chỉ hiện khi từ khoá bắt đầu bằng '#', tức là
    // học viên đang tìm theo hashtag (bấm chip trên card hoặc tự gõ).
    function wf2SyncSearchChip(rawQuery) {
        if (!searchActive) return;
        if (rawQuery && rawQuery.charAt(0) === '#') {
            searchActiveText.textContent = rawQuery;
            searchActive.style.display = '';
        } else {
            searchActive.style.display = 'none';
        }
    }

    function wf2ApplySearch() {
        var activePanel = document.querySelector('.wf2-tab-panel.active');
        if (!activePanel) return;
        var rawQuery = (searchInput.value || '').trim();
        var q = wf2Deaccent(rawQuery);
        wf2SyncSearchChip(rawQuery);
        activePanel.querySelectorAll('.wf2-card').forEach(function (card) {
            // Nếu bộ lọc sidebar đã ẩn card này thì search không cần bật lại.
            if (card.dataset.filteredOut === '1') {
                card.style.display = 'none';
                return;
            }
            // Khớp tiêu đề HOẶC hashtag, không phân biệt dấu. Từ khoá bắt đầu
            // bằng '#' thì chỉ so hashtag.
            var match;
            if (!q) {
                match = true;
            } else if (q.charAt(0) === '#') {
                match = wf2Deaccent(card.dataset.hashtags).indexOf(q) !== -1;
            } else {
                match = wf2Deaccent(card.dataset.title).indexOf(q) !== -1
                    || wf2Deaccent(card.dataset.hashtags).indexOf(q) !== -1;
            }
            card.style.display = match ? '' : 'none';
        });
        wf2RefreshEmptyStates();
    }

    // ---------- Gợi ý tìm kiếm ----------
    var suggestBox = document.getElementById('wf2Suggest');
    var SUGGEST_HISTORY_KEY = 'wf2_search_history';
    var SUGGEST_HISTORY_MAX = 5;
    var SUGGEST_MAX_PER_GROUP = 5;
    var SUGGEST_MAX_TOTAL = 8;
    var suggestItems = [];      // dữ liệu của các mục đang hiển thị
    var suggestActiveIndex = -1;

    var WF2_SVG_SEARCH = '<svg class="wf2-ico" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.35-4.35"/></svg>';
    var WF2_SVG_CLOCK = '<svg class="wf2-ico" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>';
    var WF2_SVG_TAG = '<svg class="wf2-ico" width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>';

    function wf2EscapeHtml(value) {
        var div = document.createElement('div');
        div.textContent = value == null ? '' : String(value);
        return div.innerHTML;
    }

    function wf2ReadHistory() {
        try {
            var raw = window.localStorage.getItem(SUGGEST_HISTORY_KEY);
            var parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed.filter(function (item) { return typeof item === 'string' && item; }) : [];
        } catch (e) {
            return [];
        }
    }

    function wf2WriteHistory(list) {
        try {
            window.localStorage.setItem(SUGGEST_HISTORY_KEY, JSON.stringify(list.slice(0, SUGGEST_HISTORY_MAX)));
        } catch (e) {
            // Chế độ riêng tư chặn localStorage -> bỏ qua, gợi ý vẫn chạy.
        }
    }

    function wf2PushHistory(value) {
        var text = String(value || '').trim();
        if (!text) return;
        var list = wf2ReadHistory().filter(function (item) { return item.toLowerCase() !== text.toLowerCase(); });
        list.unshift(text);
        wf2WriteHistory(list);
    }

    function wf2RemoveHistory(value) {
        var list = wf2ReadHistory().filter(function (item) { return item !== value; });
        wf2WriteHistory(list);
    }

    // Nguồn gợi ý = chính các card của tab đang mở. Hashtag lấy từ chip
    // (giữ nguyên hoa/thường người tạo đề đã nhập), tên bài lấy từ tiêu đề.
    function wf2CollectSuggestSource() {
        var panel = document.querySelector('.wf2-tab-panel.active');
        var hashtags = new Map();
        var titles = new Map();
        if (!panel) return { hashtags: [], titles: [] };

        panel.querySelectorAll('.wf2-card').forEach(function (card) {
            var titleEl = card.querySelector('.wf2-full-card__title, .wf2-practice-card__title');
            if (titleEl) {
                var title = titleEl.textContent.trim();
                if (title && !titles.has(title.toLowerCase())) {
                    titles.set(title.toLowerCase(), title);
                }
            }
            card.querySelectorAll('.wf2-tag[data-tag]').forEach(function (chip) {
                var tag = (chip.dataset.tag || '').trim();
                if (tag && !hashtags.has(tag.toLowerCase())) {
                    hashtags.set(tag.toLowerCase(), tag);
                }
            });
        });

        return {
            hashtags: Array.from(hashtags.values()),
            titles: Array.from(titles.values())
        };
    }

    // Tô đậm phần CHƯA khớp, để phần người dùng vừa gõ chìm xuống — giống
    // cách các ô gợi ý quen thuộc phân biệt "đã gõ" với "gợi ý thêm".
    function wf2HighlightMatch(text, query) {
        if (!query) return wf2EscapeHtml(text);
        var index = wf2Deaccent(text).indexOf(query);
        if (index === -1) return wf2EscapeHtml(text);
        var before = text.slice(0, index);
        var mid = text.slice(index, index + query.length);
        var after = text.slice(index + query.length);
        return wf2EscapeHtml(before)
            + wf2EscapeHtml(mid)
            + '<strong>' + wf2EscapeHtml(after) + '</strong>';
    }

    function wf2BuildSuggestions(rawQuery) {
        var query = wf2Deaccent(rawQuery.trim());
        var results = [];

        // Ô trống + đang focus -> hiện lịch sử tìm kiếm.
        if (!query) {
            wf2ReadHistory().forEach(function (item) {
                results.push({ kind: 'history', value: item, label: item });
            });
            return results;
        }

        // Gõ '#abc' thì chỉ gợi ý hashtag; bỏ '#' khi so khớp.
        var isHashtagQuery = query.charAt(0) === '#';
        var needle = isHashtagQuery ? query.slice(1) : query;
        if (!needle) return results;

        var source = wf2CollectSuggestSource();

        source.hashtags.forEach(function (tag) {
            if (results.length >= SUGGEST_MAX_PER_GROUP) return;
            if (wf2Deaccent(tag).indexOf(needle) === -1) return;
            results.push({ kind: 'hashtag', value: '#' + tag, label: tag, needle: needle });
        });

        if (isHashtagQuery) return results;

        var titleCount = 0;
        source.titles.forEach(function (title) {
            if (titleCount >= SUGGEST_MAX_PER_GROUP || results.length >= SUGGEST_MAX_TOTAL) return;
            if (wf2Deaccent(title).indexOf(needle) === -1) return;
            titleCount++;
            results.push({ kind: 'title', value: title, label: title, needle: needle });
        });

        return results.slice(0, SUGGEST_MAX_TOTAL);
    }

    function wf2RenderSuggest(rawQuery) {
        if (!suggestBox) return;

        suggestItems = wf2BuildSuggestions(rawQuery);
        suggestActiveIndex = -1;

        if (!suggestItems.length) {
            wf2CloseSuggest();
            return;
        }

        var html = '';
        var lastKind = null;

        suggestItems.forEach(function (item, index) {
            if (item.kind !== lastKind) {
                if (item.kind === 'history') html += '<div class="wf2-suggest-group">Tìm gần đây</div>';
                if (item.kind === 'hashtag') html += '<div class="wf2-suggest-group">Hashtag</div>';
                if (item.kind === 'title') html += '<div class="wf2-suggest-group">Tên bài</div>';
                lastKind = item.kind;
            }

            var icon = item.kind === 'history' ? WF2_SVG_CLOCK
                     : item.kind === 'hashtag' ? WF2_SVG_TAG
                     : WF2_SVG_SEARCH;

            var label = item.kind === 'hashtag'
                ? '#' + wf2HighlightMatch(item.label, item.needle)
                : wf2HighlightMatch(item.label, item.needle || '');

            html += '<button type="button" class="wf2-suggest-item" role="option" data-index="' + index + '">'
                  + icon
                  + '<span class="wf2-suggest-text">' + label + '</span>'
                  + (item.kind === 'history'
                        ? '<span class="wf2-suggest-remove" data-remove="' + wf2EscapeHtml(item.value) + '" title="Xoá khỏi lịch sử">×</span>'
                        : '')
                  + '</button>';
        });

        suggestBox.innerHTML = html;
        suggestBox.classList.add('is-open');
        searchInput.setAttribute('aria-expanded', 'true');
    }

    function wf2CloseSuggest() {
        if (!suggestBox) return;
        suggestBox.classList.remove('is-open');
        suggestBox.innerHTML = '';
        suggestItems = [];
        suggestActiveIndex = -1;
        searchInput.setAttribute('aria-expanded', 'false');
    }

    function wf2SetActiveSuggest(index) {
        var nodes = suggestBox.querySelectorAll('.wf2-suggest-item');
        if (!nodes.length) return;

        if (index < 0) index = nodes.length - 1;
        if (index >= nodes.length) index = 0;
        suggestActiveIndex = index;

        nodes.forEach(function (node, i) {
            node.classList.toggle('is-active', i === index);
            if (i === index) node.scrollIntoView({ block: 'nearest' });
        });
    }

    function wf2ChooseSuggest(index) {
        var item = suggestItems[index];
        if (!item) return;
        searchInput.value = item.value;
        wf2PushHistory(item.value);
        wf2CloseSuggest();
        wf2ApplySearch();
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            wf2RenderSuggest(searchInput.value);
            wf2ApplySearch();
        });

        searchInput.addEventListener('focus', function () {
            wf2RenderSuggest(searchInput.value);
        });

        searchInput.addEventListener('keydown', function (event) {
            var isOpen = suggestBox && suggestBox.classList.contains('is-open');

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                if (!isOpen) { wf2RenderSuggest(searchInput.value); return; }
                wf2SetActiveSuggest(suggestActiveIndex + 1);
                return;
            }

            if (event.key === 'ArrowUp') {
                event.preventDefault();
                if (!isOpen) return;
                wf2SetActiveSuggest(suggestActiveIndex - 1);
                return;
            }

            if (event.key === 'Enter') {
                // Chưa chọn mục nào -> Enter chỉ lưu từ khoá đang gõ.
                if (isOpen && suggestActiveIndex >= 0) {
                    event.preventDefault();
                    wf2ChooseSuggest(suggestActiveIndex);
                } else {
                    wf2PushHistory(searchInput.value);
                    wf2CloseSuggest();
                }
                return;
            }

            if (event.key === 'Escape') {
                wf2CloseSuggest();
            }
        });
    }

    if (suggestBox) {
        suggestBox.addEventListener('mousedown', function (event) {
            // mousedown thay vì click: chạy trước blur nên ô search không kịp
            // đóng dropdown làm hỏng cú bấm.
            var removeBtn = event.target.closest('.wf2-suggest-remove');
            if (removeBtn) {
                event.preventDefault();
                wf2RemoveHistory(removeBtn.dataset.remove);
                wf2RenderSuggest(searchInput.value);
                return;
            }

            var itemBtn = event.target.closest('.wf2-suggest-item');
            if (itemBtn) {
                event.preventDefault();
                wf2ChooseSuggest(parseInt(itemBtn.dataset.index, 10));
            }
        });
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.wf2-search')) {
            wf2CloseSuggest();
        }
    });

    // Bấm chip hashtag trên card -> tìm ngay theo hashtag đó.
    // Chip nằm ở tab Practice, nhưng đổi luôn ô search nên nếu chuyển tab
    // Full Test thì từ khoá "#..." sẽ không khớp card nào (đúng ý: Full Test
    // không có hashtag).
    document.querySelectorAll('.wf2-tag[data-tag]').forEach(function (chip) {
        chip.addEventListener('click', function () {
            if (!searchInput) return;
            searchInput.value = '#' + (chip.dataset.tag || '');
            wf2PushHistory(searchInput.value);
            wf2CloseSuggest();
            wf2ApplySearch();
            searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    // Bấm chip "Đang tìm: ..." -> xoá từ khoá.
    var searchClear = document.getElementById('wf2SearchClear');
    if (searchClear) {
        searchClear.addEventListener('click', function () {
            if (!searchInput) return;
            searchInput.value = '';
            wf2CloseSuggest();
            wf2ApplySearch();
        });
    }

    // ---------- Filter sidebar ----------
    function wf2ApplyFilters() {
        var statusChecked = Array.from(document.querySelectorAll('.wf2-filter-status:checked')).map(function (el) { return el.value; });
        var difficultyChecked = Array.from(document.querySelectorAll('.wf2-filter-difficulty:checked')).map(function (el) { return el.value; });

        // Gom điều kiện theo skill:
        //   full      = tick "Full đề"
        //   singleAll = tick "Bài lẻ (mọi phần)" — checkbox không có data-passage
        //   passages  = tập các phần cụ thể được tick (Passage 2, Part 3...)
        var skillFilters = {};
        document.querySelectorAll('.wf2-filter-skill:checked').forEach(function (el) {
            var skill = el.dataset.skill;
            var mode = el.dataset.mode;
            var passage = el.dataset.passage || null;
            if (!skillFilters[skill]) {
                skillFilters[skill] = { full: false, singleAll: false, passages: new Set() };
            }
            if (mode === 'full') skillFilters[skill].full = true;
            if (mode === 'single') {
                if (passage) {
                    skillFilters[skill].passages.add(passage);
                } else {
                    skillFilters[skill].singleAll = true;
                }
            }
        });
        var anySkillFilterActive = Object.keys(skillFilters).length > 0;

        document.querySelectorAll('.wf2-card').forEach(function (card) {
            var visible = true;

            // Trạng thái áp dụng cho CẢ 2 tab (Full Test lẫn Practice).
            if (statusChecked.length && statusChecked.indexOf(card.dataset.status) === -1) {
                visible = false;
            }

            // Độ khó cũng áp dụng cho cả 2 tab.
            if (visible && difficultyChecked.length && difficultyChecked.indexOf(card.dataset.difficulty || '') === -1) {
                visible = false;
            }

            // Bộ lọc skill / bài lẻ-full đề CHỈ áp dụng cho card Practice
            // (card Full Test luôn chứa đủ 4 kỹ năng nên không có nghĩa để lọc).
            if (visible && anySkillFilterActive && card.dataset.skill) {
                var f = skillFilters[card.dataset.skill];
                if (!f) {
                    // Skill này không được tick gì -> ẩn.
                    visible = false;
                } else if (card.dataset.mode === 'full') {
                    visible = f.full;
                } else if (f.singleAll) {
                    // "Bài lẻ (mọi phần)" bao trùm mọi đề lẻ, kể cả đề chưa gán phần số.
                    visible = true;
                } else if (f.passages.size > 0) {
                    visible = f.passages.has(card.dataset.passage);
                } else {
                    // Chỉ tick "Full đề" -> đề lẻ phải bị ẩn.
                    visible = false;
                }
            }

            card.dataset.filteredOut = visible ? '0' : '1';
        });

        wf2ApplySearch(); // search áp dụng tiếp trên kết quả đã lọc
    }

    function wf2ResetFilters() {
        document.querySelectorAll('.wf2-filter-skill, .wf2-filter-status, .wf2-filter-difficulty').forEach(function (el) { el.checked = false; });
        document.querySelectorAll('.wf2-card').forEach(function (card) { card.dataset.filteredOut = '0'; });
        if (searchInput) searchInput.value = '';
        wf2CloseSuggest();
        wf2ApplySearch();
    }

    function wf2RefreshEmptyStates() {
        document.querySelectorAll('.wf2-tab-panel').forEach(function (panel) {
            var cards = panel.querySelectorAll('.wf2-card');
            var visibleCount = Array.from(cards).filter(function (c) { return c.style.display !== 'none'; }).length;
            var emptyEl = panel.querySelector('.wf2-empty-filtered');
            if (emptyEl) {
                emptyEl.style.display = (cards.length > 0 && visibleCount === 0) ? '' : 'none';
            }
        });
    }

    var resetBtn = document.getElementById('wf2FilterReset');
    if (resetBtn) resetBtn.addEventListener('click', wf2ResetFilters);

    // Mọi checkbox trong sidebar đều lọc ngay khi tick.
    document.querySelectorAll('.wf2-filter-status, .wf2-filter-difficulty, .wf2-filter-skill').forEach(function (el) {
        el.addEventListener('change', wf2ApplyFilters);
    });

    // Khởi tạo trạng thái filteredOut ban đầu.
    document.querySelectorAll('.wf2-card').forEach(function (card) { card.dataset.filteredOut = '0'; });
})();
</script>
@endpush