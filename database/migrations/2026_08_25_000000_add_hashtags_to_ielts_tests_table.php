<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('ielts_tests', 'hashtags')) {
            return;
        }

        Schema::table('ielts_tests', function (Blueprint $table) {
            // JSON array các hashtag do người tạo đề nhập, KHÔNG chứa dấu '#'
            // (dấu '#' được thêm khi render). VD:
            //   ["[Reading] T/F/NG", "CAM", "[Reading] Gap Filling"]
            // Chỉ nhập/dùng cho Practice test — hiển thị thành chip trên card
            // ở /panel/ielts-tests/mock, tab "Practice by Skill".
            // null = chưa nhập, hoặc test không phải loại practice.
            $table->text('hashtags')->nullable()->after('difficulty_level');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('ielts_tests', 'hashtags')) {
            return;
        }

        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->dropColumn('hashtags');
        });
    }
};
