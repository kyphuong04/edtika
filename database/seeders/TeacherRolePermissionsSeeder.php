<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Seeder;

class TeacherRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Teacher role = Instructor (tạo khóa học, chấm bài, quản lý học sinh)
     *
     * @return void
     */
    public function run()
    {
        $teacherRoleId = Role::getTeacherRoleId(); // role_id = 3
        
        // Permissions cho Teacher/Instructor
        $allowedSections = [
            // Main Menu
            'panel_general_dashboard',
            'panel_general_events_calendar',
            
            // MEMBERS Section
            // Students Management
            'panel_organization_students',
            'panel_organization_students_lists',
            'panel_organization_students_create',
            'panel_organization_students_edit',
            'panel_organization_students_delete',
            'panel_organization_students_export_excel',
            
            // EDUCATION Section
            // Courses Management
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
            'panel_webinars_learning_page',
            
            // Upcoming Courses
            'panel_upcoming_courses',
            'panel_upcoming_courses_create',
            'panel_upcoming_courses_edit',
            'panel_upcoming_courses_delete',
            'panel_upcoming_courses_lists',
            'panel_upcoming_courses_followings',
            
            // Events
            'panel_events',
            'panel_events_create',
            'panel_events_edit',
            'panel_events_delete',
            'panel_events_lists',
            'panel_events_organization_events',
            'panel_events_my_purchases',
            'panel_events_comments',
            
            // Bundles
            'panel_bundles',
            'panel_bundles_create',
            'panel_bundles_edit',
            'panel_bundles_delete',
            'panel_bundles_lists',
            
            // Meetings
            'panel_meetings',
            'panel_meetings_requests',
            'panel_meetings_settings',
            'panel_meetings_my_reservation',
            'panel_meetings_purchased_packages',
            
            // Student Tracking
            'panel_students_tracking',
            
            // EVALUATION Section
            // Assignments
            'panel_assignments',
            'panel_assignments_lists',
            'panel_assignments_my_courses_assignments',
            
            // Quizzes
            'panel_quizzes',
            'panel_quizzes_create',
            'panel_quizzes_edit',
            'panel_quizzes_delete',
            'panel_quizzes_lists',
            'panel_quizzes_results',
            'panel_quizzes_my_results',
            'panel_quizzes_not_participated',
            
            // Certificates
            'panel_certificates',
            'panel_certificates_lists',
            'panel_certificates_students_lists',
            'panel_certificates_achievements',
            'panel_certificates_validate',
            
            // FINANCIAL & MARKETING Section
            // Store
            'panel_store',
            'panel_store_products',
            'panel_store_products_create',
            'panel_store_products_edit',
            'panel_store_products_delete',
            'panel_store_products_lists',
            'panel_store_sales',
            'panel_store_my_purchases',
            'panel_store_comments',
            
            // Financial
            'panel_financial',
            'panel_financial_sales',
            'panel_financial_summary',
            'panel_financial_payout',
            'panel_financial_charge_account',
            'panel_financial_subscribes',
            'panel_financial_installments',
            
            // Marketing
            'panel_marketing',
            'panel_marketing_special_offers',
            'panel_marketing_promotions',
            'panel_marketing_discounts',
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
            
            // Forums
            'panel_forums',
            'panel_forums_new_topic',
            'panel_forums_my_topics',
            'panel_forums_my_posts',
            'panel_forums_bookmarks',
            
            // Noticeboard
            'panel_noticeboard',
            'panel_noticeboard_history',
            'panel_noticeboard_create',
            'panel_noticeboard_edit',
            'panel_noticeboard_delete',
            'panel_noticeboard_lists',
            
            // Course Noticeboard
            'panel_noticeboard_course_notices',
            'panel_noticeboard_course_notices_create',
            'panel_noticeboard_delete',
            
            // Notifications
            'panel_notifications',
            
            // Blog
            'panel_blog',
            'panel_blog_new_article',    // created/edited in panel controller
            'panel_blog_my_articles',    // list own posts
            'panel_blog_delete_article', // delete own post
            // 'panel_blog_lists',       // legacy, kept for backward compatibility
            'panel_blog_comments',
            
            // AI Contents
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
            $exists = Permission::where('role_id', $teacherRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $teacherRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("✓ Added " . count($insertData) . " new permissions for Teacher role");
        }
        
        // 2. Remove old permissions (delete ones not in the allowed list)
        $deletedCount = Permission::where('role_id', $teacherRoleId)
            ->whereNotIn('section_id', $allowedSectionIds)
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->warn("✗ Removed " . $deletedCount . " permissions no longer in the allowed list");
        }
        
        // 3. Summary
        $totalPermissions = Permission::where('role_id', $teacherRoleId)->count();
        $this->command->info("── Teacher role now has {$totalPermissions} permissions total");
    }
}
