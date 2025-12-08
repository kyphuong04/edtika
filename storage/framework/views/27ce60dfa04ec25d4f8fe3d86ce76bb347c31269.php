<div class="position-fixed position-bottom-0 position-left-0 position-right-0 z-index-3 bg-white soft-shadow-2">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between py-16">

        <div class="d-flex align-items-center">
            
            <a href="<?php echo e((!empty($webinar) and $currentStep > 1) ? ("/panel/courses/{$webinar->id}/step/" . ($currentStep - 1)) : '#!'); ?>" class="d-flex-center size-48 rounded-circle bg-gray-100">
                <?php echo e(svg("iconsax-lin-arrow-left", ['height' => 16, 'width' => 16, 'class' => (!empty($webinar) and $currentStep > 1) ? 'text-primary' : 'text-gray-500'])); ?>
            </a>

            
            <div id="getNextStep" class="d-flex-center size-48 rounded-circle bg-gray-100 ml-16 cursor-pointer">
                <?php echo e(svg("iconsax-lin-arrow-right", ['height' => 16, 'width' => 16, 'class' => ($currentStep < $stepCount) ? 'text-primary' : 'text-gray-500'])); ?>
            </div>

        </div>

        <div class="d-flex align-items-center mt-20 mt-lg-0 gap-8">
            
            <button type="button" id="saveAsDraft" class=" btn btn-transparent text-gray-500"><?php echo e(trans('public.save_as_draft')); ?></button>

            <?php if(!empty($webinar) and $webinar->creator_id == $authUser->id): ?>
                <?php echo $__env->make('design_1.panel.includes.content_delete_btn', [
                    'deleteContentUrl' => "/panel/courses/{$webinar->id}/delete?redirect_to=/panel/courses",
                    'deleteContentClassName' => 'webinar-actions text-danger ml-16',
                    'deleteContentItem' => $webinar,
                    'deleteContentItemType' => "course",
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            
            <button type="button" id="sendForReview" class="btn btn-lg btn-primary ml-16"><?php echo e(!empty(getGeneralOptionsSettings('direct_publication_of_courses')) ? trans('update.publish') : trans('public.send_for_review')); ?></button>




        </div>
    </div>

    <?php
        $stepProgressPercent = (($currentStep * 100) / $stepCount);
    ?>

    <div class="create-course-bottom-progress">
        <div class="create-course-bottom-progress__process" style="width: <?php echo e($stepProgressPercent); ?>%"></div>
    </div>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/bottom_actions.blade.php ENDPATH**/ ?>