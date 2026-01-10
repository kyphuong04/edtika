<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixQuestionTypes extends Command
{
    protected $signature = 'ielts:fix-question-types';
    protected $description = 'Fix question types from bank format to valid ENUM format in ielts_test_questions';

    public function handle()
    {
        $this->info('Fixing question types...');
        
        // Mapping from Question Bank types to valid ENUM values
        $mapping = [
            'multiple_choice_single' => 'multiple_choice',
            'multiple_choice_multiple' => 'multiple_select',
            'mcq' => 'multiple_choice',
            'mcq_single' => 'multiple_choice',
            'mcq_multiple' => 'multiple_select',
            'choose_two' => 'multiple_select',
            'choose_three' => 'multiple_select',
            'true_false_not_given' => 'true_false_ng',
            'tfng' => 'true_false_ng',
            'yes_no_not_given' => 'yes_no_ng',
            'ynng' => 'yes_no_ng',
            'matching_headings' => 'matching',
            'matching_information' => 'matching',
            'matching_features' => 'matching',
            'matching_sentence_endings' => 'sentence_completion',
            'form_completion' => 'fill_blank',
            'flow_chart_completion' => 'flow_chart',
            'diagram_labeling' => 'diagram_label',
            'map_labeling' => 'diagram_label',
            'writing' => 'essay',
            'speaking' => 'essay',
        ];
        
        $updated = 0;
        
        foreach ($mapping as $oldType => $newType) {
            $count = DB::table('ielts_test_questions')
                ->where('question_type', $oldType)
                ->update(['question_type' => $newType]);
            
            if ($count > 0) {
                $this->line("  {$oldType} → {$newType}: {$count} questions");
                $updated += $count;
            }
        }
        
        $this->info("Done! Updated {$updated} questions.");
        
        // Show current distribution
        $this->info("\nCurrent question type distribution:");
        $types = DB::table('ielts_test_questions')
            ->select('question_type', DB::raw('count(*) as count'))
            ->groupBy('question_type')
            ->get();
        
        foreach ($types as $type) {
            $this->line("  {$type->question_type}: {$type->count}");
        }
        
        return 0;
    }
}
