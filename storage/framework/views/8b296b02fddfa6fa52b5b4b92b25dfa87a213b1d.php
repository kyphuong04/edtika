<div class="js-content-form change-chapter-form mt-16" data-action="/panel/chapters/change">

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

        <h4 class="font-14 font-weight-bold mt-12"><?php echo e(trans('update.change_chapter')); ?></h4>
        <p class="font-12 text-gray-500 mt-4"><?php echo e(trans('update.create_a_new_section_and_include_different_materials')); ?></p>
    </div>

    <input type="hidden" name="ajax[webinar_id]" class="" value="<?php echo e($webinar->id); ?>">
    <input type="hidden" name="ajax[item_id]" class="js-item-id" value="">
    <input type="hidden" name="ajax[item_type]" class="js-item-type" value="">

    <div class="form-group">
        <label class="form-group-label"><?php echo e(trans('public.chapter')); ?></label>

        <select name="ajax[chapter_id]" class="js-ajax-chapter_id form-control">
            <option value=""><?php echo e(trans('update.select_chapter')); ?></option>

            <?php if(!empty($webinar->chapters) and count($webinar->chapters)): ?>
                <?php $__currentLoopData = $webinar->chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($chapter->id); ?>"><?php echo e($chapter->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </select>
    </div>

</div>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/webinars/create/modals/change_chapter.blade.php ENDPATH**/ ?>