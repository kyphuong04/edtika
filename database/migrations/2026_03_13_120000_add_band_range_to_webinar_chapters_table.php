<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBandRangeToWebinarChaptersTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('webinar_chapters', 'band_range')) {
            Schema::table('webinar_chapters', function (Blueprint $table) {
                $table->string('band_range', 20)->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('webinar_chapters', 'band_range')) {
            Schema::table('webinar_chapters', function (Blueprint $table) {
                $table->dropColumn('band_range');
            });
        }
    }
}