<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add question types columns to question bank tables
 * Checks if columns exist before adding
 */
return new class extends Migration
{
    public function up()
    {
        // Mock question bank
        Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_mock_question_bank', 'question_type')) {
                $table->string('question_type', 50)->after('group_id')->nullable();
            }
            
            if (!Schema::hasColumn('ielts_mock_question_bank', 'question_data')) {
                $table->json('question_data')->nullable();
            }
            
            if (!Schema::hasColumn('ielts_mock_question_bank', 'word_limit')) {
                $table->string('word_limit', 50)->nullable();
            }
            
            if (!Schema::hasColumn('ielts_mock_question_bank', 'marks')) {
                $table->decimal('marks', 4, 1)->default(1.0);
            }
        });

        // Practice question bank  
        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_practice_question_bank', 'question_type')) {
                $table->string('question_type', 50)->after('group_id')->nullable();
            }
            
            if (!Schema::hasColumn('ielts_practice_question_bank', 'question_data')) {
                $table->json('question_data')->nullable();
            }
            
            if (!Schema::hasColumn('ielts_practice_question_bank', 'word_limit')) {
                $table->string('word_limit', 50)->nullable();
            }
            
            if (!Schema::hasColumn('ielts_practice_question_bank', 'marks')) {
                $table->decimal('marks', 4, 1)->default(1.0);
            }
        });
    }

    public function down()
    {
        Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
            $columns = ['question_type', 'question_data', 'word_limit', 'marks'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('ielts_mock_question_bank', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            $columns = ['question_type', 'question_data', 'word_limit', 'marks'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('ielts_practice_question_bank', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
