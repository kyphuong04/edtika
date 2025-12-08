<?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reviewRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="js-all-reviews-card">
        <?php echo $__env->make('design_1.web.components.reviews.review', [
            'comment' => $reviewRow,
            'commentCreator' => $reviewRow->creator,
            'replies' => $reviewRow->comments,
            'description' => $reviewRow->description,
            'replyItemId' => $reviewRow->id,
            'showRate' => true,
            'showReplyForm' => true,
            'deleteUrlPrefix' => "/reviews",
            'className' => 'bg-white p-16 rounded-12 border-gray-200 '. ($loop->first ? 'mt-24' : 'mt-16')
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="js-review-reply-form">

        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/reviews/all_cards.blade.php ENDPATH**/ ?>