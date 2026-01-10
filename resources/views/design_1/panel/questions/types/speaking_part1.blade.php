{{-- Speaking P1 (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="blue">
    <h4>
        <x-iconsax-lin-message-2 class="icons text-primary mr-8" width="20px" height="20px"/>
        Speaking Part 1 - Introduction & Interview
        <span class="badge badge-primary ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box primary">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm nhiều câu hỏi Part 1 về cùng 1 topic. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Shared Settings --}}
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Topic Category <span class="text-danger">*</span></label>
                <select id="sp1Topic" class="form-control">
                    <option value="">-- Select --</option>
                    <option value="work_study">Work / Study</option>
                    <option value="hometown">Hometown</option>
                    <option value="home">Home / Accommodation</option>
                    <option value="family">Family</option>
                    <option value="hobbies">Hobbies / Free time</option>
                    <option value="travel">Travel</option>
                    <option value="food">Food</option>
                    <option value="technology">Technology</option>
                    <option value="other">Other</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Response Time</label>
                <select id="sp1Time" class="form-control">
                    <option value="20-30">20-30 seconds</option>
                    <option value="30-45" selected>30-45 seconds</option>
                </select>
            </div>
        </div>
    </div>
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="sp1Progress" class="progress-bar bg-primary" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="sp1ValidCount">0</span>/<span id="sp1TotalCount">0</span> câu hợp lệ</small>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="sp1SelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Câu hỏi <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="sp1Rows"></div>
    
    {{-- Max rows warning --}}
    <div id="sp1MaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 8 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="sp1AddRow">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm câu hỏi
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons text-primary mr-4" width="16px" height="16px"/>
            Hợp lệ: <strong id="sp1SelectedCount" class="text-primary">0</strong> câu hỏi
        </div>
    </div>
    
    {{-- Sample answer section --}}
    <div class="mt-16">
        <label class="form-group-label">Sample Answer Notes (chung cho topic)</label>
        <textarea id="sp1SampleNotes" class="form-control" rows="2" placeholder="Ghi chú về cách trả lời" maxlength="500"></textarea>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="sp1BatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 8;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var question = $.trim($row.find('.sp1-question').val());
        var isValid = question.length >= 10;
        
        $row.find('.sp1-question').toggleClass('is-invalid', question.length > 0 && question.length < 10)
            .toggleClass('is-valid', question.length >= 10);
        
        $row.toggleClass('has-error', !isValid && question.length > 0);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#sp1MaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row sp1-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check sp1-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control sp1-question" placeholder="Ví dụ: Do you work or study?" maxlength="300"></div>' +
            '<button type="button" class="btn btn-remove"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>' +
        '</div>';
        
        $('#sp1Rows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#sp1MaxWarning').slideDown(200);
        }
        
        $('#sp1Rows .batch-row:last .sp1-question').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var topic = $('#sp1Topic').val();
        var time = $('#sp1Time').val();
        var notes = $.trim($('#sp1SampleNotes').val());
        
        // Validate topic
        $('#sp1Topic').toggleClass('is-invalid', !topic && $('.sp1-row').length > 0)
            .toggleClass('is-valid', !!topic);
        
        $('.sp1-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.sp1-checkbox').is(':checked') && isValid && topic) {
                validCount++;
                var q = $.trim($row.find('.sp1-question').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: q,
                    topic: topic,
                    response_time: time,
                    sample_notes: notes || null,
                    question_type: 'speaking_part1',
                    question_type_label: 'Speaking Part 1'
                });
            }
        });
        
        var total = $('.sp1-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#sp1SelectedCount').text(questions.length);
        $('#sp1ValidCount').text(validCount);
        $('#sp1TotalCount').text(total);
        $('#sp1Progress').css('width', percent + '%');
        $('#sp1BatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.sp1-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 4 questions
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#sp1AddRow').off('click.sp1').on('click.sp1', addRow);
    $('#sp1Topic, #sp1Time').off('change.sp1').on('change.sp1', updateUI);
    $('#sp1SampleNotes').off('input.sp1').on('input.sp1', updateUI);
    
    $(document).off('change.sp1', '.sp1-checkbox').on('change.sp1', '.sp1-checkbox', function() {
        $(this).closest('.sp1-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#sp1SelectAll').off('change.sp1').on('change.sp1', function() {
        var c = $(this).is(':checked');
        $('.sp1-checkbox').prop('checked', c);
        $('.sp1-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.sp1', '.sp1-question').on('input.sp1', '.sp1-question', function() {
        validateRow($(this).closest('.sp1-row'));
        updateUI();
    });
    
    $(document).off('click.sp12', '.btn-remove').on('click.sp12', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.sp1-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#sp1MaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.sp1').on('input.sp1', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.sp1').on('keydown.sp1', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
