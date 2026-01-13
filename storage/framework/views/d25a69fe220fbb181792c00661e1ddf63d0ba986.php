
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="green">
    <h4>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8 text-success"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Matching Features
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
        <span><strong>Batch Mode:</strong> Thêm danh sách features trước, sau đó thêm các statements cần match. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="form-group mb-16">
        <label class="form-group-label is-required">Danh sách Features (người/lý thuyết/địa điểm)</label>
        <small class="text-muted d-block mb-8">Các options mà học sinh sẽ chọn. Mỗi feature có thể được dùng nhiều lần.</small>
        <div id="mfFeatures" class="dynamic-inputs"></div>
        <button type="button" class="btn btn-sm btn-outline-success mt-8" id="mfAddFeature">
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
<?php endif; ?>Thêm Feature
        </button>
    </div>
    
    <hr class="my-16">
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mfProgress" class="progress-bar bg-success" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mfValidCount">0</span>/<span id="mfTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mfSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Statement cần match <span class="text-danger">*</span></div>
        <div style="width: 100px; text-align: center;">Feature <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="mfRows"></div>
    
    
    <div id="mfMaxWarning" class="max-rows-warning" style="display: none;">
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
        <button type="button" class="btn btn-add-row" id="mfAddRow">
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
<?php $component->withAttributes(['class' => 'icons text-success mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="mfSelectedCount" class="text-success">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="mfBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var L = 'ABCDEFGHIJ', featureCount = 0, rowCount = 0, maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function addFeature() {
        if (featureCount >= 10) return;
        var l = L[featureCount++];
        $('#mfFeatures').append('<div class="input-group mb-8" style="animation: slideIn 0.2s ease-out;"><span class="input-group-text" style="min-width: 36px; justify-content: center; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: #fff; border: none;">'+l+'</span><input type="text" class="form-control mf-feature-input" data-letter="'+l+'" placeholder="Ví dụ: Dr. Sarah Johnson / The Green Theory..." maxlength="200"><button type="button" class="btn btn-outline-danger mf-remove-feature" style="border-radius: 0 6px 6px 0;"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button></div>');
        updateSelects();
    }
    
    function updateSelects() {
        var opts = '<option value="">--</option>';
        $('#mfFeatures .mf-feature-input').each(function() {
            var l = $(this).data('letter');
            var v = $(this).val() || l;
            opts += '<option value="'+l+'">'+l+' - '+v.substring(0,25)+'</option>';
        });
        $('.mf-answer').each(function() {
            var current = $(this).val();
            $(this).html(opts).val(current);
        });
    }
    
    function validateRow($row) {
        var statement = $.trim($row.find('.mf-statement').val());
        var answer = $row.find('.mf-answer').val();
        var isValid = statement.length >= 5 && answer !== '';
        
        $row.find('.mf-statement').toggleClass('is-invalid', statement.length > 0 && statement.length < 5)
            .toggleClass('is-valid', statement.length >= 5);
        $row.find('.mf-answer').toggleClass('is-invalid', answer === '' && statement.length > 0)
            .toggleClass('is-valid', answer !== '');
        
        $row.toggleClass('has-error', !isValid && (statement.length > 0 || answer !== ''));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mfMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check mf-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control mf-statement" placeholder="Statement cần match với feature" maxlength="500"></div>' +
            '<div style="width: 100px;"><select class="form-control mf-answer"></select></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#mfRows').append(html);
        updateSelects();
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mfMaxWarning').slideDown(200);
        }
        
        $('#mfRows .batch-row:last .mf-statement').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var features = {};
        
        $('#mfFeatures .mf-feature-input').each(function() {
            features[$(this).data('letter')] = $(this).val();
        });
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.mf-checkbox').is(':checked') && isValid) {
                validCount++;
                var q = {
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.mf-statement').val()),
                    correct_answer: $row.find('.mf-answer').val(),
                    question_type: 'matching_features',
                    question_type_label: 'Matching Features'
                };
                for (var k in features) q['options['+k+']'] = features[k];
                questions.push(q);
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#mfSelectedCount').text(questions.length);
        $('#mfValidCount').text(validCount);
        $('#mfTotalCount').text(total);
        $('#mfProgress').css('width', percent + '%');
        $('#mfBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.batch-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init 3 features and 4 rows
    addFeature(); addFeature(); addFeature();
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#mfAddFeature').off('click.mf').on('click.mf', addFeature);
    $('#mfAddRow').off('click.mf').on('click.mf', addRow);
    
    $(document).off('click.mf', '.mf-remove-feature').on('click.mf', '.mf-remove-feature', function() {
        featureCount--;
        $(this).closest('.input-group').remove();
        updateSelects();
        updateUI();
    });
    
    $(document).off('input.mf', '.mf-feature-input').on('input.mf', '.mf-feature-input', function() {
        updateSelects();
        updateUI();
    });
    
    $(document).off('change.mf', '.mf-checkbox').on('change.mf', '.mf-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mfSelectAll').off('change.mf').on('change.mf', function() {
        var c = $(this).is(':checked');
        $('.mf-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.mf2', '.mf-statement').on('input.mf2', '.mf-statement', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('change.mf2', '.mf-answer').on('change.mf2', '.mf-answer', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.mf3', '.btn-remove').on('click.mf3', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mfMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.mf').on('input.mf', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mf').on('keydown.mf', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/matching_features.blade.php ENDPATH**/ ?>