<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lịch sử tra từ của học viên ở Dictionary Hub (hiện khi bấm vào ô tìm kiếm).
 * Mỗi từ chỉ 1 dòng / người dùng: tra lại thì cập nhật searched_at.
 * Thời gian lưu dạng Unix timestamp (int) giống các bảng mới khác.
 */
class CreateDictionarySearchHistoriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('dictionary_search_histories')) {
            return;
        }

        Schema::create('dictionary_search_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('query', 100);
            $table->unsignedInteger('searched_at');
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->unique(['user_id', 'query']);
            $table->index(['user_id', 'searched_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('dictionary_search_histories');
    }
}
