<?php

namespace App\Mixins\Panel;

class SidebarItems
{

    static public function getItems(): array
    {
        $user = auth()->user();

        return [
            'main_menu' => self::getMainMenuSectionItems($user),
            'members' => self::getMembersSectionItems($user),
            'education' => self::getEducationSectionItems($user),
            'evaluation' => self::getEvaluationSectionItems($user),
            'financial_&_marketing' => self::getFinancialSectionItems($user),
            'communications' => self::getCommunicationsSectionItems($user),
            'user' => self::getOtherSectionItems($user),
            'support' => self::getSupportSectionItems($user),
        ];
    }

    static public function getMainMenuSectionItems($user)
    {
        $items = [];

        $items['dashboard'] = [
            'icon' => self::getIcon('dashboard'),
            'text' => trans('panel.dashboard'),
            'url' => '/panel',
            'items' => []
        ];

        if (!$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {
            $items['events'] = [
                'icon' => self::getIcon('events'),
                'text' => trans('update.events_calendar'),
                'url' => '/panel/events',
                'items' => []
            ];
        }


        return $items;
    }

    // Helper function to check if user is admin or teacher
    static private function isAdminOrTeacher($user)
    {
        if (!$user) {
            return false;
        }
        return $user->isAdmin() || $user->isTeacher() || $user->role_name === 'admin';
    }

    static public function getMembersSectionItems($user)
    {
        $items = [];

        if (self::isAdminOrTeacher($user)) {

            // Admin, Manager, and CEO can manage teachers
            if (in_array($user->role_name, ['admin', 'manager', 'ceo'])) {
                // Support both instructors and teachers permissions (teachers is alias for instructors)
                if ($user->can('panel_organization_instructors') || $user->can('panel_organization_teachers')) {
                    $items['teachers'] = [
                        'icon' => self::getIcon('instructors'),
                        'text' => trans('panel.teacher'),
                        'url' => '/panel/manage/teachers',
                        'items' => []
                    ];

                    if ($user->can('panel_organization_instructors_create') || $user->can('panel_organization_teachers_create')) {
                        $items['teachers']['items'][] = ['text' => trans('public.new'), 'url' => '/panel/manage/teachers/new'];
                    }

                    if ($user->can('panel_organization_instructors_lists') || $user->can('panel_organization_teachers_lists')) {
                        $items['teachers']['items'][] = ['text' => trans('public.list'), 'url' => '/panel/manage/teachers'];
                    }
                }
            }

            // Both Admin and Teacher can manage students
            // Feedback & Grading - standalone item above Student Tracking
            if ($user->isAdmin() || $user->isTeacher() || $user->isOrganization() || $user->isManager() || $user->isCeo()) {
                $items['feedback_grading'] = [
                    'icon' => self::getIcon('feedback_grading'),
                    'text' => 'Feedback & grading',
                    'url' => '/panel/ielts-grading',
                    'items' => []
                ];
            }

            // Both Admin and Teacher can manage students
            if ($user->can('panel_organization_students')) {
                // For teachers: direct link to My Students, no dropdown
                if ($user->isTeacher()) {
                    $items['students'] = [
                        'icon' => self::getIcon('students'),
                        'text' => trans('panel.students_tracking'),
                        'url' => '/panel/my-students',
                        'items' => []
                    ];
                } else {
                    // For admin and other roles: keep dropdown functionality
                    $items['students'] = [
                        'icon' => self::getIcon('students'),
                        'text' => trans('panel.students_tracking'),
                        'url' => '/panel/manage/students',
                        'items' => []
                    ];

                    if ($user->can('panel_organization_students_create')) {
                        $items['students']['items'][] = ['text' => trans('public.new'), 'url' => '/panel/manage/students/new'];
                    }
                }
            }

        }

        return $items;
    }

    static public function getEducationSectionItems($user)
    {
        $items = [];

        if ($user->can('panel_webinars') && !$user->isTeacher()) {
            // For user/student: direct link, no dropdown
            if ($user->isUser() || $user->isStudent()) {
                $items['webinars'] = [
                    'icon' => self::getIcon('webinars'),
                    'text' => trans('panel.webinars'),
                    'url' => '/panel/courses/purchases',
                    'items' => []
                ];
            } else {
            $items['webinars'] = [
                'icon' => self::getIcon('webinars'),
                'text' => trans('panel.webinars'),
                'url' => '/panel/courses',
                'items' => []
            ];

            if (self::isAdminOrTeacher($user)) {
                if ($user->can('panel_webinars_create')) {
                    $items['webinars']['items'][] = ['text' => trans('public.new'), 'url' => '/panel/courses/new'];
                }

                if ($user->can('panel_webinars_lists')) {
                    $items['webinars']['items'][] = ['text' => trans('panel.my_classes'), 'url' => '/panel/courses'];
                }

                if ($user->can('panel_webinars_invited_lists')) {
                    $items['webinars']['items'][] = ['text' => trans('panel.invited_classes'), 'url' => '/panel/courses/invitations'];
                }
            }

            if (!empty($user->organ_id) and $user->can('panel_webinars_organization_classes')) {
                $items['webinars']['items'][] = ['text' => trans('panel.organization_classes'), 'url' => '/panel/courses/organization_classes'];
            }

            if ($user->can('panel_webinars_my_purchases')) {
                $items['webinars']['items'][] = ['text' => trans('panel.my_courses'), 'url' => '/panel/courses/purchases'];
            }

            if (self::isAdminOrTeacher($user) and $user->can('panel_webinars_my_class_comments')) {
                $items['webinars']['items'][] = ['text' => trans('panel.my_class_comments'), 'url' => '/panel/courses/comments'];
            }

            if (!$user->isUser() && !$user->isStudent() && $user->can('panel_webinars_comments')) {
                $items['webinars']['items'][] = ['text' => trans('panel.my_comments'), 'url' => '/panel/courses/my-comments'];
            }
            } // end else
        }

        if ($user->can('panel_upcoming_courses') && !$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {
            $items['upcoming_courses'] = [
                'icon' => self::getIcon('upcoming_courses'),
                'text' => trans('update.upcoming_courses'),
                'url' => '/panel/upcoming_courses',
                'items' => []
            ];

            if (self::isAdminOrTeacher($user)) {
                if ($user->can('panel_upcoming_courses_create')) {
                    $items['upcoming_courses']['items'][] = ['text' => trans('public.new'), 'url' => '/panel/upcoming_courses/new'];
                }

                if ($user->can('panel_upcoming_courses_lists')) {
                    $items['upcoming_courses']['items'][] = ['text' => trans('update.my_upcoming_courses'), 'url' => '/panel/upcoming_courses'];
                }
            }

            if ($user->can('panel_upcoming_courses_followings')) {
                $items['upcoming_courses']['items'][] = ['text' => trans('update.following_courses'), 'url' => '/panel/upcoming_courses/followings'];
            }
        }


        if (self::isAdminOrTeacher($user) and $user->can('panel_bundles')) {
            $items['bundles'] = [
                'icon' => self::getIcon('bundles'),
                'text' => trans('update.bundles'),
                'url' => '/panel/bundles',
                'items' => []
            ];
        }

        if ($user->can('panel_meetings') && !$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {

            $items['meetings'] = [
                'icon' => self::getIcon('meetings'),
                'text' => trans('panel.meetings'),
                'url' => '/panel/meetings',
                'items' => []
            ];

            if ($user->can('panel_meetings_my_reservation')) {
                $items['meetings']['items'][] = ['text' => trans('public.my_reservation'), 'url' => '/panel/meetings/reservation'];
            }

            if (self::isAdminOrTeacher($user)) {
                if ($user->can('panel_meetings_requests')) {
                    $items['meetings']['items'][] = ['text' => trans('panel.requests'), 'url' => '/panel/meetings/requests'];
                }

                if ($user->can('panel_meetings_settings')) {
                    $items['meetings']['items'][] = ['text' => trans('panel.settings'), 'url' => '/panel/meetings/settings'];
                }
            }
        }

        // IELTS Tests - Different interfaces for different roles
        {
            // Admin/Teacher/Manager/CEO → Panel management with dropdown
            if ($user->isAdmin() || $user->isTeacher() || $user->isOrganization() || $user->isManager() || $user->isCeo()) {
                $ieltsItems = [
                    ['text' => trans('update.my_tests'), 'url' => '/panel/my-ielts-tests'],
                    ['text' => trans('update.create_new_test'), 'url' => '/panel/my-ielts-tests/create-inline'],
                    ['text' => trans('update.create_from_bank'), 'url' => '/panel/my-ielts-tests/create'],
                ];
                
                // Manager/CEO/Admin get additional menu for All Tests Overview
                if ($user->isAdmin() || $user->isManager() || $user->isCeo()) {
                    $ieltsItems[] = ['text' => trans('update.all_tests'), 'url' => '/admin/ielts-tests'];
                }
                
                $items['ielts_tests'] = [
                    'icon' => self::getIcon('ielts_tests'),
                    'text' => trans('update.ielts_tests'),
                    'url' => '/panel/my-ielts-tests',
                    'items' => $ieltsItems
                ];
            } 
            // Student/User → Two separate flat items (no dropdown)
            else {
                $items['mock_tests'] = [
                    'icon' => self::getIcon('ielts_tests'),
                    'text' => trans('update.mock_tests'),
                    'url' => '/panel/ielts-tests/mock',
                    'items' => []
                ];
                $items['practice_tests'] = [
                    'icon' => self::getIcon('ielts_tests'),
                    'text' => trans('update.practice_tests'),
                    'url' => '/panel/ielts-tests/practice',
                    'items' => []
                ];
            }
        }
        
        // Question Bank - For Teachers, Organizations, Admins, Managers, CEOs (role hierarchy)
        if ($user->isAdmin() || $user->isTeacher() || $user->isOrganization() || $user->isManager() || $user->isCeo()) {
            $questionBankItems = [
                ['text' => trans('panel.dashboard'), 'url' => '/panel/question-bank'],
                ['text' => trans('update.mock_groups'), 'url' => '/panel/question-groups?type=mock'],
                ['text' => trans('update.practice_groups'), 'url' => '/panel/question-groups?type=practice'],
            ];

            if (!$user->isTeacher()) {
                $questionBankItems[] = ['text' => trans('update.import_questions'), 'url' => '/panel/question-bank/import'];
            }
            
            // Admin/Manager/CEO can see Pending Approval for Question Bank
            if ($user->isAdmin() || $user->isManager() || $user->isCeo()) {
                $questionBankItems[] = ['text' => trans('update.pending_approval'), 'url' => '/admin/question-groups/pending'];
            }
            
            $items['question_bank'] = [
                'icon' => self::getIcon('question_bank'),
                'text' => trans('update.question_bank'),
                'url' => '/panel/question-bank',
                'items' => $questionBankItems
            ];
        }

        // Dictionary & Flashcard - Hidden for teacher role
        if (!$user->isTeacher()) {
            $items['dictionary'] = [
                'icon' => self::getIcon('dictionary'),
                'text' => trans('panel.dictionary_and_flashcard'),
                'url' => '/panel/dictionary',
                'items' => []
            ];
        }

        if ($user->can('panel_quizzes') && !$user->isUser() && !$user->isStudent()) {
            $items['quizzes'] = [
                'icon' => self::getIcon('quizzes'),
                'text' => trans('panel.quizzes'),
                'url' => '/panel/quizzes',
                'items' => []
            ];

            if (self::isAdminOrTeacher($user)) {
                if ($user->can('panel_quizzes_create')) {
                    $items['quizzes']['items'][] = ['text' => trans('quiz.new_quiz'), 'url' => '/panel/quizzes/new'];
                }

                if ($user->can('panel_quizzes_lists')) {
                    $items['quizzes']['items'][] = ['text' => trans('public.list'), 'url' => '/panel/quizzes'];
                }

                if ($user->can('panel_quizzes_results')) {
                    $items['quizzes']['items'][] = ['text' => trans('public.results'), 'url' => '/panel/quizzes/results'];
                }
            }

            if (($user->isStudent() || $user->isUser()) && $user->can('panel_quizzes_my_results')) {
                $items['quizzes']['items'][] = ['text' => trans('public.my_results'), 'url' => '/panel/quizzes/my-results'];
            }

            if (($user->isStudent() || $user->isUser()) && $user->can('panel_quizzes_not_participated')) {
                $items['quizzes']['items'][] = ['text' => trans('update.not_participated'), 'url' => '/panel/quizzes/opens'];
            }
        }

        if ($user->can('panel_certificates') && !$user->isUser() && !$user->isStudent()) {
            $items['certificates'] = [
                'icon' => self::getIcon('certificates'),
                'text' => trans('panel.certificates'),
                'url' => '/panel/certificates',
                'items' => []
            ];

            if ((self::isAdminOrTeacher($user)) and $user->can('panel_certificates_lists')) {
                $items['certificates']['items'][] = ['text' => trans('public.list'), 'url' => '/panel/certificates'];
                $items['certificates']['items'][] = ['text' => trans('webinars.all_students'), 'url' => '/panel/certificates/students'];
            }

            if ($user->can('panel_certificates_achievements')) {
                $items['certificates']['items'][] = ['text' => trans('update.my_achievements'), 'url' => '/panel/certificates/my-achievements'];
            }

            if (!$user->isUser() && !$user->isStudent()) {
                $items['certificates']['items'][] = ['text' => trans('site.certificate_validation'), 'url' => '/certificate_validation'];
            }
        }

        return $items;
    }

    static public function getEvaluationSectionItems($user)
    {
        if ($user->isTeacher()) {
            return [];
        }

        $items = [];

        // Students Tracking for Instructors
        if (($user->isOrganization() || $user->isTeacher()) && $user->can('panel_webinars')) {
            $items['students_tracking'] = [
                'icon' => self::getIcon('students'),
                'text' => trans('panel.students_tracking'),
                'url' => '/panel/students-tracking',
                'items' => []
            ];
        }

        if ($user->can('panel_assignments') && !$user->isUser() && !$user->isStudent()) {
            $items['assignments'] = [
                'icon' => self::getIcon('assignments'),
                'text' => trans('update.assignments'),
                'url' => '/panel/assignments',
                'items' => []
            ];

            if ($user->can('panel_assignments_lists')) {
                $items['assignments']['items'][] = ['text' => trans('update.my_assignments'), 'url' => '/panel/assignments/my-requests'];
            }

            if ((self::isAdminOrTeacher($user)) and $user->can('panel_assignments_my_courses_assignments')) {
                $items['assignments']['items'][] = ['text' => trans('update.assignments'), 'url' => '/panel/assignments'];
                $items['assignments']['items'][] = ['text' => trans('update.students_assignments'), 'url' => '/panel/assignments/histories'];
            }
        }


        return $items;
    }

    static public function getFinancialSectionItems($user)
    {
        // Hide entire Financial & Marketing section for user/student/teacher roles
        if ($user->isUser() || $user->isStudent() || $user->isTeacher()) {
            return [];
        }

        $items = [];

        if ($user->can('panel_products')) {
            $items['store'] = [
                'icon' => self::getIcon('store'),
                'text' => trans('update.store'),
                'url' => '/panel/store',
                'items' => []
            ];

            if (self::isAdminOrTeacher($user)) {

                if ($user->can('panel_products_create')) {
                    $items['store']['items'][] = ['text' => trans('update.new_product'), 'url' => '/panel/store/products/new'];
                }

                if ($user->can('panel_products_lists')) {
                    $items['store']['items'][] = ['text' => trans('update.products'), 'url' => '/panel/store/products'];
                }

                if ($user->can('panel_products_sales')) {
                    $items['store']['items'][] = ['text' => trans('panel.sales'), 'url' => '/panel/store/sales'];
                }
            }

            // Only show purchases for students and above (not for leads)
        if (!$user->isUser() && $user->can('panel_products_purchases')) {
            $items['store']['items'][] = ['text' => trans('panel.my_purchases'), 'url' => '/panel/store/purchases'];
        }
            if ((self::isAdminOrTeacher($user)) and $user->can('panel_products_comments')) {
                $items['store']['items'][] = ['text' => trans('update.product_comments'), 'url' => '/panel/store/products/comments'];
            }

            if ($user->can('panel_products_my_comments')) {
                $items['store']['items'][] = ['text' => trans('panel.my_comments'), 'url' => '/panel/store/products/my-comments'];
            }
        }

        if ($user->can('panel_financial')) {

            $items['financial'] = [
                'icon' => self::getIcon('financial'),
                'text' => trans('panel.financial'),
                'url' => '/panel/financial',
                'items' => []
            ];

            if ((self::isAdminOrTeacher($user)) and $user->can('panel_financial_sales_reports')) {
                $items['financial']['items'][] = ['text' => trans('financial.sales_report'), 'url' => '/panel/financial/sales'];
            }

            if ($user->can('panel_financial_summary')) {
                $items['financial']['items'][] = ['text' => trans('financial.financial_summary'), 'url' => '/panel/financial/summary'];
            }

            // Only show payout for users with income streams
            // - Teachers/Admin/Manager/CEO: from course sales
            // - Students: only if they have affiliate enabled
            if ($user->can('panel_financial_payout')) {
                $isTeacherOrAbove = !$user->isUser() && !$user->isStudent();
                $isStudentWithAffiliate = $user->isStudent() && !empty($user->affiliate);
                
                if ($isTeacherOrAbove || $isStudentWithAffiliate) {
                    $items['financial']['items'][] = ['text' => trans('financial.payout'), 'url' => '/panel/financial/payout'];
                }
            }
            if ($user->can('panel_financial_charge_account')) {
                $items['financial']['items'][] = ['text' => trans('financial.charge_account'), 'url' => '/panel/financial/account'];
            }

            if ($user->can('panel_financial_subscribes')) {
                $items['financial']['items'][] = ['text' => trans('financial.subscribes'), 'url' => '/panel/financial/subscribes'];
            }

            if ((self::isAdminOrTeacher($user)) and getRegistrationPackagesGeneralSettings('status') and $user->can('panel_financial_registration_packages')) {
                $items['financial']['items'][] = ['text' => trans('update.registration_packages'), 'url' => route('panelRegistrationPackagesLists')];
            }

            if ($user->can('panel_financial_installments')) {
                $items['financial']['items'][] = ['text' => trans('update.installments'), 'url' => '/panel/financial/installments'];
            }

        }

        $referralSettings = getReferralSettings();

        if (
            (
                !$user->isUser() or
                (!empty($referralSettings) and $referralSettings['status'] and $user->affiliate) or
                (!empty(getRegistrationBonusSettings('status')) and $user->enable_registration_bonus)
            ) and $user->can('panel_marketing')
        ) {

            $items['marketing'] = [
                'icon' => self::getIcon('marketing'),
                'text' => trans('panel.marketing'),
                'url' => '/panel/marketing',
                'items' => [

                ]
            ];


            if (!$user->isUser()) {
                if ($user->can('panel_marketing_special_offers')) {
                    $items['marketing']['items'][] = ['text' => trans('panel.discounts'), 'url' => '/panel/marketing/special_offers'];
                }

                if ($user->can('panel_marketing_promotions')) {
                    $items['marketing']['items'][] = ['text' => trans('panel.promotions'), 'url' => '/panel/marketing/promotions'];
                }
            }

            if ($user->can('panel_marketing_affiliates')) {
                $items['marketing']['items'][] = ['text' => trans('panel.affiliates'), 'url' => '/panel/marketing/affiliates'];
            }

            if ($user->can('panel_marketing_registration_bonus')) {
                $items['marketing']['items'][] = ['text' => trans('update.registration_bonus'), 'url' => '/panel/marketing/registration_bonus'];
            }

            if (!empty(getFeaturesSettings('frontend_coupons_status'))) {
                if ($user->can('panel_marketing_coupons')) {
                    $items['marketing']['items'][] = ['text' => trans('update.coupons'), 'url' => '/panel/marketing/discounts'];
                }

                if ($user->can('panel_marketing_new_coupon')) {
                    $items['marketing']['items'][] = ['text' => trans('update.new_coupon'), 'url' => '/panel/marketing/discounts/new'];
                }
            }

        }

        // rewards
        $rewardSetting = getRewardsSettings();

        if ($user->can('panel_rewards')) {
            $items['rewards'] = [
                'icon' => self::getIcon('rewards'),
                'text' => trans('update.rewards'),
                'url' => '/panel/rewards',
                'items' => []
            ];
        }

        return $items;
    }

    static public function getCommunicationsSectionItems($user)
    {
        $items = [];

        // Forums
        if ($user->can('panel_forums') && !$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {
            $items['forums'] = [
                'icon' => self::getIcon('forums'),
                'text' => trans('update.forums'),
                'url' => '/forums',
                'items' => []
            ];

            if ($user->can('panel_forums_new_topic')) {
                $items['forums']['items'][] = ['text' => trans('update.new_topic'), 'url' => '/forums/create-topic'];
            }

            if ($user->can('panel_forums_my_topics')) {
                $items['forums']['items'][] = ['text' => trans('update.my_topics'), 'url' => '/panel/forums/topics'];
            }

            if ($user->can('panel_forums_my_posts')) {
                $items['forums']['items'][] = ['text' => trans('update.my_posts'), 'url' => '/panel/forums/posts'];
            }

            if ($user->can('panel_forums_bookmarks')) {
                $items['forums']['items'][] = ['text' => trans('update.bookmarks'), 'url' => '/panel/forums/bookmarks'];
            }
        }


        // Articles
        if ((self::isAdminOrTeacher($user)) and $user->can('panel_blog')) {

            $items['blog'] = [
                'icon' => self::getIcon('blog'),
                'text' => trans('update.articles'),
                'url' => '/panel/blog',
                'items' => [

                ]
            ];

            if (!$user->isTeacher() && $user->can('panel_blog_new_article')) {
                $items['blog']['items'][] = ['text' => trans('update.new_article'), 'url' => '/panel/blog/new'];
            }

            if ($user->can('panel_blog_my_articles')) {
                $items['blog']['items'][] = ['text' => trans('update.my_articles'), 'url' => '/panel/blog'];
            }

            if ($user->can('panel_blog_comments')) {
                $items['blog']['items'][] = ['text' => trans('panel.comments'), 'url' => '/panel/blog/comments'];
            }

        }


        // noticeboard
        if ((self::isAdminOrTeacher($user)) and $user->can('panel_noticeboard')) {
            $items['noticeboard'] = [
                'icon' => self::getIcon('noticeboard'),
                'text' => trans('panel.noticeboard'),
                'url' => '/panel/noticeboard',
                'extraUrl' => '/panel/course-noticeboard',
                'items' => []
            ];

            if ($user->can('panel_noticeboard_history')) {
                $items['noticeboard']['items'][] = ['text' => trans('public.history'), 'url' => '/panel/noticeboard'];
            }

            if ($user->can('panel_noticeboard_create')) {
                $items['noticeboard']['items'][] = ['text' => trans('public.new'), 'url' => '/panel/noticeboard/new'];
            }

            if ($user->can('panel_noticeboard_course_notices')) {
                $items['noticeboard']['items'][] = ['text' => trans('update.course_notices'), 'url' => '/panel/course-noticeboard'];
            }

            if ($user->can('panel_noticeboard_course_notices_create')) {
                $items['noticeboard']['items'][] = ['text' => trans('update.new_course_notices'), 'url' => '/panel/course-noticeboard/new'];
            }
        }

        // AI Contents
        if ($user->checkAccessToAIContentFeature() and $user->can('panel_ai_contents')) {
            $items['ai_contents'] = [
                'icon' => self::getIcon('ai_contents'),
                'text' => trans('update.ai_contents'),
                'url' => '/panel/ai-contents',
                'items' => []
            ];
        }

        if ($user->can('panel_notifications') && !$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {
            $items['notifications'] = [
                'icon' => self::getIcon('notifications'),
                'text' => trans('panel.notifications'),
                'url' => '/panel/notifications',
                'items' => []
            ];
        }

        return $items;
    }

    static public function getSupportSectionItems($user)
    {
        $items = [];

        if ($user->can('panel_support')) {
            $items['support'] = [
                'icon' => self::getIcon('support'),
                'text' => trans('panel.support'),
                'url' => '/panel/support',
                'items' => []
            ];

            if ($user->can('panel_support_lists')) {
                $items['support']['items'][] = ['text' => trans('update.classes_support'), 'url' => '/panel/support'];
            }

            if ($user->can('panel_support_tickets')) {
                $items['support']['items'][] = ['text' => trans('update.support_tickets'), 'url' => '/panel/support/tickets'];
            }
        }

        return $items;
    }

    static public function getOtherSectionItems($user)
    {
        $items = [];

        if ($user->can('panel_others_profile_url') && !$user->isUser() && !$user->isStudent() && !$user->isTeacher()) {
            $items['profile'] = [
                'icon' => self::getIcon('profile'),
                'text' => trans('public.my_profile'),
                'url' => $user->getProfileUrl(),
                'items' => []
            ];

            if ($user->isAdmin()) {
                $items['admin_performance'] = [
                    'icon' => self::getIcon('dashboard'),
                    'text' => 'Admin Performance',
                    'url' => '/panel/admin-performance',
                    'items' => []
                ];
            }
        }

        if ($user->can('panel_others_profile_setting')) {
            $items['setting'] = [
                'icon' => self::getIcon('setting'),
                'text' => trans('public.my_profile'),
                'url' => '/panel/setting',
                'items' => []
            ];
        }

        if ($user->can('panel_others_logout')) {
            $items['logout'] = [
                'icon' => self::getIcon('logout'),
                'text' => trans('panel.log_out'),
                'url' => '/logout',
                'className' => 'text-danger',
                'items' => []
            ];
        }

        return $items;
    }

    static public function getIcon($name)
    {
        return view()->make('design_1.panel.includes.sidebar.icons', ['name' => $name]);
    }

}

