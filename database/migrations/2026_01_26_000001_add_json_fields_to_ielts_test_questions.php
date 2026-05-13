<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Nếu bảng không tồn tại thì bỏ qua migration
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        Schema::table('ielts_test_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_test_questions', 'table_structure')) {
                $table->json('table_structure')->nullable()->after('correct_answer');
            }

            if (!Schema::hasColumn('ielts_test_questions', 'flow_data')) {
                $table->json('flow_data')->nullable()->after('table_structure');
            }

            if (!Schema::hasColumn('ielts_test_questions', 'question_data')) {
                $table->json('question_data')->nullable()->after('flow_data');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        Schema::table('ielts_test_questions', function (Blueprint $table) {
            $columns = ['table_structure', 'flow_data', 'question_data'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('ielts_test_questions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};