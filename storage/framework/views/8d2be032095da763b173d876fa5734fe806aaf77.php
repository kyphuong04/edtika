

<?php $__env->startSection('content'); ?>
<section class="section">
    
    <div class="bg-white rounded-16 shadow-sm p-24 mb-24" style="border-radius: 12px;">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-12 p-12 mr-16" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-clock text-white" style="font-size: 24px;"></i>
                </div>
                <div>
                    <h1 class="font-20 font-weight-bold text-dark mb-4">
                        Question Groups - Pending Approval
                    </h1>
                    <p class="text-gray-500 font-13 mb-0">
                        <i class="fas fa-info-circle mr-4" style="font-size: 14px;"></i>
                        Review and approve submitted question groups
                    </p>
                </div>
            </div>
            <div>
                <span class="badge badge-warning" style="padding: 10px 20px; font-size: 14px; border-radius: 20px; font-weight: 600;">
                    <?php echo e($groups->total()); ?> Pending
                </span>
            </div>
        </div>
    </div>

    
    <div class="row mb-24">
        <?php
            $skillStats = [
                'reading' => ['icon' => 'fa-book', 'color' => '#3b82f6', 'bg' => 'rgba(59, 130, 246, 0.1)', 'count' => $groups->where('skill', 'reading')->count()],
                'listening' => ['icon' => 'fa-headphones', 'color' => '#1a3a5c', 'bg' => 'rgba(26, 58, 92, 0.1)', 'count' => $groups->where('skill', 'listening')->count()],
                'writing' => ['icon' => 'fa-pen', 'color' => '#8b5cf6', 'bg' => 'rgba(139, 92, 246, 0.1)', 'count' => $groups->where('skill', 'writing')->count()],
                'speaking' => ['icon' => 'fa-microphone', 'color' => '#10b981', 'bg' => 'rgba(16, 185, 129, 0.1)', 'count' => $groups->where('skill', 'speaking')->count()]
            ];
        ?>
        <?php $__currentLoopData = ['reading', 'listening', 'writing', 'speaking']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-3">
                <div class="bg-white rounded-12 p-16 shadow-sm h-100 d-flex align-items-center" style="border-radius: 12px;">
                    <div class="rounded-10 p-12 mr-16" style="background: <?php echo e($skillStats[$skill]['bg']); ?>; border-radius: 10px;">
                        <i class="fas <?php echo e($skillStats[$skill]['icon']); ?>" style="font-size: 24px; color: <?php echo e($skillStats[$skill]['color']); ?>"></i>
                    </div>
                    <div>
                        <div class="font-24 font-weight-bold" style="color: <?php echo e($skillStats[$skill]['color']); ?>;">
                            <?php echo e($skillStats[$skill]['count']); ?>

                        </div>
                        <div class="font-12 text-gray-500 text-uppercase"><?php echo e(ucfirst($skill)); ?></div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="bg-white rounded-16 shadow-sm p-20 mb-24" style="border-radius: 12px;">
        <form method="GET" class="m-0">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Skill</label>
                    <select name="skill" class="form-control">
                        <option value="">All Skills</option>
                        <option value="reading" <?php echo e(request('skill') == 'reading' ? 'selected' : ''); ?>>
                            <i class="fas fa-book"></i> Reading
                        </option>
                        <option value="listening" <?php echo e(request('skill') == 'listening' ? 'selected' : ''); ?>>
                            <i class="fas fa-headphones"></i> Listening
                        </option>
                        <option value="writing" <?php echo e(request('skill') == 'writing' ? 'selected' : ''); ?>>
                            <i class="fas fa-pen"></i> Writing
                        </option>
                        <option value="speaking" <?php echo e(request('skill') == 'speaking' ? 'selected' : ''); ?>>
                            <i class="fas fa-microphone"></i> Speaking
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Type</label>
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="mock" <?php echo e(request('type') == 'mock' ? 'selected' : ''); ?>>Mock</option>
                        <option value="practice" <?php echo e(request('type') == 'practice' ? 'selected' : ''); ?>>Practice</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="font-12 font-weight-bold text-gray-600 text-uppercase mb-8">Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by title or creator..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-filter mr-8"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-16 shadow-sm overflow-hidden" style="border-radius: 12px;">
        <?php if($groups->isEmpty()): ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5 style="font-weight: 600;">All caught up!</h5>
                <p class="text-muted">No question groups pending approval</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
                            <th class="font-11 text-gray-600 text-uppercase py-16 px-20 font-weight-bold border-0">Group</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Type</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Skill</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Created By</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Submitted</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center">Questions</th>
                            <th class="font-11 text-gray-600 text-uppercase py-16 font-weight-bold border-0 text-center" style="width: 250px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="transition: all 0.2s ease; border-bottom: 1px solid #f0f0f0;" class="hover-row">
                            <td class="py-16 px-20">
                                <div class="d-flex align-items-center">
                                    <?php
                                        $skillIcons = [
                                            'reading' => ['icon' => 'fa-book', 'color' => '#3b82f6'],
                                            'listening' => ['icon' => 'fa-headphones', 'color' => '#1a3a5c'],
                                            'writing' => ['icon' => 'fa-pen', 'color' => '#8b5cf6'],
                                            'speaking' => ['icon' => 'fa-microphone', 'color' => '#10b981']
                                        ];
                                        $currentSkill = $skillIcons[$group->skill] ?? ['icon' => 'fa-circle', 'color' => '#6c757d'];
                                    ?>
                                    <div class="rounded-8 p-10 mr-12" style="background: rgba(<?php echo e(hexdec(substr($currentSkill['color'], 1, 2))); ?>, <?php echo e(hexdec(substr($currentSkill['color'], 3, 2))); ?>, <?php echo e(hexdec(substr($currentSkill['color'], 5, 2))); ?>, 0.1);">
                                        <i class="fas <?php echo e($currentSkill['icon']); ?>" style="color: <?php echo e($currentSkill['color']); ?>; font-size: 16px;"></i>
                                    </div>
                                    <div>
                                        <div class="font-14 font-weight-bold text-dark"><?php echo e($group->title); ?></div>
                                        <?php if($group->description): ?>
                                            <div class="font-12 text-gray-500 mt-4"><?php echo e(Str::limit($group->description, 60)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 70px; padding: 5px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; <?php echo e($group->bank_type === 'mock' ? 'background: #e3f2fd; color: #1976d2;' : 'background: #e8f5e9; color: #388e3c;'); ?>">
                                    <?php echo e($group->bank_type === 'mock' ? 'Mock' : 'Practice'); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 100px; padding: 6px 10px; border-radius: 16px; font-size: 11px; font-weight: 600; 
                                    <?php if($group->skill === 'reading'): ?> background: #dbeafe; color: #1e40af;
                                    <?php elseif($group->skill === 'listening'): ?> background: #e0e7ff; color: #4338ca;
                                    <?php elseif($group->skill === 'writing'): ?> background: #fce7f3; color: #be185d;
                                    <?php elseif($group->skill === 'speaking'): ?> background: #d1fae5; color: #065f46;
                                    <?php endif; ?>">
                                    <i class="fas <?php echo e($currentSkill['icon']); ?> mr-1" style="font-size: 10px;"></i>
                                    <?php echo e(ucfirst($group->skill)); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <div class="font-13 text-gray-700">
                                    <i class="fas fa-user-circle mr-1 text-gray-400"></i>
                                    <?php echo e($group->creator->full_name ?? 'Unknown'); ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <div class="font-13 text-gray-600">
                                    <i class="fas fa-clock mr-1 text-gray-400" style="font-size: 11px;"></i>
                                    <?php echo e(dateTimeFormat($group->updated_at, 'j M Y, H:i')); ?>

                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge" style="display: inline-block; width: 40px; background: #f0f9ff; border: 2px solid #bae6fd; color: #0369a1; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 700;">
                                    <?php echo e($group->questions_count); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center" style="gap: 6px;">
                                    <a href="<?php echo e(route('panel.question-groups.show', $group->id)); ?>" 
                                       class="btn btn-sm" target="_blank"
                                       style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: #f8f9fa; color: #495057; border: 1px solid #dee2e6;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <form action="<?php echo e(route('admin.question_groups.approve', $group->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm"
                                                style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    
                                    <button type="button" class="btn btn-sm" 
                                            data-toggle="modal" data-target="#rejectModal<?php echo e($group->id); ?>"
                                            style="padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 600; background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        
                        <div class="modal fade" id="rejectModal<?php echo e($group->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
                                    <div class="modal-header" style="background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%); color: white; border: none;">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle mr-2"></i>Reject Question Group</h5>
                                        <button type="button" class="close" data-dismiss="modal" style="color: white; opacity: 1;">&times;</button>
                                    </div>
                                    <form action="<?php echo e(route('admin.question_groups.reject', $group->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-body" style="padding: 24px;">
                                            <p style="margin-bottom: 8px;"><strong>Group:</strong> <?php echo e($group->title); ?></p>
                                            <p style="color: #6c757d; font-size: 14px; margin-bottom: 16px;">Please provide a reason for rejecting this question group:</p>
                                            <textarea name="rejection_reason" class="form-control" rows="4" required 
                                                      placeholder="Enter rejection reason..." 
                                                      style="border-radius: 8px; border: 1px solid #dee2e6; font-size: 14px;"></textarea>
                                        </div>
                                        <div class="modal-footer" style="border-top: 1px solid #f0f0f0; padding: 16px 24px;">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 6px;">Cancel</button>
                                            <button type="submit" class="btn btn-danger" style="border-radius: 6px;">
                                                <i class="fas fa-ban mr-1"></i> Reject Group
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            
            <?php if($groups->hasPages()): ?>
                <div class="p-3" style="border-top: 1px solid #f0f0f0;">
                    <?php echo e($groups->links()); ?>

                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<style>
.hover-row:hover {
    background: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.text-gray-500 { color: #6c757d; }
.text-gray-600 { color: #4b5563; }
.text-gray-700 { color: #374151; }
.text-gray-400 { color: #9ca3af; }
.rounded-8 { border-radius: 8px; }
.rounded-10 { border-radius: 10px; }
.rounded-12 { border-radius: 12px; }
.rounded-16 { border-radius: 16px; }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\edtika\resources\views/admin/ielts_tests/pending_approval.blade.php ENDPATH**/ ?>