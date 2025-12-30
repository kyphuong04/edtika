<?php

namespace App\Imports;

use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class WritingQuestionImport implements ToModel, WithHeadingRow, WithValidation
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
        // Upload task image file if specified (for Task 1 graphs/charts)
        $imagePath = null;
        if (!empty($row['task_image'])) {
            $imagePath = $this->uploadImageFile($row['task_image']);
        }
        
        $data = [
            'skill' => 'writing',
            'section_type' => $row['task_type'] ?? null, // Task 1 or Task 2
            'question_type' => $row['question_type'] ?? 'essay',
            'question_text' => $row['question_text'],
            'instruction' => $row['instruction'] ?? null,
            'image_file' => $imagePath,
            'correct_answer' => $row['sample_answer'] ?? $row['model_answer'], // Model answer for writing
            'explanation' => $row['explanation'] ?? null,
            'points' => $row['points'] ?? 10.0,
            'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
            'tags' => $this->parseTags($row['tags'] ?? null),
            'created_by' => $this->createdBy,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        
        // Add practice-specific fields
        if ($this->bankType === 'practice') {
            $data['practice_focus'] = $row['practice_focus'] ?? null;
            $data['target_band'] = $row['target_band'] ?? null;
        }
        
        $model = $this->bankType === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
            
        return new $model($data);
    }
    
    protected function uploadImageFile($filename)
    {
        $sourcePath = $this->tempPath . '/images/' . $filename;
        
        if (!file_exists($sourcePath)) {
            throw new \Exception("Task image not found: {$filename}");
        }
        
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = 'writing_' . uniqid() . '.' . $extension;
        $destinationPath = 'question_bank/writing/images/' . date('Y/m') . '/' . $newFilename;
        
        Storage::disk('public')->put(
            $destinationPath,
            file_get_contents($sourcePath)
        );
        
        return $destinationPath;
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
            'question_type' => 'in:essay,graph_description,letter_writing,report_writing',
            'question_text' => 'required|string',
            'sample_answer' => 'required|string',
            'difficulty_level' => 'in:beginner,intermediate,advanced',
        ];
    }
}
