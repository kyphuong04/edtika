<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
        $posts = $frontComponentsDataMixins->getBlogData();
    ?>

    <?php if($posts->isNotEmpty()): ?>
        <?php $__env->startPush('styles_top'); ?>
            <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("blog")); ?>">
        <?php $__env->stopPush(); ?>

        <div class="blog-section position-relative" <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>

            <div class="container">
                <div class="d-flex-center flex-column text-center">
                    <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['pre_title'])): ?>
                        <div class="d-inline-flex-center py-8 px-16 rounded-8 border-primary bg-primary-20 font-12 text-primary"><?php echo e($contents['main_content']['pre_title']); ?></div>
                    <?php endif; ?>

                    <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['title'])): ?>
                        <h2 class="mt-12 font-32 text-dark"><?php echo e($contents['main_content']['title']); ?></h2>
                    <?php endif; ?>

                    <?php if(!empty($contents['main_content']) and !empty($contents['main_content']['description'])): ?>
                        <p class="mt-16 font-16 text-gray-500"><?php echo nl2br($contents['main_content']['description']); ?></p>
                    <?php endif; ?>
                </div>

                
                <?php
                    $firstPost = $posts->first();
                    $posts = $posts->slice(1); // other last 4 item
                ?>

                <div class="row mt-4">
                    <div class="col-12 col-lg-6 mt-24">
                        <?php echo $__env->make('landingBuilder.front.components.blog.post_card', ['post' => $firstPost, 'className' => 'one-large-col', 'showPostStats' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row">
                            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $postRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-6 mt-24">
                                    <?php echo $__env->make('landingBuilder.front.components.blog.post_card', ['post' => $postRow, 'className' => 'four-small-col'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <?php if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label'])): ?>
                    <div class="d-flex-center flex-column text-center mt-24">

                        <a href="<?php echo e(!empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : ''); ?>" class="btn-flip-effect btn btn-primary btn-xlg text-white gap-8" data-text="<?php echo e($contents['main_content']['button']['label']); ?>">
                            <?php if(!empty($contents['main_content']['button']['icon'])): ?>
                                <?php echo e(svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                            <?php endif; ?>

                            <span class="btn-flip-effect__text text-white"><?php echo e($contents['main_content']['button']['label']); ?></span>
                        </a>

                    </div>
                <?php endif; ?>

            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/blog/index.blade.php ENDPATH**/ ?>