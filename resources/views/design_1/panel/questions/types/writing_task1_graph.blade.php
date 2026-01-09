{{-- Writing Task 1 - Graph/Chart - Optimized --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content">
    <h4>
        <x-iconsax-lin-chart-2 class="icons text-info mr-8" width="20px" height="20px"/>
        Writing Task 1 - Graph/Chart/Table
    </h4>
    
    <div class="info-box info">
        <x-iconsax-lin-edit class="icons" width="16px" height="16px"/>
        <span>Students describe visual data. 20 minutes, minimum 150 words.</span>
    </div>
    
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label class="form-group-label is-required">Chart Type</label>
                <select name="question_data[chart_type]" class="form-control" required>
                    <option value="">-- Select --</option>
                    <option value="line_graph">Line Graph</option>
                    <option value="bar_chart">Bar Chart</option>
                    <option value="pie_chart">Pie Chart</option>
                    <option value="table">Table</option>
                    <option value="mixed">Mixed/Combined</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="form-group">
                <label class="form-group-label is-required">Graph/Chart Image</label>
                <input type="file" name="task_image" class="form-control" accept="image/*" required>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-group-label is-required">Task Instructions</label>
        <textarea name="question_data[instructions]" class="form-control" rows="3" required
                  placeholder="Summarise the information by selecting and reporting the main features..."></textarea>
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
    </div>
    
    <div class="form-group">
        <label class="form-group-label">Sample Answer</label>
        <textarea name="question_data[sample_answer]" class="form-control" rows="4" 
                  placeholder="Model answer for grading reference"></textarea>
    </div>
</div>
