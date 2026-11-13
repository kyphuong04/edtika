<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * GĐ1 - Khóa học (bundle) theo design mới.
 * Tất cả cột đều nullable hoặc có default để không ảnh hưởng bundle cũ
 * và dễ đổi khi yêu cầu của khách thay đổi.
 */
return new class extends Migration {
    public function up()
    {
        Schema::table('bundles', function (Blueprint $table) {
            if (!Schema::hasColumn('bundles', 'original_price')) {
                // Giá gốc của toàn bộ khóa, teacher tự nhập; price là giá bán
                $table->integer('original_price')->unsigned()->nullable()->after('price');
            }
            if (!Schema::hasColumn('bundles', 'bundle_type')) {
                // band_path | skill_intensive | full_skills | combo (xem Bundle::TYPES)
                $table->string('bundle_type', 32)->nullable()->after('category_id');
            }
            if (!Schema::hasColumn('bundles', 'exam_type')) {
                // ielts | toeic | aptis (xem Bundle::EXAM_TYPES)
                $table->string('exam_type', 32)->nullable()->default('ielts')->after('bundle_type');
            }
            if (!Schema::hasColumn('bundles', 'band_from')) {
                $table->decimal('band_from', 3, 1)->nullable()->after('exam_type');
            }
            if (!Schema::hasColumn('bundles', 'band_to')) {
                $table->decimal('band_to', 3, 1)->nullable()->after('band_from');
            }
            if (!Schema::hasColumn('bundles', 'roadmap_months')) {
                $table->unsignedTinyInteger('roadmap_months')->nullable()->after('band_to');
            }
            if (!Schema::hasColumn('bundles', 'is_combo')) {
                $table->boolean('is_combo')->default(false)->after('roadmap_months');
            }
            if (!Schema::hasColumn('bundles', 'sequential_modules')) {
                // true: phải xong module trước mới mở module sau (khóa kỹ năng)
                $table->boolean('sequential_modules')->default(false)->after('is_combo');
            }
            if (!Schema::hasColumn('bundles', 'sequential_lessons')) {
                // true: trong một module phải xong lesson trước mới mở lesson sau
                $table->boolean('sequential_lessons')->default(true)->after('sequential_modules');
            }
            if (!Schema::hasColumn('bundles', 'demo_video_duration')) {
                // Thời lượng video giới thiệu (giây)
                $table->unsignedInteger('demo_video_duration')->nullable()->after('video_demo_source');
            }
        });
    }

    public function down()
    {
        Schema::table('bundles', function (Blueprint $table) {
            foreach (['original_price', 'bundle_type', 'exam_type', 'band_from', 'band_to', 'roadmap_months', 'is_combo', 'sequential_modules', 'sequential_lessons', 'demo_video_duration'] as $column) {
                if (Schema::hasColumn('bundles', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
