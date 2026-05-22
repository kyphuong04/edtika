<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIeltsTestFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ielts_test_feedbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('test_id');
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('message');
            $table->integer('created_at')->nullable();

            $table->index('test_id');
            $table->index('sender_id');

            // foreign keys optional to avoid migrations ordering issues
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ielts_test_feedbacks');
    }
}
