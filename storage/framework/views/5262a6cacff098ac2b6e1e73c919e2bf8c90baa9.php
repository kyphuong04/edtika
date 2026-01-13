<div class="bg-white p-16 rounded-24">
    <div class="learning-page__file-player-card mb-16 bg-gray-100">
        <img src="<?php echo e($textLesson->image); ?>" alt="" class="img-cover">
    </div>

    
    <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $textLesson,
        'itemType' => 'text_lesson',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/text_lesson.blade.php ENDPATH**/ ?>