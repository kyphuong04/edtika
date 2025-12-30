
<div class="bg-white p-16 rounded-24">

    
    <?php echo $__env->make('design_1.web.components.comments.submit_form', [
        'commentForItemId' => $course->id,
        'commentForItemName' => "webinar_id",
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(!empty($courseComments) and count($courseComments['comments'])): ?>
        <div class="js-course-comments-container">
            <?php echo $__env->make('design_1.web.components.comments.all_cards', [
                'comments' => $courseComments['comments'],
                'commentForItemId' => $course->id,
                'commentForItemName' => "webinar_id",
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <?php if(!empty($courseComments['has_more'])): ?>
            <div class="d-flex-center mt-16">
                <button type="button" class="js-comments-load-more-btn d-flex-center py-16 px-24 rounded-12 border-dashed border-gray-300 text-gray-500 bg-white bg-hover-gray-100 cursor-pointer" data-path="/comments/lists/webinar/<?php echo e($course->id); ?>">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-rotate-left'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4"><?php echo e(trans('update.load_more')); ?></span>
                </button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

</div>

<div class="js-reply-to-comment-html d-none">
    <?php echo $__env->make('design_1.web.components.comments.reply_form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/tabs/comments.blade.php ENDPATH**/ ?>