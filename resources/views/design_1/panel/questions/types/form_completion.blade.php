{{-- Form Completion (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="purple">
    <h4>
        <x-iconsax-lin-clipboard-text class="icons text-purple mr-8" width="20px" height="20px"/>
        Form Completion
        <span class="badge badge-secondary ml-8" style="background: #6f42c1;">Batch Mode</span>
    </h4>
    
    <div class="info-box" style="background: rgba(111, 66, 193, 0.1); border-color: #6f42c1;">
        <x-iconsax-lin-flash class="icons" style="color: #6f42c1;" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm nhiều fields của form cần điền (Listening Part 1). <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Shared Settings --}}
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit (áp dụng tất cả)</label>
                <select id="formcWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="ONE WORD AND/OR A NUMBER" selected>ONE WORD AND/OR A NUMBER</option>
                    <option value="NO MORE THAN TWO WORDS AND/OR A NUMBER">NO MORE THAN TWO WORDS AND/OR A NUMBER</option>
                </select>
            </div>
        </div>
    </div>
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="formcProgress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: #6f42c1;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="formcValidCount">0</span>/<span id="formcTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="formcSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 150px;">Field Label <span class="text-danger">*</span></div>
        <div class="flex-fill">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 120px; text-align: center;">Alternative</div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="formcRows"></div>
    
    {{-- Max rows warning --}}
    <div id="formcMaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 10 fields
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="formcAddRow">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm field
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons mr-4" style="color: #6f42c1;" width="20px" height="20px"/>
            Hợp lệ: <strong id="formcSelectedCount" style="color: #6f42c1;">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="formcBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 10;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var defaultFields = ['Name', 'Phone', 'Address', 'Date', 'Email'];
    
    function validateRow($row) {
        var label = $.trim($row.find('.formc-label').val());
        var answer = $.trim($row.find('.formc-answer').val());
        var isValid = label.length >= 1 && answer.length >= 1;
        
        $row.find('.formc-label').toggleClass('is-invalid', label.length === 0 && answer.length > 0)
            .toggleClass('is-valid', label.length >= 1);
        $row.find('.formc-answer').toggleClass('is-invalid', answer.length === 0 && label.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && (label.length > 0 || answer.length > 0));
        return isValid;
    }
    
    function addRow(fieldLabel) {
        if (rowCount >= maxRows) {
            $('#formcMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        var label = fieldLabel || '';
        rowCount++;
        
        var html = '<div class="batch-row formc-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check formc-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div style="width: 150px; padding-right: 8px;"><input type="text" class="form-control form-control-sm formc-label" value="'+label+'" placeholder="Field label" maxlength="100"></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control formc-answer" placeholder="Đáp án chính xác" maxlength="200"></div>' +
            '<div style="width: 120px;"><input type="text" class="form-control form-control-sm formc-alt" placeholder="Alt spelling" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#formcRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#formcMaxWarning').slideDown(200);
        }
        
        if (!fieldLabel) {
            $('#formcRows .batch-row:last .formc-label').focus();
        }
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#formcWordLimit').val();
        
        $('.formc-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.formc-checkbox').is(':checked') && isValid) {
                validCount++;
                var label = $.trim($row.find('.formc-label').val());
                var answer = $.trim($row.find('.formc-answer').val());
                var alt = $.trim($row.find('.formc-alt').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: label,
                    field_label: label,
                    correct_answer: answer,
                    alternative_answer: alt || null,
                    word_limit: wordLimit,
                    question_type: 'form_completion',
                    question_type_label: 'Form Completion'
                });
            }
        });
        
        var total = $('.formc-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#formcSelectedCount').text(questions.length);
        $('#formcValidCount').text(validCount);
        $('#formcTotalCount').text(total);
        $('#formcProgress').css('width', percent + '%');
        $('#formcBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.formc-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with common fields
    for (var i = 0; i < 5; i++) addRow(defaultFields[i] || '');
    
    // Event handlers
    $('#formcAddRow').off('click.formc').on('click.formc', function() { addRow(''); });
    $('#formcWordLimit').off('change.formc').on('change.formc', updateUI);
    
    $(document).off('change.formc', '.formc-checkbox').on('change.formc', '.formc-checkbox', function() {
        $(this).closest('.formc-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#formcSelectAll').off('change.formc').on('change.formc', function() {
        var c = $(this).is(':checked');
        $('.formc-checkbox').prop('checked', c);
        $('.formc-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.formc', '.formc-label, .formc-answer, .formc-alt').on('input.formc', '.formc-label, .formc-answer, .formc-alt', function() {
        validateRow($(this).closest('.formc-row'));
        updateUI();
    });
    
    $(document).off('click.formc2', '.btn-remove').on('click.formc2', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.formc-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#formcMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.formc').on('input.formc', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.formc').on('keydown.formc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow('');
        }
    });
}, 100);
</script>
