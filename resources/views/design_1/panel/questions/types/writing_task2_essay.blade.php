{{-- Writing Task 2 - Essay - Optimized --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content">
    <h4>
        <x-iconsax-lin-document-text class="icons text-danger mr-8" width="20px" height="20px"/>
        Writing Task 2 - Essay
    </h4>
    
    <div class="info-box danger">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/>
        <span>40 minutes, minimum 250 words. This is the main writing task.</span>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label class="form-group-label is-required">Essay Type</label>
                <select name="question_data[essay_type]" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="opinion">Opinion Essay (agree/disagree)</option>
                    <option value="discussion">Discussion Essay (both sides)</option>
                    <option value="problem_solution">Problem & Solution</option>
                    <option value="advantages_disadvantages">Advantages & Disadvantages</option>
                    <option value="two_part">Two-Part Question</option>
                    <option value="direct">Direct Question</option>
                </select>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Time (min)</label>
                <input type="number" name="question_data[time_limit]" class="form-control" value="40">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Word Limit</label>
                <input type="text" name="word_limit" class="form-control" value="at least 250 words">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Topic Tags</label>
        <input type="text" name="question_data[topic_tags]" class="form-control" 
               placeholder="e.g., technology, society, environment">
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Sample Answer (Band 8+)</label>
        <textarea name="question_data[sample_answer]" class="form-control" rows="5" 
                  placeholder="Model answer for teacher reference"></textarea>
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Key Points to Address</label>
        <textarea name="question_data[key_points]" class="form-control" rows="3" 
                  placeholder="Points students should cover for a high band score"></textarea>
    </div>
</div>
