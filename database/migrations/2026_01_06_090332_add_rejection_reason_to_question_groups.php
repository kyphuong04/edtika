<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nếu bảng chưa tồn tại thì bỏ qua migration
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            // Chỉ thêm cột nếu chưa tồn tại
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
        // Nếu bảng chưa tồn tại thì bỏ qua
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            // Chỉ xóa cột nếu cột tồn tại
            if (Schema::hasColumn('ielts_question_groups', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};