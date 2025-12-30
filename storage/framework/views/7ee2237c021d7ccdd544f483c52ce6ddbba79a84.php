<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.feature_webinars')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.feature_webinars')); ?></div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form action="<?php echo e(getAdminPanelUrl()); ?>/webinars/features" method="get" class="row mb-0">
                        <div class="col-12 col-lg-4">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"><?php echo e(trans('admin/main.page')); ?></label>
                                        <select class="custom-select" name="page">
                                            <option selected disabled><?php echo e(trans('admin/main.select_page')); ?></option>
                                            <option value=""><?php echo e(trans('admin/main.all')); ?></option>
                                            <?php $__currentLoopData = \App\Models\FeatureWebinar::$pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($page); ?>" <?php if(request()->get('page', null) == $page): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.page_'.$page)); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                                        <select class="custom-select" name="status">
                                            <option selected disabled><?php echo e(trans('admin/main.status')); ?></option>
                                            <option value=""><?php echo e(trans('admin/main.all')); ?></option>
                                            <option value="pending" <?php if(request()->get('status', null) == 'pending'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.pending')); ?></option>
                                            <option value="publish" <?php if(request()->get('status', null) == 'publish'): ?> selected="selected" <?php endif; ?>><?php echo e(trans('admin/main.published')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label"><?php echo e(trans('admin/main.webinar_title')); ?></label>
                                        <input type="text" name="webinar_title" class="form-control" value="<?php echo e(request()->get('webinar_title',null)); ?>"/>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label class="input-label"><?php echo e(trans('public.category')); ?></label>

                                        <select id="categories" class="custom-select" name="category_id">
                                            <option <?php echo e(!empty($webinar) ? '' : 'selected'); ?> disabled><?php echo e(trans('public.choose_category')); ?></option>
                                            <option value=""><?php echo e(trans('admin/main.all')); ?></option>
                                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if(!empty($category->subCategories) and count($category->subCategories)): ?>
                                                    <optgroup label="<?php echo e($category->title); ?>">
                                                        <?php $__currentLoopData = $category->subCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subCategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($subCategory->id); ?>" <?php echo e((request()->get('category_id',null) == $subCategory->id) ? 'selected' : ''); ?>><?php echo e($subCategory->title); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </optgroup>
                                                <?php else: ?>
                                                    <option value="<?php echo e($category->id); ?>" <?php echo e((!empty($webinar) and $webinar->category_id == $category->id) ? 'selected' : ''); ?>><?php echo e($category->title); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-center ">
                     <button type="submit" class="btn btn-primary btn-block btn-lg"><?php echo e(trans('admin/main.show_results')); ?></button>
                </div>
                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                    <div class="card-header justify-content-between">
                            
                            <div>
                               <h5 class="font-14 mb-0"><?php echo e($pageTitle); ?></h5>
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_items_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_feature_webinars_export_excel')): ?>
                                <div class="d-flex align-items-center gap-12">
                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
                                        <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                        <span class="ml-4 font-12"><?php echo e(trans('admin/main.export_xls')); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_feature_webinars_create')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features/create" target="_blank" class="btn btn-primary">
                                       <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-add'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-white','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                       <span class="ml-4 font-12"><?php echo e(trans('admin/main.add_new')); ?></span>
                                   </a>
                               <?php endif; ?>

                            </div>
                           
               </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th><?php echo e(trans('admin/main.webinar_title')); ?></th>
                                        <th><?php echo e(trans('admin/main.webinar_status')); ?></th>
                                        <th class="text-center"><?php echo e(trans('public.date')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.instructor')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.category')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.page')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.status')); ?></th>
                                        <th><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                        <tr>
                                            <td>
                                                <a class="text-dark" href="<?php echo e($feature->webinar->getUrl()); ?>" target="_blank"><?php echo e($feature->webinar->title); ?></a>
                                            </td>

                                            <td class="text-center"><?php echo e(trans('admin/main.'.$feature->webinar->status)); ?></td>

                                            <td class="text-center"><?php echo e(dateTimeFormat($feature->updated_at, 'j M Y | H:i')); ?></td>
                                            <td class="text-center"><?php echo e($feature->webinar->teacher->full_name); ?></td>
                                            <td class="text-center"><?php echo e($feature->webinar->category->title); ?></td>
                                            <td class="text-center"><?php echo e(trans('admin/main.page_'.$feature->page)); ?></td>
                                            <td class="text-center">
                                            <span class="badge-status <?php echo e(($feature->status == 'publish') ? 'text-success bg-success-30' : 'text-warning bg-warning-30'); ?>"><?php echo e(($feature->status == 'publish') ? trans('admin/main.published') : trans('admin/main.pending')); ?></span>
                                            </td>
                                            <td width="150">
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
            <!-- Publish/Pending Toggle -->
            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features/<?php echo e($feature->id); ?>/<?php echo e(($feature->status == 'publish') ? 'pending' : 'publish'); ?>" 
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                <?php if($feature->status == 'publish'): ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye-slash'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="text-warning"><?php echo e(trans('admin/main.pending')); ?></span>
                <?php else: ?>
                    <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-eye'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success mr-2','width' => '18px','height' => '18px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                    <span class="text-success"><?php echo e(trans('admin/main.publish')); ?></span>
                <?php endif; ?>
            </a>

            <!-- Edit Action -->
            <a href="<?php echo e(getAdminPanelUrl()); ?>/webinars/features/<?php echo e($feature->id); ?>/edit" 
               class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
            </a>

            <!-- Delete Action -->
            <?php echo $__env->make('admin.includes.delete_button',[
                'url' => getAdminPanelUrl().'/webinars/features/'. $feature->id .'/delete',
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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($features->appends(request()->input())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/webinars/feature/lists.blade.php ENDPATH**/ ?>