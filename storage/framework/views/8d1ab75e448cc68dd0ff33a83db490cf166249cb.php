

<?php $__env->startSection('content'); ?>
<section class="mt-30">
    <div class="d-flex align-items-center justify-content-between mb-20">
        <h1 class="section-title">Edit: <?php echo e($test->title); ?></h1>
        <div>
            <a href="<?php echo e(route('panel.my_ielts_tests.sections', $test->id)); ?>" class="btn btn-primary mr-10">
                <i class="fas fa-list mr-5"></i>Manage Sections
            </a>
            <a href="<?php echo e(route('panel.my_ielts_tests')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-5"></i>Back
            </a>
        </div>
    </div>

    
    <div class="row mb-20">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Sections</div>
                <div class="font-24 font-weight-bold text-primary"><?php echo e($test->sections->count()); ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Questions</div>
                <div class="font-24 font-weight-bold text-info">
                    <?php echo e($test->sections->sum(function($s) { return $s->questions->count(); })); ?>

                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Attempts</div>
                <div class="font-24 font-weight-bold text-success"><?php echo e($test->attempts->count()); ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="text-gray font-12">Status</div>
                <div class="font-16 font-weight-bold">
                    <span class="status-badge status-<?php echo e($test->status); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $test->status))); ?>

                    </span>
                </div>
            </div>
        </div>
    </div>

    <form action="<?php echo e(route('panel.my_ielts_tests.update', $test->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Basic Information</h3>

            <div class="form-group">
                <label class="input-label">Test Title *</label>
                <input type="text" name="title" class="form-control" value="<?php echo e(old('title', $test->title)); ?>" required>
            </div>

            <div class="form-group">
                <label class="input-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $test->description)); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Test Type</label>
                        <input type="text" class="form-control" value="<?php echo e(ucfirst(str_replace('_', ' ', $test->type))); ?>" disabled>
                        <small class="text-muted">Type cannot be changed after creation</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Total Duration (minutes) *</label>
                        <input type="number" name="total_duration" class="form-control"
                               value="<?php echo e(old('total_duration', $test->total_duration)); ?>" required min="1">
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="font-16 font-weight-bold mb-20">Target Band</h3>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Minimum Band</label>
                        <input type="number" name="target_band_min" class="form-control"
                               value="<?php echo e(old('target_band_min', $test->target_band_min)); ?>" min="1" max="9" step="0.5">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="input-label">Maximum Band</label>
                        <input type="number" name="target_band_max" class="form-control"
                               value="<?php echo e(old('target_band_max', $test->target_band_max)); ?>" min="1" max="9" step="0.5">
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($test->type === 'practice_test'): ?>
            <div class="form-section">
                <h3 class="font-16 font-weight-bold mb-20">Practice Settings</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">Practice Mode</label>
                            <select name="practice_mode" class="form-control">
                                <option value="timed" <?php echo e($test->practice_mode === 'timed' ? 'selected' : ''); ?>>Timed</option>
                                <option value="untimed" <?php echo e($test->practice_mode === 'untimed' ? 'selected' : ''); ?>>Untimed</option>
                                <option value="exam_mode" <?php echo e($test->practice_mode === 'exam_mode' ? 'selected' : ''); ?>>Exam Mode</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="input-label">Practice Category</label>
                            <select name="practice_category_id" class="form-control">
                                <option value="">-- None --</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill => $cats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup label="<?php echo e(ucfirst($skill)); ?>">
                                        <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($cat->id); ?>" <?php echo e($test->practice_category_id == $cat->id ? 'selected' : ''); ?>>
                                                <?php echo e($cat->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="d-flex align-items-center justify-content-between mt-30">
            <a href="<?php echo e(route('panel.my_ielts_tests')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-5"></i>Back to My Tests
            </a>
            
            <div>
                <?php if($test->status === 'draft' || $test->status === 'rejected'): ?>
                    <button type="submit" class="btn btn-primary mr-10">
                        <i class="fas fa-save mr-5"></i>Save Changes
                    </button>
                    
                    <?php if($test->sections->count() > 0): ?>
                        <a href="<?php echo e(route('panel.my_ielts_tests.submit_approval', $test->id)); ?>" 
                           class="btn btn-success"
                           onclick="return confirm('Submit this test for approval? You won\'t be able to edit it until it\'s reviewed.')">
                            <i class="fas fa-paper-plane mr-5"></i>Submit for Approval
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled title="Add sections first">
                            <i class="fas fa-paper-plane mr-5"></i>Submit for Approval
                        </button>
                    <?php endif; ?>
                <?php elseif($test->status === 'pending_approval'): ?>
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-clock mr-5"></i>This test is pending approval by Manager/CEO
                    </div>
                <?php elseif($test->status === 'approved'): ?>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-5"></i>Save Changes
                    </button>
                    <span class="badge badge-success ml-10">Approved</span>
                <?php endif; ?>
            </div>
        </div>
    </form>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_tests_manage/edit.blade.php ENDPATH**/ ?>