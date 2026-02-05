<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Make correct_answer nullable in question bank tables
 * This allows header rows to have null correct_answer
 */
return new class extends Migration
{
    public function up()
    {
        // Mock question bank
        Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
            DB::statement('ALTER TABLE `ielts_mock_question_bank` MODIFY `correct_answer` TEXT NULL');
        });

        // Practice question bank  
        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            DB::statement('ALTER TABLE `ielts_practice_question_bank` MODIFY `correct_answer` TEXT NULL');
        });
    }

    public function down()
    {
        // Rollback: make NOT NULL again (but set empty string for null values first)
        DB::statement('UPDATE `ielts_mock_question_bank` SET `correct_answer` = "" WHERE `correct_answer` IS NULL');
        DB::statement('UPDATE `ielts_practice_question_bank` SET `correct_answer` = "" WHERE `correct_answer` IS NULL');
        
        Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
            DB::statement('ALTER TABLE `ielts_mock_question_bank` MODIFY `correct_answer` TEXT NOT NULL');
        });

        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            DB::statement('ALTER TABLE `ielts_practice_question_bank` MODIFY `correct_answer` TEXT NOT NULL');
        });
    }
};
