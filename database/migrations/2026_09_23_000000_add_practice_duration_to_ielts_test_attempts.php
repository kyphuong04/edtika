<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPracticeDurationToIeltsTestAttempts extends Migration
{
    public function up()
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            // NULL = học viên chưa chọn; 0 = chọn "không giới hạn".
            $table->integer('practice_duration_seconds')->nullable()->after('remaining_time_seconds');
        });
    }

    public function down()
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            $table->dropColumn('practice_duration_seconds');
        });
    }
}