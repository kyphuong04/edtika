<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPaymentChannelsTable extends Migration
{
    public function up()
    {
        Schema::table('payment_channels', function (Blueprint $table) {
            // Nếu cột settings tồn tại thì thêm currencies sau settings
            if (Schema::hasColumn('payment_channels', 'settings')) {
                if (!Schema::hasColumn('payment_channels', 'currencies')) {
                    $table->text('currencies')
                          ->nullable()
                          ->after('settings');
                }
            } else {
                // Nếu settings không tồn tại thì thêm currencies ở cuối bảng
                if (!Schema::hasColumn('payment_channels', 'currencies')) {
                    $table->text('currencies')
                          ->nullable();
                }
            }
        });
    }

    public function down()
    {
        Schema::table('payment_channels', function (Blueprint $table) {
            if (Schema::hasColumn('payment_channels', 'currencies')) {
                $table->dropColumn('currencies');
            }
        });
    }
}