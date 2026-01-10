
<?php echo $__env->make('design_1.panel.questions.types._form_styles', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="type-form-content" data-type-color="cyan">
    <h4>
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-text'); ?>
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
        Summary Completion
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
        <span><strong>Batch Mode:</strong> Thêm nhiều câu trong summary cần điền. Sử dụng <code>_____</code> để đánh dấu chỗ trống. <kbd>Alt+A</kbd> để thêm nhanh.</span>
    </div>
    
    
    <div class="row mb-16">
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Input Type</label>
                <select id="sumcInputType" class="form-control">
                    <option value="passage">Words from passage</option>
                    <option value="wordlist">Words from list</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-group mb-0">
                <label class="form-group-label">Word Limit</label>
                <select id="sumcWordLimit" class="form-control">
                    <option value="ONE WORD ONLY">ONE WORD ONLY</option>
                    <option value="NO MORE THAN TWO WORDS" selected>NO MORE THAN TWO WORDS</option>
                    <option value="NO MORE THAN THREE WORDS">NO MORE THAN THREE WORDS</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-6 d-flex align-items-end">
            <div class="batch-progress flex-fill ml-md-16 mt-8 mt-md-0">
                <div class="progress" style="height: 8px; border-radius: 4px;">
                    <div id="sumcProgress" class="progress-bar bg-info" style="width: 0%; transition: width 0.3s;"></div>
                </div>
                <small class="text-muted mt-4 d-block"><span id="sumcValidCount">0</span>/<span id="sumcTotalCount">0</span> câu hợp lệ</small>
            </div>
        </div>
    </div>
    
    <div id="sumcWordListBox" class="form-group mb-16" style="display: none;">
        <label class="form-group-label">Word List Options (comma-separated)</label>
        <textarea id="sumcWordList" class="form-control" rows="2" placeholder="word1, word2, word3" maxlength="500"></textarea>
    </div>
    
    
    <div class="batch-header">
        <div style="width: 40px; text-align: center;">
            <input type="checkbox" id="sumcSelectAll" class="batch-check" title="Chọn tất cả" checked>
        </div>
        <div style="width: 50px; text-align: center;">Q.</div>
        <div class="flex-fill">Câu trong summary với blank <code>_____</code> <span class="text-danger">*</span></div>
        <div style="width: 150px; text-align: center;">Đáp án <span class="text-danger">*</span></div>
        <div style="width: 40px;"></div>
    </div>
    
    <div id="sumcRows"></div>
    
    
    <div id="sumcMaxWarning" class="max-rows-warning" style="display: none;">
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
        <button type="button" class="btn btn-add-row" id="sumcAddRow" style="--type-color: #17a2b8;">
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
<?php endif; ?>Thêm câu
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
            Hợp lệ: <strong id="sumcSelectedCount" class="text-info">0</strong> câu hỏi
        </div>
    </div>
    
    <input type="hidden" name="batch_mode" value="1">
    <input type="hidden" name="batch_questions" id="sumcBatchData" value="[]">
</div>

<style>
.info-box.info { background: rgba(23, 162, 184, 0.08); border-left-color: #17a2b8; }
</style>

<script>
setTimeout(function() {
    var rowCount = 0;
    var maxRows = 12;
    var startQNum = parseInt($('#question_number').val()) || 1;
    
    function validateRow($row) {
        var sentence = $.trim($row.find('.sumc-sentence').val());
        var answer = $.trim($row.find('.sumc-answer').val());
        var hasBlank = sentence.indexOf('_____') !== -1 || sentence.indexOf('____') !== -1 || sentence.indexOf('___') !== -1;
        var isValid = sentence.length >= 10 && hasBlank && answer.length >= 1;
        
        $row.find('.sumc-sentence').toggleClass('is-invalid', sentence.length > 0 && (!hasBlank || sentence.length < 10))
            .toggleClass('is-valid', sentence.length >= 10 && hasBlank);
        $row.find('.sumc-answer').toggleClass('is-invalid', answer.length === 0 && sentence.length > 0)
            .toggleClass('is-valid', answer.length >= 1);
        
        $row.toggleClass('has-error', !isValid && (sentence.length > 0 || answer.length > 0));
        return isValid;
    }
    
    function addRow() {
        if (rowCount >= maxRows) {
            $('#sumcMaxWarning').slideDown(200);
            return;
        }
        var qNum = startQNum + rowCount;
        rowCount++;
        
        var html = '<div class="batch-row selected" data-qnum="'+qNum+'" style="animation: slideIn 0.2s ease-out;">' +
            '<div style="width: 40px; text-align: center;"><input type="checkbox" class="batch-check sumc-checkbox" checked></div>' +
            '<div style="width: 50px; text-align: center;"><span class="q-badge" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">Q'+qNum+'</span></div>' +
            '<div class="flex-fill mx-12"><input type="text" class="form-control sumc-sentence" placeholder="Ví dụ: The city was known for its _____ architecture." maxlength="500"></div>' +
            '<div style="width: 150px;"><input type="text" class="form-control sumc-answer" placeholder="Đáp án" maxlength="100"></div>' +
            '<button type="button" class="btn btn-remove ml-8"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></button>' +
        '</div>';
        
        $('#sumcRows').append(html);
        updateUI();
        
        if (rowCount >= maxRows) {
            $('#sumcMaxWarning').slideDown(200);
        }
        
        $('#sumcRows .batch-row:last .sumc-sentence').focus();
    }
    
    function updateUI() {
        var questions = [];
        var validCount = 0;
        var wordLimit = $('#sumcWordLimit').val();
        var inputType = $('#sumcInputType').val();
        var wordList = $('#sumcWordList').val();
        
        $('.batch-row').each(function() {
            var $row = $(this);
            var isValid = validateRow($row);
            
            if ($row.find('.sumc-checkbox').is(':checked') && isValid) {
                validCount++;
                questions.push({
                    question_number: $row.data('qnum'),
                    question_text: $.trim($row.find('.sumc-sentence').val()),
                    correct_answer: $.trim($row.find('.sumc-answer').val()),
                    word_limit: wordLimit,
                    input_type: inputType,
                    word_list: inputType === 'wordlist' ? wordList : null,
                    question_type: 'summary_completion',
                    question_type_label: 'Summary Completion'
                });
            }
        });
        
        var total = $('.batch-row').length;
        var percent = total > 0 ? Math.round((validCount / total) * 100) : 0;
        
        $('#sumcSelectedCount').text(questions.length);
        $('#sumcValidCount').text(validCount);
        $('#sumcTotalCount').text(total);
        $('#sumcProgress').css('width', percent + '%');
        $('#sumcBatchData').val(JSON.stringify(questions));
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
    $('#sumcAddRow').off('click.sumc').on('click.sumc', addRow);
    
    $('#sumcInputType').off('change.sumc').on('change.sumc', function() {
        $('#sumcWordListBox').toggle($(this).val() === 'wordlist');
        updateUI();
    });
    
    $('#sumcWordLimit, #sumcWordList').off('change.sumc input.sumc').on('change.sumc input.sumc', updateUI);
    
    $(document).off('change.sumc', '.sumc-checkbox').on('change.sumc', '.sumc-checkbox', function() {
        $(this).closest('.batch-row').toggleClass('selected', $(this).is(':checked'));
        updateUI();
    });
    
    $('#sumcSelectAll').off('change.sumc').on('change.sumc', function() {
        var c = $(this).is(':checked');
        $('.sumc-checkbox').prop('checked', c);
        $('.batch-row').toggleClass('selected', c);
        updateUI();
    });
    
    $(document).off('input.sumc', '.sumc-sentence, .sumc-answer').on('input.sumc', '.sumc-sentence, .sumc-answer', function() {
        validateRow($(this).closest('.batch-row'));
        updateUI();
    });
    
    $(document).off('click.sumc', '.btn-remove').on('click.sumc', '.btn-remove', function(e) {
        e.stopPropagation();
        $(this).closest('.batch-row').fadeOut(200, function() {
            $(this).remove();
            rowCount--;
            updateQNumbers();
            if (rowCount < maxRows) {
                $('#sumcMaxWarning').slideUp(200);
            }
        });
    });
    
    $('#question_number').off('input.sumc').on('input.sumc', updateQNumbers);
    
    // Keyboard shortcut
    $(document).off('keydown.sumc').on('keydown.sumc', function(e) {
        if (e.altKey && e.key === 'a') {
            e.preventDefault();
            addRow();
        }
    });
}, 100);
</script>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/questions/types/summary_completion.blade.php ENDPATH**/ ?>