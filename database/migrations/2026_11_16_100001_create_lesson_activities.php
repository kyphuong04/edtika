<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ6 - Pre Lesson, Bài Tập, Post Lesson trong từng lesson của khóa học:
 * - ielts_tests.type thêm 'lesson': loại đề "Bài tập khóa học" (chỉ Listening/Reading), chỉ dùng gắn vào lesson,
 *   không hiện ở trang Luyện đề.
 * - webinar_chapters.pre_vocabulary_set_id: bộ từ (trong Từ điển của teacher) dùng làm Pre Lesson của lesson.
 * - bundle_lesson_tests: đề gắn vào lesson với vai trò 'exercise' (Bài Tập - luyện thêm) hoặc 'post' (Post Lesson).
 * - bundle_pre_lesson_progress: học viên đã hoàn thành Pre Lesson của lesson nào + Word List flashcard của khóa.
 */
return new class extends Migration {
    public function up()
    {
        DB::statement("ALTER TABLE ielts_tests MODIFY `type` ENUM('mock','practice','diagnostic','lesson') NOT NULL");

        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (!Schema::hasColumn('webinar_chapters', 'pre_vocabulary_set_id')) {
                $table->unsignedBigInteger('pre_vocabulary_set_id')->nullable()->index();
            }
        });

        if (!Schema::hasTable('bundle_lesson_tests')) {
            Schema::create('bundle_lesson_tests', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('chapter_id')->index();
                $table->unsignedBigInteger('ielts_test_id')->index();
                $table->string('role', 16); // exercise | post
                $table->unsignedInteger('sort_order')->default(0);
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('created_at')->nullable();

                $table->unique(['chapter_id', 'ielts_test_id', 'role'], 'bundle_lesson_tests_unique');
            });
        }

        if (!Schema::hasTable('bundle_pre_lesson_progress')) {
            Schema::create('bundle_pre_lesson_progress', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('bundle_id')->index();
                $table->unsignedInteger('chapter_id');
                $table->unsignedBigInteger('vocabulary_set_id')->nullable();
                // Word List (folder flashcard) "Khóa học: ..." của học viên chứa các từ của khóa
                $table->unsignedBigInteger('word_list_id')->nullable();
                $table->unsignedInteger('completed_at')->nullable();
                $table->unsignedInteger('created_at')->nullable();
                $table->unsignedInteger('updated_at')->nullable();

                $table->unique(['user_id', 'chapter_id'], 'bundle_pre_lesson_progress_unique');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bundle_pre_lesson_progress');
        Schema::dropIfExists('bundle_lesson_tests');

        Schema::table('webinar_chapters', function (Blueprint $table) {
            if (Schema::hasColumn('webinar_chapters', 'pre_vocabulary_set_id')) {
                $table->dropColumn('pre_vocabulary_set_id');
            }
        });

        // Chỉ thu hẹp lại enum khi không còn đề loại 'lesson' (tránh lỗi / mất dữ liệu)
        if (!DB::table('ielts_tests')->where('type', 'lesson')->exists()) {
            DB::statement("ALTER TABLE ielts_tests MODIFY `type` ENUM('mock','practice','diagnostic') NOT NULL");
        }
    }
};
