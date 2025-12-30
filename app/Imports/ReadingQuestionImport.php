<?php

namespace App\Imports;

use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ReadingQuestionImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $bankType;
    protected $tempPath;
    protected $createdBy;
    
    public function __construct($bankType, $tempPath, $createdBy)
    {
        $this->bankType = $bankType;
        $this->tempPath = $tempPath;
        $this->createdBy = $createdBy;
    }
    
    public function model(array $row)
    {
        // Find or create group if Group Name is specified
        $groupId = null;
        if (!empty($row['group_name'])) {
            $group = \App\Models\IeltsQuestionGroup::firstOrCreate(
                [
                    'title' => $row['group_name'],
                    'bank_type' => $this->bankType,
                    'skill' => 'reading',
                ],
                [
                    'creator_id' => $this->createdBy,
                    'description' => 'Auto-created from import',
                    'passage' => $row['passage_text'] ?? '',
                    'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
                    'tags' => !empty($row['tags']) ? explode(',', $row['tags']) : [],
                ]
            );
            $groupId = $group->id;
        }
        
        // Upload image if specified
        $imagePath = null;
        if (!empty($row['image_file'])) {
            $imagePath = $this->uploadImageFile($row['image_file']);
        }
        
        // Parse options
        $options = [];
        foreach (['option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'option_f', 'option_g', 'option_h'] as $key) {
            if (!empty($row[$key])) {
                $options[str_replace('option_', '', $key)] = $row[$key];
            }
        }
        
        // Parse tags
        $tags = !empty($row['tags']) ? array_map('trim', explode(',', $row['tags'])) : [];
        
        // Determine model class
        $modelClass = $this->bankType === 'mock' 
            ? \App\Models\IeltsMockQuestionBank::class 
            : \App\Models\IeltsPracticeQuestionBank::class;
        
        return new $modelClass([
            'group_id' => $groupId,
            'creator_id' => $this->createdBy,
            'skill' => 'reading',
            'question_type' => $row['question_type'] ?? 'multiple_choice',
            'question_text' => $row['question_text'],
            'passage_text' => $row['passage_text'] ?? '',
            'image_file' => $imagePath,
            'correct_answer' => $row['correct_answer'],
            'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
            'options' => $options,
            'tags' => $tags,
            'instruction' => $row['instruction'] ?? '',
            'usage_count' => 0,
        ]);
    }
    
    protected function uploadImageFile($filename)
    {
        $sourcePath = $this->tempPath . '/images/' . $filename;
        
        if (!file_exists($sourcePath)) {
            throw new \Exception("Image file not found: {$filename}");
        }
        
        // Generate unique filename
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = 'reading_' . uniqid() . '.' . $extension;
        $destinationPath = 'question_bank/reading/images/' . date('Y/m') . '/' . $newFilename;
        
        Storage::disk('public')->put(
            $destinationPath,
            file_get_contents($sourcePath)
        );
        
        return $destinationPath;
    }
    
    protected function parseAnswerOptions($row)
    {
        if (in_array($row['question_type'], ['multiple_choice', 'matching_headings', 'matching_information'])) {
            $options = [];
            foreach (['option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'option_f', 'option_g', 'option_h'] as $key) {
                if (!empty($row[$key])) {
                    $options[] = $row[$key];
                }
            }
            return json_encode($options);
        }
        
        return null;
    }
    
    protected function parseTags($tagsString)
    {
        if (empty($tagsString)) {
            return null;
        }
        
        $tags = array_map('trim', explode(',', $tagsString));
        return json_encode(array_filter($tags));
    }
    
    public function rules(): array
    {
        return [
            'question_type' => 'required|string',
            'question_text' => 'required|string',
            'correct_answer' => 'required|string',
            'difficulty_level' => 'in:beginner,intermediate,advanced',
        ];
    }
}
