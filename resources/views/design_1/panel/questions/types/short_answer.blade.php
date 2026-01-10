{{-- Short Answer (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="green">
    <h4>
        <x-iconsax-lin-info-circle class="icons text-success mr-8" width="20px" height="20px"/>
        Short Answer Questions
        <span class="badge badge-success ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box success">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm nhiều câu hỏi ngắn. Mỗi câu có câu hỏi và đáp án. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Shared Word Limit --}}
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit (áp dụng tất cả)</label>
                <select id="saWordLimit" class="form-control">
                    <option value="NO MORE THAN ONE WORD">NO MORE THAN ONE WORD</option>
                    <option value="NO MORE THAN TWO WORDS">NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN THREE WORDS" selected>NO MORE THAN THREE WORDS</option>
                    <option value="NO MORE THAN THREE WORDS AND/OR A NUMBER">NO MORE THAN THREE WORDS AND/OR A NUMBER</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8 d-flex align-items-end">
            <div class="batch-progress flex-fill ml-md-16 mt-8 mt-md-0">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div id="saProgress" class="progress-bar bg-success" style="width: 0%; transition: width 0.3s;"></div>
                </div>
                <small class="text-muted mt-4 d-block"><span id="saValidCount">0</span>/<span id="saTotalCount">0</span> câu hợp lệ</small>
            </div>
        </div>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="saSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Câu hỏi <span class="text-danger">*</span></div>
        <div style="width: 180px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="saRows"></div>
    
    {{-- Max rows warning --}}
    <div id="saMaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 12 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="saAddRow">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm câu hỏi
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons text-success mr-4" width="20px" height="20px"/>
            Hợp lệ: <strong id="saSelectedCount" class="text-success">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="saBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var question = $.trim($row.find('.sa-question').val());
        var answer = $.trim($row.find('.sa-answer').val());
        var isValid = question.length >= 3 && answer.length >= 1;
        
        $row.find('.sa-question').toggleClass('is-invalid', question.length > 0 && question.length < 3)
            .toggleClass('is-valid', question.length >= 3);
        $row.find('.sa-answer').toggleClass('is-invalid', answer.length === 0 && question.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && (question.length > 0 || answer.length > 0));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#saMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check sa-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control sa-question" placeholder="Nhập câu hỏi..." maxlength="300"></div>' +
            '<div style="width: 180px;"><input type="text" class="form-control sa-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#saRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#saMaxWarning').slideDown(200);
        }
        
        $('#saRows .batch-row:last .sa-question').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#saWordLimit').val();
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.sa-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.sa-question').val()),
                    correct_answer: $.trim($row.find('.sa-answer').val()),
                    word_limit: wordLimit,
                    question_type: 'short_answer',
                    question_type_label: 'Short Answer'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#saSelectedCount').text(questions.length);
        $('#saValidCount').text(validCount);
        $('#saTotalCount').text(total);
        $('#saProgress').css('width', percent + '%');
        $('#saBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.batch-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Initialize with 4 rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#saAddRow').off('click.sa').on('click.sa', addRow);
    $('#saWordLimit').off('change.sa').on('change.sa', updateUI);
    
    $(document).off('change.sa', '.sa-checkbox').on('change.sa', '.sa-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#saSelectAll').off('change.sa').on('change.sa', function() {
        var c = $(this).is(':checked');
        $('.sa-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.sa', '.sa-question, .sa-answer').on('input.sa', '.sa-question, .sa-answer', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.sa', '.btn-remove').on('click.sa', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#saMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.sa').on('input.sa', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.sa').on('keydown.sa', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
