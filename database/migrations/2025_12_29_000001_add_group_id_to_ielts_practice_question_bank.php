<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddGroupIdToIeltsPracticeQuestionBank extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | Chỉ chạy migration nếu bảng tồn tại
        |--------------------------------------------------------------------------
        */
        if (!Schema::hasTable('ielts_practice_question_bank')) {
            return;
        }

        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            /*
            |--------------------------------------------------------------------------
            | Thêm cột group_id nếu chưa tồn tại
            |--------------------------------------------------------------------------
            */
            if (!Schema::hasColumn('ielts_practice_question_bank', 'group_id')) {
                $table->unsignedBigInteger('group_id')
                      ->nullable()
                      ->after('id');

                $table->index('group_id');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Thêm foreign key nếu bảng đích tồn tại
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('ielts_question_groups')) {
            try {
                DB::statement("
                    ALTER TABLE `ielts_practice_question_bank`
                    ADD CONSTRAINT `ielts_practice_question_bank_group_id_foreign`
                    FOREIGN KEY (`group_id`)
                    REFERENCES `ielts_question_groups`(`id`)
                    ON DELETE CASCADE
                ");
            } catch (\Exception $e) {
                // Bỏ qua nếu foreign key đã tồn tại
            }
        }
    }

    public function down()
    {
        if (!Schema::hasTable('ielts_practice_question_bank')) {
            return;
        }

        try {
            DB::statement("
                ALTER TABLE `ielts_practice_question_bank`
                DROP FOREIGN KEY `ielts_practice_question_bank_group_id_foreign`
            ");
        } catch (\Exception $e) {
            // Ignore
        }

        Schema::table('ielts_practice_question_bank', function (Blueprint $table) {
            if (Schema::hasColumn('ielts_practice_question_bank', 'group_id')) {
                try {
                    $table->dropIndex(['group_id']);
                } catch (\Exception $e) {
                    // Ignore
                }

                $table->dropColumn('group_id');
            }
        });
    }
}