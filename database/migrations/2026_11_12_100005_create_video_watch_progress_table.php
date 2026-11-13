<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ1 - Ghi nhận học viên xem video thật (thay cho việc tự tick "đã học").
 * Khi xem đủ ngưỡng (config bundle_learning.video_complete_ratio) sẽ tạo dòng course_learning.
 */
return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('video_watch_progress')) {
            Schema::create('video_watch_progress', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->integer('file_id')->unsigned();
                $table->unsignedInteger('last_position')->default(0);   // giây, vị trí xem gần nhất
                $table->unsignedInteger('max_position')->default(0);    // giây, vị trí xa nhất đã xem
                $table->unsignedInteger('duration_seconds')->nullable();
                $table->integer('completed_at')->unsigned()->nullable();
                $table->integer('created_at')->unsigned();
                $table->integer('updated_at')->unsigned()->nullable();

                $table->unique(['user_id', 'file_id']);
                $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
                $table->foreign('file_id')->on('files')->references('id')->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('video_watch_progress');
    }
};
