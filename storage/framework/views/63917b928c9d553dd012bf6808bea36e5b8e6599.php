<?php $__env->startPush("styles_top"); ?>
    <link rel="stylesheet" href="<?php echo e(getDesign1StylePath("search")); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection("content"); ?>
    <main class="pb-80">
        <section class="search-hero position-relative">
            <img src="<?php echo e(getThemePageBackgroundSettings('search')); ?>" class="img-cover" alt="<?php echo e(trans('public.search')); ?>"/>
            <div class="search-hero__mask"></div>

            <div class="container position-relative d-flex-center flex-column z-index-3">
                <h1 class="font-24 font-weight-bold text-white"><?php echo e(trans('update.search_results')); ?></h1>

                <?php if(!empty(request()->get('search'))): ?>
                    <div class="mt-8 font-12 text-white opacity-75"><?php echo e(trans('update.n_results_found_for_search', ['count' => $resultCount, 'search' => request()->get('search')])); ?></div>
                <?php endif; ?>

                <div class="row justify-content-center w-100">
                    <div class="col-12 col-lg-6">
                        <div class="search-form-box bg-white p-12 mt-20 rounded-16 w-100">
                            <form action="/search" method="get">
                                <div class="form-group d-flex align-items-center mb-0">
                                    <input type="text" name="search" class="form-control border-0 p-12" value="<?php echo e(request()->get('search','')); ?>" placeholder="<?php echo e(trans('home.slider_search_placeholder')); ?>"/>
                                    <button type="submit" class="btn btn-primary btn-lg"><?php echo e(trans('public.search')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="d-flex-center gap-16 mt-24 w-100">
                    <?php if(!empty($webinars) and $webinars->isNotEmpty()): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionWebinars"><?php echo e(trans('update.courses')); ?> (<?php echo e(count($webinars)); ?>)</div>
                    <?php endif; ?>

                    <?php if(!empty($bundles) and $bundles->isNotEmpty()): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionBundles"><?php echo e(trans('update.bundles')); ?> (<?php echo e(count($bundles)); ?>)</div>
                    <?php endif; ?>

                    <?php if(!empty($products) and $products->isNotEmpty()): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionProducts"><?php echo e(trans('update.products')); ?> (<?php echo e(count($products)); ?>)</div>
                    <?php endif; ?>

                    <?php if(!empty($upcomingCourses) and $upcomingCourses->isNotEmpty()): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionUpcomingCourses"><?php echo e(trans('update.upcoming_courses')); ?> (<?php echo e(count($upcomingCourses)); ?>)</div>
                    <?php endif; ?>

                    <?php if(!empty($posts) and $posts->isNotEmpty()): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionPosts"><?php echo e(trans('update.posts')); ?> (<?php echo e(count($posts)); ?>)</div>
                    <?php endif; ?>

                    <?php if(!empty($instructors) and !empty($organizations) and (count($instructors) + count($organizations)) > 0): ?>
                        <div class="js-content-anchor search-hero__content-anchor p-10 rounded-8 cursor-pointer font-12 text-white" data-anchor-id="sectionUsers"><?php echo e(trans('panel.users')); ?> (<?php echo e((count($instructors) + count($organizations))); ?>)</div>
                    <?php endif; ?>

                </div>

            </div>

        </section>

        
        <?php if(!empty($webinars) and $webinars->isNotEmpty()): ?>
            <section id="sectionWebinars" class="container mt-48">
                <h3 class="font-24 font-weight-bold"><?php echo e(trans('update.courses')); ?></h3>

                <div class="row">
                    <?php echo $__env->make('design_1.web.courses.components.cards.grids.index',['courses' => $webinars, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </section>
        <?php endif; ?>

        
        <?php if(!empty($bundles) and $bundles->isNotEmpty()): ?>
            <section id="sectionBundles" class="container mt-48">
                <h3 class="font-24 font-weight-bold"><?php echo e(trans('update.bundles')); ?></h3>

                <div class="row">
                    <?php echo $__env->make('design_1.web.bundles.components.cards.grids.index',['bundles' => $bundles, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </section>
        <?php endif; ?>

        
        <?php if(!empty($products) and $products->isNotEmpty()): ?>
            <section id="sectionProducts" class="container mt-48">
                <h3 class="font-24 font-weight-bold"><?php echo e(trans('update.store_products')); ?></h3>

                <div class="row">
                    <?php echo $__env->make('design_1.web.products.components.cards.grids.index',['products' => $products, 'gridCardClassName' => "col-12 col-md-6 col-lg-4 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </section>
        <?php endif; ?>

        
        <?php if(!empty($upcomingCourses) and $upcomingCourses->isNotEmpty()): ?>
            <section id="sectionUpcomingCourses" class="container mt-48">
                <h3 class="font-24 font-weight-bold"><?php echo e(trans('update.upcoming_courses')); ?></h3>

                <div class="row">
                    <?php echo $__env->make('design_1.web.upcoming_courses.components.cards.grids.index',['upcomingCourses' => $upcomingCourses, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </section>
        <?php endif; ?>

        
        <?php if(!empty($posts) and $posts->isNotEmpty()): ?>
            <section id="sectionPosts" class="container mt-48">
                <h3 class="font-24 font-weight-bold"><?php echo e(trans('update.blog_posts')); ?></h3>

                <div class="row">
                    <?php echo $__env->make('design_1.web.blog.components.cards.grids.index',['posts' => $posts, 'gridCardClassName' => "col-12 col-md-6 col-lg-3 mt-16"], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </section>
        <?php endif; ?>

        
        <?php if((!empty($instructors) and count($instructors)) or (!empty($organizations) and count($organizations))): ?>
            <section id="sectionUsers" class="container">

                <?php if(!empty($organizations) and count($organizations)): ?>
                    <div class="mt-48">
                        <h3 class="font-24 font-weight-bold"><?php echo e(trans('home.organizations')); ?></h3>

                        <div class="row">
                            <?php $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organ): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('design_1.web.search.includes.user_card',['userCard' => $organ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(!empty($instructors) and count($instructors)): ?>
                    <div class="mt-48">
                        <h3 class="font-24 font-weight-bold"><?php echo e(trans('home.instructors')); ?></h3>

                        <div class="row">
                            <?php $__currentLoopData = $instructors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $instructor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('design_1.web.search.includes.user_card',['userCard' => $instructor], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

            </section>
        <?php endif; ?>
    </main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

    <script src="<?php echo e(getDesign1ScriptPath("search")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make("design_1.web.layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/search/index.blade.php ENDPATH**/ ?>