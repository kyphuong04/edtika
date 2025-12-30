<?php if(!empty($nextSession) and $nextSession->date > time() and checkTimestampInToday($nextSession->date)): ?>
    <div class="js-next-session-info d-flex align-items-center cursor-pointer" data-webinar-id="<?php echo e($saleItem->id); ?>">
        <div class="d-flex-center">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.live_session_in_progress')); ?></h5>
            <p class="font-12 text-gray-500"><?php echo e(trans('update.join_it_now')); ?></p>
        </div>
    </div>
<?php elseif($sale->payment_method == \App\Models\Sale::$subscribe and $sale->checkExpiredPurchaseWithSubscribe($sale->buyer_id, $saleItem->id, !empty($sale->webinar) ? 'webinar_id' : 'bundle_id')): ?>
    <div class="d-flex align-items-center">
        <div class="d-flex-center">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-cup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.expired_subscription')); ?></h5>
            <p class="font-12 text-gray-500"><?php echo e(trans('update.your_subscription_plan_expired')); ?></p>
        </div>
    </div>
<?php elseif(!empty($saleItem->access_days)): ?>
    <?php if(!$saleItem->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id)): ?>
        <div class="d-flex align-items-center">
            <div class="d-flex-center">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-gift'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.access_days_expired')); ?></h5>
                <p class="font-12 text-gray-500"><?php echo e(trans('update.your_purchase_expired')); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex align-items-center">
            <div class="d-flex-center">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-calendar-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.course_expiry')); ?></h5>
                <p class="font-12 text-gray-500"><?php echo e(trans('update.the_course_access_expires_on_date', ['date' => dateTimeFormat($saleItem->getExpiredAccessDays($sale->created_at, $sale->gift_id), 'j M Y')])); ?></p>
            </div>
        </div>
    <?php endif; ?>
<?php elseif(!empty($sale->webinar)): ?>
    <?php
        $percent = $saleItem->getProgress(true);
    ?>

    <?php if($percent > 99): ?>
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-36 bg-success rounded-circle">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-tick-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <div class="ml-8">
                <h5 class="font-12 font-weight-bold text-dark"><?php echo e(trans('update.completed')); ?>!</h5>
                <p class="font-12 text-gray-500"><?php echo e(trans('update.you_did_it_perfectly...')); ?></p>
            </div>
        </div>
    <?php else: ?>
        <div class="w-100">
            <div class="d-flex align-items-center gap-4 font-12">
                <span class="font-weight-bold text-dark"><?php echo e(round($percent,1)); ?>%</span>
                <span class="text-gray-500"><?php echo e(trans('update.av._learning_progress')); ?></span>
            </div>

            <div class="progress-bar d-flex mt-8 rounded-4 bg-gray-100 w-100">
                <span class="bg-success rounded-4" style="width: <?php echo e($percent); ?>%"></span>
            </div>
        </div>
    <?php endif; ?>
<?php elseif(count($saleItem->reviews->where('creator_id', $authUser->id)) < 1): ?>
    <a href="<?php echo e($saleItem->getUrl()); ?>?tab=reviews" target="_blank" class="d-flex align-items-center">
        <div class="d-flex-center">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-star-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning ','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
        </div>

        <div class="ml-8">
            <h5 class="font-12 font-weight-bold text-dark"><?php echo e((!empty($sale->bundle)) ? trans('update.rate_this_bundle') : trans('update.rate_this_course')); ?></h5>
            <p class="font-12 text-gray-500"><?php echo e((!empty($sale->bundle)) ? trans('update.submit_a_review_for_the_course') : trans('update.submit_a_review_for_the_course')); ?></p>
        </div>
    </a>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_purchases/item_card/progress_and_chart.blade.php ENDPATH**/ ?>