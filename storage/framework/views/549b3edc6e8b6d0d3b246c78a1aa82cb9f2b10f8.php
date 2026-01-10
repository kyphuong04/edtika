


<?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $qNum = $q->question_number ?? $loop->iteration;
        $saved = $userAnswers[$q->id] ?? '';
        $options = is_array($q->options) ? $q->options : json_decode($q->options ?? '[]', true);
        if(empty($options) && !empty($q->option_a)) {
            $options = [];
            if($q->option_a) $options['A'] = $q->option_a;
            if($q->option_b) $options['B'] = $q->option_b;
            if($q->option_c) $options['C'] = $q->option_c;
            if($q->option_d) $options['D'] = $q->option_d;
        }
    ?>
    <div class="idp-question" data-q-num="<?php echo e($qNum); ?>">
        <div style="margin-bottom: 8px;">
            <span class="idp-q-num"><?php echo e($qNum); ?></span>
            <span class="idp-q-text"><?php echo e($q->question_text ?? $q->content ?? ''); ?></span>
        </div>
        <div class="idp-options">
            <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="idp-option">
                    <input type="radio" name="q_<?php echo e($q->id); ?>" value="<?php echo e($key); ?>"
                           <?php echo e($saved === $key ? 'checked' : ''); ?>

                           onchange="saveAnswer(<?php echo e($q->id); ?>, '<?php echo e($key); ?>')">
                    <span><strong><?php echo e($key); ?></strong> <?php echo e($text); ?></span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_mcq.blade.php ENDPATH**/ ?>