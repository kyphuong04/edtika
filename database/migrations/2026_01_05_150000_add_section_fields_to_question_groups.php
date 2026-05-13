<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add section fields to IELTS question groups
     */
    public function up(): void
    {
        // Nếu bảng chưa tồn tại thì bỏ qua migration này
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_question_groups', 'section_id')) {
                $table->unsignedBigInteger('section_id')
                    ->nullable()
                    ->after('id');
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_type')) {
                $table->string('question_type', 100)
                    ->nullable();
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_start')) {
                $table->integer('question_start')
                    ->nullable();
            }

            if (!Schema::hasColumn('ielts_question_groups', 'question_end')) {
                $table->integer('question_end')
                    ->nullable();
            }

            if (!Schema::hasColumn('ielts_question_groups', 'max_words')) {
                $table->integer('max_words')
                    ->nullable();
            }
        });

        // Chỉ thêm foreign key nếu cả 2 bảng đều tồn tại
        if (
            Schema::hasTable('ielts_question_groups') &&
            Schema::hasTable('ielts_test_sections') &&
            Schema::hasColumn('ielts_question_groups', 'section_id')
        ) {
            try {
                Schema::table('ielts_question_groups', function (Blueprint $table) {
                    $table->foreign('section_id')
                        ->references('id')
                        ->on('ielts_test_sections')
                        ->onDelete('cascade');
                });
            } catch (\Throwable $e) {
                // Bỏ qua nếu foreign key đã tồn tại
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nếu bảng không tồn tại thì bỏ qua
        if (!Schema::hasTable('ielts_question_groups')) {
            return;
        }

        // Drop foreign key nếu tồn tại
        if (Schema::hasColumn('ielts_question_groups', 'section_id')) {
            try {
                Schema::table('ielts_question_groups', function (Blueprint $table) {
                    $table->dropForeign(['section_id']);
                });
            } catch (\Throwable $e) {
                // Ignore if foreign key doesn't exist
            }
        }

        Schema::table('ielts_question_groups', function (Blueprint $table) {
            foreach ([
                'section_id',
                'question_type',
                'question_start',
                'question_end',
                'max_words',
            ] as $column) {
                if (Schema::hasColumn('ielts_question_groups', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};