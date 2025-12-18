# Complete IELTS Platform Database Migration

## Overview

This migration adds **20 essential tables** to complete the IELTS learning platform database.

**Total:** 20 main tables + 2 translation tables = **22 new tables**

---

## What's Included

### 1. Flashcard System (4 tables)
- `flashcard_decks` - User flashcard collections
- `flashcard_deck_translations` - Multi-language support
- `flashcards` - Individual cards with spaced repetition
- `flashcard_review_logs` - SM-2 algorithm tracking

### 2. Dictionary System (3 tables)
- `dictionary_words` - English-Vietnamese dictionary
- `dictionary_word_translations` - Definitions per language
- `user_saved_words` - Bookmarked vocabulary

### 3. KPI Tracking (3 tables)
- `teacher_kpis` - Teacher performance metrics
- `admin_kpis` - Admin/staff productivity
- `sales_kpis` - Sales team performance

### 4. Lead/CRM Management (2 tables)
- `leads` - Prospect/lead information
- `lead_activities` - Interaction history

### 5. Direct Chat (3 tables)
- `chat_conversations` - Chat rooms
- `chat_participants` - Conversation members
- `chat_messages` - Message history

### 6. Leaderboard (2 tables)
- `leaderboards` - Competition configurations
- `leaderboard_entries` - User rankings

### 7. Enhanced Streak (2 tables)
- `user_activity_streaks` - Daily streaks
- `streak_history` - Activity logs

### 8. Student Goals (1 table)
- `student_weekly_goals` - Weekly targets

### 9. Mock Tests (2 tables)
- `ielts_mock_tests` - Full test simulations
- `mock_test_sessions` - Active test sessions

---

## Installation

### Option 1: Direct MySQL Import (Recommended)

```bash
# Navigate to project root
cd c:\xampp\htdocs\edtika

# Import via MySQL command line
mysql -u root -p edtika < database/migrations/complete_ielts_platform_migration.sql

# Or using phpMyAdmin:
# 1. Open phpMyAdmin
# 2. Select 'edtika' database
# 3. Click 'Import' tab
# 4. Choose file: database/migrations/complete_ielts_platform_migration.sql
# 5. Click 'Go'
```

### Option 2: Laravel Migration (If converting to Laravel format)

```bash
# This SQL file can be wrapped in a Laravel migration
php artisan make:migration add_complete_ielts_platform_tables

# Copy SQL content to the up() method using DB::statement()
# Then run:
php artisan migrate
```

---

## Verification

After running the migration, verify tables were created:

```sql
-- Check all new tables exist
SHOW TABLES LIKE 'flashcard%';
SHOW TABLES LIKE 'dictionary%';
SHOW TABLES LIKE '%_kpis';
SHOW TABLES LIKE 'lead%';
SHOW TABLES LIKE 'chat_%';
SHOW TABLES LIKE 'leaderboard%';
SHOW TABLES LIKE '%streak%';
SHOW TABLES LIKE 'student_weekly_goals';
SHOW TABLES LIKE 'ielts_mock%';
SHOW TABLES LIKE 'mock_test%';

-- Count total tables (should be 291 + 22 = 313)
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'edtika';
```

---

## Foreign Key Dependencies

All foreign keys reference existing tables:
- ✅ `users` - Main user table
- ✅ `ielts_skills` - IELTS 4 skills
- ✅ `webinars` - Courses
- ✅ `quizzes` - Quiz system

**No additional setup needed** - all dependencies already exist!

---

## Next Steps After Migration

### 1. Create Laravel Models

```bash
php artisan make:model FlashcardDeck
php artisan make:model Flashcard
php artisan make:model DictionaryWord
php artisan make:model Lead
php artisan make:model ChatConversation
# ... etc
```

### 2. Seed Sample Data (Optional)

Create seeders for:
- Dictionary words (common IELTS vocabulary)
- Leaderboard configurations
- System default flashcard decks

### 3. Build Controllers

```bash
php artisan make:controller Panel/FlashcardController
php artisan make:controller Panel/DictionaryController
php artisan make:controller Panel/KPIController
php artisan make:controller Panel/LeadController
php artisan make:controller Panel/ChatController
```

### 4. Add Routes

Update `routes/panel.php` and `routes/web.php`

### 5. Create Views

Based on implementation plan requirements.

---

## Rollback (If Needed)

```sql
-- Drop all tables in reverse order (respects foreign keys)
DROP TABLE IF EXISTS `mock_test_sessions`;
DROP TABLE IF EXISTS `ielts_mock_tests`;
DROP TABLE IF EXISTS `student_weekly_goals`;
DROP TABLE IF EXISTS `streak_history`;
DROP TABLE IF EXISTS `user_activity_streaks`;
DROP TABLE IF EXISTS `leaderboard_entries`;
DROP TABLE IF EXISTS `leaderboards`;
DROP TABLE IF EXISTS `chat_messages`;
DROP TABLE IF EXISTS `chat_participants`;
DROP TABLE IF EXISTS `chat_conversations`;
DROP TABLE IF EXISTS `lead_activities`;
DROP TABLE IF EXISTS `leads`;
DROP TABLE IF EXISTS `sales_kpis`;
DROP TABLE IF EXISTS `admin_kpis`;
DROP TABLE IF EXISTS `teacher_kpis`;
DROP TABLE IF EXISTS `user_saved_words`;
DROP TABLE IF EXISTS `dictionary_word_translations`;
DROP TABLE IF EXISTS `dictionary_words`;
DROP TABLE IF EXISTS `flashcard_review_logs`;
DROP TABLE IF EXISTS `flashcards`;
DROP TABLE IF EXISTS `flashcard_deck_translations`;
DROP TABLE IF EXISTS `flashcard_decks`;
```

---

## Technical Details

### Conventions Followed:
- ✅ Primary key: `int(10) UNSIGNED`
- ✅ Timestamps: `bigint(20) UNSIGNED` (Unix timestamp)
- ✅ Engine: InnoDB
- ✅ Charset: utf8mb4_unicode_ci
- ✅ Foreign key naming: `{table}_{column}_foreign`
- ✅ Translation tables: `{table}_translations`

### Security Features:
- ✅ All user references with CASCADE delete
- ✅ IP tracking for sensitive operations
- ✅ Device info logging for security
- ✅ Proper indexing for performance

---

## Support

If you encounter issues:

1. **Check MySQL version**: Requires MySQL 5.7+ or MariaDB 10.2+
2. **Check permissions**: User must have CREATE, ALTER, INDEX privileges
3. **Check foreign keys**: Ensure `users`, `ielts_skills`, `webinars`, `quizzes` tables exist
4. **Check character set**: Database should be utf8mb4

---

## Database Size Impact

Estimated additional space needed:
- Tables structure: ~2 MB
- With moderate data (1000 users):
  - Flashcards: ~50 MB
  - Dictionary: ~100 MB (50k+ words)
  - Chat messages: ~200 MB
  - KPI/Analytics: ~10 MB
  - Other: ~20 MB

**Total:** ~380 MB additional storage

---

## Performance Recommendations

After migration:

```sql
-- Optimize tables
OPTIMIZE TABLE flashcard_decks, flashcards, dictionary_words;

-- Analyze for better query planning
ANALYZE TABLE chat_messages, leaderboard_entries, streak_history;

-- Check indexes are properly created
SHOW INDEX FROM flashcard_review_logs;
SHOW INDEX FROM user_saved_words;
```

---

## Compatibility

✅ Compatible with existing 291 tables
✅ No conflicts with current structure
✅ All foreign keys validated
✅ Follows exact naming conventions
✅ Ready for immediate use

---

**Created:** 2025-12-17
**Version:** 1.0
**Status:** Production Ready ✅
