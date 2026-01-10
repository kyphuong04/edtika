@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Edit Question</h1>
            <p class="text-gray-500 font-14 mt-4">
                Group: {{ $group->title }} • {{ $group->skill_label ?? ucfirst($group->skill) }} • Band {{ $group->target_band }}
            </p>
        </div>
        <a href="{{ route('panel.question-groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm">
            <x-iconsax-bul-arrow-left class="icons mr-8" width="16px" height="16px"/>Back to Group
        </a>
    </div>

    {{-- Form Container --}}
    <form id="questionForm" method="POST" action="{{ route('panel.questions.update', $question->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        {{-- Question Type Display --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="font-14 font-weight-bold mb-8">Question Type</h3>
                    <span class="badge badge-primary font-12 px-12 py-6">
                        {{ \App\Enums\IeltsQuestionType::label($question->question_type) ?? $question->question_type }}
                    </span>
                </div>
                <input type="hidden" name="question_type" value="{{ $question->question_type }}">
            </div>
        </div>
        
        {{-- Question Content --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold mb-16">Question Content</h3>
            
            {{-- Question Text --}}
            <div class="form-group">
                <label class="form-group-label is-required bg-white">Question Text</label>
                <textarea name="question" class="form-control" rows="4" placeholder="Enter the question text..." required>{{ $question->question_text }}</textarea>
            </div>
            
            {{-- Type-specific fields --}}
            <div id="typeSpecificFields" class="mt-24">
                @php
                    $type = $question->question_type;
                    $viewPath = 'design_1.panel.questions.types.' . $type;
                @endphp
                
                @if(view()->exists($viewPath))
                    @include($viewPath, ['question' => $question, 'questionData' => $questionData, 'editing' => true])
                @else
                    {{-- Default fields for unknown types --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-group-label">Correct Answer</label>
                                <input type="text" name="correct_answer" class="form-control" value="{{ $question->correct_answer }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-group-label">Alternative Answers</label>
                                <input type="text" name="alternative_answers" class="form-control" 
                                       value="{{ is_array(json_decode($question->alternative_answers)) ? implode(', ', json_decode($question->alternative_answers)) : $question->alternative_answers }}"
                                       placeholder="Separate with commas">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            {{-- Explanation / Sample Answer --}}
            @if(in_array($group->skill, ['speaking', 'writing']))
                <div class="form-group mt-20">
                    <label class="form-group-label">Model / Sample Answer</label>
                    <textarea name="explanation" class="form-control" rows="6" placeholder="Provide a model answer or explanation...">{{ $question->explanation }}</textarea>
                </div>
            @else
                <div class="form-group mt-20">
                    <label class="form-group-label">Explanation (optional)</label>
                    <textarea name="explanation" class="form-control" rows="3" placeholder="Explain why this is the correct answer...">{{ $question->explanation }}</textarea>
                </div>
            @endif
            
            {{-- Marks --}}
            <div class="row mt-24">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Points/Marks</label>
                        <input type="number" name="marks" class="form-control" value="{{ $question->marks ?? 1.0 }}" step="0.5" min="0.5" max="10">
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Word Limit</label>
                        <input type="number" name="word_limit" class="form-control" value="{{ $question->word_limit }}" placeholder="Leave empty if not applicable">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Actions --}}
        <div class="bg-white rounded-16 p-16">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('panel.question-groups.show', $group->id) }}" class="btn btn-outline-secondary">
                    <x-iconsax-bul-close-circle class="icons mr-8" width="16px" height="16px"/>Cancel
                </a>
                
                <div class="d-flex gap-8">
                    <button type="submit" class="btn btn-success">
                        <x-iconsax-bul-tick-square class="icons mr-8" width="16px" height="16px"/>Update Question
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
/* Type selector cards - for consistency */
.badge-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
}
</style>
@endsection

@push('scripts_bottom')
<script src="/assets/vendors/select2/select2.min.js"></script>
<script>
$(document).ready(function() {
    // init select2
    $('.select2').select2({
        minimumResultsForSearch: 10,
        width: '100%'
    });
    
    // form submit via AJAX
    $('#questionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-8"></span>Updating...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                $.toast({
                    heading: 'Success',
                    text: response.message || 'Question updated!',
                    position: 'top-right',
                    icon: 'success',
                    showHideTransition: 'slide'
                });
                
                // redirect
                setTimeout(function() {
                    window.location.href = "{{ route('panel.question-groups.show', $group->id) }}";
                }, 1000);
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalText);
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    Object.values(xhr.responseJSON.errors).forEach(function(errors) {
                        $.toast({
                            heading: 'Error',
                            text: errors[0],
                            position: 'top-right',
                            icon: 'error'
                        });
                    });
                } else {
                    let errorMsg = 'Failed to update question';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $.toast({
                        heading: 'Error',
                        text: errorMsg,
                        position: 'top-right',
                        icon: 'error'
                    });
                }
            }
        });
    });
});
</script>
@endpush
