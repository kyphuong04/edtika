<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * "Tài liệu IELTS liên quan" ở sidebar Giải thích đáp án — giáo viên nhập
 * riêng cho từng đề: [{ "title": "...", "description": "...", "url": "https://..." }]
 */
class AddReviewResourcesToIeltsTestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ielts_tests') || Schema::hasColumn('ielts_tests', 'review_resources')) {
            return;
        }

        Schema::table('ielts_tests', function (Blueprint $table) {
            $table->text('review_resources')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasTable('ielts_tests') && Schema::hasColumn('ielts_tests', 'review_resources')) {
            Schema::table('ielts_tests', function (Blueprint $table) {
                $table->dropColumn('review_resources');
            });
        }
    }
}
