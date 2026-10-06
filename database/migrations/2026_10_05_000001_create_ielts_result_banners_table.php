<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Banner trang kết quả IELTS — 1 dòng / 1 mức kết quả (low | mid | high).
 * Chưa có dòng nào cho 1 mức => trang kết quả dùng ảnh mặc định trong
 * public/assets/images/ielts/result-banners/{tier}.webp.
 */
class CreateIeltsResultBannersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ielts_result_banners')) {
            return;
        }

        Schema::create('ielts_result_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tier', 16)->unique();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_result_banners');
    }
}