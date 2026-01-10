


<?php
    $matchOptions = is_array($matchingOptions) ? $matchingOptions : json_decode($matchingOptions ?? '[]', true);
    $optionKeys = array_keys($matchOptions);
    if(empty($optionKeys)) {
        $optionKeys = ['A', 'B', 'C', 'D', 'E'];
    }
?>


<?php if(!empty($matchOptions)): ?>
    <div style="margin-bottom: 12px; font-size: 13px;">
        <?php $__currentLoopData = $matchOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div><strong><?php echo e($key); ?></strong> &nbsp; <?php echo e($text); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>

<table class="idp-match-table">
    <thead>
        <tr>
            <th style="width: auto;"></th>
            <?php $__currentLoopData = $optionKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th><?php echo e($opt); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $qNum = $q->question_number ?? $loop->iteration;
                $saved = $userAnswers[$q->id] ?? '';
            ?>
            <tr data-q-num="<?php echo e($qNum); ?>">
                <td>
                    <span class="idp-q-num"><?php echo e($qNum); ?></span>
                    <span class="idp-q-text"><?php echo e($q->question_text ?? $q->content ?? ''); ?></span>
                </td>
                <?php $__currentLoopData = $optionKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <input type="radio" name="q_<?php echo e($q->id); ?>" value="<?php echo e($opt); ?>"
                               <?php echo e($saved === $opt ? 'checked' : ''); ?>

                               onchange="saveAnswer(<?php echo e($q->id); ?>, '<?php echo e($opt); ?>')">
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_matching.blade.php ENDPATH**/ ?>