<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Transcript Listening có cấu trúc cho từng Part:
 *   { speakers: [{id, name, narrator}], lines: [{speaker, text, start}] }
 * - start: giây tính từ đầu file audio của Part (null = chưa đánh dấu).
 * - text: chữ thường, đoạn đáp án đánh dấu bằng [[q5]]...[[/q5]] (5 = số câu).
 * Cột transcript (HTML) cũ giữ nguyên để tương thích.
 */
class AddTranscriptDataToIeltsTestPartsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('ielts_test_parts') || Schema::hasColumn('ielts_test_parts', 'transcript_data')) {
            return;
        }

        Schema::table('ielts_test_parts', function (Blueprint $table) {
            $table->longText('transcript_data')->nullable()->after('transcript');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('ielts_test_parts', 'transcript_data')) {
            Schema::table('ielts_test_parts', function (Blueprint $table) {
                $table->dropColumn('transcript_data');
            });
        }
    }
}
