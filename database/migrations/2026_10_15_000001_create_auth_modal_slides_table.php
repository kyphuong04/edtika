<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Slider bên phải modal Đăng nhập / Đăng ký — 1 dòng / 1 vị trí slide (1..3).
 * Vị trí nào chưa có dòng => modal dùng slide lấy từ cài đặt theme (như trước đây).
 */
class CreateAuthModalSlidesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('auth_modal_slides')) {
            return;
        }

        Schema::create('auth_modal_slides', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('slot')->unique();
            $table->string('image_path')->nullable();
            $table->string('title', 160)->nullable();
            $table->string('subtitle', 255)->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('auth_modal_slides');
    }
}
