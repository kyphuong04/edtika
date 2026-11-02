<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Link chia sẻ công khai của 1 bài làm IELTS: /ielts-share/{token}.
 * Ai có link đều xem được trang kết quả + bài chữa Listening / Reading
 * (không cần đăng nhập). Học viên tắt link -> is_active = 0, link trả 404.
 *
 * tier / banner_id: ảnh 1:1 đã chọn cho link (og:image). Giữ cố định để
 * Facebook / LinkedIn không thấy ảnh đổi mỗi lần quét lại.
 */
class CreateIeltsAttemptSharesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ielts_attempt_shares')) {
            return;
        }

        Schema::create('ielts_attempt_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('attempt_id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('test_id');
            $table->string('token', 40);
            $table->string('tier', 10)->nullable();
            $table->unsignedInteger('banner_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->unique('attempt_id');
            $table->unique('token');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ielts_attempt_shares');
    }
}
