{{-- T/F/NG (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="cyan">
    <h4>
        <x-iconsax-bul-tick-circle class="icons text-info mr-8" width="20px" height="20px"/>
        True / False / Not Given
        <span class="badge badge-success ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box success">
        <x-iconsax-lin-lamp-on class="icons" width="16px" height="16px"/>
        <span><strong>Hướng dẫn:</strong> Nhập statement và chọn đáp án. <strong>True</strong> = đúng với passage, <strong>False</strong> = trái với passage, <strong>Not Given</strong> = không đề cập.</span>
    </div>
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16" id="tfngProgress" style="display:none;">
        <div class="progress-bar"><div class="progress-fill" style="width:0%"></div></div>
        <div class="progress-text"><span id="tfngValidCount">0</span>/<span id="tfngTotalCount">0</span> hoàn thành</div>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div class="col-check">
            <input type="checkbox" id="tfngSelectAll" class="batch-check" title="Chọn/Bỏ chọn tất cả">
        </div>
        <div class="col-qnum">Q.</div>
        <div class="flex-fill">Statement</div>
        <div style="width: 140px; text-align: center;">Đáp án</div>
        <div class="col-action"></div>
    </div>
    
    <div id="tfngRows"></div>
    
    <div class="max-rows-warning" id="tfngMaxWarning">
        <x-iconsax-lin-warning-2 class="icons mr-4" width="16px" height="16px"/>Đã đạt giới hạn 15 câu hỏi.
    </div>
    
    <div class="batch-footer">
        <button type="button" class="btn-add-row" id="tfngAddRow">
            <x-iconsax-lin-add class="icons" width="16px" height="16px"/>Thêm statement
        </button>
        <div class="batch-counter">
            <span>Hợp lệ:</span>
            <strong id="tfngSelectedCount">0</strong>
            <span>câu</span>
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="tfngBatchData" value="[]">
</div>

<style>
.tfng-row { --type-color: #0891b2; --type-gradient: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
.tfng-row .q-badge { background: var(--type-gradient); }
.tfng-row.selected { border-color: var(--type-color); background: rgba(8,145,178,0.03); }
.tfng-row .form-control:focus { border-color: var(--type-color); box-shadow: 0 0 0 3px rgba(8,145,178,0.1); }
.tfng-row select.answer-select { font-weight: 600; }
.tfng-row select.answer-select option[value="true"] { color: #059669; }
.tfng-row select.answer-select option[value="false"] { color: #dc2626; }
.tfng-row select.answer-select option[value="not_given"] { color: #6b7280; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0, maxRows = 15;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var $container = $('#tfngRows');
    
    function createRow(qNum) {
        return $(`
            <div class="batch-row tfng-row selected new" data-qnum="${qNum}">
                <div class="col-check" style="width:44px;text-align:center;">
                    <input type="checkbox" class="batch-check tfng-checkbox" checked>
                </div>
                <div class="col-qnum" style="width:56px;text-align:center;">
                    <span class="q-badge">Q${qNum}</span>
                </div>
                <div class="flex-fill px-8">
                    <input type="text" class="form-control tfng-statement" placeholder="Nhập statement..." maxlength="500">
                </div>
                <div style="width:140px;">
                    <select class="form-control answer-select tfng-answer">
                        <option value="">-- Chọn --</option>
                        <option value="TRUE">TRUE</option>
                        <option value="FALSE">FALSE</option>
                        <option value="NOT GIVEN">NOT GIVEN</option>
                    </select>
                </div>
                <div class="col-action" style="width:44px;text-align:center;">
                    <button type="button" class="btn-remove tfng-remove-row" title="Xóa"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                </div>
            </div>
        `);
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#tfngMaxWarning').addClass('show');
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        $container.append(createRow(qNum));
        updateUI();
        $container.find('.tfng-row:last .tfng-statement').focus();
    }
    
    function validateRow($row) {
        var statement = $row.find('.tfng-statement').val().trim();
        var answer = $row.find('.tfng-answer').val();
        var isChecked = $row.find('.tfng-checkbox').is(':checked');
        
        $row.find('.tfng-statement').toggleClass('is-invalid', isChecked && !statement && answer);
        $row.find('.tfng-answer').toggleClass('is-invalid', isChecked && statement && !answer);
        
        var isValid = isChecked && statement && answer;
        $row.toggleClass('is-valid', isValid);
        $row.toggleClass('has-error', isChecked && (statement || answer) && (!statement || !answer));
        
        return isValid;
    }
    
    function updateUI() {
        var validQuestions = [];
        var totalChecked = 0;
        
        $('.tfng-row').each(function() {
            var $row = $(this);
            if (validateRow($row)) {
                validQuestions.push({
                    question_number: $row.data('qnum'),
                    question_text: $row.find('.tfng-statement').val().trim(),
                    correct_answer: $row.find('.tfng-answer').val(),
                    question_type: 'true_false_not_given',
                    question_type_label: 'True / False / Not Given'
                });
            }
            if ($row.find('.tfng-checkbox').is(':checked')) totalChecked++;
        });
        
        $('#tfngSelectedCount').text(validQuestions.length);
        $('#tfngBatchData').val(JSON.stringify(validQuestions));
        
        // Progress
        $('#tfngValidCount').text(validQuestions.length);
        $('#tfngTotalCount').text(totalChecked);
        var pct = totalChecked > 0 ? (validQuestions.length / totalChecked * 100) : 0;
        $('#tfngProgress .progress-fill').css('width', pct + '%');
        $('#tfngProgress').toggle(totalChecked > 0);
        
        $('#tfngMaxWarning').toggleClass('show', rowCount >= maxRows);
        $('#tfngAddRow').prop('disabled', rowCount >= maxRows);
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.tfng-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    for (var i = 0; i < 4; i++) addRow();
    
    $('#tfngAddRow').off('click.tfng').on('click.tfng', addRow);
    
    $(document).off('change.tfng', '.tfng-checkbox').on('change.tfng', '.tfng-checkbox', function() {
        $(this).closest('.tfng-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#tfngSelectAll').off('change.tfng').on('change.tfng', function() {
        var c = $(this).is(':checked');
        $('.tfng-checkbox').prop('checked', c);
        $('.tfng-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.tfng change.tfng2', '.tfng-statement, .tfng-answer').on('input.tfng change.tfng2', '.tfng-statement, .tfng-answer', function() {
        validateRow($(this).closest('.tfng-row'));
        updateUI();
    });
    
    $(document).off('click.tfng', '.tfng-remove-row').on('click.tfng', '.tfng-remove-row', function(e) {
        e.stopPropagation();
        $(this).closest('.tfng-row').remove();
        rowCount--;
        updateQNumbers();
    });
    
    $('#question_number').off('input.tfng').on('input.tfng', updateQNumbers);
}, 100);
</script>
