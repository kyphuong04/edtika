


<?php
    $tableStructure = is_array($tableData) ? $tableData : json_decode($tableData ?? '[]', true);
?>

<?php if(!empty($tableStructure)): ?>
    <table class="idp-table">
        <?php if(!empty($tableStructure['headers'])): ?>
            <thead>
                <tr>
                    <?php $__currentLoopData = $tableStructure['headers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th><?php echo e($header); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
        <?php endif; ?>
        <tbody>
            <?php $__currentLoopData = $tableStructure['rows'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cell): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <td>
                            <?php if(isset($cell['question_id'])): ?>
                                <?php
                                    $q = $questions->firstWhere('id', $cell['question_id']);
                                    $qNum = $q->question_number ?? '';
                                    $saved = $userAnswers[$q->id] ?? '';
                                ?>
                                <span class="idp-q-num"><?php echo e($qNum); ?></span>
                                <input type="text" class="idp-input" value="<?php echo e($saved); ?>"
                                       oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
                            <?php else: ?>
                                <?php echo e($cell['text'] ?? $cell); ?>

                            <?php endif; ?>
                        </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php else: ?>
    
    <table class="idp-table">
        <tbody>
            <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $qNum = $q->question_number ?? $loop->iteration;
                    $saved = $userAnswers[$q->id] ?? '';
                    $text = $q->question_text ?? $q->content ?? '';
                ?>
                <tr data-q-num="<?php echo e($qNum); ?>">
                    <td>
                        <span class="idp-q-num"><?php echo e($qNum); ?></span>
                        <span class="idp-q-text"><?php echo e($text); ?></span>
                    </td>
                    <td>
                        <input type="text" class="idp-input" value="<?php echo e($saved); ?>"
                               oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_table.blade.php ENDPATH**/ ?>