<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">

    
    <h3 class="font-14 font-weight-bold mb-24"><?php echo e(trans('update.pricing_options')); ?></h3>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.price')); ?></label>
        <span class="has-translation bg-gray-100 text-gray-500"><?php echo e($currency); ?></span>
        <input type="text" name="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e((!empty($bundle) and !empty($bundle->price)) ? convertPriceToUserCurrency($bundle->price) : old('price')); ?>" placeholder="<?php echo e(trans('public.0_for_free')); ?>" oninput="validatePrice(this)"/>
        <div class="invalid-feedback d-block"><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <?php echo e($message); ?> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?></div>
    </div>


    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('update.access_days')); ?> (<?php echo e(trans('public.optional')); ?>)</label>
        <span class="has-translation bg-gray-100 text-gray-500 w-auto px-8"><?php echo e(trans('public.days')); ?></span>
        <input type="number" name="access_days" class="form-control <?php $__errorArgs = ['access_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(!empty($bundle) ? $bundle->access_days : old('access_days')); ?>"/>

        <?php $__errorArgs = ['access_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback">
            <?php echo e($message); ?>

        </div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <p class="font-12 text-gray-500 mt-8">- <?php echo e(trans('update.access_days_input_hint')); ?></p>
    </div>

    <div class="form-group">
        <div class="d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="subscribeSwitch" type="checkbox" name="subscribe" class="custom-control-input" <?php echo e((!empty($bundle) and $bundle->subscribe) ? 'checked' :  ''); ?>>
                <label class="custom-control-label cursor-pointer" for="subscribeSwitch"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="subscribeSwitch"><?php echo e(trans('update.include_subscribe')); ?></label>
            </div>
        </div>

        <p class="font-12 text-gray-500 mt-8">- <?php echo e(trans('forms.subscribe_hint')); ?></p>
    </div>

    

    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-moneys'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>

            <div class="ml-8">
                <h5 class="font-14 font-weight-bold"><?php echo e(trans('update.pricing_plans')); ?></h5>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.create_different_pricing_plans_and_present_your_course_in_different_prices')); ?></p>
            </div>
        </div>
    </div>

    <div class="mt-16 p-16 rounded-16 bg-gray-100 border-gray-300">
        <p class="font-12 text-gray-500">- <?php echo e(trans('webinars.sale_plans_hint_1')); ?></p>
        <p class="font-12 text-gray-500 mt-12">- <?php echo e(trans('webinars.sale_plans_hint_2')); ?></p>
        <p class="font-12 text-gray-500 mt-12">- <?php echo e(trans('webinars.sale_plans_hint_3')); ?></p>
    </div>

    
    <div class="row">
        <div class="col-lg-6">
            <?php echo $__env->make('design_1.panel.bundles.create.includes.accordions.price_plan', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <?php if(!empty($bundle->tickets) and count($bundle->tickets)): ?>
            <div class="col-lg-6 mt-20 mt-lg-16">
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.pricing_plans')); ?></h3>

                    <ul class="draggable-content-lists price-plan-draggable-lists" data-path="/panel/tickets/orders" data-drag-class="price-plan-draggable-lists">
                        <?php $__currentLoopData = $bundle->tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pricePlan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('design_1.panel.bundles.create.includes.accordions.price_plan',['plan' => $pricePlan], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php else: ?>
            <div class="col-lg-6 d-flex-center flex-column px-32 py-120 text-center">
                <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-receipt-2'); ?>
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
                <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('public.ticket_no_result')); ?></h3>
                <p class="mt-4 font-12 text-gray-500"><?php echo trans('public.ticket_no_result_hint'); ?></p>
            </div>
        <?php endif; ?>
    </div>


</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/steps/step_3.blade.php ENDPATH**/ ?>