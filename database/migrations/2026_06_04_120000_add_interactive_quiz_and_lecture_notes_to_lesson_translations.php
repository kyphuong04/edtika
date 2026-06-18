<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('file_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('file_translations', 'interactive_quiz')) {
                $table->longText('interactive_quiz')->nullable()->after('description');
            }

            if (!Schema::hasColumn('file_translations', 'lecture_notes')) {
                $table->longText('lecture_notes')->nullable()->after('interactive_quiz');
            }
        });

        Schema::table('text_lesson_translations', function (Blueprint $table) {
            if (!Schema::hasColumn('text_lesson_translations', 'interactive_quiz')) {
                $table->longText('interactive_quiz')->nullable()->after('content');
            }

            if (!Schema::hasColumn('text_lesson_translations', 'lecture_notes')) {
                $table->longText('lecture_notes')->nullable()->after('interactive_quiz');
            }
        });
    }

    public function down(): void
    {
        Schema::table('file_translations', function (Blueprint $table) {
            if (Schema::hasColumn('file_translations', 'lecture_notes')) {
                $table->dropColumn('lecture_notes');
            }

            if (Schema::hasColumn('file_translations', 'interactive_quiz')) {
                $table->dropColumn('interactive_quiz');
            }
        });

        Schema::table('text_lesson_translations', function (Blueprint $table) {
            if (Schema::hasColumn('text_lesson_translations', 'lecture_notes')) {
                $table->dropColumn('lecture_notes');
            }

            if (Schema::hasColumn('text_lesson_translations', 'interactive_quiz')) {
                $table->dropColumn('interactive_quiz');
            }
        });
    }
};
