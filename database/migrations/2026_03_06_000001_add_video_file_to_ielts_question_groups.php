<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoFileToIeltsQuestionGroups extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip if table does not exist
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        // Skip if column already exists
        if (Schema::hasColumn('ielts_question_groups', 'video_file')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            // Add after audio_file if that column exists,
            // otherwise add at the end of the table.
            if (Schema::hasColumn('ielts_question_groups', 'audio_file')) {
                $table->string('video_file')->nullable()->after('audio_file');
            } else {
                $table->string('video_file')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Skip if table does not exist
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        // Skip if column does not exist
        if (!Schema::hasColumn('ielts_question_groups', 'video_file')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            $table->dropColumn('video_file');
        });
    }
}