<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->unsignedBigInteger('speaking_question_id')->nullable()->after('speaking_recording_path');
        });

        // Thêm trạng thái trung gian 'speaking': đã làm xong hết các đề trắc
        // nghiệm, đang chờ ghi âm câu Speaking, chưa được tính là completed.
        DB::statement("ALTER TABLE ielts_placement_attempts MODIFY status ENUM('in_progress','speaking','completed','abandoned') DEFAULT 'in_progress'");
    }

    public function down(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->dropColumn('speaking_question_id');
        });

        DB::statement("ALTER TABLE ielts_placement_attempts MODIFY status ENUM('in_progress','completed','abandoned') DEFAULT 'in_progress'");
    }
};