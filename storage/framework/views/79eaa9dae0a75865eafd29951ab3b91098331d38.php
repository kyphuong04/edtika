

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1>IELTS Tests</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a></div>
                <div class="breadcrumb-item">IELTS Tests</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Total Tests</span>
                            <div class="d-flex-center size-48 bg-primary-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-primary','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($tests->total()); ?></h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Mock Tests</span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-document-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-success','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($tests->where('type', 'mock')->count()); ?></h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Practice Tests</span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-note-text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-accent','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($tests->where('type', 'practice')->count()); ?></h5>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8">Pending Approval</span>
                            <div class="d-flex-center size-48 bg-warning-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clock'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-warning','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($tests->where('status', 'pending_approval')->count()); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">
            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form action="<?php echo e(route('admin.ielts_tests.index')); ?>" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.search')); ?></label>
                                <input type="text" class="form-control" name="search" value="<?php echo e(request()->get('search')); ?>" placeholder="Search test title...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label">Type</label>
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="mock" <?php echo e(request()->get('type') == 'mock' ? 'selected' : ''); ?>>Mock Test</option>
                                    <option value="practice" <?php echo e(request()->get('type') == 'practice' ? 'selected' : ''); ?>>Practice Test</option>
                                    <option value="diagnostic" <?php echo e(request()->get('type') == 'diagnostic' ? 'selected' : ''); ?>>Diagnostic Test</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="draft" <?php echo e(request()->get('status') == 'draft' ? 'selected' : ''); ?>>Draft</option>
                                    <option value="pending_approval" <?php echo e(request()->get('status') == 'pending_approval' ? 'selected' : ''); ?>>Pending Approval</option>
                                    <option value="approved" <?php echo e(request()->get('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="published" <?php echo e(request()->get('status') == 'published' ? 'selected' : ''); ?>>Published</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-3 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-primary w-100"><?php echo e(trans('admin/main.show_results')); ?></button>
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
                                <p class="font-12 mt-4 mb-0 text-gray-500">Manage all IELTS tests in a single place</p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                <a href="<?php echo e(route('admin.ielts_tests.create')); ?>" class="btn btn-primary">
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
                                    <span class="ml-4 font-12">Create New Test</span>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <thead>
                                        <tr>
                                            <th class="text-left">Title</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Format</th>
                                            <th class="text-center">Duration</th>
                                            <th class="text-center">Attempts</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Created By</th>
                                            <th><?php echo e(trans('admin/main.actions')); ?></th>
                                        </tr> 
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <span class="text-black font-weight-bold"><?php echo e($test->title); ?></span>
                                                    <?php if($test->target_band_min && $test->target_band_max): ?>
                                                        <small class="d-block text-gray-700">Target: Band <?php echo e($test->target_band_min); ?> - <?php echo e($test->target_band_max); ?></small>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php if($test->type === 'mock'): ?>
                                                        <span class="badge-status  text-black bg-primary-30" style="min-width: 90px; display: inline-block;">Mock</span>
                                                    <?php elseif($test->type === 'practice'): ?>
                                                        <span class="badge-status text-black bg-info-30" style="min-width: 90px; display: inline-block;">Practice</span>
                                                    <?php else: ?>
                                                        <span class="badge-status text-black bg-gray-200" style="min-width: 90px; display: inline-block;">Diagnostic</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge-status text-dark bg-gray-300" style="min-width: 90px; display: inline-block;"><?php echo e(ucfirst($test->format)); ?></span>
                                                </td>

                                                <td class="text-center">
                                                    <?php echo e($test->total_duration); ?> min
                                                    <small class="d-block text-gray-500">
                                                        <?php if($test->has_listening): ?> L:<?php echo e($test->listening_duration); ?> <?php endif; ?>
                                                        <?php if($test->has_reading): ?> R:<?php echo e($test->reading_duration); ?> <?php endif; ?>
                                                        <?php if($test->has_writing): ?> W:<?php echo e($test->writing_duration); ?> <?php endif; ?>
                                                        <?php if($test->has_speaking): ?> S:<?php echo e($test->speaking_duration); ?> <?php endif; ?>
                                                    </small>
                                                </td>

                                                <td class="text-center">
                                                    <?php echo e($test->attempts->count()); ?>

                                                </td>

                                                <td class="text-center">
                                                    <?php if($test->status === 'published'): ?>
                                                        <span class="badge-status text-black bg-success-30" style="min-width: 90px; display: inline-block;">Published</span>
                                                    <?php elseif($test->status === 'pending_approval'): ?>
                                                        <span class="badge-status text-black bg-warning-30" style="min-width: 90px; display: inline-block;">Pending</span>
                                                    <?php elseif($test->status === 'approved'): ?>
                                                        <span class="badge-status text-black bg-info-30" style="min-width: 90px; display: inline-block;">Approved</span>
                                                    <?php elseif($test->status === 'rejected'): ?>
                                                        <span class="badge-status text-black bg-danger-30" style="min-width: 90px; display: inline-block;">Rejected</span>
                                                    <?php else: ?>
                                                        <span class="badge-status text-black bg-gray-200" style="min-width: 90px; display: inline-block;">Draft</span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="text-center">
                                                    <?php echo e($test->creator->full_name ?? 'Unknown'); ?>

                                                </td>

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
                                                            <a href="<?php echo e(route('admin.ielts_tests.sections', $test->id)); ?>" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-hierarchy-square'); ?>
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
                                                                <span class="text-gray-500 font-14">Manage Sections</span>
                                                            </a>

                                                            <a href="<?php echo e(route('admin.ielts_tests.edit', $test->id)); ?>" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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

                                                            <?php if($test->canBeEdited()): ?>
                                                                <?php echo $__env->make('admin.includes.delete_button',[
                                                                    'url' => route('admin.ielts_tests.destroy', $test->id),
                                                                    'btnClass' => 'dropdown-item text-danger mb-0 py-3 px-0 font-14',
                                                                    'btnText' => trans("admin/main.delete"),
                                                                    'btnIcon' => 'trash',
                                                                    'iconType' => 'lin',
                                                                    'iconClass' => 'text-danger mr-2',
                                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($tests->appends(request()->input())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/admin/ielts_tests/index.blade.php ENDPATH**/ ?>