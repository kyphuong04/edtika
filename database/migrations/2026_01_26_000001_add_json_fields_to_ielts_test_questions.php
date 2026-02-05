<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add table_structure and other JSON fields to ielts_test_questions for complex question types
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            // Add table_structure for table completion questions
            if (!Schema::hasColumn('ielts_test_questions', 'table_structure')) {
                $table->json('table_structure')->nullable()->after('correct_answer');
            }
            
            // Add flow_data for flowchart questions
            if (!Schema::hasColumn('ielts_test_questions', 'flow_data')) {
                $table->json('flow_data')->nullable()->after('table_structure');
            }
            
            // Add question_data for other complex question types
            if (!Schema::hasColumn('ielts_test_questions', 'question_data')) {
                $table->json('question_data')->nullable()->after('flow_data');
            }
        });
    }

    public function down()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            $columns = ['table_structure', 'flow_data', 'question_data'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('ielts_test_questions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
