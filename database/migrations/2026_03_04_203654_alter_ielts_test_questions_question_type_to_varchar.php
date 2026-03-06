<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Change from ENUM to VARCHAR(100) to support all question types
        // including bank types like multiple_choice_single, true_false_not_given, etc.
        DB::statement("ALTER TABLE ielts_test_questions MODIFY COLUMN question_type VARCHAR(100) NOT NULL DEFAULT ''");
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Revert to original ENUM (rows with non-enum values will be reset to '')
        DB::statement("UPDATE ielts_test_questions SET question_type = 'fill_blank' WHERE question_type NOT IN ('fill_blank','multiple_choice','multiple_select','matching','true_false_ng','yes_no_ng','short_answer','essay','diagram_label','sentence_completion','note_completion','table_completion','flow_chart','summary_completion')");
        DB::statement("ALTER TABLE ielts_test_questions MODIFY COLUMN question_type ENUM('fill_blank','multiple_choice','multiple_select','matching','true_false_ng','yes_no_ng','short_answer','essay','diagram_label','sentence_completion','note_completion','table_completion','flow_chart','summary_completion') NOT NULL");
    }
};
