@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .test-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .test-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .skill-badge {
        padding: 4px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
        display: inline-block;
    }
    .skill-listening { background: #dbeafe; color: #1e40af; }
    .skill-reading { background: #d1fae5; color: #065f46; }
    .skill-writing { background: #fef3c7; color: #92400e; }
    .skill-speaking { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
        <h1 class="section-title">IELTS Tests</h1>
    </div>

    {{-- Mock Tests Section --}}
    <section class="mt-25">
        <h2 class="section-title mb-20">
            <i class="fas fa-clipboard-list mr-2"></i>
            Mock Tests
            <span class="badge badge-primary ml-2">{{ $mockTests->count() }}</span>
        </h2>
        <p class="text-gray mb-20">Complete IELTS exam simulations. All 4 skills in strict order.</p>

        @if($mockTests->isEmpty())
            <div class="no-result">
                <div class="no-result-logo">
                    <img src="/assets/default/img/no-results/support.png" alt="">
                </div>
                <div class="d-flex align-items-center flex-column mt-30 text-center">
                    <h2>No mock tests available!</h2>
                    <p class="mt-5 text-center">There are no mock tests available at this moment.</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($mockTests as $test)
                    <div class="col-md-6 col-lg-4">
                        <div class="test-card">
                            <div class="d-flex justify-content-between align-items-start mb-15">
                                <h3 class="font-16 font-weight-bold">{{ $test->title }}</h3>
                                <div>
                                    @if($test->is_lead_test)
                                        <span class="badge badge-warning">Diagnostic</span>
                                    @elseif($test->is_free)
                                        <span class="badge badge-success">Free</span>
                                    @elseif($test->require_enrollment)
                                        @if($test->user_enrolled)
                                            <span class="badge badge-success">Enrolled</span>
                                        @else
                                            <span class="badge badge-secondary">🔒 Requires Course</span>
                                        @endif
                                    @else
                                        <span class="badge badge-primary">Mock</span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-gray font-14 mb-15">{{ Str::limit($test->description, 100) }}</p>

                            <div class="mb-15">
                                <span class="skill-badge skill-listening">L</span>
                                <span class="skill-badge skill-reading">R</span>
                                <span class="skill-badge skill-writing">W</span>
                                <span class="skill-badge skill-speaking">S</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between text-gray font-12 mb-15">
                                <div>
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $test->total_duration }} minutes
                                </div>
                                <div>
                                    <i class="far fa-question-circle mr-1"></i>
                                    ~80 questions
                                </div>
                            </div>

                            @if($test->user_attempts > 0)
                                <div class="alert alert-success py-2 px-3 font-12 mb-15">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Completed {{ $test->user_attempts }} time(s)
                                    @if($test->best_attempt)
                                        <br>Best: Band {{ $test->best_attempt->overall_band ?? 'Pending' }}
                                    @endif
                                </div>
                            @endif

                            @if($test->target_band_min && $test->target_band_max)
                                <div class="text-gray font-12 mb-15">
                                    <i class="fas fa-bullseye mr-1"></i>
                                    Target: Band {{ $test->target_band_min }} - {{ $test->target_band_max }}
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="btn btn-sm btn-primary flex-fill">
                                    View Details
                                </a>
                                @if($test->can_take)
                                    <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="flex-fill">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            Start Test
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary flex-fill" disabled>
                                        Max Attempts Reached
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Practice Tests Section --}}
    <section class="mt-40">
        <h2 class="section-title mb-20">
            <i class="fas fa-dumbbell mr-2"></i>
            Practice Tests
            <span class="badge badge-info ml-2">{{ $practiceTests->count() }}</span>
        </h2>
        <p class="text-gray mb-20">Skill-focused practice. Choose 1-4 skills with instant feedback.</p>

        @if($practiceTests->isEmpty())
            <div class="no-result">
                <div class="no-result-logo">
                    <img src="/assets/default/img/no-results/support.png" alt="">
                </div>
                <div class="d-flex align-items-center flex-column mt-30 text-center">
                    <h2>No practice tests available!</h2>
                    <p class="mt-5 text-center">Practice tests will be added soon.</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($practiceTests as $test)
                    <div class="col-md-6 col-lg-4">
                        <div class="test-card">
                            <div class="d-flex justify-content-between align-items-start mb-15">
                                <h3 class="font-16 font-weight-bold">{{ $test->title }}</h3>
                                <span class="badge badge-info">Practice</span>
                            </div>

                            <p class="text-gray font-14 mb-15">{{ Str::limit($test->description, 100) }}</p>

                            <div class="mb-15">
                                @if($test->has_listening)
                                    <span class="skill-badge skill-listening">Listening</span>
                                @endif
                                @if($test->has_reading)
                                    <span class="skill-badge skill-reading">Reading</span>
                                @endif
                                @if($test->has_writing)
                                    <span class="skill-badge skill-writing">Writing</span>
                                @endif
                                @if($test->has_speaking)
                                    <span class="skill-badge skill-speaking">Speaking</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-center justify-content-between text-gray font-12 mb-15">
                                <div>
                                    <i class="far fa-clock mr-1"></i>
                                    @if($test->practice_mode === 'untimed')
                                        Untimed
                                    @else
                                        {{ $test->total_duration }} min
                                    @endif
                                </div>
                                <div>
                                    <i class="fas fa-redo mr-1"></i>
                                    Unlimited retakes
                                </div>
                            </div>

                            @if($test->user_attempts > 0)
                                <div class="alert alert-info py-2 px-3 font-12 mb-15">
                                    <i class="fas fa-history mr-1"></i>
                                    Practiced {{ $test->user_attempts }} time(s)
                                </div>
                            @endif

                            @if($test->practiceCategory)
                                <div class="text-gray font-12 mb-15">
                                    <i class="fas fa-folder mr-1"></i>
                                    {{ $test->practiceCategory->name }}
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                <a href="{{ route('panel.ielts_tests.show', $test->id) }}" class="btn btn-sm btn-primary flex-fill">
                                    View Details
                                </a>
                                <form action="{{ route('panel.ielts_tests.start', $test->id) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success w-100">
                                        Start Practice
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</section>
@endsection
