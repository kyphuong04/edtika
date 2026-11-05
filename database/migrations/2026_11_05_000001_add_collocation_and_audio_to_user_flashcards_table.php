<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Từ học viên tự lưu từ Dictionary Hub giờ lưu thêm collocation và link audio
 * (lấy từ bộ từ nội bộ hoặc API từ điển) để hiện lại trong Word List / flashcard.
 */
class AddCollocationAndAudioToUserFlashcardsTable extends Migration
{
    public function up()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            if (!Schema::hasColumn('user_flashcards', 'collocation')) {
                $table->text('collocation')->nullable()->after('translation');
            }
            if (!Schema::hasColumn('user_flashcards', 'audio_url')) {
                $table->text('audio_url')->nullable()->after('pronunciation');
            }
        });
    }

    public function down()
    {
        Schema::table('user_flashcards', function (Blueprint $table) {
            foreach (['collocation', 'audio_url'] as $column) {
                if (Schema::hasColumn('user_flashcards', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
