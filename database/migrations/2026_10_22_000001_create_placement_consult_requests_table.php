<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Yêu cầu tư vấn lộ trình học gửi từ trang kết quả Placement Test
 * (/placement-test/result). Gắn với attempt để đội ngũ học thuật biết
 * học viên đang ở level nào và nghe lại phần Speaking khi chấm chữa.
 *
 * course_key / course_title đang là snapshot từ danh sách khoá học GIẢ
 * (App\Support\Placement\PlacementCourseCatalog). Khi có khoá học thật, có thể
 * thêm cột webinar_id mà không phải đổi các cột hiện có.
 */
class CreatePlacementConsultRequestsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('placement_consult_requests')) {
            return;
        }

        Schema::create('placement_consult_requests', function (Blueprint $table) {
            $table->increments('id');
            // Không đặt foreign key cứng để tránh lệch kiểu cột giữa các bảng
            // cũ (users dùng increments) — chỉ đánh index để tra cứu.
            $table->unsignedBigInteger('attempt_id')->nullable()->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('full_name', 100);
            $table->string('mobile', 20);
            $table->string('email', 150)->nullable();
            $table->string('course_key', 50);
            $table->string('course_title', 150);
            $table->string('final_level', 10)->nullable();
            $table->string('status', 20)->default('new'); // new | contacted | closed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placement_consult_requests');
    }
}
