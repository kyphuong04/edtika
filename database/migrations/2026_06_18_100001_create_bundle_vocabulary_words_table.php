<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBundleVocabularyWordsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bundle_vocabulary_words')) {
            Schema::create('bundle_vocabulary_words', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('vocabulary_set_id');
                $table->string('word');
                $table->string('part_of_speech', 50)->nullable();
                $table->string('pronunciation', 255)->nullable();
                $table->text('definition')->nullable();
                $table->text('translation_vi')->nullable();
                $table->text('example')->nullable();
                $table->text('image_url')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index('vocabulary_set_id');
                $table->index('word');
                $table->unique(['vocabulary_set_id', 'word', 'part_of_speech'], 'bundle_vocab_unique_word_pos');

                $table->foreign('vocabulary_set_id')->references('id')->on('bundle_vocabulary_sets')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('bundle_vocabulary_words');
    }
}
