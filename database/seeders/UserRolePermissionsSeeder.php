<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Seeder;

class UserRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * User role = Lead (người mới đăng ký, chưa mua khóa học)
     *
     * @return void
     */
    public function run()
    {
        $userRoleId = Role::getUserRoleId(); // role_id = 1
        
        // Permissions cho User (Lead) - chỉ có quyền xem và đăng ký cơ bản
        $allowedSections = [
            // Main Menu
            'panel_general_dashboard',
            
            // EDUCATION Section - chỉ xem để browse
            'panel_webinars',
            'panel_upcoming_courses',
            
            // FINANCIAL & MARKETING Section
            'panel_financial',
            'panel_financial_charge_account',
            
            // Marketing
            'panel_marketing',
            'panel_marketing_affiliates',
            'panel_marketing_registration_bonus',
            
            // Reward Points
            'panel_rewards',
            
            // COMMUNICATIONS Section
            // Support
            'panel_support',
            'panel_support_create',
            'panel_support_lists',
            'panel_support_tickets',
            
            // Notifications
            'panel_notifications',
            
            // USER Section
            'panel_settings',
            'panel_settings_general',
            'panel_settings_images',
            'panel_settings_password',
            'panel_settings_identity_and_financial',
            
            'panel_others_profile_url',
            'panel_others_profile_setting',
            'panel_others_logout',
        ];
        
        $sections = Section::whereIn('name', $allowedSections)->get();
        $allowedSectionIds = $sections->pluck('id')->toArray();
        
        // 1. Add new permissions (insert missing ones)
        $insertData = [];
        foreach ($sections as $section) {
            $exists = Permission::where('role_id', $userRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $userRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("✓ Added " . count($insertData) . " new permissions for User (Lead) role");
        }
        
        // 2. Remove old permissions (delete ones not in the allowed list)
        $deletedCount = Permission::where('role_id', $userRoleId)
            ->whereNotIn('section_id', $allowedSectionIds)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->warn("✗ Removed " . $deletedCount . " permissions no longer in the allowed list");
        }
        
        // 3. Summary
        $totalPermissions = Permission::where('role_id', $userRoleId)->count();
        $this->command->info("── User (Lead) role now has {$totalPermissions} permissions total");
    }
}
