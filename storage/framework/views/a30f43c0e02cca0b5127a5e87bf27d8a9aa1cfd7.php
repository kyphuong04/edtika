<div class="actions-dropdown position-relative d-flex justify-content-end align-items-center">
    <div class="d-flex-center size-40 bg-white border-gray-200 rounded-8 cursor-pointer">
        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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

    <div class="actions-dropdown__dropdown-menu dropdown-menu-width-220">
        <ul class="my-8">

            <?php if(!empty($sale->gift_id) and $sale->buyer_id == $authUser->id): ?>
                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="/panel/courses/<?php echo e($saleItem->id); ?>/sale/<?php echo e($sale->id); ?>/invoice" target="_blank" class=""><?php echo e(trans('public.invoice')); ?></a>
                </li>
            <?php else: ?>
                <?php if(!empty($saleItem->access_days) and !$saleItem->checkHasExpiredAccessDays($sale->created_at, $sale->gift_id)): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class=""><?php echo e(trans('update.enroll_on_course')); ?></a>
                    </li>
                <?php elseif(!empty($sale->webinar)): ?>
                    <li class="actions-dropdown__dropdown-menu-item">
                        <a href="<?php echo e($saleItem->getLearningPageUrl()); ?>" target="_blank" class="webinar-actions d-block"><?php echo e(trans('update.learning_page')); ?></a>
                    </li>

                    <?php if(!empty($saleItem->start_date) and ($saleItem->start_date > time() or ($saleItem->isProgressing() and !empty($nextSession)))): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <button type="button" data-webinar-id="<?php echo e($saleItem->id); ?>" class="js-next-session-info"><?php echo e(trans('footer.join')); ?></button>
                        </li>
                    <?php endif; ?>

                    <?php if(!empty($saleItem->downloadable) or (!empty($saleItem->files) and count($saleItem->files))): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="<?php echo e($saleItem->getUrl()); ?>?tab=content" target="_blank" class=""><?php echo e(trans('home.download')); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if($saleItem->price > 0): ?>
                        <li class="actions-dropdown__dropdown-menu-item">
                            <a href="/panel/courses/<?php echo e($saleItem->id); ?>/sale/<?php echo e($sale->id); ?>/invoice" target="_blank" class=""><?php echo e(trans('public.invoice')); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <li class="actions-dropdown__dropdown-menu-item">
                    <a href="<?php echo e($saleItem->getUrl()); ?>?tab=reviews" target="_blank" class=""><?php echo e(trans('public.feedback')); ?></a>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_purchases/item_card/actions_dropdown.blade.php ENDPATH**/ ?>