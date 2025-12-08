<?php $__env->startPush('libraries_top'); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <section class="section">
        <div class="section-header">
            <h1><?php echo e(trans('update.quizzes_related')); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e(trans('update.quizzes_related')); ?></div>
            </div>
        </div>

        <?php if(empty(getCertificateMainSettings("certificate_api_user_id")) or empty(getCertificateMainSettings("certificate_api_key"))): ?>
            <div class="my-2 alert alert-danger d-flex align-items-center justify-content-between">
                <p class=""><?php echo e(trans('update.certificate_credentials_not_set_hint')); ?></p>

                <a href="<?php echo e(getAdminPanelUrl("/certificates/settings")); ?>" target="_blank" class="text-white btn-link"><?php echo e(trans('admin/main.settings')); ?></a>
            </div>
        <?php endif; ?>

        <div class="section-body">
            <section class="card">
                <div class="card-body">
                    <form action="<?php echo e(getAdminPanelUrl()); ?>/certificates" method="get" class="row mb-0">

                        <div class="col-12 col-md-3">
                            <div class="form-group">
                                <label class="input-label"><?php echo e(trans('quiz.quiz_title')); ?></label>
                                <input type="text" name="quiz_title" class="form-control" value="<?php echo e(!empty($instructor) ? $instructor : ''); ?>"/>
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
                                <label class="input-label"><?php echo e(trans('admin/main.student')); ?></label>
                                <select name="student_ids[]" multiple="multiple" data-search-option="just_student_role" class="form-control search-user-select2"
                                        data-placeholder="Search students">

                                    <?php if(!empty($students) and $students->count() > 0): ?>
                                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($student->id); ?>" selected><?php echo e($student->full_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                           <div class="col-md-3 d-flex align-items-center ">
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
                                <h5 class="font-14 mb-0"><?php echo e(trans('update.quizzes_related')); ?></h5>
                                <p class="font-12 mt-4 mb-0 text-gray-500"><?php echo e(trans('update.manage_certificates_list')); ?></p>
                            </div>

                            <div class="d-flex align-items-center gap-12">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin_certificate_export_excel')): ?>
                                    <a href="<?php echo e(getAdminPanelUrl()); ?>/certificates/excel?<?php echo e(http_build_query(request()->all())); ?>" class="btn bg-white bg-hover-gray-100 border-gray-400 text-gray-500">
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
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table custom-table font-14"> 
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left"><?php echo e(trans('admin/main.title')); ?></th>
                                        <th class="text-left"><?php echo e(trans('quiz.student')); ?></th>
                                        <th class="text-left"><?php echo e(trans('admin/main.instructor')); ?></th>
                                        <th class="text-center"><?php echo e(trans('admin/main.grade')); ?></th>
                                        <th class="text-center"><?php echo e(trans('public.date_time')); ?></th>
                                        <th><?php echo e(trans('admin/main.action')); ?></th>
                                    </tr>

                                    <?php $__currentLoopData = $certificates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $certificate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center"><?php echo e($certificate->id); ?></td>
                                            <td class="text-left">
                                                <span><?php echo e($certificate->quiz->title); ?></span>
                                                <small class="d-block text-left text-gray-500"><?php echo e(!empty($certificate->quiz->webinar) ? $certificate->quiz->webinar->title : trans('update.delete_item')); ?>)</small>
                                            </td>
                                            <td class="text-left"><?php echo e($certificate->student->full_name); ?></td>
                                            <td class="text-left"><?php echo e($certificate->quiz->teacher->full_name); ?></td>
                                            <td class="text-center"><?php echo e($certificate->quizzesResult->user_grade); ?></td>
                                            <td class="text-center"><?php echo e(dateTimeFormat($certificate->created_at, 'j M Y')); ?></td>
                                            <td>
                                                <a href="<?php echo e(getAdminPanelUrl()); ?>/certificates/<?php echo e($certificate->id); ?>/download" target="_blank" class="btn-transparent text-primary" data-toggle="tooltip" title="<?php echo e(trans('quiz.download_certificate')); ?>">
                                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-lin-import-2'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-500 mr-2','width' => '20px','height' => '20px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                                                </a>
                                            </td>


                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            <?php echo e($certificates->appends(request()->input())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/certificates/lists.blade.php ENDPATH**/ ?>