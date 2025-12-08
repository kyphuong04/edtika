<section class="mt-30">
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="section-title after-line"><?php echo e(trans('update.related_courses')); ?></h2>
        <button id="addRelatedCourse" type="button" class="btn btn-primary btn-sm mt-3" data-path="<?php echo e(getAdminPanelUrl("/relatedCourses/get-form")); ?>?item=<?php echo e($relatedCourseItemId); ?>&item_type=<?php echo e($relatedCourseItemType); ?>"><?php echo e(trans('update.add_related_courses')); ?></button>
    </div>

    <div class="row mt-10">
        <div class="col-12">
            <?php if(!empty($relatedCourses) and !$relatedCourses->isEmpty()): ?>
                <div class="table-responsive">
                    <table class="table custom-table border-0 text-center font-14">

                        <tr>
                            <th><?php echo e(trans('public.title')); ?></th>
                            <th class="text-left"><?php echo e(trans('public.instructor')); ?></th>
                            <th><?php echo e(trans('public.price')); ?></th>
                            <th><?php echo e(trans('public.publish_date')); ?></th>
                            <th width="80px"><?php echo e(trans('admin/main.action')); ?></th>
                        </tr>

                        <?php $__currentLoopData = $relatedCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relatedCourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!empty($relatedCourse->course->title)): ?>
                                <tr>
                                    <th><?php echo e($relatedCourse->course->title); ?></th>
                                    <td class="text-left"><?php echo e($relatedCourse->course->teacher->full_name); ?></td>
                                    <td><?php echo e(handlePrice($relatedCourse->course->price)); ?></td>
                                    <td><?php echo e(dateTimeFormat($relatedCourse->course->created_at,'j F Y | H:i')); ?></td>

                                    <td>
                                         <div class="btn-group dropdown table-actions position-relative">
                                             <button type="button" class="btn-transparent dropdown-toggle" data-toggle="dropdown">
                                                 <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-more'); ?>
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
                                             </button>

                                             <div class="dropdown-menu dropdown-menu-right">
                                                 <button type="button"
                                                         class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4 js-edit-related-course"
                                                         data-path="<?php echo e(getAdminPanelUrl("/relatedCourses/{$relatedCourse->id}/edit")); ?>?item=<?php echo e($relatedCourseItemId); ?>&item_type=<?php echo e($relatedCourseItemType); ?>">
                                                     <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-edit-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                     <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.edit')); ?></span>
                                                 </button>

                                                 <?php echo $__env->make('admin.includes.delete_button',[
                                                     'url' => getAdminPanelUrl().'/relatedCourses/'.$relatedCourse->id.'/delete',
                                                     'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                     'btnText' => trans("admin/main.delete"),
                                                     'btnIcon' => 'trash',
                                                     'iconType' => 'lin',
                                                     'iconClass' => 'text-danger mr-2',
                                                 ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                             </div>
                                         </div>
                                    </td>




                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </table>
                </div>
            <?php else: ?>
                <div class="d-flex-center flex-column px-32 py-120 text-center">
                    <div class="d-flex-center size-64 rounded-12 bg-primary-30">
                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-arrange-circle-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '32px','height' => '32px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    </div>
                    <h3 class="font-16 font-weight-bold mt-12"><?php echo e(trans('update.related_courses_no_result')); ?></h3>
                    <p class="mt-4 font-12 text-gray-500"><?php echo trans('update.related_courses_no_result_hint'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts_bottom'); ?>
    <!-- Modal -->
    <script src="/assets/admin/js/parts/related_courses.min.js"></script>
<?php $__env->stopPush(); ?>
<?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/webinars/relatedCourse/add_related_course.blade.php ENDPATH**/ ?>