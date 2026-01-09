
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="green">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Multiple Choice (Single Answer)
        <span class="badge badge-success ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box success">
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
        <span><strong>Batch Mode:</strong> Tạo nhiều câu hỏi trắc nghiệm. Mỗi câu có options riêng. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mcsProgress" class="progress-bar bg-success" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mcsValidCount">0</span>/<span id="mcsTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mcsSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Câu hỏi & Options <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="mcsRows"></div>
    
    
    <div id="mcsMaxWarning" class="max-rows-warning" style="display: none;">
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
        <button type="button" class="btn btn-add-row" id="mcsAddRow">
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
<?php $component->withName('iconsax-bul-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success mr-4','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="mcsSelectedCount" class="text-success">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="mcsBatchData" value="[]">
</div>

<style>
.mcs-options-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; margin-top: 8px; }
.mcs-option-item { display: flex; align-items: center; gap: 8px; }
.mcs-option-letter { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; font-weight: 600; font-size: 12px; flex-shrink: 0; transition: all 0.2s; }
.mcs-option-letter.correct { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #fff; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 10;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var L = ['A','B','C','D'];
    
    function validateRow($row) {
        var questionText = $.trim($row.find('.mcs-question').val());
        var correct = $row.find('.mcs-correct').val();
        var optionCount = 0;
        $row.find('.mcs-option').each(function() {
            if ($.trim($(this).val())) optionCount++;
        });
        
        var isValid = questionText.length >= 5 && correct !== '' && optionCount >= 2;
        
        $row.find('.mcs-question').toggleClass('is-invalid', questionText.length > 0 && questionText.length < 5)
            .toggleClass('is-valid', questionText.length >= 5);
        $row.find('.mcs-correct').toggleClass('is-invalid', correct === '' && questionText.length > 0)
            .toggleClass('is-valid', correct !== '');
        
        $row.toggleClass('has-error', !isValid && questionText.length > 0);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mcsMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var optionsHtml = L.map(function(l) {
            return '<div class="mcs-option-item"><span class="mcs-option-letter" data-letter="'+l+'">'+l+'</span><input type="text" class="form-control form-control-sm mcs-option" data-letter="'+l+'" placeholder="Option '+l+'" maxlength="200"></div>';
        }).join('');
        
        var html = '<div class="batch-row mcs-row p-12 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out; margin-bottom: 12px;">' +
            '<div class="d-flex align-items-start">' +
                '<div style="width: 40px; text-align: center; padding-top: 8px;"><input type="checkbox" class="batch-check mcs-checkbox" checked></div>' +
                '<div style="width: 50px; text-align: center; padding-top: 4px;"><span class="q-badge">Q'+qNum+'</span></div>' +
                '<div class="flex-fill mx-12">' +
                    '<input type="text" class="form-control form-control-sm mcs-question mb-8" placeholder="Nhập câu hỏi..." maxlength="500">' +
                    '<div class="mcs-options-grid">'+optionsHtml+'</div>' +
                    '<div class="mt-8 d-flex align-items-center gap-12">' +
                        '<label class="font-12 text-muted mb-0">Đáp án đúng:</label>' +
                        '<select class="form-control form-control-sm mcs-correct" style="width: 80px;"><option value="">--</option>'+L.map(function(l){return '<option value="'+l+'">'+l+'</option>';}).join('')+'</select>' +
                    '</div>' +
                '</div>' +
                '<button type="button" class="btn btn-remove"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></button>' +
            '</div>' +
        '</div>';
        
        $('#mcsRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mcsMaxWarning').slideDown(200);
        }
        
        $('#mcsRows .batch-row:last .mcs-question').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        
        $('.mcs-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            // Update correct indicator
            var correct = $row.find('.mcs-correct').val();
            $row.find('.mcs-option-letter').removeClass('correct');
            if (correct) $row.find('.mcs-option-letter[data-letter="'+correct+'"]').addClass('correct');
            
            if ($row.find('.mcs-checkbox').is(':checked') && isValid) {
                validCount++;
                var options = {};
                $row.find('.mcs-option').each(function() {
                    var l = $(this).data('letter');
                    var v = $.trim($(this).val());
                    if (v) options[l] = v;
                });
                
                var q = {
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.mcs-question').val()),
                    correct_answer: correct,
                    question_type: 'multiple_choice_single',
                    question_type_label: 'Multiple Choice (Single)'
                };
                for (var k in options) q['options['+k+']'] = options[k];
                questions.push(q);
            }
        });
        
        var total = $('.mcs-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#mcsSelectedCount').text(questions.length);
        $('#mcsValidCount').text(validCount);
        $('#mcsTotalCount').text(total);
        $('#mcsProgress').css('width', percent + '%');
        $('#mcsBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.mcs-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Initialize with 2 rows
    for (var i = 0; i < 2; i++) addRow();
    
    // Event handlers
    $('#mcsAddRow').off('click.mcs').on('click.mcs', addRow);
    
    $(document).off('change.mcs', '.mcs-checkbox').on('change.mcs', '.mcs-checkbox', function() {
        $(this).closest('.mcs-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mcsSelectAll').off('change.mcs').on('change.mcs', function() {
        var c = $(this).is(':checked');
        $('.mcs-checkbox').prop('checked', c);
        $('.mcs-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.mcs', '.mcs-question, .mcs-option').on('input.mcs', '.mcs-question, .mcs-option', function() {
        validateRow($(this).closest('.mcs-row'));
        updateUI();
    });
    
    $(document).off('change.mcs2', '.mcs-correct').on('change.mcs2', '.mcs-correct', function() {
        validateRow($(this).closest('.mcs-row'));
        updateUI();
    });
    
    $(document).off('click.mcs', '.btn-remove').on('click.mcs', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.mcs-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mcsMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.mcs').on('input.mcs', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mcs').on('keydown.mcs', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/multiple_choice_single.blade.php ENDPATH**/ ?>