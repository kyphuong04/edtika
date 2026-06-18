<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiveCourseStudentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('live_course_students')) {
            Schema::create('live_course_students', function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id');
                $table->integer('live_course_id')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('created_at')->unsigned()->nullable();

                $table->unique(['live_course_id', 'user_id']);

                $table->foreign('live_course_id')->references('id')->on('live_courses')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('live_course_students')) {
            Schema::table('live_course_students', function (Blueprint $table) {
                $table->dropForeign(['live_course_id']);
                $table->dropForeign(['user_id']);
            });

            Schema::dropIfExists('live_course_students');
        }
    }
}
