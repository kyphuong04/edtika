<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Chạy tất cả role permissions seeders
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  Đồng bộ permissions cho tất cả các roles');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->newLine();
        
        // 1. User Role (Lead - chưa mua khóa học)
        $this->command->info('🔹 [1/6] User Role (Lead)...');
        $this->call(UserRolePermissionsSeeder::class);
        $this->command->newLine();
        
        // 2. Student Role (đã mua khóa học)
        $this->command->info('🔹 [2/6] Student Role...');
        $this->call(StudentRolePermissionsSeeder::class);
        $this->command->newLine();
        
        // 3. Teacher Role (Instructor)
        $this->command->info('🔹 [3/6] Teacher Role (Instructor)...');
        $this->call(TeacherRolePermissionsSeeder::class);
        $this->command->newLine();
        
        // 4. Admin Role (toàn quyền)
        $this->command->info('🔹 [4/6] Admin Role...');
        $this->call(AdminRolePermissionsSeeder::class);
        $this->command->newLine();
        
        // 5. Manager Role (toàn quyền)
        $this->command->info('🔹 [5/6] Manager Role...');
        $this->call(ManagerRolePermissionsSeeder::class);
        $this->command->newLine();
        
        // 6. CEO Role (toàn quyền)
        $this->command->info('🔹 [6/6] CEO Role...');
        $this->call(CeoRolePermissionsSeeder::class);
        $this->command->newLine();
        
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('  ✓ Hoàn tất đồng bộ permissions cho 6 roles!');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}
