<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * GĐ5 - Thêm kênh thanh toán Demo. Kênh để "active" nhưng driver chỉ chạy khi PAYMENT_DEMO_ENABLED=true.
 */
return new class extends Migration {
    public function up()
    {
        $exists = DB::table('payment_channels')->where('class_name', 'Demo')->exists();

        if (!$exists) {
            DB::table('payment_channels')->insert([
                'title' => 'Demo (VietQR / Ví / Thẻ)',
                'class_name' => 'Demo',
                'status' => 'active',
                'image' => null,
                'credentials' => null,
                'created_at' => time(),
            ]);
        }
    }

    public function down()
    {
        DB::table('payment_channels')->where('class_name', 'Demo')->delete();
    }
};
