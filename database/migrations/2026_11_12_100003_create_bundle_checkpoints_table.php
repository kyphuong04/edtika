<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ1 - Bài kiểm tra cấp bundle: Mid-term (mở sau N module) và Final (mở khi xong mọi module).
 */
return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('bundle_checkpoints')) {
            Schema::create('bundle_checkpoints', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('bundle_id')->unsigned();
                $table->string('type', 16); // mid | final (xem BundleCheckpoint)
                $table->string('title')->nullable();
                $table->integer('quiz_id')->unsigned()->nullable();
                // Số module cần hoàn thành để mở. NULL = tất cả module
                $table->unsignedTinyInteger('unlock_after_modules')->nullable();
                $table->unsignedInteger('order')->default(0);
                $table->integer('created_at')->unsigned();
                $table->integer('updated_at')->unsigned()->nullable();

                $table->index(['bundle_id', 'type']);
                $table->foreign('bundle_id')->on('bundles')->references('id')->cascadeOnDelete();
                $table->foreign('quiz_id')->on('quizzes')->references('id')->nullOnDelete();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bundle_checkpoints');
    }
};
