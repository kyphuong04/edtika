

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="section-header">
        <h1>Pending Approval</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="<?php echo e(getAdminPanelUrl()); ?>">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="<?php echo e(route('admin.ielts_tests.index')); ?>">IELTS Tests</a></div>
            <div class="breadcrumb-item">Pending Approval</div>
        </div>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-header">
                <h4>Tests Awaiting Approval</h4>
                <div class="card-header-action">
                    <span class="badge badge-warning"><?php echo e($tests->count()); ?> Tests</span>
                </div>
            </div>
            <div class="card-body">
                <?php if($tests->isEmpty()): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>All caught up!</h5>
                        <p class="text-gray">No tests pending approval</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Type</th>
                                    <th>Created By</th>
                                    <th>Submitted</th>
                                    <th>Sections</th>
                                    <th>Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($test->title); ?></strong>
                                        <small class="d-block text-gray"><?php echo e(Str::limit($test->description, 50)); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($test->type === 'mock' ? 'primary' : 'info'); ?>">
                                            <?php echo e(ucfirst($test->type)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($test->creator->full_name ?? 'Unknown'); ?></td>
                                    <td><?php echo e(dateTimeFormat($test->updated_at, 'j M Y, H:i')); ?></td>
                                    <td><?php echo e($test->sections->count()); ?> sections</td>
                                    <td>
                                        <?php echo e($test->sections->sum(function($s) { return $s->questions->count(); })); ?> questions
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" 
                                                    data-target="#reviewModal<?php echo e($test->id); ?>">
                                                <i class="fas fa-eye mr-1"></i>
                                                Review
                                            </button>
                                            
                                            <form action="<?php echo e(route('admin.ielts_tests.approve', $test->id)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check mr-1"></i>
                                                    Approve
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" 
                                                    data-target="#rejectModal<?php echo e($test->id); ?>">
                                                <i class="fas fa-times mr-1"></i>
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="reviewModal<?php echo e($test->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Review: <?php echo e($test->title); ?></h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <strong>Type:</strong> <?php echo e(ucfirst($test->type)); ?><br>
                                                        <strong>Format:</strong> <?php echo e(ucfirst($test->format)); ?><br>
                                                        <strong>Duration:</strong> <?php echo e($test->total_duration); ?> minutes
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>Skills:</strong><br>
                                                        <?php if($test->has_listening): ?> <span class="badge badge-info">Listening</span> <?php endif; ?>
                                                        <?php if($test->has_reading): ?> <span class="badge badge-success">Reading</span> <?php endif; ?>
                                                        <?php if($test->has_writing): ?> <span class="badge badge-warning">Writing</span> <?php endif; ?>
                                                        <?php if($test->has_speaking): ?> <span class="badge badge-danger">Speaking</span> <?php endif; ?>
                                                    </div>
                                                </div>

                                                <h6 class="mt-3">Description:</h6>
                                                <p><?php echo e($test->description); ?></p>

                                                <h6 class="mt-3">Sections (<?php echo e($test->sections->count()); ?>):</h6>
                                                <ul>
                                                    <?php $__currentLoopData = $test->sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <?php echo e($section->title); ?> - <?php echo e(ucfirst($section->skill)); ?>

                                                            (<?php echo e($section->questions->count()); ?> questions)
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>

                                                <?php if($test->isMockTest()): ?>
                                                    <?php
                                                        $validation = $test->validateMockTestStructure();
                                                    ?>
                                                    
                                                    <?php if($validation['valid']): ?>
                                                        <div class="alert alert-success">
                                                            <i class="fas fa-check-circle mr-2"></i>
                                                            Mock test structure is valid
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                                            <strong>Validation Issues:</strong>
                                                            <ul class="mb-0 mt-2">
                                                                <?php $__currentLoopData = $validation['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <li><?php echo e($error); ?></li>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </ul>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="modal fade" id="rejectModal<?php echo e($test->id); ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Test</h5>
                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="<?php echo e(route('admin.ielts_tests.reject', $test->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <div class="modal-body">
                                                    <p>Please provide a reason for rejecting this test:</p>
                                                    <textarea name="rejection_reason" class="form-control" rows="4" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Test</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/ielts_tests/pending_approval.blade.php ENDPATH**/ ?>