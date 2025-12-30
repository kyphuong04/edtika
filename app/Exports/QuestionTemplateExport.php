<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class QuestionTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $skill;
    protected $templateData;
    
    public function __construct($skill, $templateData)
    {
        $this->skill = $skill;
        $this->templateData = $templateData;
    }
    
    /**
     * Return array of sample data (3 rows for better example)
     */
    public function array(): array
    {
        return [
            $this->templateData['sample_row'],
            $this->getSecondSampleRow(),
            $this->getThirdSampleRow(),
        ];
    }
    
    /**
     * Return headers
     */
    public function headings(): array
    {
        return $this->templateData['headers'];
    }
    
    /**
     * Get second sample row based on skill
     */
    protected function getSecondSampleRow()
    {
        $samples = [
            'listening' => ['Part 1 - Hotel Conversation', 2, 'fill_blank', 'The meeting is at _____ PM', 'Part1.mp3', '00:30', '00:45', '3', 'beginner', 'time, numbers', 'Write NO MORE THAN TWO WORDS', '', '', '', '', ''],
            'reading' => ['Climate Change Passage', 2, 'true_false_not_given', 'Renewable energy is expensive', 'Long passage here...', '', 'Not Given', 'advanced', 'inference', 'Do statements agree?', 'True', 'False', 'Not Given', ''],
            'writing' => ['Academic Writing Set 1', 2, 'Task 2', 'Technology has made life more complicated. Discuss.', '', 'Model answer here...', 10.0, 'advanced', 'opinion, technology', 'Write at least 250 words'],
            'speaking' => ['Speaking Part 1 - Hometown', 2, 'Part 1', 'Where do you live?', '', 'Sample answer here...', 3.0, 'beginner', 'description, location', 'Answer the question'],
        ];
        
        return $samples[$this->skill] ?? $this->templateData['sample_row'];
    }
    
    /**
     * Get third sample row
     */
    protected function getThirdSampleRow()
    {
        $samples = [
            'listening' => ['Part 2 - University Tour', 3, 'matching_information', 'Which building has the library?', 'Part2.mp3', '01:00', '01:30', 'Building C', 'intermediate', 'matching, details', 'Match each facility', 'Building A', 'Building B', 'Building C', 'Building D'],
            'reading' => ['Technology and Education', 3, 'summary_completion', 'AI provides _____ learning', 'Passage about AI...', '', 'personalized', 'beginner', 'completion', 'Complete the summary', '', '', '', ''],
            'writing' => ['General Training Set 1', 3, 'Task 1', 'Write a letter of complaint', '', 'Model letter...', 5.0, 'intermediate', 'letter, complaint', 'Write at least 150 words'],
            'speaking' => ['Speaking Part 2 - Cue Cards', 3, 'Part 2', 'Describe a memorable trip', 'cuecard.png', 'Discussion sample...', 5.0, 'intermediate', 'description, travel', 'Speak for 1-2 minutes'],
        ];
        
        return $samples[$this->skill] ?? $this->templateData['sample_row'];
    }
    
    /**
     * Style the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style first row (headers) - Blue background with white text
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
            ],
            // Sample rows styling
            '2:4' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2']
                ],
            ],
        ];
    }
    
    /**
     * Set column widths for better readability
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12,  // Question #
            'B' => 20,  // Question Type
            'C' => 40,  // Question Text
            'D' => 50,  // Passage/Audio
            'E' => 15,  // Timestamp/Image
            'F' => 15,  // Timestamp End
            'G' => 25,  // Correct Answer
            'H' => 15,  // Difficulty
            'I' => 20,  // Tags
            'J' => 30,  // Instruction
            'K' => 15,  // Option A
            'L' => 15,  // Option B
            'M' => 15,  // Option C
            'N' => 15,  // Option D
        ];
    }
}
