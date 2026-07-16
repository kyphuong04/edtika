<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('placement_tests', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['A1', 'A2', 'B1', 'B2', 'B2+']);
            $table->string('title');
            $table->text('description')->nullable();

            // Đoạn văn đọc dùng chung cho các câu hỏi trong đề (tối đa 1 đoạn/đề).
            // Nullable vì không phải đề nào cũng có reading passage.
            $table->text('reading_passage')->nullable();

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('level');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('placement_tests');
    }
};