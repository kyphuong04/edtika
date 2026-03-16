@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    /* ── Grade Tests Page ───────────────────────────────────── */
    .gt-main-content { padding-left: 20px; padding-right: 20px; }
    .gt-info-box {
        background: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        min-height: 108px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .gt-info-box__label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
        text-align: center;
        margin-bottom: 9px;
    }
    .gt-info-box {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: flex-start; /* keep label at top */
        align-items: center;
    }

    .gt-info-box .gt-go-btn { margin: 0 auto; }
    .gt-info-box__number {
        font-size: 38px;
        font-weight: 700;
        color: #111827;
        line-height: 1;
    }
    .gt-go-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        justify-content: center;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #d1d5db;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: background .15s;
        white-space: nowrap;
        min-width: 60px;
        max-width: 90px;
    }
    .gt-go-btn:hover { background: #e5e7eb; color: #111827; text-decoration: none; }

    /* ── Queue Sections ─────────────────────────────────────── */
    .gt-queue-section {
        background: #fff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-top: 20px;
    }
    .gt-queue-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    .gt-queue-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }
    .gt-total-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 14px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #d1d5db;
        font-size: 12px;
        color: #374151;
        font-weight: 500;
    }
    .gt-queue-scroll {
        max-height: 370px;
        overflow-y: auto;
        padding-right: 2px;
    }
    .gt-queue-scroll::-webkit-scrollbar { width: 5px; }
    .gt-queue-scroll::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
    .gt-queue-scroll::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 3px; }

    /* ── Submission Card ─────────────────────────────────────── */
    .gt-card {
        background: #fff;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 10px;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        transition: box-shadow .15s;
    }
    .gt-card:last-child { margin-bottom: 0; }
    .gt-card:hover { box-shadow: 0 2px 10px rgba(0,0,0,.09); }
    .gt-card__info { min-width: 0; flex: 1; text-align: start; }
    .gt-card__title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: .02em;
        word-break: break-word;
    }
    .gt-card__meta {
        font-size: 12px;
        color: #6b7280;
        margin-top: 3px;
    }
    .gt-card__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .gt-ai-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border-radius: 20px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
    }
    .gt-grade-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 16px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #d1d5db;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s, border-color .15s;
    }
    .gt-grade-btn:hover { background: #f3f4f6; color: #111827; text-decoration: none; }

    .gt-empty {
        text-align: center;
        padding: 28px 0;
        font-size: 13px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="container-fluid gt-main-content" style="padding-bottom:40px;">

    {{-- ── Top 3 info boxes ──────────────────────────────────── --}}
    <div class="row g-3 mb-1">

        {{-- Box 1 : Graded today --}}
        <div class="col-12 col-md-4">
            <div class="gt-info-box">
                <div class="gt-info-box__label">Số bài đã chấm hôm nay</div>
                <div class="gt-info-box__number">{{ $gradedTodayCount }}</div>
            </div>
        </div>

        {{-- Box 2 : Go to graded list --}}
        <div class="col-12 col-md-4">
            <div class="gt-info-box">
                <div class="gt-info-box__label">Danh sách các bài đã chấm</div>
                <a href="{{ route('panel.ielts_grading.graded', ['skill' => $skill]) }}" class="gt-go-btn">
                    Go &nbsp;<x-iconsax-lin-arrow-right class="icons" width="14px" height="14px"/>
                </a>
            </div>
        </div>

        {{-- Box 3 : Switch to the other skill --}}
        @php $otherSkill = $skill === 'writing' ? 'speaking' : 'writing'; @endphp
        <div class="col-12 col-md-4">
            <div class="gt-info-box">
                <div class="gt-info-box__label">
                    Hàng chờ {{ $skill === 'writing' ? 'Speaking' : 'Writing' }}
                </div>
                <a href="{{ route('panel.ielts_grading.index', ['skill' => $otherSkill]) }}" class="gt-go-btn">
                    Go &nbsp;<x-iconsax-lin-arrow-right class="icons" width="14px" height="14px"/>
                </a>
            </div>
        </div>

    </div>

    {{-- ── Alert Queue ────────────────────────────────────────── --}}
    @php $skillLabel = $skill === 'writing' ? 'Writing' : 'Speaking'; @endphp

    <div class="gt-queue-section">
        <div class="gt-queue-header">
            <span class="gt-queue-title">Hàng chờ {{ $skillLabel }} (Alert)</span>
            <span class="gt-total-badge">Tổng số bài: {{ $alertAttempts->count() }} bài</span>
        </div>

        @if($alertAttempts->count() > 0)
            <div class="gt-queue-scroll">
                @foreach($alertAttempts as $attempt)
                    @php
                        $testTitle   = $attempt->test->title ?? 'Unknown Test';
                        $studentName = $attempt->user->full_name ?? 'Student';
                        $courseName  = optional(optional($attempt->test)->webinar)->title ?? '-';
                        $aiScore     = $attempt->ai_score_display;
                        $aimBand     = $attempt->aim_band_display;
                    @endphp
                    <div class="gt-card">
                        <div class="gt-card__info">
                            <div class="gt-card__title">
                                {{ strtoupper($skill) }} - {{ strtoupper($testTitle) }} - {{ strtoupper($studentName) }}
                            </div>
                            <div class="gt-card__meta">Aim band: {{ (isset($aimBand) && $aimBand !== null && $aimBand !== '') ? $aimBand : '-' }}</div>
                            <div class="gt-card__meta">Khóa học: {{ (isset($courseName) && $courseName !== '-' && $courseName !== null && $courseName !== '') ? $courseName : '-' }}</div>
                        </div>
                        <div class="gt-card__actions">
                            <span class="gt-ai-badge">
                                Điểm AI: {{ $aiScore !== null ? number_format((float)$aiScore, 1) : '-' }}
                            </span>
                            <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => $skill]) }}"
                               class="gt-grade-btn">
                                Chấm điểm &nbsp;<x-iconsax-lin-arrow-right class="icons" width="13px" height="13px"/>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="gt-empty">Không có bài nào trong hàng chờ Alert</div>
        @endif
    </div>

    {{-- ── Normal Queue ───────────────────────────────────────── --}}
    <div class="gt-queue-section">
        <div class="gt-queue-header">
            <span class="gt-queue-title">Hàng chờ {{ $skillLabel }}</span>
            <span class="gt-total-badge">Tổng số bài: {{ $normalAttempts->count() }} bài</span>
        </div>

        @if($normalAttempts->count() > 0)
            <div class="gt-queue-scroll">
                @foreach($normalAttempts as $attempt)
                    @php
                        $testTitle   = $attempt->test->title ?? 'Unknown Test';
                        $studentName = $attempt->user->full_name ?? 'Student';
                        $courseName  = optional(optional($attempt->test)->webinar)->title ?? '-';
                        $aiScore     = $attempt->ai_score_display;
                        $aimBand     = $attempt->aim_band_display;
                    @endphp
                    <div class="gt-card">
                        <div class="gt-card__info">
                            <div class="gt-card__title">
                                {{ strtoupper($skill) }} - {{ strtoupper($testTitle) }} - {{ strtoupper($studentName) }}
                            </div>
                            <div class="gt-card__meta">Aim band: {{ (isset($aimBand) && $aimBand !== null && $aimBand !== '') ? $aimBand : '-' }}</div>
                            <div class="gt-card__meta">Khóa học: {{ (isset($courseName) && $courseName !== '-' && $courseName !== null && $courseName !== '') ? $courseName : '-' }}</div>
                        </div>
                        <div class="gt-card__actions">
                            <span class="gt-ai-badge">
                                Điểm AI: {{ $aiScore !== null ? number_format((float)$aiScore, 1) : '-' }}
                            </span>
                            <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => $skill]) }}"
                               class="gt-grade-btn">
                                Chấm điểm &nbsp;<x-iconsax-lin-arrow-right class="icons" width="13px" height="13px"/>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="gt-empty">Không có bài nào đang chờ chấm</div>
        @endif
    </div>

</div>
@endsection
                    
