{{-- Speaking P3 (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="pink">
    <h4>
        <x-iconsax-lin-messages-3 class="icons text-danger mr-8" width="20px" height="20px"/>
        Speaking Part 3 - Discussion
        <span class="badge badge-danger ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box danger">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm nhiều câu hỏi discussion về cùng topic. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Shared Settings --}}
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Related Topic <span class="text-danger">*</span></label>
                <input type="text" id="sp3Topic" class="form-control" placeholder="e.g., Tourism, Environment" maxlength="100">
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Complexity</label>
                <select id="sp3Complexity" class="form-control">
                    <option value="moderate">Moderate</option>
                    <option value="challenging" selected>Challenging</option>
                    <option value="very_challenging">Very Challenging</option>
                </select>
            </div>
        </div>
    </div>
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="sp3Progress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: #e83e8c;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="sp3ValidCount">0</span>/<span id="sp3TotalCount">0</span> câu hợp lệ</small>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="sp3SelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 120px;">Type</div>
        <div class="flex-fill">Câu hỏi Discussion <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="sp3Rows"></div>
    
    {{-- Max rows warning --}}
    <div id="sp3MaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 6 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="sp3AddRow">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm câu hỏi
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons mr-4" style="color: #e83e8c;" width="16px" height="16px"/>
            Hợp lệ: <strong id="sp3SelectedCount" style="color: #e83e8c;">0</strong> câu hỏi
        </div>
    </div>
    
    {{-- Sample points section --}}
    <div class="mt-16">
        <label class="form-group-label">Sample Answer Points (chung cho topic)</label>
        <textarea id="sp3SamplePoints" class="form-control" rows="2" placeholder="Key points a strong answer should cover" maxlength="500"></textarea>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="sp3BatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 6;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var question = $.trim($row.find('.sp3-question').val());
        var isValid = question.length >= 15;
        
        $row.find('.sp3-question').toggleClass('is-invalid', question.length > 0 && question.length < 15)
            .toggleClass('is-valid', question.length >= 15);
        
        $row.toggleClass('has-error', !isValid && question.length > 0);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#sp3MaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row sp3-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check sp3-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div style="width: 120px; padding-right: 8px;"><select class="form-control form-control-sm sp3-type"><option value="opinion">Opinion</option><option value="comparison">Comparison</option><option value="prediction">Prediction</option><option value="cause_effect">Cause/Effect</option><option value="hypothetical">Hypothetical</option></select></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control sp3-question" placeholder="Ví dụ: What impact does technology have on education?" maxlength="400"></div>' +
            '<button type="button" class="btn btn-remove"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>' +
        '</div>';
        
        $('#sp3Rows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#sp3MaxWarning').slideDown(200);
        }
        
        $('#sp3Rows .batch-row:last .sp3-question').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var topic = $.trim($('#sp3Topic').val());
        var complexity = $('#sp3Complexity').val();
        var points = $.trim($('#sp3SamplePoints').val());
        
        // Validate topic
        $('#sp3Topic').toggleClass('is-invalid', !topic && $('.sp3-row').length > 0)
            .toggleClass('is-valid', topic.length >= 3);
        
        $('.sp3-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.sp3-checkbox').is(':checked') && isValid && topic.length >= 3) {
                validCount++;
                var qType = $row.find('.sp3-type').val();
                var q = $.trim($row.find('.sp3-question').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: q,
                    related_topic: topic,
                    question_subtype: qType,
                    complexity: complexity,
                    sample_points: points || null,
                    question_type: 'speaking_part3',
                    question_type_label: 'Speaking Part 3'
                });
            }
        });
        
        var total = $('.sp3-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#sp3SelectedCount').text(questions.length);
        $('#sp3ValidCount').text(validCount);
        $('#sp3TotalCount').text(total);
        $('#sp3Progress').css('width', percent + '%');
        $('#sp3BatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.sp3-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 3 questions
    for (var i = 0; i < 3; i++) addRow();
    
    // Event handlers
    $('#sp3AddRow').off('click.sp3').on('click.sp3', addRow);
    $('#sp3Topic').off('input.sp3').on('input.sp3', updateUI);
    $('#sp3Complexity').off('change.sp3').on('change.sp3', updateUI);
    $('#sp3SamplePoints').off('input.sp3').on('input.sp3', updateUI);
    
    $(document).off('change.sp3', '.sp3-checkbox').on('change.sp3', '.sp3-checkbox', function() {
        $(this).closest('.sp3-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $(document).off('change.sp32', '.sp3-type').on('change.sp32', '.sp3-type', updateUI);
    
    $('#sp3SelectAll').off('change.sp3').on('change.sp3', function() {
        var c = $(this).is(':checked');
        $('.sp3-checkbox').prop('checked', c);
        $('.sp3-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.sp3', '.sp3-question').on('input.sp3', '.sp3-question', function() {
        validateRow($(this).closest('.sp3-row'));
        updateUI();
    });
    
    $(document).off('click.sp33', '.btn-remove').on('click.sp33', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.sp3-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#sp3MaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.sp3').on('input.sp3', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.sp3').on('keydown.sp3', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
