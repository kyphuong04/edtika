<?php
    $courseCertificatesCount = $course->quizzes->where('certificate', true)->count();

    if ($course->certificate) {
        $courseCertificatesCount += 1;
    }

    $userPassedCourseCertificate = !empty($authUser) ? $course->getUserPassedCourseCertificate($authUser) : null;
?>

<?php if($courseCertificatesCount > 0): ?>
    <div id="allCertificatesAccordion">
        <div class="accordion p-12 rounded-12 border-gray-200 bg-white mt-16">
            <div class="accordion__title d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center cursor-pointer" href="#collapseCertificatesAccordion" data-parent="#allCertificatesAccordion" role="button" data-toggle="collapse">
                    <div class="d-flex-center size-48 rounded-12 bg-primary-20">
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
                        <div class="font-14 font-weight-bold"><?php echo e(trans('panel.certificates')); ?></div>
                        <div class="d-flex align-items-center mt-4 font-12 text-gray-500"><?php echo e($courseCertificatesCount); ?> <?php echo e(trans('public.parts')); ?></div>
                    </div>
                </div>

                <div class="collapse-arrow-icon d-flex cursor-pointer" href="#collapseCertificatesAccordion" data-parent="#allCertificatesAccordion" role="button" data-toggle="collapse">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-up-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                </div>
            </div>

            <div id="collapseCertificatesAccordion" class="accordion__collapse border-0 " role="tabpanel">
                <?php $__currentLoopData = $course->quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($quiz2->certificate)): ?>
                        <section class="<?php echo e($loop->first ? '' : 'mt-16'); ?>" id="certificateAccordion">
                            <?php echo $__env->make('design_1.web.courses.show.tabs.contents.quiz_certificate' , ['quiz' => $quiz2, 'accordionParent' => 'certificateAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </section>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($course->certificate): ?>
                    <section class="<?php echo e(($courseCertificatesCount > 1) ? 'mt-16' : '0'); ?>" id="courseCertificateAccordion">
                        <?php echo $__env->make('design_1.web.courses.show.tabs.contents.course_certificate' , ['certificate' => $userPassedCourseCertificate, 'accordionParent' => 'courseCertificateAccordion'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </section>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/tabs/contents/all_certificates.blade.php ENDPATH**/ ?>