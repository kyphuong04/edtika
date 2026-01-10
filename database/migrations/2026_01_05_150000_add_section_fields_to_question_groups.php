<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add section_id and question number ranges to question groups
     */
    public function up(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_question_groups', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_type')) {
                $table->string('question_type', 100)->nullable()->after('skill');
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_start')) {
                $table->integer('question_start')->nullable()->after('question_type');
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_end')) {
                $table->integer('question_end')->nullable()->after('question_start');
            }

            if (!Schema::hasColumn('ielts_question_groups', 'max_words')) {
                $table->integer('max_words')->nullable()->after('instructions');
            }
        });

        // Add foreign key separately after ensuring column exists
        if (Schema::hasColumn('ielts_question_groups', 'section_id') && 
            Schema::hasTable('ielts_test_sections')) {
            try {
                Schema::table('ielts_question_groups', function (Blueprint $table) {
                    $table->foreign('section_id')
                          ->references('id')
                          ->on('ielts_test_sections')
                          ->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist or constraint error
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ielts_question_groups', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
            if (Schema::hasColumn('ielts_question_groups', 'question_type')) {
                $table->dropColumn('question_type');
            }
            if (Schema::hasColumn('ielts_question_groups', 'question_start')) {
                $table->dropColumn('question_start');
            }
            if (Schema::hasColumn('ielts_question_groups', 'question_end')) {
                $table->dropColumn('question_end');
            }
            if (Schema::hasColumn('ielts_question_groups', 'max_words')) {
                $table->dropColumn('max_words');
            }
        });
    }
};
