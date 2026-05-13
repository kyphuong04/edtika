<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add question_group_id and related fields to ielts_test_questions.
 *
 * Migration này sẽ tự động bỏ qua nếu bảng ielts_test_questions chưa tồn tại.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Nếu bảng chưa tồn tại thì bỏ qua migration
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        Schema::table('ielts_test_questions', function (Blueprint $table) {
            // question_group_id
            if (!Schema::hasColumn('ielts_test_questions', 'question_group_id')) {
                $table->unsignedBigInteger('question_group_id')
                    ->nullable();
                $table->index('question_group_id');
            }

            // question_number
            if (!Schema::hasColumn('ielts_test_questions', 'question_number')) {
                $table->integer('question_number')
                    ->nullable();
            }

            // question_order
            if (!Schema::hasColumn('ielts_test_questions', 'question_order')) {
                $table->integer('question_order')
                    ->default(0);
            }
        });
    }

    public function down(): void
    {
        // Nếu bảng không tồn tại thì bỏ qua
        if (!Schema::hasTable('ielts_test_questions')) {
            return;
        }

        Schema::table('ielts_test_questions', function (Blueprint $table) {
            // Drop index trước nếu tồn tại
            try {
                $table->dropIndex(['question_group_id']);
            } catch (\Throwable $e) {
                // Ignore if index does not exist
            }

            // Drop columns nếu tồn tại
            foreach ([
                'question_group_id',
                'question_number',
                'question_order',
            ] as $column) {
                if (Schema::hasColumn('ielts_test_questions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};