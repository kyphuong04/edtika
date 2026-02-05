@extends('design_1.panel.layouts.panel')

@push('styles_top')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
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

    /* Section */
    .tests-section {
        margin-bottom: 48px;
    }
    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    .section-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .section-icon.mock { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
    .section-icon.practice { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    .section-title .badge {
        font-size: 13px;
        font-weight: 500;
        margin-left: 8px;
        vertical-align: middle;
    }
    .section-desc {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    /* Test Cards Grid */
    .tests-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 24px;
    }

    /* Test Card */
    .test-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .test-card:hover {
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transform: translateY(-4px);
        border-color: transparent;
    }
    .test-card.mock::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }
    .test-card.practice::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
    }

    .test-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    .test-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }
    .test-card-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .test-card-badge.mock { background: #ede9fe; color: #7c3aed; }
    .test-card-badge.practice { background: #d1fae5; color: #059669; }
    .test-card-badge.free { background: #d1fae5; color: #059669; }
    .test-card-badge.locked { background: #f3f4f6; color: #6b7280; }

    .test-card-desc {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    /* Skills Row */
    .skills-row {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .skill-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .skill-badge.listening { background: #dbeafe; color: #1e40af; }
    .skill-badge.reading { background: #d1fae5; color: #065f46; }
    .skill-badge.writing { background: #fef3c7; color: #92400e; }
    .skill-badge.speaking { background: #fee2e2; color: #991b1b; }

    /* Meta Info */
    .test-meta {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
        color: #6b7280;
        font-size: 13px;
    }
    .test-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .test-meta-item i {
        color: #9ca3af;
    }

    /* Completion Badge */
    .completion-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: #ecfdf5;
        border-radius: 10px;
        margin-bottom: 16px;
        font-size: 13px;
        color: #065f46;
    }
    .completion-badge i {
        color: #10b981;
    }

    /* Actions */
    .test-actions {
        display: flex;
        gap: 12px;
    }
    .btn-view {
        flex: 1;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        background: #f3f4f6;
        color: #374151;
        border: none;
    }
    .btn-view:hover {
        background: #e5e7eb;
        color: #1f2937;
        text-decoration: none;
    }
    .btn-start {
        flex: 1;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-align: center;
        text-decoration: none;
        transition: all 0.2s;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-start.mock { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .btn-start.practice { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .btn-start:hover {
        transform: scale(1.02);
        color: white;
        text-decoration: none;
    }
    .btn-start:disabled {
        background: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
        transform: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f9fafb;
        border-radius: 16px;
        border: 2px dashed #e5e7eb;
    }
    .empty-state img {
        max-width: 200px;
        margin-bottom: 24px;
        opacity: 0.8;
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
</style>
@endpush

@section('content')
@section('content')
<section class="mt-30">
    <!-- Page Header -->
    <div class="page-header">
        <h1>{{ trans('update.ielts_tests') }}</h1>
        <p>{{ trans('update.ielts_tests_library_hint') }}</p>
    </div>

    {{-- Mock Tests Section --}}
    <section class="tests-section">
        <div class="section-header">
            <div class="section-icon mock">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h2 class="section-title">
                    {{ trans('update.mock_tests') }}
                    <span class="badge badge-primary">{{ $mockTests->count() }}</span>
                </h2>
                <p class="section-desc">{{ trans('update.mock_tests_desc') }}</p>
            </div>
        </div>

        @if($mockTests->isEmpty())
            <div class="empty-state">
                <img src="/assets/default/img/no-results/support.png" alt="">
                <h3>{{ trans('update.no_mock_tests_available') }}</h3>
                <p>{{ trans('update.no_mock_tests_hint') }}</p>
            </div>
        @else
            <div class="tests-grid">
                @foreach($mockTests as $test)
                    <div class="test-card mock">
                        <div class="test-card-header">
                            <h3 class="test-card-title">{{ $test->title }}</h3>
                            @if($test->is_free)
                                <span class="test-card-badge free">{{ trans('update.free') }}</span>
                            @elseif($test->require_enrollment && !$test->user_enrolled)
                                <span class="test-card-badge locked">{{ trans('update.enroll_required') }}</span>
                            @else
                                <span class="test-card-badge mock">{{ trans('update.mock') }}</span>
                            @endif
                        </div>

                        @if($test->description)
                        <p class="test-card-desc">{{ Str::limit($test->description, 100) }}</p>
                        @endif

                        <div class="skills-row">
                            @if($test->has_listening)<span class="skill-badge listening">{{ trans('update.L') }}</span>@endif
                            @if($test->has_reading)<span class="skill-badge reading">{{ trans('update.R') }}</span>@endif
                            @if($test->has_writing)<span class="skill-badge writing">{{ trans('update.W') }}</span>@endif
                            @if($test->has_speaking)<span class="skill-badge speaking">{{ trans('update.S') }}</span>@endif
                        </div>

                        <div class="test-meta">
                            <div class="test-meta-item">
                                <i class="far fa-clock"></i>
                                <span>{{ $test->total_duration }} {{ trans('update.min') }}</span>
                            </div>
                            <div class="test-meta-item">
                                <i class="fas fa-question-circle"></i>
                                <span>~80 {{ trans('update.questions') }}</span>
                            </div>
                            @if($test->target_band_min && $test->target_band_max)
                            <div class="test-meta-item">
                                <i class="fas fa-bullseye"></i>
                                <span>{{ trans('update.band') }} {{ $test->target_band_min }}-{{ $test->target_band_max }}</span>
                            </div>
                            @endif
                        </div>

                        @if($test->user_attempts > 0)
                            <div class="completion-badge">
                                <i class="fas fa-check-circle"></i>
                                <span>
                                    {{ trans('admin/main.completed') }} {{ $test->user_attempts }}x
                                    @if($test->best_attempt && $test->best_attempt->overall_band)
                                        • {{ trans('update.best_score') }}: {{ trans('update.band') }} {{ $test->best_attempt->overall_band }}
                                    @endif
                                </span>
                            </div>
                        @endif

                        <div class="test-actions">
                            <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="btn-view">
                                {{ trans('update.view_details') }}
                            </a>
                            @if($test->can_take)
                                <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="flex:1;">
                                    @csrf
                                    <button type="submit" class="btn-start mock w-100">
                                        {{ trans('update.start_test') }}
                                    </button>
                                </form>
                            @else
                                <button class="btn-start" disabled style="flex:1;">
                                    {{ trans('update.max_attempts') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Practice Tests Section --}}
    <section class="tests-section">
        <div class="section-header">
            <div class="section-icon practice">
                <i class="fas fa-dumbbell"></i>
            </div>
            <div>
                <h2 class="section-title">
                    {{ trans('update.practice_tests') }}
                    <span class="badge badge-success">{{ $practiceTests->count() }}</span>
                </h2>
                <p class="section-desc">{{ trans('update.practice_tests_desc') }}</p>
            </div>
        </div>

        @if($practiceTests->isEmpty())
            <div class="empty-state">
                <img src="/assets/default/img/no-results/support.png" alt="">
                <h3>{{ trans('update.no_practice_tests_available') }}</h3>
                <p>{{ trans('update.no_practice_tests_hint') }}</p>
            </div>
        @else
            <div class="tests-grid">
                @foreach($practiceTests as $test)
                    <div class="test-card practice">
                        <div class="test-card-header">
                            <h3 class="test-card-title">{{ $test->title }}</h3>
                            <span class="test-card-badge practice">{{ trans('update.practice') }}</span>
                        </div>

                        @if($test->description)
                        <p class="test-card-desc">{{ Str::limit($test->description, 100) }}</p>
                        @endif

                        <div class="skills-row">
                            @if($test->has_listening)<span class="skill-badge listening">{{ trans('update.listening') }}</span>@endif
                            @if($test->has_reading)<span class="skill-badge reading">{{ trans('update.reading') }}</span>@endif
                            @if($test->has_writing)<span class="skill-badge writing">{{ trans('update.writing') }}</span>@endif
                            @if($test->has_speaking)<span class="skill-badge speaking">{{ trans('update.speaking') }}</span>@endif
                        </div>

                        <div class="test-meta">
                            <div class="test-meta-item">
                                <i class="far fa-clock"></i>
                                @if($test->practice_mode === 'untimed')
                                    <span>{{ trans('update.untimed') }}</span>
                                @else
                                    <span>{{ $test->total_duration }} {{ trans('update.min') }}</span>
                                @endif
                            </div>
                            <div class="test-meta-item">
                                <i class="fas fa-redo"></i>
                                <span>{{ trans('update.unlimited') }}</span>
                            </div>
                            @if($test->practiceCategory)
                            <div class="test-meta-item">
                                <i class="fas fa-folder"></i>
                                <span>{{ $test->practiceCategory->name }}</span>
                            </div>
                            @endif
                        </div>

                        @if($test->user_attempts > 0)
                            <div class="completion-badge">
                                <i class="fas fa-history"></i>
                                <span>{{ trans('update.practiced_count', ['count' => $test->user_attempts]) }}</span>
                            </div>
                        @endif

                        <div class="test-actions">
                            <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="btn-view">
                                {{ trans('update.view_details') }}
                            </a>
                            <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" style="flex:1;">
                                @csrf
                                <button type="submit" class="btn-start practice w-100">
                                    {{ trans('update.start_practice') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</section>
@endsection
