<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchiveToPlacementAttempts extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE ielts_placement_attempts MODIFY status VARCHAR(20) NOT NULL DEFAULT 'in_progress'");
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            // Reset bài thi = chuyển status sang 'archived' thay vì xoá dữ liệu.
            // hasPlacementResult() chỉ đếm status='completed' nên học viên sẽ
            // thi lại được ngay, trong khi lần thi cũ vẫn tra cứu được.
            $table->timestamp('archived_at')->nullable()->after('completed_at');
            $table->unsignedInteger('archived_by')->nullable()->after('archived_at');
            $table->string('archive_reason', 255)->nullable()->after('archived_by');

            $table->index('status');
            $table->index('archived_at');
        });
    }

    public function down()
    {
        Schema::table('ielts_placement_attempts', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['archived_at']);
            $table->dropColumn(['archived_at', 'archived_by', 'archive_reason']);
        });
    }
}