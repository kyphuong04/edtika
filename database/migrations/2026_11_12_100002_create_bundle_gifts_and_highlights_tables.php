<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ1 - Mục "Quà tặng khóa học" và slider "Chi tiết và nội dung" trên trang chi tiết.
 */
return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('bundle_gifts')) {
            Schema::create('bundle_gifts', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('bundle_id')->unsigned();
                $table->string('title');
                $table->string('icon', 64)->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->unsignedInteger('order')->default(0);
                $table->integer('created_at')->unsigned();
                $table->integer('updated_at')->unsigned()->nullable();

                $table->index(['bundle_id', 'order']);
                $table->foreign('bundle_id')->on('bundles')->references('id')->cascadeOnDelete();
            });
        }

        if (!Schema::hasTable('bundle_highlights')) {
            Schema::create('bundle_highlights', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->integer('bundle_id')->unsigned();
                $table->string('title');
                $table->text('description')->nullable();
                $table->unsignedInteger('order')->default(0);
                $table->integer('created_at')->unsigned();
                $table->integer('updated_at')->unsigned()->nullable();

                $table->index(['bundle_id', 'order']);
                $table->foreign('bundle_id')->on('bundles')->references('id')->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bundle_highlights');
        Schema::dropIfExists('bundle_gifts');
    }
};
