<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ielts_test_attempts')) {
            return;
        }

        if (Schema::hasColumn('ielts_test_attempts', 'is_preview')) {
            return;
        }

        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            // Attempt tạo bởi teacher khi bấm "Xem trước như học viên".
            // Bị loại khỏi mọi thống kê/leaderboard/hàng đợi chấm bài qua
            // global scope 'exclude_preview' trên model IeltsTestAttempt,
            // và bị xóa bởi lệnh ielts:cleanup-preview-attempts (chạy hằng ngày).
            $table->boolean('is_preview')->default(false)->after('attempt_number')->index();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('ielts_test_attempts')) {
            return;
        }

        if (!Schema::hasColumn('ielts_test_attempts', 'is_preview')) {
            return;
        }

        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            $table->dropColumn('is_preview');
        });
    }
};
