<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // IELTS Mock Question Bank
        if (
            Schema::hasTable('ielts_mock_question_bank') &&
            Schema::hasColumn('ielts_mock_question_bank', 'correct_answer')
        ) {
            DB::statement("
                ALTER TABLE `ielts_mock_question_bank`
                MODIFY `correct_answer` TEXT NULL
            ");
        }

        // IELTS Practice Question Bank
        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            Schema::hasColumn('ielts_practice_question_bank', 'correct_answer')
        ) {
            DB::statement("
                ALTER TABLE `ielts_practice_question_bank`
                MODIFY `correct_answer` TEXT NULL
            ");
        }
    }

    public function down()
    {
        // Mock Question Bank
        if (
            Schema::hasTable('ielts_mock_question_bank') &&
            Schema::hasColumn('ielts_mock_question_bank', 'correct_answer')
        ) {
            DB::statement("
                UPDATE `ielts_mock_question_bank`
                SET `correct_answer` = ''
                WHERE `correct_answer` IS NULL
            ");

            DB::statement("
                ALTER TABLE `ielts_mock_question_bank`
                MODIFY `correct_answer` TEXT NOT NULL
            ");
        }

        // Practice Question Bank
        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            Schema::hasColumn('ielts_practice_question_bank', 'correct_answer')
        ) {
            DB::statement("
                UPDATE `ielts_practice_question_bank`
                SET `correct_answer` = ''
                WHERE `correct_answer` IS NULL
            ");

            DB::statement("
                ALTER TABLE `ielts_practice_question_bank`
                MODIFY `correct_answer` TEXT NOT NULL
            ");
        }
    }
};