-- ================================================================
-- QUESTION GROUPS MIGRATION
-- Adds support for grouping multiple questions under one passage/audio
-- ================================================================

-- Create new table for Question Groups
CREATE TABLE IF NOT EXISTS `ielts_question_groups` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `creator_id` INT UNSIGNED NOT NULL,
    `bank_type` ENUM('mock', 'practice') NOT NULL,
    `skill` ENUM('listening', 'reading', 'writing', 'speaking') NOT NULL,
    
    -- Group metadata
    `title` VARCHAR(255) NOT NULL COMMENT 'e.g., "Climate Change Passage", "Part 1 Audio"',
    `description` TEXT NULL,
    
    -- Content (skill-specific, nullable)
    `passage` LONGTEXT NULL COMMENT 'Reading passage with rich text',
    `transcript` TEXT NULL COMMENT 'Listening transcript',
    `audio_file` VARCHAR(500) NULL COMMENT 'Path to audio file',
    `task_image` VARCHAR(500) NULL COMMENT 'Writing task chart/diagram',
    
    -- Practice-specific
    `target_band` DECIMAL(2,1) NULL COMMENT 'For practice questions',
    `practice_focus` VARCHAR(100) NULL,
    
    -- Metadata
    `tags` JSON NULL COMMENT 'Topics: ["environment", "technology"]',
    `difficulty_level` ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    `usage_count` INT UNSIGNED DEFAULT 0 COMMENT 'Times used in tests',
    
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    
    INDEX `idx_bank_skill` (`bank_type`, `skill`),
    INDEX `idx_creator` (`creator_id`),
    INDEX `idx_difficulty` (`difficulty_level`),
    INDEX `idx_deleted` (`deleted_at`),
    FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- Update Mock Question Bank
-- ================================================================

-- Add group_id column
ALTER TABLE `ielts_mock_question_bank`
ADD COLUMN `group_id` INT UNSIGNED NULL COMMENT 'Link to question group, NULL for standalone' AFTER `id`;

-- Add question_order column
ALTER TABLE `ielts_mock_question_bank`
ADD COLUMN `question_order` TINYINT UNSIGNED NULL COMMENT 'Order within group (1-14)';

-- Add index and foreign key for group_id
ALTER TABLE `ielts_mock_question_bank`
ADD INDEX `idx_group` (`group_id`);

ALTER TABLE `ielts_mock_question_bank`
ADD FOREIGN KEY `fk_mock_group` (`group_id`) 
    REFERENCES `ielts_question_groups`(`id`) 
    ON DELETE CASCADE;

-- Make passage/audio/transcript nullable (now can be stored in group)
ALTER TABLE `ielts_mock_question_bank`
MODIFY COLUMN `passage` LONGTEXT NULL;

ALTER TABLE `ielts_mock_question_bank`
MODIFY COLUMN `transcript` TEXT NULL;

ALTER TABLE `ielts_mock_question_bank`
MODIFY COLUMN `audio_file` VARCHAR(500) NULL;

ALTER TABLE `ielts_mock_question_bank`
MODIFY COLUMN `task_image` VARCHAR(500) NULL;

-- ================================================================
-- Update Practice Question Bank
-- ================================================================

-- Add group_id column
ALTER TABLE `ielts_practice_question_bank`
ADD COLUMN `group_id` INT UNSIGNED NULL COMMENT 'Link to question group, NULL for standalone' AFTER `id`;

-- Add question_order column
ALTER TABLE `ielts_practice_question_bank`
ADD COLUMN `question_order` TINYINT UNSIGNED NULL COMMENT 'Order within group (1-14)';

-- Add index and foreign key for group_id
ALTER TABLE `ielts_practice_question_bank`
ADD INDEX `idx_group` (`group_id`);

ALTER TABLE `ielts_practice_question_bank`
ADD FOREIGN KEY `fk_practice_group` (`group_id`) 
    REFERENCES `ielts_question_groups`(`id`) 
    ON DELETE CASCADE;

-- Make passage/audio/transcript nullable
ALTER TABLE `ielts_practice_question_bank`
MODIFY COLUMN `passage` LONGTEXT NULL;

ALTER TABLE `ielts_practice_question_bank`
MODIFY COLUMN `transcript` TEXT NULL;

ALTER TABLE `ielts_practice_question_bank`
MODIFY COLUMN `audio_file` VARCHAR(500) NULL;

ALTER TABLE `ielts_practice_question_bank`
MODIFY COLUMN `task_image` VARCHAR(500) NULL;

-- ================================================================
-- Sample Data (Optional - for testing)
-- ================================================================

-- Insert a sample Reading group
INSERT INTO `ielts_question_groups` (
    `creator_id`, `bank_type`, `skill`, `title`, `description`,
    `passage`, `difficulty_level`, `tags`
) VALUES (
    1, -- Replace with valid creator_id
    'mock',
    'reading',
    'Climate Change and Its Impact on Ocean Ecosystems',
    'IELTS Academic Reading passage about environmental science',
    '<p>Scientists have long warned about the devastating effects of climate change on our planet\'s ecosystems. Recent studies have shown that rising ocean temperatures are having particularly severe impacts on marine life...</p>',
    'intermediate',
    '["environment", "science", "ocean", "climate-change"]'
);

-- Add questions to the group (example)
-- Note: Update group_id to match the inserted group
INSERT INTO `ielts_mock_question_bank` (
    `creator_id`, `group_id`, `question_order`, `skill`, `question_type`,
    `question_text`, `correct_answer`, `difficulty_level`, `points`
) VALUES
(1, 1, 1, 'reading', 'multiple_choice', 'What is the main topic of the passage?', 'A', 'intermediate', 1),
(1, 1, 2, 'reading', 'true_false_ng', 'Ocean temperatures have been rising steadily.', 'True', 'intermediate', 1),
(1, 1, 3, 'reading', 'fill_blank', 'Scientists describe the effects as _____.', 'devastating', 'intermediate', 1);

-- ================================================================
-- Verification Queries
-- ================================================================

-- Check structure
-- DESCRIBE ielts_question_groups;
-- DESCRIBE ielts_mock_question_bank;

-- View groups with question counts
-- SELECT 
--     g.id, g.title, g.skill, g.difficulty_level,
--     COUNT(q.id) as question_count
-- FROM ielts_question_groups g
-- LEFT JOIN ielts_mock_question_bank q ON g.id = q.group_id
-- GROUP BY g.id;
