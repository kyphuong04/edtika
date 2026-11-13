<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ1 - Bổ sung cho nội dung học:
 * - webinars.skill: kỹ năng của module (bộ lọc "Khóa học của tôi")
 * - files.duration_seconds: thời lượng video
 * - quizzes.file_id + show_at_second: Mini Quiz bật ra tại mốc video
 * - bundle_vocabulary_sets.chapter_id: Word List (Pre Lesson) theo từng lesson
 */
return new class extends Migration {
    public function up()
    {
        Schema::table('webinars', function (Blueprint $table) {
            if (!Schema::hasColumn('webinars', 'skill')) {
                // listening | reading | writing | speaking (xem Webinar::SKILLS)
                $table->string('skill', 32)->nullable();
            }
        });

        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'duration_seconds')) {
                $table->unsignedInteger('duration_seconds')->nullable();
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (!Schema::hasColumn('quizzes', 'file_id')) {
                $table->integer('file_id')->unsigned()->nullable();
                $table->foreign('file_id')->on('files')->references('id')->nullOnDelete();
            }
            if (!Schema::hasColumn('quizzes', 'show_at_second')) {
                $table->unsignedInteger('show_at_second')->nullable();
            }
        });

        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_vocabulary_sets', 'chapter_id')) {
                $table->integer('chapter_id')->unsigned()->nullable()->after('bundle_id');
                $table->foreign('chapter_id')->on('webinar_chapters')->references('id')->nullOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_vocabulary_sets', 'chapter_id')) {
                $table->dropForeign(['chapter_id']);
                $table->dropColumn('chapter_id');
            }
        });

        Schema::table('quizzes', function (Blueprint $table) {
            if (Schema::hasColumn('quizzes', 'file_id')) {
                $table->dropForeign(['file_id']);
                $table->dropColumn('file_id');
            }
            if (Schema::hasColumn('quizzes', 'show_at_second')) {
                $table->dropColumn('show_at_second');
            }
        });

        Schema::table('files', function (Blueprint $table) {
            if (Schema::hasColumn('files', 'duration_seconds')) {
                $table->dropColumn('duration_seconds');
            }
        });

        Schema::table('webinars', function (Blueprint $table) {
            if (Schema::hasColumn('webinars', 'skill')) {
                $table->dropColumn('skill');
            }
        });
    }
};
