<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGradingFieldsToIeltsTestAttempts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Nếu bảng chưa tồn tại thì bỏ qua migration này
        if (!Schema::hasTable('ielts_test_attempts')) {
            return;
        }

        Schema::table('ielts_test_attempts', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Writing grading fields
            |--------------------------------------------------------------------------
            */
            if (!Schema::hasColumn('ielts_test_attempts', 'writing_feedback')) {
                $table->text('writing_feedback')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'writing_criteria')) {
                $table->json('writing_criteria')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'writing_graded_by')) {
                $table->unsignedBigInteger('writing_graded_by')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'writing_graded_at')) {
                $table->integer('writing_graded_at')->nullable();
            }

            /*
            |--------------------------------------------------------------------------
            | Speaking grading fields
            |--------------------------------------------------------------------------
            */
            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_feedback')) {
                $table->text('speaking_feedback')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_criteria')) {
                $table->json('speaking_criteria')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_graded_by')) {
                $table->unsignedBigInteger('speaking_graded_by')->nullable();
            }

            if (!Schema::hasColumn('ielts_test_attempts', 'speaking_graded_at')) {
                $table->integer('speaking_graded_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Nếu bảng không tồn tại thì bỏ qua
        if (!Schema::hasTable('ielts_test_attempts')) {
            return;
        }

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