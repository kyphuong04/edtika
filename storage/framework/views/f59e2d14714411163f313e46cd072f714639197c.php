<div class="bg-white p-16 rounded-24">
    <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 mb-20 px-16">
        
        <?php if($quiz->not_participated): ?>
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.quiz.not_participated', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(!empty($quiz->result)): ?>
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.quiz.result_status', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

    </div>

    
    <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $quiz,
        'itemType' => 'quiz',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/quiz.blade.php ENDPATH**/ ?>