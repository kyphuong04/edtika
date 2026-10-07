<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Báo lỗi đáp án — học viên gửi từ sidebar "Giải thích đáp án" ở trang chữa
 * bài; giáo viên tạo đề (teacher_id = ielts_tests.created_by lúc gửi) xem và
 * xử lý tại Panel > Bảng thông báo > Thông báo đề lỗi.
 *
 * attempt_id không đặt khoá ngoại: bài làm xem trước (mentor preview) bị xoá
 * khi thoát preview nhưng báo cáo vẫn cần giữ lại.
 * current_answer / student_answer là ảnh chụp tại lúc gửi — đề có sửa đáp án
 * sau đó thì giáo viên vẫn thấy học viên đã báo lỗi trên đáp án nào.
 */
class CreateIeltsAnswerReportsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ielts_answer_reports')) {
            return;
        }

        Schema::create('ielts_answer_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');      // người gửi (học viên)
            $table->unsignedInteger('teacher_id');   // người nhận = người tạo đề
            $table->unsignedInteger('attempt_id')->nullable();
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('section_id')->nullable();
            $table->unsignedInteger('question_id');
            $table->string('question_number', 20)->nullable();
            $table->string('skill', 20)->nullable();
            $table->string('question_type', 50)->nullable();
            $table->text('current_answer')->nullable();
            $table->text('student_answer')->nullable();
            $table->text('message');
            $table->string('status', 16)->default('new'); // new | resolved
            $table->unsignedInteger('resolved_by')->nullable();
            $table->unsignedInteger('resolved_at')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->index(['teacher_id', 'status']);
            $table->index('test_id');
            $table->index(['user_id', 'question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_answer_reports');
    }
}
