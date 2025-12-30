<?php
    $activePageTab = request()->get("tab", 'information');
?>

<div class="custom-tabs mt-16">
    <div class="course-tabs-card position-relative">
        <div class="course-tabs-card__mask"></div>

        <div class="position-relative d-flex align-items-center gap-20 gap-lg-40 flex-wrap bg-white px-16 px-lg-20 rounded-12 z-index-2 w-100">
            <div class="navbar-item d-flex-center cursor-pointer <?php echo e(($activePageTab == "information") ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#aboutCourseTab">
                <span class=""><?php echo e(trans('update.about_course')); ?></span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer <?php echo e(($activePageTab == "content") ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#contentTab">
                <span class=""><?php echo e(trans('update.content')); ?></span>
            </div>

            <div class="navbar-item d-flex-center cursor-pointer <?php echo e(($activePageTab == "comments") ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#commentsTab">
                <span class="ml-4"><?php echo e(trans('panel.comments')); ?></span>

                <span class="course-tab-counter d-flex-center p-4 rounded-8 ml-4 font-12">
                    <?php echo e((!empty($courseComments) and !empty($courseComments['comments_count'])) ? $courseComments['comments_count'] : 0); ?>

                </span>
            </div>

            <div id="showCourseReviewsTab" class="navbar-item d-flex-center cursor-pointer <?php echo e(($activePageTab == "reviews") ? 'active' : ''); ?>" data-tab-toggle data-tab-href="#reviewsTab">
                <span class="ml-4"><?php echo e(trans('product.reviews')); ?></span>

                <span class="course-tab-counter d-flex-center p-4 rounded-8 ml-4 font-12">
                    <?php echo e((!empty($courseReviews) and !empty($courseReviews['reviews_count'])) ? $courseReviews['reviews_count'] : 0); ?>

                </span>
            </div>
        </div>
    </div>

    <div class="custom-tabs-body mt-16">

        <div class="custom-tabs-content <?php echo e(($activePageTab == "information") ? 'active' : ''); ?>" id="aboutCourseTab">
            <?php echo $__env->make('design_1.web.courses.show.tabs.about', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="custom-tabs-content <?php echo e(($activePageTab == "content") ? 'active' : ''); ?>" id="contentTab">
            <?php echo $__env->make('design_1.web.courses.show.tabs.content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="custom-tabs-content <?php echo e(($activePageTab == "comments") ? 'active' : ''); ?>" id="commentsTab">
            <?php echo $__env->make('design_1.web.courses.show.tabs.comments', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>

        <div class="custom-tabs-content <?php echo e(($activePageTab == "reviews") ? 'active' : ''); ?>" id="reviewsTab">
            <?php echo $__env->make('design_1.web.courses.show.tabs.reviews', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/show/includes/page_body.blade.php ENDPATH**/ ?>