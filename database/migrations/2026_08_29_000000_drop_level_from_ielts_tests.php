<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }

    public function down(): void
    {
        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->nullable()->after('difficulty_level');
        });
    }
};