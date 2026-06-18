<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveCoursesTable extends Migration
{
    public function up()
    {
        Schema::create('live_courses', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('creator_id')->unsigned();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('language', 10)->nullable();
            $table->enum('session_api', ['local', 'zoom'])->default('local');
            $table->string('link', 500)->nullable();
            $table->string('api_secret', 255)->nullable();
            $table->integer('date')->unsigned()->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->integer('extra_time_to_join')->unsigned()->nullable();
            $table->integer('group_id')->unsigned()->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('created_at')->unsigned();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_courses');
    }
}
