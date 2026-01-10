{{-- Matching Info (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="cyan">
    <h4>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8 text-info"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Matching Information
        <span class="badge badge-info ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box info">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Nhập nhiều statements, chọn đoạn văn (A, B, C...) phù hợp cho mỗi câu. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Progress bar --}}
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="miProgress" class="progress-bar bg-info" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="miValidCount">0</span>/<span id="miTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="miSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Statement <span class="text-danger">*</span></div>
        <div style="width: 100px; text-align: center;">Đoạn <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="miRows"></div>
    
    {{-- Max rows warning --}}
    <div id="miMaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 12 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="miAddRow" style="--type-color: #17a2b8;">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm statement
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons text-info mr-4" width="20px" height="20px"/>
            Hợp lệ: <strong id="miSelectedCount" class="text-info">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="miBatchData" value="[]">
</div>

<style>
.info-box.info { background: rgba(23, 162, 184, 0.08); border-left-color: #17a2b8; }
.mi-para-select { font-weight: 700; font-size: 16px; text-align: center; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var paragraphs = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    
    function buildParagraphOptions(selected) {
        return '<option value="">--</option>' + paragraphs.map(function(p) {
            return '<option value="'+p+'" '+(p === selected ? 'selected' : '')+'>'+p+'</option>';
        }).join('');
    }
    
    function validateRow($row) {
        var statement = $.trim($row.find('.mi-statement').val());
        var para = $row.find('.mi-para-select').val();
        var isValid = statement.length >= 5 && para !== '';
        
        $row.find('.mi-statement').toggleClass('is-invalid', statement.length > 0 && statement.length < 5)
            .toggleClass('is-valid', statement.length >= 5);
        $row.find('.mi-para-select').toggleClass('is-invalid', para === '' && statement.length > 0)
            .toggleClass('is-valid', para !== '');
        
        $row.toggleClass('has-error', !isValid && (statement.length > 0 || para !== ''));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#miMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check mi-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control mi-statement" placeholder="Nhập statement cần match với đoạn văn..." maxlength="500"></div>' +
            '<div style="width: 100px;"><select class="form-control mi-para-select">'+buildParagraphOptions()+'</select></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#miRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#miMaxWarning').slideDown(200);
        }
        
        $('#miRows .batch-row:last .mi-statement').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.mi-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.mi-statement').val()),
                    correct_answer: $row.find('.mi-para-select').val(),
                    question_type: 'matching_information',
                    question_type_label: 'Matching Information'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#miSelectedCount').text(questions.length);
        $('#miValidCount').text(validCount);
        $('#miTotalCount').text(total);
        $('#miProgress').css('width', percent + '%');
        $('#miBatchData').val(JSON.stringify(questions));
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
    $('#miAddRow').off('click.mi').on('click.mi', addRow);
    
    $(document).off('change.mi', '.mi-checkbox').on('change.mi', '.mi-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#miSelectAll').off('change.mi').on('change.mi', function() {
        var c = $(this).is(':checked');
        $('.mi-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.mi', '.mi-statement').on('input.mi', '.mi-statement', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('change.mi2', '.mi-para-select').on('change.mi2', '.mi-para-select', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.mi', '.btn-remove').on('click.mi', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#miMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.mi').on('input.mi', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mi').on('keydown.mi', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
