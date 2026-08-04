@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = false;
    $floatingBar = null;
    $dontShowCookieSecurity = true;
@endphp

@push('styles_top')
<style>
/* Reset Trang (Bỏ overflow: hidden để trang tự do cuộn) */
html, body {
    background-color: #F8FAFC;
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

.pt-mini-header {
    max-width: 1400px; margin: 0 auto; padding: 16px 24px 0; height: 56px;
    display: flex; align-items: center;
}
.pt-mini-header .pt-brand {
    font-size: 22px; font-weight: 900; color: #1E1B4B; text-decoration: none;
}

.pt-intro-wrap {
    max-width: 1400px;
    min-height: calc(100vh - 72px);
    margin: 0 auto; 
    padding: 24px;
    display: flex; 
    align-items: stretch; 
}

/* Chuyển từ dạng Card sang Layout Split Screen */
.pt-intro-layout {
    display: grid; 
    grid-template-columns: 1.1fr 0.9fr; /* Tỉ lệ 55:45 */
    gap: 60px;
    width: 100%; 
}

/* ── LEFT COLUMN (Nội dung tràn tự nhiên, không cần scrollbar riêng) ── */
.pt-intro-left {
    display: flex; 
    flex-direction: column; 
    justify-content: center; /* Căn giữa nội dung theo chiều dọc */
    padding-bottom: 40px;
}

.pt-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    background: #EEF2FF; color: #4338CA;
    font-size: 11px; font-weight: 800; letter-spacing: 0.5px;
    padding: 6px 14px; border-radius: 99px; margin-bottom: 16px; text-transform: uppercase;
    width: fit-content;
}

.pt-title-main {
    font-size: 46px; 
    line-height: 1.15; 
    font-weight: 900; 
    color: #0F172A;
    margin: 0 0 6px 0; 
    letter-spacing: -0.5px;
}
.pt-title-sub {
    font-size: 26px; 
    line-height: 1.3;
    font-weight: 800;
    color: #5B21B6; 
    margin: 0 0 16px 0;
}
.pt-title-sub span {
    color: #6D28D9;
}

.pt-meta-row {
    display: flex; align-items: center; gap: 16px; margin-bottom: 24px;
}
.pt-meta-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 14px; font-weight: 700; color: #1E293B;
}
.pt-meta-divider { color: #CBD5E1; }

.pt-intro-desc {
    font-size: 15px; line-height: 1.6; color: #334155; margin: 0 0 16px 0;
}
.pt-intro-desc strong { color: #5B21B6; font-weight: 800; } 

/* Chuyển grid thành tự động co giãn để đẹp hơn trên trang rộng */
.pt-feature-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); 
    gap: 16px; 
    margin-bottom: 24px;
}
.pt-feature-card {
    background: #FFFFFF; border: 1px solid #E2E8F0;
    border-radius: 16px; padding: 16px 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}
.pt-feature-icon {
    width: 32px; height: 32px; border-radius: 8px; background: #EEF2FF; color: #4338CA;
    display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.pt-feature-card h5 { font-size: 14px; font-weight: 800; color: #0F172A; margin: 0 0 6px 0; }
.pt-feature-card p { font-size: 12.5px; line-height: 1.45; color: #475569; margin: 0; }

.pt-tip-banner {
    display: flex; gap: 12px; align-items: flex-start;
    background: #FFFFFF; border: 1px solid #E2E8F0; border-radius: 16px;
    padding: 16px 20px; margin-bottom: 32px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
}
.pt-tip-banner svg { width: 24px; height: 24px; flex-shrink: 0; margin-top: 2px; }
.pt-tip-banner p { font-size: 13.5px; line-height: 1.55; color: #1E293B; margin: 0; }
.pt-tip-banner strong { color: #4338CA; font-weight: 800; }

.pt-start-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    background: linear-gradient(90deg, #6D28D9 0%, #4338CA 100%);
    color: #FFF; border: none; border-radius: 12px;
    padding: 16px 40px; font-weight: 700; font-size: 16px;
    text-decoration: none; cursor: pointer; width: fit-content;
    box-shadow: 0 8px 20px -6px rgba(79, 70, 229, 0.6);
    transition: all 0.2s ease;
}
.pt-start-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 24px -6px rgba(79, 70, 229, 0.8); color: #FFF; }

/* ── RIGHT COLUMN (Mascot & Background) ── */
.pt-intro-right {
    position: relative; 
    background: radial-gradient(circle at 50% 40%, #B490FF 0%, #8B5CF6 40%, #5B21B6 100%);
    border-radius: 32px; /* Thêm bo góc vì đã bỏ Card Wrapper */
    display: flex; align-items: center; justify-content: center; overflow: hidden;
    min-height: 600px; /* Chiều cao tối thiểu để hiện đẹp con robot */
}

.pt-chat-bubble {
    position: absolute; top: 10%; left: 8%; z-index: 10;
    background: #FFF; border-radius: 16px; padding: 14px 16px;
    width: 210px; box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}
.pt-chat-bubble::after {
    content: ''; position: absolute;
    bottom: -10px; right: 40px; 
    border-width: 12px 12px 0 0;
    border-style: solid;
    border-color: #FFF transparent transparent transparent;
}
.pt-chat-bubble h4 { margin: 0 0 6px 0; font-size: 13px; color: #0F172A; font-weight: 800; }
.pt-chat-bubble h4 span { color: #6D28D9; }
.pt-chat-bubble p { margin: 0; font-size: 12px; line-height: 1.5; color: #334155; }
.pt-chat-bubble strong { color: #6D28D9; }

/* Robot Image */
.pt-robot-img {
    position: relative; z-index: 5; 
    width: 100%; max-width: 800px;
    object-fit: contain; 
    transform: translateY(20px);
}

/* Glass Badges */
.pt-glass-badge {
    position: absolute; z-index: 10;
    background: rgba(255, 255, 255, 0.1); 
    backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
    border: 1.5px solid rgba(255, 255, 255, 0.6); 
    border-radius: 50%; 
    color: #FFF; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;
    box-shadow: 0 0 20px rgba(255,255,255,0.3), inset 0 0 15px rgba(255,255,255,0.2);
}
.pt-badge-free { top: 15%; right: 8%; width: 95px; height: 95px; }
.pt-badge-time { bottom: 35%; left: 6%; width: 85px; height: 85px; }
.pt-badge-tag { bottom: 18%; right: 5%; width: 110px; height: 110px; }

.pt-glass-badge span { font-weight: 800; font-size: 10.5px; line-height: 1.25; margin-top: 4px; }
.pt-badge-time span { font-size: 13px; } 

.pt-star { position: absolute; z-index: 2; animation: twinkle 3s infinite ease-in-out; }
/* Tăng không gian cột trái (từ 1.1:0.9 thành 1.2:0.8) để chữ đủ chỗ nằm 1 hàng */
.pt-intro-layout {
    display: grid; 
    grid-template-columns: 1.25fr 0.75fr; 
    gap: 40px; /* Giảm gap một chút để tối ưu diện tích */
    width: 100%; 
}

/* Dùng clamp() để chữ to tối đa 40px, tự động nhỏ lại nếu màn hình hẹp, ép nằm 1 dòng trên PC */
.pt-title-main {
    font-size: clamp(30px, 2.8vw, 40px); 
    line-height: 1.2; 
    font-weight: 900; 
    color: #0F172A;
    margin: 0 0 6px 0; 
    letter-spacing: -0.5px;
    white-space: nowrap; /* Bắt buộc không rớt dòng trên Desktop */
}

.pt-title-sub {
    font-size: clamp(18px, 1.8vw, 24px); 
    line-height: 1.3;
    font-weight: 800;
    color: #5B21B6; 
    margin: 0 0 20px 0;
    white-space: nowrap; /* Bắt buộc không rớt dòng trên Desktop */
}
.pt-title-sub span {
    color: #6D28D9;
}

/* ── RESPONSIVE (Xử lý cho Mobile & Tablet) ── */
@media (max-width: 1024px) {
    .pt-intro-layout {
        grid-template-columns: 1fr; /* Tràn xuống 1 cột */
        gap: 32px;
    }
    .pt-intro-right {
        order: -1;
        min-height: 450px;
    }
    /* Trên điện thoại/tablet thì BẮT BUỘC phải bỏ white-space để chữ tự động rớt dòng, nếu không sẽ bị tràn màn hình */
    .pt-title-main, .pt-title-sub { 
        white-space: normal; 
    }
    .pt-title-main { font-size: 34px; }
    .pt-title-sub { font-size: 20px; }
}   
@keyframes twinkle { 0%, 100% { opacity: 0.4; transform: scale(0.8); } 50% { opacity: 1; transform: scale(1.2); } }

/* ── RESPONSIVE (Cho màn hình nhỏ) ── */
@media (max-width: 1024px) {
    .pt-intro-layout {
        grid-template-columns: 1fr; /* Tràn xuống 1 cột */
        gap: 32px;
    }
    .pt-intro-right {
        order: -1; /* Đưa hình robot lên trên nội dung text ở Mobile */
        min-height: 450px;
    }
    .pt-title-main { font-size: 36px; }
    .pt-title-sub { font-size: 22px; }
}
@media (max-width: 640px) {
    .pt-feature-grid { grid-template-columns: 1fr 1fr; } /* 2 cột trên điện thoại */
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
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Chỉ trong 30 phút
                </div>
                <span class="pt-meta-divider">|</span>
                <div class="pt-meta-item">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="#4338CA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/><path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/><path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/><path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/></svg>
                <p>Bắt đầu hành trình học hiệu quả bằng việc hiểu đúng trình độ hiện tại của bạn. Chỉ mất khoảng <strong>30 phút</strong> để nhận báo cáo đánh giá chi tiết và lộ trình học được <strong>cá nhân hóa</strong> theo mục tiêu!</p>
            </div>

            <a href="{{ route('placement.mic_check') }}" class="pt-start-btn">
                Bắt đầu 
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>

        {{-- ── PHẢI: Hình ảnh Robot & Background ── --}}
        <div class="pt-intro-right">
            
            <svg class="pt-star" style="top:15%; right:15%" width="18" height="18" viewBox="0 0 24 24" fill="#FFF"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            <svg class="pt-star" style="top:40%; right:5%" width="12" height="12" viewBox="0 0 24 24" fill="#FFF"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>
            <svg class="pt-star" style="bottom:25%; left:20%" width="14" height="14" viewBox="0 0 24 24" fill="#FFF"><path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/></svg>

            <div class="pt-chat-bubble">
                <h4>Xin chào! Mình là <span>Edti</span> 👋</h4>
                <p>Mình sẽ giúp bạn xác định chính xác trình độ tiếng Anh chỉ trong khoảng <strong>30 phút</strong>.</p>
            </div>

            <div class="pt-glass-badge pt-badge-free">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                <span>100%<br>MIỄN PHÍ</span>
            </div>

            <div class="pt-glass-badge pt-badge-time">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>30<br>PHÚT</span>
            </div>

            <div class="pt-glass-badge pt-badge-tag">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#FFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 2.5 2.5 0 0 1-.96-4.43 2.5 2.5 0 0 1 2.45-3.9 2.5 2.5 0 0 1 3.93-2.53A2.5 2.5 0 0 1 9.5 2Z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 2.5 2.5 0 0 0 .96-4.43 2.5 2.5 0 0 0-2.45-3.9 2.5 2.5 0 0 0-3.93-2.53A2.5 2.5 0 0 0 14.5 2Z"/></svg>
                <span>ADAPTIVE<br>PLACEMENT<br>TEST</span>
            </div>

            <img src="https://res.cloudinary.com/dozs7ggs4/image/upload/v1785208045/EDTI-ROBOT-SAYHI_ai08gx.png" alt="Robot" class="pt-robot-img">
            
        </div>
    </div>
</div>
@endsection