<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | IELTS Mock Question Bank
        |--------------------------------------------------------------------------
        | Chỉ chạy nếu bảng tồn tại
        */
        if (Schema::hasTable('ielts_mock_question_bank')) {
            Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
                if (!Schema::hasColumn('ielts_mock_question_bank', 'question_type')) {
                    $table->string('question_type', 50)
                          ->nullable()
                          ->after('group_id');
                }

                if (!Schema::hasColumn('ielts_mock_question_bank', 'question_data')) {
                    $table->json('question_data')->nullable();
                }

                if (!Schema::hasColumn('ielts_mock_question_bank', 'word_limit')) {
                    $table->string('word_limit', 50)->nullable();
                }

                if (!Schema::hasColumn('ielts_mock_question_bank', 'marks')) {
                    $table->decimal('marks', 4, 1)->default(1.0);
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | IELTS Practice Question Bank
        |--------------------------------------------------------------------------
        | Chỉ chạy nếu bảng tồn tại
        */
        if (Schema::hasTable('ielts_practice_question_bank')) {
            Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
                if (!Schema::hasColumn('ielts_practice_question_bank', 'question_type')) {
                    $table->string('question_type', 50)
                          ->nullable()
                          ->after('group_id');
                }

                if (!Schema::hasColumn('ielts_practice_question_bank', 'question_data')) {
                    $table->json('question_data')->nullable();
                }

                if (!Schema::hasColumn('ielts_practice_question_bank', 'word_limit')) {
                    $table->string('word_limit', 50)->nullable();
                }

                if (!Schema::hasColumn('ielts_practice_question_bank', 'marks')) {
                    $table->decimal('marks', 4, 1)->default(1.0);
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('ielts_mock_question_bank')) {
            Schema::table('ielts_mock_question_bank', function (Blueprint $table) {
                foreach (['question_type', 'question_data', 'word_limit', 'marks'] as $column) {
                    if (Schema::hasColumn('ielts_mock_question_bank', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('ielts_practice_question_bank')) {
            Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
                foreach (['question_type', 'question_data', 'word_limit', 'marks'] as $column) {
                    if (Schema::hasColumn('ielts_practice_question_bank', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};