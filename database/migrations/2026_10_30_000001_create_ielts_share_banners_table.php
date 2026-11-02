<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ảnh 1:1 dùng khi học viên chia sẻ bài làm IELTS lên mạng xã hội
 * (og:image của link chia sẻ + ảnh xem trước trong cửa sổ "Chia sẻ bài làm").
 *
 * Mỗi mức (low / mid / high — cùng ngưỡng với banner trang kết quả) có thể
 * có nhiều ảnh; hệ thống chọn ngẫu nhiên 1 ảnh đang bật. Manager / CEO
 * quản lý tại Admin > IELTS Tests > Banner chia sẻ bài làm.
 */
class CreateIeltsShareBannersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ielts_share_banners')) {
            return;
        }

        Schema::create('ielts_share_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tier', 10);
            $table->string('image_path', 255);
            $table->string('title', 120)->nullable();          // ghi chú nội bộ / alt ảnh
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->index(['tier', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_share_banners');
    }
}
