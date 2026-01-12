# Dictionary & Flashcard Feature

## Overview
This feature integrates Cambridge Dictionary API to provide English-Vietnamese dictionary functionality with flashcard management for all user roles.

## Features
- **Dictionary Search**: Search English-Vietnamese translations using Cambridge Dictionary API
- **Word Pronunciation**: Audio pronunciation support
- **Related Words**: Display nearby/related entries
- **Spell Checking**: "Did you mean?" suggestions for misspelled words
- **Flashcard Management**: Save words to personal flashcards for later review
- **Flashcard Study**: Interactive flip cards to practice vocabulary
- **Multi-Role Support**: Available for Admin, Teacher, Organization, Student, and User roles

## Installation

### 1. Database Migration
Run the migrations to create the flashcards table and add permissions:

```bash
php artisan migrate
```

This will:
- Create the `flashcards` table
- Add `admin_dictionary` permission to the Education section
- Assign permission to Admin, Manager, Teacher, and Organization roles

### 2. Cambridge Dictionary API Setup

#### Get API Access Key
1. Visit [Cambridge Dictionary API](https://dictionary.cambridge.org/api/)
2. Sign up for an API account
3. Get your API access key

#### Configure API Key
Add the following to your `.env` file:

```env
CAMBRIDGE_DICT_ACCESS_KEY=your_api_key_here
```

Replace `your_api_key_here` with your actual Cambridge Dictionary API access key.

### 3. Clear Cache
After adding the API key, clear the application cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Usage

### Admin Panel
- Navigate to: **Admin Panel > Education > Dictionary & Flashcard**
- Access URL: `/admin/dictionary`
- Search for words and view definitions

### User Panel
- Navigate to: **Panel > Education > Dictionary & Flashcard**
- Access URLs:
  - Dictionary: `/panel/dictionary`
  - My Flashcards: `/panel/dictionary/flashcards`

### Features in Panel:
1. **Search Words**:
   - Type a word in the search box
   - Get instant suggestions as you type
   - View full definition, pronunciation, and examples

2. **Save to Flashcard**:
   - Click "Save to Flashcard" button on any word
   - Word is saved to your personal collection

3. **Study Flashcards**:
   - View all saved flashcards in grid layout
   - Click on a card to flip and see the definition
   - Delete unwanted flashcards

## File Structure

### Controllers
- `app/Http/Controllers/Admin/DictionaryController.php` - Admin dictionary controller
- `app/Http/Controllers/Panel/DictionaryController.php` - User panel dictionary controller

### Models
- `app/Models/Flashcard.php` - Flashcard model

### Views
- `resources/views/admin/dictionary/index.blade.php` - Admin dictionary view
- `resources/views/design_1/panel/dictionary/index.blade.php` - Panel dictionary view
- `resources/views/design_1/panel/dictionary/flashcards.blade.php` - Flashcards management view

### Routes
- `routes/admin.php` - Admin dictionary routes
- `routes/panel.php` - Panel dictionary and flashcard routes

### Migrations
- `database/migrations/2025_01_12_000001_create_flashcards_table.php` - Flashcards table
- `database/migrations/2025_01_12_000002_add_dictionary_permissions.php` - Permissions

### Sidebar Integration
- `app/Mixins/Panel/SidebarItems.php` - Added Dictionary menu to Education section
- `resources/views/admin/includes/sidebar/education.blade.php` - Admin sidebar
- `resources/views/design_1/panel/includes/sidebar/icons.blade.php` - Panel icons

### Translations
- `lang/en/admin/main.php` - Admin translations
- `lang/en/panel.php` - Panel translations

## API Endpoints

### Admin Routes (`/admin/dictionary`)
- `GET /` - Dictionary index page
- `GET /dictionaries` - Get available dictionaries
- `GET /search` - Search for words
- `GET /search-first` - Get best matching entry
- `GET /did-you-mean` - Get spell suggestions
- `GET /nearby-entries` - Get related words
- `GET /entry` - Get entry by ID

### Panel Routes (`/panel/dictionary`)
- `GET /` - Dictionary index page
- `GET /dictionaries` - Get available dictionaries
- `GET /search` - Search for words
- `GET /search-first` - Get best matching entry
- `GET /did-you-mean` - Get spell suggestions
- `GET /nearby-entries` - Get related words
- `GET /entry` - Get entry by ID
- `GET /flashcards` - View all flashcards
- `POST /flashcards/save` - Save word to flashcard
- `DELETE /flashcards/{id}` - Delete flashcard

## Database Schema

### flashcards Table
```sql
CREATE TABLE `flashcards` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `word` varchar(255) NOT NULL,
  `pronunciation` varchar(255) DEFAULT NULL,
  `definition` text NOT NULL,
  `example` text DEFAULT NULL,
  `translation` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flashcards_user_id_index` (`user_id`),
  CONSTRAINT `flashcards_user_id_foreign` FOREIGN KEY (`user_id`) 
    REFERENCES `users` (`id`) ON DELETE CASCADE
);
```

## Permissions

### Admin Permission
- **Permission Name**: `admin_dictionary`
- **Section**: Education
- **Caption**: Dictionary & Flashcard
- **Assigned To**: Admin, Manager, Teacher, Organization roles

### Panel Access
- All authenticated users can access the panel dictionary
- No special permission required for panel access

## Components Used

### Icons (Iconsax)
- `book` - Dictionary icon
- `bookmark-2` - Flashcard icon
- `search-normal` - Search icon
- `volume-high` - Audio icon
- `save-2` - Save icon
- `trash` - Delete icon

### UI Components
- Bootstrap 4/5 grid system
- Card components
- Form inputs
- Buttons
- Modal dialogs (SweetAlert2)
- Pagination

## Customization

### Change Dictionary Language
To change from English-Vietnamese to another language pair:

1. Edit the dictionary code in controllers:
```php
$dictionaryCode = 'english-vietnamese'; // Change to your desired language pair
```

2. Available dictionary codes can be retrieved from:
```php
$api->getDictionaries()
```

### Styling
The UI uses CSS variables for theming. Main colors:
- `--primary`: Primary color
- `--primary-dark`: Darker variant
- `--primary-rgb`: RGB values for transparency

Customize in the `<style>` sections of the blade files.

## Troubleshooting

### API Not Working
1. Check if `CAMBRIDGE_DICT_ACCESS_KEY` is set in `.env`
2. Verify API key is valid
3. Check API rate limits
4. Review Laravel logs: `storage/logs/laravel.log`

### Flashcards Not Saving
1. Check database connection
2. Verify `flashcards` table exists
3. Check user authentication
4. Review browser console for JavaScript errors

### Permissions Issues
1. Run migration: `php artisan migrate`
2. Clear cache: `php artisan cache:clear`
3. Check role assignments in database
4. Verify user has appropriate role

## Credits
- Cambridge Dictionary API for word definitions
- Iconsax for icons
- Laravel Framework
- Bootstrap for UI components
