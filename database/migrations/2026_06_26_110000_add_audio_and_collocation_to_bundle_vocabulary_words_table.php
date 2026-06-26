<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudioAndCollocationToBundleVocabularyWordsTable extends Migration
{
    public function up()
    {
        Schema::table('bundle_vocabulary_words', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_vocabulary_words', 'audio_url')) {
                $table->text('audio_url')->nullable()->after('image_url');
            }

            if (!Schema::hasColumn('bundle_vocabulary_words', 'collocation')) {
                $table->text('collocation')->nullable()->after('audio_url');
            }
        });
    }

    public function down()
    {
        Schema::table('bundle_vocabulary_words', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_vocabulary_words', 'collocation')) {
                $table->dropColumn('collocation');
            }

            if (Schema::hasColumn('bundle_vocabulary_words', 'audio_url')) {
                $table->dropColumn('audio_url');
            }
        });
    }
}
