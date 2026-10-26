<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Thêm phần XỬ LÝ cho yêu cầu tư vấn (trang admin "Yêu cầu tư vấn"):
 *   - admin_note : ghi chú nội bộ của người tư vấn
 *   - handled_by : user admin/manager/ceo cập nhật trạng thái gần nhất
 *   - handled_at : thời điểm cập nhật gần nhất
 * Kèm index cho status vì sidebar admin đếm số yêu cầu "mới" ở mọi trang.
 *
 * Viết dạng bổ sung (có kiểm tra cột) để chạy được dù bảng gốc đã migrate hay chưa.
 */
class AddHandlingColumnsToPlacementConsultRequestsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('placement_consult_requests')) {
            return;
        }

        Schema::table('placement_consult_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('placement_consult_requests', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('placement_consult_requests', 'handled_by')) {
                $table->unsignedInteger('handled_by')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('placement_consult_requests', 'handled_at')) {
                $table->timestamp('handled_at')->nullable()->after('handled_by');
            }
            $table->index('status', 'placement_consult_requests_status_index');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('placement_consult_requests')) {
            return;
        }

        Schema::table('placement_consult_requests', function (Blueprint $table) {
            $table->dropIndex('placement_consult_requests_status_index');
            $table->dropColumn(['admin_note', 'handled_by', 'handled_at']);
        });
    }
}
