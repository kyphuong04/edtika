


<?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';
        
        // Check if text has blank placeholder
        $hasBlank = preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text);
    ?>
    <div class="idp-question" data-q-num="<?php echo e($qNum); ?>">
        <?php if($hasBlank): ?>
            
            <span class="idp-q-num"><?php echo e($qNum); ?></span>
            <span class="idp-q-text">
                <?php echo preg_replace(
                    '/_{2,}|\[\s*\d*\s*\]|____/',
                    '<input type="text" class="idp-input" value="' . e($saved) . '" 
                            data-qid="' . $q->id . '" 
                            oninput="autoSave(' . $q->id . ', this.value)">',
                    e($text),
                    1
                ); ?>

            </span>
        <?php else: ?>
            
            <div style="margin-bottom: 6px;">
                <span class="idp-q-num"><?php echo e($qNum); ?></span>
                <span class="idp-q-text"><?php echo e($text); ?></span>
            </div>
            <div style="margin-left: 28px;">
                <input type="text" class="idp-input idp-input-lg" 
                       value="<?php echo e($saved); ?>"
                       data-qid="<?php echo e($q->id); ?>"
                       oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_sentence.blade.php ENDPATH**/ ?>