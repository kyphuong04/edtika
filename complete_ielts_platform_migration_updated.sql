-- ============================================
-- Complete IELTS Platform Database Migration
-- Missing Tables: 20 tables + translation tables
-- Compatible with existing database structure
-- ============================================

-- ============================================
-- SECTION 1: FLASHCARD SYSTEM (4 tables)
-- ============================================

CREATE TABLE `flashcard_decks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `card_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User flashcard decks';

CREATE TABLE `flashcard_deck_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `flashcard_deck_id` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  KEY `flashcard_deck_id` (`flashcard_deck_id`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `flashcards` (
  `id` int(10) UNSIGNED NOT NULL,
  `deck_id` int(10) UNSIGNED NOT NULL,
  `front` text NOT NULL COMMENT 'Word/Question',
  `back` text NOT NULL COMMENT 'Definition/Answer',
  `image` varchar(255) DEFAULT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `example_sentence` text DEFAULT NULL,
  `source_type` enum('manual','auto_from_lesson','dictionary') NOT NULL DEFAULT 'manual',
  `source_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'lesson_id or word_id',
  `order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  KEY `deck_id` (`deck_id`),
  KEY `source_type` (`source_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual flashcards';

CREATE TABLE `flashcard_review_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `flashcard_id` int(10) UNSIGNED NOT NULL,
  `quality` tinyint(1) NOT NULL COMMENT 'SM-2: 0-5, 5=perfect recall',
  `ease_factor` decimal(4,2) NOT NULL DEFAULT 2.50,
  `interval_days` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `next_review_date` date NOT NULL,
  `reviewed_at` bigint(20) UNSIGNED NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `flashcard_id` (`flashcard_id`),
  KEY `next_review_date` (`next_review_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Spaced repetition review history';

-- ============================================
-- SECTION 2: DICTIONARY SYSTEM (3 tables)
-- ============================================

CREATE TABLE `dictionary_words` (
  `id` int(10) UNSIGNED NOT NULL,
  `word` varchar(255) NOT NULL,
  `pronunciation_us` varchar(100) DEFAULT NULL,
  `pronunciation_uk` varchar(100) DEFAULT NULL,
  `audio_us` varchar(255) DEFAULT NULL,
  `audio_uk` varchar(255) DEFAULT NULL,
  `word_type` enum('noun','verb','adjective','adverb','pronoun','preposition','conjunction','interjection') DEFAULT NULL,
  `difficulty_level` enum('a1','a2','b1','b2','c1','c2') DEFAULT NULL COMMENT 'CEFR levels',
  `ielts_band` decimal(2,1) DEFAULT NULL COMMENT '4.0-9.0',
  `frequency_rank` int(10) UNSIGNED DEFAULT NULL COMMENT 'Lower = more common',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  UNIQUE KEY `word` (`word`),
  KEY `word_type` (`word_type`),
  KEY `difficulty_level` (`difficulty_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Dictionary word entries';

CREATE TABLE `dictionary_word_translations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `word_id` int(10) UNSIGNED NOT NULL,
  `locale` varchar(255) NOT NULL,
  `definition` text DEFAULT NULL,
  `example_sentences` json DEFAULT NULL,
  `synonyms` json DEFAULT NULL,
  `antonyms` json DEFAULT NULL,
  KEY `word_id` (`word_id`),
  KEY `locale` (`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_saved_words` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `word_id` int(10) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `mastery_level` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=new, 1=learning, 2=reviewing, 3=mastered',
  `saved_at` bigint(20) UNSIGNED NOT NULL,
  `last_reviewed_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `user_word` (`user_id`,`word_id`),
  KEY `user_id` (`user_id`),
  KEY `word_id` (`word_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User bookmarked vocabulary';

-- ============================================
-- SECTION 3: KPI TRACKING (3 tables)
-- ============================================

CREATE TABLE `teacher_kpis` (
  `id` int(10) UNSIGNED NOT NULL,
  `teacher_id` int(10) UNSIGNED NOT NULL,
  `period_type` enum('daily','weekly','monthly') NOT NULL,
  `period_date` date NOT NULL COMMENT 'Start date of period',
  `avg_response_time_minutes` decimal(10,2) DEFAULT NULL,
  `messages_answered` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `student_queries_total` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `writing_graded_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `speaking_graded_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_graded_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_grading_time_hours` decimal(10,2) DEFAULT NULL,
  `pending_grading_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `active_students_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_student_rating` decimal(3,2) DEFAULT NULL COMMENT '0.00-5.00 stars',
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lessons_created` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lessons_approved` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `lessons_rejected` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `teacher_period` (`teacher_id`,`period_type`,`period_date`),
  KEY `teacher_id` (`teacher_id`),
  KEY `period_date` (`period_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Teacher performance KPIs';

CREATE TABLE `admin_kpis` (
  `id` int(10) UNSIGNED NOT NULL,
  `admin_id` int(10) UNSIGNED NOT NULL,
  `period_type` enum('daily','weekly','monthly') NOT NULL,
  `period_date` date NOT NULL,
  `tickets_received` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tickets_resolved` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `tickets_pending` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_resolution_time_hours` decimal(10,2) DEFAULT NULL,
  `content_reviewed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `content_approved` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `content_rejected` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reports_processed` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_contacted` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_converted` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `admin_period` (`admin_id`,`period_type`,`period_date`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin/Staff performance tracking';

CREATE TABLE `manager_kpis` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(10) UNSIGNED NOT NULL,
  `period_type` enum('daily','weekly','monthly','quarterly') NOT NULL,
  `period_date` date NOT NULL,
  -- Team performance metrics
  `total_teachers` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_admins` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_sales` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `avg_team_kpi_achievement` decimal(5,2) DEFAULT NULL COMMENT 'Average KPI achievement % of team',
  -- Student metrics
  `total_active_students` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `new_students_enrolled` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `student_retention_rate` decimal(5,2) DEFAULT NULL COMMENT 'Percentage',
  `avg_student_satisfaction` decimal(3,2) DEFAULT NULL COMMENT '0.00-5.00 stars',
  -- Revenue metrics (overview)
  `total_revenue` decimal(15,2) NOT NULL DEFAULT 0.00,
  `target_revenue` decimal(15,2) DEFAULT NULL,
  `revenue_achievement_rate` decimal(5,2) DEFAULT NULL,
  -- Quality metrics
  `course_completion_rate` decimal(5,2) DEFAULT NULL COMMENT 'Overall completion rate',
  `avg_test_pass_rate` decimal(5,2) DEFAULT NULL COMMENT 'Students passing tests',
  `content_quality_score` decimal(5,2) DEFAULT NULL COMMENT 'Based on reviews/reports',
  -- Operational metrics
  `pending_approvals` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Content waiting approval',
  `escalated_issues` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `resolved_escalations` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `manager_period` (`manager_id`,`period_type`,`period_date`),
  KEY `manager_id` (`manager_id`),
  KEY `period_date` (`period_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Manager performance and team oversight KPIs';


CREATE TABLE `sales_kpis` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'Sale/Admin with sales role',
  `period_type` enum('weekly','monthly','quarterly','yearly') NOT NULL,
  `period_date` date NOT NULL,
  `leads_assigned` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_contacted` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_qualified` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_converted` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `leads_rejected` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `conversion_rate` decimal(5,2) DEFAULT NULL COMMENT 'Percentage',
  `revenue_generated` decimal(15,2) NOT NULL DEFAULT 0.00,
  `target_revenue` decimal(15,2) DEFAULT NULL,
  `achievement_rate` decimal(5,2) DEFAULT NULL COMMENT 'Percentage',
  `calls_made` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `emails_sent` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `meetings_held` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `user_period` (`user_id`,`period_type`,`period_date`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sales performance tracking';

-- ============================================
-- SECTION 3B: KPI TARGET CONFIGURATION (1 table)
-- ============================================

CREATE TABLE `role_kpi_targets` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL COMMENT 'Teacher, Admin, Manager role',
  `period_type` enum('daily','weekly','monthly','quarterly','yearly') NOT NULL,
  `kpi_category` enum('teacher','admin','sales','general') NOT NULL,
  `metric_name` varchar(100) NOT NULL COMMENT 'e.g., avg_response_time_minutes, tickets_resolved',
  `target_value` decimal(15,2) NOT NULL COMMENT 'Numeric target',
  `comparison_operator` enum('gte','lte','eq') NOT NULL DEFAULT 'gte' COMMENT 'gte=>=, lte=<=, eq==',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `description` text DEFAULT NULL,
  `reward_points` int(10) UNSIGNED DEFAULT NULL COMMENT 'Points if target met',
  `created_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'CEO/Manager who set it',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  KEY `role_id` (`role_id`),
  KEY `period_type` (`period_type`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='KPI targets set by CEO/Manager for roles';


-- ============================================
-- SECTION 4: LEAD/CRM MANAGEMENT (2 tables)
-- ============================================

CREATE TABLE `leads` (
  `id` int(10) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL COMMENT 'Landing page, Facebook, Google Ads, etc',
  `utm_source` varchar(100) DEFAULT NULL,
  `utm_campaign` varchar(100) DEFAULT NULL,
  `utm_medium` varchar(100) DEFAULT NULL,
  `target_band` decimal(2,1) DEFAULT NULL,
  `current_level` varchar(50) DEFAULT NULL,
  `interested_courses` json DEFAULT NULL,
  `diagnostic_test_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'If took placement test',
  `status` enum('new','contacted','qualified','converted','rejected','lost') NOT NULL DEFAULT 'new',
  `assigned_to` int(10) UNSIGNED DEFAULT NULL COMMENT 'Sale/Admin assigned',
  `notes` text DEFAULT NULL,
  `converted_user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'User ID after conversion',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  KEY `status` (`status`),
  KEY `assigned_to` (`assigned_to`),
  KEY `email` (`email`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lead/prospect management';

CREATE TABLE `lead_activities` (
  `id` int(10) UNSIGNED NOT NULL,
  `lead_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'Who did the activity',
  `activity_type` enum('call','email','meeting','note','status_change','sms','whatsapp') NOT NULL,
  `description` text DEFAULT NULL,
  `outcome` varchar(255) DEFAULT NULL,
  `next_follow_up` datetime DEFAULT NULL,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  KEY `lead_id` (`lead_id`),
  KEY `user_id` (`user_id`),
  KEY `activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lead interaction history';

-- ============================================
-- SECTION 5: DIRECT CHAT SYSTEM (3 tables)
-- ============================================

CREATE TABLE `chat_conversations` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` enum('direct','group') NOT NULL DEFAULT 'direct',
  `title` varchar(255) DEFAULT NULL COMMENT 'For group chats',
  `created_by` int(10) UNSIGNED NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  KEY `created_by` (`created_by`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chat conversations';

CREATE TABLE `chat_participants` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversation_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `role` enum('member','admin') NOT NULL DEFAULT 'member',
  `last_read_at` bigint(20) UNSIGNED DEFAULT NULL,
  `joined_at` bigint(20) UNSIGNED NOT NULL,
  `left_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `conv_user` (`conversation_id`,`user_id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chat participants';

CREATE TABLE `chat_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `conversation_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `attachment_type` enum('image','file','audio','video') DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_system_message` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  KEY `conversation_id` (`conversation_id`),
  KEY `sender_id` (`sender_id`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Chat messages';

-- ============================================
-- SECTION 6: LEADERBOARD SYSTEM (2 tables)
-- ============================================

CREATE TABLE `leaderboards` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('weekly','monthly','all_time','skill_specific','course_specific') NOT NULL,
  `skill_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'For skill-specific',
  `webinar_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'For course-specific',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `prize_info` json DEFAULT NULL COMMENT 'Prizes for top ranks',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  KEY `type` (`type`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Leaderboard configurations';

CREATE TABLE `leaderboard_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `leaderboard_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `score` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `rank` int(10) UNSIGNED DEFAULT NULL,
  `additional_data` json DEFAULT NULL COMMENT 'Extra metrics',
  `updated_at` bigint(20) UNSIGNED NOT NULL,
  UNIQUE KEY `board_user` (`leaderboard_id`,`user_id`),
  KEY `leaderboard_id` (`leaderboard_id`),
  KEY `user_id` (`user_id`),
  KEY `rank` (`rank`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Leaderboard user rankings';

-- ============================================
-- SECTION 7: ENHANCED STREAK TRACKING (2 tables)
-- ============================================

CREATE TABLE `user_activity_streaks` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `current_streak` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `longest_streak` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_activity_date` date DEFAULT NULL,
  `freeze_tokens` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Skip days without breaking streak',
  `total_active_days` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `updated_at` bigint(20) UNSIGNED NOT NULL,
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User daily activity streaks';

CREATE TABLE `streak_history` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `activity_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `activities` json DEFAULT NULL COMMENT 'lessons, quizzes completed',
  `minutes_studied` int(10) UNSIGNED NOT NULL DEFAULT 0,
  UNIQUE KEY `user_date` (`user_id`,`date`),
  KEY `user_id` (`user_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Daily activity log';

-- ============================================
-- SECTION 8: STUDENT GOALS (1 table)
-- ============================================

CREATE TABLE `student_weekly_goals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `week_start_date` date NOT NULL,
  `week_end_date` date NOT NULL,
  `target_study_hours` decimal(5,2) DEFAULT NULL,
  `target_lessons` int(10) UNSIGNED DEFAULT NULL,
  `target_exercises` int(10) UNSIGNED DEFAULT NULL,
  `target_mock_tests` int(10) UNSIGNED DEFAULT NULL,
  `actual_study_hours` decimal(5,2) NOT NULL DEFAULT 0.00,
  `actual_lessons` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `actual_exercises` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `actual_mock_tests` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `achievement_rate` decimal(5,2) DEFAULT NULL COMMENT 'Overall percentage',
  `created_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `user_week` (`user_id`,`week_start_date`),
  KEY `user_id` (`user_id`),
  KEY `week_start_date` (`week_start_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Student weekly learning goals';

-- ============================================
-- SECTION 9: MOCK TEST SIMULATION (2 tables)
-- ============================================

CREATE TABLE `ielts_mock_tests` (
  `id` int(10) UNSIGNED NOT NULL,
  `quiz_id` int(10) UNSIGNED NOT NULL COMMENT 'Link to main quiz',
  `test_code` varchar(50) NOT NULL,
  `test_type` enum('academic','general') NOT NULL,
  `is_full_test` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=all 4 skills',
  `simulates_real_exam` tinyint(1) NOT NULL DEFAULT 1,
  `listening_duration` int(10) UNSIGNED NOT NULL DEFAULT 30 COMMENT 'minutes',
  `reading_duration` int(10) UNSIGNED NOT NULL DEFAULT 60,
  `writing_duration` int(10) UNSIGNED NOT NULL DEFAULT 60,
  `speaking_duration` int(10) UNSIGNED NOT NULL DEFAULT 15,
  `total_duration` int(10) UNSIGNED NOT NULL DEFAULT 165,
  `instructions` text DEFAULT NULL,
  `created_at` bigint(20) UNSIGNED NOT NULL,
  UNIQUE KEY `test_code` (`test_code`),
  KEY `quiz_id` (`quiz_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='IELTS mock test configurations';

CREATE TABLE `mock_test_sessions` (
  `id` int(10) UNSIGNED NOT NULL,
  `mock_test_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `started_at` bigint(20) UNSIGNED NOT NULL,
  `completed_at` bigint(20) UNSIGNED DEFAULT NULL,
  `current_section` enum('listening','reading','writing','speaking','completed') NOT NULL,
  `time_remaining_seconds` int(10) UNSIGNED DEFAULT NULL,
  `is_paused` tinyint(1) NOT NULL DEFAULT 0,
  `session_data` json DEFAULT NULL COMMENT 'Answers and progress',
  `device_info` json DEFAULT NULL COMMENT 'Browser, OS for security',
  KEY `mock_test_id` (`mock_test_id`),
  KEY `user_id` (`user_id`),
  KEY `started_at` (`started_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Active mock test sessions';

-- ============================================
-- PRIMARY KEYS & AUTO_INCREMENT
-- ============================================

-- Flashcard System
ALTER TABLE `flashcard_decks`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `flashcard_deck_translations`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `flashcards`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `flashcard_review_logs`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Dictionary System
ALTER TABLE `dictionary_words`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `dictionary_word_translations`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_saved_words`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- KPI Tracking
ALTER TABLE `teacher_kpis`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `admin_kpis`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


--KPI Manager
ALTER TABLE `manager_kpis`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `manager_kpis`
  ADD CONSTRAINT `manager_kpis_manager_id_foreign`
    FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `sales_kpis`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Lead/CRM
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `lead_activities`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Chat System
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `chat_participants`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Leaderboard
ALTER TABLE `leaderboards`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `leaderboard_entries`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Streak Tracking
ALTER TABLE `user_activity_streaks`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `streak_history`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Student Goals
ALTER TABLE `student_weekly_goals`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- Mock Tests
ALTER TABLE `ielts_mock_tests`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `mock_test_sessions`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

-- ============================================
-- FOREIGN KEY CONSTRAINTS
-- ============================================

-- Flashcard System
ALTER TABLE `flashcard_decks`
  ADD CONSTRAINT `flashcard_decks_user_id_foreign` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `flashcard_deck_translations`
  ADD CONSTRAINT `flashcard_deck_translations_deck_id_foreign`
    FOREIGN KEY (`flashcard_deck_id`) REFERENCES `flashcard_decks` (`id`) ON DELETE CASCADE;

ALTER TABLE `flashcards`
  ADD CONSTRAINT `flashcards_deck_id_foreign`
    FOREIGN KEY (`deck_id`) REFERENCES `flashcard_decks` (`id`) ON DELETE CASCADE;

ALTER TABLE `flashcard_review_logs`
  ADD CONSTRAINT `flashcard_review_logs_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `flashcard_review_logs_flashcard_id_foreign`
    FOREIGN KEY (`flashcard_id`) REFERENCES `flashcards` (`id`) ON DELETE CASCADE;

-- Dictionary System
ALTER TABLE `dictionary_word_translations`
  ADD CONSTRAINT `dict_word_translations_word_id_foreign`
    FOREIGN KEY (`word_id`) REFERENCES `dictionary_words` (`id`) ON DELETE CASCADE;

ALTER TABLE `user_saved_words`
  ADD CONSTRAINT `user_saved_words_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_saved_words_word_id_foreign`
    FOREIGN KEY (`word_id`) REFERENCES `dictionary_words` (`id`) ON DELETE CASCADE;

-- KPI Tracking
ALTER TABLE `teacher_kpis`
  ADD CONSTRAINT `teacher_kpis_teacher_id_foreign`
    FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `admin_kpis`
  ADD CONSTRAINT `admin_kpis_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `sales_kpis`
  ADD CONSTRAINT `sales_kpis_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
    -- KPI Targets
ALTER TABLE `role_kpi_targets`
  ADD PRIMARY KEY (`id`),
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
  -- KPI Targets
ALTER TABLE `role_kpi_targets`
  ADD CONSTRAINT `role_kpi_targets_role_id_foreign`
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_kpi_targets_created_by_foreign`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;


-- Lead/CRM
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_assigned_to_foreign`
    FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leads_converted_user_id_foreign`
    FOREIGN KEY (`converted_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

ALTER TABLE `lead_activities`
  ADD CONSTRAINT `lead_activities_lead_id_foreign`
    FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lead_activities_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Chat System
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_created_by_foreign`
    FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat_participants`
  ADD CONSTRAINT `chat_participants_conversation_id_foreign`
    FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_participants_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_conversation_id_foreign`
    FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign`
    FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Leaderboard
ALTER TABLE `leaderboards`
  ADD CONSTRAINT `leaderboards_skill_id_foreign`
    FOREIGN KEY (`skill_id`) REFERENCES `ielts_skills` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaderboards_webinar_id_foreign`
    FOREIGN KEY (`webinar_id`) REFERENCES `webinars` (`id`) ON DELETE SET NULL;

ALTER TABLE `leaderboard_entries`
  ADD CONSTRAINT `leaderboard_entries_leaderboard_id_foreign`
    FOREIGN KEY (`leaderboard_id`) REFERENCES `leaderboards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaderboard_entries_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Streak Tracking
ALTER TABLE `user_activity_streaks`
  ADD CONSTRAINT `user_activity_streaks_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `streak_history`
  ADD CONSTRAINT `streak_history_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Student Goals
ALTER TABLE `student_weekly_goals`
  ADD CONSTRAINT `student_weekly_goals_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

-- Mock Tests
ALTER TABLE `ielts_mock_tests`
  ADD CONSTRAINT `ielts_mock_tests_quiz_id_foreign`
    FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

ALTER TABLE `mock_test_sessions`
  ADD CONSTRAINT `mock_test_sessions_mock_test_id_foreign`
    FOREIGN KEY (`mock_test_id`) REFERENCES `ielts_mock_tests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mock_test_sessions_user_id_foreign`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;


-- ============================================
-- END OF MIGRATION
-- Total: 21 tables + 2 translation tables = 23 tables
-- ============================================

