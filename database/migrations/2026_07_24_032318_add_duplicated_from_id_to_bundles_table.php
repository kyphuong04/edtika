<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDuplicatedFromIdToBundlesTable extends Migration
{
    public function up()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->unsignedBigInteger('duplicated_from_id')->nullable()->after('creator_id');

            $table->index('duplicated_from_id');
        });
    }

    public function down()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropIndex(['duplicated_from_id']);
            $table->dropColumn('duplicated_from_id');
        });
    }
}