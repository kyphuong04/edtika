<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActionUrlToNotificationsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('notifications', 'action_url')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->text('action_url')->nullable()->after('message');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('notifications', 'action_url')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn('action_url');
            });
        }
    }
}