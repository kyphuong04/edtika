


<?php if(!empty($title)): ?>
    <div class="idp-note-title"><?php echo e($title); ?></div>
<?php endif; ?>

<ul class="idp-note-bullet" style="list-style: none; padding: 0;">
    <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $qNum = $q->question_number ?? $loop->iteration;
            $saved = $userAnswers[$q->id] ?? '';
            $text = $q->question_text ?? $q->content ?? '';
            
            // Detect if it's a section header (no blank)
            $isHeader = !preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text) && empty($text);
        ?>
        
        <?php if($isHeader && !empty($q->section_title)): ?>
            <li style="font-weight: bold; margin-top: 12px; margin-bottom: 6px;">
                <?php echo e($q->section_title); ?>

            </li>
        <?php else: ?>
            <li class="idp-question" data-q-num="<?php echo e($qNum); ?>" style="margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                <span style="margin-right: 4px;">•</span>
                <?php if(preg_match('/_{2,}|\[\s*\d*\s*\]|____/', $text)): ?>
                    <span>
                        <?php echo preg_replace_callback(
                            '/_{2,}|\[\s*\d*\s*\]|____/',
                            function($m) use ($q, $qNum, $saved) {
                                return '<span class="idp-q-num">' . $qNum . '</span> ' .
                                       '<input type="text" class="idp-input" value="' . e($saved) . '" 
                                               oninput="autoSave(' . $q->id . ', this.value)">';
                            },
                            e($text),
                            1
                        ); ?>

                    </span>
                <?php else: ?>
                    <span class="idp-q-text"><?php echo e($text); ?></span>
                    <span class="idp-q-num"><?php echo e($qNum); ?></span>
                    <input type="text" class="idp-input" value="<?php echo e($saved); ?>" 
                           oninput="autoSave(<?php echo e($q->id); ?>, this.value)">
                <?php endif; ?>
            </li>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests/partials/idp_type_note.blade.php ENDPATH**/ ?>