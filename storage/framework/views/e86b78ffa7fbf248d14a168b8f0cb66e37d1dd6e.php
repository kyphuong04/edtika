<?php
    $learningMaterialsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','learning_materials') : null;
    $companyLogosExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','company_logos') : null;
    $requirementsExtraDescription = !empty($course->webinarExtraDescription) ? $course->webinarExtraDescription->where('type','requirements') : null;
?>


<?php if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'top_of_page'): ?>
    <?php $__currentLoopData = $installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installmentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('design_1.web.installments.includes.card',[
               'installment' => $installmentRow,
               'itemPrice' => $course->getPrice(),
               'itemId' => $course->id,
               'itemType' => 'course',
               'className' => '',
           ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>


<div class="bg-white py-16 rounded-24">

    
    <?php if(!empty($learningMaterialsExtraDescription) and count($learningMaterialsExtraDescription)): ?>
        <div class="mb-32 px-16">
            <div class="course-extra-card bg-gray-100 p-12 rounded-12 mt-40">
                <div class="course-extra-card__title p-16 rounded-12 border-dashed border-gray-200 bg-white">
                    <h3 class="font-16 font-weight-bold"><?php echo e(trans('update.what_you_will_learn')); ?></h3>
                </div>

                <div class="d-grid grid-columns-auto grid-lg-columns-2 gap-12 mt-12">
                    <?php $__currentLoopData = $learningMaterialsExtraDescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $learningMaterial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center p-16 rounded-8 bg-white">
                            <div class="size-16">
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tick-icon','data' => ['class' => 'icons text-primary','width' => '16px','height' => '16px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('tick-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>

                            <span class="flex-1 ml-4 font-14 text-gray-500"><?php echo e($learningMaterial->value); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($course->description): ?>
        <div class="px-16">
            <h2 class="font-16 font-weight-bold"><?php echo e(trans('update.about_this_course')); ?></h2>

            <div class="course-show-description mt-12 text-gray-500">
                <?php echo nl2br($course->description); ?>

            </div>
        </div>
    <?php endif; ?>

    
    <?php if(!empty($requirementsExtraDescription) and count($requirementsExtraDescription)): ?>
        <div class="px-16 pb-28">
            <div class="course-extra-card bg-gray-100 p-12 pb-28 rounded-12 mt-32">
                <div class="course-extra-card__title d-flex align-items-center justify-content-between p-16 rounded-12 border-dashed border-gray-200 bg-white">
                    <h3 class="font-16 font-weight-bold"><?php echo e(trans('update.requirements')); ?></h3>

                    <div class="size-24">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-task-square'); ?>
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

                <div class="">
                    <?php $__currentLoopData = $requirementsExtraDescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requirementExtraDescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex align-items-center <?php echo e($loop->first ? 'mt-20' : 'mt-16'); ?>">
                            <div class="size-16">
                                <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tick-icon','data' => ['class' => 'icons text-primary','width' => '16px','height' => '16px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('tick-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                            </div>

                            <span class="flex-1 ml-4 font-14 text-gray-500"><?php echo e($requirementExtraDescription->value); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="course-extra-card__float-img">
                    <img src="/assets/design_1/img/courses/requirements.svg" alt="<?php echo e(trans('update.requirements')); ?>" class="img-fluid">
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(!empty($companyLogosExtraDescription) and count($companyLogosExtraDescription)): ?>
        <div class="mt-32 p-16 pb-28 border-top-gray-200 border-bottom-gray-200">
            <div class="">
                <h2 class="font-16 font-weight-bold"><?php echo e(trans('update.trusted_companies')); ?></h2>
                <p class="mt-4 font-12 text-gray-500">+3200 Companies trusted our courses for their staff tutoring</p>
            </div>

            <div class="position-relative mt-16">
                <div class="swiper-container js-make-swiper course-trusted-companies-slider pb-0"
                     data-item="course-trusted-companies-slider"
                     data-autoplay="true"
                     data-loop="true"
                     data-breakpoints="1440:5.5,769:4.2,320:1.4"
                >
                    <div class="swiper-wrapper py-0 mx-16 mx-md-32">
                        <?php $__currentLoopData = $companyLogosExtraDescription; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyLogo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="swiper-slide course-company-logos d-flex-center">
                                <img src="<?php echo e($companyLogo->value); ?>" class="img-fluid" alt="<?php echo e(trans('update.company_logos')); ?>">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if(!empty($course->faqs) and $course->faqs->count() > 0): ?>
        <div id="courseFAQParent" class="px-16 mt-32">
            <div class="">
                <h2 class="font-16 font-weight-bold"><?php echo e(trans('public.faq')); ?></h2>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.check_frequently_asked_questions_about_this_course')); ?></p>
            </div>

            <?php $__currentLoopData = $course->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="accordion p-20 rounded-12 border-gray-200 bg-white <?php echo e($loop->first ? 'mt-16' : 'mt-20'); ?>">
                    <div class="accordion__title d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center cursor-pointer" href="#courseFAQ_<?php echo e($faq->id); ?>" data-parent="#courseFAQParent" role="button" data-toggle="collapse">
                            <div class="size-24">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-message-question'); ?>
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

                            <div class="font-14 font-weight-bold ml-8">
                                <?php echo e(clean($faq->title,'title')); ?>

                            </div>
                        </div>

                        <div class="collapse-arrow-icon d-flex cursor-pointer" href="#courseFAQ_<?php echo e($faq->id); ?>" data-parent="#courseFAQParent" role="button" data-toggle="collapse">
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

                    <div id="courseFAQ_<?php echo e($faq->id); ?>" class="accordion__collapse border-0 " role="tabpanel">
                        <div class="p-16 rounded-8 border-gray-200 text-gray-500 mt-8">
                            <?php echo e(clean($faq->answer,'answer')); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
    <?php endif; ?>

    
    <?php if(!empty($course->prerequisites) and $course->prerequisites->count() > 0): ?>
        <div class="px-16 mt-32">
            <div class="">
                <h2 class="font-16 font-weight-bold"><?php echo e(trans('public.prerequisites')); ?></h2>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.we_suggest_passing_prerequisites_for_more_efficient_learning')); ?></p>
            </div>

            <div class="row">
                <?php $__currentLoopData = $course->prerequisites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prerequisite): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(!empty($prerequisite->prerequisiteWebinar)): ?>
                        <div class="col-12 col-md-6 col-lg-3 mt-16">
                            <?php echo $__env->make('design_1.web.courses.show.includes.prerequisite',['courseItem' => $prerequisite->prerequisiteWebinar], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endif; ?>

</div>



<div class="course-about-instructor-card position-relative mt-32 mt-lg-60">
    <div class="course-about-instructor-card__mask"></div>

    <div class="position-relative d-flex flex-column flex-lg-row align-items-start gap-24 bg-white px-16 rounded-24 z-index-3">
        <div class="course-about-instructor-card__details flex-1 py-16">
            <div class="d-flex align-items-center">
                <div class="position-relative d-flex-center size-80 rounded-12 bg-gray-200">
                    <img src="<?php echo e($course->teacher->getAvatar(80)); ?>" alt="<?php echo e($course->teacher->full_name); ?>" class="img-cover rounded-12">
                </div>

                <div class="ml-12 flex-1">
                    <a href="<?php echo e($course->teacher->getProfileUrl()); ?>" target="_blank" class="">
                        <h6 class="font-14 font-weight-bold text-dark"><?php echo e($course->teacher->full_name); ?></h6>
                    </a>

                    <?php
                        $courseInstructorRates = $course->teacher->rates(true);
                    ?>

                    <?php echo $__env->make('design_1.web.components.rate', [
                        'rate' => $courseInstructorRates['rate'],
                        'rateCount' => $courseInstructorRates['count'],
                        'rateClassName' => 'mt-4',
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <div class="d-flex align-items-center gap-12 mt-8">
                        <div class="d-flex align-items-center p-8 rounded-24 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-video-play'); ?>
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
                            <span class="mx-4 font-weight-bold"><?php echo e($course->teacher->getTeacherCoursesCount()); ?></span>
                            <span class=""><?php echo e(trans('update.courses')); ?></span>
                        </div>

                        <div class="d-flex align-items-center p-8 rounded-24 border-gray-200 bg-gray-100 text-gray-500 font-12">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-teacher'); ?>
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
                            <span class="mx-4 font-weight-bold"><?php echo e($course->teacher->getTeacherStudentsCount()); ?></span>
                            <span class=""><?php echo e(trans('quiz.students')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-16 text-gray-500"><?php echo truncate($course->teacher->about, 716); ?></div>
        </div>

        <div class="course-about-instructor-card__secondary-img position-relative">
            <img src="<?php echo e($course->teacher->getProfileSecondaryImage()); ?>" alt="<?php echo e($course->teacher->full_name); ?>" class="img-cover">

            <?php if($course->teacher->hasMeeting()): ?>
                <a href="<?php echo e($course->teacher->getMeetingReservationUrl()); ?>" target="_blank" class="course-about-instructor-card__book-meeting-btn d-inline-flex align-items-center gap-8 px-24 py-12 cursor-pointer">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="text-white"><?php echo e(trans('public.book_a_meeting')); ?></span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php if(!empty($recentReviews) and count($recentReviews)): ?>
    <div class="bg-white p-16 mt-28 rounded-24">
        <div class="d-flex align-content-center justify-content-between">
            <div class="">
                <h2 class="font-16 font-weight-bold"><?php echo e(trans('update.recent_reviews')); ?></h2>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.check_what_students_say_about_the_course')); ?></p>
            </div>

            <div class="js-view-more-reviews d-flex align-content-center cursor-pointer">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-arrow-right'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '16px','height' => '16px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                <span class="ml-4 text-primary font-weight-bold"><?php echo e(trans('update.more_reviews')); ?></span>
            </div>
        </div>

        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper course-recent-reviews-slider pb-0"
                 data-item="course-recent-reviews-slider"
                 data-autoplay="true"
                 data-loop="true"
                 data-breakpoints="1440:2.4,991:1.7,660:1.2"
            >
                <div class="swiper-wrapper py-0 mx-16 mx-md-32">
                    <?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recentReview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <div class="bg-white p-16 rounded-12 border-gray-200">
                                <div class="d-flex align-content-center">
                                    <div class="size-40 rounded-circle">
                                        <img src="<?php echo e($recentReview->creator->getAvatar(40)); ?>" alt="<?php echo e($recentReview->creator->full_name); ?>" class="img-cover rounded-circle">
                                    </div>
                                    <div class="ml-8">
                                        <span class="d-block font-weight-bold"><?php echo e($recentReview->creator->full_name); ?></span>
                                        <span class="d-block font-12 mt-4 text-gray-500"><?php echo e(dateTimeFormat($recentReview->created_at, 'j M Y')); ?></span>
                                    </div>
                                </div>

                                <div class="course-recent-review-desc mt-16 text-gray-500">
                                    <?php echo clean(truncate($recentReview->description, 150), 'description'); ?>

                                </div>

                                <div class="mt-16 pt-16 border-top-gray-100">
                                    <?php echo $__env->make('design_1.web.components.rate', [
                                        'rate' => $recentReview->rates,
                                        'rateClassName' => '',
                                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if(!empty($course->relatedCourses) and $course->relatedCourses->count() > 0): ?>
    <?php
        $relatedCourses = [];

        foreach($course->relatedCourses as $relatedCourse) {
            if(!empty($relatedCourse->course)) {
                $relatedCourses[] = $relatedCourse->course;
            }
        }
    ?>

    <?php if(count($relatedCourses)): ?>
        <div class="mt-48">
            <div class="">
                <h2 class="font-16 font-weight-bold"><?php echo e(trans('update.related_courses')); ?></h2>
                <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.explore_courses_we_published_currently_and_enjoy_updated_information')); ?></p>
            </div>

            <div class="row">
                <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $relatedCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>


<?php if(!empty($installments) and count($installments) and getInstallmentsSettings('installment_plans_position') == 'bottom_of_page'): ?>
    <?php $__currentLoopData = $installments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $installmentRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('design_1.web.installments.includes.card',[
               'installment' => $installmentRow,
               'itemPrice' => $course->getPrice(),
               'itemId' => $course->id,
               'itemType' => 'course',
               'className' => 'mt-48',
           ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/tabs/about.blade.php ENDPATH**/ ?>