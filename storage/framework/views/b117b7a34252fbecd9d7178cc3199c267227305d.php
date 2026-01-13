
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="cyan">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-hierarchy-2'); ?>
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
        Flow Chart Completion
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
        <span><strong>Batch Mode:</strong> Upload 1 flow chart, thêm nhiều steps cần điền. Dùng _____ cho blank. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-6">
            <div class="form-group mb-0">
                <label class="form-group-label">Flow Chart Image (tùy chọn)</label>
                <input type="file" name="chart_image" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit</label>
                <select id="fcWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN THREE WORDS">NO MORE THAN THREE WORDS</option>
                </select>
            </div>
        </div>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="fcProgress" class="progress-bar bg-info" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="fcValidCount">0</span>/<span id="fcTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="fcSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 80px;">Stage</div>
        <div class="flex-fill">Step với blank (_____) <span class="text-danger">*</span></div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="fcRows"></div>
    
    
    <div id="fcMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 12 steps
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="fcAddRow">
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
<?php endif; ?>Thêm step
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
<?php $component->withAttributes(['class' => 'icons text-info mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="fcSelectedCount" class="text-info">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="fcBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var step = $.trim($row.find('.fc-step').val());
        var answer = $.trim($row.find('.fc-answer').val());
        var hasBlank = step.indexOf('_____') !== -1 || step.indexOf('___') !== -1;
        var isValid = step.length >= 5 && answer.length >= 1;
        
        $row.find('.fc-step').toggleClass('is-invalid', step.length > 0 && (step.length < 5 || !hasBlank))
            .toggleClass('is-valid', step.length >= 5 && hasBlank);
        $row.find('.fc-answer').toggleClass('is-invalid', answer.length === 0 && step.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && step.length > 0);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#fcMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        var stageNum = rowCount + 1;
        rowCount++;
        
        var html = '<div class="batch-row fc-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check fc-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div style="width: 80px; padding-right: 8px;"><input type="text" class="form-control form-control-sm fc-stage" value="Step '+stageNum+'" maxlength="50"></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control fc-step" placeholder="Ví dụ: _____ are collected and sorted" maxlength="300"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control fc-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#fcRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#fcMaxWarning').slideDown(200);
        }
        
        $('#fcRows .batch-row:last .fc-step').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#fcWordLimit').val();
        
        $('.fc-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.fc-checkbox').is(':checked') && isValid) {
                validCount++;
                var stage = $.trim($row.find('.fc-stage').val());
                var step = $.trim($row.find('.fc-step').val());
                var answer = $.trim($row.find('.fc-answer').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: step,
                    correct_answer: answer,
                    word_limit: wordLimit,
                    stage: stage,
                    question_type: 'flow_chart',
                    question_type_label: 'Flow Chart Completion'
                });
            }
        });
        
        var total = $('.fc-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#fcSelectedCount').text(questions.length);
        $('#fcValidCount').text(validCount);
        $('#fcTotalCount').text(total);
        $('#fcProgress').css('width', percent + '%');
        $('#fcBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.fc-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 4 rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#fcAddRow').off('click.fc').on('click.fc', addRow);
    $('#fcWordLimit').off('change.fc').on('change.fc', updateUI);
    
    $(document).off('change.fc', '.fc-checkbox').on('change.fc', '.fc-checkbox', function() {
        $(this).closest('.fc-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#fcSelectAll').off('change.fc').on('change.fc', function() {
        var c = $(this).is(':checked');
        $('.fc-checkbox').prop('checked', c);
        $('.fc-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.fc', '.fc-stage, .fc-step, .fc-answer').on('input.fc', '.fc-stage, .fc-step, .fc-answer', function() {
        validateRow($(this).closest('.fc-row'));
        updateUI();
    });
    
    $(document).off('click.fc2', '.btn-remove').on('click.fc2', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.fc-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#fcMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.fc').on('input.fc', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.fc').on('keydown.fc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/flow_chart.blade.php ENDPATH**/ ?>