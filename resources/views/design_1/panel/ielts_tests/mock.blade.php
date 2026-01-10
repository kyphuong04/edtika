@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .page-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* Page Header */
    .page-header {
        margin-bottom: 32px;
    }
    .page-header h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }
    .page-header p {
        color: #6b7280;
        margin: 0;
    }

    /* Mock Test Card */
    .mock-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .mock-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }
    .mock-card::before {
        content: '';
        display: block;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .mock-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .mock-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .mock-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #ede9fe;
        color: #7c3aed;
    }

    /* Skills Grid */
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        padding: 24px;
    }
    @media (max-width: 768px) {
        .skills-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }

    /* Skill Item */
    .skill-item {
        text-align: center;
        padding: 20px 16px;
        border-right: 1px solid #f3f4f6;
    }
    .skill-item:last-child {
        border-right: none;
    }
    @media (max-width: 768px) {
        .skill-item {
            border-right: none;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
        }
    }

    .skill-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 22px;
    }
    .skill-item.listening .skill-icon { background: #dbeafe; color: #3b82f6; }
    .skill-item.reading .skill-icon { background: #d1fae5; color: #10b981; }
    .skill-item.writing .skill-icon { background: #fef3c7; color: #f59e0b; }
    .skill-item.speaking .skill-icon { background: #fee2e2; color: #ef4444; }

    .skill-name {
        font-size: 15px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 12px;
    }
    .skill-duration {
        font-size: 12px;
        color: #9ca3af;
        margin-bottom: 16px;
    }

    .skill-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    .skill-status.pending {
        background: #f3f4f6;
        color: #6b7280;
    }
    .skill-status.completed {
        background: #d1fae5;
        color: #059669;
    }

    /* Full Test Footer */
    .full-test-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .full-test-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .full-test-icon {
        font-size: 24px;
        opacity: 0.9;
    }
    .full-test-details h4 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 4px;
    }
    .full-test-details p {
        font-size: 13px;
        opacity: 0.8;
        margin: 0;
    }

    .btn-full-start {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 32px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 15px;
        background: white;
        color: #7c3aed;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-full-start:hover {
        transform: scale(1.05);
        text-decoration: none;
        color: #7c3aed;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    /* Attempts Info */
    .attempts-info {
        background: #fef3c7;
        color: #92400e;
        padding: 12px 28px;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #f9fafb;
        border-radius: 20px;
        border: 2px dashed #e5e7eb;
    }
    .empty-state img {
        max-width: 180px;
        margin-bottom: 24px;
        opacity: 0.7;
    }
    .empty-state h3 {
        font-size: 20px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #6b7280;
        margin: 0;
    }

    /* Daily Limit Badge */
    .daily-limit-badge {
        flex-shrink: 0;
    }
    .limit-box {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 14px;
    }
    .limit-box.success {
        background: #d1fae5;
        color: #059669;
    }
    .limit-box.warning {
        background: #fee2e2;
        color: #dc2626;
    }
    .limit-box i {
        font-size: 18px;
    }

    /* Cannot Take States */
    .cannot-take-reason {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background: #fef3c7;
        color: #92400e;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="page-container mt-30">
    <!-- Page Header with Daily Limit -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1>Mock Tests</h1>
                <p>Mô phỏng bài thi IELTS thực tế với đầy đủ 4 kỹ năng</p>
            </div>
            <div class="daily-limit-badge">
                @if(isset($remainingToday) && $remainingToday > 0)
                    <div class="limit-box success">
                        <i class="fas fa-check-circle"></i>
                        <span>Còn <strong>{{ $remainingToday }}</strong>/{{ $dailyLimit ?? 2 }} lượt hôm nay</span>
                    </div>
                @else
                    <div class="limit-box warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Hết lượt hôm nay</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($mockTests->isEmpty())
        <div class="empty-state">
            <img src="/assets/default/img/no-results/support.png" alt="">
            <h3>Chưa có Mock Tests</h3>
            <p>Mock tests sẽ được thêm sớm!</p>
        </div>
    @else
        @foreach($mockTests as $test)
        <div class="mock-card">
            <div class="mock-card-header">
                <h2 class="mock-card-title">{{ $test->title }}</h2>
                <span class="mock-badge">
                    @if($test->is_free)
                        Free
                    @else
                        Mock Test
                    @endif
                </span>
            </div>

            @if($test->user_attempts > 0)
            <div class="attempts-info">
                <span>
                    <i class="fas fa-history mr-2"></i>
                    Đã làm {{ $test->user_attempts }} lần
                    @if($test->best_attempt && $test->best_attempt->overall_band)
                        • Điểm cao nhất: Band {{ $test->best_attempt->overall_band }}
                    @endif
                </span>
                <a href="#" class="text-warning font-weight-bold">Xem kết quả</a>
            </div>
            @endif

            <div class="skills-grid">
                <!-- Listening -->
                <div class="skill-item listening">
                    <div class="skill-icon">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="skill-name">Listening</div>
                    <div class="skill-duration">{{ $test->listening_duration ?? 30 }} phút</div>
                    <div class="skill-status pending">
                        <i class="far fa-clock"></i> Chờ làm
                    </div>
                </div>

                <!-- Reading -->
                <div class="skill-item reading">
                    <div class="skill-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="skill-name">Reading</div>
                    <div class="skill-duration">{{ $test->reading_duration ?? 60 }} phút</div>
                    <div class="skill-status pending">
                        <i class="far fa-clock"></i> Chờ làm
                    </div>
                </div>

                <!-- Writing -->
                <div class="skill-item writing">
                    <div class="skill-icon">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                    <div class="skill-name">Writing</div>
                    <div class="skill-duration">{{ $test->writing_duration ?? 60 }} phút</div>
                    <div class="skill-status pending">
                        <i class="far fa-clock"></i> Chờ làm
                    </div>
                </div>

                <!-- Speaking -->
                <div class="skill-item speaking">
                    <div class="skill-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="skill-name">Speaking</div>
                    <div class="skill-duration">{{ $test->speaking_duration ?? 15 }} phút</div>
                    <div class="skill-status pending">
                        <i class="far fa-clock"></i> Chờ làm
                    </div>
                </div>
            </div>

            <!-- Full Test Footer -->
            <div class="full-test-footer">
                <div class="full-test-info">
                    <span class="full-test-icon"><i class="fas fa-th-large"></i></span>
                    <div class="full-test-details">
                        <h4>Full Test</h4>
                        <p>{{ $test->total_duration }} phút • 4 kỹ năng liên tiếp</p>
                    </div>
                </div>
                @if($test->can_take === true)
                <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-full-start">
                        <i class="fas fa-play"></i> Bắt đầu làm bài
                    </button>
                </form>
                @elseif($test->can_take === 'daily_limit')
                <div class="cannot-take-reason">
                    <i class="fas fa-clock"></i>
                    <span>Hết lượt hôm nay, quay lại ngày mai!</span>
                </div>
                @elseif($test->can_take === 'max_attempts')
                <div class="cannot-take-reason">
                    <i class="fas fa-lock"></i>
                    <span>Đã đạt giới hạn 3 lần làm bài</span>
                </div>
                @elseif($test->can_take === 'not_enrolled')
                <a href="#" class="btn-full-start" style="background: #fef3c7; color: #92400e;">
                    <i class="fas fa-shopping-cart"></i> Đăng ký khóa học
                </a>
                @else
                <span class="btn-full-start" style="opacity: 0.6; cursor: not-allowed;">
                    <i class="fas fa-lock"></i> Không khả dụng
                </span>
                @endif
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
