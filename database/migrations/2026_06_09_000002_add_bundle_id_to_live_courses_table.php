<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBundleIdToLiveCoursesTable extends Migration
{
    public function up()
    {
        Schema::table('live_courses', function (Blueprint $table) {
            if (!Schema::hasColumn('live_courses', 'bundle_id')) {
                $table->integer('bundle_id')->unsigned()->nullable()->after('group_id');
                $table->foreign('bundle_id')->references('id')->on('bundles')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('live_courses', function (Blueprint $table) {
            if (Schema::hasColumn('live_courses', 'bundle_id')) {
                $table->dropForeign(['bundle_id']);
                $table->dropColumn('bundle_id');
            }
        });
    }
}
