<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('placement_tests', function (Blueprint $table) {
            $table->json('reading_passages')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('placement_tests', function (Blueprint $table) {
            $table->dropColumn('reading_passages');
        });
    }
};