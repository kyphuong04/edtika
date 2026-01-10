{{-- Default Form (Fallback) - Optimized --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content">
    <h4>
        <x-iconsax-lin-document-text class="icons text-secondary mr-8" width="20px" height="20px"/>
        General Question
    </h4>
    
    <div class="info-box info">
        <x-iconsax-lin-info-circle class="icons" width="16px" height="16px"/>
        <span>No specific template for this question type. Use general fields below.</span>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">Correct Answer</label>
                <input type="text" name="correct_answer" class="form-control" placeholder="Enter correct answer">
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label class="form-group-label">Word Limit</label>
                <input type="text" name="word_limit" class="form-control" placeholder="e.g., NO MORE THAN TWO WORDS">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Alternative Answers</label>
        <textarea name="alternative_answers[]" class="form-control" rows="2" 
                  placeholder="One per line (optional)"></textarea>
    </div>
</div>
