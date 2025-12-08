<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }
    ?>

    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("trending_categories")); ?>">
    <?php $__env->stopPush(); ?>

    <div class="trending-categories-section position-relative" <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>

        <div class="container position-relative">

            <?php if(!empty($contents['floating_background'])): ?>
                <div class="trending-categories-section__floating-background d-flex-center">
                    <img src="<?php echo e($contents['floating_background']); ?>" alt="floating_background" class="">
                </div>
            <?php endif; ?>


            <div class="d-flex-center flex-column text-center">
                <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])): ?>
                    <div class="d-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary"><?php echo e($contents['main_content']['pre_title']); ?></div>
                <?php endif; ?>

                <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['title'])): ?>
                    <h2 class="mt-8 font-32 text-dark"><?php echo e($contents['main_content']['title']); ?></h2>
                <?php endif; ?>
            </div>

            
            <?php if(!empty($contents['trending_categories']) and is_array($contents['trending_categories'])): ?>
                <div class="row">
                    <?php $__currentLoopData = $contents['trending_categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trendingCategoryData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!empty($trendingCategoryData['category'])): ?>
                            <?php
                                $trendingCategory = \App\Models\TrendCategory::query()->where('id', $trendingCategoryData['category'])->first();
                            ?>

                            <?php if(!empty($trendingCategory)): ?>
                                <div class="col-6 col-md-4 col-lg-3 mt-24">
                                    <a href="<?php echo e($trendingCategory->category->getUrl()); ?>" target="_blank">
                                        <div class="trending-categories-section__item-card position-relative">
                                            <div class="trending-categories-section__item-card-mask"></div>

                                            <div class="position-relative bg-white p-16 rounded-24 z-index-2">
                                                <div class="d-inline-flex-center size-48 rounded-circle " style="background-color: <?php echo e($trendingCategory->color); ?>">
                                                    <img src="<?php echo e($trendingCategory->icon); ?>" alt="<?php echo e($trendingCategory->category->title); ?>" class="img-fluid" width="24px" height="24px">
                                                </div>

                                                <h4 class="mt-16 font-20 text-dark"><?php echo e($trendingCategory->category->title); ?></h4>
                                                <div class="mt-8 font-16 text-gray-500"><?php echo e(trans('update.count_courses', ['count' => $trendingCategory->category->getCoursesCount()])); ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/trending_categories/index.blade.php ENDPATH**/ ?>