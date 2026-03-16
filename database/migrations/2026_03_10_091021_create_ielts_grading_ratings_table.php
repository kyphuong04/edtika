<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ielts_grading_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attempt_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('instructor_id');
            $table->string('skill', 20); // 'writing' or 'speaking'
            $table->tinyInteger('rating'); // 1–5
            $table->timestamps();

            $table->unique(['attempt_id', 'student_id', 'skill']);
            $table->index('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ielts_grading_ratings');
    }
};
