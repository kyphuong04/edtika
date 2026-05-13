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
            Schema::hasColumn('ielts_mock_question_bank', 'word_limit')
        ) {
            DB::statement("
                ALTER TABLE `ielts_mock_question_bank`
                MODIFY `word_limit` VARCHAR(100) NULL
            ");
        }

        // IELTS Practice Question Bank
        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            Schema::hasColumn('ielts_practice_question_bank', 'word_limit')
        ) {
            DB::statement("
                ALTER TABLE `ielts_practice_question_bank`
                MODIFY `word_limit` VARCHAR(100) NULL
            ");
        }
    }

    public function down()
    {
        // IELTS Mock Question Bank
        if (
            Schema::hasTable('ielts_mock_question_bank') &&
            Schema::hasColumn('ielts_mock_question_bank', 'word_limit')
        ) {
            DB::statement("
                ALTER TABLE `ielts_mock_question_bank`
                MODIFY `word_limit` VARCHAR(50) NULL
            ");
        }

        // IELTS Practice Question Bank
        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            Schema::hasColumn('ielts_practice_question_bank', 'word_limit')
        ) {
            DB::statement("
                ALTER TABLE `ielts_practice_question_bank`
                MODIFY `word_limit` VARCHAR(50) NULL
            ");
        }
    }
};