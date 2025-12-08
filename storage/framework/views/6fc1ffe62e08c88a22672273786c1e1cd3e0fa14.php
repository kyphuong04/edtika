<?php $__env->startPush('styles_top'); ?>
    <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="font-14 font-weight-bold text-dark"><?php echo e(trans('update.select_a_date')); ?></h3>
                    <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.select_a_date_from_the_calendar_and_check_events')); ?></p>
                </div>

                <div class="dashboard-events-calendar mt-20">
                    <input type="hidden" id="inlineEventsCalender" value="">
                    <div id="dashboardEventsCalendar"></div>
                </div>

            </div>
        </div>

        <div class="js-day-events-card col-12 col-lg-6 mt-20 mt-lg-0">
            <?php echo $__env->make('design_1.panel.events.day_events', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="col-12 col-lg-3 mt-20 mt-lg-0">
            
            <div class="bg-white p-16 rounded-24">
                <div class="pb-6 border-bottom-gray-100">
                    <h3 class="d-flex align-items-center font-14 font-weight-bold text-dark"><?php echo e(trans('update.upcoming_events')); ?></h3>
                    <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.check_upcoming_events_and_add_them_to_reminder')); ?></p>
                </div>

                
                <?php if(!empty($upcomingEvents) and count($upcomingEvents)): ?>
                    <?php $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcomingEvent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="js-upcoming-event-card d-flex align-items-center p-12 rounded-16 mt-16 bg-gray-100 cursor-pointer" data-day="<?php echo e($upcomingEvent['event_at']); ?>">
                            <div class="events-calendar__upcoming-event-date-box d-flex-center flex-column text-center rounded-8 bg-gray-200">
                                <span class="font-weight-bold text-dark"><?php echo e(dateTimeFormat($upcomingEvent['event_at'], 'j')); ?></span>
                                <span class="font-12 text-gray-400 mt-2"><?php echo e(dateTimeFormat($upcomingEvent['event_at'], 'M')); ?></span>
                            </div>
                            <div class="ml-8">
                                <div class=""><?php echo e(trans("update.{$upcomingEvent['title']}")); ?></div>
                                <p class="font-12 text-gray-500 mt-8"><?php echo e($upcomingEvent['subtitle']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush("scripts_bottom"); ?>
    <script>
        var $eventsWithTimestamp = <?php echo json_encode((!empty($eventsWithTimestamp) and count($eventsWithTimestamp)) ? $eventsWithTimestamp : [], 15, 512) ?>;
    </script>

    <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
    <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>


    <script src="/assets/design_1/js/panel/events_calendar.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/events/index.blade.php ENDPATH**/ ?>