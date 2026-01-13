<?php
    $saleItem = !empty($sale->webinar) ? $sale->webinar : $sale->bundle;

    $lastSession = !empty($sale->webinar) ? $sale->webinar->lastSession() : null;
    $nextSession = !empty($sale->webinar) ? $sale->webinar->nextSession() : null;
    $isProgressing = false;

    if(!empty($sale->webinar) and $sale->webinar->start_date <= time() and !empty($lastSession) and $lastSession->date > time()) {
        $isProgressing = true;
    }
?>

<?php if(!empty($saleItem)): ?>
    <div class="panel-course-card-1 position-relative">
        <div class="card-mask"></div>

        <div class="position-relative d-flex flex-column flex-lg-row  gap-12 z-index-2 bg-white p-12 rounded-24">
            
            <div class="panel-course-card-1__image position-relative rounded-16 bg-gray-100">
                <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank">
                    <img src="<?php echo e($saleItem->getImage()); ?>" alt="" class="img-cover rounded-16">
                </a>
                
                <?php echo $__env->make("design_1.panel.webinars.my_purchases.item_card.badges", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php if($saleItem->type == 'webinar'): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="d-flex-center w-100 h-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </a>
                    </div>
                <?php elseif($saleItem->type == "text_lesson"): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="d-flex-center w-100 h-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-note-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </a>
                    </div>
                <?php elseif($saleItem->type == "course"): ?>
                    <div class="is-live-course-icon d-flex-center size-64 rounded-circle">
                        <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="d-flex-center w-100 h-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-video-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="panel-course-card-1__content flex-1 d-flex flex-column">
                <div class="bg-gray-100 p-16 rounded-16 mb-12">
                    <div class="d-flex align-items-start justify-content-between gap-12">
                        <div class="">
                            <h3 class="font-16 text-dark">
                                <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="text-decoration-none text-dark">
                                    <?php echo e(truncate($saleItem->title, 46)); ?>

                                </a>
                            </h3>

                            <?php echo $__env->make("design_1.web.components.rate", [
                                'rate' => round($saleItem->getRate(),1),
                                'rateCount' => $saleItem->reviews()->where('status', 'active')->count(),
                                'rateClassName' => 'mt-8',
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        
                        <?php echo $__env->make("design_1.panel.webinars.my_purchases.item_card.actions_dropdown", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    
                    <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="text-decoration-none">
                        <?php echo $__env->make("design_1.panel.webinars.my_purchases.item_card.stats", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </a>
                </div>

                
                <div class="row align-items-center justify-content-between mt-auto">
                    <div class="col-10">
                        <?php echo $__env->make("design_1.panel.webinars.my_purchases.item_card.progress_and_chart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    
                    <?php if(!empty($sale->webinar)): ?>
                        <div class="col-2 d-flex align-items-center justify-content-end">
                            <a href="<?php echo e($saleItem->getLearningPageUrl()); ?>" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 text-primary mr-4"><?php echo e(trans('update.continue_learning')); ?></span>
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary mt-2','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </a>
                        </div>
                    <?php elseif(!empty($sale->bundle)): ?>
                        <div class="col-2 d-flex align-items-center justify-content-end">
                            <a href="<?php echo e($saleItem->getUrl()); ?>" target="_blank" class="continue-learning-link d-flex align-items-center cursor-pointer text-decoration-none">
                                <span class="font-12 text-primary mr-4"><?php echo e(trans('update.details')); ?></span>
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary mt-2','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_purchases/item_card/index.blade.php ENDPATH**/ ?>