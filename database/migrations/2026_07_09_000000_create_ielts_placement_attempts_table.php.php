<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores the full result of one Adaptive Placement Test attempt.
 *
 * One row = one complete attempt by a student: the 3 branching sub-tests
 * taken (each picked adaptively based on the score of the previous one),
 * the final CEFR level reached, and the follow-up Speaking recording for
 * teacher review. This result is permanent and tied to the student's
 * profile, since it affects the company's contract / outcome commitment.
 */
class CreateIeltsPlacementAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('ielts_placement_attempts', function (Blueprint $table) {
            $table->id();

            // Who took the test
            $table->unsignedBigInteger('user_id');

            // Overall attempt lifecycle status
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');

            // Which step (1, 2, or 3) the student is currently on / last completed
            $table->unsignedTinyInteger('current_step')->default(1);

            // ---- Step 1 — always starts at B1 ----
            $table->unsignedBigInteger('step_1_test_id')->nullable();
            $table->unsignedBigInteger('step_1_attempt_id')->nullable();
            $table->string('step_1_level', 5)->nullable();           // A1 / A2 / B1 / B2 / B2+
            $table->unsignedTinyInteger('step_1_score')->nullable(); // correct answers out of 10

            // ---- Step 2 ----
            $table->unsignedBigInteger('step_2_test_id')->nullable();
            $table->unsignedBigInteger('step_2_attempt_id')->nullable();
            $table->string('step_2_level', 5)->nullable();
            $table->unsignedTinyInteger('step_2_score')->nullable();

            // ---- Step 3 ----
            $table->unsignedBigInteger('step_3_test_id')->nullable();
            $table->unsignedBigInteger('step_3_attempt_id')->nullable();
            $table->string('step_3_level', 5)->nullable();
            $table->unsignedTinyInteger('step_3_score')->nullable();

            // Final CEFR level determined by the decision tree — this is the
            // official placement result used for contracts / commitments.
            $table->string('final_level', 5)->nullable();

            // ---- Speaking follow-up (does NOT affect final_level) ----
            $table->unsignedBigInteger('speaking_question_id')->nullable();
            $table->string('speaking_audio_path', 500)->nullable();
            $table->unsignedBigInteger('speaking_reviewed_by')->nullable(); // staff who listened
            $table->integer('speaking_reviewed_at')->nullable();
            $table->text('speaking_review_notes')->nullable();             // advising notes

            // Timing (30 min total for the 3 sub-tests: 10 min each)
            $table->integer('started_at')->nullable();
            $table->integer('completed_at')->nullable();
            $table->unsignedInteger('total_time_seconds')->nullable();

            // Timestamps — integer unix time, matching this codebase's convention
            // (all other Ielts* models use $timestamps = false with int casts).
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();

            $table->index('user_id');
            $table->index('status');
            $table->index('final_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_placement_attempts');
    }
}