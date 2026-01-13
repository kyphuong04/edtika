<div class="js-content-form chapter-form mt-16" data-action="/panel/chapters/<?php echo e(!empty($chapter) ? $chapter->id.'/update' : 'store'); ?>">

    <?php if(empty($chapter)): ?>
        <div class="d-flex-center flex-column mt-12 mb-24">
            <div class="d-flex-center size-64 rounded-16 bg-primary">
                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-category'); ?>
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

            <h4 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.new_course_chapter')); ?></h4>
            <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.create_a_new_section_and_include_different_materials')); ?></p>
        </div>
    <?php endif; ?>

    <input type="hidden" name="ajax[chapter][webinar_id]" class="js-chapter-webinar-id" value="<?php echo e(!empty($chapter) ? $chapter->webinar_id : ''); ?>">
    

    <?php echo $__env->make('design_1.panel.includes.locale.locale_select',[
        'itemRow' => !empty($chapter) ? $chapter : null,
        'withoutReloadLocale' => true,
        'extraClass' => 'js-webinar-content-locale',
        'extraData' => "data-webinar-id='".(!empty($chapter) ? $chapter->webinar_id : '')."'  data-id='".(!empty($chapter) ? $chapter->id : '')."'  data-relation='chapters' data-fields='title'"
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.chapter_title')); ?></label>
        <span class="has-translation bg-gray-300 rounded-8 p-8"><?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-translate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?></span>
        <input type="text" name="ajax[chapter][title]" class="js-ajax-title form-control" value="<?php echo e(!empty($chapter) ? $chapter->title : ''); ?>"/>
        <span class="invalid-feedback"></span>
    </div>

    <div class="form-group d-flex align-items-center">
        <div class="custom-switch mr-8">
            <input id="statusSwitch" type="checkbox" name="ajax[chapter][status]" class="custom-control-input" <?php echo e((!empty($chapter) and $chapter->status == \App\Models\WebinarChapter::$chapterActive) ? 'checked' :  ''); ?>>
            <label class="custom-control-label cursor-pointer" for="statusSwitch"></label>
        </div>

        <div class="">
            <label class="cursor-pointer" for="statusSwitch"><?php echo e(trans('public.active')); ?></label>
        </div>
    </div>

    <?php if(getFeaturesSettings('sequence_content_status')): ?>
        <div class="form-group d-flex align-items-center">
            <div class="custom-switch mr-8">
                <input id="checkAllContentsPassSwitch_record" type="checkbox" name="ajax[chapter][check_all_contents_pass]" class="custom-control-input" <?php echo e((!empty($chapter) and $chapter->check_all_contents_pass) ? 'checked' :  ''); ?>>
                <label class="custom-control-label cursor-pointer" for="checkAllContentsPassSwitch_record"></label>
            </div>

            <div class="">
                <label class="cursor-pointer" for="checkAllContentsPassSwitch_record"><?php echo e(trans('update.check_all_contents_pass')); ?></label>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/modals/chapter.blade.php ENDPATH**/ ?>