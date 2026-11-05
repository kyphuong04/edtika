@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = false;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<style>
/* ════════════════════════════════════════════════════════════════════
   Trang intro Placement Test — tông tím/lavender theo thiết kế mới.
   Màu dùng chung khai báo ở :root (--pt-*) để chỉnh 1 chỗ là đổi cả trang.
   ════════════════════════════════════════════════════════════════════ */
:root {
    --pt-primary: #5B21B6;        /* tím chính: tiêu đề phụ, icon, chữ nhấn */
    --pt-primary-dark: #3B0F86;   /* tím đậm: nút, hover */
    --pt-accent: #7C3AED;         /* tím sáng: gradient, viền focus */
    --pt-lavender: #F1EBFF;       /* nền icon / pill */
    --pt-lavender-2: #E6DAFF;
    --pt-border: #ECE6FA;         /* viền card */
    --pt-ink: #1A1033;            /* chữ tiêu đề */
    --pt-text: #3F3A55;           /* chữ nội dung */
    --pt-muted: #6B6683;
    --pt-bg: #FBFAFF;
}

/* ── RESET TRANG: khóa scroll, không tràn ngang ── */
html, body {
    margin: 0;
    height: 100%;
    overflow-x: hidden;
    overflow-y: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: var(--pt-bg);
    /* Vầng sáng tím rất nhẹ ở 2 góc như ảnh thiết kế */
    background-image:
        radial-gradient(900px 600px at 100% 0%, rgba(196, 170, 255, 0.28), transparent 60%),
        radial-gradient(700px 500px at 0% 100%, rgba(214, 196, 255, 0.22), transparent 60%);
    background-repeat: no-repeat;
    min-height: 100%;
}

/* Ép box-sizing cho các khối của trang này (layout cha có thể không set) */
.pt-mini-header, .pt-intro-wrap, .pt-intro-layout,
.pt-intro-left, .pt-intro-right,
.pt-feature-grid, .pt-feature-card, .pt-tip-banner,
.pt-chat-bubble, .pt-start-btn, .pt-glass-badge {
    box-sizing: border-box;
}

.pt-mini-header,
.pt-intro-wrap { position: relative; z-index: 1; }

/* height 72px đã bao gồm padding → khớp đúng với calc(100vh - 72px) bên dưới */
.pt-mini-header {
    max-width: 1400px; margin: 0 auto;
    height: 72px; padding: 16px 24px 0;
    display: flex; align-items: center;
}
.pt-mini-header .pt-brand {
    font-size: 26px; font-weight: 900; letter-spacing: -0.5px;
    color: var(--pt-primary); text-decoration: none;
}

.pt-intro-wrap {
    max-width: 1400px;
    height: calc(100vh - 72px);
    margin: 0 auto;
    padding: 8px 24px 16px;
    display: flex;
    align-items: center;
    overflow: hidden;
}

/* ── LAYOUT CHÍNH ── */
.pt-intro-layout {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 40px;
    width: 100%;
    height: 100%;
    min-width: 0;
}

/* ── CỘT TRÁI (Nội dung) ── */
.pt-intro-left {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.pt-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--pt-lavender); color: var(--pt-primary);
    border: 1px solid var(--pt-lavender-2);
    font-size: 11px; font-weight: 800; letter-spacing: 0.6px;
    padding: 7px 16px; border-radius: 99px;
    margin-bottom: clamp(10px, 1.6vh, 18px);
    text-transform: uppercase;
    width: fit-content;
}

/* Cỡ chữ vừa theo cả chiều rộng lẫn chiều cao màn hình */
.pt-title-main {
    font-size: clamp(26px, min(2.8vw, 4.4vh), 42px);
    line-height: 1.18;
    font-weight: 900;
    color: var(--pt-ink);
    margin: 0 0 6px 0;
    letter-spacing: -0.8px;
    white-space: nowrap;
}

/* Dòng phụ: tím, nét vừa (không đậm như tiêu đề) */
.pt-title-sub {
    font-size: clamp(18px, min(1.9vw, 3vh), 26px);
    line-height: 1.3;
    font-weight: 500;
    color: var(--pt-primary);
    margin: 0 0 clamp(12px, 2.2vh, 22px) 0;
    white-space: nowrap;
}
.pt-title-sub span { color: var(--pt-primary); }

.pt-meta-row {
    display: flex; align-items: center; gap: 18px;
    margin-bottom: clamp(12px, 2.2vh, 22px);
    flex-wrap: wrap;
}
.pt-meta-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 14px; font-weight: 700; color: var(--pt-ink);
}
.pt-meta-item svg { stroke: var(--pt-primary); }
.pt-meta-divider { width: 1px; height: 18px; background: #DCD3F0; color: transparent; overflow: hidden; }

.pt-intro-desc {
    font-size: 15px; line-height: 1.6; color: var(--pt-text);
    margin: 0 0 clamp(8px, 1.4vh, 14px) 0;
}
.pt-intro-desc strong { color: var(--pt-ink); font-weight: 800; }

.pt-feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: clamp(10px, 1.4vh, 14px);
    margin: clamp(6px, 1vh, 10px) 0 clamp(12px, 2vh, 20px);
}

/* Card trắng, viền lavender mảnh, bóng rất nhẹ */
.pt-feature-card {
    background: #FFFFFF;
    border: 1px solid var(--pt-border);
    border-radius: 14px;
    padding: clamp(12px, 1.7vh, 16px) 14px;
    box-shadow: 0 6px 20px -10px rgba(91, 33, 182, 0.16);
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}
.pt-feature-card:hover {
    transform: translateY(-3px);
    border-color: var(--pt-lavender-2);
    box-shadow: 0 14px 30px -12px rgba(91, 33, 182, 0.28);
}
.pt-feature-icon {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--pt-lavender); color: var(--pt-primary);
    display: flex; align-items: center; justify-content: center;
    margin-bottom: clamp(8px, 1.2vh, 12px);
}
.pt-feature-card h5 { font-size: 14.5px; font-weight: 800; color: var(--pt-primary); margin: 0 0 5px 0; }
.pt-feature-card p { font-size: 12.5px; line-height: 1.45; color: var(--pt-muted); margin: 0; }

.pt-tip-banner {
    display: flex; gap: 14px; align-items: center;
    background: #FFFFFF;
    border: 1px solid var(--pt-border);
    border-radius: 14px;
    padding: clamp(12px, 1.7vh, 16px) 20px;
    margin-bottom: clamp(16px, 2.8vh, 32px);
    box-shadow: 0 6px 20px -10px rgba(91, 33, 182, 0.16);
}
.pt-tip-banner svg { width: 26px; height: 26px; flex-shrink: 0; stroke: var(--pt-primary); }
.pt-tip-banner p { font-size: 13.5px; line-height: 1.55; color: var(--pt-text); margin: 0; }
.pt-tip-banner strong { color: var(--pt-ink); font-weight: 800; }
.pt-tip-banner strong.is-accent { color: var(--pt-primary); }

.pt-start-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 12px;
    background: linear-gradient(135deg, var(--pt-accent) 0%, var(--pt-primary) 45%, var(--pt-primary-dark) 100%);
    color: #FFF; border: none; border-radius: 14px;
    padding: clamp(12px, 1.7vh, 16px) 40px;
    font-weight: 700; font-size: 17px;
    text-decoration: none; cursor: pointer; width: fit-content;
    box-shadow: 0 12px 26px -8px rgba(91, 33, 182, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.18);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.pt-start-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 30px -8px rgba(91, 33, 182, 0.7), inset 0 1px 0 rgba(255, 255, 255, 0.18); color: #FFF; }
.pt-start-btn:focus-visible { outline: 3px solid #C4B5FD; outline-offset: 3px; }

/* ── CỘT PHẢI (Mascot) ── */
.pt-intro-right {
    position: relative;
    background: transparent;
    display: flex; align-items: center; justify-content: center;
    overflow: visible;
    min-width: 0;
    min-height: 0;
    height: 100%;
    /* Chỉnh --robot-scale để phóng to / thu nhỏ robot (tính theo chiều cao cột) */
    --robot-scale: 0.82;
}

/* Vầng sáng tím mềm phía sau robot */
.pt-intro-right::before {
    content: '';
    position: absolute;
    top: 46%; left: 52%;
    transform: translate(-50%, -50%);
    width: 92%;
    aspect-ratio: 1;
    background: radial-gradient(circle,
        rgba(196, 170, 255, 0.55) 0%,
        rgba(214, 196, 255, 0.28) 38%,
        rgba(236, 228, 255, 0) 68%);
    z-index: 0;
    pointer-events: none;
}

.pt-chat-bubble {
    position: absolute; top: 6%; left: 6%; z-index: 10;
    background: #FFF;
    border: 1px solid var(--pt-border);
    border-radius: 16px; padding: 14px 18px;
    width: min(230px, 48%);
    box-shadow: 0 18px 40px -16px rgba(91, 33, 182, 0.35);
}
.pt-chat-bubble::after {
    content: ''; position: absolute;
    bottom: -9px; left: 34%;
    width: 16px; height: 16px;
    background: #FFF;
    border-right: 1px solid var(--pt-border);
    border-bottom: 1px solid var(--pt-border);
    transform: rotate(45deg);
    border-bottom-right-radius: 3px;
}
.pt-chat-bubble h4 { margin: 0 0 6px 0; font-size: 14px; color: var(--pt-primary); font-weight: 800; }
.pt-chat-bubble h4 span { color: var(--pt-primary); }
.pt-chat-bubble p { margin: 0; font-size: 12.5px; line-height: 1.5; color: var(--pt-primary); }
.pt-chat-bubble strong { color: var(--pt-primary-dark); }

.pt-robot-pic { display: contents; }
.pt-robot-img {
    position: absolute;
    bottom: 3%;
    left: 53%;
    transform: translateX(-50%);
    /* Rộng theo chiều cao màn hình (ảnh tỉ lệ 900x1154 ≈ 0.78) nhưng không vượt 84% cột,
       để robot luôn nằm giữa các badge mà không đè lên chúng */
    width: min(84%, calc((100vh - 120px) * 0.78 * var(--robot-scale)));
    height: auto;
    max-width: none;
    object-fit: contain;
    object-position: bottom center;
    z-index: 5;
    filter: drop-shadow(0 24px 30px rgba(91, 33, 182, 0.22));
}

/* ── BADGE TRÒN TRẮNG quanh robot ── */
.pt-glass-badge {
    position: absolute; z-index: 10;
    background: rgba(255, 255, 255, 0.92);
    border: 1px solid var(--pt-border);
    border-radius: 50%;
    color: var(--pt-primary);
    display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
    box-shadow: 0 14px 32px -12px rgba(91, 33, 182, 0.35);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.pt-glass-badge svg { stroke: var(--pt-primary); }
.pt-badge-free { top: 14%;    right: 3%;  width: 100px; height: 100px; }
.pt-badge-time { top: 46%;    left: 2%;   width: 88px;  height: 88px; }
.pt-badge-tag  { bottom: 24%; right: 0;   width: 112px; height: 112px; }

.pt-glass-badge span { font-weight: 800; font-size: 10.5px; line-height: 1.3; margin-top: 6px; letter-spacing: 0.2px; }
.pt-badge-time span { font-size: 12.5px; }

.pt-star { position: absolute; z-index: 2; animation: twinkle 3s infinite ease-in-out; }
.pt-star:nth-of-type(2) { animation-delay: .8s; }
.pt-star:nth-of-type(3) { animation-delay: 1.6s; }
.pt-star:nth-of-type(4) { animation-delay: 2.2s; }

/* ── ANIMATIONS ── */
@keyframes twinkle { 0%, 100% { opacity: 0.45; transform: scale(0.85); } 50% { opacity: 1; transform: scale(1.15); } }

@media (prefers-reduced-motion: reduce) {
    .pt-star { animation: none; opacity: 0.7; }
    .pt-start-btn, .pt-feature-card { transition: none; }
    .pt-feature-card:hover, .pt-start-btn:hover { transform: none; }
}

/* ── Từ 1400px trở xuống: cho tiêu đề xuống dòng, tránh tràn ngang ── */
@media (max-width: 1400px) {
    .pt-title-main, .pt-title-sub { white-space: normal; }
}

/* ── Màn hình quá thấp: mở scroll dọc để không cắt mất nút Bắt đầu ── */
@media (max-height: 620px) {
    html, body { overflow-y: auto; height: auto; }
    .pt-intro-wrap { height: auto; min-height: calc(100vh - 72px); padding-bottom: 32px; }
    .pt-intro-right { min-height: 460px; }
}

/* ── RESPONSIVE ── */
@media (max-width: 1024px) {
    html, body { overflow-y: auto; height: auto; }

    .pt-intro-wrap {
        height: auto;
        min-height: calc(100vh - 72px);
        overflow: visible;
        padding: 24px;
        align-items: stretch;
    }
    .pt-intro-layout {
        grid-template-columns: 1fr;
        gap: 32px;
        height: auto;
    }
    .pt-intro-right {
        order: -1;
        min-height: 460px;
        height: auto;
        padding-top: 70px;   /* chừa chỗ cho bong bóng chat, không che mặt robot */
    }
    .pt-robot-img {
        position: relative;
        left: auto;
        bottom: auto;
        transform: none;
        height: auto;
        width: 100%;
        max-width: 400px;
    }
    .pt-badge-time { top: 46%; }
    .pt-badge-tag  { bottom: 18%; }
    .pt-title-main { font-size: 34px; }
    .pt-title-sub { font-size: 21px; }
}

@media (max-width: 640px) {
    .pt-feature-grid { grid-template-columns: 1fr 1fr; }
    .pt-intro-right { padding-top: 92px; min-height: 0; }
    .pt-chat-bubble { width: 200px; top: 0; left: 0; padding: 12px 14px; }
    .pt-robot-img { max-width: 300px; }
    .pt-badge-free { width: 76px; height: 76px; top: 4%; right: 0; }
    .pt-badge-time { width: 70px; height: 70px; left: 0; }
    .pt-badge-tag  { width: 88px; height: 88px; right: 0; }
    .pt-glass-badge svg { width: 20px; height: 20px; }
    .pt-glass-badge span { font-size: 9px; margin-top: 4px; }
    .pt-badge-time span { font-size: 10.5px; }
    .pt-title-main { font-size: 28px; }
    .pt-title-sub { font-size: 18px; }
}
</style>
@endpush

@section('content')
<div class="pt-mini-header">
    <a href="/" class="pt-brand">EDTIKA</a>
</div>

<div class="pt-intro-wrap">
    <div class="pt-intro-layout">

        {{-- ── TRÁI: Nội dung mô tả ── --}}
        <div class="pt-intro-left">

            <div class="pt-eyebrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                ADAPTIVE PLACEMENT TEST
            </div>

            <h1 class="pt-title-main">Kiểm tra trình độ tiếng Anh của bạn</h1>
            <h2 class="pt-title-sub">với <span>Adaptive Placement Test</span> cùng EDTIKA</h2>

            <div class="pt-meta-row">
                <div class="pt-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5B21B6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Chỉ trong 30 phút
                </div>
                <span class="pt-meta-divider" aria-hidden="true"></span>
                <div class="pt-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5B21B6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    Hoàn toàn miễn phí
                </div>
            </div>

            <p class="pt-intro-desc">Khám phá năng lực tiếng Anh của bạn cùng bài Kiểm tra Đánh giá Adaptive Placement Test được phát triển bởi đội ngũ học thuật của <strong>EDTIKA.</strong></p>

            <p class="pt-intro-desc">Bài kiểm tra thông minh tự động điều chỉnh độ khó theo năng lực của bạn. Sau khi hoàn thành, hệ thống sẽ xác định chính xác năng lực hiện tại, phân tích điểm mạnh và điểm cần cải thiện, đồng thời đề xuất lộ trình học phù hợp với mục tiêu của bạn.</p>

            <div class="pt-feature-grid">
                <!-- Box 1: Adaptive -->
                <div class="pt-feature-card">
                    <div class="pt-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2" ry="2"/>
                            <rect x="9" y="9" width="6" height="6"/>
                            <line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/>
                            <line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>
                            <line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/>
                            <line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>
                        </svg>
                    </div>
                    <h5>Adaptive</h5>
                    <p>Độ khó tự động điều chỉnh theo năng lực</p>
                </div>

                <!-- Box 2: Chuẩn CEFR -->
                <div class="pt-feature-card">
                    <div class="pt-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            <polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </div>
                    <h5>Chuẩn CEFR</h5>
                    <p>Đánh giá theo khung tham chiếu Cambridge</p>
                </div>

                <!-- Box 3: Kết quả chính xác -->
                <div class="pt-feature-card">
                    <div class="pt-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <circle cx="12" cy="12" r="6"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <h5>Kết quả chính xác</h5>
                    <p>Ước lượng trình độ hiện tại nhanh chóng</p>
                </div>

                <!-- Box 4: Báo cáo chi tiết -->
                <div class="pt-feature-card">
                    <div class="pt-feature-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="14" width="4" height="6"/><rect x="10" y="8" width="4" height="12"/><rect x="17" y="4" width="4" height="16"/>
                        </svg>
                    </div>
                    <h5>Báo cáo chi tiết</h5>
                    <p>Chỉ ra điểm mạnh, điểm yếu và lộ trình học</p>
                </div>
            </div>

            <div class="pt-tip-banner">
                <svg viewBox="0 0 24 24" fill="none" stroke="#5B21B6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
                <p>Bắt đầu hành trình học hiệu quả bằng việc hiểu đúng trình độ hiện tại của bạn. Chỉ mất khoảng <strong>30 phút</strong> để nhận báo cáo đánh giá chi tiết và lộ trình học được <strong class="is-accent">cá nhân hóa</strong> theo mục tiêu!</p>
            </div>

            <a href="{{ route('placement.mic_check') }}" class="pt-start-btn">
                Bắt đầu
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- ── PHẢI: Hình ảnh Robot ── --}}
        <div class="pt-intro-right">

            {{-- Ngôi sao 4 cánh lấp lánh --}}
            <svg class="pt-star" aria-hidden="true" style="top:30%; right:9%" width="22" height="22" viewBox="0 0 24 24" fill="#C4B5FD"><path d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <svg class="pt-star" aria-hidden="true" style="top:44%; left:16%" width="16" height="16" viewBox="0 0 24 24" fill="#C4B5FD"><path d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <svg class="pt-star" aria-hidden="true" style="bottom:22%; left:10%" width="22" height="22" viewBox="0 0 24 24" fill="#C4B5FD"><path d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>
            <svg class="pt-star" aria-hidden="true" style="top:9%; right:34%" width="12" height="12" viewBox="0 0 24 24" fill="#DDD6FE"><path d="M12 0c.9 6.6 5.4 11.1 12 12-6.6.9-11.1 5.4-12 12-.9-6.6-5.4-11.1-12-12C6.6 11.1 11.1 6.6 12 0z"/></svg>

            <div class="pt-chat-bubble">
                <h4>Xin chào! Mình là <span>Edti</span> 👋</h4>
                <p>Mình sẽ giúp bạn xác định chính xác trình độ tiếng Anh chỉ trong khoảng <strong>30 phút</strong>.</p>
            </div>

            <div class="pt-glass-badge pt-badge-free">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                <span>100%<br>MIỄN PHÍ</span>
            </div>

            <div class="pt-glass-badge pt-badge-time">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>30<br>PHÚT</span>
            </div>

            <div class="pt-glass-badge pt-badge-tag">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 2.5 2.5 0 0 1-.96-4.43 2.5 2.5 0 0 1 2.45-3.9 2.5 2.5 0 0 1 3.93-2.53A2.5 2.5 0 0 1 9.5 2Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 2.5 2.5 0 0 0 .96-4.43 2.5 2.5 0 0 0-2.45-3.9 2.5 2.5 0 0 0-3.93-2.53A2.5 2.5 0 0 0 14.5 2Z"/></svg>
                <span>ADAPTIVE<br>PLACEMENT<br>TEST</span>
            </div>

            <picture class="pt-robot-pic">
                <source srcset="{{ asset('assets/placement/edti-robot-podium.webp') }}" type="image/webp">
                <img src="{{ asset('assets/placement/edti-robot-podium.png') }}"
                     alt="Edti — trợ lý của EDTIKA"
                     width="900" height="1154"
                     fetchpriority="high"
                     class="pt-robot-img">
            </picture>

        </div>
    </div>
</div>
@endsection