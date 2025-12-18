<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Seeder;

class CeoRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * CEO role = Giám đốc (toàn quyền truy cập)
     *
     * @return void
     */
    public function run()
    {
        $ceoRoleId = Role::getCeoRoleId(); // role_id = 6
        
        // CEO gets full access - ALL panel AND admin sections
        $allSections = Section::whereIn('type', ['panel', 'admin'])->get();
        $allowedSectionIds = $allSections->pluck('id')->toArray();

        $this->command->info("Creating permissions for CEO role (id: {$ceoRoleId})...");
        
        // 1. Add all panel and admin permissions
        $insertData = [];
        foreach ($allSections as $section) {
            $exists = Permission::where('role_id', $ceoRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $ceoRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("✓ Added " . count($insertData) . " new permissions for CEO role");
        }
        
        // 2. Remove old permissions not in panel sections
        $deletedCount = Permission::where('role_id', $ceoRoleId)
            ->whereNotIn('section_id', $allowedSectionIds)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->warn("✗ Removed " . $deletedCount . " non-panel permissions");
        }
        
        // 3. Summary
        $totalPermissions = Permission::where('role_id', $ceoRoleId)->count();
        $this->command->info("── CEO role now has {$totalPermissions} permissions (full panel + admin access)");
    }
}
