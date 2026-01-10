{{-- Writing Task 1 - Process/Diagram - Optimized --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content">
    <h4>
        <x-iconsax-lin-setting-2 class="icons text-warning mr-8" width="20px" height="20px"/>
        Writing Task 1 - Process/Diagram
    </h4>
    
    <div class="info-box warning">
        <x-iconsax-lin-edit class="icons" width="16px" height="16px"/>
        <span>Students describe a process, cycle, or how something works. 20 min, 150+ words.</span>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label class="form-group-label is-required">Diagram Type</label>
                <select name="question_data[diagram_type]" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="process">Linear Process</option>
                    <option value="cycle">Cycle</option>
                    <option value="how_it_works">How Something Works</option>
                    <option value="map_comparison">Map Comparison (before/after)</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="form-group">
                <label class="form-group-label is-required">Process Diagram Image</label>
                <input type="file" name="task_image" class="form-control" accept="image/*" required>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label is-required">Task Instructions</label>
        <textarea name="question_data[instructions]" class="form-control" rows="3" required
                  placeholder="The diagram shows how cement is made..."></textarea>
    </div>
    
    <div class="row">
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Time (min)</label>
                <input type="number" name="question_data[time_limit]" class="form-control" value="20">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Word Limit</label>
                <input type="text" name="word_limit" class="form-control" value="at least 150 words">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Stages/Steps</label>
                <input type="number" name="question_data[num_stages]" class="form-control" placeholder="e.g., 6">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Sample Answer</label>
        <textarea name="question_data[sample_answer]" class="form-control" rows="4"></textarea>
    </div>
</div>
