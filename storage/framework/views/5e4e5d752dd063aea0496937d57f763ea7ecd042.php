<?php if($course->certificate): ?>
    <div
        class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100"
    >
        <div class="d-flex align-items-center cursor-pointer js-content-tab-item"
             data-type="course_certificate"
             data-id="<?php echo e(!empty($courseCertificate) ? $courseCertificate->id : ''); ?>"
             data-passed-error=""
             data-access-days-error=""
        >
            <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-teacher'); ?>
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
                <div class="font-weight-bold font-14 text-dark"><?php echo e(trans('update.course_certificate')); ?></div>

                <?php if(!empty($courseCertificate)): ?>
                    <span class="font-12 text-gray-500 mt-4"><?php echo e(dateTimeFormat($courseCertificate->created_at, 'j M Y')); ?></span>
                <?php else: ?>
                    <span class="font-12 text-danger mt-4"><?php echo e(trans("update.not_achieve")); ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <?php if(!empty($courseCertificate)): ?>
                <a href="/panel/certificates/webinars/<?php echo e($courseCertificate->id); ?>/show" target="_blank" class="d-flex-center size-32 rounded-circle bg-white">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import'); ?>
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
                </a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php if(!empty($course->quizzes) and count($course->quizzes)): ?>
    <?php $__currentLoopData = $course->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseQuiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if($courseQuiz->certificate): ?>
            <?php
                $courseQuizResult = $courseQuiz->result;
            ?>
            <div
                class="sidebar-content-item d-flex align-items-center justify-content-between mb-12 p-12 rounded-20 bg-gray-100"
            >
                <div class="d-flex align-items-center cursor-pointer js-content-tab-item"
                     data-type="quiz_certificate"
                     data-id="<?php echo e(!empty($courseQuizResult) ? $courseQuizResult->id : ''); ?>"
                     data-extra-key="quiz_id"
                     data-extra-value="<?php echo e($courseQuiz->id); ?>"
                     data-passed-error=""
                     data-access-days-error=""
                >
                    <div class="position-relative d-flex-center size-48 rounded-12 bg-primary-20">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-award'); ?>
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
                        <div class="font-weight-bold font-14 text-dark"><?php echo e(truncate($courseQuiz->title, 27)); ?></div>

                        <?php if(!empty($courseQuizResult)): ?>
                            <span class="font-12 text-gray-500 mt-4"><?php echo e(dateTimeFormat($courseQuizResult->created_at, 'j M Y')); ?></span>
                        <?php else: ?>
                            <span class="font-12 text-danger mt-4"><?php echo e(trans("update.not_achieve")); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <?php if(!empty($courseQuizResult)): ?>
                        <a href="/panel/quizzes/results/<?php echo e($courseQuizResult->id); ?>/showCertificate" target="_blank" class="d-flex-center size-32 rounded-circle bg-white">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import-2'); ?>
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
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/sidebar/tabs/certificates.blade.php ENDPATH**/ ?>