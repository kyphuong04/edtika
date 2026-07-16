<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ielts_placement_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            // in_progress: đang làm | completed: đã có kết quả cuối cùng | abandoned: bỏ dở (hết hạn phiên)
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');

            // Đang ở đề thứ mấy trong tối đa 3 đề (1, 2, hoặc 3)
            $table->unsignedTinyInteger('current_step')->default(1);

            // Danh sách id các đề (placement_tests.id) đã/đang làm theo đúng thứ tự, vd [12, 5, 9]
            $table->json('test_ids_taken')->nullable();

            // Điểm số (số câu đúng) tương ứng theo từng đề trong test_ids_taken, vd [7, 6]
            $table->json('scores')->nullable();

            // Level đang tạm tính trong lúc làm (chưa chắc là kết quả cuối)
            $table->string('current_level', 5)->nullable();

            // Level CHÍNH THỨC — chỉ set khi status = completed. Đây là field
            // User::latestPlacementResult() / hasPlacementResult() dựa vào.
            $table->string('final_level', 5)->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Ghi âm câu Speaking cuối bài — không chấm điểm, chỉ để giáo viên nghe tư vấn.
            $table->string('speaking_recording_path')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            // Không dùng ->foreign()->references()->on('users') vì users.id trong
            // project này không cùng kiểu dữ liệu chuẩn (bigint unsigned) — tránh lỗi
            // "incompatible" khi tạo constraint. Vẫn đảm bảo toàn vẹn ở tầng ứng dụng
            // (Model, Controller) thay vì DB-level constraint.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ielts_placement_attempts');
    }
};