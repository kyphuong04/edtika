<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWordProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('user_word_progress')) {
            Schema::create('user_word_progress', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->bigInteger('flashcard_id')->unsigned()->nullable();
                $table->bigInteger('word_list_id')->unsigned()->nullable();
                $table->string('word');
                $table->integer('practice_count')->default(0);
                $table->integer('correct_count')->default(0);
                $table->timestamp('last_practiced_at')->nullable();
                $table->boolean('is_learned')->default(false);
                $table->timestamp('learned_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('flashcard_id')->references('id')->on('user_flashcards')->onDelete('cascade');
                $table->foreign('word_list_id')->references('id')->on('word_lists')->onDelete('cascade');
                
                $table->index('user_id');
                $table->index('flashcard_id');
                $table->index('word_list_id');
                $table->index('last_practiced_at');
                $table->index(['user_id', 'word']);
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
        Schema::dropIfExists('user_word_progress');
    }
}
