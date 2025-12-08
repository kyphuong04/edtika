<?php
    $checkSequenceContent = $assignment->checkSequenceContent();
    $sequenceContentHasError = (!empty($checkSequenceContent) and (!empty($checkSequenceContent['all_passed_items_error']) or !empty($checkSequenceContent['access_after_day_error'])));

    $assignmentPersonalNote = $assignment->personalNote()->where('user_id', $authUser->id)->first();
    $hasPersonalNote = (!empty($assignmentPersonalNote) and !empty($assignmentPersonalNote->note));

    $hasSequenceContentError = (!empty($checkSequenceContent) and $sequenceContentHasError);

    $requestStudent = null;

    if (request()->get('type') == "assignment" and request()->get('item') == $assignment->id and !empty(request()->get('student'))) {
        $requestStudent = request()->get('student');
    }
?>


<div class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-16 cursor-pointer js-content-tab-item <?php echo e(($user->isAdmin() or $course->isPartnerTeacher($user->id)) ? 'js-not-access-toast' : ($hasSequenceContentError ? 'js-sequence-content-error-modal' : '')); ?>"
     data-type="assignment"
     data-id="<?php echo e($assignment->id); ?>"
     data-extra-key="student"
     data-extra-value="<?php echo e(!empty($requestStudent) ? $requestStudent : ''); ?>"
     data-passed-error="<?php echo e(!empty($checkSequenceContent['all_passed_items_error']) ? $checkSequenceContent['all_passed_items_error'] : ''); ?>"
     data-access-days-error="<?php echo e(!empty($checkSequenceContent['access_after_day_error']) ? $checkSequenceContent['access_after_day_error'] : ''); ?>"
>
    <div class="d-flex align-items-center">
        <div class="position-relative d-flex-center size-48 rounded-12 bg-gray-200">
            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-text'); ?>
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

            <?php if($hasSequenceContentError): ?>
                <div class="sidebar-item-lock-icon d-flex-center rounded-circle bg-white">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-lock-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-danger','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="ml-8">
            <span class=" d-block font-weight-bold font-14 text-dark"><?php echo e(truncate($assignment->title, 27)); ?></span>
            <span class=" d-block font-12 text-gray-500 mt-4"><?php echo e(trans('update.assignment')); ?></span>
        </div>
    </div>

    <div class="d-flex align-items-center gap-8">
        <?php if($hasPersonalNote): ?>
            <div class="">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note'); ?>
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
            </div>
        <?php endif; ?>


    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/sidebar/tabs/contents/assignment.blade.php ENDPATH**/ ?>