<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartOfSpeechToUserFlashcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            $table->string('part_of_speech')->nullable()->after('word');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            $table->dropColumn('part_of_speech');
        });
    }
}
