<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstructionToPlacementQuestions extends Migration
{
    public function up()
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            // Đề bài / hướng dẫn riêng của từng câu, VD: "Choose the correct
            // letter, A, B or C." Hiển thị phía TRÊN nội dung câu hỏi.
            $table->text('instruction')->nullable()->after('question_text');
        });
    }

    public function down()
    {
        Schema::table('placement_questions', function (Blueprint $table) {
            $table->dropColumn('instruction');
        });
    }
}