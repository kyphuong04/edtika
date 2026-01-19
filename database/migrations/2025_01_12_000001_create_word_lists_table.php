<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('word_lists')) {
            Schema::create('word_lists', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('category')->nullable(); // e.g., 'ielts', 'general', 'custom'
                $table->string('level')->nullable(); // e.g., 'band_4.5', 'band_5.0', etc.
                $table->boolean('is_public')->default(false);
                $table->integer('word_count')->default(0);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('user_id');
                $table->index('category');
                $table->index('level');
            });
        }

        // Pivot table for word_list and flashcard relationship
        if (!Schema::hasTable('flashcard_word_list')) {
            Schema::create('flashcard_word_list', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('word_list_id')->unsigned();
                $table->bigInteger('flashcard_id')->unsigned();
                $table->integer('order')->default(0);
                $table->timestamps();

                $table->foreign('word_list_id')->references('id')->on('word_lists')->onDelete('cascade');
                $table->foreign('flashcard_id')->references('id')->on('user_flashcards')->onDelete('cascade');
                
                $table->unique(['word_list_id', 'flashcard_id']);
                $table->index('word_list_id');
                $table->index('flashcard_id');
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
        Schema::dropIfExists('flashcard_word_list');
        Schema::dropIfExists('word_lists');
    }
}
