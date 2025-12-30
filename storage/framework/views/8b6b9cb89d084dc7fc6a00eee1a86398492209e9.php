<?php if(!empty($authUser)): ?>
    <?php if(!$hasBought): ?>
        <div class="d-flex-center flex-column text-center mt-24 rounded-12 border-gray-200 border-dashed bg-white p-32 pb-40">
            <div class="d-flex-center size-56 rounded-12 bg-primary-20">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-star'); ?>
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

            <?php if($itemName == "webinar_id"): ?>
                <h5 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.review_this_course')); ?></h5>
                <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.you_need_to_enroll_of_the_course_to_review_this_course')); ?></p>
            <?php elseif($itemName == "bundle_id"): ?>
                <h5 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.review_this_bundle')); ?></h5>
                <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.you_need_to_enroll_of_the_bundle_to_review_this_bundle')); ?></p>
            <?php elseif($itemName == "product_id"): ?>
                <h5 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.review_this_product')); ?></h5>
                <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.you_need_to_enroll_of_the_product_to_review_this_product')); ?></p>
            <?php endif; ?>
        </div>
    <?php elseif($itemRow->reviews()->where('creator_id', $authUser->id)->count() < 1): ?>
        <div class="bg-white mt-24 rounded-12 border-gray-200 border-dashed p-16">
            <h5 class="font-14"><?php echo e(trans('update.write_your_review')); ?></h5>

            <form action="<?php echo e($reviewFormPath); ?>" class="mt-16" method="post">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="<?php echo e($itemName); ?>" value="<?php echo e($itemRow->id); ?>"/>

                <div class="form-group">
                    <textarea name="description" class="js-ajax-description form-control" rows="6"></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <h6 class="font-12"><?php echo e(trans('update.feedback_parameters')); ?></h6>

                <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-16 mt-12">

                    <?php $__currentLoopData = $reviewOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reviewOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group mb-0">
                            <div class="barrating-stars bg-gray-100 p-12 rounded-8 js-ajax-<?php echo e($reviewOption); ?> ">
                                <div class="text-gray-500 mb-8"><?php echo e(trans("product.{$reviewOption}")); ?></div>
                                <select name="<?php echo e($reviewOption); ?>" data-rate="1">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <div class="invalid-feedback"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                            <h6 class="font-14"><?php echo e(trans('update.submit_review')); ?></h6>
                            <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.course_page_submit_review_hint')); ?></p>
                        </div>
                    </div>

                    <button type="button" class="js-submit-review-btn btn btn-lg btn-primary mt-16 mt-lg-0"><?php echo e(trans('product.post_review')); ?></button>
                </div>
            </form>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/components/reviews/submit_form.blade.php ENDPATH**/ ?>