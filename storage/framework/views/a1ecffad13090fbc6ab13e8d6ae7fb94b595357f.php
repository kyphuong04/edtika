<?php if($user->id == $assignment->creator_id and !empty($assignmentStudentId)): ?>
    <div class="d-flex align-items-center justify-content-between mt-16 rounded-16 p-12 border-gray-300 border-dashed">
        <div class="d-flex align-items-center">
            <div class="d-flex-center size-48 rounded-12 bg-primary-20">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-star'); ?>
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
                <h5 class="font-14 text-dark"><?php echo e(trans('update.rate_assignment')); ?></h5>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.rate_assignment_instructor_hint_msg')); ?></p>
            </div>
        </div>

        <button type="button"
            class="js-show-submit-rate btn btn-primary btn-lg"
            data-path="/course/assignment/<?php echo e($assignment->id); ?>/history/<?php echo e($assignmentHistory->id); ?>/grade-modal?student=<?php echo e($assignmentStudentId); ?>"
        >
            <?php echo e(trans('update.submit_rate')); ?>

        </button>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/assignment/instructor_rate.blade.php ENDPATH**/ ?>