<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInteractiveQuizAndLectureNotesToLessonTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('text_lesson_translations')) {
            if (!Schema::hasColumn('text_lesson_translations', 'interactive_quiz')) {
                Schema::table('text_lesson_translations', function (Blueprint $table) {
                    $table->json('interactive_quiz')->nullable()->after('content');
                });
            }

            if (!Schema::hasColumn('text_lesson_translations', 'lecture_notes')) {
                $afterColumn = Schema::hasColumn('text_lesson_translations', 'interactive_quiz') ? 'interactive_quiz' : 'content';

                Schema::table('text_lesson_translations', function (Blueprint $table) use ($afterColumn) {
                    $table->json('lecture_notes')->nullable()->after($afterColumn);
                });
            }
        }

        if (Schema::hasTable('file_translations')) {
            if (!Schema::hasColumn('file_translations', 'interactive_quiz')) {
                Schema::table('file_translations', function (Blueprint $table) {
                    $table->json('interactive_quiz')->nullable()->after('description');
                });
            }

            if (!Schema::hasColumn('file_translations', 'lecture_notes')) {
                $afterColumn = Schema::hasColumn('file_translations', 'interactive_quiz') ? 'interactive_quiz' : 'description';

                Schema::table('file_translations', function (Blueprint $table) use ($afterColumn) {
                    $table->json('lecture_notes')->nullable()->after($afterColumn);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('text_lesson_translations')) {
            if (Schema::hasColumn('text_lesson_translations', 'lecture_notes')) {
                Schema::table('text_lesson_translations', function (Blueprint $table) {
                    $table->dropColumn('lecture_notes');
                });
            }

            if (Schema::hasColumn('text_lesson_translations', 'interactive_quiz')) {
                Schema::table('text_lesson_translations', function (Blueprint $table) {
                    $table->dropColumn('interactive_quiz');
                });
            }
        }

        if (Schema::hasTable('file_translations')) {
            if (Schema::hasColumn('file_translations', 'lecture_notes')) {
                Schema::table('file_translations', function (Blueprint $table) {
                    $table->dropColumn('lecture_notes');
                });
            }

            if (Schema::hasColumn('file_translations', 'interactive_quiz')) {
                Schema::table('file_translations', function (Blueprint $table) {
                    $table->dropColumn('interactive_quiz');
                });
            }
        }
    }
}
