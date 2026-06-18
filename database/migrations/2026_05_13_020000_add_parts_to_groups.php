<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPartsToGroups extends Migration
{
    public function up()
    {
        if (empty(DB::select("SHOW COLUMNS FROM ielts_question_groups LIKE 'part_id'"))) {
            Schema::table('ielts_question_groups', function (Blueprint $table) {
                $table->unsignedBigInteger('part_id')->nullable()->after('section_id');
                $table->foreign('part_id')->references('id')->on('ielts_test_parts')->onDelete('cascade');
                $table->index('part_id');
            });
        }
    }

    public function down()
    {
        if (!empty(DB::select("SHOW COLUMNS FROM ielts_question_groups LIKE 'part_id'"))) {
            Schema::table('ielts_question_groups', function (Blueprint $table) {
                $table->dropForeign(['part_id']);
                $table->dropColumn('part_id');
            });
        }
    }
}
