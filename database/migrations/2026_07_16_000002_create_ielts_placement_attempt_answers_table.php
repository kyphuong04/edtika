<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_placement_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('placement_test_id');
            $table->unsignedBigInteger('placement_question_id');

            // Đáp án học viên nộp — lưu dạng JSON cho mọi loại câu hỏi:
            // multiple_choice / listening_image_choice: chuỗi đơn ("Option A" hoặc "A")
            // error_correction: chuỗi câu đã sửa
            // sentence_completion: mảng theo từng chỗ trống
            $table->json('answer_given')->nullable();

            $table->boolean('is_correct')->default(false);

            $table->timestamps();

            $table->index('attempt_id');
            $table->index('placement_question_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_placement_attempt_answers');
    }
};