<?php if($user->hasMeeting()): ?>
    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="/assets/default/vendors/persian-datepicker/persian-datepicker.min.css"/>

        <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("profile_reserve_meeting")); ?>">
    <?php $__env->stopPush(); ?>

    <form action="<?php echo e($user->getMeetingReservationUrl()); ?>/overview" method="get">

        <div class="mt-16 text-gray-500"><?php echo e(trans('update.please_pick_a_day_from_the_calendar_and_select_an_available_time_slot_you_will_be_redirected_to_the_meeting_booking_process')); ?></div>

        <?php echo $__env->make('design_1.web.users.profile.tabs.reserveMeeting.top_stats', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="row mt-16">
            <div class="col-12 col-lg-5">
                <?php echo $__env->make('design_1.web.users.profile.tabs.reserveMeeting.calendar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="col-12 col-lg-7 mt-20 mt-lg-0">
                <?php echo $__env->make('design_1.web.users.profile.tabs.reserveMeeting.times', ['instructor' => $user], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

    </form>

    <?php if(
           !empty($instructorDiscounts) and
           count($instructorDiscounts)
       ): ?>
        <div class="">
            <?php echo $__env->make('design_1.web.instructor_discounts.cards', ['allDiscounts' => $instructorDiscounts, 'discountCardClassName' => "user-profile-discount-card mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    <?php endif; ?>


    <?php $__env->startPush('scripts_bottom2'); ?>
        <script src="/assets/default/vendors/persian-datepicker/persian-date.js"></script>
        <script src="/assets/default/vendors/persian-datepicker/persian-datepicker.js"></script>

        <script>
            var hasMonthTime = 'true';
            var hasMonthDay = 'true';
            var hasMonthHour = 'true';
            var nextBtnIcon = ``;
            var prevBtnIcon = ``;
            var availableDays = <?php echo e(json_encode($times)); ?>;
        </script>

        <script src="<?php echo e(getDesign1ScriptPath("profile_reserve_meeting")); ?>"></script>
    <?php $__env->stopPush(); ?>

<?php else: ?>
    <?php echo $__env->make('design_1.panel.includes.no-result',[
        'file_name' => 'profile_meeting.svg',
        'title' => trans('update.user_profile_not_have_meeting'),
        'hint' => trans('update.user_profile_not_have_meeting_hint'),
        'extraClass' => 'mt-0',
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/users/profile/tabs/reserveMeeting/index.blade.php ENDPATH**/ ?>