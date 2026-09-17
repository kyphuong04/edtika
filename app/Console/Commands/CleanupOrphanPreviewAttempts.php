<?php

namespace App\Console\Commands;

use App\Models\IeltsTestAttempt;
use Illuminate\Console\Command;

class CleanupOrphanPreviewAttempts extends Command
{
    protected $signature = 'ielts:cleanup-orphan-preview-attempts {--force : Thực sự xóa, mặc định chỉ liệt kê (dry-run)}';

    protected $description = 'Dọn attempt mồ côi: giáo viên tự làm test của chính mình (tạo trước khi có cờ is_preview) — dữ liệu giả làm bẩn thống kê và hàng đợi chấm';

    public function handle()
    {
        // Attempt do chính người tạo ra test đó thực hiện — trước đây là
        // preview "như học viên" nhưng chưa có cờ is_preview để phân biệt.
        $orphans = IeltsTestAttempt::withoutGlobalScope('not_preview')
            ->where('is_preview', false)
            ->whereIn('id', function ($query) {
                $query->select('a.id')
                    ->from('ielts_test_attempts as a')
                    ->join('ielts_tests as t', 't.id', '=', 'a.test_id')
                    ->whereColumn('a.user_id', 't.created_by');
            })
            ->with('user:id,full_name,email')
            ->get();

        if ($orphans->isEmpty()) {
            $this->info('Không tìm thấy attempt mồ côi nào.');
            return 0;
        }

        $this->warn('Tìm thấy ' . $orphans->count() . ' attempt do chính creator của test thực hiện:');
        foreach ($orphans as $attempt) {
            $this->line(sprintf(
                '  #%d test_id=%d user=%s (%s) status=%s started_at=%s',
                $attempt->id,
                $attempt->test_id,
                $attempt->user->full_name ?? '?',
                $attempt->user->email ?? '?',
                $attempt->status,
                $attempt->started_at ? date('Y-m-d H:i', $attempt->started_at) : '-'
            ));
        }

        if (!$this->option('force')) {
            $this->info('Chạy với --force để xóa kèm answer và file audio.');
            return 0;
        }

        $deleted = 0;
        foreach ($orphans as $attempt) {
            $attempt->deleteWithRelatedData();
            $deleted++;
        }

        $this->info("Đã xóa {$deleted} attempt mồ côi (kèm answer + audio).");
        return 0;
    }
}
