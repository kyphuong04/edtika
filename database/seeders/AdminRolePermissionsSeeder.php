<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Section;
use App\Models\Role;

class AdminRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Grant all panel permissions to admin role (organization replacement)
     *
     * @return void
     */
    public function run()
    {
        // Get admin role (id = 4)
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found!');
            return;
        }

        // Get all panel sections
        $panelSections = Section::where('type', 'panel')->get();

        $this->command->info("Creating permissions for Admin role (id: {$adminRole->id})...");
        
        $count = 0;
        foreach ($panelSections as $section) {
            Permission::updateOrCreate(
                [
                    'role_id' => $adminRole->id,
                    'section_id' => $section->id,
                ],
                [
                    'allow' => true
                ]
            );
            $count++;
        }

        $this->command->info("Created {$count} permissions for Admin role");
        $this->command->info("Admin role now has full panel access (organization functionality)");
    }
}
