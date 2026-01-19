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
        if (!Schema::hasTable('user_flashcards')) {
            Schema::create('user_flashcards', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('user_id')->unsigned();
                $table->string('word');
                $table->string('pronunciation')->nullable();
                $table->text('definition');
                $table->text('example')->nullable();
                $table->text('translation')->nullable();
                $table->timestamps();

                $table->index('user_id');
            });

            // Add foreign key after table creation
            Schema::table('user_flashcards', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_flashcards');
    }
}
