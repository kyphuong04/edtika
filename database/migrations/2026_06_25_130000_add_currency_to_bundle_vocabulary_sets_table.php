<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyToBundleVocabularySetsTable extends Migration
{
    public function up()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (!Schema::hasColumn('bundle_vocabulary_sets', 'currency_code')) {
                $table->string('currency_code', 3)->default('VND')->after('sale_price');
                $table->index('currency_code');
            }
        });
    }

    public function down()
    {
        Schema::table('bundle_vocabulary_sets', function (Blueprint $table) {
            if (Schema::hasColumn('bundle_vocabulary_sets', 'currency_code')) {
                $table->dropIndex(['currency_code']);
                $table->dropColumn('currency_code');
            }
        });
    }
}
