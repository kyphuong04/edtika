<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoFileToIeltsQuestionGroups extends Migration
{
    public function up()
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            $table->string('video_file')->nullable()->after('audio_file');
        });
    }

    public function down()
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            $table->dropColumn('video_file');
        });
    }
}
