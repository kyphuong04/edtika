{{-- Enhanced Sections Management View --}}
@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Test Structure: Sections → Parts → Questions</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ getAdminPanelUrl() }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('admin.ielts_tests.index') }}">IELTS Tests</a></div>
            <div class="breadcrumb-item">{{ $test->title }}</div>
        </div>
    </div>

    <div class="section-body">
        {{-- Test Info Header --}}
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card border-left-primary shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <h4 class="mb-1">{{ $test->title }}</h4>
                                <p class="text-muted mb-0">
                                    <span class="badge badge-{{ $test->type === 'mock' ? 'danger' : 'primary' }}">
                                        {{ ucfirst($test->type) }} Test
                                    </span>
                                    <span class="ml-2">{{ ucfirst($test->format) }} Format</span>
                                    @if($test->target_band_min || $test->target_band_max)
                                        <span class="mx-2">•</span>
                                        <span>Target Band: 
                                            {{ $test->target_band_min ?? '—' }} - {{ $test->target_band_max ?? '—' }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-5 text-right">
                                <a href="{{ route('admin.ielts_tests.edit', $test->id) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit mr-1"></i>Edit Test
                                </a>
                                <a href="{{ route('admin.ielts_tests.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-arrow-left mr-1"></i>Back to Tests
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Structure Guide --}}
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <h6 class="alert-heading"><i class="fas fa-sitemap mr-2"></i>Test Structure</h6>
            <p class="mb-2">Your test is organized in a three-level hierarchy:</p>
            <ul class="mb-0">
                <li><strong>Sections</strong> = Skills (Listening, Reading, Writing, Speaking)</li>
                <li><strong>Parts/Question Groups</strong> = Logical groups within each section (e.g., Listening Part 1, Part 2)</li>
                <li><strong>Questions</strong> = Individual questions within each part</li>
            </ul>
            <button type="button" class="close" data-dismiss="alert">×</button>
        </div>

        {{-- Sections Overview --}}
        <div class="row">
            @foreach($test->sections->sortBy('sort_order') as $section)
            <div class="col-lg-6 mb-4">
                <div class="card section-card shadow-sm">
                    {{-- Section Header --}}
                    <div class="card-header bg-gradient-{{ 
                        $section->skill === 'listening' ? 'info' :
                        ($section->skill === 'reading' ? 'success' :
                        ($section->skill === 'writing' ? 'warning' : 'danger'))
                    }} text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1 text-white">
                                    <i class="fas {{ 
                                        $section->skill === 'listening' ? 'fa-headphones' :
                                        ($section->skill === 'reading' ? 'fa-book-open' :
                                        ($section->skill === 'writing' ? 'fa-pencil-alt' : 'fa-microphone'))
                                    }} mr-2"></i>
                                    {{ ucfirst($section->skill) }}
                                </h5>
                                <small class="text-white-70">{{ $section->title }}</small>
                            </div>
                            <div class="text-right">
                                <div class="text-white-70 small">
                                    {{ $section->question_start }}-{{ $section->question_end }}
                                </div>
                                @if($section->duration_minutes)
                                    <small class="text-white-70">{{ $section->duration_minutes }} min</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Section Body --}}
                    <div class="card-body">
                        {{-- Progress Bar --}}
                        @php
                            $totalRequired = $section->question_end - $section->question_start + 1;
                            $totalAdded = $section->questions->count();
                            $progress = $totalRequired > 0 ? ($totalAdded / $totalRequired) * 100 : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small font-weight-bold">Completion Progress</span>
                                <span class="small text-muted">{{ $totalAdded }}/{{ $totalRequired }} questions</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-{{ 
                                    $section->skill === 'listening' ? 'info' :
                                    ($section->skill === 'reading' ? 'success' :
                                    ($section->skill === 'writing' ? 'warning' : 'danger'))
                                }}" role="progressbar" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        {{-- Parts/Question Groups --}}
                        @if($section->questionGroups->isEmpty())
                            <div class="text-center py-4 border-top">
                                <i class="fas fa-layer-group fa-2x text-gray mb-2" style="opacity: 0.5;"></i>
                                <p class="text-muted small">No parts created yet</p>
                                <a href="{{ route('admin.ielts_tests.question_groups', $section->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-plus mr-1"></i>Add Parts
                                </a>
                            </div>
                        @else
                            <div class="parts-list border-top pt-3">
                                @foreach($section->questionGroups->sortBy('question_start') as $part)
                                    <div class="part-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 font-weight-bold">
                                                    Part {{ $loop->iteration }}: 
                                                    {{ Str::limit($part->title, 40) }}
                                                </h6>
                                                <div class="badge-group small">
                                                    <span class="badge badge-light">Q{{ $part->question_start }}-{{ $part->question_end }}</span>
                                                    <span class="badge badge-light">{{ $part->questions->count() }}/{{ $part->question_end - $part->question_start + 1 }} questions</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('admin.ielts_tests.questions', $part->id) }}" 
                                               class="btn btn-xs btn-outline-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>

                                        {{-- Media Indicators --}}
                                        @if($part->audio_file || $part->task_image || $part->video_file)
                                            <div class="media-icons small text-muted">
                                                @if($part->audio_file)
                                                    <i class="fas fa-volume-up text-primary mr-2" title="Has audio"></i>
                                                @endif
                                                @if($part->task_image)
                                                    <i class="fas fa-image text-success mr-2" title="Has image"></i>
                                                @endif
                                                @if($part->video_file)
                                                    <i class="fas fa-video text-danger mr-2" title="Has video"></i>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center pt-2 border-top">
                                <a href="{{ route('admin.ielts_tests.question_groups', $section->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-layer-group mr-1"></i>Manage Parts
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Overall Progress Card --}}
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">Overall Test Completion</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            @php
                                $totalSections = $test->sections->count();
                                $completeSections = $test->sections->filter(function($s) {
                                    return $s->questions->count() >= ($s->question_end - $s->question_start + 1);
                                })->count();
                                
                                $totalParts = $test->sections->sum(function($s) { return $s->questionGroups->count(); });
                                $totalQuestionsRequired = $test->sections->sum(function($s) { return $s->question_end - $s->question_start + 1; });
                                $totalQuestionsAdded = $test->sections->sum(function($s) { return $s->questions->count(); });
                            @endphp
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <h4 class="text-primary font-weight-bold">{{ $totalSections }}</h4>
                                    <small class="text-muted">Sections</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <h4 class="text-success font-weight-bold">{{ $totalParts }}</h4>
                                    <small class="text-muted">Parts/Groups</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <h4 class="text-info font-weight-bold">{{ $totalQuestionsAdded }}/{{ $totalQuestionsRequired }}</h4>
                                    <small class="text-muted">Questions Added</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    @php
                                        $overallProgress = $totalQuestionsRequired > 0 ? ($totalQuestionsAdded / $totalQuestionsRequired) * 100 : 0;
                                    @endphp
                                    <h4 class="text-warning font-weight-bold">{{ number_format($overallProgress, 1) }}%</h4>
                                    <small class="text-muted">Complete</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="progress mt-4" style="height: 10px;">
                            @php
                                $overallProgress = $totalQuestionsRequired > 0 ? ($totalQuestionsAdded / $totalQuestionsRequired) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-success" style="width: {{ $overallProgress }}%"></div>
                        </div>

                        <div class="mt-3 text-center">
                            @if($totalQuestionsAdded >= $totalQuestionsRequired)
                                <button type="button" class="btn btn-lg btn-success">
                                    <i class="fas fa-check-circle mr-2"></i>Test Ready for Review!
                                </button>
                            @else
                                <p class="text-muted">
                                    {{ $totalQuestionsRequired - $totalQuestionsAdded }} more questions needed to complete this test.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles_bottom')
<style>
.section-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.section-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.part-item {
    background: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
    margin: 0 -12px;
    padding: 12px;
}

.badge-group {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
}

.btn-xs {
    padding: 0.25rem 0.5rem;
    font-size: 0.7rem;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #0c5460 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%);
}

.text-white-70 {
    color: rgba(255, 255, 255, 0.7);
}
</style>
@endpush
