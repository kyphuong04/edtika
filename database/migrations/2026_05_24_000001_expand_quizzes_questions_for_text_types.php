<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes_questions', 'question_data')) {
                // add JSON column without relying on a specific column order
                $table->json('question_data')->nullable();
            }
        });

        if (Schema::hasColumn('quizzes_questions', 'type') && DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quizzes_questions MODIFY type VARCHAR(50) NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('quizzes_questions', 'type') && DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE quizzes_questions MODIFY type ENUM('multiple', 'descriptive') NOT NULL");
        }

        Schema::table('quizzes_questions', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes_questions', 'question_data')) {
                $table->dropColumn('question_data');
            }
        });
    }
};