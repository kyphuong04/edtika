<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupIdToIeltsPracticeQuestionBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            // Add group_id column after id
            $table->bigInteger('group_id')->unsigned()->nullable()->after('id');
            
            // Add index for performance
            $table->index('group_id');
            
            // Add foreign key constraint
            $table->foreign('group_id')
                  ->references('id')
                  ->on('ielts_question_groups')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['group_id']);
            
            // Drop index
            $table->dropIndex(['group_id']);
            
            // Drop column
            $table->dropColumn('group_id');
        });
    }
}
