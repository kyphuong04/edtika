

<?php $__env->startPush('styles_top'); ?>
<style>
    .grading-container {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .grading-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 24px;
    }
    
    .grading-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }
    
    .stat-card {
        background: rgba(255,255,255,0.15);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }
    
    .stat-number {
        font-size: 32px;
        font-weight: 700;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 13px;
        opacity: 0.9;
        margin-top: 6px;
    }
    
    .filter-bar {
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        display: flex;
        gap: 16px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    
    .filter-group select {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
    }
    
    .attempt-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: all 0.2s;
        border-left: 4px solid transparent;
    }
    
    .attempt-card:hover {
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    
    .attempt-card.pending-writing {
        border-left-color: #f59e0b;
    }
    
    .attempt-card.pending-speaking {
        border-left-color: #ef4444;
    }
    
    .attempt-card.graded {
        border-left-color: #10b981;
    }
    
    .attempt-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    
    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .student-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
    }
    
    .student-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }
    
    .test-name {
        font-size: 13px;
        color: #6b7280;
    }
    
    .attempt-badges {
        display: flex;
        gap: 8px;
    }
    
    .badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-mock {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .badge-practice {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
    }
    
    .badge-graded {
        background: #d1fae5;
        color: #065f46;
    }
    
    .attempt-skills {
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .skill-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: #f9fafb;
        border-radius: 8px;
        font-size: 13px;
    }
    
    .skill-item.needs-grading {
        background: #fef3c7;
        color: #92400e;
    }
    
    .skill-item.graded {
        background: #d1fae5;
        color: #065f46;
    }
    
    .skill-icon {
        width: 20px;
        height: 20px;
    }
    
    .attempt-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-grade {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-grade-writing {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #fff;
    }
    
    .btn-grade-writing:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
    }
    
    .btn-grade-speaking {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
    }
    
    .btn-grade-speaking:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
    }
    
    .btn-view {
        background: #f3f4f6;
        color: #374151;
    }
    
    .btn-view:hover {
        background: #e5e7eb;
    }
    
    .completed-date {
        font-size: 12px;
        color: #9ca3af;
        margin-top: 12px;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 12px;
    }
    
    .empty-state-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }
    
    .empty-state h3 {
        font-size: 18px;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .empty-state p {
        font-size: 14px;
        color: #6b7280;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="grading-container mt-20">
    
    <div class="grading-header">
        <h1 class="mb-2">IELTS Grading Dashboard</h1>
        <p class="mb-0 opacity-85">Review and grade student writing and speaking submissions</p>
        
        <div class="grading-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo e($pendingWriting); ?></div>
                <div class="stat-label">✍️ Writing Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo e($pendingSpeaking); ?></div>
                <div class="stat-label">🎤 Speaking Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo e($pendingWriting + $pendingSpeaking); ?></div>
                <div class="stat-label">📋 Total Pending</div>
            </div>
        </div>
    </div>
    
    
    <div class="filter-bar">
        <div class="filter-group">
            <label>Status:</label>
            <select onchange="applyFilter('status', this.value)">
                <option value="pending" <?php echo e($currentStatus === 'pending' ? 'selected' : ''); ?>>Pending</option>
                <option value="graded" <?php echo e($currentStatus === 'graded' ? 'selected' : ''); ?>>Graded</option>
                <option value="all" <?php echo e($currentStatus === 'all' ? 'selected' : ''); ?>>All</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>Test Type:</label>
            <select onchange="applyFilter('type', this.value)">
                <option value="all" <?php echo e($currentType === 'all' ? 'selected' : ''); ?>>All Types</option>
                <option value="mock" <?php echo e($currentType === 'mock' ? 'selected' : ''); ?>>Mock Tests</option>
                <option value="practice" <?php echo e($currentType === 'practice' ? 'selected' : ''); ?>>Practice Tests</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label>Skill:</label>
            <select onchange="applyFilter('skill', this.value)">
                <option value="all" <?php echo e($currentSkill === 'all' ? 'selected' : ''); ?>>All Skills</option>
                <option value="writing" <?php echo e($currentSkill === 'writing' ? 'selected' : ''); ?>>Writing Only</option>
                <option value="speaking" <?php echo e($currentSkill === 'speaking' ? 'selected' : ''); ?>>Speaking Only</option>
            </select>
        </div>
    </div>
    
    
    <?php if($attempts->count() > 0): ?>
        <?php $__currentLoopData = $attempts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attempt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $needsWriting = $attempt->test->has_writing && !$attempt->writing_band;
                $needsSpeaking = $attempt->test->has_speaking && !$attempt->speaking_band;
                $cardClass = $needsWriting ? 'pending-writing' : ($needsSpeaking ? 'pending-speaking' : 'graded');
            ?>
            
            <div class="attempt-card <?php echo e($cardClass); ?>">
                <div class="attempt-header">
                    <div class="student-info">
                        <div class="student-avatar">
                            <?php echo e(strtoupper(substr($attempt->user->full_name ?? 'U', 0, 1))); ?>

                        </div>
                        <div>
                            <div class="student-name"><?php echo e($attempt->user->full_name ?? 'Unknown User'); ?></div>
                            <div class="test-name"><?php echo e($attempt->test->title ?? 'Unknown Test'); ?></div>
                        </div>
                    </div>
                    
                    <div class="attempt-badges">
                        <span class="badge <?php echo e($attempt->test->test_type === 'mock' ? 'badge-mock' : 'badge-practice'); ?>">
                            <?php echo e(ucfirst($attempt->test->test_type ?? 'Test')); ?>

                        </span>
                        <?php if($needsWriting || $needsSpeaking): ?>
                            <span class="badge badge-pending">Pending Grading</span>
                        <?php else: ?>
                            <span class="badge badge-graded">Graded</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="attempt-skills">
                    <?php
                        // Count actual answers for each skill
                        $listeningAnswers = $attempt->answers->filter(fn($a) => $a->question && $a->question->section && $a->question->section->skill === 'listening')->count();
                        $readingAnswers = $attempt->answers->filter(fn($a) => $a->question && $a->question->section && $a->question->section->skill === 'reading')->count();
                    ?>
                    
                    <?php if($attempt->test->has_listening): ?>
                        <div class="skill-item graded">
                            <span>🎧</span>
                            Listening: <strong><?php echo e($attempt->listening_score ?? 0); ?>/40</strong>
                            <small class="text-muted">(<?php echo e($listeningAnswers); ?> answered)</small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($attempt->test->has_reading): ?>
                        <div class="skill-item graded">
                            <span>📖</span>
                            Reading: <strong><?php echo e($attempt->reading_score ?? 0); ?>/40</strong>
                            <small class="text-muted">(<?php echo e($readingAnswers); ?> answered)</small>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($attempt->test->has_writing): ?>
                        <div class="skill-item <?php echo e($needsWriting ? 'needs-grading' : 'graded'); ?>">
                            <span>✍️</span>
                            Writing: <strong><?php echo e($attempt->writing_band ? 'Band ' . $attempt->writing_band : 'Needs Grading'); ?></strong>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($attempt->test->has_speaking): ?>
                        <div class="skill-item <?php echo e($needsSpeaking ? 'needs-grading' : 'graded'); ?>">
                            <span>🎤</span>
                            Speaking: <strong><?php echo e($attempt->speaking_band ? 'Band ' . $attempt->speaking_band : 'Needs Grading'); ?></strong>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="attempt-actions">
                    <?php if($needsWriting): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'writing'])); ?>" class="btn-grade btn-grade-writing">
                            <span>✍️</span> Grade Writing
                        </a>
                    <?php endif; ?>
                    
                    <?php if($needsSpeaking): ?>
                        <a href="<?php echo e(route('panel.ielts_grading.grade', ['attemptId' => $attempt->id, 'skill' => 'speaking'])); ?>" class="btn-grade btn-grade-speaking">
                            <span>🎤</span> Grade Speaking
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo e(route('panel.ielts_tests.review', $attempt->id)); ?>" class="btn-grade btn-view" target="_blank">
                        <span>👁️</span> View Full Attempt
                    </a>
                </div>
                
                <div class="completed-date">
                    Completed: <?php echo e($attempt->completed_at ? date('M j, Y \a\t g:i A', $attempt->completed_at) : 'N/A'); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        
        <div class="mt-4">
            <?php echo e($attempts->appends(request()->query())->links()); ?>

        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <h3>No Attempts Found</h3>
            <p>There are no test attempts matching your current filters.</p>
        </div>
    <?php endif; ?>
</div>

<script>
function applyFilter(key, value) {
    const url = new URL(window.location.href);
    url.searchParams.set(key, value);
    window.location.href = url.toString();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('design_1.panel.layouts.panel', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\edtika\resources\views/design_1/panel/ielts_grading/index.blade.php ENDPATH**/ ?>