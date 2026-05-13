<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRegistrationPackageIdToSalesTable extends Migration
{
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | SALES TABLE
        |--------------------------------------------------------------------------
        | Tạo cột registration_package_id trước,
        | sau đó mới MODIFY cột type.
        */
        Schema::table('sales', function (Blueprint $table) {
            $table->integer('registration_package_id')
                ->unsigned()
                ->nullable()
                ->after('promotion_id');
        });

        DB::statement("
            ALTER TABLE `sales`
            MODIFY COLUMN `type`
            ENUM(
                'webinar',
                'meeting',
                'subscribe',
                'promotion',
                'registration_package'
            )
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci
            NOT NULL
            AFTER `registration_package_id`
        ");

        /*
        |--------------------------------------------------------------------------
        | ORDER_ITEMS TABLE
        |--------------------------------------------------------------------------
        */
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('registration_package_id')
                ->unsigned()
                ->nullable()
                ->after('promotion_id');
        });

        /*
        |--------------------------------------------------------------------------
        | ACCOUNTING TABLE
        |--------------------------------------------------------------------------
        | Tạo cột trước, rồi mới MODIFY type_account.
        */
        Schema::table('accounting', function (Blueprint $table) {
            $table->integer('registration_package_id')
                ->unsigned()
                ->nullable()
                ->after('promotion_id');
        });

        DB::statement("
            ALTER TABLE `accounting`
            MODIFY COLUMN `type_account`
            ENUM(
                'income',
                'asset',
                'subscribe',
                'promotion',
                'registration_package'
            )
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci
            NULL DEFAULT NULL
            AFTER `type`
        ");
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('registration_package_id');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('registration_package_id');
        });

        Schema::table('accounting', function (Blueprint $table) {
            $table->dropColumn('registration_package_id');
        });
    }
}