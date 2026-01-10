<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm các fields còn thiếu cho ielts_question_groups
 * - instructions: hướng dẫn cho group (chỉ thêm nếu chưa có)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            // Only add instructions if not exists (others already in table)
            if (!Schema::hasColumn('ielts_question_groups', 'instructions')) {
                $table->text('instructions')->nullable()->after('description');
            }
            
            // audio_path might be different from audio_file
            if (!Schema::hasColumn('ielts_question_groups', 'audio_path')) {
                $table->string('audio_path')->nullable()->after('audio_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ielts_question_groups', 'instructions')) {
                $table->dropColumn('instructions');
            }
            if (Schema::hasColumn('ielts_question_groups', 'audio_path')) {
                $table->dropColumn('audio_path');
            }
        });
    }
};
