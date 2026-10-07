<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Bổ sung teacher_id (người nhận báo lỗi = người tạo đề) cho bảng
 * ielts_answer_reports đã được tạo từ bản trước (bản gửi cho manager).
 * Báo cáo cũ được gán theo ielts_tests.created_by.
 */
class AddTeacherIdToIeltsAnswerReportsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ielts_answer_reports') || Schema::hasColumn('ielts_answer_reports', 'teacher_id')) {
            return;
        }

        Schema::table('ielts_answer_reports', function (Blueprint $table) {
            $table->unsignedInteger('teacher_id')->nullable()->after('user_id');
            $table->index(['teacher_id', 'status']);
        });

        DB::statement(
            'UPDATE ielts_answer_reports r
             JOIN ielts_tests t ON t.id = r.test_id
             SET r.teacher_id = t.created_by
             WHERE r.teacher_id IS NULL'
        );
    }

    public function down()
    {
        if (Schema::hasColumn('ielts_answer_reports', 'teacher_id')) {
            Schema::table('ielts_answer_reports', function (Blueprint $table) {
                $table->dropIndex(['teacher_id', 'status']);
                $table->dropColumn('teacher_id');
            });
        }
    }
}