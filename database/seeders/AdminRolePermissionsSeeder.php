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
     * Admin role = Quản trị viên (quản lý nội dung, học viên, CRM)
     *
     * @return void
     */
    public function run()
    {
        $adminRoleId = Role::where('name', 'admin')->value('id'); // role_id = 4
        
        if (!$adminRoleId) {
            $this->command->error('Admin role not found!');
            return;
        }

        // Admin permissions - comprehensive but not full access
        $allowedSections = [
            // Main Menu
            'panel_general_dashboard',
            'panel_general_events_calendar',
            
            // MEMBERS Section
            'panel_organization_students',
            'panel_organization_students_lists',
            'panel_organization_students_create',
            'panel_organization_students_edit',
            'panel_organization_students_delete',
            'panel_organization_students_export_excel',
            
            'panel_organization_instructors',
            'panel_organization_instructors_lists',
            'panel_organization_instructors_create',
            'panel_organization_instructors_edit',
            'panel_organization_instructors_delete',
            
            // EDUCATION Section
            'panel_webinars',
            'panel_webinars_create',
            'panel_webinars_edit',
            'panel_webinars_delete',
            'panel_webinars_lists',
            'panel_webinars_my_classes',
            'panel_webinars_organization_classes',
            'panel_webinars_invitations',
            'panel_webinars_my_purchases',
            'panel_webinars_comments',
            'panel_webinars_favorites',
            'panel_webinars_statistics',
            'panel_webinars_duplicate',
            'panel_webinars_export_students_list',
            'panel_webinars_attendance',
            'panel_webinars_learning_page',
            'panel_webinars_personal_course_notes',
            
            'panel_upcoming_courses',
            'panel_upcoming_courses_create',
            'panel_upcoming_courses_edit',
            'panel_upcoming_courses_delete',
            'panel_upcoming_courses_lists',
            'panel_upcoming_courses_followings',
            
            'panel_events',
            'panel_events_create',
            'panel_events_edit',
            'panel_events_delete',
            'panel_events_lists',
            'panel_events_organization_events',
            'panel_events_my_purchases',
            'panel_events_comments',
            
            'panel_bundles',
            'panel_bundles_create',
            'panel_bundles_edit',
            'panel_bundles_delete',
            'panel_bundles_lists',
            
            'panel_meetings',
            'panel_meetings_requests',
            'panel_meetings_settings',
            'panel_meetings_my_reservation',
            'panel_meetings_purchased_packages',
            
            'panel_students_tracking',
            
            // EVALUATION Section
            'panel_assignments',
            'panel_assignments_lists',
            'panel_assignments_my_courses_assignments',
            
            'panel_quizzes',
            'panel_quizzes_create',
            'panel_quizzes_edit',
            'panel_quizzes_delete',
            'panel_quizzes_lists',
            'panel_quizzes_results',
            'panel_quizzes_my_results',
            'panel_quizzes_not_participated',
            
            // IELTS Tests
            'panel_ielts_tests',
            'panel_ielts_tests_create',
            'panel_ielts_tests_edit',
            'panel_ielts_tests_delete',
            'panel_ielts_tests_lists',
            'panel_ielts_tests_approve',
            
            'panel_certificates',
            'panel_certificates_lists',
            'panel_certificates_students_lists',
            'panel_certificates_achievements',
            'panel_certificates_validate',
            
            // FINANCIAL & MARKETING Section
            'panel_store',
            'panel_store_products',
            'panel_store_products_create',
            'panel_store_products_edit',
            'panel_store_products_delete',
            'panel_store_products_lists',
            'panel_store_sales',
            'panel_store_my_purchases',
            'panel_store_comments',
            
            'panel_financial',
            'panel_financial_sales',
            'panel_financial_summary',
            'panel_financial_payout',
            'panel_financial_charge_account',
            'panel_financial_subscribes',
            'panel_financial_installments',
            
            'panel_marketing',
            'panel_marketing_special_offers',
            'panel_marketing_promotions',
            'panel_marketing_discounts',
            'panel_marketing_affiliates',
            'panel_marketing_registration_bonus',
            
            'panel_rewards',
            
            // COMMUNICATIONS Section
            'panel_support',
            'panel_support_create',
            'panel_support_lists',
            'panel_support_tickets',
            
            'panel_forums',
            'panel_forums_new_topic',
            'panel_forums_my_topics',
            'panel_forums_my_posts',
            'panel_forums_bookmarks',
            
            'panel_noticeboard',
            'panel_noticeboard_create',
            'panel_noticeboard_edit',
            'panel_noticeboard_delete',
            'panel_noticeboard_lists',
            
            'panel_course_noticeboard',
            'panel_course_noticeboard_create',
            'panel_course_noticeboard_edit',
            'panel_course_noticeboard_delete',
            'panel_course_noticeboard_lists',
            
            'panel_notifications',
            
            'panel_blog',
            'panel_blog_create',
            'panel_blog_edit',
            'panel_blog_delete',
            'panel_blog_lists',
            'panel_blog_comments',
            
            'panel_ai_contents',
            
            // USER Section
            'panel_settings',
            'panel_settings_general',
            'panel_settings_images',
            'panel_settings_password',
            'panel_settings_identity_and_financial',
            'panel_settings_account_delete',
            
            'panel_others_profile_url',
            'panel_others_profile_setting',
            'panel_others_logout',
        ];
        
        $sections = Section::whereIn('name', $allowedSections)->get();
        $allowedSectionIds = $sections->pluck('id')->toArray();
        
        // 1. Add new permissions (insert missing ones)
        $insertData = [];
        foreach ($sections as $section) {
            $exists = Permission::where('role_id', $adminRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $adminRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("✓ Added " . count($insertData) . " new permissions for Admin role");
        }
        
        // 2. Remove old permissions (delete ones not in the allowed list)
        $deletedCount = Permission::where('role_id', $adminRoleId)
            ->whereNotIn('section_id', $allowedSectionIds)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->warn("✗ Removed " . $deletedCount . " permissions no longer in the allowed list");
        }
        
        // 3. Summary
        $totalPermissions = Permission::where('role_id', $adminRoleId)->count();
        $this->command->info("── Admin role now has {$totalPermissions} permissions total");
    }
}

