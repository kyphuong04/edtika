<div class="<?php echo e($className); ?>">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <div class="size-48 rounded-circle bg-gray-100">
                <img src="<?php echo e($comment->user->getAvatar()); ?>" class="js-avatar-img img-cover rounded-circle" alt="<?php echo e($comment->user->full_name); ?>">
            </div>
            <div class="ml-8">
                <h6 class="font-14 font-weight-bold"><?php echo e($comment->user->full_name); ?></h6>
                <div class="mt-4 font-12 text-gray-500"><?php echo e($comment->user->role->caption); ?></div>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <span class="font-12 text-gray-500"><?php echo e(dateTimeFormat($comment->created_at, 'j M Y')); ?></span>

            <div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
                <button type="button" class="d-flex-center size-20 ml-8 btn-transparent">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </button>

                <div class="actions-dropdown__dropdown-menu dropdown-menu-top-32">
                    <ul class="my-8">

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" class="js-reply-comment" data-comment="<?php echo e($mainCommentId); ?>" data-item="<?php echo e($commentForItemId); ?>" data-item-name="<?php echo e($commentForItemName); ?>"><?php echo e(trans('panel.reply')); ?></button>
                        </li>

                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" class="js-report-comment" data-comment="<?php echo e($comment->id); ?>" data-item="<?php echo e($commentForItemId); ?>" data-item-name="<?php echo e($commentForItemName); ?>"><?php echo e(trans('panel.report')); ?></button>
                        </li>

                        <?php if(auth()->check() and auth()->user()->id == $comment->user_id): ?>
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/comments/<?php echo e($comment->id); ?>/delete" class="delete-action text-danger"><?php echo e(trans('public.delete')); ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 text-gray-500 font-14">
        <?php echo clean($comment->comment, 'comment'); ?>

    </div>

    <?php if(!empty($replies) and count($replies)): ?>
        <?php $__currentLoopData = $replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $replyRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('design_1.web.components.comments.comment', [
                'comment' => $replyRow,
                'replies' => null,
                'mainCommentId' => $mainCommentId,
                'commentForItemId' => $commentForItemId,
                'commentForItemName' => $commentForItemName,
                'className' => 'bg-gray-100 p-16 rounded-8 mt-16',
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/comments/comment.blade.php ENDPATH**/ ?>