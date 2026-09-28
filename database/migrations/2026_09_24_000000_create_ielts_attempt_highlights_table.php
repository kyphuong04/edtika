<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIeltsAttemptHighlightsTable extends Migration
{
    public function up()
    {
        Schema::create('ielts_attempt_highlights', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attempt_id');
            $table->unsignedInteger('test_id');
            $table->unsignedInteger('part_id');
            // Offset trong TEXT THUẦN của passage thuộc part này.
            $table->unsignedInteger('start_offset');
            $table->unsignedInteger('end_offset');
            // Chuỗi đối chiếu: passage bị sửa -> bỏ qua thay vì tô nhầm chỗ.
            $table->string('text', 500);
            $table->text('note')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->index(['attempt_id', 'part_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_attempt_highlights');
    }
}