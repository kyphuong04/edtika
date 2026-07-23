<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_placement_attempts', 'current_test_id')) {
                $table->unsignedBigInteger('current_test_id')->nullable()->after('current_step');
            }

            // Song song với test_ids_taken, lưu luôn LEVEL của từng đề đã làm theo đúng
            // thứ tự (vd ["B1","A2","B1"]) — dùng để tra bảng luật phân luồng mà không
            // cần join lại placement_tests mỗi lần tính bước tiếp theo.
            if (!Schema::hasColumn('ielts_placement_attempts', 'levels_taken')) {
                $table->json('levels_taken')->nullable()->after('test_ids_taken');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->dropColumn(['current_test_id', 'levels_taken']);
        });
    }
};