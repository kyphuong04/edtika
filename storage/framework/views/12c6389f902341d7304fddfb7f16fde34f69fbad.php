<?php $__env->startPush('libraries_top'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('admin/main.quizzes')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('admin/main.quizzes')); ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_quizzes')); ?></span>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalQuizzes); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.active_quizzes')); ?></span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-clipboard-tick'); ?>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalActiveQuizzes); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div> 
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_students')); ?></span>
                            <div class="d-flex-center size-48 bg-accent-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-profile-2user'); ?>
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
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalStudents); ?></h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card-statistic">
                    <div class="card-statistic__mask"></div>
                    <div class="card-statistic__wrap">
                        <div class="d-flex align-items-start justify-content-between">
                            <span class="text-gray-500 mt-8"><?php echo e(trans('admin/main.total_passed_students')); ?></span>
                            <div class="d-flex-center size-48 bg-success-30 rounded-12">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bul-user-tick'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-whatsapp','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                        </div>
                        <h5 class="font-24 mt-12 line-height-1 text-black"><?php echo e($totalPassedStudents); ?></h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card mt-32">
                <div class="card-body pb-4">
                    <form action="<?php echo e(getAdminPanelUrl()); ?>/quizzes" method="get" class="row mb-0">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.search')); ?></label>
                                <input type="text" class="form-control" name="title" value="<?php echo e(request()->get('title')); ?>">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.start_date')); ?></label>
                                <div class="input-group">
                                    <input type="date" id="fsdate" class="text-center form-control" name="from" value="<?php echo e(request()->get('from')); ?>" placeholder="Start Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.end_date')); ?></label>
                                <div class="input-group">
                                    <input type="date" id="lsdate" class="text-center form-control" name="to" value="<?php echo e(request()->get('to')); ?>" placeholder="End Date">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.filters')); ?></label>
                                <select name="sort" data-plugin-selectTwo class="form-control populate">
                                    <option value=""><?php echo e(trans('admin/main.filter_type')); ?></option>
                                    <option value="have_certificate" <?php if(request()->get('sort') == 'have_certificate'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.quizzes_have_certificate')); ?></option>
                                    <option value="students_count_asc" <?php if(request()->get('sort') == 'students_count_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.students_ascending')); ?></option>
                                    <option value="students_count_desc" <?php if(request()->get('sort') == 'students_count_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.students_descending')); ?></option>
                                    <option value="passed_count_asc" <?php if(request()->get('sort') == 'passed_count_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.passed_students_ascending')); ?></option>
                                    <option value="passed_count_desc" <?php if(request()->get('sort') == 'passed_count_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.passes_students_descending')); ?></option>
                                    <option value="grade_avg_asc" <?php if(request()->get('sort') == 'grade_avg_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.grades_average_ascending')); ?></option>
                                    <option value="grade_avg_desc" <?php if(request()->get('sort') == 'grade_avg_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.grades_average_descending')); ?></option>
                                    <option value="created_at_asc" <?php if(request()->get('sort') == 'created_at_asc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.create_date_ascending')); ?></option>
                                    <option value="created_at_desc" <?php if(request()->get('sort') == 'created_at_desc'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.create_date_descending')); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.instructor')); ?></label>
                                <select name="teacher_ids[]" multiple="multiple" data-search-option="just_teacher_role" class="form-control search-user-select2"
                                        data-placeholder="Search teachers">

                                    <?php if(!empty($teachers) and $teachers->count() > 0): ?>
                                        <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>" selected><?php echo e($teacher->full_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.class')); ?></label>
                                <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                        data-placeholder="Search classes">

                                    <?php if(!empty($webinars) and $webinars->count() > 0): ?>
                                        <?php $__currentLoopData = $webinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($webinar->id); ?>" selected><?php echo e($webinar->title); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('admin/main.status')); ?></label>
                                <select name="statue" data-plugin-selectTwo class="form-control populate">
                                    <option value=""><?php echo e(trans('admin/main.all_status')); ?></option>
                                    <option value="active" <?php if(request()->get('status') == 'active'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.active')); ?></option>
                                    <option value="inactive" <?php if(request()->get('status') == 'inactive'): ?> selected <?php endif; ?>><?php echo e(trans('admin/main.inactive')); ?></option>
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
                               <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_all_quizzes_in_a_single_place')); ?></p>
                           </div>
                           
                            <div class="d-flex align-items-center gap-12">

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_lists_excel')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                               <?php endif; ?>

                               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_create')): ?>
                                   <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes/create" target="_blank" class="btn btn-primary">
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
                                       <span class="ml-4 font-12"><?php echo e(trans('quiz.new_quiz')); ?></span>
                                   </a>
                               <?php endif; ?>

                            </div>
                           
                       </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14">
                                    <tr>
                                        <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.instructor')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.question_count')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.students_count')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.average_grade')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.certificate')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.status')); ?></th>
                                        <th><?php echo e(trans('admin/main.actions')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $quizzes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quiz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <span><?php echo e($quiz->title); ?></span>
                                                <?php if(!empty($quiz->webinar)): ?>
                                                    <small class="d-block text-left text-gray-500"><?php echo e($quiz->webinar->title); ?></small>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-left"><?php echo e($quiz->teacher->full_name); ?></td>

                                            <td class="text-center">
                                            <span><?php echo e($quiz->quizQuestions->count()); ?></span>
                                                <?php if(($quiz->display_limited_questions and !empty($quiz->display_number_of_questions))): ?>
                                                    <span class="d-block text-gray-500 font-12">(<?php echo e(trans('public.active')); ?>: <?php echo e($quiz->display_number_of_questions); ?>)</span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <span><?php echo e($quiz->quizResults->pluck('user_id')->count()); ?></span>
                                                <span class="d-block text-gray-500 font-12">(<?php echo e(trans('admin/main.passed')); ?>: <?php echo e($quiz->quizResults->where('status','passed')->count()); ?>)</span>
                                            </td>

                                            <td class="text-center"><?php echo e(round($quiz->quizResults->avg('user_grade'),2)); ?> </td>

                                            <td class="text-center">
                                                <?php if($quiz->certificate): ?>
                                                    <a class="text-success fas fa-check"></a>
                                                <?php else: ?>
                                                    <a class="text-danger fas fa-times"></a>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <?php if($quiz->status === \App\Models\Quiz::ACTIVE): ?>
                                                <span class="badge-status text-success bg-success-30"><?php echo e(trans('admin/main.active')); ?></span>
                                                <?php else: ?>
                                                <span class="badge-status text-danger bg-danger-30"><?php echo e(trans('admin/main.inactive')); ?></span>
                                                <?php endif; ?>
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
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_results')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes/<?php echo e($quiz->id); ?>/results" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
                                                            <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-chart'); ?>
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
                                                                <span class="text-gray-500 font-14"><?php echo e(trans('admin/main.quiz_results')); ?></span>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_edit')): ?>
                                                            <a href="<?php echo e(getAdminPanelUrl()); ?>/quizzes/<?php echo e($quiz->id); ?>/edit" class="dropdown-item d-flex align-items-center mb-3 py-3 px-0 gap-4">
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
                                                        <?php endif; ?>

                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_quizzes_delete')): ?>

                                                            <?php echo $__env->make('admin.includes.delete_button',[
                                                           'url' => getAdminPanelUrl().'/quizzes/'.$quiz->id.'/delete',
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

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($quizzes->appends(request()->input())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/quizzes/lists.blade.php ENDPATH**/ ?>