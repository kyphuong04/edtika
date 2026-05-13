<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestParts extends Migration
{
    public function up()
    {
        Schema::create('ielts_test_parts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('section_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->text('passage')->nullable();
            $table->text('transcript')->nullable();
            $table->string('audio_file')->nullable();
            $table->string('task_image')->nullable();
            $table->string('video_file')->nullable();
            $table->integer('sort_order')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();

            $table->foreign('section_id')->references('id')->on('ielts_test_sections')->onDelete('cascade');
            $table->index('section_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_test_parts');
    }
}
