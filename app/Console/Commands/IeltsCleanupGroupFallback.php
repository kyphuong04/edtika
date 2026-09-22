<?php

namespace App\Console\Commands;

use App\Models\IeltsQuestionGroup;
use App\Models\IeltsTestPart;
use App\Models\IeltsTestSection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IeltsCleanupGroupFallback extends Command
{
    protected $signature = 'ielts:cleanup-group-fallback {--apply : Ghi thay đổi (mặc định chỉ liệt kê)}';

    protected $description = 'Gỡ passage/task_image bị copy từ Part sang Question Group; liệt kê media path hỏng';

    public function handle()
    {
        $groupsTable   = (new IeltsQuestionGroup)->getTable();
        $partsTable    = (new IeltsTestPart)->getTable();
        $sectionsTable = (new IeltsTestSection)->getTable();
        $apply = (bool) $this->option('apply');

        // ── 1. Group có passage / task_image trùng y hệt Part cha ─────────
        $rows = DB::table("$groupsTable as g")
            ->join("$partsTable as p", 'p.id', '=', 'g.part_id')
            ->where(function ($q) {
                $q->whereRaw('CAST(g.passage AS BINARY) = CAST(p.passage AS BINARY)')
                ->orWhereRaw('CAST(g.task_image AS BINARY) = CAST(p.task_image AS BINARY)');
            })
            ->select([
                'g.id', 'g.part_id', 'g.section_id', 'g.skill',
                'g.passage as g_passage', 'p.passage as p_passage',
                'g.task_image as g_image', 'p.task_image as p_image',
            ])
            ->get();

        $passageCount = 0;
        $imageCount = 0;

        DB::transaction(function () use ($rows, $groupsTable, $apply, &$passageCount, &$imageCount) {
            foreach ($rows as $row) {
                $update = [];

                // So sánh lại bằng PHP: collation MySQL *_ci coi "A" = "a".
                if ($row->g_passage !== null && $row->g_passage === $row->p_passage) {
                    $update['passage'] = null;
                    $passageCount++;
                }

                if ($row->g_image !== null && $row->g_image === $row->p_image) {
                    $update['task_image'] = null;
                    $imageCount++;
                }

                if (!$update) {
                    continue;
                }

                $this->line(sprintf(
                    '  group#%d (part#%d, section#%d): %s',
                    $row->id, $row->part_id, $row->section_id, implode(', ', array_keys($update))
                ));

                if ($apply) {
                    DB::table($groupsTable)->where('id', $row->id)->update($update);
                }
            }
        });

        $this->info("Passage trùng Part: {$passageCount} | Ảnh trùng Part: {$imageCount}"
            . ($apply ? ' -> ĐÃ gỡ.' : ' (dry-run, chạy lại với --apply để ghi)'));

        // ── 2. Media path hỏng (chỉ là tên file, không có thư mục) ────────
        //    Hệ quả của lỗi mục 2 — không tự sửa được, giáo viên phải upload lại.
        $this->line('');
        $this->warn('Media path nghi hỏng (không chứa "/"):');

        $targets = [
            $partsTable    => ['audio_file', 'task_image', 'video_file'],
            $groupsTable   => ['audio_file', 'task_image', 'video_file'],
            $sectionsTable => ['audio_file'],
        ];

        foreach ($targets as $table => $columns) {
            foreach ($columns as $column) {
                DB::table($table)
                    ->whereNotNull($column)
                    ->where($column, '!=', '')
                    ->where($column, 'not like', '%/%')
                    ->orderBy('id')
                    ->each(function ($r) use ($table, $column) {
                        $this->line("  {$table}#{$r->id}.{$column} = {$r->{$column}}");
                    });
            }
        }

        return 0;
    }
}