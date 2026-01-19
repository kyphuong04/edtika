<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css"/>
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <?php echo $__env->make('design_1.panel.webinars.my_purchases.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('design_1.panel.webinars.my_purchases.upcoming_live_sessions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php if(!empty($sales) and $sales->isNotEmpty()): ?>
        <div id="tableListContainer" class="" data-view-data-path="/panel/courses">
            <div class="js-page-sales-lists row mt-20">
                <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 col-lg-6 mb-32">
                        <?php echo $__env->make("design_1.panel.webinars.my_purchases.item_card.index", ['sale' => $saleRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer"
                 data-container-items=".js-page-sales-lists">
                <?php echo $pagination; ?>

            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
            'file_name' => 'purchased_courses.svg',
            'title' => trans('panel.no_result_purchases') ,
            'hint' => trans('panel.no_result_purchases_hint') ,
            'btn' => ['url' => '/classes?sort=newest','text' => trans('panel.start_learning')]
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script>
        var undefinedActiveSessionLang = '<?php echo e(trans('webinars.undefined_active_session')); ?>';
        var saveSuccessLang = '<?php echo e(trans('webinars.success_store')); ?>';
        var selectChapterLang = '<?php echo e(trans('update.select_chapter')); ?>';
        var liveSessionInfoLang = '<?php echo e(trans('update.live_session_info')); ?>';
        var joinTheSessionLang = '<?php echo e(trans('update.join_the_session')); ?>';
    </script>

    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>
    <script src="/assets/design_1/js/parts/swiper_slider.min.js"></script>
    <script src="/assets/design_1/js/panel/my_course_lists.min.js"></script>
    <script src="/assets/design_1/js/panel/make_next_session.min.js"></script>

<?php $__env->stopPush(); ?>

<<<<<<<< HEAD:storage/framework/views/3b1b82f458cc4b6eefd896d49932438efd2753c5.php
<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_courses/index.blade.php ENDPATH**/ ?>
========
<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/my_purchases/index.blade.php ENDPATH**/ ?>
>>>>>>>> frontend:storage/framework/views/02c27313d3ae2853bbc227c1f25e74fded5bd5cb.php
