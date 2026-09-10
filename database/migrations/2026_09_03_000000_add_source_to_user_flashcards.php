<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceToUserFlashcards extends Migration
{
    public function up()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            $table->string('source', 20)->default('user')->after('user_id')->index();
            $table->unsignedBigInteger('bundle_vocabulary_word_id')->nullable()->after('source')->index();
            $table->unsignedBigInteger('vocabulary_set_id')->nullable()->after('bundle_vocabulary_word_id')->index();
        });
    }

    public function down()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            $table->dropColumn(['source', 'bundle_vocabulary_word_id', 'vocabulary_set_id']);
        });
    }
}