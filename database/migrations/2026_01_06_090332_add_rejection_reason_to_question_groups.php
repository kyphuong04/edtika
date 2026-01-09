<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_question_groups', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ielts_question_groups', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};
