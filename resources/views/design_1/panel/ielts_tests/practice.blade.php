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

    /* Band Filter Tabs */
    .band-tabs {
        display: flex;
        gap: 12px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }
    .band-tab {
        padding: 10px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        transition: all 0.2s;
        text-decoration: none;
    }
    .band-tab:hover {
        border-color: #3b82f6;
        color: #3b82f6;
        text-decoration: none;
    }
    .band-tab.active {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    /* Practice Test Card */
    .practice-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .practice-card:hover {
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    }

    .practice-card-header {
        padding: 24px 28px 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .practice-card-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
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
    @media (max-width: 480px) {
        .skills-grid {
            grid-template-columns: 1fr;
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
        margin-bottom: 16px;
    }

    /* Skill Start Button */
    .btn-skill-start {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 22px;
        border-radius: 22px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        color: white;
    }
    .btn-skill-start:hover {
        transform: scale(1.05);
        text-decoration: none;
        color: white;
    }
    .skill-item.listening .btn-skill-start { background: #3b82f6; }
    .skill-item.reading .btn-skill-start { background: #10b981; }
    .skill-item.writing .btn-skill-start { background: #f59e0b; }
    .skill-item.speaking .btn-skill-start { background: #ef4444; }
    .skill-item.listening .btn-skill-start:hover { background: #2563eb; }
    .skill-item.reading .btn-skill-start:hover { background: #059669; }
    .skill-item.writing .btn-skill-start:hover { background: #d97706; }
    .skill-item.speaking .btn-skill-start:hover { background: #dc2626; }

    .skill-status {
        margin-top: 12px;
        font-size: 18px;
        color: #d1d5db;
    }
    .skill-status.unlocked { color: #10b981; }

    /* Full Test Footer */
    .full-test-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 28px;
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        position: relative;
    }
    .full-test-footer::before {
        content: 'NEW';
        position: absolute;
        top: -8px;
        left: 20px;
        background: #ef4444;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 4px;
    }
    .full-test-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .full-test-icon {
        font-size: 18px;
        color: #475569;
    }
    .full-test-label {
        font-size: 15px;
        font-weight: 600;
        color: #334155;
    }
    .full-test-label sup {
        color: #94a3b8;
        font-size: 11px;
    }

    /* Progress Bar */
    .progress-container {
        flex: 1;
        max-width: 200px;
        margin: 0 24px;
    }
    .progress-bar {
        height: 8px;
        background: #cbd5e1;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #3b82f6, #10b981);
        border-radius: 4px;
        transition: width 0.3s;
    }
    .progress-text {
        font-size: 12px;
        color: #64748b;
        text-align: center;
        margin-top: 4px;
    }

    /* Full Test Start Button */
    .btn-full-start {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        background: #334155;
        color: white;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-full-start:hover {
        background: #1e293b;
        transform: scale(1.02);
        text-decoration: none;
        color: white;
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

    /* Unlimited Badge */
    .unlimited-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        background: #d1fae5;
        color: #059669;
        font-size: 14px;
        font-weight: 600;
    }
    .unlimited-badge i {
        font-size: 18px;
    }
</style>
@endpush

@section('content')
<div class="page-container mt-30">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
            <div>
                <h1>{{ trans('update.practice_tests') }}</h1>
                <p>{{ trans('update.practice_tests_hint') }}</p>
            </div>
            <div class="unlimited-badge">
                <i class="fas fa-infinity"></i>
                <span>{{ trans('update.unlimited_attempts') }}</span>
            </div>
        </div>
    </div>

    <!-- Band Score Tabs -->
    <div class="band-tabs">
        <a href="{{ route('panel.ielts_tests.practice') }}" class="band-tab {{ !request('band') ? 'active' : '' }}">
            {{ trans('admin/main.all') }}
        </a>
        <a href="{{ route('panel.ielts_tests.practice', ['band' => '4-5']) }}" class="band-tab {{ request('band') === '4-5' ? 'active' : '' }}">
            Band 4.0 - 5.0
        </a>
        <a href="{{ route('panel.ielts_tests.practice', ['band' => '5-6']) }}" class="band-tab {{ request('band') === '5-6' ? 'active' : '' }}">
            Band 5.0 - 6.0
        </a>
        <a href="{{ route('panel.ielts_tests.practice', ['band' => '6-7']) }}" class="band-tab {{ request('band') === '6-7' ? 'active' : '' }}">
            Band 6.0 - 7.0
        </a>
        <a href="{{ route('panel.ielts_tests.practice', ['band' => '7-8']) }}" class="band-tab {{ request('band') === '7-8' ? 'active' : '' }}">
            Band 7.0 - 8.0
        </a>
    </div>

    @if($practiceTests->isEmpty())
        <div class="empty-state">
            <img src="/assets/default/img/no-results/support.png" alt="">
            <h3>{{ trans('update.no_practice_tests_available') }}</h3>
            <p>{{ trans('update.no_practice_tests_hint') }}</p>
        </div>
    @else
        @foreach($practiceTests as $test)
        <div class="practice-card">
            <div class="practice-card-header">
                <h2 class="practice-card-title">{{ $test->title }}</h2>
            </div>

            <div class="skills-grid">
                <!-- Listening -->
                @if($test->has_listening)
                <div class="skill-item listening">
                    <div class="skill-icon">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="skill-name">{{ trans('update.listening') }}</div>
                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="skill" value="listening">
                        <button type="submit" class="btn-skill-start">
                            <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                        </button>
                    </form>
                    <div class="skill-status unlocked">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                @endif

                <!-- Reading -->
                @if($test->has_reading)
                <div class="skill-item reading">
                    <div class="skill-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="skill-name">{{ trans('update.reading') }}</div>
                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="skill" value="reading">
                        <button type="submit" class="btn-skill-start">
                            <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                        </button>
                    </form>
                    <div class="skill-status unlocked">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                @endif

                <!-- Writing -->
                @if($test->has_writing)
                <div class="skill-item writing">
                    <div class="skill-icon">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                    <div class="skill-name">{{ trans('update.writing') }}</div>
                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="skill" value="writing">
                        <button type="submit" class="btn-skill-start">
                            <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                        </button>
                    </form>
                    <div class="skill-status unlocked">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                @endif

                <!-- Speaking -->
                @if($test->has_speaking)
                <div class="skill-item speaking">
                    <div class="skill-icon">
                        <i class="fas fa-microphone"></i>
                    </div>
                    <div class="skill-name">{{ trans('update.speaking') }}</div>
                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="skill" value="speaking">
                        <button type="submit" class="btn-skill-start">
                            <i class="fas fa-bolt"></i> {{ trans('update.take_test') }}
                        </button>
                    </form>
                    <div class="skill-status unlocked">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                @endif
            </div>

            <!-- Full Test Footer -->
            <div class="full-test-footer">
                <div class="full-test-info">
                    <span class="full-test-icon"><i class="fas fa-th-large"></i></span>
                    <span class="full-test-label">{{ trans('update.full_test') }}<sup>®</sup></span>
                </div>
                <div class="progress-container">
                    @php
                        $progress = $test->user_attempts > 0 ? min(100, ($test->user_attempts * 25)) : 0;
                    @endphp
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $progress }}%"></div>
                    </div>
                    <div class="progress-text">{{ $progress }}%</div>
                </div>
                <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-full-start">
                        <i class="fas fa-bolt"></i> {{ trans('admin/main.start') }}
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
