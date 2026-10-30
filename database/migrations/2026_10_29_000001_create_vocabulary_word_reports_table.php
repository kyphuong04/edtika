<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Báo lỗi từ vựng — học viên gửi từ nút "Báo lỗi" trong cửa sổ chi tiết từ
 * (trang Từ điển & Flashcard). Người nhận là giáo viên tạo bộ từ, xem và xử lý
 * tại Panel > Bảng thông báo > Thông báo từ vựng lỗi.
 *
 * source = bundle   → word_id = bundle_vocabulary_words.id,  set_id = bundle_vocabulary_sets.id
 * source = academic → word_id = academic_word_list_words.id, set_id = academic_word_lists.id
 *
 * Không đặt khoá ngoại tới từ / bộ từ: giáo viên có thể sửa hoặc xoá từ sau khi
 * nhận báo cáo nhưng báo cáo vẫn cần giữ lại. word / part_of_speech /
 * current_meaning / set_name là ảnh chụp nội dung tại lúc gửi.
 */
class CreateVocabularyWordReportsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('vocabulary_word_reports')) {
            return;
        }

        Schema::create('vocabulary_word_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');      // người gửi (học viên)
            $table->unsignedInteger('teacher_id');   // người nhận = người tạo bộ từ
            $table->string('source', 16);            // bundle | academic
            $table->unsignedInteger('set_id')->nullable();
            $table->string('set_name')->nullable();
            $table->unsignedInteger('word_id');
            $table->unsignedInteger('flashcard_id')->nullable();
            $table->string('word');
            $table->string('part_of_speech', 50)->nullable();
            $table->text('current_meaning')->nullable();
            $table->text('message');
            $table->string('status', 16)->default('new'); // new | resolved
            $table->unsignedInteger('resolved_by')->nullable();
            $table->unsignedInteger('resolved_at')->nullable();
            $table->unsignedInteger('created_at')->nullable();
            $table->unsignedInteger('updated_at')->nullable();

            $table->index(['teacher_id', 'status']);
            $table->index(['source', 'set_id']);
            $table->index(['user_id', 'source', 'word_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vocabulary_word_reports');
    }
}
