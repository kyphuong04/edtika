<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        /*
        |--------------------------------------------------------------------------
        | Update certificates.type enum
        |--------------------------------------------------------------------------
        */
        if (Schema::hasTable('certificates') && Schema::hasColumn('certificates', 'type')) {
            DB::statement("
                ALTER TABLE `certificates`
                MODIFY COLUMN `type`
                ENUM('quiz', 'course', 'bundle')
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci
                NOT NULL
                AFTER `user_grade`
            ");
        }

        /*
        |--------------------------------------------------------------------------
        | Update certificates_templates.type enum
        |--------------------------------------------------------------------------
        */
        if (
            Schema::hasTable('certificates_templates') &&
            Schema::hasColumn('certificates_templates', 'type')
        ) {
            DB::statement("
                ALTER TABLE `certificates_templates`
                MODIFY COLUMN `type`
                ENUM('quiz', 'course', 'bundle')
                CHARACTER SET utf8mb4
                COLLATE utf8mb4_unicode_ci
                NOT NULL
            ");
        }

        /*
        |--------------------------------------------------------------------------
        | Add bundle_id to certificates
        |--------------------------------------------------------------------------
        */
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'bundle_id')) {
                $table->unsignedInteger('bundle_id')
                      ->nullable()
                      ->after('webinar_id');
            }
        });

        /*
        |--------------------------------------------------------------------------
        | Add foreign key if not exists
        |--------------------------------------------------------------------------
        */
        try {
            DB::statement("
                ALTER TABLE `certificates`
                ADD CONSTRAINT `certificates_bundle_id_foreign`
                FOREIGN KEY (`bundle_id`)
                REFERENCES `bundles`(`id`)
                ON DELETE CASCADE
            ");
        } catch (\Exception $e) {
            // Bỏ qua nếu foreign key đã tồn tại
        }
    }

    public function down()
    {
        try {
            DB::statement("
                ALTER TABLE `certificates`
                DROP FOREIGN KEY `certificates_bundle_id_foreign`
            ");
        } catch (\Exception $e) {
            // Ignore
        }

        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'bundle_id')) {
                $table->dropColumn('bundle_id');
            }
        });
    }
};