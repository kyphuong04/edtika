<?php if(!empty($landingComponent) and $landingComponent->enable): ?>
    <?php
        $contents = [];
        if (!empty($landingComponent->content)) {
            $contents = json_decode($landingComponent->content, true);
        }

        $frontComponentsDataMixins = (new \App\Mixins\LandingBuilder\FrontComponentsDataMixins());
    ?>


    <?php $__env->startPush('styles_top'); ?>
        <link rel="stylesheet" href="<?php echo e(getLandingComponentStylePath("instructors")); ?>">
    <?php $__env->stopPush(); ?>

    <div class="instructors-section position-relative" <?php if(!empty($contents['background'])): ?> style="background-image: url(<?php echo e($contents['background']); ?>)" <?php endif; ?>>
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
                $ids = [];
                if (!empty($contents['specific_instructors']) and is_array($contents['specific_instructors'])) {
                    foreach ($contents['specific_instructors'] as $instructorData) {
                        if (!empty($instructorData['instructor_id'])) {
                            $ids[] = $instructorData['instructor_id'];
                        }
                    }
                }

                $instructors = $frontComponentsDataMixins->getUsersByIds($ids, \App\Models\Role::$teacher);
            ?>

            <?php if($instructors->isNotEmpty()): ?>
                <div class="d-grid grid-columns-auto grid-lg-columns-3 gap-32 mt-40">
                    <?php echo $__env->make('design_1.web.instructors.components.cards.grids.index', ['instructors' => $instructors, 'gridCardClassName' => ""], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php endif; ?>

            
            <div class="d-flex-center flex-column text-lg-center mt-48">
                <?php if(!empty($contents['cta_section'])): ?>
                    <div class="d-flex align-items-lg-center gap-4">
                        <?php if(!empty($contents['cta_section']['icon'])): ?>
                            <div class="d-flex-center size-24">
                                <?php echo e(svg("iconsax-{$contents['cta_section']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons text-dark"])); ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex flex-column font-16 flex-lg-row align-items-lg-center gap-4">
                            <?php if(!empty($contents['cta_section']['title_bold_text'])): ?>
                                <h5 class="font-14"><?php echo e($contents['cta_section']['title_bold_text']); ?></h5>
                            <?php endif; ?>

                            <?php if(!empty($contents['cta_section']['title_regular_text'])): ?>
                                <div class="font-16"><?php echo e($contents['cta_section']['title_regular_text']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(!empty($contents['cta_section']['title_regular_text'])): ?>
                        <div class="font-16 text-gray-500 mt-16"><?php echo e($contents['cta_section']['title_regular_text']); ?></div>
                    <?php endif; ?>
                <?php endif; ?>

                
                <?php if(!empty($contents['main_content']['button']) and !empty($contents['main_content']['button']['label'])): ?>
                    <a href="<?php echo e(!empty($contents['main_content']['button']['url']) ? $contents['main_content']['button']['url'] : ''); ?>" class="btn-flip-effect btn btn-primary btn-xlg gap-8 mt-24 text-white" data-text="<?php echo e($contents['main_content']['button']['label']); ?>">
                        <?php if(!empty($contents['main_content']['button']['icon'])): ?>
                            <?php echo e(svg("iconsax-{$contents['main_content']['button']['icon']}", ['width' => '24px', 'height' => '24px', 'class' => "icons"])); ?>
                        <?php endif; ?>

                        <span class="btn-flip-effect__text text-white"><?php echo e($contents['main_content']['button']['label']); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/landingBuilder/front/components/instructors/index.blade.php ENDPATH**/ ?>