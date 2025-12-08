<?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="js-comment-card">
        <?php echo $__env->make('design_1.web.components.comments.comment', [
            'comment' => $commentRow,
            'replies' => $commentRow->replies,
            'mainCommentId' => $commentRow->id,
            'commentForItemId' => $commentForItemId,
            'commentForItemName' => $commentForItemName,
            'className' => 'bg-white p-16 rounded-12 border-gray-200 '. ($loop->first ? 'mt-24' : 'mt-16')
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="js-comment-reply-form">

        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/comments/all_cards.blade.php ENDPATH**/ ?>