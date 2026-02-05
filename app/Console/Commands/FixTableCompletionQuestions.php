<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IeltsTestQuestion;

class FixTableCompletionQuestions extends Command
{
    protected $signature = 'fix:table-completion';
    protected $description = 'Fix table completion questions with missing table_structure';

    public function handle()
    {
        $this->info('🔧 Fixing table completion questions...');
        
        // Table structure based on your screenshot
        $tableStructure = [
            'headers' => ['Origin:', 'Word \'Viking\' is', 'Vikings came from...'],
            'rows' => [
                ['Dates of the Viking Age', 'In Britain: AD [1] abs', 'Length varies elsewhere'],
                ['Territorial extent:', 'In doubtfdff [2]', 'check'],
            ],
            'num_cols' => 3,
            'num_rows' => 2
        ];

        // Find questions 1 and 2
        $questions = IeltsTestQuestion::whereIn('question_number', [1, 2])
            ->where('question_type', 'table_completion')
            ->orderBy('question_number')
            ->get();

        $this->info("Found {$questions->count()} questions");

        foreach ($questions as $question) {
            $this->line("\nUpdating Question #{$question->question_number} (ID: {$question->id})");
            $this->line("Current question_data: " . ($question->question_data ? json_encode($question->question_data) : 'null'));
            $this->line("Current table_structure: " . ($question->table_structure ? json_encode($question->table_structure) : 'null'));
            
            // Update with table structure
            $question->table_structure = $tableStructure;
            $question->save();
            
            $this->info("✅ Updated!");
        }

        $this->info("\n✨ Done! Refresh your test page to see the table.");
        
        return 0;
    }
}
