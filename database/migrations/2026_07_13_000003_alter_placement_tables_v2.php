<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * CHỈ CHẠY FILE NÀY nếu bạn ĐÃ chạy `php artisan migrate` với 2 migration gốc
 * (create_placement_tests_table, create_placement_questions_table) từ trước.
 *
 * Nếu bạn CHƯA migrate lần nào, KHÔNG cần file này — chỉ cần dùng bản đã cập nhật
 * của 2 migration gốc (đã sửa sẵn) rồi `php artisan migrate` như bình thường.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('placement_tests', 'reading_passage')) {
            Schema::table('placement_tests', function (Blueprint $table) {
                $table->text('reading_passage')->nullable()->after('description');
            });
        }

        // Mở rộng enum `type` để thêm error_correction + listening_image_choice.
        DB::statement("ALTER TABLE placement_questions MODIFY type ENUM('multiple_choice','sentence_completion','error_correction','listening_image_choice') NOT NULL");

        Schema::table('placement_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('placement_questions', 'linked_to_passage')) {
                $table->boolean('linked_to_passage')->default(false)->after('audio_path');
            }
            if (!Schema::hasColumn('placement_questions', 'word_bank')) {
                $table->json('word_bank')->nullable()->after('options');
            }
            if (!Schema::hasColumn('placement_questions', 'blank_hints')) {
                $table->json('blank_hints')->nullable()->after('word_bank');
            }
            // Nếu bản trước đó của bạn lỡ đã có cột option_type, xoá đi cho gọn (không dùng nữa).
            if (Schema::hasColumn('placement_questions', 'option_type')) {
                $table->dropColumn('option_type');
            }
            if (Schema::hasColumn('placement_questions', 'uses_shared_passage')) {
                $table->dropColumn('uses_shared_passage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn(['linked_to_passage', 'word_bank', 'blank_hints']);
        });

        DB::statement("ALTER TABLE placement_questions MODIFY type ENUM('multiple_choice','sentence_completion') NOT NULL");

        Schema::table('placement_tests', function (Blueprint $table) {
            $table->dropColumn('reading_passage');
        });
    }
};