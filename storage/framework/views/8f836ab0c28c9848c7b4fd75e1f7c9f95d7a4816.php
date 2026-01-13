


<?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $text = $q->question_text ?? $q->content ?? '';
    ?>
    <div class="idp-question" data-q-num="<?php echo e($qNum); ?>">
        <?php if(!empty($text)): ?>
            <span class="idp-q-num"><?php echo e($qNum); ?></span>
            <span class="idp-q-text"><?php echo e($text); ?></span>
            <input type="text" class="idp-input" style="margin-left: 8px;"
                   value="<?php echo e($saved); ?>"
                   oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
        <?php else: ?>
            <span class="idp-q-num"><?php echo e($qNum); ?></span>
            <input type="text" class="idp-input idp-input-lg"
                   value="<?php echo e($saved); ?>"
                   oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_fill_blank.blade.php ENDPATH**/ ?>