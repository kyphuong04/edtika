
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="cyan">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-info mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Multiple Choice (Multiple Answers)
        <span class="badge badge-info ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box info">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-flash'); ?>
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
        <span><strong>Batch Mode:</strong> Tạo nhiều câu hỏi trắc nghiệm nhiều đáp án. Click vào chữ cái để chọn đáp án đúng. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mcmProgress" class="progress-bar bg-info" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mcmValidCount">0</span>/<span id="mcmTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mcmSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Câu hỏi & Options (tick nhiều đáp án) <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="mcmRows"></div>
    
    
    <div id="mcmMaxWarning" class="max-rows-warning" style="display: none;">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-warning-2'); ?>
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
<?php endif; ?> Đã đạt giới hạn tối đa 10 câu hỏi
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="mcmAddRow">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
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
<?php endif; ?>Thêm câu hỏi
        </button>
        <div class="batch-counter">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-info mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="mcmSelectedCount" class="text-info">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="mcmBatchData" value="[]">
</div>

<style>
.mcm-options-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-top: 8px; }
.mcm-option-item { display: flex; align-items: center; gap: 8px; }
.mcm-option-letter { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; font-weight: 600; font-size: 12px; flex-shrink: 0; cursor: pointer; transition: all 0.2s; user-select: none; }
.mcm-option-letter.correct { background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%); color: #fff; }
.mcm-option-letter:hover { background: #e2e6ea; }
.mcm-correct-badge { font-size: 10px; padding: 2px 6px; background: #17a2b8; color: #fff; border-radius: 3px; margin-left: 8px; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 10;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var L = ['A','B','C','D','E'];
    
    function validateRow($row) {
        var questionText = $.trim($row.find('.mcm-question').val());
        var correctCount = $row.find('.mcm-correct-checkbox:checked').length;
        var optionCount = 0;
        $row.find('.mcm-option').each(function() {
            if ($.trim($(this).val())) optionCount++;
        });
        
        var isValid = questionText.length >= 5 && correctCount >= 2 && optionCount >= 2;
        
        $row.find('.mcm-question').toggleClass('is-invalid', questionText.length > 0 && questionText.length < 5)
            .toggleClass('is-valid', questionText.length >= 5);
        
        $row.toggleClass('has-error', !isValid && questionText.length > 0);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mcmMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var optionsHtml = L.map(function(l) {
            return '<div class="mcm-option-item"><label class="mcm-option-letter" data-letter="'+l+'" title="Click để chọn/bỏ đáp án"><input type="checkbox" class="mcm-correct-checkbox" data-letter="'+l+'" style="display:none;">'+l+'</label><input type="text" class="form-control form-control-sm mcm-option" data-letter="'+l+'" placeholder="Option '+l+'" maxlength="200"></div>';
        }).join('');
        
        var html = '<div class="batch-row mcm-row p-12 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out; margin-bottom: 12px;">' +
            '<div class="d-flex align-items-start">' +
                '<div style="width: 40px; text-align: center; padding-top: 8px;"><input type="checkbox" class="batch-check mcm-checkbox" checked></div>' +
                '<div style="width: 50px; text-align: center; padding-top: 4px;"><span class="q-badge">Q'+qNum+'</span></div>' +
                '<div class="flex-fill mx-12">' +
                    '<input type="text" class="form-control form-control-sm mcm-question mb-8" placeholder="Nhập câu hỏi (ví dụ: Which TWO statements are TRUE?)" maxlength="500">' +
                    '<div class="mcm-options-grid">'+optionsHtml+'</div>' +
                    '<div class="mt-8 font-11 text-muted d-flex align-items-center">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-4"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>Click vào chữ cái để chọn đáp án đúng (chọn ít nhất 2)' +
                        '<span class="mcm-correct-badge ml-8">Đã chọn: <span class="mcm-correct-count">0</span></span>' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="btn btn-remove"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>' +
            '</div>' +
        '</div>';
        
        $('#mcmRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mcmMaxWarning').slideDown(200);
        }
        
        $('#mcmRows .batch-row:last .mcm-question').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        
        $('.mcm-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            // Update correct count badge
            var correctCount = $row.find('.mcm-correct-checkbox:checked').length;
            $row.find('.mcm-correct-count').text(correctCount);
            
            if ($row.find('.mcm-checkbox').is(':checked') && isValid) {
                validCount++;
                var correctAnswers = [];
                $row.find('.mcm-correct-checkbox:checked').each(function() {
                    correctAnswers.push($(this).data('letter'));
                });
                var options = {};
                $row.find('.mcm-option').each(function() {
                    var l = $(this).data('letter');
                    var v = $.trim($(this).val());
                    if (v) options[l] = v;
                });
                
                var q = {
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.mcm-question').val()),
                    correct_answer: correctAnswers,
                    question_type: 'multiple_choice_multiple',
                    question_type_label: 'Multiple Choice (Multiple)'
                };
                for (var k in options) q['options['+k+']'] = options[k];
                questions.push(q);
            }
        });
        
        var total = $('.mcm-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#mcmSelectedCount').text(questions.length);
        $('#mcmValidCount').text(validCount);
        $('#mcmTotalCount').text(total);
        $('#mcmProgress').css('width', percent + '%');
        $('#mcmBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.mcm-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 2 rows
    for (var i = 0; i < 2; i++) addRow();
    
    // Event handlers
    $('#mcmAddRow').off('click.mcm').on('click.mcm', addRow);
    
    $(document).off('change.mcm', '.mcm-checkbox').on('change.mcm', '.mcm-checkbox', function() {
        $(this).closest('.mcm-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mcmSelectAll').off('change.mcm').on('change.mcm', function() {
        var c = $(this).is(':checked');
        $('.mcm-checkbox').prop('checked', c);
        $('.mcm-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('click.mcm2', '.mcm-option-letter').on('click.mcm2', '.mcm-option-letter', function() {
        var $checkbox = $(this).find('.mcm-correct-checkbox');
        $checkbox.prop('checked', !$checkbox.is(':checked'));
        $(this).toggleClass('correct', $checkbox.is(':checked'));
        validateRow($(this).closest('.mcm-row'));
        updateUI();
    });
    
    $(document).off('input.mcm', '.mcm-question, .mcm-option').on('input.mcm', '.mcm-question, .mcm-option', function() {
        validateRow($(this).closest('.mcm-row'));
        updateUI();
    });
    
    $(document).off('click.mcm3', '.btn-remove').on('click.mcm3', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.mcm-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mcmMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.mcm').on('input.mcm', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mcm').on('keydown.mcm', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/multiple_choice_multiple.blade.php ENDPATH**/ ?>