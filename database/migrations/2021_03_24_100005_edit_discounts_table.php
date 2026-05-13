<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Facades\DB;

class EditDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Xóa cột name trong discounts nếu tồn tại
        if (Schema::hasColumn('discounts', 'name')) {
            DB::statement("ALTER TABLE `discounts` DROP COLUMN `name`");
        }

        // Xóa cột count trong discount_users nếu tồn tại
        if (Schema::hasTable('discount_users') && Schema::hasColumn('discount_users', 'count')) {
            DB::statement("ALTER TABLE `discount_users` DROP COLUMN `count`");
        }

        // Xóa cột started_at nếu tồn tại
        if (Schema::hasColumn('discounts', 'started_at')) {
            DB::statement("ALTER TABLE `discounts` DROP COLUMN `started_at`");
        }

        // Sửa cột created_at nếu tồn tại
        if (Schema::hasColumn('discounts', 'created_at')) {
            DB::statement("
                ALTER TABLE `discounts`
                MODIFY COLUMN `created_at` INT UNSIGNED NOT NULL
            ");
        }

        // Thêm cột title nếu chưa tồn tại
        if (!Schema::hasColumn('discounts', 'title')) {
            Schema::table('discounts', function (Blueprint $table) {
                $table->string('title')->after('creator_id');
            });
        }

        // Thêm cột code nếu chưa tồn tại
        if (!Schema::hasColumn('discounts', 'code')) {
            Schema::table('discounts', function (Blueprint $table) {
                $table->string('code', 64)->unique()->after('title');
            });
        }

        // Thêm cột type nếu chưa tồn tại
        if (!Schema::hasColumn('discounts', 'type')) {
            Schema::table('discounts', function (Blueprint $table) {
                $table->enum('type', ['all_users', 'special_users'])->after('count');
            });
        }
    }
}
