<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddAudioClipsToPlacement extends Migration
{
    public function up()
    {
        Schema::table('placement_tests', function (Blueprint $table) {
            $table->json('audio_clips')->nullable()->after('reading_passages');
        });

        Schema::table('placement_questions', function (Blueprint $table) {
            $table->string('audio_clip_id', 20)->nullable()->after('has_audio');
        });

        $this->backfill();
    }

    /**
     * Chuyển audio_path (1-1 với câu hỏi) sang mô hình clip ở cấp đề.
     * Các câu trong cùng đề đang trỏ tới CÙNG path sẽ tự động gộp thành 1 clip
     * -> hiển thị thành 1 nhóm dùng chung, đúng nghiệp vụ mới.
     *
     * Dùng query builder thô thay vì Eloquent: migration phải chạy được cả khi
     * model sau này đổi $fillable/$casts hoặc bị xoá.
     */
    private function backfill(): void
    {
        DB::table('placement_tests')->orderBy('id')->chunk(50, function ($tests) {
            foreach ($tests as $test) {
                $questions = DB::table('placement_questions')
                    ->where('placement_test_id', $test->id)
                    ->orderBy('order_index')
                    ->get();

                $clips = [];
                $seq = 0;

                foreach ($questions as $q) {
                    if (empty($q->audio_path)) {
                        continue;
                    }

                    $clipId = null;
                    foreach ($clips as $clip) {
                        if ($clip['path'] === $q->audio_path) {
                            $clipId = $clip['id'];
                            break;
                        }
                    }

                    if ($clipId === null) {
                        $clipId = 'a' . (++$seq);
                        $clips[] = [
                            'id'    => $clipId,
                            'label' => basename($q->audio_path),
                            'path'  => $q->audio_path,
                        ];
                    }

                    DB::table('placement_questions')
                        ->where('id', $q->id)
                        ->update(['audio_clip_id' => $clipId]);
                }

                if ($clips) {
                    DB::table('placement_tests')
                        ->where('id', $test->id)
                        ->update(['audio_clips' => json_encode($clips, JSON_UNESCAPED_UNICODE)]);
                }
            }
        });
    }

    public function down()
    {
        Schema::table('placement_tests', function (Blueprint $table) {
            $table->dropColumn('audio_clips');
        });

        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn('audio_clip_id');
        });
    }
}