<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Increase word_limit column length to accommodate longer text
 */
return new class extends Migration
{
    public function up()
    {
        // Mock question bank
        DB::statement('ALTER TABLE `ielts_mock_question_bank` MODIFY `word_limit` VARCHAR(100) NULL');

        // Practice question bank  
        DB::statement('ALTER TABLE `ielts_practice_question_bank` MODIFY `word_limit` VARCHAR(100) NULL');
    }

    public function down()
    {
        // Rollback to VARCHAR(50)
        DB::statement('ALTER TABLE `ielts_mock_question_bank` MODIFY `word_limit` VARCHAR(50) NULL');
        DB::statement('ALTER TABLE `ielts_practice_question_bank` MODIFY `word_limit` VARCHAR(50) NULL');
    }
};
