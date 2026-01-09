
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="red">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-map'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger mr-8','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        Map/Plan Labeling
        <span class="badge badge-danger ml-8">Batch Mode</span>
    </h4>
    
    <div class="info-box danger">
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
        <span><strong>Batch Mode:</strong> Upload 1 map/plan, thêm nhiều locations cần điền. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-6">
            <div class="form-group mb-0">
                <label class="form-group-label is-required">Map/Plan Image <span class="text-danger">*</span></label>
                <input type="file" name="diagram_image" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Input Type</label>
                <select id="mlInputType" class="form-control">
                    <option value="text">Free text (from listening)</option>
                    <option value="options">Select from options</option>
                </select>
            </div>
        </div>
    </div>
    
    <div id="mlOptionsBox" class="form-group mb-16" style="display: none;">
        <label class="form-group-label">Location Options (comma-separated)</label>
        <textarea id="mlOptions" class="form-control" rows="2" placeholder="Library, Cafeteria, Main Hall" maxlength="500"></textarea>
        <small class="text-muted">Các options sẽ được hiển thị cho học sinh chọn</small>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mlProgress" class="progress-bar bg-danger" style="width: 0%; transition: width 0.3s;"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mlValidCount">0</span>/<span id="mlTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mlSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 80px;">Ref</div>
        <div class="flex-fill">Mô tả location</div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="mlRows"></div>
    
    
    <div id="mlMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 15 locations
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="mlAddRow">
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
<?php endif; ?>Thêm location
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
<?php $component->withAttributes(['class' => 'icons text-danger mr-4','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="mlSelectedCount" class="text-danger">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="mlBatchData" value="[]">
</div>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 15;
    var startQNum = parseInt($('#question_number').val()) || 1;
    var L = 'ABCDEFGHIJKLMNO';
    
    function validateRow($row) {
        var answer = $.trim($row.find('.ml-answer').val());
        var isValid = answer.length >= 1;
        
        $row.find('.ml-answer').toggleClass('is-invalid', !isValid && $row.find('.ml-answer').val().length > 0)
            .toggleClass('is-valid', isValid);
        
        $row.toggleClass('has-error', !isValid);
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mlMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        var ref = L[rowCount] || (rowCount + 1);
        rowCount++;
        
        var html = '<div class="batch-row ml-row d-flex align-items-center mb-8 p-8 selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check ml-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge">Q'+qNum+'</span></div>' +
            '<div style="width: 80px; padding-right: 8px;"><input type="text" class="form-control form-control-sm ml-ref" value="'+ref+'" maxlength="10"></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control ml-desc" placeholder="Mô tả vị trí trên bản đồ" maxlength="200"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control ml-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#mlRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mlMaxWarning').slideDown(200);
        }
        
        $('#mlRows .batch-row:last .ml-answer').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var inputType = $('#mlInputType').val();
        var options = $.trim($('#mlOptions').val());
        
        $('.ml-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.ml-checkbox').is(':checked') && isValid) {
                validCount++;
                var ref = $.trim($row.find('.ml-ref').val());
                var desc = $.trim($row.find('.ml-desc').val());
                var answer = $.trim($row.find('.ml-answer').val());
                
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: desc || 'Location ' + ref,
                    correct_answer: answer,
                    location_ref: ref,
                    input_type: inputType,
                    options: inputType === 'options' ? options : null,
                    question_type: 'map_labeling',
                    question_type_label: 'Map/Plan Labeling'
                });
            }
        });
        
        var total = $('.ml-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#mlSelectedCount').text(questions.length);
        $('#mlValidCount').text(validCount);
        $('#mlTotalCount').text(total);
        $('#mlProgress').css('width', percent + '%');
        $('#mlBatchData').val(JSON.stringify(questions));
    }
    
    function updateQNumbers() {
        startQNum = parseInt($('#question_number').val()) || 1;
        $('.ml-row').each(function(idx) {
            $(this).data('qnum', startQNum + idx);
            $(this).find('.q-badge').text('Q' + (startQNum + idx));
        });
        updateUI();
    }
    
    // Init with 4 rows
    for (var i = 0; i < 4; i++) addRow();
    
    // Event handlers
    $('#mlAddRow').off('click.ml').on('click.ml', addRow);
    
    $('#mlInputType').off('change.ml').on('change.ml', function() {
        $('#mlOptionsBox').slideToggle(200, function() {
            $(this).toggle($(this).closest('.type-form-content').find('#mlInputType').val() === 'options');
        });
        $('#mlOptionsBox').toggle($(this).val() === 'options');
        updateUI();
    });
    
    $('#mlOptions').off('input.ml').on('input.ml', updateUI);
    
    $(document).off('change.ml', '.ml-checkbox').on('change.ml', '.ml-checkbox', function() {
        $(this).closest('.ml-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mlSelectAll').off('change.ml').on('change.ml', function() {
        var c = $(this).is(':checked');
        $('.ml-checkbox').prop('checked', c);
        $('.ml-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.ml2', '.ml-ref, .ml-desc, .ml-answer').on('input.ml2', '.ml-ref, .ml-desc, .ml-answer', function() {
        validateRow($(this).closest('.ml-row'));
        updateUI();
    });
    
    $(document).off('click.ml2', '.btn-remove').on('click.ml2', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.ml-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mlMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.ml').on('input.ml', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.ml').on('keydown.ml', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/map_labeling.blade.php ENDPATH**/ ?>