
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="purple">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-tick-square'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-purple mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Yes / No / Not Given
        <span class="badge badge-purple ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box purple">
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
        <span><strong>Batch Mode:</strong> Thêm nhiều statements cùng lúc. Tick ✓ để chọn câu muốn tạo. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    <div class="info-box warning mb-16">
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
        <span>Tests if statement <strong>agrees</strong> with writer's views/claims (not facts from passage)</span>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="ynngProgress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%);"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="ynngValidCount">0</span>/<span id="ynngTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="ynngSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Statement <span class="text-danger">*</span></div>
        <div style="width: 140px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    
    <div id="ynngRows"></div>
    
    
    <div id="ynngMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 15 câu hỏi
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="ynngAddRow" style="--type-color: #7367f0;">
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
<?php endif; ?>Thêm statement
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
<?php $component->withAttributes(['class' => 'icons mr-4','style' => 'color: #7367f0;','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="ynngSelectedCount" style="color: #7367f0;">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="ynngBatchData" value="[]">
</div>

<style>
.badge-purple { background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%); color: #fff; }
.text-purple { color: #7367f0 !important; }
.info-box.purple { background: rgba(115, 103, 240, 0.1); border-left-color: #7367f0; }
.ynng-answer-select option[value="yes"] { color: #28a745; font-weight: 600; }
.ynng-answer-select option[value="no"] { color: #dc3545; font-weight: 600; }
.ynng-answer-select option[value="not_given"] { color: #6c757d; font-weight: 600; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 15;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var statement = $.trim($row.find('.ynng-statement').val());
        var answer = $row.find('.ynng-answer-select').val();
        var isValid = statement.length >= 5 && answer !== '';
        
        $row.find('.ynng-statement').toggleClass('is-invalid', statement.length > 0 && statement.length < 5)
            .toggleClass('is-valid', statement.length >= 5);
        $row.find('.ynng-answer-select').toggleClass('is-invalid', answer === '' && statement.length > 0)
            .toggleClass('is-valid', answer !== '');
        
        $row.toggleClass('has-error', !isValid && (statement.length > 0 || answer !== ''));
        return isValid;
    }
    
    function addRow(statement, answer) {
        if (rowCount >= maxRows) {
            $('#ynngMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check ynng-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #7367f0 0%, #9e95f5 100%);">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control ynng-statement" placeholder="Nhập statement về ý kiến/quan điểm của tác giả..." value="'+(statement || '')+'" maxlength="500"></div>' +
            '<div style="width: 140px;">' +
                '<select class="form-control ynng-answer-select">' +
                    '<option value="">-- Chọn --</option>' +
                    '<option value="yes"'+(answer === 'yes' ? ' selected' : '')+'>✓ YES</option>' +
                    '<option value="no"'+(answer === 'no' ? ' selected' : '')+'>✗ NO</option>' +
                    '<option value="not_given"'+(answer === 'not_given' ? ' selected' : '')+'>? NOT GIVEN</option>' +
                '</select>' +
            '</div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>' +
        '</div>';
        
        $('#ynngRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#ynngMaxWarning').slideDown(200);
        }
        
        $('#ynngRows .batch-row:last .ynng-statement').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.ynng-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.ynng-statement').val()),
                    correct_answer: $row.find('.ynng-answer-select').val(),
                    question_type: 'yes_no_not_given',
                    question_type_label: 'Yes / No / Not Given'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#ynngSelectedCount').text(questions.length);
        $('#ynngValidCount').text(validCount);
        $('#ynngTotalCount').text(total);
        $('#ynngProgress').css('width', percent + '%');
        $('#ynngBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.batch-row').each(function(idx) {
            var qNum = startQNum + idx;
            $(this).data('qnum', qNum);
            $(this).find('.q-badge').text('Q' + qNum);
        });
        updateUI();
    }
    
    // Initialize with 4 rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#ynngAddRow').off('click.ynng').on('click.ynng', function() { addRow(); });
    
    $(document).off('change.ynng', '.ynng-checkbox').on('change.ynng', '.ynng-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#ynngSelectAll').off('change.ynng').on('change.ynng', function() {
        var checked = $(this).is(':checked');
        $('.ynng-checkbox').prop('checked', checked);
        $('.batch-row').toggleClass('selected', checked);
        updateUI();
    });
    
    $(document).off('input.ynng', '.ynng-statement').on('input.ynng', '.ynng-statement', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('change.ynng2', '.ynng-answer-select').on('change.ynng2', '.ynng-answer-select', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.ynng', '.btn-remove').on('click.ynng', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#ynngMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.ynng').on('input.ynng', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.ynng').on('keydown.ynng', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/yes_no_not_given.blade.php ENDPATH**/ ?>