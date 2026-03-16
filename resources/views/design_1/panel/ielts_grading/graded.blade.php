@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    /* ── Graded List Page ────────────────────────────────────── */
    .gl-page-header {
        background: #fff;
        border-radius: 14px;
        padding: 18px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .gt-main-content { padding-left: 20px; padding-right: 20px; }
    .gl-page-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }
    .gl-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 16px;
        border-radius: 20px;
        background: #fff;
        border: 1px solid #d1d5db;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        text-decoration: none;
        transition: background .15s;
    }
    .gl-back-btn:hover { background: #e5e7eb; color: #111827; text-decoration: none; }

    .gl-card {
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
    .gl-card:hover { box-shadow: 0 2px 10px rgba(0,0,0,.09); }
    .gl-card__info { min-width: 0; flex: 1; }
    .gl-card__title {
        font-size: 13px;
        font-weight: 700;
        color: #111827;
        text-transform: uppercase;
        letter-spacing: .02em;
        word-break: break-word;
    }
    .gl-card__meta {
        font-size: 12px;
        color: #6b7280;
        margin-top: 3px;
    }
    .gl-card__actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }
    .gl-band-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 14px;
        border-radius: 20px;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
        font-size: 13px;
        font-weight: 600;
        color: #065f46;
        white-space: nowrap;
    }
    .gl-view-btn {
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
        transition: background .15s;
    }
    .gl-view-btn:hover { background: #f3f4f6; color: #111827; text-decoration: none; }

    .gl-empty {
        text-align: center;
        padding: 48px 0;
        font-size: 14px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="container-fluid gt-main-content" style="padding-bottom: 40px;">

    {{-- Page header --}}
    <div class="gl-page-header">
        <h2 class="gl-page-title mb-0">
            Danh sách các bài {{ $skill === 'writing' ? 'Writing' : 'Speaking' }} đã chấm
        </h2>
        <a href="{{ route('panel.ielts_grading.index', ['skill' => $skill]) }}" class="gl-back-btn">
            ← Quay lại hàng chờ
        </a>
    </div>

    {{-- Graded attempts list --}}
    @if($attempts->count() > 0)
        @foreach($attempts as $attempt)
            @php
                $testTitle   = $attempt->test->title ?? 'Unknown Test';
                $studentName = $attempt->user->full_name ?? 'Student';
                $courseName  = optional(optional($attempt->test)->webinar)->title ?? '-';
                $band        = $skill === 'writing' ? $attempt->writing_band : $attempt->speaking_band;
                $gradedAt    = $skill === 'writing' ? $attempt->writing_graded_at : $attempt->speaking_graded_at;
            @endphp
            <div class="gl-card">
                <div class="gl-card__info">
                    <div class="gl-card__title">
                        {{ strtoupper($skill) }} - {{ strtoupper($testTitle) }} - {{ strtoupper($studentName) }}
                    </div>
                    <div class="gl-card__meta">Khóa học: {{ $courseName }}</div>
                    @if($gradedAt)
                        <div class="gl-card__meta">Ngày chấm: {{ date('d/m/Y H:i', $gradedAt) }}</div>
                    @endif
                </div>
                <div class="gl-card__actions">
                    <span class="gl-band-badge">
                        Band: {{ $band !== null ? number_format((float)$band, 1) : '-' }}
                    </span>
                    <a href="{{ route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => $skill]) }}"
                       class="gl-view-btn">
                        Xem chi tiết &nbsp;<x-iconsax-lin-arrow-right class="icons" width="13px" height="13px"/>
                    </a>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $attempts->appends(request()->query())->links() }}
        </div>
    @else
        <div class="gl-empty">
            Chưa có bài {{ $skill === 'writing' ? 'Writing' : 'Speaking' }} nào được chấm
        </div>
    @endif

</div>
@endsection
