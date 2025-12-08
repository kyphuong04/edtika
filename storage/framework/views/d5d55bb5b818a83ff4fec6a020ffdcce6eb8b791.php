<?php if(!empty($assignmentHistory->messages) and count($assignmentHistory->messages)): ?>
    <div class="assignment-history-messages px-16" data-simplebar <?php if((!empty($isRtl))): ?> data-simplebar-direction="rtl" <?php endif; ?>>
        <?php $__currentLoopData = $assignmentHistory->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white p-16 rounded-16 border-gray-200 mb-16">
                <div class="d-flex align-items-center">
                    <div class="size-40 rounded-circle">
                        <img src="<?php echo e($message->sender->getAvatar(40)); ?>" class="img-cover rounded-circle" alt="<?php echo e($message->sender->full_name); ?>">
                    </div>
                    <div class="ml-4">
                        <h4 class="font-14 font-weight-bold text-dark"><?php echo e($message->sender->full_name); ?></h4>
                        <span class="d-block font-12 text-gray-500 mt-2"><?php echo e(dateTimeFormat($message->created_at, 'j M Y H:i')); ?></span>
                    </div>
                </div>

                <div class="mt-12 text-gray-500"><?php echo $message->message; ?></div>

                <?php if(!empty($message->file_path)): ?>
                    <a href="<?php echo e($message->getDownloadUrl($assignment->id)); ?>" target="_blank" class="d-inline-flex-center p-8 mt-16 rounded-8 border-gray-300 bg-white bg-hover-gray-100">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-download'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        <span class="ml-4 font-12 text-gray-500"><?php echo e(!empty($message->file_title) ? $message->file_title : trans('update.attachment')); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <div class="d-flex-center flex-column text-center w-100 h-100">
        <div class="">
            <img src="/assets/design_1/img/courses/learning_page/assignment/no-messages.svg" alt="" class="img-fluid" width="285px" height="212px">
        </div>

        <h5 class="mt-12 font-16 text-dark"><?php echo e(trans('update.no_assignment')); ?></h5>
        <div class="mt-8 text-gray-500"><?php echo e(trans('update.submit_your_assignment_and_evaluate_your_learning')); ?></div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/assignment/messages.blade.php ENDPATH**/ ?>