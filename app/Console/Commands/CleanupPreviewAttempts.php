<?php

namespace App\Console\Commands;

use App\Models\IeltsTestAttempt;
use Illuminate\Console\Command;

class CleanupPreviewAttempts extends Command
{
    protected $signature = 'ielts:cleanup-preview-attempts {--hours=48 : Xóa preview không hoạt động quá số giờ này}';

    protected $description = 'Xóa các attempt xem trước (is_preview) quá hạn, kèm answer và file audio Speaking';

    public function handle()
    {
        $hours = max(1, (int) $this->option('hours'));
        $cutoff = time() - $hours * 3600;

        // Cột thời gian của attempt là unix timestamp, updated_at có thể null
        // với attempt vừa tạo chưa từng save answer.
        $previews = IeltsTestAttempt::withoutGlobalScope('not_preview')
            ->where('is_preview', true)
            ->where(function ($query) use ($cutoff) {
                $query->where('updated_at', '<', $cutoff)
                    ->orWhere(function ($q) use ($cutoff) {
                        $q->whereNull('updated_at')
                            ->where('started_at', '<', $cutoff);
                    });
            })
            ->get();

        if ($previews->isEmpty()) {
            $this->info('Không có preview attempt nào quá hạn.');
            return 0;
        }

        $deleted = 0;
        foreach ($previews as $preview) {
            $preview->deleteWithRelatedData();
            $deleted++;
        }

        $this->info("Đã xóa {$deleted} preview attempt quá hạn (kèm answer + audio).");
        return 0;
    }
}
