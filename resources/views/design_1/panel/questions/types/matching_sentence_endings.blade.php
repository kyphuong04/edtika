{{-- Matching Endings (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="yellow">
    <h4>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8 text-warning"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Matching Sentence Endings
        <span class="badge badge-warning ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box warning">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm danh sách endings trước, sau đó thêm các sentence beginnings. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Endings List (Shared) --}}
    <div class="form-group mb-16">
        <label class="form-group-label is-required">Các phần cuối câu (Sentence Endings)</label>
        <small class="text-muted d-block mb-8">Thêm endings. Nên có thêm vài endings làm nhiễu (distractors).</small>
        <div id="mseEndings" class="dynamic-inputs"></div>
        <button type="button" class="btn btn-sm btn-outline-warning mt-8" id="mseAddEnd">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm Ending
        </button>
    </div>
    
    <hr class="my-16">
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mseProgress" class="progress-bar bg-warning" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mseValidCount">0</span>/<span id="mseTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    {{-- Batch Sentence Beginnings --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mseSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Phần đầu câu <span class="text-danger">*</span></div>
        <div style="width: 100px; text-align: center;">Ending <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="mseRows"></div>
    
    {{-- Max rows warning --}}
    <div id="mseMaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 10 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="mseAddRow" style="--type-color: #ffc107; color: #212529;">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm sentence
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons text-warning mr-4" width="20px" height="20px"/>
            Hợp lệ: <strong id="mseSelectedCount" class="text-warning">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="mseBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var L = 'ABCDEFGH', endingCount = 0, rowCount = 0, maxRows = 10;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function addEnding() {
        if (endingCount >= 8) return;
        var l = L[endingCount++];
        $('#mseEndings').append('<div class="input-group mb-8" style="animation: slideIn 0.2s ease-out;"><span class="input-group-text" style="min-width: 36px; justify-content: center; font-weight: 600; background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: #212529; border: none;">'+l+'</span><input type="text" class="form-control mse-ending-input" data-letter="'+l+'" placeholder="...phần cuối câu '+l+'" maxlength="300"><button type="button" class="btn btn-outline-danger mse-remove-ending" style="border-radius: 0 6px 6px 0;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button></div>');
        updateSelects();
    }
    
    function updateSelects() {
        var opts = '<option value="">--</option>';
        $('#mseEndings .mse-ending-input').each(function() {
            var l = $(this).data('letter');
            var v = $(this).val() || l;
            opts += '<option value="'+l+'">'+l+' - '+v.substring(0,20)+'</option>';
        });
        $('.mse-answer').each(function() {
            var current = $(this).val();
            $(this).html(opts).val(current);
        });
    }
    
    function validateRow($row) {
        var beginning = $.trim($row.find('.mse-beginning').val());
        var answer = $row.find('.mse-answer').val();
        var isValid = beginning.length >= 5 && answer !== '';
        
        $row.find('.mse-beginning').toggleClass('is-invalid', beginning.length > 0 && beginning.length < 5)
            .toggleClass('is-valid', beginning.length >= 5);
        $row.find('.mse-answer').toggleClass('is-invalid', answer === '' && beginning.length > 0)
            .toggleClass('is-valid', answer !== '');
        
        $row.toggleClass('has-error', !isValid && (beginning.length > 0 || answer !== ''));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mseMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check mse-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: #212529;">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control mse-beginning" placeholder="Phần đầu câu (ví dụ: The researcher concluded that...)" maxlength="400"></div>' +
            '<div style="width: 100px;"><select class="form-control mse-answer"></select></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#mseRows').append(html);
        updateSelects();
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mseMaxWarning').slideDown(200);
        }
        
        $('#mseRows .batch-row:last .mse-beginning').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var endings = {};
        
        $('#mseEndings .mse-ending-input').each(function() {
            endings[$(this).data('letter')] = $(this).val();
        });
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.mse-checkbox').is(':checked') && isValid) {
                validCount++;
                var q = {
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.mse-beginning').val()),
                    correct_answer: $row.find('.mse-answer').val(),
                    question_type: 'matching_sentence_endings',
                    question_type_label: 'Matching Sentence Endings'
                };
                for (var k in endings) q['options['+k+']'] = endings[k];
                questions.push(q);
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#mseSelectedCount').text(questions.length);
        $('#mseValidCount').text(validCount);
        $('#mseTotalCount').text(total);
        $('#mseProgress').css('width', percent + '%');
        $('#mseBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.batch-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init 5 endings and 4 rows
    for (var i = 0; i < 5; i++) addEnding();
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#mseAddEnd').off('click.mse').on('click.mse', addEnding);
    $('#mseAddRow').off('click.mse').on('click.mse', addRow);
    
    $(document).off('click.mse', '.mse-remove-ending').on('click.mse', '.mse-remove-ending', function() {
        endingCount--;
        $(this).closest('.input-group').remove();
        updateSelects();
        updateUI();
    });
    
    $(document).off('input.mse', '.mse-ending-input').on('input.mse', '.mse-ending-input', function() {
        updateSelects();
        updateUI();
    });
    
    $(document).off('change.mse', '.mse-checkbox').on('change.mse', '.mse-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mseSelectAll').off('change.mse').on('change.mse', function() {
        var c = $(this).is(':checked');
        $('.mse-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.mse2', '.mse-beginning').on('input.mse2', '.mse-beginning', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('change.mse2', '.mse-answer').on('change.mse2', '.mse-answer', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.mse3', '.btn-remove').on('click.mse3', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mseMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.mse').on('input.mse', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mse').on('keydown.mse', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
