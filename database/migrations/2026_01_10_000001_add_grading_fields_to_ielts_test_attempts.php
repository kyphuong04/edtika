<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradingFieldsToIeltsTestAttempts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            // Writing grading fields
            if (!Schema::hasColumn('ielts_test_attempts', 'writing_feedback')) {
                $table->text('writing_feedback')->nullable()->after('writing_score');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'writing_criteria')) {
                $table->json('writing_criteria')->nullable()->after('writing_feedback');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'writing_graded_by')) {
                $table->unsignedBigInteger('writing_graded_by')->nullable()->after('writing_criteria');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'writing_graded_at')) {
                $table->integer('writing_graded_at')->nullable()->after('writing_graded_by');
            }
            
            // Speaking grading fields
            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_feedback')) {
                $table->text('speaking_feedback')->nullable()->after('speaking_score');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_criteria')) {
                $table->json('speaking_criteria')->nullable()->after('speaking_feedback');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_graded_by')) {
                $table->unsignedBigInteger('speaking_graded_by')->nullable()->after('speaking_criteria');
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_graded_at')) {
                $table->integer('speaking_graded_at')->nullable()->after('speaking_graded_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            $columns = [
                'writing_feedback',
                'writing_criteria',
                'writing_graded_by',
                'writing_graded_at',
                'speaking_feedback',
                'speaking_criteria',
                'speaking_graded_by',
                'speaking_graded_at',
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('ielts_test_attempts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
