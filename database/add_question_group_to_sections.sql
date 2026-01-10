-- Migration: Add question_group_id to ielts_test_sections
-- This links test sections directly to question groups (Parts)

ALTER TABLE `ielts_test_sections` 
ADD COLUMN `question_group_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `test_id`,
ADD INDEX `fk_section_question_group` (`question_group_id`);

-- Link existing sections to groups based on matching skill and section_type
-- (Run this manually if you want to auto-link existing data)

-- UPDATE ielts_test_sections s
-- INNER JOIN ielts_question_groups g ON s.skill = g.skill
-- SET s.question_group_id = g.id
-- WHERE s.question_group_id IS NULL;
