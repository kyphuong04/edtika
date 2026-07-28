<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHiddenAtAndPublishedAtToBundlesTable extends Migration
{
    public function up()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->unsignedBigInteger('hidden_at')->nullable()->after('status');
            $table->unsignedBigInteger('published_at')->nullable()->after('hidden_at');
        });
    }

    public function down()
    {
        Schema::table('bundles', function (Blueprint $table) {
            $table->dropColumn(['hidden_at', 'published_at']);
        });
    }
}