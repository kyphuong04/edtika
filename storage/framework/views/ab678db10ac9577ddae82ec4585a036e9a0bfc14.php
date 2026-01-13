
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="yellow">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Sentence Completion
        <span class="badge badge-success ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box success">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-lamp-on'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        <span><strong>Hướng dẫn:</strong> Nhập câu với blank (dùng _____ hoặc ______) và đáp án. Checkbox để chọn câu muốn thêm.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit <small class="text-muted">(áp dụng tất cả)</small></label>
                <select id="scWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN THREE WORDS">NO MORE THAN THREE WORDS</option>
                    <option value="NO MORE THAN TWO WORDS AND/OR A NUMBER">NO MORE THAN TWO WORDS AND/OR A NUMBER</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8 d-flex align-items-end">
            <div class="batch-progress flex-fill" id="scProgress" style="display:none;">
                <div class="progress-bar"><div class="progress-fill" style="width:0%"></div></div>
                <div class="progress-text"><span id="scValidCount">0</span>/<span id="scTotalCount">0</span> hoàn thành</div>
            </div>
        </div>
    </div>
    
    
    <div class="batch-header">
        <div class="col-check">
            <input type="checkbox" id="scSelectAll" class="batch-check" title="Chọn/Bỏ chọn tất cả">
        </div>
        <div class="col-qnum">Q.</div>
        <div class="flex-fill">Câu có blank (_____)</div>
        <div style="width: 160px; text-align: center;">Đáp án</div>
        <div class="col-action"></div>
    </div>
    
    <div id="scRows"></div>
    
    <div class="max-rows-warning" id="scMaxWarning">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-warning-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons mr-4','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>Đã đạt giới hạn 12 câu hỏi mỗi lần thêm.
    </div>
    
    <div class="batch-footer">
        <button type="button" class="btn-add-row" id="scAddRow">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>Thêm câu
            <span class="shortcut-hint"><kbd>Alt+A</kbd></span>
        </button>
        <div class="batch-counter">
            <span>Đã chọn:</span>
            <strong id="scSelectedCount">0</strong>
            <span>câu hỏi hợp lệ</span>
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="scBatchData" value="[]">
</div>

<style>
/* Sentence Completion specific styles */
.sc-row { --type-color: #d97706; --type-gradient: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); }
.sc-row .q-badge { background: var(--type-gradient); }
.sc-row.selected { border-color: var(--type-color); background: rgba(217,119,6,0.03); }
.sc-row .form-control:focus { border-color: var(--type-color); box-shadow: 0 0 0 3px rgba(217,119,6,0.1); }
</style>

<script>
setTimeout(function() {
    var rowCount = 0, maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var $container = $('#scRows');
    
    function createRow(qNum) {
        return $(`
            <div class="batch-row sc-row selected new" data-qnum="${qNum}">
                <div class="col-check" style="width:44px;text-align:center;">
                    <input type="checkbox" class="batch-check sc-checkbox" checked>
                </div>
                <div class="col-qnum" style="width:56px;text-align:center;">
                    <span class="q-badge">Q${qNum}</span>
                </div>
                <div class="flex-fill px-8">
                    <div class="input-wrap">
                        <input type="text" class="form-control sc-sentence" placeholder="Nhập câu với blank, ví dụ: The main cause was _____" maxlength="500">
                    </div>
                </div>
                <div style="width:160px;">
                    <input type="text" class="form-control answer-field sc-answer" placeholder="Đáp án" maxlength="100">
                </div>
                <div class="col-action" style="width:44px;text-align:center;">
                    <button type="button" class="btn-remove sc-remove-row" title="Xóa"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>
                </div>
            </div>
        `);
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#scMaxWarning').addClass('show');
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        $container.append(createRow(qNum));
        updateUI();
        // Focus vào input mới
        $container.find('.sc-row:last .sc-sentence').focus();
    }
    
    function validateRow($row) {
        var sentence = $row.find('.sc-sentence').val().trim();
        var answer = $row.find('.sc-answer').val().trim();
        var isChecked = $row.find('.sc-checkbox').is(':checked');
        
        // Validation: câu phải có blank
        var hasBlank = sentence.includes('_____') || sentence.includes('______') || sentence.includes('___');
        
        $row.find('.sc-sentence').toggleClass('is-invalid', isChecked && sentence && !hasBlank);
        $row.find('.sc-answer').toggleClass('is-invalid', isChecked && sentence && hasBlank && !answer);
        
        var isValid = isChecked && sentence && hasBlank && answer;
        $row.toggleClass('is-valid', isValid);
        $row.toggleClass('has-error', isChecked && (!sentence || !hasBlank || !answer) && (sentence || answer));
        
        return isValid;
    }
    
    function updateUI() {
        var validQuestions = [];
        var totalChecked = 0;
        var wordLimit = $('#scWordLimit').val();
        
        $('.sc-row').each(function() {
            var $row = $(this);
            if (validateRow($row)) {
                validQuestions.push({
                    question_number: $row.data('qnum'),
                    question_text: $row.find('.sc-sentence').val().trim(),
                    correct_answer: $row.find('.sc-answer').val().trim(),
                    word_limit: wordLimit,
                    question_type: 'sentence_completion',
                    question_type_label: 'Sentence Completion'
                });
            }
            if ($row.find('.sc-checkbox').is(':checked')) totalChecked++;
        });
        
        $('#scSelectedCount').text(validQuestions.length);
        $('#scBatchData').val(JSON.stringify(validQuestions));
        
        // Progress
        $('#scValidCount').text(validQuestions.length);
        $('#scTotalCount').text(totalChecked);
        var pct = totalChecked > 0 ? (validQuestions.length / totalChecked * 100) : 0;
        $('#scProgress .progress-fill').css('width', pct + '%');
        $('#scProgress').toggle(totalChecked > 0);
        
        // Max warning
        $('#scMaxWarning').toggleClass('show', rowCount >= maxRows);
        $('#scAddRow').prop('disabled', rowCount >= maxRows);
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.sc-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Events
    $('#scAddRow').off('click.sc').on('click.sc', addRow);
    $('#scWordLimit').off('change.sc').on('change.sc', updateUI);
    
    $(document).off('change.sc', '.sc-checkbox').on('change.sc', '.sc-checkbox', function() {
        $(this).closest('.sc-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#scSelectAll').off('change.sc').on('change.sc', function() {
        var c = $(this).is(':checked');
        $('.sc-checkbox').prop('checked', c);
        $('.sc-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.sc', '.sc-sentence, .sc-answer').on('input.sc', '.sc-sentence, .sc-answer', function() {
        validateRow($(this).closest('.sc-row'));
        updateUI();
    });
    
    $(document).off('click.sc', '.sc-remove-row').on('click.sc', '.sc-remove-row', function(e) {
        e.stopPropagation();
        $(this).closest('.sc-row').remove();
        rowCount--;
        updateQNumbers();
    });
    
    $('#question_number').off('input.sc').on('input.sc', updateQNumbers);
    
    // Keyboard shortcuts
    $(document).off('keydown.sc').on('keydown.sc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
    $('#question_number').off('input.sc').on('input.sc', updateQNumbers);
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/sentence_completion.blade.php ENDPATH**/ ?>