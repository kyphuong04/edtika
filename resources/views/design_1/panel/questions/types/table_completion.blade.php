{{-- Table Completion (batch) --}}
@include('design_1.panel.questions.types._form_styles')

<div class="type-form-content" data-type-color="purple">
    <h4>
        <x-iconsax-lin-grid-1 class="icons text-purple mr-8" width="20px" height="20px"/>
        Table Completion
        <span class="badge badge-purple ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box purple">
        <x-iconsax-lin-flash class="icons" width="16px" height="16px"/>
        <span><strong>Batch Mode:</strong> Thêm nhiều ô trống (cells) trong bảng cần điền. Sử dụng <code>_____</code> để đánh dấu chỗ trống. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    {{-- Shared Settings --}}
    <div class="row mb-16">
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit</label>
                <select id="tcWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN TWO WORDS AND/OR A NUMBER">NO MORE THAN TWO WORDS AND/OR A NUMBER</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Column Headers</label>
                <input type="text" id="tcColumns" class="form-control" placeholder="Year, Event, Impact" maxlength="200">
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex align-items-end">
            <div class="batch-progress flex-fill ml-md-16 mt-8 mt-md-0">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div id="tcProgress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);"></div>
                </div>
                <small class="text-muted mt-4 d-block"><span id="tcValidCount">0</span>/<span id="tcTotalCount">0</span> câu hợp lệ</small>
            </div>
        </div>
    </div>
    
    {{-- Table header --}}
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="tcSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 100px;">Row/Cell</div>
        <div class="flex-fill">Nội dung ô với blank <code>_____</code> <span class="text-danger">*</span></div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="tcRows"></div>
    
    {{-- Max rows warning --}}
    <div id="tcMaxWarning" class="max-rows-warning" style="display: none;">
        <x-iconsax-lin-warning-2 class="icons" width="16px" height="16px"/> Đã đạt giới hạn tối đa 12 câu hỏi
    </div>
    
    {{-- Footer --}}
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="tcAddRow" style="--type-color: #6f42c1;">
            <x-iconsax-lin-add class="icons mr-4" width="16px" height="16px"/>Thêm ô
        </button>
        <div class="batch-counter">
            <x-iconsax-bul-tick-circle class="icons mr-4" style="color: #6f42c1;" width="20px" height="20px"/>
            Hợp lệ: <strong id="tcSelectedCount" style="color: #6f42c1;">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="tcBatchData" value="[]">
</div>

<style>
.badge-purple { background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%); color: #fff; }
.text-purple { color: #6f42c1 !important; }
.info-box.purple { background: rgba(111, 66, 193, 0.08); border-left-color: #6f42c1; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var cell = $.trim($row.find('.tc-cell').val());
        var answer = $.trim($row.find('.tc-answer').val());
        var hasBlank = cell.indexOf('_____') !== -1 || cell.indexOf('____') !== -1 || cell.indexOf('___') !== -1;
        var isValid = cell.length >= 3 && hasBlank && answer.length >= 1;
        
        $row.find('.tc-cell').toggleClass('is-invalid', cell.length > 0 && (!hasBlank || cell.length < 3))
            .toggleClass('is-valid', cell.length >= 3 && hasBlank);
        $row.find('.tc-answer').toggleClass('is-invalid', answer.length === 0 && cell.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && (cell.length > 0 || answer.length > 0));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#tcMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check tc-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #6f42c1 0%, #563d7c 100%);">Q'+qNum+'</span></div>' +
            '<div style="width: 100px; padding-right: 8px;"><input type="text" class="form-control form-control-sm tc-rowinfo" placeholder="R1, C1" maxlength="30"></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control tc-cell" placeholder="Ví dụ: Year: 1995, Event: _____" maxlength="400"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control tc-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#tcRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#tcMaxWarning').slideDown(200);
        }
        
        $('#tcRows .batch-row:last .tc-cell').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#tcWordLimit').val();
        var columns = $('#tcColumns').val();
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.tc-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.tc-cell').val()),
                    correct_answer: $.trim($row.find('.tc-answer').val()),
                    word_limit: wordLimit,
                    row_context: $.trim($row.find('.tc-rowinfo').val()),
                    columns: columns,
                    question_type: 'table_completion',
                    question_type_label: 'Table Completion'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#tcSelectedCount').text(questions.length);
        $('#tcValidCount').text(validCount);
        $('#tcTotalCount').text(total);
        $('#tcProgress').css('width', percent + '%');
        $('#tcBatchData').val(JSON.stringify(questions));
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
    $('#tcAddRow').off('click.tc').on('click.tc', addRow);
    $('#tcWordLimit, #tcColumns').off('change.tc').on('change.tc', updateUI);
    
    $(document).off('change.tc', '.tc-checkbox').on('change.tc', '.tc-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#tcSelectAll').off('change.tc').on('change.tc', function() {
        var c = $(this).is(':checked');
        $('.tc-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.tc', '.tc-cell, .tc-answer, .tc-rowinfo').on('input.tc', '.tc-cell, .tc-answer, .tc-rowinfo', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.tc', '.btn-remove').on('click.tc', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#tcMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.tc').on('input.tc', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.tc').on('keydown.tc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
