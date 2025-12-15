<div class="bg-white p-16 rounded-24 w-100 mt-24">
    <h4 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.my_assignments')); ?></h4>

    <?php if(!empty($myAssignments) and count($myAssignments)): ?>
        <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-16 mt-16">
            <?php $__currentLoopData = $myAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $myAssignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e("{$myAssignment->webinar->getLearningPageUrl()}?type=assignment&item={$myAssignment->id}"); ?>" target="_blank" class="text-decoration-none">
                    <div class="student-dashboard__my-assignment-box d-flex flex-column bg-gray-100 p-16 rounded-16">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="size-48 rounded-8 bg-gray-100">
                                <img src="<?php echo e($myAssignment->webinar->getIcon()); ?>" alt="" class="img-cover rounded-8">
                            </div>

                            <?php if(empty($myAssignment->assignmentHistory) or ($myAssignment->assignmentHistory->status == \App\Models\WebinarAssignmentHistory::$notSubmitted)): ?>
                                <div class="p-8 rounded-8 bg-danger-30 text-danger font-8"><?php echo e(trans('update.not_submitted')); ?></div>
                            <?php else: ?>
                                <?php switch($myAssignment->assignmentHistory->status):
                                    case (\App\Models\WebinarAssignmentHistory::$passed): ?>
                                        <div class="p-8 rounded-8 bg-success-30 text-success font-8"><?php echo e(trans('quiz.passed')); ?></div>
                                        <?php break; ?>
                                    <?php case (\App\Models\WebinarAssignmentHistory::$pending): ?>
                                        <div class="p-8 rounded-8 bg-warning-30 text-warning font-8"><?php echo e(trans('update.pending_review')); ?></div>
                                        <?php break; ?>
                                    <?php case (\App\Models\WebinarAssignmentHistory::$notPassed): ?>
                                        <div class="p-8 rounded-8 bg-warning-30 text-warning font-8"><?php echo e(trans('quiz.failed')); ?></div>
                                        <?php break; ?>
                                <?php endswitch; ?>
                            <?php endif; ?>
                        </div>

                        <h5 class="font-13 text-dark mt-12 mb-24"><?php echo e($myAssignment->title); ?></h5>

                        <?php
                            $myAssignmentTeacher = $myAssignment->webinar->teacher;
                        ?>

                        <div class="d-flex align-items-center mt-auto">
                            <div class="size-32 rounded-circle">
                                <img src="<?php echo e($myAssignmentTeacher->getAvatar(32)); ?>" alt="" class="img-cover rounded-circle">
                            </div>
                            <div class="ml-8">
                                <span class="d-block font-weight-bold font-12 text-dark"><?php echo e(truncate($myAssignmentTeacher->full_name, 16)); ?></span>
                                <span class="d-block font-12 text-gray-500 mt-4"><?php echo e(dateTimeFormat($myAssignment->created_at, 'j M Y H:i')); ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="d-flex-center flex-column text-center p-32 bg-gray-100 border-dashed border-gray-200 rounded-16 mt-16">
            <div class="d-flex-center size-48 rounded-12 bg-primary-40">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-text'); ?>
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

            <h5 class="font-14 text-dark mt-12"><?php echo e(trans('update.no_assignment')); ?></h5>
            <div class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_dont_have_not_submitted_or_pending_review_assignments')); ?></div>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/dashboard/student/includes/my_assignments.blade.php ENDPATH**/ ?>