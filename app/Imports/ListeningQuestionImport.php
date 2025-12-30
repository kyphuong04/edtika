<?php

namespace App\Imports;

use App\Models\IeltsMockQuestionBank;
use App\Models\IeltsPracticeQuestionBank;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ListeningQuestionImport implements ToModel, WithHeadingRow, WithValidation
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
    
    /**
     * Create model from Excel row
     */
    public function model(array $row)
    {
        // Upload audio file if specified
        $audioPath = null;
        if (!empty($row['audio_file'])) {
            $audioPath = $this->uploadAudioFile($row['audio_file']);
        }
        
        $data = [
            'skill' => 'listening',
            'section_type' => $row['section_type'] ?? null,
            'question_type' => $row['question_type'],
            'question_text' => $row['question_text'],
            'instruction' => $row['instruction'] ?? null,
            'passage_text' => $row['passage_text'] ?? null,
            'audio_file' => $audioPath,
            'timestamp_start' => $row['timestamp_start'] ?? null,
            'timestamp_end' => $row['timestamp_end'] ?? null,
            'answer_options' => $this->parseAnswerOptions($row),
            'correct_answer' => $row['correct_answer'],
            'explanation' => $row['explanation'] ?? null,
            'points' => $row['points'] ?? 1.0,
            'difficulty_level' => $row['difficulty_level'] ?? 'intermediate',
            'tags' => $this->parseTags($row['tags'] ?? null),
            'created_by' => $this->createdBy,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        
        $model = $this->bankType === 'mock' 
            ? IeltsMockQuestionBank::class 
            : IeltsPracticeQuestionBank::class;
            
        return new $model($data);
    }
    
    /**
     * Upload audio file from extracted ZIP to storage
     */
    protected function uploadAudioFile($filename)
    {
        $sourcePath = $this->tempPath . '/audio/' . $filename;
        
        if (!file_exists($sourcePath)) {
            throw new \Exception("Audio file not found: {$filename}");
        }
        
        // Generate unique filename
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = 'listening_' . uniqid() . '.' . $extension;
        $destinationPath = 'question_bank/listening/audio/' . date('Y/m') . '/' . $newFilename;
        
        // Upload to storage
        Storage::disk('public')->put(
            $destinationPath,
            file_get_contents($sourcePath)
        );
        
        return $destinationPath;
    }
    
    /**
     * Parse answer options from Excel
     */
    protected function parseAnswerOptions($row)
    {
        // If it's multiple choice, get options from columns
        if (in_array($row['question_type'], ['multiple_choice', 'matching_information'])) {
            $options = [];
            foreach (['option_a', 'option_b', 'option_c', 'option_d', 'option_e'] as $key) {
                if (!empty($row[$key])) {
                    $options[] = $row[$key];
                }
            }
            return json_encode($options);
        }
        
        return null;
    }
    
    /**
     * Parse tags from comma-separated string
     */
    protected function parseTags($tagsString)
    {
        if (empty($tagsString)) {
            return null;
        }
        
        $tags = array_map('trim', explode(',', $tagsString));
        return json_encode(array_filter($tags));
    }
    
    /**
     * Validation rules
     */
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
