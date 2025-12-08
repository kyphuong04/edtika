<div class="bg-white rounded-24 p-16">
    <?php if($file->online_viewer): ?>
        <div class="learning-page__file-player-card mb-16">
            <iframe src="/ViewerJS/index.html#<?php echo e($filePath); ?>" class="file-online-viewer rounded-sm <?php echo e($file->downloadable ? 'has-download-card' : ''); ?> " frameborder="0" allowfullscreen></iframe>
        </div>
    <?php elseif($file->storage == 'youtube'): ?>
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <div class="js-file-player-el plyr__video-embed w-100 h-100" id="fileVideo<?php echo e($file->id); ?>">
                <iframe
                    src="<?php echo e($file->file); ?>?origin=<?php echo e(url('/')); ?>&amp;iv_load_policy=0&amp;modestbranding=0&amp;playsinline=0&amp;showinfo=0&amp;rel=0&amp;enablejsapi=0"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    class="img-cover rounded-16"
                ></iframe>
            </div>
        </div>
    <?php elseif($file->storage == 'vimeo'): ?>
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <div class="js-file-player-el plyr__video-embed w-100 h-100" id="fileVideo<?php echo e($file->id); ?>">
                <iframe
                    src="<?php echo e($file->getVimeoPath()); ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media"
                    allowfullscreen
                    allowtransparency
                    allow="autoplay"
                    class="img-cover rounded-16"
                ></iframe>
            </div>
        </div>
    <?php elseif($file->storage == 'secure_host'): ?>
        <div class="learning-page__file-player-card js-learning-file-video-player-box mb-16 bg-gray-400" data-id="<?php echo e($file->id); ?>">
            <img src="<?php echo e($course->getImageCover()); ?>" class="img-cover rounded-12" alt="<?php echo e($course->title); ?>"/>

            <div class="file-player-button js-learning-file-video-player-btn d-flex-center rounded-circle size-92 cursor-pointer" data-id="<?php echo e($file->id); ?>">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-play'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
            </div>
        </div>
    <?php elseif(in_array($file->storage, ['google_drive', 'iframe'])): ?>
        <div class="learning-page__file-player-card mb-16 ">
            <?php echo $file->file; ?>

        </div>
    <?php elseif($file->storage == "upload_archive"): ?>
        <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 px-48 mb-16">
            <div class="">
                <img src="/assets/design_1/img/courses/learning_page/file_downloadable.svg" alt="" class="img-fluid" width="285px" height="212px">
            </div>
            <h4 class="font-16 mt-12"><?php echo e(trans('update.show_html_file')); ?></h4>
            <div class="mt-8 font-12 text-gray-500"><?php echo e(trans('update.you_can_show_online_the_file_from_the_following_link')); ?></div>


            <a href="<?php echo e($course->getUrl()); ?>/file/<?php echo e($file->id); ?>/showHtml" class="btn btn-primary btn-lg mt-24" target="_blank"><?php echo e(trans('update.show_html_file')); ?></a>
        </div>
    <?php elseif($file->downloadable and !$file->isVideo()): ?>
        <div class="d-flex-center flex-column text-center border-gray-200 rounded-12 py-160 px-48 mb-16">
            <div class="">
                <img src="/assets/design_1/img/courses/learning_page/file_downloadable.svg" alt="" class="img-fluid" width="285px" height="212px">
            </div>
            <h4 class="font-16 mt-12"><?php echo e(trans('update.download_the_file')); ?></h4>
            <div class="mt-8 font-12 text-gray-500"><?php echo e(trans('update.you_can_download_the_file_from_the_following_link')); ?></div>

            <div class="d-flex align-items-center gap-40 mt-16 ">
                <div class="d-flex align-items-center text-left">
                    <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-document-1'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <div class="ml-8">
                        <span class="d-block font-12 text-gray-400"><?php echo e(trans('public.file_type')); ?></span>
                        <span class="d-block font-14 text-gray-500 font-weight-bold mt-2"><?php echo e(trans("update.file_type_{$file->file_type}")); ?></span>
                    </div>
                </div>

                <?php if($file->volume > 0): ?>
                    <div class="d-flex align-items-center text-left">
                        <div class="d-flex-center size-40 rounded-circle bg-gray-100">
                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-ram'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                        </div>
                        <div class="ml-8">
                            <span class="d-block font-12 text-gray-400"><?php echo e(trans('public.volume')); ?></span>
                            <span class="d-block font-14 text-gray-500 font-weight-bold mt-2"><?php echo e($file->getVolume()); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <a href="<?php echo e($course->getUrl()); ?>/file/<?php echo e($file->id); ?>/download" class="btn btn-primary btn-lg mt-24" target="_blank"><?php echo e(trans('home.download')); ?></a>
        </div>
    <?php elseif($file->isVideo()): ?>
        <div class="learning-page__file-player-card mb-16 bg-gray-400">
            <video id="fileVideo<?php echo e($file->id); ?>" class="js-file-player-el plyr-io-video" controls preload="auto" width="100%" height="426" data-poster="<?php echo e($course->getImageCover()); ?>">
                <source src="<?php echo e($file->file); ?>" type="video/mp4"/>
            </video>
        </div>
    <?php endif; ?>

    
    <?php echo $__env->make('design_1.web.courses.learning_page.includes.contents.includes.item_footer_actions_and_desc', [
        'item' => $file,
        'itemType' => 'file',
        'courseSlug' => $course->slug,
        'courseUrl' => $course->getUrl(),
        'itemHasPersonalNote' => $hasPersonalNote
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/web/courses/learning_page/includes/contents/file.blade.php ENDPATH**/ ?>