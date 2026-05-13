<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMeetingIdToMeetingTimeIdInAccountingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Kiểm tra cột meeting_id có tồn tại hay không
        if (!Schema::hasColumn('accounting', 'meeting_id')) {
            return;
        }

        // Lấy danh sách foreign keys hiện có
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'accounting'
            AND COLUMN_NAME = 'meeting_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Nếu tồn tại foreign key thì mới drop
        if (!empty($foreignKeys)) {
            foreach ($foreignKeys as $fk) {
                DB::statement("ALTER TABLE `accounting` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }
        }

        // Đổi tên cột
        DB::statement("
            ALTER TABLE `accounting`
            CHANGE COLUMN `meeting_id` `meeting_time_id` INT UNSIGNED NULL
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounting', function (Blueprint $table) {
            //
        });
    }
}
