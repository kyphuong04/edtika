<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Vì sao bạn làm sai câu này?" — học viên chọn lý do ở sidebar Giải thích
 * đáp án (trang chữa bài Reading / Listening). Mỗi số câu của 1 lượt làm
 * giữ đúng 1 lý do (chọn lại = cập nhật).
 *
 * slot_index: vị trí ô trong câu hỏi (câu Note completion 7 chỗ trống = 7 ô).
 * attempt_id không đặt khoá ngoại: bài làm xem trước (mentor preview) bị xoá
 * khi thoát preview — giống ielts_answer_reports.
 * Sau này gửi cho giáo viên: dùng test_id + question_id để lọc theo đề.
 */
class CreateIeltsAttemptMistakeReasonsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ielts_attempt_mistake_reasons')) {
            return;
        }

        Schema::create('ielts_attempt_mistake_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attempt_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('question_id');
            $table->unsignedSmallInteger('slot_index')->default(0);
            $table->string('question_number', 20)->nullable();
            $table->string('skill', 20)->nullable();
            $table->string('question_type', 50)->nullable();
            $table->string('reason_code', 40);
            $table->string('reason_text', 255)->nullable();   // chỉ khi chọn "Lý do khác"
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->unique(['attempt_id', 'question_id', 'slot_index'], 'ielts_mistake_reason_slot_unique');
            $table->index(['test_id', 'question_id']);
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_attempt_mistake_reasons');
    }
}
