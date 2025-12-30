<li data-id="<?php echo e(!empty($plan) ? $plan->id :''); ?>" class="accordion bg-white rounded-15 p-16 border-gray-200 mt-16">
    <div class="accordion__title d-flex align-items-center justify-content-between" role="tab" id="price_plan_<?php echo e(!empty($plan) ? $plan->id :'record'); ?>">
        <div class="font-weight-bold font-14 cursor-pointer" href="#collapsePricePlan<?php echo e(!empty($plan) ? $plan->id :'record'); ?>" data-parent="#price_plansAccordion" role="button" data-toggle="collapse">
            <span><?php echo e(!empty($plan) ? $plan->title : trans('update.new_pricing_plan')); ?></span>
        </div>

        <?php if(!empty($plan)): ?>
            <div class="d-flex align-items-center">

                

                <div class="actions-dropdown position-relative mr-12">
                    <button type="button" class="btn-transparent d-flex align-items-center justify-content-center">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </button>

                    <div class="actions-dropdown__dropdown-menu">
                        <ul class="my-8">
                            <li class="actions-dropdown__dropdown-menu-item">
                                <a href="/panel/tickets/<?php echo e($plan->id); ?>/delete" class="delete-action d-flex align-items-center w-100 px-16 py-8 bg-transparent text-danger"><?php echo e(trans('delete')); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="collapse-arrow-icon d-flex cursor-pointer" href="#collapsePricePlan<?php echo e(!empty($plan) ? $plan->id :'record'); ?>" data-parent="#price_plansAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

    </div>

    <div id="collapsePricePlan<?php echo e(!empty($plan) ? $plan->id :'record'); ?>" class="accordion__collapse <?php echo e(empty($plan) ? 'show' : ''); ?>" role="tabpanel">
        <div class="js-content-form js-price_plan-form mt-20" data-action="/panel/tickets/<?php echo e(!empty($plan) ? $plan->id . '/update' : 'store'); ?>">
            <input type="hidden" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][bundle_id]" value="<?php echo e(!empty($bundle) ? $bundle->id :''); ?>">

            <div class="row">
                <div class="col-12 col-lg-12">

                    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
                        'itemRow' => !empty($plan) ? $plan : null,
                        'withoutReloadLocale' => true,
                        'extraClass' => 'js-webinar-content-locale',
                        'extraData' => "data-webinar-id='".(!empty($bundle) ? $bundle->id : '')."'  data-id='".(!empty($plan) ? $plan->id : '')."'  data-relation='tickets' data-fields='title'"
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('title')); ?></label>

                        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>

                        <input type="text" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][title]" class="js-ajax-title form-control" value="<?php echo e(!empty($plan) ? $plan->title : ''); ?>"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group ">
                        <label class="form-group-label"><?php echo e(trans('public.discount')); ?></label>

                        <span class="has-translation bg-gray-200 rounded-8 p-8 text-gray-500 font-14">%</span>

                        <input type="text" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][discount]" class="js-ajax-discount form-control" value="<?php echo e(!empty($plan) ? $plan->discount : ''); ?>"/>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('public.capacity')); ?></label>

                        <input type="text" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][capacity]" class="js-ajax-capacity form-control" value="<?php echo e(!empty($plan) ? $plan->capacity : ''); ?>" placeholder="<?php echo e(trans('update.leave_it_blank_for_using_course_capacity')); ?>"/>
                        <div class="invalid-feedback"></div>

                        <?php if(empty($plan) and !empty($bundle->capacity) and !empty($sumTicketsCapacities)): ?>
                            <div class="test-gray-500 font-12 mt-8"><?php echo e(trans('panel.remaining')); ?>: <span class="js-ticket-remaining-capacity"><?php echo e($bundle->capacity - $sumTicketsCapacities); ?></span></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-0">
                                <label class="form-group-label"><?php echo e(trans('public.start_date')); ?></label>

                                <span class="has-translation bg-transparent rounded-8 p-8">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </span>

                                <input type="text" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][start_date]" class="js-ajax-start_date form-control datetimepicker js-default-init-date-picker bg-white" value="<?php echo e(!empty($plan) ? dateTimeFormat($plan->start_date, 'Y-m-d  H:i', false) :''); ?>" aria-describedby="dateRangeLabel" data-drops="up"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 mt-20 mt-md-0">
                            <div class="form-group mb-0">
                                <label class="form-group-label"><?php echo e(trans('webinars.end_date')); ?></label>

                                <span class="has-translation bg-transparent rounded-8 p-8">
                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '24','height' => '24']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                </span>

                                <input type="text" name="ajax[<?php echo e(!empty($plan) ? $plan->id : 'new'); ?>][end_date]" class="js-ajax-end_date form-control datetimepicker js-default-init-date-picker bg-white" value="<?php echo e(!empty($plan) ? dateTimeFormat($plan->end_date, 'Y-m-d  H:i', false) :''); ?>" aria-describedby="dateRangeLabel" data-drops="up"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-28 d-flex align-items-center">
                <button type="button" class="js-save-price_plan btn btn-sm btn-primary"><?php echo e(trans('save')); ?></button>

                <?php if(!empty($plan)): ?>
                    <a href="/panel/tickets/<?php echo e($plan->id); ?>/delete" class="delete-action btn btn-sm btn-outline-danger ml-8 cancel-accordion"><?php echo e(trans('delete')); ?></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/includes/accordions/price_plan.blade.php ENDPATH**/ ?>