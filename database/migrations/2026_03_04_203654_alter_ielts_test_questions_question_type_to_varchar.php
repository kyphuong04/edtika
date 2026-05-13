<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip migration if table does not exist
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        // Skip if column does not exist
        if (!Schema::hasColumn('ielts_test_questions', 'question_type')) {
            return;
        }

        // Change question_type from ENUM to VARCHAR(100)
        DB::statement("
            ALTER TABLE `ielts_test_questions`
            MODIFY COLUMN `question_type` VARCHAR(100) NOT NULL DEFAULT ''
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip if table does not exist
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        // Skip if column does not exist
        if (!Schema::hasColumn('ielts_test_questions', 'question_type')) {
            return;
        }

        // Reset unsupported values before converting back to ENUM
        DB::statement("
            UPDATE `ielts_test_questions`
            SET `question_type` = 'fill_blank'
            WHERE `question_type` NOT IN (
                'fill_blank',
                'multiple_choice',
                'multiple_select',
                'matching',
                'true_false_ng',
                'yes_no_ng',
                'short_answer',
                'essay',
                'diagram_label',
                'sentence_completion',
                'note_completion',
                'table_completion',
                'flow_chart',
                'summary_completion'
            )
        ");

        // Restore original ENUM definition
        DB::statement("
            ALTER TABLE `ielts_test_questions`
            MODIFY COLUMN `question_type`
            ENUM(
                'fill_blank',
                'multiple_choice',
                'multiple_select',
                'matching',
                'true_false_ng',
                'yes_no_ng',
                'short_answer',
                'essay',
                'diagram_label',
                'sentence_completion',
                'note_completion',
                'table_completion',
                'flow_chart',
                'summary_completion'
            ) NOT NULL
        ");
    }
};