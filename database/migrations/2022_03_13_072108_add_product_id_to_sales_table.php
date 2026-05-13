<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddProductIdToSalesTable extends Migration
{
    public function up()
    {
        // SALES
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'product_order_id')) {
                $table->unsignedInteger('product_order_id')
                      ->nullable()
                      ->after('promotion_id');
            }
        });

        DB::statement("
            ALTER TABLE `sales`
            MODIFY COLUMN `type`
            ENUM(
                'webinar',
                'meeting',
                'subscribe',
                'promotion',
                'registration_package',
                'product'
            )
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci
            NOT NULL
        ");

        // ORDER ITEMS
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_order_id')) {
                $table->unsignedInteger('product_order_id')
                      ->nullable()
                      ->after('registration_package_id');
            }
        });

        // ACCOUNTING
        Schema::table('accounting', function (Blueprint $table) {
            if (!Schema::hasColumn('accounting', 'product_id')) {
                $table->unsignedInteger('product_id')
                      ->nullable()
                      ->after('registration_package_id');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'product_order_id')) {
                $table->dropColumn('product_order_id');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'product_order_id')) {
                $table->dropColumn('product_order_id');
            }
        });

        Schema::table('accounting', function (Blueprint $table) {
            if (Schema::hasColumn('accounting', 'product_id')) {
                $table->dropColumn('product_id');
            }
        });
    }
}