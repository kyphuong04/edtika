<?php if(!empty($assignment->attachments) and count($assignment->attachments)): ?>
    <div class="bg-gray-100 p-12 rounded-16 mt-16">
        <h4 class="font-14 text-dark"><?php echo e(trans('public.attachments')); ?></h4>

        <div class="d-grid grid-columns-4 gap-12 mt-12">
            <?php $__currentLoopData = $assignment->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($attachment->getDownloadUrl()); ?>" target="_blank" class="d-flex align-items-center p-16 rounded-16 bg-white text-dark">
                    <div class="d-flex-center size-56 bg-gray-100 rounded-circle">
                        <div class="d-flex-center size-40 bg-gray-200 rounded-circle">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-download'); ?>
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
                    </div>
                    <div class="ml-8">
                        <h5 class="font-14 text-dark"><?php echo e($attachment->title); ?></h5>
                        <div class="d-flex align-items-center gap-4 font-12 text-gray-500 mt-4">
                            <span class=""><?php echo e($attachment->getFileSize()); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/assignment/attachments.blade.php ENDPATH**/ ?>