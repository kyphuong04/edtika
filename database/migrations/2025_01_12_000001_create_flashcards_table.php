<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('flashcards')) {
            Schema::create('flashcards', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('user_id')->unsigned();
                $table->string('word');
                $table->string('pronunciation')->nullable();
                $table->text('definition');
                $table->text('example')->nullable();
                $table->text('translation')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->on('users')->references('id')->cascadeOnDelete();
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flashcards');
    }
}
