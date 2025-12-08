<?php if(empty($authUser)): ?>
    <div class="d-flex-center flex-column text-center rounded-12 border-gray-200 border-dashed bg-white p-32 pb-40">
        <div class="d-flex-center size-56 rounded-12 bg-primary-20">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-messages-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <h5 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.leave_a_comment')); ?></h5>
        <a href="/login" class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.please_login_to_leave_comments')); ?></a>
    </div>
<?php else: ?>
    <div class="position-relative comment-have-a-question-card">
        <div class="comment-have-a-question-card__mask"></div>
        <div class="position-relative d-flex align-items-center p-16 rounded-12 bg-gray-100 border-gray-200 z-index-2">
            <div class="size-40 rounded-circle">
                <img src="<?php echo e($authUser->getAvatar(40)); ?>" alt="" class="img-cover rounded-circle">
            </div>
            <div class="ml-8">
                <div class="font-weight-bold"><?php echo e(trans('update.have_a_question?')); ?></div>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_can_leave_your_comment_other_users_and_the_instructor_will_help_you')); ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white mt-24 p-16 rounded-12 border-gray-200">
        <h5 class="font-14 "><?php echo e(trans('update.leave_a_comment')); ?></h5>

        <form action="/comments/store" class="mt-16" method="post">
            <input type="hidden" id="commentItemId" name="item_id" value="<?php echo e($commentForItemId); ?>">
            <input type="hidden" id="commentItemName" name="item_name" value="<?php echo e($commentForItemName); ?>">

            <div class="form-group mb-0">
                <textarea name="comment" class="js-ajax-comment form-control" rows="6"></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-16 pt-16 border-top-gray-100">
                <div class="d-flex align-items-center">
                    <div class="d-flex-center size-48 bg-gray-300 rounded-12">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <h6 class="font-14"><?php echo e(trans('update.comments_approval')); ?></h6>
                        <p class="mt-4 font-12 text-gray-500"><?php echo e(!empty(getGeneralOptionsSettings("direct_publication_of_comments")) ? trans('update.your_comments_will_be_published_directly') : trans('update.your_comments_will_be_published_after_admin_approval')); ?></p>
                    </div>
                </div>

                <div class="d-flex align-items-center mt-16 mt-lg-0">
                    <button type="button" class="js-submit-comment-btn btn btn-lg btn-primary"><?php echo e(trans('update.submit_comment')); ?></button>
                </div>
            </div>
        </form>

    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/comments/submit_form.blade.php ENDPATH**/ ?>