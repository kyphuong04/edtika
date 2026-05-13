<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class AddNewStatusInReserveMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Sửa enum status nếu cột status tồn tại
        if (Schema::hasColumn('reserve_meetings', 'status')) {
            DB::statement("
                ALTER TABLE `reserve_meetings`
                MODIFY COLUMN `status`
                ENUM('pending','open','finished','canceled')
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci
                NOT NULL
            ");
        }

        // Thêm sale_id
        if (!Schema::hasColumn('reserve_meetings', 'sale_id')) {
            Schema::table('reserve_meetings', function (Blueprint $table) {
                if (Schema::hasColumn('reserve_meetings', 'meeting_id')) {
                    $table->integer('sale_id')->unsigned()->nullable()->after('meeting_id');
                } else {
                    $table->integer('sale_id')->unsigned()->nullable()->after('id');
                }
            });
        }

        // Thêm date
        if (!Schema::hasColumn('reserve_meetings', 'date')) {
            Schema::table('reserve_meetings', function (Blueprint $table) {
                if (Schema::hasColumn('reserve_meetings', 'day')) {
                    $table->integer('date')->unsigned()->after('day');
                } else {
                    $table->integer('date')->unsigned()->default(0);
                }
            });
        }

        // Thêm foreign key cho sale_id nếu chưa có
        $foreignKey = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'reserve_meetings'
              AND CONSTRAINT_NAME = 'reserve_meetings_sale_id_foreign'
              AND CONSTRAINT_TYPE = 'FOREIGN KEY'
        ");

        if (empty($foreignKey)) {
            Schema::table('reserve_meetings', function (Blueprint $table) {
                $table->foreign('sale_id')
                      ->references('id')
                      ->on('sales')
                      ->onDelete('cascade');
            });
        }
    }
}
