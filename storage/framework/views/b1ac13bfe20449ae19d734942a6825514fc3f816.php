<div class="d-flex align-items-center justify-content-between bg-gray-100 p-12 rounded-12">
    <div class="">
        <h2 class="font-24"><?php echo e($item->title); ?></h2>

        <div class="d-flex flex-wrap align-items-center gap-16 gap-lg-24 mt-12">

            <?php if($itemType == "file"): ?>
                <div class="d-flex align-items-center">
                    <?php
                        $itemIcon = !empty($item) ? $item->getIconXByType() : 'document';
                    ?>

                    <?php echo e(svg("iconsax-lin-{$itemIcon}", ['height' => 20, 'width' => 20, 'class' => 'text-gray-500'])); ?>

                    <span class="ml-4 text-gray-500"><?php echo e(trans('update.file_type_' . $item->file_type)); ?></span>
                </div>
            <?php endif; ?>

            <?php if($itemType == "session"): ?>
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-video'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4 text-gray-500"><?php echo e(trans('update.live_session')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($itemType == "quiz"): ?>
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4 text-gray-500"><?php echo e(trans('quiz.quiz')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($itemType == "quiz"): ?>
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clipboard-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4 text-gray-500"><?php echo e(trans('quiz.quiz')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($itemType == "text_lesson"): ?>
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-note-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4 text-gray-500"><?php echo e(trans('webinars.text_lesson')); ?></span>
                </div>
            <?php endif; ?>

            <?php
                $itemDuration = null;

                if (!empty($item->duration)) {
                    $itemDuration = $item->duration;
                }

                if (!empty($item->study_time)) {
                    $itemDuration = $item->study_time;
                }
            ?>
            <?php if(!empty($itemDuration)): ?>
                <div class="d-flex align-items-center">
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-clock-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="ml-4 text-gray-500"><?php echo e(convertMinutesToHourAndMinute($itemDuration)); ?> <?php echo e(trans('public.minutes')); ?></span>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <div class="d-flex align-items-center gap-16">
        <?php if($itemType == "file" and $item->downloadable): ?>
            <a href="<?php echo e($course->getUrl()); ?>/file/<?php echo e($item->id); ?>/download" class="d-flex-center size-48 rounded-circle bg-white" data-tippy-content="<?php echo e(trans('home.download')); ?>">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import-2'); ?>
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
            </a>
        <?php endif; ?>

        <?php if(!empty(getFeaturesSettings('course_notes_status'))): ?>
            <div class="position-relative d-flex-center size-48 rounded-circle bg-white cursor-pointer <?php echo e($itemHasPersonalNote ? 'js-edit-personal-note' : 'js-add-personal-note'); ?>"
                 data-item-id="<?php echo e($item->id); ?>"
                 data-item-type="<?php echo e($item->getMorphClass()); ?>"
                 data-tippy-content="<?php echo e(trans('update.personal_note')); ?>"
            >
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-text'); ?>
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

                <?php if($itemHasPersonalNote): ?>
                    <div class="has-personal-note-beep"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if(!empty($itemType) and $itemType == 'text_lesson'): ?>
    <?php if(!empty($item->summary)): ?>
        <div class="mt-16 text-gray-500"><?php echo nl2br($item->summary); ?></div>
    <?php endif; ?>

    <?php if(!empty($item->content)): ?>
        <div class="mt-16 text-gray-500"><?php echo nl2br($item->content); ?></div>
    <?php endif; ?>
<?php else: ?>
    <?php if(!empty($item->description)): ?>
        <div class="mt-16 text-gray-500"><?php echo nl2br($item->description); ?></div>
    <?php endif; ?>
<?php endif; ?>



<?php if(!empty($item->attachments) and count($item->attachments)): ?>
    <div class="bg-gray-100 p-12 rounded-16 mt-24">
        <h4 class="font-14 text-dark"><?php echo e(trans('update.attachments')); ?></h4>

        <div class="d-grid grid-columns-auto grid-lg-columns-4 gap-12 mt-12">
            <?php $__currentLoopData = $item->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itemAttachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(!empty($itemAttachment->file)): ?>
                    <a href="<?php echo e($courseUrl); ?>/file/<?php echo e($itemAttachment->file->id); ?>/download" target="_blank" class="d-flex align-items-center p-16 rounded-16 bg-white text-dark">
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
                            <h5 class="font-14 text-dark"><?php echo e($itemAttachment->file->title); ?></h5>
                            <div class="d-flex align-items-center gap-4 font-12 text-gray-500 mt-4">
                                <span class=""><?php echo e(trans("update.file_type_{$itemAttachment->file->file_type}")); ?></span>

                                <?php if(!empty($itemAttachment->file->volume)): ?>
                                    <span class="">| <?php echo e($itemAttachment->file->getVolume()); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>


<div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between mt-24 pt-16 border-top-gray-100">
    <?php if(!in_array($itemType, ['quiz'])): ?>
        <div class="d-flex align-items-center form-group mb-0">
            <div class="custom-switch mr-8">
                <input type="checkbox"
                       name="passed_section_toggle[]"
                       id="fileReadToggle<?php echo e($item->id); ?>"
                       data-item-name="<?php echo e($itemType); ?>_id"
                       data-course-slug="<?php echo e($courseSlug); ?>"
                       value="<?php echo e($item->id); ?>"
                       class="js-passed-item-toggle custom-control-input"
                    <?php echo e((!empty($item->checkPassedItem())) ? 'checked' : ''); ?>

                >
                <label class="custom-control-label cursor-pointer" for="fileReadToggle<?php echo e($item->id); ?>"></label>
            </div>

            <div class="">
                <label class="cursor-pointer text-gray-500" for="fileReadToggle<?php echo e($item->id); ?>"><?php echo e(trans('public.i_passed_this_lesson')); ?></label>
            </div>
        </div>
    <?php else: ?>
        <div class=""></div>
    <?php endif; ?>

    
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/includes/item_footer_actions_and_desc.blade.php ENDPATH**/ ?>