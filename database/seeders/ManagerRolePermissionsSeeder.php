<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Seeder;

class ManagerRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Manager role = Quản lý (xem báo cáo, KPI, overview)
     *
     * @return void
     */
    public function run()
    {
        $managerRoleId = Role::getManagerRoleId(); // role_id = 5
        
        // Manager gets full access - ALL panel AND admin sections
        $allSections = Section::whereIn('type', ['panel', 'admin'])->get();
        $allowedSectionIds = $allSections->pluck('id')->toArray();

        $this->command->info("Creating permissions for Manager role (id: {$managerRoleId})...");
        
        // 1. Add all panel and admin permissions
        $insertData = [];
        foreach ($allSections as $section) {
            $exists = Permission::where('role_id', $managerRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $managerRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("✓ Added " . count($insertData) . " new permissions for Manager role");
        }
        
        // 2. Remove old permissions not in panel sections
        $deletedCount = Permission::where('role_id', $managerRoleId)
            ->whereNotIn('section_id', $allowedSectionIds)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->warn("✗ Removed " . $deletedCount . " non-panel permissions");
        }
        
        // 3. Summary
        $totalPermissions = Permission::where('role_id', $managerRoleId)->count();
        $this->command->info("── Manager role now has {$totalPermissions} permissions (full panel + admin access)");
    }
}
