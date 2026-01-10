
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="purple">
    <h4>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-8 text-purple"><path d="M4 12h8"/><path d="M4 18h12"/><path d="M4 6h16"/></svg>
        Matching Headings
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
        <span><strong>Cách dùng:</strong> Nhập nội dung heading và chọn đoạn văn phù hợp. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="batch-progress mb-16">
        <div class="progress" style="height: 8px; border-radius: 4px;">
            <div id="mhProgress" class="progress-bar" style="width: 0%; transition: width 0.3s; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
        </div>
        <small class="text-muted mt-4 d-block"><span id="mhValidCount">0</span>/<span id="mhTotalCount">0</span> câu hợp lệ</small>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="mhSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div style="width: 50px; text-align: center;">No.</div>
        <div class="flex-fill ml-8">Nội dung Heading <span class="text-danger">*</span></div>
        <div style="width: 120px; text-align: center;">Đoạn đúng <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    
    <div id="mhHeadingRows"></div>
    
    
    <div id="mhMaxWarning" class="max-rows-warning" style="display: none;">
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
<?php endif; ?> Đã đạt giới hạn tối đa 10 headings
    </div>
    
    
    <div class="batch-footer">
        <button type="button" class="btn btn-add-row" id="mhAddRow" style="--type-color: #667eea;">
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
<?php endif; ?>Thêm heading
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
<?php $component->withAttributes(['class' => 'icons mr-4','style' => 'color: #667eea;','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            Hợp lệ: <strong id="selectedCount" style="color: #667eea;">0</strong> câu hỏi
        </div>
    </div>
    
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="batchQuestionsData" value="[]">
</div>

<style>
.badge-purple { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
.text-purple { color: #667eea !important; }
.info-box.purple { background: rgba(102, 126, 234, 0.08); border-left-color: #667eea; }
.mh-heading-label { 
    width: 40px; height: 40px; 
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 6px;
}
.mh-para-select {
    font-weight: 600;
    font-size: 15px;
    text-align: center;
}
</style>

<script>
setTimeout(function() {
    var headings = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x'];
    var paragraphs = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    var rowCount = 0;
    var maxRows = 10;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function buildParagraphDropdown(selectedPara) {
        var html = '<option value="">-- Chọn --</option>';
        paragraphs.forEach(function(p) {
            var sel = (p === selectedPara) ? 'selected' : '';
            html += '<option value="'+p+'" '+sel+'>Đoạn '+p+'</option>';
        });
        return html;
    }
    
    function validateRow($row) {
        var headingText = $.trim($row.find('.mh-heading-input').val());
        var para = $row.find('.mh-para-select').val();
        var isValid = headingText.length >= 3 && para !== '';
        
        $row.find('.mh-heading-input').toggleClass('is-invalid', headingText.length > 0 && headingText.length < 3)
            .toggleClass('is-valid', headingText.length >= 3);
        $row.find('.mh-para-select').toggleClass('is-invalid', para === '' && headingText.length > 0)
            .toggleClass('is-valid', para !== '');
        
        $row.toggleClass('has-error', !isValid && (headingText.length > 0 || para !== ''));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#mhMaxWarning').slideDown(200);
            return;
        }
        var h = headings[rowCount];
        var qNum = startQNum + rowCount;
        var defaultPara = paragraphs[rowCount] || '';
        rowCount++;
        
        var html = '<div class="batch-row selected" data-heading="'+h+'" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check mh-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">Q'+qNum+'</span></div>' +
            '<div style="width: 50px; text-align: center;"><span class="mh-heading-label">'+h+'</span></div>' +
            '<div class="flex-fill mx-8"><input type="text" class="form-control mh-heading-input" placeholder="Nhập nội dung heading '+h+'..." maxlength="300"></div>' +
            '<div style="width: 120px;"><select class="form-control mh-para-select">'+buildParagraphDropdown(defaultPara)+'</select></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#mhHeadingRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#mhMaxWarning').slideDown(200);
        }
        
        $('#mhHeadingRows .batch-row:last .mh-heading-input').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.mh-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    paragraph: $row.find('.mh-para-select').val(),
                    heading: $row.data('heading'),
                    heading_text: $.trim($row.find('.mh-heading-input').val()),
                    question_type: 'matching_headings',
                    question_type_label: 'Matching Headings'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#selectedCount').text(questions.length);
        $('#mhValidCount').text(validCount);
        $('#mhTotalCount').text(total);
        $('#mhProgress').css('width', percent + '%');
        $('#batchQuestionsData').val(JSON.stringify(questions));
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
    
    // Init with 6 rows
    for (var i = 0; i < 6; i++) {
        addRow();
    }
    
    // Event handlers
    $('#mhAddRow').off('click.mh').on('click.mh', addRow);
    
    $(document).off('change.mh', '.mh-checkbox').on('change.mh', '.mh-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#mhSelectAll').off('change.mh').on('change.mh', function() {
        var checked = $(this).is(':checked');
        $('.mh-checkbox').prop('checked', checked);
        $('.batch-row').toggleClass('selected', checked);
        updateUI();
    });
    
    $(document).off('change.mh', '.mh-para-select').on('change.mh', '.mh-para-select', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.mh', '.btn-remove').on('click.mh', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#mhMaxWarning').slideUp(200);
            }
        });
    });
    
    $(document).off('input.mh', '.mh-heading-input').on('input.mh', '.mh-heading-input', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $('#question_number').off('input.mh').on('input.mh', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.mh').on('keydown.mh', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
    
}, 100);
</script>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/matching_headings.blade.php ENDPATH**/ ?>