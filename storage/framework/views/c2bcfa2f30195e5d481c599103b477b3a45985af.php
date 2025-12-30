<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $statisticsBackgroundColor = "secondary";
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        if (!empty($contents['background_color'])) {
            $statisticsBackgroundColor = $contents['background_color'];
        }
    ?>

    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("statistics")); ?>">
    <?php $__env->stopPush(); ?>

    <div class="container">
        <div class="statistics-section">
            <div class="statistics-section__mask"></div>

            <div class="statistics-section__contents position-relative z-index-2" style="background-color: var(<?php echo e("--".$statisticsBackgroundColor); ?>); <?php echo e((!empty($contents['background']) ? "background-image: url({$contents['background']}); " : '')); ?>">
                <div class="row ">
                    <?php if(!empty($contents['statistics']) and is_array($contents['statistics'])): ?>
                        <?php $__currentLoopData = $contents['statistics']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statistic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($statistic['title']) and !empty($statistic['data_type']) and !empty($statistic['data_source'])): ?>
                                <?php
                                    $statisticComponentFrontMixins = (new \App\Mixins\LandingBuilder\StatisticComponentFrontMixins());
                                    $statisticValue = $statisticComponentFrontMixins->calculateStatisticData($statistic);
                                    $isNumber = is_numeric($statisticValue);
                                ?>

                                <div class="statistic-col col-6 col-lg-3 ">
                                    <div class="d-flex align-items-center gap-12">
                                        <?php if(!empty($statistic['icon'])): ?>
                                            <div class="statistics-section__icon-<?php echo e($loop->iteration); ?> d-flex-center size-72 rounded-circle">
                                                <?php echo e(svg("iconsax-{$statistic['icon']}", ['width' => '32px', 'height' => '32px', 'class' => "icons"])); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="">
                                            <?php if($isNumber): ?>
                                                <h4 class="js-statistic-value-counterup statistics-section__counter-value font-24 text-white" data-from="0" data-to="<?php echo e($statisticValue); ?>" data-speed="1000">0</h4>
                                            <?php else: ?>
                                                <h4 class="statistics-section__counter-value font-24 text-white"><?php echo e($statisticValue); ?></h4>
                                            <?php endif; ?>
                                            <p class="font-16 text-white mt-4"><?php echo e($statistic['title']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/vendors/counterup/jquery.counterup.min.js"></script>

    <script src="<?php echo e(getLandingComponentScriptPath("statistics")); ?>"></script>
<?php $__env->stopPush(); ?>

<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/statistics/index.blade.php ENDPATH**/ ?>