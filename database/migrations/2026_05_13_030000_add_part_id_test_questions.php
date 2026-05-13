<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartIdToIeltsTestQuestionsTable extends Migration
{
    public function up()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('part_id')->nullable()->after('section_id');
            $table->foreign('part_id')->references('id')->on('ielts_test_parts')->onDelete('cascade');
            $table->index('part_id');
        });
    }

    public function down()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            $table->dropForeign(['part_id']);
            $table->dropColumn('part_id');
        });
    }
class AddPartIdTestQuestionsPart extends Migration
