@extends('design_1.panel.layouts.panel')

@push('styles_top')
<style>
    .test-item {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    .test-item:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        border-color: #d1d5db;
    }
    .section-stats {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    .section-stats span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        color: #6b7280;
    }
    .btn-group-section {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h1 class="section-title">{{ trans('update.manage_sections_title') }}: {{ $test->title }}</h1>
            <p class="text-gray">{{ trans('update.manage_sections_hint') }}</p>
        </div>
        <div>
            <a href="{{ route('panel.my_ielts_tests.edit', $test->id) }}" class="btn btn-secondary mr-10">
                <i class="fas fa-arrow-left mr-5"></i>{{ trans('update.back_to_edit') }}
            </a>
            <a href="{{ route('panel.my_ielts_tests.sections.create', $test->id) }}" class="btn btn-primary">
                <i class="fas fa-plus mr-5"></i>{{ trans('update.add_section') }}
            </a>
        </div>
    </div>

    {{-- Progress (for mock tests) --}}
    @if($test->type === 'mock_test')
        <div class="alert alert-info">
            <strong>{{ trans('update.mock_test_requirements') }}</strong> {{ trans('update.mock_test_requirements_hint') }}
            <br>
           <small>
                Current: {{ $test->sections->count() }}/4 sections |
                @if($test->sections->where('skill', 'listening')->count()) ✓ Listening @endif
                @if($test->sections->where('skill', 'reading')->count()) ✓ Reading @endif
                @if($test->sections->where('skill', 'writing')->count()) ✓ Writing @endif
                @if($test->sections->where('skill', 'speaking')->count()) ✓ Speaking @endif
            </small>
        </div>
    @endif

    {{-- Sections List --}}
@if($test->sections->isEmpty())
        <div class="no-result mt-50">
            <div class="d-flex align-items-center flex-column mt-30 text-center">
                <h2>{{ trans('update.no_sections_yet') }}</h2>
                <p class="mt-5">{{ trans('update.click_add_section_hint') }}</p>
                <div class="alert alert-info mt-20" style="max-width: 600px;">
                    <i class="fas fa-info-circle mr-5"></i>
                    <strong>{{ trans('update.admin_panel_note') }}</strong>
                    {{ trans('update.admin_panel_hint') }}
                </div>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($test->sections->sortBy('sort_order') as $section)
                <div class="col-12 mb-20">
                    <div class="test-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-fill">
                                <div class="d-flex align-items-center mb-10">
                                    <span class="badge badge-{{ $section->skill === 'listening' ? 'primary' : ($section->skill === 'reading' ? 'success' : ($section->skill === 'writing' ? 'warning' : 'danger')) }}">
                                        {{ ucfirst($section->skill) }}
                                    </span>
                                    <h4 class="font-16 font-weight-bold mb-0 ml-10">{{ $section->title }}</h4>
                                </div>

                                @if($section->description)
                                    <p class="text-gray font-14 mb-10">{{ $section->description }}</p>
                                @endif

                                <div class="d-flex align-items-center text-gray font-12">
                                    <div class="mr-20">
                                        <i class="far fa-clock mr-5"></i>{{ $section->duration_minutes }} {{ trans('update.min') }}
                                    </div>
                                    <div class="mr-20">
                                        <i class="far fa-list-alt mr-5"></i>{{ $section->questions->count() }} {{ trans('update.questions_count') }}
                                    </div>
                                    @if($section->audio_file)
                                        <div class="mr-20">
                                            <i class="fas fa-headphones mr-5"></i>{{ trans('update.has_audio') }}
                                        </div>
                                    @endif
                                    @if($section->passage_text)
                                        <div class="mr-20">
                                            <i class="fas fa-align-left mr-5"></i>{{ trans('update.has_passage') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <a href="{{ route('panel.my_ielts_tests.questions', [$test->id, $section->id]) }}" 
                                   class="btn btn-sm btn-info mr-5">
                                    <i class="fas fa-question-circle"></i> {{ trans('update.questions') }}
                                </a>
                                <a href="{{ route('panel.my_ielts_tests.sections.edit', [$test->id, $section->id]) }}" 
                                   class="btn btn-sm btn-primary mr-5">
                                    <i class="far fa-edit"></i> {{ trans('update.edit') }}
                                </a>
                                <a href="{{ route('panel.my_ielts_tests.sections.delete', [$test->id, $section->id]) }}" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('{{ trans('update.delete_section_confirm') }}')">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Publish Button --}}
    @if($test->sections->count() >= ($test->type === 'mock_test' ? 4 : 1))
        <div class="mt-30 text-center">
            <p class="text-gray mb-15">{{ trans('update.ready_to_publish_hint') }}</p>
            <a href="#" class="btn btn-success btn-lg">
                <i class="fas fa-check mr-5"></i>{{ trans('update.publish_test') }}
            </a>
        </div>
    @endif
</section>
@endsection
