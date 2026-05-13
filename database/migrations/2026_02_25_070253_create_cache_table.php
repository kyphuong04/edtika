<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cache')) {
            DB::statement("
                CREATE TABLE `cache` (
                    `key` VARCHAR(255) NOT NULL,
                    `value` MEDIUMTEXT NOT NULL,
                    `expiration` INT NOT NULL,
                    PRIMARY KEY (`key`)
                ) ENGINE=InnoDB
                DEFAULT CHARSET=utf8mb4
                COLLATE=utf8mb4_general_ci
            ");
        }

        if (!Schema::hasTable('cache_locks')) {
            DB::statement("
                CREATE TABLE `cache_locks` (
                    `key` VARCHAR(255) NOT NULL,
                    `owner` VARCHAR(255) NOT NULL,
                    `expiration` INT NOT NULL,
                    PRIMARY KEY (`key`)
                ) ENGINE=InnoDB
                DEFAULT CHARSET=utf8mb4
                COLLATE=utf8mb4_general_ci
            ");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};