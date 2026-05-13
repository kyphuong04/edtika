<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | IELTS Mock Question Bank
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('ielts_mock_question_bank') &&
            !Schema::hasColumn('ielts_mock_question_bank', 'table_structure')
        ) {
            Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
                $table->json('table_structure')->nullable()->after('question_data');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | IELTS Practice Question Bank
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            !Schema::hasColumn('ielts_practice_question_bank', 'table_structure')
        ) {
            Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
                $table->json('table_structure')->nullable()->after('question_data');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('ielts_mock_question_bank') &&
            Schema::hasColumn('ielts_mock_question_bank', 'table_structure')
        ) {
            Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
                $table->dropColumn('table_structure');
            });
        }

        if (
            Schema::hasTable('ielts_practice_question_bank') &&
            Schema::hasColumn('ielts_practice_question_bank', 'table_structure')
        ) {
            Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
                $table->dropColumn('table_structure');
            });
        }
    }
};