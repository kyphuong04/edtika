<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Nút "Làm lại" ở trang kết quả: bài cũ chuyển sang status 'archived' (giống
 * cách PlacementResultManagerController reset Placement Test) thay vì xoá.
 * Mọi nơi đọc kết quả đều lọc status = 'completed' nên bài archived tự biến
 * mất khỏi lịch sử, điểm cao nhất, hàng chờ chấm — nhưng dữ liệu vẫn còn.
 */
class AddArchiveColumnsToIeltsTestAttempts extends Migration
{
    public function up()
    {
        $this->allowArchivedStatus();

        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            if (!Schema::hasColumn('ielts_test_attempts', 'archived_at')) {
                $table->unsignedInteger('archived_at')->nullable();
            }
            if (!Schema::hasColumn('ielts_test_attempts', 'archived_by')) {
                $table->unsignedInteger('archived_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('ielts_test_attempts', function (Blueprint $table) {
            if (Schema::hasColumn('ielts_test_attempts', 'archived_at')) {
                $table->dropColumn('archived_at');
            }
            if (Schema::hasColumn('ielts_test_attempts', 'archived_by')) {
                $table->dropColumn('archived_by');
            }
        });
    }

    /**
     * Nếu cột status là ENUM thì thêm giá trị 'archived', giữ nguyên
     * nullable/default hiện có. Cột VARCHAR thì không cần làm gì.
     */
    private function allowArchivedStatus(): void
    {
        $column = DB::selectOne(
            "SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT
               FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'ielts_test_attempts'
                AND COLUMN_NAME = 'status'"
        );

        if (!$column) {
            return;
        }

        $type = (string) $column->COLUMN_TYPE;

        if (stripos($type, 'enum(') !== 0 || stripos($type, "'archived'") !== false) {
            return;
        }

        $newType = substr($type, 0, -1) . ",'archived')";
        $nullable = $column->IS_NULLABLE === 'YES' ? 'NULL' : 'NOT NULL';
        $default = '';

        if ($column->COLUMN_DEFAULT !== null && strtoupper((string) $column->COLUMN_DEFAULT) !== 'NULL') {
            $default = ' DEFAULT ' . DB::getPdo()->quote(trim((string) $column->COLUMN_DEFAULT, "'"));
        }

        DB::statement("ALTER TABLE ielts_test_attempts MODIFY status {$newType} {$nullable}{$default}");
    }
}