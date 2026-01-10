<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add question_group_id to ielts_test_questions table
 * This allows questions to be grouped together for IDP-style display
 */
return new class extends Migration
{
    public function up()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_test_questions', 'question_group_id')) {
                $table->unsignedBigInteger('question_group_id')->nullable()->after('section_id');
                $table->index('question_group_id');
            }
            
            if (!Schema::hasColumn('ielts_test_questions', 'question_number')) {
                $table->integer('question_number')->nullable()->after('question_group_id');
            }
            
            if (!Schema::hasColumn('ielts_test_questions', 'question_order')) {
                $table->integer('question_order')->default(0)->after('question_number');
            }
        });
    }

    public function down()
    {
        Schema::table('ielts_test_questions', function (Blueprint $table) {
            if (Schema::hasColumn('ielts_test_questions', 'question_group_id')) {
                $table->dropColumn('question_group_id');
            }
            if (Schema::hasColumn('ielts_test_questions', 'question_number')) {
                $table->dropColumn('question_number');
            }
            if (Schema::hasColumn('ielts_test_questions', 'question_order')) {
                $table->dropColumn('question_order');
            }
        });
    }
};
