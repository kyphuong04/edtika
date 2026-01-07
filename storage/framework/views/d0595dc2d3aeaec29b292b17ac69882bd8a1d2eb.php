<?php if(!empty($pendingCertificates) and count($pendingCertificates)): ?>
    <div class="mt-28">
        <h3 class="font-16 font-weight-bold"><?php echo e(trans('update.potential_certificates')); ?></h3>
        <p class="mt-4 font-12 text-gray-500"><?php echo e(trans('update.you_have_potential_to_get_the_following_certificates_according_to_your_activities')); ?></p>

        <div class="position-relative mt-16">
            <div class="swiper-container js-make-swiper pending-certificates-swiper pb-24"
                 data-item="pending-certificates-swiper"
                 data-autoplay="false"
                 data-breakpoints="1440:4.2,769:3.4,320:1.4"
            >
                <div class="swiper-wrapper py-8">
                    <?php $__currentLoopData = $pendingCertificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendingCertificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="swiper-slide">
                            <?php if($pendingCertificate->type == 'quiz'): ?>
                            <a href="/panel/quizzes/<?php echo e($pendingCertificate->id); ?>/start" target="_blank" class="d-block text-decoration-none">
                            <?php else: ?>
                            <a href="<?php echo e($pendingCertificate->getLearningPageUrl()); ?>" target="_blank" class="d-block text-decoration-none">
                            <?php endif; ?>
                                <div class="bg-white p-20 rounded-24">
                                    <div class="d-flex align-items-center justify-content-between">

                                        <?php if($pendingCertificate->type == 'quiz'): ?>
                                                <?php if(!empty($pendingCertificate->icon)): ?>
                                                 <div class="d-flex-center size-64 rounded-12">
                                                    <img src="<?php echo e($pendingCertificate->icon); ?>" class="img-cover rounded-12">
                                                <?php else: ?>
                                                 <div class="d-flex-center size-64 bg-primary-30 rounded-12">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>

                                            <?php
                                                $percent = $pendingCertificate->getProgress(true);
                                            ?>

                                            <div class="js-pending-certificate-chart d-flex-center size-64" data-id="courseChart_<?php echo e($pendingCertificate->id); ?>" data-percent="<?php echo e(round($percent, 1)); ?>">
                                                <canvas id="courseChart_<?php echo e($pendingCertificate->id); ?>" width="64px" height="64px"></canvas>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($pendingCertificate->type == 'quiz'): ?>
                                            <div class="d-flex align-items-center ml-8 cursor-pointer">
                                                <span class="font-12 text-primary mr-4"><?php echo e(trans('update.take_quiz')); ?></span>
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
                                            </div>
                                        <?php else: ?>
                                            <div class="d-flex align-items-center ml-8 cursor-pointer">
                                                <span class="font-12 text-primary mr-4"><?php echo e(trans('update.continue_learning')); ?></span>
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
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <h5 class="mt-12 font-14 font-weight-bold text-ellipsis text-dark"><?php echo e($pendingCertificate->title); ?></h5>

                                    <p class="mt-4 font-12 text-gray-500 text-ellipsis">
                                        <?php if($pendingCertificate->type == 'quiz'): ?>
                                            <?php echo e(trans('update.take_this_quiz_to_get_the_certificate')); ?>

                                        <?php else: ?>
                                            <?php echo e(trans('update.complete_the_course_to_get_certificate')); ?>

                                        <?php endif; ?>
                                    </p>

                                    <?php if($pendingCertificate->type == 'quiz'): ?>
                                        <span class="mt-4 font-12 text-gray-500 text-ellipsis d-block"><?php echo e($pendingCertificate->webinar->title); ?></span>
                                    <?php else: ?>
                                        <span class="mt-4 font-12 text-gray-500 text-ellipsis d-block"><?php echo e($pendingCertificate->title); ?></span>
                                    <?php endif; ?>

                                    <div class="d-flex align-items-center mt-12 flex-wrap">

                                        <?php if($pendingCertificate->type == 'quiz'): ?>
                                            <div class="d-flex align-items-center mr-16">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
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
                                                <span class="ml-4 font-12 text-gray-400"><?php echo e($pendingCertificate->quizQuestions->sum('grade')); ?></span>
                                            </div>

                                            <div class="d-flex align-items-center mr-16">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-timer-1'); ?>
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
                                                <span class="ml-4 font-12 text-gray-400"><?php echo e($pendingCertificate->time); ?> <?php echo e(trans('public.min')); ?>.</span>
                                            </div>

                                            <?php if(!empty($pendingCertificate->expiry_days) and !empty($pendingCertificate->expiry_timestamp)): ?>
                                                <div class="d-flex align-items-center mr-16">
                                                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-calendar-2'); ?>
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
                                                    <span class="ml-4 font-12 text-gray-400"><?php echo e(trans('update.expired_on_date', ['date' => dateTimeFormat($pendingCertificate->expiry_timestamp, 'j M Y H:i')])); ?></span>
                                                </div>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <div class="d-flex align-items-center mr-16">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-2'); ?>
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
                                                <span class="ml-4 font-12 text-gray-400"><?php echo e($pendingCertificate->getAllLessonsCount()); ?></span>
                                            </div>

                                            <div class="d-flex align-items-center mr-16">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
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
                                                <span class="ml-4 font-12 text-gray-400"><?php echo e(convertMinutesToHourAndMinute($pendingCertificate->duration)); ?> <?php echo e(trans('home.hours')); ?></span>
                                            </div>

                                        <?php endif; ?>

                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/certificates/my_achievements/potential_certificates.blade.php ENDPATH**/ ?>