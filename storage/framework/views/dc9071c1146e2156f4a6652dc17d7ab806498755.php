<?php $__env->startSection('content'); ?>
    <div class="row pb-56">
        <div class="col-12 col-lg-6">
            <div class="bg-white p-16 rounded-24">
                <h3 class="font-14 font-weight-bold"><?php echo e(trans('update.general_information')); ?></h3>

                <form method="post" action="/panel/support/store" class="mt-24" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>


                    <div class="form-group ">
                        <label class="form-group-label"><?php echo e(trans('public.type')); ?></label>

                        <select name="type" id="supportType" class="form-control select2  <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-allow-clear="false" data-search="false" data-minimum-results-for-search="Infinity">
                            <option selected disabled></option>
                            <option value="course_support" <?php if($errors->has('webinar_id')): ?> selected <?php endif; ?>><?php echo e(trans('panel.course_support')); ?></option>
                            <option value="platform_support" <?php if($errors->has('department_id')): ?> selected <?php endif; ?>><?php echo e(trans('panel.platform_support')); ?></option>
                        </select>

                        <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div id="departmentInput" class="form-group  <?php if(!$errors->has('department_id')): ?> d-none <?php endif; ?>">
                        <label class="form-group-label"><?php echo e(trans('panel.department')); ?></label>

                        <select name="department_id" id="departments" class="form-control select2 <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-allow-clear="false" data-search="false">
                            <option selected disabled></option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>"><?php echo e($department->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div id="courseInput" class="form-group  <?php if(!$errors->has('webinar_id')): ?> d-none <?php endif; ?>">
                        <label class="form-group-label"><?php echo e(trans('product.course')); ?></label>
                        <select name="webinar_id" class="form-control select2 <?php $__errorArgs = ['webinar_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="" selected disabled><?php echo e(trans('panel.select_course')); ?></option>

                            <?php $__currentLoopData = $webinars; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $webinar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($webinar->id); ?>"><?php echo e($webinar->title); ?> - <?php echo e($webinar->creator->full_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['webinar_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('site.subject')); ?></label>
                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>  is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"/>
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback">
                            <?php echo e($message); ?>

                        </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-group-label"><?php echo e(trans('site.message')); ?></label>
                        <textarea name="message" class="form-control" rows="10"><?php echo e(old('message')); ?></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-group-label"><?php echo e(trans('panel.attach_file')); ?></label>

                        <div class="custom-file bg-white">
                            <input type="file" name="attach" class="js-ajax-upload-file-input js-ajax-attach custom-file-input" data-upload-name="attach" id="attachFile">
                            <span class="custom-file-text"><?php echo e(trans('update.select_a_file')); ?></span>
                            <label class="custom-file-label" for="attachFile"><?php echo e(trans('update.browse')); ?></label>
                        </div>

                        <div class="invalid-feedback d-block"></div>
                    </div>

                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between border-top-gray-100 pt-16 mt-16">
                        <div class="d-flex align-items-center">
                            <div class="d-flex-center size-48 rounded-12 bg-gray-200">
                                <?php if (isset($component)) { $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e = $component; } ?>
<?php $component = BladeUI\Icons\Components\Svg::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('iconsax-bol-info-circle'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(BladeUI\Icons\Components\Svg::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'icons text-gray-400','width' => '24px','height' => '24px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e)): ?>
<?php $component = $__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e; ?>
<?php unset($__componentOriginalcd9972c8156dfa6e5fd36675ca7bf5f21b506e2e); ?>
<?php endif; ?>
                            </div>
                            <div class="ml-8">
                                <h5 class="font-14"><?php echo e(trans('update.notice')); ?></h5>
                                <p class="mt-2 font-12 text-gray-500"><?php echo e(trans('update.the_support_message_sending_hint')); ?></p>
                            </div>
                        </div>

                        <button type="submit" class="js-submit-special-offer-btn btn btn-lg btn-primary mt-20 mt-lg-0"><?php echo e(trans('site.send_message')); ?></button>
                    </div>



                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
    <script src="/assets/design_1/js/panel/conversations.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/support/create/index.blade.php ENDPATH**/ ?>