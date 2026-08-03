<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            // Giải thích đáp án — chỉ hiển thị cho Manager/CEO ở trang xem chi
            // tiết bài làm, KHÔNG bao giờ trả về cho học viên khi đang làm bài.
            $table->text('answer_help')->nullable()->after('correct_answer');
        });
    }

    public function down(): void
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn('answer_help');
        });
    }
};