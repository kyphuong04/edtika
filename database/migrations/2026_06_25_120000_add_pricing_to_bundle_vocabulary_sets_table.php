<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPricingToBundleVocabularySetsTable extends Migration
{
    public function up()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_vocabulary_sets', 'original_price')) {
                $table->decimal('original_price', 12, 2)->nullable()->after('description');
            }

            if (!Schema::hasColumn('bundle_vocabulary_sets', 'sale_price')) {
                $table->decimal('sale_price', 12, 2)->nullable()->after('original_price');
            }

            if (!Schema::hasColumn('bundle_vocabulary_sets', 'is_published_global')) {
                $table->boolean('is_published_global')->default(false)->after('sale_price');
                $table->index('is_published_global');
            }
        });
    }

    public function down()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_vocabulary_sets', 'is_published_global')) {
                $table->dropIndex(['is_published_global']);
                $table->dropColumn('is_published_global');
            }

            if (Schema::hasColumn('bundle_vocabulary_sets', 'sale_price')) {
                $table->dropColumn('sale_price');
            }

            if (Schema::hasColumn('bundle_vocabulary_sets', 'original_price')) {
                $table->dropColumn('original_price');
            }
        });
    }
}
