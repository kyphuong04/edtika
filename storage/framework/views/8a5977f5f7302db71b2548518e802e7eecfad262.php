
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="yellow">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-image'); ?>
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
        Diagram Labeling
        <span class="badge badge-warning ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box warning">
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
        <span><strong>Batch Mode:</strong> Upload 1 diagram, thêm nhiều labels cần điền. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-6">
            <div class="form-group mb-0">
                <label class="form-group-label is-required">Diagram Image <span class="text-danger">*</span></label>
                <input type="file" name="diagram_image" id="dlDiagramImg" class="form-control" accept="image/*">
                <small class="text-muted">Upload diagram với các số labels</small>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit</label>
                <select id="dlWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN THREE WORDS">NO MORE THAN THREE WORDS</option>
                </select>
            </div>
        </div>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="dlProgress" class="progress-bar bg-warning" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="dlValidCount">0</span>/<span id="dlTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="dlSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 80px;">Label #</div>
        <div class="flex-fill">Mô tả (tùy chọn)</div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="dlRows"></div>
    
    
    <div id="dlMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 15 labels
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="dlAddRow">
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
<?php endif; ?>Thêm label
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
<?php $component->withAttributes(['class' => 'icons text-warning mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="dlSelectedCount" class="text-warning">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="dlBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 15;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var answer = $.trim($row.find('.dl-answer').val());
        var isValid = answer.length >= 1;
        
        $row.find('.dl-answer').toggleClass('is-invalid', !isValid && $row.find('.dl-answer').val().length > 0)
            .toggleClass('is-valid', isValid);
        
        $row.toggleClass('has-error', !isValid);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#dlMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        var labelNum = rowCount + 1;
        rowCount++;
        
        var html = '<div class="batch-row dl-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check dl-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div style="width: 80px; padding-right: 8px;"><input type="number" class="form-control form-control-sm dl-labelnum" value="'+labelNum+'" min="1" max="99"></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control dl-desc" placeholder="Mô tả vị trí (tùy chọn)" maxlength="200"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control dl-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#dlRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#dlMaxWarning').slideDown(200);
        }
        
        $('#dlRows .batch-row:last .dl-answer').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#dlWordLimit').val();
        
        $('.dl-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.dl-checkbox').is(':checked') && isValid) {
                validCount++;
                var labelNum = $row.find('.dl-labelnum').val();
                var desc = $.trim($row.find('.dl-desc').val());
                var answer = $.trim($row.find('.dl-answer').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: desc || 'Label ' + labelNum,
                    correct_answer: answer,
                    word_limit: wordLimit,
                    label_number: labelNum,
                    question_type: 'diagram_labeling',
                    question_type_label: 'Diagram Labeling'
                });
            }
        });
        
        var total = $('.dl-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#dlSelectedCount').text(questions.length);
        $('#dlValidCount').text(validCount);
        $('#dlTotalCount').text(total);
        $('#dlProgress').css('width', percent + '%');
        $('#dlBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.dl-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 4 rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#dlAddRow').off('click.dl').on('click.dl', addRow);
    $('#dlWordLimit').off('change.dl').on('change.dl', updateUI);
    
    $(document).off('change.dl', '.dl-checkbox').on('change.dl', '.dl-checkbox', function() {
        $(this).closest('.dl-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#dlSelectAll').off('change.dl').on('change.dl', function() {
        var c = $(this).is(':checked');
        $('.dl-checkbox').prop('checked', c);
        $('.dl-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.dl', '.dl-labelnum, .dl-desc, .dl-answer').on('input.dl', '.dl-labelnum, .dl-desc, .dl-answer', function() {
        validateRow($(this).closest('.dl-row'));
        updateUI();
    });
    
    $(document).off('click.dl2', '.btn-remove').on('click.dl2', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.dl-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#dlMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.dl').on('input.dl', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.dl').on('keydown.dl', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/diagram_labeling.blade.php ENDPATH**/ ?>