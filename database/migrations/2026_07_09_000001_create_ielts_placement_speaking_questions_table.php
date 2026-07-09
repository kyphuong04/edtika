<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bank of Speaking questions used exclusively for the follow-up Speaking
 * step of the Adaptive Placement Test (after the 3 branching sub-tests).
 * One question is picked at random per attempt. Results here never affect
 * the placement's final_level — they are recorded for teacher review only.
 */
class CreateIeltsPlacementSpeakingQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('ielts_placement_speaking_questions', function (Blueprint $table) {
            $table->id();

            // The question/topic shown to the student, e.g.
            // "Describe a place you would like to visit and explain why."
            $table->text('prompt_text');

            // Optional extra guidance shown below the prompt.
            $table->text('instruction')->nullable();

            // How long the student gets to prepare before recording starts.
            $table->unsignedInteger('prep_time_seconds')->default(30);

            // How long the student is allowed to record their answer.
            $table->unsignedInteger('answer_time_seconds')->default(60);

            // Only 'active' questions are eligible for random selection.
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Which staff member added this question (nullable: seeded/legacy rows).
            $table->unsignedBigInteger('created_by')->nullable();

            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();

            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_placement_speaking_questions');
    }
}