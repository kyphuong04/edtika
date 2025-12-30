<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/sortable/jquery-ui.min.css"/>
<?php $__env->stopPush(); ?>

<div class="bg-white rounded-16 p-16 mt-32">


    <div class="d-flex align-items-center justify-content-between mt-32 p-12 rounded-16 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 bg-primary-20 rounded-12">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-bill'); ?>
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
                <h5 class="font-14 font-weight-bold"><?php echo e(trans('update.frequently_asked_questions')); ?></h5>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.add_FAQ_and_display_them_on_the_course_page')); ?></p>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6 mt-20">
            <?php echo $__env->make('design_1.panel.bundles.create.includes.accordions.faq', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="col-lg-6 mt-36">
            <?php if(!empty($bundle->faqs) and count($bundle->faqs)): ?>
                <div class="p-16 rounded-16 border-gray-200">
                    <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.faqs')); ?></h3>

                    <ul class="draggable-content-lists faq-draggable-lists" data-path="" data-drag-class="faq-draggable-lists">
                        <?php $__currentLoopData = $bundle->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faqInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('design_1.panel.bundles.create.includes.accordions.faq',['faq' => $faqInfo], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <div class="d-flex-center flex-column px-32 py-120 text-center">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-message-question'); ?>
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
                    <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('public.faq_no_result')); ?></h3>
                    <p class="mt-4 font-12 text-gray-500"><?php echo trans('public.faq_no_result_hint'); ?></p>
                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/sortable/jquery-ui.min.js"></script>

<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/bundles/create/steps/step_5.blade.php ENDPATH**/ ?>