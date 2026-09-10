<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->unsignedTinyInteger('practice_part_number')->nullable()->after('practice_scope');
        });
    }

    public function down(): void
    {
        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->dropColumn('practice_part_number');
        });
    }
};