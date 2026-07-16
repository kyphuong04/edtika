<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_test_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('order_index')->default(0);

            // multiple_choice | sentence_completion | error_correction | listening_image_choice
            $table->enum('type', [
                'multiple_choice',
                'sentence_completion',
                'error_correction',
                'listening_image_choice',
            ]);

            // Cờ "có audio" áp dụng cho mọi type (kể cả multiple_choice/sentence_completion
            // nếu về sau muốn gắn audio cho dạng đó). listening_image_choice luôn bắt buộc = true.
            $table->boolean('has_audio')->default(false);
            $table->string('audio_path')->nullable();

            // Câu hỏi có dùng chung đoạn văn đọc của đề (placement_tests.reading_passage) không.
            // Chỉ áp dụng cho multiple_choice, tối đa 1 đoạn văn / đề.
            $table->boolean('linked_to_passage')->default(false);

            $table->text('question_text');

            // multiple_choice: ["Option A", "Option B", ...]
            // listening_image_choice: ["path/to/A.jpg", "path/to/B.jpg", "path/to/C.jpg"] (luôn đúng 3 phần tử)
            $table->json('options')->nullable();

            // Danh sách từ gợi ý hiển thị phía trên (chỉ dùng cho sentence_completion), vd ["do","learn","make","take"]
            $table->json('word_bank')->nullable();

            // Gợi ý riêng cho từng chỗ trống của sentence_completion, vd [null, "move", "make"]
            $table->json('blank_hints')->nullable();

            // multiple_choice:        ["Option A"] (mảng 1 phần tử, khớp 1 phần tử trong options)
            // sentence_completion:    [["answer1","alt1"], ["answer2"]] - theo từng chỗ trống ___,
            //                         mỗi phần tử con là các đáp án được chấp nhận cho chỗ trống đó
            // error_correction:       ["câu đúng hoàn chỉnh"] (so khớp không phân biệt hoa/thường)
            // listening_image_choice: ["A"] / ["B"] / ["C"]
            $table->json('correct_answer')->nullable();

            $table->decimal('points', 4, 2)->default(1.00);

            $table->timestamps();

            $table->index(['placement_test_id', 'order_index']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_questions');
    }
};