<div class="p-12 rounded-12 border-dashed border-gray-200 w-100 h-100">
    <h4 class="font-14 font-weight-bold text-dark">
        <?php if($user->id == $assignment->creator_id): ?>
            <?php echo e(trans('update.reply_to_the_conversation')); ?>

        <?php else: ?>
            <?php echo e(trans('update.send_assignment')); ?>

        <?php endif; ?>
    </h4>

    <div class="js-assignment-conversation-form d-flex flex-column mt-24" data-action="/course/assignment/<?php echo e($assignment->id); ?>/history/<?php echo e($assignmentHistory->id); ?>/message">

        <?php if($user->id == $assignment->creator_id): ?>
            <input type="hidden" name="student_id" value="<?php echo e($assignmentHistory->student_id); ?>">
        <?php endif; ?>

        <div class="form-group">
            <label class="form-group-label"><?php echo e(trans('public.description')); ?></label>
            <textarea name="description" rows="14" class="js-ajax-description form-control"></textarea>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group">
            <label class="form-group-label"><?php echo e(trans('update.file_title')); ?> (<?php echo e(trans('public.optional')); ?>)</label>
            <input type="text" name="file_title" class="js-ajax-file_title form-control"/>
            <div class="invalid-feedback"></div>
        </div>

        <div class="form-group custom-input-file flex-1">
            <label class="form-group-label"><?php echo e(trans('update.attachment')); ?></label>

            <div class="custom-file bg-white js-ajax-attachment">
                <input type="file" name="attachment" class="custom-file-input js-ajax-upload-file-input" id="attachmentsInput" data-upload-name="attachment">
                <span class="custom-file-text text-gray-500"></span>
                <label class="custom-file-label bg-transparent" for="attachmentsInput">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-export'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </label>
            </div>

            <div class="invalid-feedback d-block"></div>
        </div>

        <button type="button" class="js-send-assignment-conversation btn btn-primary btn-lg btn-block"><?php echo e(trans('update.send')); ?></button>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/assignment/form.blade.php ENDPATH**/ ?>