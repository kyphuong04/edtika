# IELTS Test Creation System - Technical Integration Guide

## 📋 Component Integration Steps

### 1. Update IeltsTestController

Add imports and update methods to use the enhanced views and service:

```php
// app/Http/Controllers/Admin/IeltsTestController.php

use App\Services\IeltsTestCreationService;
use Illuminate\Support\Str;

class IeltsTestController extends Controller
{
    protected $testService;

    public function __construct(IeltsTestCreationService $testService)
    {
        $this->testService = $testService;
    }

    /**
     * Use enhanced sections view
     */
    public function manageSections($testId)
    {
        $test = IeltsTest::with('sections.questionGroups.questions')
            ->findOrFail($testId);

        return view('admin.ielts_tests.sections_enhanced', [
            'test' => $test,
        ]);
    }

    /**
     * Use enhanced question groups view
     */
    public function manageQuestionGroups($sectionId)
    {
        $section = IeltsTestSection::with('questionGroups.questions')
            ->findOrFail($sectionId);

        $questionGroups = $section->questionGroups()
            ->with('questions')
            ->get();

        return view('admin.ielts_tests.question_groups_enhanced', [
            'section' => $section,
            'questionGroups' => $questionGroups,
        ]);
    }

    /**
     * Store question group with media upload
     */
    public function storeQuestionGroup(Request $request, $sectionId)
    {
        $section = IeltsTestSection::findOrFail($sectionId);

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'question_type' => 'required|string',
            'question_start' => 'required|integer|min:1',
            'question_end' => 'required|integer|gte:question_start',
            'max_words' => 'nullable|integer',
            'target_band' => 'nullable|numeric',
            'instructions' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a',
            'task_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,webm',
            'passage' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('audio_file')) {
            $validated['audio_file'] = $request->file('audio_file')
                ->store('ielts/audio', 'public');
        }

        if ($request->hasFile('task_image')) {
            $validated['task_image'] = $request->file('task_image')
                ->store('ielts/images', 'public');
        }

        if ($request->hasFile('video_file')) {
            $validated['video_file'] = $request->file('video_file')
                ->store('ielts/videos', 'public');
        }

        $group = $this->testService->addQuestionGroup($section, $validated);

        return redirect()
            ->route('admin.ielts_tests.question_groups', $sectionId)
            ->with('success', 'Question group created successfully!');
    }

    /**
     * Updated wizard store to use service
     */
    public function wizardStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'type' => 'required|in:mock,practice',
            'format' => 'required|in:academic,general',
            'difficulty_level' => 'nullable|in:beginner,intermediate,advanced',
            'target_band_min' => 'nullable|numeric',
            'target_band_max' => 'nullable|numeric',
            'description' => 'nullable|string',
            'practice_skill' => 'nullable|in:listening,reading,writing,speaking',
            'skill_duration' => 'nullable|integer',
            'practice_mode' => 'nullable|in:untimed,timed,exam',
            'show_answers_immediately' => 'nullable|boolean',
            'allow_retake' => 'nullable|boolean',
        ]);

        if ($validated['type'] === 'mock') {
            $test = $this->testService->createMockTest($validated);
        } else {
            $test = $this->testService->createPracticeTest($validated);
        }

        $firstSection = $test->sections()->first();

        return redirect()
            ->route('admin.ielts_tests.question_groups', $firstSection->id)
            ->with('success', 'Test created! Now add your question groups and questions.');
    }
}
```

### 2. Create Form Request Class

```php
// app/Http/Requests/StoreQuestionGroupRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionGroupRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:500',
            'description' => 'nullable|string',
            'question_type' => 'required|in:multiple_choice,matching,fill_blanks,table_completion,true_false_not_given,short_answer,flowchart,diagram_labeling,multiple_select,essay_writing',
            'question_start' => 'required|integer|min:1',
            'question_end' => 'required|integer|gte:question_start',
            'max_words' => 'nullable|integer',
            'target_band' => 'nullable|numeric|between:4,9',
            'instructions' => 'nullable|string',
            'audio_file' => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:50000',
            'task_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10000',
            'video_file' => 'nullable|file|mimes:mp4,avi,mov,webm|max:200000',
            'passage' => 'nullable|string',
        ];
    }
}
```

### 3. Add Routes (if not already present)

```php
// routes/admin.php

Route::group(['prefix' => 'ielts-tests'], function () {
    // Existing routes...

    // Enhanced views
    Route::get('/{testId}/sections-enhanced', 'IeltsTestController@manageSections')
        ->name('admin.ielts_tests.sections_enhanced');

    Route::get('/sections/{sectionId}/question-groups-enhanced', 'IeltsTestController@manageQuestionGroups')
        ->name('admin.ielts_tests.question_groups_enhanced');

    // Media handling
    Route::post('/{testId}/media/upload', 'IeltsTestController@uploadMedia')
        ->name('admin.ielts_tests.media.upload');

    Route::delete('/{testId}/media/{type}/{filename}', 'IeltsTestController@deleteMedia')
        ->name('admin.ielts_tests.media.delete');
});
```

### 4. Update Navigation Menu

In your admin layout or navigation file, add links to use enhanced views:

```blade
<li>
    <a href="{{ route('admin.ielts_tests.index') }}">
        <i class="fas fa-graduation-cap"></i>
        <span>IELTS Tests</span>
    </a>
    <ul class="submenu">
        <li><a href="{{ route('admin.ielts_tests.wizard') }}">Create (Wizard)</a></li>
        <li><a href="{{ route('admin.ielts_tests.index') }}">All Tests</a></li>
    </ul>
</li>
```

### 5. Update View Includes

In your main dashboard/admin layout, reference the new components:

```blade
@include('admin.ielts_tests.components.question_group_form', [
    'action' => route('admin.ielts_tests.question_groups.store', $section->id),
    'section' => $section
])
```

### 6. Add Helper Functions (Optional)

```php
// app/Helpers/IeltsTestHelper.php

class IeltsTestHelper
{
    public static function getQuestionTypeLabel($type)
    {
        $labels = [
            'multiple_choice' => 'Multiple Choice',
            'matching' => 'Matching',
            'fill_blanks' => 'Fill in the Blanks',
            'table_completion' => 'Table Completion',
            'true_false_not_given' => 'True/False/Not Given',
            'short_answer' => 'Short Answer',
            'flowchart' => 'Flowchart Completion',
            'diagram_labeling' => 'Diagram Labeling',
            'multiple_select' => 'Multiple Select',
            'essay_writing' => 'Essay Writing',
        ];

        return $labels[$type] ?? $type;
    }

    public static function getSkillIcon($skill)
    {
        $icons = [
            'listening' => 'fa-headphones',
            'reading' => 'fa-book-open',
            'writing' => 'fa-pencil-alt',
            'speaking' => 'fa-microphone',
        ];

        return $icons[$skill] ?? 'fa-circle';
    }

    public static function getSkillColor($skill)
    {
        $colors = [
            'listening' => 'info',
            'reading' => 'success',
            'writing' => 'warning',
            'speaking' => 'danger',
        ];

        return $colors[$skill] ?? 'secondary';
    }
}
```

### 7. Add AJAX Endpoints (Optional)

For smooth form loading in modals:

```php
public function createQuestionGroupForm($sectionId)
{
    $section = IeltsTestSection::findOrFail($sectionId);
    
    return view('admin.ielts_tests.components.question_group_form', [
        'action' => route('admin.ielts_tests.question_groups.store', $sectionId),
        'section' => $section,
        'method' => 'POST',
    ]);
}
```

### 8. Update Blade Templates

Add this to `resources/views/admin/layouts/app.blade.php` if not already present:

```blade
@push('styles_bottom')
<style>
.bg-gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.bg-gradient-info { background: linear-gradient(135deg, #17a2b8 0%, #0c5460 100%); }
.bg-gradient-success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); }
.bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #bd2130 100%); }
</style>
@endpush

@push('scripts_bottom')
<script>
// Add any global scripts here
</script>
@endpush
```

---

## 📦 Database Considerations

### Ensure Migrations Are Run

```bash
php artisan migrate
```

The following tables should exist:
- `ielts_tests`
- `ielts_test_sections`
- `ielts_question_groups`
- `ielts_test_questions`
- `ielts_test_answers`
- `ielts_test_attempts`

### Verify Columns Exist

Check that `ielts_question_groups` has these columns:
- `audio_file`
- `audio_path`
- `task_image`
- `video_file`
- `passage`
- `transcript`
- `instructions`
- `max_words`
- `target_band`

### Add Missing Columns (if needed)

```php
// Create migration if needed
php artisan make:migration add_media_fields_to_ielts_question_groups
```

---

## 🧪 Testing

### Test Question Group Creation

```php
// tests/Feature/IeltsTestCreationTest.php

public function test_can_create_question_group_with_media()
{
    $test = IeltsTest::factory()->create();
    $section = $test->sections()->first();
    
    $response = $this->post(route('admin.ielts_tests.question_groups.store', $section->id), [
        'title' => 'Test Question Group',
        'question_type' => 'table_completion',
        'question_start' => 1,
        'question_end' => 5,
        'max_words' => 3,
        'audio_file' => UploadedFile::fake()->create('audio.mp3'),
    ]);

    $this->assertDatabaseHas('ielts_question_groups', [
        'title' => 'Test Question Group',
        'section_id' => $section->id,
    ]);
}
```

---

## 🚀 Deployment

1. **Run migrations**: `php artisan migrate`
2. **Clear cache**: `php artisan cache:clear`
3. **Update storage links**: `php artisan storage:link`
4. **Test wizard creation**: Create a test through the wizard UI
5. **Verify views render**: Check sections and question groups pages

---

## 📝 Customization

### Change Media Upload Path

In controller:
```php
$path = $request->file('audio_file')
    ->store('your/custom/path', 'disk-name');
```

### Add More Question Types

Update validation and helpers:
```php
'question_type' => 'required|in:...your_types',
```

### Customize Progress Calculation

In service:
```php
public function getProgressPercentage(IeltsTestSection $section)
{
    // Your custom logic
}
```

---

**Last Updated**: May 2026
