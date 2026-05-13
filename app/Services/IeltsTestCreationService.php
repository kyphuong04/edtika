<?php

namespace App\Services;

use App\Models\IeltsTest;
use App\Models\IeltsTestSection;
use App\Models\IeltsQuestionGroup;
use App\Models\IeltsTestQuestion;
use Illuminate\Support\Str;

/**
 * IELTS Test Creation Service
 * Handles the hierarchical creation of tests with sections, parts, and questions
 */
class IeltsTestCreationService
{
    /**
     * Create a complete Mock Test with all sections
     * 
     * @param array $data
     * @return IeltsTest
     */
    public function createMockTest(array $data): IeltsTest
    {
        $test = $this->createTestRecord($data, 'mock');
        $this->createMockSections($test);
        
        return $test->fresh(['sections']);
    }

    /**
     * Create a Practice Test for a single skill
     * 
     * @param array $data
     * @return IeltsTest
     */
    public function createPracticeTest(array $data): IeltsTest
    {
        $test = $this->createTestRecord($data, 'practice');
        
        $skill = $data['practice_skill'] ?? 'reading';
        $duration = $data['skill_duration'] ?? $this->getDefaultDuration($skill);
        
        $this->createPracticeSection($test, $skill, $duration);
        
        return $test->fresh(['sections']);
    }

    /**
     * Create base test record
     */
    private function createTestRecord(array $data, string $type): IeltsTest
    {
        $slug = $this->generateUniqueSlug($data['title']);
        
        $testData = [
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'type' => $type,
            'format' => $data['format'] ?? 'academic',
            'difficulty_level' => $data['difficulty_level'] ?? 'intermediate',
            'target_band_min' => $data['target_band_min'] ?? null,
            'target_band_max' => $data['target_band_max'] ?? null,
            'created_at' => time(),
            'creator_id' => auth()->id(),
        ];

        if ($type === 'practice') {
            $testData['practice_skill'] = $data['practice_skill'] ?? 'reading';
            $testData['practice_mode'] = $data['practice_mode'] ?? 'untimed';
            $testData['show_answers_immediately'] = $data['show_answers_immediately'] ?? 1;
            $testData['allow_retake'] = $data['allow_retake'] ?? 1;
        }

        return IeltsTest::create($testData);
    }

    /**
     * Create all Mock Test sections (Listening, Reading, Writing, Speaking)
     */
    private function createMockSections(IeltsTest $test): void
    {
        $sections = [
            [
                'skill' => 'listening',
                'title' => 'Listening',
                'description' => 'IELTS Listening Section - 30 minutes',
                'question_start' => 1,
                'question_end' => 40,
                'duration_minutes' => 30,
                'sort_order' => 1,
            ],
            [
                'skill' => 'reading',
                'title' => 'Reading',
                'description' => 'IELTS Reading Section - 60 minutes',
                'question_start' => 41,
                'question_end' => 80,
                'duration_minutes' => 60,
                'sort_order' => 2,
            ],
            [
                'skill' => 'writing',
                'title' => 'Writing',
                'description' => 'IELTS Writing Section - 60 minutes (2 tasks)',
                'question_start' => 81,
                'question_end' => 82,
                'duration_minutes' => 60,
                'sort_order' => 3,
            ],
            [
                'skill' => 'speaking',
                'title' => 'Speaking',
                'description' => 'IELTS Speaking Section - 11-14 minutes',
                'question_start' => 83,
                'question_end' => 100,
                'duration_minutes' => 15,
                'sort_order' => 4,
            ],
        ];

        foreach ($sections as $sectionData) {
            $sectionData['test_id'] = $test->id;
            IeltsTestSection::create($sectionData);
        }
    }

    /**
     * Create a single Practice Test section
     */
    private function createPracticeSection(IeltsTest $test, string $skill, int $duration): IeltsTestSection
    {
        $config = [
            'listening' => [
                'title' => 'Listening Practice',
                'description' => 'IELTS Listening Practice - 30 minutes',
                'questions' => [1, 40],
            ],
            'reading' => [
                'title' => 'Reading Practice',
                'description' => 'IELTS Reading Practice - 60 minutes',
                'questions' => [1, 40],
            ],
            'writing' => [
                'title' => 'Writing Practice',
                'description' => 'IELTS Writing Practice - 60 minutes',
                'questions' => [1, 2],
            ],
            'speaking' => [
                'title' => 'Speaking Practice',
                'description' => 'IELTS Speaking Practice - 11-14 minutes',
                'questions' => [1, 20],
            ],
        ];

        $config = $config[$skill] ?? $config['reading'];

        return IeltsTestSection::create([
            'test_id' => $test->id,
            'skill' => $skill,
            'title' => $config['title'],
            'description' => $config['description'],
            'question_start' => $config['questions'][0],
            'question_end' => $config['questions'][1],
            'duration_minutes' => $duration,
            'sort_order' => 1,
        ]);
    }

    /**
     * Add a Question Group (Part) to a section
     */
    public function addQuestionGroup(
        IeltsTestSection $section,
        array $groupData
    ): IeltsQuestionGroup
    {
        $groupData['section_id'] = $section->id;
        $groupData['creator_id'] = auth()->id();
        $groupData['skill'] = $section->skill;

        return IeltsQuestionGroup::create($groupData);
    }

    /**
     * Update an existing Question Group
     */
    public function updateQuestionGroup(
        IeltsQuestionGroup $group,
        array $data
    ): IeltsQuestionGroup
    {
        $group->update($data);
        return $group->fresh();
    }

    /**
     * Add questions to a question group
     */
    public function addQuestionsToGroup(
        IeltsQuestionGroup $group,
        array $questions
    ): void
    {
        foreach ($questions as $index => $questionData) {
            $questionData['section_id'] = $group->section_id;
            $questionData['question_group_id'] = $group->id;
            $questionData['question_number'] = $questionData['question_number'] ?? ($group->question_start + $index);
            $questionData['question_order'] = $index + 1;
            $questionData['created_at'] = time();

            IeltsTestQuestion::create($questionData);
        }
    }

    /**
     * Generate unique slug for test
     */
    private function generateUniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = 1;

        while (IeltsTest::where('slug', $slug)->exists()) {
            $slug = Str::slug($title) . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get default duration for a skill
     */
    private function getDefaultDuration(string $skill): int
    {
        return match ($skill) {
            'listening' => 30,
            'reading' => 60,
            'writing' => 60,
            'speaking' => 15,
            default => 30,
        };
    }

    /**
     * Clone an existing test with all sections and groups
     */
    public function cloneTest(IeltsTest $sourceTest, string $newTitle): IeltsTest
    {
        $newTest = $sourceTest->replicate();
        $newTest->title = $newTitle;
        $newTest->slug = $this->generateUniqueSlug($newTitle);
        $newTest->created_at = time();
        $newTest->submitted_for_approval_at = null;
        $newTest->approved_at = null;
        $newTest->status = 'draft';
        $newTest->save();

        // Clone sections
        foreach ($sourceTest->sections as $section) {
            $newSection = $section->replicate();
            $newSection->test_id = $newTest->id;
            $newSection->created_at = time();
            $newSection->save();

            // Clone question groups
            foreach ($section->questionGroups as $group) {
                $newGroup = $group->replicate();
                $newGroup->section_id = $newSection->id;
                $newGroup->created_at = time();
                $newGroup->save();

                // Clone questions
                foreach ($group->questions as $question) {
                    $newQuestion = $question->replicate();
                    $newQuestion->section_id = $newSection->id;
                    $newQuestion->question_group_id = $newGroup->id;
                    $newQuestion->created_at = time();
                    $newQuestion->save();
                }
            }
        }

        return $newTest;
    }

    /**
     * Validate test structure completeness
     */
    public function validateTestStructure(IeltsTest $test): array
    {
        $errors = [];

        if ($test->type === 'mock') {
            // Check all 4 skills present
            $skills = ['listening', 'reading', 'writing', 'speaking'];
            foreach ($skills as $skill) {
                if (!$test->sections()->where('skill', $skill)->exists()) {
                    $errors[] = "Missing {$skill} section";
                }
            }
        }

        // Check sections have question groups
        foreach ($test->sections as $section) {
            $totalQuestions = $section->question_end - $section->question_start + 1;
            $addedQuestions = $section->questions()->count();
            
            if ($addedQuestions < $totalQuestions) {
                $errors[] = "{$section->skill}: Only {$addedQuestions}/{$totalQuestions} questions added";
            }
        }

        return $errors;
    }
}
