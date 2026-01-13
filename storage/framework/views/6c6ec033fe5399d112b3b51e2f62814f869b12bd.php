
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="gray">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-secondary mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Note Completion
        <span class="badge badge-secondary ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box secondary">
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
        <span><strong>Batch Mode:</strong> Thêm nhiều notes/bullet points cần điền. Sử dụng <code>_____</code> để đánh dấu chỗ trống. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-4">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit (áp dụng tất cả)</label>
                <select id="ncWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN TWO WORDS AND/OR A NUMBER">NO MORE THAN TWO WORDS AND/OR A NUMBER</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-8 d-flex align-items-end">
            <div class="batch-progress flex-fill ml-md-16 mt-8 mt-md-0">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div id="ncProgress" class="progress-bar bg-secondary" style="width: 0%; transition: width 0.3s;"></div>
                </div>
                <small class="text-muted mt-4 d-block"><span id="ncValidCount">0</span>/<span id="ncTotalCount">0</span> câu hợp lệ</small>
            </div>
        </div>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="ncSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Note với blank <code>_____</code> <span class="text-danger">*</span></div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="ncRows"></div>
    
    
    <div id="ncMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 12 câu hỏi
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="ncAddRow" style="--type-color: #6c757d;">
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
<?php endif; ?>Thêm note
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
<?php $component->withAttributes(['class' => 'icons text-secondary mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="ncSelectedCount" class="text-secondary">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="ncBatchData" value="[]">
</div>

<style>
.info-box.secondary { background: rgba(108, 117, 125, 0.08); border-left-color: #6c757d; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var note = $.trim($row.find('.nc-note').val());
        var answer = $.trim($row.find('.nc-answer').val());
        var hasBlank = note.indexOf('_____') !== -1 || note.indexOf('____') !== -1 || note.indexOf('___') !== -1;
        var isValid = note.length >= 5 && hasBlank && answer.length >= 1;
        
        $row.find('.nc-note').toggleClass('is-invalid', note.length > 0 && (!hasBlank || note.length < 5))
            .toggleClass('is-valid', note.length >= 5 && hasBlank);
        $row.find('.nc-answer').toggleClass('is-invalid', answer.length === 0 && note.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && (note.length > 0 || answer.length > 0));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#ncMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check nc-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control nc-note" placeholder="Ví dụ: Main materials: wood and _____" maxlength="400"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control nc-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#ncRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#ncMaxWarning').slideDown(200);
        }
        
        $('#ncRows .batch-row:last .nc-note').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#ncWordLimit').val();
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.nc-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.nc-note').val()),
                    correct_answer: $.trim($row.find('.nc-answer').val()),
                    word_limit: wordLimit,
                    question_type: 'note_completion',
                    question_type_label: 'Note Completion'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#ncSelectedCount').text(questions.length);
        $('#ncValidCount').text(validCount);
        $('#ncTotalCount').text(total);
        $('#ncProgress').css('width', percent + '%');
        $('#ncBatchData').val(JSON.stringify(questions));
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
    $('#ncAddRow').off('click.nc').on('click.nc', addRow);
    $('#ncWordLimit').off('change.nc').on('change.nc', updateUI);
    
    $(document).off('change.nc', '.nc-checkbox').on('change.nc', '.nc-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#ncSelectAll').off('change.nc').on('change.nc', function() {
        var c = $(this).is(':checked');
        $('.nc-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.nc', '.nc-note, .nc-answer').on('input.nc', '.nc-note, .nc-answer', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.nc', '.btn-remove').on('click.nc', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#ncMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.nc').on('input.nc', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.nc').on('keydown.nc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/note_completion.blade.php ENDPATH**/ ?>