<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->timestamp('current_step_started_at')->nullable()->after('started_at');
            $table->unsignedTinyInteger('scored_steps')->nullable()->after('final_level');
        });
    }

    public function down(): void
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->dropColumn(['current_step_started_at', 'scored_steps']);
        });
    }
};