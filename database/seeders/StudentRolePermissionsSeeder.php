<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Section;
use Illuminate\Database\Seeder;

class StudentRolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $studentRoleId = Role::getStudentRoleId(); // role_id = 2
        
        // Get all sections that students should have access to
        $allowedSections = [
            // Main Menu
            'panel_general_dashboard',
            'panel_general_events_calendar',
            
            // EDUCATION Section
            // Courses
            'panel_webinars',
            'panel_webinars_organization_classes',
            'panel_webinars_my_purchases',
            'panel_webinars_comments',
            'panel_webinars_favorites',
            'panel_webinars_personal_course_notes',
            'panel_webinars_attendance',
            'panel_webinars_learning_page',
            
            // Upcoming Courses
            'panel_upcoming_courses',
            'panel_upcoming_courses_followings',
            
            // Events
            'panel_events',
            'panel_events_organization_events',
            'panel_events_my_purchases',
            'panel_events_comments',
            
            // Meetings
            'panel_meetings',
            'panel_meetings_my_reservation',
            'panel_meetings_purchased_packages',
            
            // EVALUATION Section
            // Assignments
            'panel_assignments',
            'panel_assignments_lists',
            
            // Quizzes
            'panel_quizzes',
            'panel_quizzes_my_results',
            'panel_quizzes_not_participated',
            
            // Certificates
            'panel_certificates',
            'panel_certificates_achievements',
            'panel_certificates_validate',
            
            // FINANCIAL & MARKETING Section
            // Store
            'panel_store',
            'panel_store_my_purchases',
            'panel_store_comments',
            
            // Financial
            'panel_financial',
            'panel_financial_summary',
            'panel_financial_payout',
            'panel_financial_charge_account',
            'panel_financial_subscribes',
            'panel_financial_installments',
            
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
            
            // Forums
            'panel_forums',
            'panel_forums_new_topic',
            'panel_forums_my_topics',
            'panel_forums_my_posts',
            'panel_forums_bookmarks',
            
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
        
        $insertData = [];
        $now = time();
        
        foreach ($sections as $section) {
            // Check if permission already exists
            $exists = Permission::where('role_id', $studentRoleId)
                ->where('section_id', $section->id)
                ->exists();
            
            if (!$exists) {
                $insertData[] = [
                    'role_id' => $studentRoleId,
                    'section_id' => $section->id,
                    'allow' => true,
                ];
            }
        }
        
        if (!empty($insertData)) {
            Permission::insert($insertData);
            $this->command->info("Created " . count($insertData) . " permissions for Student role (role_id: {$studentRoleId})");
        } else {
            $this->command->info("All permissions already exist for Student role");
        }
    }
}
