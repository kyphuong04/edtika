<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            // JSON: { "<scope_key>": { "budget_seconds": int|null, "used_seconds": int, "started_at": int|null } }
            // scope_key = tên skill ("listening"/"reading"/"writing") hoặc
            // "speaking-part-{partId}" cho từng Part của Speaking (Mock only).
            // null trên toàn cột = chưa khởi tạo (Practice test, hoặc attempt
            // tạo trước khi có tính năng này) -> coi như không giới hạn thời gian.
            $table->text('skill_time_budget')->nullable()->after('remaining_time_seconds');
        });
    }

    public function down(): void
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            $table->dropColumn('skill_time_budget');
        });
    }
};