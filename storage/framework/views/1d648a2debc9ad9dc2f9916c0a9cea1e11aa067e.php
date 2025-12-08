<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <?php if(empty($isCourseNotice)): ?>
        <?php echo $__env->make('design_1.panel.noticeboard.lists.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(!empty($noticeboards) and !$noticeboards->isEmpty()): ?>
        <div class="bg-white pt-16 rounded-24 mt-20">
            <div class="d-flex align-items-center justify-content-between pb-16 px-16 border-bottom-gray-100">
                <div class="">
                    <h3 class="font-16"><?php echo e(trans('panel.noticeboards')); ?></h3>

                </div>
            </div>

            
            <?php echo $__env->make('design_1.panel.noticeboard.lists.filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            
            <div id="tableListContainer" class="table-responsive-lg" data-view-data-path="/panel/<?php echo e(!empty($isCourseNotice) ? 'course-noticeboard' : 'noticeboard'); ?>">
                <table class="table panel-table">
                    <thead>
                    <tr>
                        <th class="text-left"><?php echo e(trans('webinars.title')); ?></th>
                        <th class="text-center"><?php echo e(trans('site.message')); ?></th>

                        <?php if(!empty($isCourseNotice) and $isCourseNotice): ?>
                            <th class="text-center"><?php echo e(trans('update.color')); ?></th>
                        <?php else: ?>
                            <th class="text-center"><?php echo e(trans('public.type')); ?></th>
                        <?php endif; ?>

                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                        <th class="text-right"><?php echo e(trans('public.controls')); ?></th>
                    </tr>
                    </thead>
                    <tbody class="js-table-body-lists">
                    <?php $__currentLoopData = $noticeboards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $noticeboardRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('design_1.panel.noticeboard.lists.table_items', ['noticeboard' => $noticeboardRow], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>

                
                <div id="pagination" class="js-ajax-pagination" data-container-id="tableListContainer" data-container-items=".js-table-body-lists">
                    <?php echo $pagination; ?>

                </div>
            </div>
        </div>
    <?php else: ?>
        <?php echo $__env->make('design_1.panel.includes.no-result',[
          'file_name' => 'noticeboard.svg',
            'title' => trans('update.noticeboard_no_result'),
            'hint' =>  nl2br(trans('update.noticeboard_no_result_hint')) ,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <div class="d-none" id="noticeboardMessageModal">
        <div class="text-center py-20 px-16">
            <h3 class="modal-title font-16 font-weight-bold"></h3>
            <span class="modal-time d-block font-12 text-gray-500 mt-16"></span>
            <div class="modal-message mt-8"></div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="<?php echo e(getDesign1ScriptPath("get_view_data")); ?>"></script>

    <script src="/assets/design_1/js/panel/noticeboard.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/noticeboard/lists/index.blade.php ENDPATH**/ ?>