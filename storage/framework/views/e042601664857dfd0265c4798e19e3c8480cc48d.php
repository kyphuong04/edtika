<?php if(!empty($userBadges) and count($userBadges)): ?>
    <div id="profileBadgesRow" class="d-grid grid-columns-2 grid-lg-columns-5 gap-16 mt-16">
        <?php $__currentLoopData = $userBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userBadge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="profile-badge-card position-relative mb-8">
                <div class="profile-badge-card__mask"></div>

                <div class="position-relative d-flex-center flex-column text-center bg-white rounded-16 border-gray-200 p-16 pt-32 z-index-2 h-100 w-100">
                    <div class="d-flex-center size-64 rounded-16 bg-gray-100">
                        <img src="<?php echo e(!empty($userBadge->badge_id) ? $userBadge->badge->image : $userBadge->image); ?>" class="img-fluid" alt="<?php echo e(!empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title); ?>">
                    </div>

                    <div class="mt-12 font-14 font-weight-bold "><?php echo e(!empty($userBadge->badge_id) ? $userBadge->badge->title : $userBadge->title); ?></div>

                    <div class="mt-8 font-12 text-gray-500"><?php echo (!empty($userBadge->badge_id) ? nl2br($userBadge->badge->description) : nl2br($userBadge->description)); ?></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php else: ?>
    <?php echo $__env->make('design_1.panel.includes.no-result',[
        'file_name' => 'profile_badges.svg',
        'title' => trans('update.user_profile_not_have_badges'),
        'hint' => trans('update.user_profile_not_have_badges_hint'),
        'extraClass' => 'mt-0',
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/web/users/profile/tabs/badges.blade.php ENDPATH**/ ?>