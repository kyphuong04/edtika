<?php

namespace App\Imports;

use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SpeakingQuestionImport implements ToModel, WithHeadingRow, WithValidation
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
        // Upload cue card image if specified (for Part 2)
        $imagePath = null;
        if (!empty($row['cue_card_image'])) {
            $imagePath = $this->uploadImageFile($row['cue_card_image']);
        }
        
        $data = [
            'skill' => 'speaking',
            'section_type' => $row['part'] ?? null, // Part 1, 2, or 3
            'question_type' => 'speaking_prompt',
            'question_text' => $row['question_text'],
            'instruction' => $row['instruction'] ?? null,
            'image_file' => $imagePath,
            'correct_answer' => $row['sample_answer'], // Sample answer for speaking
            'explanation' => $row['explanation'] ?? null,
            'points' => $row['points'] ?? 5.0,
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
            throw new \Exception("Cue card image not found: {$filename}");
        }
        
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = 'speaking_' . uniqid() . '.' . $extension;
        $destinationPath = 'question_bank/speaking/images/' . date('Y/m') . '/' . $newFilename;
        
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
            'question_text' => 'required|string',
            'sample_answer' => 'required|string',
            'part' => 'in:Part 1,Part 2,Part 3',
            'difficulty_level' => 'in:beginner,intermediate,advanced',
        ];
    }
}
