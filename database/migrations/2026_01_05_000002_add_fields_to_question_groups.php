<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm các fields còn thiếu cho ielts_question_groups
 *
 * Chỉ chạy nếu bảng ielts_question_groups đã tồn tại.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Nếu bảng chưa tồn tại thì bỏ qua migration này
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            // Thêm instructions nếu chưa tồn tại
            if (!Schema::hasColumn('ielts_question_groups', 'instructions')) {
                $table->text('instructions')
                    ->nullable()
                    ->after('description');
            }

            // Thêm audio_path nếu chưa tồn tại
            if (!Schema::hasColumn('ielts_question_groups', 'audio_path')) {
                // Nếu không chắc audio_file có tồn tại hay không,
                // không nên dùng ->after('audio_file')
                $table->string('audio_path')
                    ->nullable();
            }
        });
    }

    public function down(): void
    {
        // Nếu bảng không tồn tại thì bỏ qua
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

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