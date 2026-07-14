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

            // multiple_choice | sentence_completion
            $table->enum('type', ['multiple_choice', 'sentence_completion']);

            // Listening = "kỹ năng có audio". Không phải type riêng, mà là cờ gắn
            // vào bất kỳ câu hỏi nào (MC hoặc Sentence Completion) kèm file audio.
            $table->boolean('has_audio')->default(false);
            $table->string('audio_path')->nullable();

            $table->text('question_text');

            // multiple_choice: ["Option A", "Option B", ...]
            $table->json('options')->nullable();

            // multiple_choice: "Option A" (string, phải khớp 1 phần tử trong options)
            // sentence_completion: [["answer1","alt1"], ["answer2"]] - mảng theo từng chỗ trống ___,
            //                       mỗi phần tử con là các đáp án được chấp nhận cho chỗ trống đó
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