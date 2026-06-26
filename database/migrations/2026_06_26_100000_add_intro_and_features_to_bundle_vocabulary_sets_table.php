<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntroAndFeaturesToBundleVocabularySetsTable extends Migration
{
    public function up()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_vocabulary_sets', 'intro_content')) {
                $table->text('intro_content')->nullable()->after('description');
            }

            if (!Schema::hasColumn('bundle_vocabulary_sets', 'feature_content')) {
                $table->text('feature_content')->nullable()->after('intro_content');
            }
        });
    }

    public function down()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_vocabulary_sets', 'feature_content')) {
                $table->dropColumn('feature_content');
            }

            if (Schema::hasColumn('bundle_vocabulary_sets', 'intro_content')) {
                $table->dropColumn('intro_content');
            }
        });
    }
}
