<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Folder phân cấp bộ từ vựng ở tab Academic Word List.
 * Teacher (người tạo bộ từ) tự tạo folder, không cần manager duyệt; khi đăng
 * bộ từ bắt buộc chọn 1 folder của chính mình. Học viên chỉ thấy folder có
 * ít nhất 1 bộ từ mình được học (đã mua khoá + bộ đã duyệt).
 */
class CreateVocabularyFoldersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('vocabulary_folders')) {
            Schema::create('vocabulary_folders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('created_by');
                $table->string('name', 120);
                $table->string('description', 500)->nullable();
                $table->string('level', 20)->nullable();      // A1..C2 hoặc trống
                $table->string('skill', 100)->nullable();     // vd: IELTS Vocabulary
                $table->string('icon', 30)->default('book');
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['created_by', 'sort_order']);
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('bundle_vocabulary_sets') && !Schema::hasColumn('bundle_vocabulary_sets', 'folder_id')) {
            Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
                $table->unsignedBigInteger('folder_id')->nullable()->after('bundle_id');
                $table->index('folder_id');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bundle_vocabulary_sets') && Schema::hasColumn('bundle_vocabulary_sets', 'folder_id')) {
            Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
                $table->dropIndex(['folder_id']);
                $table->dropColumn('folder_id');
            });
        }

        Schema::dropIfExists('vocabulary_folders');
    }
}
