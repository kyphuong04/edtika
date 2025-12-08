<div class="learning-page__main-content" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>
    <div id="mainContent" class="w-100 h-100">
        <?php if(!empty($isForumPage)): ?>
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.forum.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(!empty($isForumAnswersPage)): ?>
            <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.forum.answers', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/main_content.blade.php ENDPATH**/ ?>