@extends('design_1.panel.layouts.panel')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/select2/select2.min.css">
@endpush

@section('content')
<section>
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-24">
        <div>
            <h1 class="font-20 font-weight-bold text-dark">Add Question</h1>
            <p class="text-gray-500 font-14 mt-4">
                Group: {{ $group->title }} • {{ $group->skill_label }} • Band {{ $group->target_band }}
            </p>
        </div>
        <a href="{{ route('panel.question-groups.show', $group->id) }}" class="btn btn-outline-secondary btn-sm">
            <x-iconsax-bul-arrow-left class="icons mr-8" width="16px" height="16px"/>Back
        </a>
    </div>

    {{-- Form Container --}}
    <form id="questionForm" method="POST" action="{{ route('panel.questions.store', $group->id) }}" enctype="multipart/form-data">
        @csrf
        
        {{-- Step 1: Question Type Selection --}}
        <div class="bg-white rounded-16 p-16 mb-16">
            <h3 class="font-14 font-weight-bold position-relative d-inline-flex is-required">Select Question Type</h3>
            
            <div class="form-group mt-16">
                <select name="question_type" id="questionType" class="form-control select2" data-placeholder="-- Choose Question Type --" required>
                    <option value=""></option>
                    @foreach($questionTypes as $type)
                        <option value="{{ $type }}">{{ \App\Enums\IeltsQuestionType::label($type) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        {{-- Step 2: Question Content (shown after type selected) --}}
        <div id="questionContentSection" class="bg-white rounded-16 p-16 mb-16" style="display: none;">
            <h3 class="font-14 font-weight-bold mb-16">Question Content</h3>
            
            {{-- Question Text --}}
            <div class="form-group">
                <label class="form-group-label is-required bg-white">Question Text</label>
                <textarea name="question" class="form-control" rows="4" placeholder="Enter the question text..." required></textarea>
            </div>
            
            {{-- Type-specific fields loaded here --}}
            <div id="typeSpecificFields" class="mt-24">
                {{-- Dynamic content via AJAX --}}
            </div>
            
            {{-- Marks --}}
            <div class="row mt-24">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label class="form-group-label">Points/Marks</label>
                        <input type="number" name="marks" class="form-control" value="1.0" step="0.5" min="0.5" max="10">
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
                    <button type="button" id="addAnotherBtn" class="btn btn-outline-success" style="display: none;">
                        <x-iconsax-bul-add class="icons mr-8" width="16px" height="16px"/>Save & Add Another
                    </button>
                    <button type="submit" class="btn btn-success">
                        <x-iconsax-bul-tick-square class="icons mr-8" width="16px" height="16px"/>Save Question
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>

<style>
/* Type selector cards - future enhancement */
.question-type-card { 
    cursor: pointer; 
    transition: all 0.2s; 
    border: 2px solid transparent;
}
.question-type-card:hover { 
    border-color: #28c76f; 
    transform: translateY(-2px); 
}
.question-type-card.selected { 
    border-color: #28c76f; 
    background: rgba(40, 199, 111, 0.05); 
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
    
    // on type change
    $('#questionType').on('change', function() {
        const type = $(this).val();
        
        if (!type) {
            $('#questionContentSection').slideUp();
            $('#addAnotherBtn').hide();
            return;
        }
        
        // show content section
        $('#questionContentSection').slideDown();
        $('#addAnotherBtn').show();
        
        // load type-specific fields
        $.get(`{{ url('panel/questions/type-form') }}/${type}`, function(html) {
            $('#typeSpecificFields').html(html);
            
            // re-init any select2 inside
            $('#typeSpecificFields .select2').select2({ width: '100%' });
        }).fail(function() {
            $('#typeSpecificFields').html(`
                <div class="alert alert-warning">
                    <x-iconsax-bul-info-circle class="icons mr-8"/>
                    No specific form for this type. Use general fields.
                </div>
            `);
        });
    });
    
    // form submit via AJAX
    $('#questionForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm mr-8"></span>Saving...');
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $.toast({
                    heading: 'Success',
                    text: response.message || 'Question saved!',
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
                    $.toast({
                        heading: 'Error',
                        text: 'Failed to save question',
                        position: 'top-right',
                        icon: 'error'
                    });
                }
            }
        });
    });
    
    // save & add another
    $('#addAnotherBtn').on('click', function() {
        const form = $('#questionForm');
        const formData = new FormData(form[0]);
        formData.append('add_another', '1');
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $.toast({
                    heading: 'Success',
                    text: 'Question saved! Add another.',
                    position: 'top-right',
                    icon: 'success'
                });
                
                // reset form but keep type
                form[0].reset();
                $('textarea[name="question"]').val('');
                $('#typeSpecificFields input, #typeSpecificFields textarea').val('');
            }
        });
    });
});
</script>
@endpush
