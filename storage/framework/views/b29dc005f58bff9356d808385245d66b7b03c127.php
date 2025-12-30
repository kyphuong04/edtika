<?php
    $progressSteps = [
        1 => [
            'name' => 'basic_information',
            'icon' => 'note-2'
        ],

        2 => [
            'name' => 'extra_information',
            'icon' => 'note-add'
        ],

        3 => [
            'name' => 'pricing',
            'icon' => 'empty-wallet'
        ],

        4 => [
            'name' => 'content',
            'icon' => 'document-cloud'
        ],

        5 => [
            'name' => 'prerequisites',
            'icon' => 'archive-tick'
        ],

        6 => [
            'name' => 'faq',
            'icon' => 'bill'
        ],

        7 => [
            'name' => 'quiz_certificate',
            'icon' => 'clipboard-tick'
        ],

    ];

    if (empty(getGeneralOptionsSettings('direct_publication_of_courses'))) {
        $progressSteps[8] = [
            'name' => 'message_to_reviewer',
            'icon' => 'shield-search'
        ];
    }

?>

<div class="position-relative d-flex align-items-center p-20 rounded-16 bg-white">
    <div class="webinar-progress-mask"></div>

    <?php $__currentLoopData = $progressSteps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $progressStep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $isActiveStep = ($currentStep == $key);
        ?>

        <div class="js-get-next-step <?php echo e($isActiveStep ? 'd-flex' : 'd-none d-lg-flex'); ?> align-items-center cursor-pointer <?php echo e(!($loop->last) ? 'mr-40' : ''); ?>" data-step="<?php echo e($key); ?>" <?php if(!$isActiveStep): ?> data-tippy-content="<?php echo e(trans('public.' . $progressStep['name'])); ?>" <?php endif; ?>>
            <div class="d-flex-center size-48 rounded-circle <?php echo e($isActiveStep ? 'bg-primary' : 'bg-gray-100'); ?>">
                <?php echo e(svg("iconsax-lin-{$progressStep['icon']}", ['height' => 24, 'width' => 24, 'class' => $isActiveStep ? 'text-white' : 'text-gray-400'])); ?>
            </div>

            <?php if($isActiveStep): ?>
                <div class="ml-8">
                    <p class="font-12 text-gray-500"><?php echo e(trans('webinars.progress_step', ['step' => $key,'count' => $stepCount])); ?></p>
                    <h6 class="font-14 font-weight-bold mt-2"><?php echo e(trans('public.' . $progressStep['name'])); ?></h6>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/includes/progress.blade.php ENDPATH**/ ?>