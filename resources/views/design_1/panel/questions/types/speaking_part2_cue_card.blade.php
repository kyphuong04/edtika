{{-- Speaking Part 2 - Cue Card - Optimized --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content">
    <h4>
        <x-iconsax-lin-personalcard class="icons text-primary mr-8" width="20px" height="20px"/>
        Speaking Part 2 - Cue Card
    </h4>
    
    <div class="info-box info">
        <x-iconsax-lin-clock class="icons" width="16px" height="16px"/>
        <span>1 minute preparation + 2 minutes speaking. Must include 3-4 bullet points.</span>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label class="form-group-label">Topic Category</label>
                <select name="question_data[topic]" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="person">Describe a person</option>
                    <option value="place">Describe a place</option>
                    <option value="object">Describe an object</option>
                    <option value="event">Describe an event</option>
                    <option value="experience">Describe an experience</option>
                    <option value="activity">Describe an activity</option>
                    <option value="abstract">Abstract (idea, skill)</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label is-required">Bullet Points (You should say...)</label>
        <div id="sp2Bullets" class="dynamic-inputs"></div>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="sp2AddBullet">
            <x-iconsax-lin-add class="icons mr-4" width="14px" height="14px"/>Add Bullet
        </button>
    </div>
    
    <div class="form-group">
        <label class="form-group-label is-required">Final Question (and explain...)</label>
        <input type="text" name="question_data[final_question]" class="form-control" required
               placeholder="e.g., and explain why you enjoyed this place">
    </div>
    
    <div class="row">
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Prep Time (sec)</label>
                <input type="number" name="question_data[prep_time]" class="form-control" value="60">
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="form-group">
                <label class="form-group-label">Speaking Time (sec)</label>
                <input type="number" name="question_data[speaking_time]" class="form-control" value="120">
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Sample Answer</label>
        <textarea name="question_data[sample_answer]" class="form-control" rows="4" 
                  placeholder="Model answer for teacher reference"></textarea>
    </div>
</div>

<script>
(function() {
    var c = 0;
    function add() {
        if (c >= 5) return;
        c++;
        $('#sp2Bullets').append('<div class="input-group mb-8"><span class="input-group-text">•</span><input type="text" name="question_data[bullet_points][]" class="form-control" placeholder="Bullet point '+c+'" required><button type="button" class="btn btn-sm btn-danger" onclick="$(this).parent().remove()">&times;</button></div>');
    }
    add(); add(); add(); // Init with 3
    $('#sp2AddBullet').on('click', add);
})();
</script>
